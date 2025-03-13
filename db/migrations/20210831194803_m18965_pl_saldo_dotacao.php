<?php

use Classes\PostgresMigration;

class M18965PlSaldoDotacao extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
drop function if exists fc_saldo_dotacao(integer, integer, date, date);
SQL
        );

        $this->execute(<<<SQL
create or replace function fc_saldo_dotacao(
    f_ano integer,
    f_coddot int,
    f_dataInicio date,
    f_dataFim date
)
    returns table
            (
                reduzido                    integer,
                ano                         integer,
                recurso                     integer,
                complemento                 integer,
                principal                   boolean,
                cp                          char(3),
                saldo_inicial               numeric(17, 2),
                saldo_anterior              numeric(17, 2),
                saldo_disponivel            numeric(17, 2), -- (saldo inicial + sumplementado + suplementado_especial - reducoes - empenhado_liquido_acumulado)
                -- tipos de suplementacao
                suplementado                numeric(17, 2),
                suplementado_especial       numeric(17, 2),
                reducoes                    numeric(17, 2),
                saldo_alteracoes_orcamentarias numeric(17, 2),
                -- execucao no periodo
                empenhado                   numeric(17, 2), --
                empenhado_liquido           numeric(17, 2), -- (empenhado - anulado)
                anulado                     numeric(17, 2), --
                liquidado                   numeric(17, 2), --
                pago                        numeric(17, 2), --

                -- execucao um dia antes do periodo informado
                empenhado_acumulado         numeric(17, 2),
                empenhado_liquido_acumulado numeric(17, 2), -- (empenhado acumulado - anulado acumulado)
                anulado_acumulado           numeric(17, 2),
                liquidado_acumulado         numeric(17, 2),
                pago_acumulado              numeric(17, 2),
                -- saldo do balancete
                a_liquidar                  numeric(17, 2), -- empenhado acumulado - anulado acumulado - liquidado acumulado
                a_pagar                     numeric(17, 2), -- empenhado acumulado - anulado acumulado - pago acumulado
                a_pagar_liquidado           numeric(17, 2)  -- liquidado acumulado - pago acumulado
            )
    language plpgsql
as
$$
declare
    primeiroDiaAno       date;
    rs_dotacao           record;
    rs_execucaoAcumulada record;
    rs_suplementacao     record;
begin

    primeiroDiaAno = f_ano || '-01-01';
    for rs_dotacao in (
        select o58_valor::numeric(17, 2) as saldo_inicial
             , coalesce((select o58_valor::numeric(17, 2)
                from orcdotacao as anterior
                where anterior.o58_coddot = orcdotacao.o58_coddot
                  and anterior.o58_anousu = (orcdotacao.o58_anousu - 1)
               ), 0) as saldo_anterior
             , o15_codigo                as recurso
             , o15_complemento           as complemento
             , o58_concarpeculiar        as cp
             , true                      as principal
        from orcdotacao
             join orctiporec on o15_codigo = o58_codigo
        where o58_coddot = f_coddot
          and o58_anousu = f_ano

        union all

        select distinct
                0::numeric(17, 2)
               ,0
               ,o201_orctiporec
               ,o201_complemento
               ,o58_concarpeculiar
               ,false as principal
        from orcdotacao
             join conlancamdot on c73_coddot = o58_coddot
                  and c73_anousu = o58_anousu
             join conlancam on c73_codlan = c70_codlan
             join conlancamcomplementorecurso on o201_codlan = c70_codlan
        where o58_coddot = f_coddot
          and o58_anousu = f_ano
          and c70_data between primeiroDiaAno and f_dataFim
          and o201_orctiporec != o58_codigo
    )
    loop

        reduzido := f_coddot;
        ano = f_ano;
        recurso = rs_dotacao.recurso;
        complemento = rs_dotacao.complemento;
        saldo_inicial = rs_dotacao.saldo_inicial;
        cp = rs_dotacao.cp;
        saldo_anterior = rs_dotacao.saldo_anterior;
        principal = rs_dotacao.principal;
        -- iniciamos as variaveis com zero
        saldo_disponivel = 0;
        suplementado = 0;
        suplementado_especial = 0;
        reducoes = 0;
        saldo_alteracoes_orcamentarias = 0;
        empenhado = 0;
        empenhado_liquido = 0;
        anulado = 0;
        liquidado = 0;
        pago = 0;
        empenhado_acumulado = 0;
        empenhado_liquido_acumulado = 0;
        anulado_acumulado = 0;
        liquidado_acumulado = 0;
        pago_acumulado = 0;
        a_liquidar = 0;
        a_pagar = 0;
        a_pagar_liquidado = 0;

        -- calcula o periodo acumulado
        select *
          into empenhado_acumulado,
              anulado_acumulado,
              liquidado_acumulado,
              pago_acumulado,
              empenhado_liquido_acumulado
        from fc_execucao_despesa_recurso(f_ano, f_coddot, rs_dotacao.recurso, primeiroDiaAno, f_dataFim);

        -- calcula o periodo
        select *
        into empenhado,
             anulado,
             liquidado,
             pago,
             empenhado_liquido
        from fc_execucao_despesa_recurso(f_ano, f_coddot, rs_dotacao.recurso, f_dataInicio, f_dataFim);

        if rs_dotacao.principal is true then
            -- calcula as suplementacoes da dotacao
            select *
            into rs_suplementacao
            from fc_alteracoes_orcamentarias(f_ano, f_coddot, primeiroDiaAno, f_dataFim);

            suplementado = rs_suplementacao.suplementado;
            suplementado_especial = rs_suplementacao.especial;
            reducoes = rs_suplementacao.reducoes;

            -- retorna a execucao orcamentaria acumulada considerando o valor de todos recursos
            select *
            into rs_execucaoAcumulada
            from fc_execucao_despesa(f_ano, f_coddot, primeiroDiaAno, f_dataFim);

            a_liquidar = rs_execucaoAcumulada.empenhado_liquido - rs_execucaoAcumulada.liquidado;
            a_pagar = rs_execucaoAcumulada.empenhado_liquido - rs_execucaoAcumulada.pago;
            a_pagar_liquidado = rs_execucaoAcumulada.liquidado - rs_execucaoAcumulada.pago;
            saldo_alteracoes_orcamentarias = rs_suplementacao.saldo_alteracoes_orcamentarias;
            saldo_disponivel = saldo_inicial + rs_suplementacao.saldo_alteracoes_orcamentarias;
        end if;

        saldo_disponivel = saldo_disponivel - empenhado_liquido_acumulado;

        return next;
    end loop;
end;
$$

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
drop function if exists fc_saldo_dotacao(integer, integer, date, date);
SQL
        );
    }
}

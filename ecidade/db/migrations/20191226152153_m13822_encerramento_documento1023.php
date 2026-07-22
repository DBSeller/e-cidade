<?php

use Classes\PostgresMigration;


class M13822EncerramentoDocumento1023 extends PostgresMigration
{
    public function down()
    {
        $this->execute(<<<SQL_DOWN

drop function if exists fc_encerramento_1023() cascade;
drop type if exists tp_documento_1023 cascade ;

SQL_DOWN
);
    }

    public function up()
    {

        $this->execute(<<<SQL_UP

drop function if exists fc_encerramento_1023()  cascade;
drop type if exists tp_documento_1023 cascade ;

create type tp_documento_1023 as
(
    conta_debito integer,
    conta_credito numeric,
    valor numeric,
    saldo_conta numeric,
    natureza_conta char
);

create or replace function fc_encerramento_1023() returns SETOF tp_documento_1023
    language plpgsql
as
$$
declare

    linha             tp_documento_1023%ROWTYPE;
    rContasEncerrar   record;
    rConsultaTranscao record;

    anoSessao integer;
    instituicaoSessao integer;

    /* valor da conta inicial na tabela conplanoexe */
    valorInicialCredito numeric;
    valorInicialDebito numeric;
begin

    anoSessao := fc_getsession('DB_anousu')::int;
    instituicaoSessao := fc_getsession('DB_instit')::int;

    for rConsultaTranscao in select c47_debito as conta_debito,
                                    c47_credito as conta_credito
                               from contabilidade.contrans
                                    inner join contabilidade.contranslan on contrans.c45_seqtrans = contranslan.c46_seqtrans
                                    inner join contabilidade.contranslr on contranslan.c46_seqtranslan = contranslr.c47_seqtranslan
                                    inner join conplanoreduz on conplanoreduz.c61_reduz = contranslr.c47_debito
                                                            and conplanoreduz.c61_anousu = contranslr.c47_anousu
                                    inner join conplano on conplano.c60_codcon = conplanoreduz.c61_codcon
                                                       and conplano.c60_anousu = conplanoreduz.c61_anousu
                             where c45_coddoc = 1023
                               and c45_anousu = anoSessao
                               and c45_instit = instituicaoSessao
                               and c46_ordem = 1 order by contranslr.c47_seqtranslr
        loop

        for rContasEncerrar in

            select (select case
                               when c60_naturezasaldo = 1 then 'D'
                               when c60_naturezasaldo = 2 then 'C'
                               end
                    from contabilidade.conplanoreduz
                             inner join contabilidade.conplano on conplanoreduz.c61_codcon = conplano.c60_codcon
                        and conplanoreduz.c61_anousu = conplano.c60_anousu
                    where c61_reduz = rConsultaTranscao.conta_debito
                      and c60_anousu = anoSessao
                   )                                                  as natureza_cadastro_conta,
                   coalesce(sum(case when c69_debito = rConsultaTranscao.conta_debito then c70_valor else 0 end), 0) as valor_debito,
                   coalesce(sum(case when c69_credito = rConsultaTranscao.conta_debito then c70_valor else 0 end), 0) as valor_credito
              from conlancam
                   inner join conlancamval on c69_codlan = c70_codlan
                   inner join conlancamdoc on c71_codlan = c70_codlan
             where c70_data between cast(anoSessao || '-01-01' as date) and cast(anoSessao || '-12-31' as date)
               and (c69_debito = rConsultaTranscao.conta_debito or c69_credito = rConsultaTranscao.conta_debito)
        loop

            /* busca os valores iniciais para as contas debito/credito */
            select c62_vlrcre, c62_vlrdeb
              into valorInicialCredito, valorInicialDebito
              from conplanoexe
             where c62_reduz = rConsultaTranscao.conta_debito
               and c62_anousu = anoSessao;

            valorInicialCredito = (valorInicialCredito + rContasEncerrar.valor_credito);
            valorInicialDebito  = (valorInicialDebito + rContasEncerrar.valor_debito);

            linha.saldo_conta = (valorInicialCredito - valorInicialDebito);
            linha.natureza_conta = 'C';
            linha.conta_debito = rConsultaTranscao.conta_debito;
            linha.conta_credito = rConsultaTranscao.conta_credito;
            linha.valor = round(abs(valorInicialCredito - valorInicialDebito), 2);

            if (linha.saldo_conta < 0) then
                linha.natureza_conta = 'D';
            end if;

            if (linha.natureza_conta <> rContasEncerrar.natureza_cadastro_conta) then
                linha.conta_debito = rConsultaTranscao.conta_credito;
                linha.conta_credito = rConsultaTranscao.conta_debito;
            end if;
            return next linha;

            end loop;
        end loop;
    return;
end;
$$


SQL_UP
);
    }
}

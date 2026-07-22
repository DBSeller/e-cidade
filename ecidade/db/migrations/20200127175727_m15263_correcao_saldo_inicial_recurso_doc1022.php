<?php

use Classes\PostgresMigration;

class M15263CorrecaoSaldoInicialRecursoDoc1022 extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP
drop function if exists fc_encerramento_ddr();
drop type if exists tp_encerramento_ddr;


create type tp_encerramento_ddr as (
                                       compara_tipo_recurso integer,
                                       codigo_recurso integer,
                                       natureza_saldo char,
                                       conta_credito integer,
                                       conta_debito integer,
                                       valor numeric
                                   );

create or replace function fc_encerramento_ddr() returns SETOF tp_encerramento_ddr
    language plpgsql
as
$$
declare

    instituicaoSessao integer;
    anoSessao         integer;
    contaComparacao   integer;
    naturezaSaldoCadastro char;
    rRecursoEncerrar  record;
    linha             tp_encerramento_ddr%ROWTYPE;
    contaDebitoLancamento integer;
    contaCreditoLancamento integer;
    valorCalculado numeric;

begin

    instituicaoSessao := fc_getsession('DB_instit');
    anoSessao := fc_getsession('DB_anousu');

    select c47_debito, (case when c60_naturezasaldo = 1 then 'D' else 'C' end) as natureza
    into contaComparacao, naturezaSaldoCadastro
    from contrans
             join contranslan on c45_seqtrans = c46_seqtrans
             join contranslr on c47_seqtranslan = c46_seqtranslan
             join conplanoreduz on c61_reduz = c47_debito
        and c61_anousu = anoSessao
             join conplano on c60_codcon = c61_codcon
        and c60_anousu = anoSessao
    where c45_coddoc = 1022
      and c45_anousu = anoSessao
      and c45_instit = instituicaoSessao order by 1 limit 1;

    for rRecursoEncerrar in

        select 1 as tipo_recurso,

               (select case
                           when (c53_tipo in (30,31))
                                then orctiporec.o15_codigo
                           when (fc_getsession('DB_utiliza_domicilio_bancario') = 't' and c71_coddoc in (120,3000))
                               then reduzdebito.c61_codigo
                           when (fc_getsession('DB_utiliza_domicilio_bancario') = 't' and c71_coddoc in (121))
                               then reduzcredito.c61_codigo
                           when (fc_getsession('DB_utiliza_domicilio_bancario') = 'f' and c71_coddoc in (120,121,3000))
                               then reduzcredito.c61_codigo
                           else reduzdebito.c61_codigo
                           end as recurso_encontrado
                   from conlancamval
                         inner join conplanoreduz reduzdebito on reduzdebito.c61_reduz = conlancamval.c69_debito
                                                             and reduzdebito.c61_anousu = conlancamval.c69_anousu
                         inner join conplanoreduz reduzcredito on reduzcredito.c61_reduz = conlancamval.c69_credito
                                                              and reduzcredito.c61_anousu = conlancamval.c69_anousu
                  where conlancamval.c69_ordem = 1
                    and conlancamval.c69_codlan = conlancam.c70_codlan
                    and conlancamval.c69_anousu = anoSessao) as codigo_recurso,

                   round( sum(case when conlancamval.c69_debito = contaComparacao then c70_valor else 0 end) +
                      coalesce( ( select c62_vlrdeb
                         from conplanoexe
                        where c62_reduz = contaComparacao
                          and c62_anousu = anoSessao ),0) ,2)
                   as valor_debito,
                   round(sum(case when conlancamval.c69_credito = contaComparacao then c70_valor else 0 end) +
                     coalesce( (select c62_vlrcre
                      from conplanoexe
                      where c62_reduz = contaComparacao
                        and c62_anousu = anoSessao ),0), 2) as valor_credito

        from conlancam
                 inner join conlancamval on conlancamval.c69_codlan = conlancam.c70_codlan
                 inner join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
                 inner join conhistdoc on c53_coddoc = c71_coddoc
                 left join conlancamemp on conlancamemp.c75_codlan = conlancam.c70_codlan
                 left join empempenho   on empempenho.e60_numemp = conlancamemp.c75_numemp
                 left join orcdotacao   on orcdotacao.o58_coddot = empempenho.e60_coddot
                                        and orcdotacao.o58_anousu = empempenho.e60_anousu
                 left join orctiporec on orctiporec.o15_codigo = orcdotacao.o58_codigo
        where conlancam.c70_data between cast(anoSessao || '-01-01' as date) and cast(anoSessao || '-12-31' as date)
          and (conlancamval.c69_credito = contaComparacao or conlancamval.c69_debito = contaComparacao)
          and ((c53_tipo in (30,31) and orctiporec.o15_tipo = 1) or (c53_coddoc in (120,121,3000)))
        group by 1,2

        union

        select o15_tipo as tipo_recurso,
               o15_codigo as codigo_recurso,
               round(sum(case when conlancamval.c69_debito = contaComparacao then c70_valor else 0 end),2) as valor_debito,
               round(sum(case when conlancamval.c69_credito = contaComparacao then c70_valor else 0 end),2) as valor_credito
        from conlancam
                 inner join conlancamval on conlancamval.c69_codlan = conlancam.c70_codlan
                 inner join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
                 inner join conlancamemp on conlancamemp.c75_codlan = conlancam.c70_codlan
                 inner join empempenho   on empempenho.e60_numemp = conlancamemp.c75_numemp
                 inner join orcdotacao   on orcdotacao.o58_coddot = empempenho.e60_coddot
            and orcdotacao.o58_anousu = empempenho.e60_anousu
                 inner join orctiporec on orctiporec.o15_codigo = orcdotacao.o58_codigo
                 inner join conhistdoc on c53_coddoc = c71_coddoc
        where conlancam.c70_data between cast(anoSessao || '-01-01' as date) and cast(anoSessao || '-12-31' as date)
          and (conlancamval.c69_credito = contaComparacao or conlancamval.c69_debito = contaComparacao)
          and c53_tipo in (30, 31)
          and orctiporec.o15_tipo = 2
        group by 1,2

        union

        select 3 as tipo_recurso,
               (select case
                         when (fc_getsession('DB_utiliza_domicilio_bancario') = 't' and c71_coddoc in (161,151))
                           then reduzdebito.c61_codigo
                         when (fc_getsession('DB_utiliza_domicilio_bancario') = 't' and c71_coddoc in (163,153))
                           then reduzcredito.c61_codigo
                         when (fc_getsession('DB_utiliza_domicilio_bancario') = 'f' and c71_coddoc in (161,151))
                           then reduzcredito.c61_codigo
                         else reduzdebito.c61_codigo
                       end as recurso_encontrado
                  from conlancamval
                       inner join conplanoreduz reduzdebito on reduzdebito.c61_reduz = conlancamval.c69_debito
                                                           and reduzdebito.c61_anousu = conlancamval.c69_anousu
                       inner join conplanoreduz reduzcredito on reduzcredito.c61_reduz = conlancamval.c69_credito
                                                            and reduzcredito.c61_anousu = conlancamval.c69_anousu
                where conlancamval.c69_ordem = 1
                  and conlancamval.c69_codlan = conlancam.c70_codlan
                  and conlancamval.c69_anousu = anoSessao) as codigo_recurso,
               round(sum(case when conlancamval.c69_debito = contaComparacao then c70_valor else 0 end),2) as valor_debito,
               round(sum(case when conlancamval.c69_credito = contaComparacao then c70_valor else 0 end),2) as valor_credito
        from conlancam
                 inner join conlancamval on conlancamval.c69_codlan = conlancam.c70_codlan
                 inner join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
                 left join conlancamemp on conlancamemp.c75_codlan = conlancam.c70_codlan
        where conlancam.c70_data between cast(anoSessao || '-01-01' as date) and cast(anoSessao || '-12-31' as date)
          and (conlancamval.c69_credito = contaComparacao or conlancamval.c69_debito = contaComparacao)
          and conlancamemp.c75_codlan is null
          and c71_coddoc in (161, 163, 151, 153)
        group by 1,2

        loop

            select c47_debito, c47_credito
            into contaDebitoLancamento, contaCreditoLancamento
            from contrans
                     join contranslan on c45_seqtrans = c46_seqtrans
                     join contranslr on c47_seqtranslan = c46_seqtranslan
            where c45_coddoc = 1022
              and c45_anousu = anoSessao
              and c45_instit = instituicaoSessao
              and c47_ref = rRecursoEncerrar.tipo_recurso
              and c47_compara = 17;

            valorCalculado = round(rRecursoEncerrar.valor_credito - rRecursoEncerrar.valor_debito, 2);
            linha.valor = abs(valorCalculado);
            linha.natureza_saldo = 'C';
            linha.conta_debito = contaDebitoLancamento;
            linha.conta_credito = contaCreditoLancamento;
            linha.codigo_recurso = rRecursoEncerrar.codigo_recurso;
            linha.compara_tipo_recurso = rRecursoEncerrar.tipo_recurso;

            if (valorCalculado < 0) then
                linha.natureza_saldo = 'D';
            end if;

            if (linha.natureza_saldo <> naturezaSaldoCadastro) then
                linha.conta_debito = contaCreditoLancamento;
                linha.conta_credito = contaDebitoLancamento;
            end if;

            return next linha;

        end loop;

    return;
end ;
$$;





SQL_UP
        );
    }


    public function down()
    {
        $this->execute(<<<SQL_DOWN

drop function if exists fc_encerramento_ddr();
drop type if exists tp_encerramento_ddr;

SQL_DOWN
        );

    }
}

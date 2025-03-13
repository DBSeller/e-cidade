<?php

use Classes\PostgresMigration;

class M13822EncerramentoDoc1022 extends PostgresMigration
{

    public function down()
    {
        $this->execute(<<<SQL_DOWN

drop function if exists fc_encerramento_ddr();
drop type if exists tp_encerramento_ddr;

SQL_DOWN
);
    }

    public function up()
    {
        $this->execute(<<<SQL_UP
drop function if exists fc_encerramento_ddr();
drop type if exists tp_encerramento_ddr;


create type tp_encerramento_ddr as (
    compara_tipo_recurso integer,
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
    rTransacao        record;
    rRecursoEncerrar  record;
    linha             tp_encerramento_ddr%ROWTYPE;
    contaDebitoLancamento integer;
    contaCreditoLancamento integer;

begin

    instituicaoSessao := fc_getsession('DB_instit');
    anoSessao := fc_getsession('DB_anousu');

    select c47_debito
      into contaComparacao
      from contrans
           join contranslan on c45_seqtrans = c46_seqtrans
           join contranslr on c47_seqtranslan = c46_seqtranslan
     where c45_coddoc = 1022
       and c45_anousu = anoSessao
       and c45_instit = instituicaoSessao;

    for rRecursoEncerrar in

        select o15_tipo as tipo_recurso,
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
         where conlancam.c70_data between cast('2019' || '-01-01' as date) and cast('2019' || '-12-31' as date)
           and (conlancamval.c69_credito = contaComparacao or conlancamval.c69_debito = contaComparacao)
           and c53_tipo in (30,31)
           and orctiporec.o15_tipo in (1,2)
         group by 1

        union

        select 3 as tipo_recurso,
               round(sum(case when conlancamval.c69_debito = contaComparacao then c70_valor else 0 end),2) as valor_debito,
               round(sum(case when conlancamval.c69_credito = contaComparacao then c70_valor else 0 end),2) as valor_credito
          from conlancam
               inner join conlancamval on conlancamval.c69_codlan = conlancam.c70_codlan
               inner join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
               left join conlancamemp on conlancamemp.c75_codlan = conlancam.c70_codlan
         where conlancam.c70_data between cast('2019' || '-01-01' as date) and cast('2019' || '-12-31' as date)
           and (conlancamval.c69_credito = contaComparacao or conlancamval.c69_debito = contaComparacao)
           and conlancamemp.c75_codlan is null
           and c71_coddoc in (120, 161, 163, 151, 153, 3000)
         group by 1

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

        linha.valor = abs(round(rRecursoEncerrar.valor_debito - rRecursoEncerrar.valor_credito, 2));
        linha.conta_debito = contaDebitoLancamento;
        linha.conta_credito = contaCreditoLancamento;
        linha.compara_tipo_recurso = rRecursoEncerrar.tipo_recurso;
        return next linha;

    end loop;

    return;
end ;
$$;


SQL_UP
);
    }
}

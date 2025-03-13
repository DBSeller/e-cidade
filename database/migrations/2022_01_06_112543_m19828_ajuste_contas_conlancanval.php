<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19828AjusteContasConlancanval extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
create temp table w_bkp_condataconf as select * from  condataconf;
truncate condataconf;
SQL
        );

        $this->acertaEmpenhos();
        $this->acertoSlips();
        DB::statement("insert into condataconf select * from w_bkp_condataconf;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    private function acertaEmpenhos()
    {
        DB::connection()->getPdo()->exec(<<<SQL
create temp table w_ajustar_contas as
select conlancamval.c69_sequen as id,
       c71_coddoc as documento,
       c69_codlan as lancamento,
       c69_credito as credito_atual,
       c69_debito as debito_atual,
       reduz_credito_certo.c61_reduz as credito_certa,
       reduz_debito_certo.c61_reduz as debito_certa,
       e60_instit as instit_emp,
       reduz_credito_lanc.c61_instit as instit_conta,
       reduz_credito_lanc.c61_codcon
  from conlancamval
  join conlancamdoc on c71_codlan = c69_codlan
  join conlancamemp on c75_codlan = c69_codlan
  join empempenho on e60_numemp = c75_numemp
       and e60_anousu = c69_anousu
  -- credito
  join conplanoreduz as reduz_credito_lanc on reduz_credito_lanc.c61_reduz = c69_credito
       and reduz_credito_lanc.c61_anousu = c69_anousu
  join conplanoreduz as reduz_credito_certo on reduz_credito_certo.c61_codcon = reduz_credito_lanc.c61_codcon
       and reduz_credito_certo.c61_anousu = c69_anousu
       and reduz_credito_certo.c61_instit = e60_instit

  -- debito
  join conplanoreduz as reduz_debito_lanc on reduz_debito_lanc.c61_reduz = c69_debito
       and reduz_debito_lanc.c61_anousu = c69_anousu
  join conplanoreduz as reduz_debito_certo on reduz_debito_certo.c61_codcon = reduz_debito_lanc.c61_codcon
       and reduz_debito_certo.c61_anousu = c69_anousu
       and reduz_debito_certo.c61_instit = e60_instit
 where c69_anousu >= 2021
   and (reduz_credito_lanc.c61_instit != e60_instit or reduz_debito_lanc.c61_instit != e60_instit);

create temp table w_bkp_conlancamval as
select  c69_sequen,
        c69_anousu,
        c69_codlan,
        c69_codhist,
        credito_certa ,
        debito_certa,
        c69_valor,
        c69_data,
        c69_ordem
from conlancamval
join w_ajustar_contas on w_ajustar_contas.id = conlancamval.c69_sequen;

delete from conlancamval
 using w_bkp_conlancamval
 where conlancamval.c69_sequen = w_bkp_conlancamval.c69_sequen;

insert into conlancamval
  select c69_sequen,
         c69_anousu,
         c69_codlan,
         c69_codhist,
         credito_certa ,
         debito_certa,
         c69_valor,
         c69_data,
         c69_ordem
from w_bkp_conlancamval;

update contabilidade.conlancaminstit set c02_instit = instit_emp
   from w_ajustar_contas
  where c02_codlan = lancamento;
SQL
        );
    }

    private function acertoSlips()
    {
        DB::connection()->getPdo()->exec(<<<SQL

create temp table w_ajustar_contas_slip as
select conlancamval.c69_sequen as id,
       c71_coddoc as documento,
       c69_codlan as lancamento,
       c69_credito as credito_atual,
       c69_debito as debito_atual,
       reduz_credito_certo.c61_reduz as credito_certa,
       reduz_debito_certo.c61_reduz as debito_certa,

       reduz_credito_lanc.c61_instit as instit_lanc,
       k17_instit as instit_slip,
       reduz_credito_lanc.c61_codcon,
       conlancamval.c69_ordem,
       k17_codigo as slip
  from conlancamval
  join conlancamdoc on c71_codlan = c69_codlan
  join conlancaminstit on c02_codlan = c69_codlan
  join conlancamslip on c84_conlancam = c69_codlan
  join slip on k17_codigo = c84_slip

  -- credito
  join conplanoreduz as reduz_credito_lanc on reduz_credito_lanc.c61_reduz = c69_credito
       and reduz_credito_lanc.c61_anousu = c69_anousu
  join conplanoreduz as reduz_credito_certo on reduz_credito_certo.c61_codcon = reduz_credito_lanc.c61_codcon
       and reduz_credito_certo.c61_anousu = c69_anousu
       and reduz_credito_certo.c61_instit = k17_instit

  -- debito
  join conplanoreduz as reduz_debito_lanc on reduz_debito_lanc.c61_reduz = c69_debito
       and reduz_debito_lanc.c61_anousu = c69_anousu
  join conplanoreduz as reduz_debito_certo on reduz_debito_certo.c61_codcon = reduz_debito_lanc.c61_codcon
       and reduz_debito_certo.c61_anousu = c69_anousu
       and reduz_debito_certo.c61_instit = k17_instit
where c69_anousu >= 2021
  and (reduz_credito_lanc.c61_instit != k17_instit or reduz_debito_lanc.c61_instit != k17_instit);

create temp table w_bkp_conlancamval_splip as
select  c69_sequen,
        c69_anousu,
        c69_codlan,
        c69_codhist,
        credito_certa ,
        debito_certa,
        c69_valor,
        c69_data,
        conlancamval.c69_ordem
from conlancamval
join w_ajustar_contas_slip on w_ajustar_contas_slip.id = conlancamval.c69_sequen;

delete from conlancamval
 using w_bkp_conlancamval_splip
 where conlancamval.c69_sequen = w_bkp_conlancamval_splip.c69_sequen;


insert into conlancamval
  select c69_sequen,
         c69_anousu,
         c69_codlan,
         c69_codhist,
         credito_certa ,
         debito_certa,
         c69_valor,
         c69_data,
         c69_ordem
from w_bkp_conlancamval_splip;

update contabilidade.conlancaminstit set c02_instit = instit_slip
   from w_ajustar_contas_slip
  where c02_codlan = lancamento;
SQL
        );
    }
}

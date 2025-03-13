<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19655AcertoLancamentoSemRecurso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
create table bkp_lancamento_sem_recurso as
select c75_codlan, o206_recurso, o206_complementorecurso, c75_numemp, c71_coddoc, c70_data
  from conlancamemp
  join empenho.empempenho on e60_numemp = c75_numemp
  join conlancam on conlancam.c70_codlan = conlancamemp.c75_codlan
  join contabilidade.conlancamdoc on c71_codlan = c75_codlan
  join origemcomplementorecurso on o206_numero = c75_numemp and o206_origem = 1
 where c70_anousu = 2021
   and e60_anousu = 2021
   and not exists (select 1 from conlancamcomplementorecurso where o201_codlan = c75_codlan)
   and c71_coddoc not in (2032, 2033, 6000, 6001, 6002, 6003, 6004, 6005, 6006, 6007, 6008, 6009, 6010, 6011, 6012, 6013)
order by c75_numemp, c75_codlan;


insert into conlancamcomplementorecurso
select nextval('conlancamcomplementorecurso_o201_sequencial_seq'), c75_codlan, o206_complementorecurso, o206_recurso
 from bkp_lancamento_sem_recurso;
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from conlancamcomplementorecurso
using bkp_lancamento_sem_recurso where o201_codlan = c75_codlan;

drop table bkp_lancamento_sem_recurso;
SQL
        );
    }
}

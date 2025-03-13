<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20170AcertoComplementoRecurso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $sql = <<<SQL

   create temp table w_correto as
   select *
     from conlancamemp
   inner join conlancamdoc on c71_codlan = c75_codlan
   inner join conlancamcomplementorecurso on c75_codlan = o201_codlan
   where c71_coddoc = 1
   and extract(year from c71_data) :: int in ( 2021, 2022);
   update conlancamcomplementorecurso
      set o201_complemento = empcompl.o201_complemento
   from (
       select conlancamcomplementorecurso.o201_sequencial as sequencial, w_correto.o201_complemento
        from conlancamcomplementorecurso
        inner join conlancamemp on o201_codlan = c75_codlan
        inner join w_correto on  w_correto.c75_numemp = conlancamemp.c75_numemp
   ) as empcompl where empcompl.sequencial = o201_sequencial;


SQL;


        DB::connection()->getPdo()->exec($sql);
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
}

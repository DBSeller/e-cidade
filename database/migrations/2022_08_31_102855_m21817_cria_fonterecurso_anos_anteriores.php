<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21817CriaFonterecursoAnosAnteriores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

create table w_anosM21817 as
select distinct c60_anousu as ano
  from conplano
  where c60_anousu >= 2018
   and c60_anousu < (select min(exercicio)  from fonterecurso )
  order by c60_anousu;


insert into fonterecurso
select nextval('fonterecurso_id_seq'),
       orctiporec_id,
       generate_series( (select min(ano) from w_anosM21817), (select max(ano) from w_anosM21817) ) ,
       codigo_siconfi,
       gestao,
       classificacaofr_id,
       tipo_detalhamento,
       descricao
  from fonterecurso
 where exercicio = 2022;


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
        $sql = <<<SQL

delete from fonterecurso where exercicio in (select ano from w_anosM21817);
drop table w_anosM21817;

SQL;

                DB::connection()->getPdo()->exec($sql);
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21957FonteRecurso2023 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into orcamento.fonterecurso
select nextval('fonterecurso_id_seq'),
       orctiporec_id,
       2023,
       fonterecurso.codigo_siconfi,
       fonterecurso.codigo_siconfi,
       fonterecurso.classificacaofr_id,
       tipo_detalhamento,
       fonterecurso.descricao
from orcamento.fonterecurso
join orcamento.fontesiconfi on fontesiconfi.codigo_siconfi = fonterecurso.codigo_siconfi
where fonterecurso.exercicio = 2022
  and not exists(
       select 1 from orcamento.fonterecurso next
        where next.exercicio = 2023
        and next.orctiporec_id = fonterecurso.orctiporec_id
      ) ;
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
        DB::connection()->getPdo()->exec("delete from orcamento.fonterecurso where exercicio = 2023");
    }
}

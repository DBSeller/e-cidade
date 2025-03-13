<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19346AcertoOrgaoUnidadeReceitas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<SQL
update orcamento.orcreceita
   set o70_orcorgao = orgao, o70_orcunidade = unidade
from ( select codigo, substr(codtrib, 1,2)::int as orgao, substr(codtrib, 3,2)::int as unidade  from db_config) as x
where o70_instit = codigo
and o70_orcorgao is null
and o70_orcunidade is null
and o70_anousu >= 2021;
SQL
        );

        DB::statement(<<<SQL
update orcamento.orcreceita
   set o70_esferaorcamentaria = 10
  from (select codigo, case when db21_tipoinstit in (5,6) then 20 else 10 end as esfera from db_config) as x
 where o70_instit = codigo
   and (o70_esferaorcamentaria is null or o70_esferaorcamentaria = 0)
   and o70_anousu >= 2021
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

    }
}

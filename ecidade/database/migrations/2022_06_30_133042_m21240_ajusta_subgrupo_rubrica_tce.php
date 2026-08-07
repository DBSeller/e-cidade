<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21240AjustaSubgrupoRubricaTce extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        update esocial.esocialrubricas set eso26_subgrupotce = '01' where eso26_rubrica in ('1212', '1003', '1050', '1206', '1401', '2501', '9299', '1213', '1202');
        update esocial.esocialrubricas set eso26_subgrupotce = '02' where eso26_natureza in ('1212');
        update esocial.esocialrubricas set eso26_subgrupotce = '03' where eso26_natureza in ('1003', '1050', '1206', '1401');
        update esocial.esocialrubricas set eso26_subgrupotce = '04' where eso26_natureza in ('2501', '9299');
        update esocial.esocialrubricas set eso26_subgrupotce = '06' where eso26_natureza in ('1213');
        update esocial.esocialrubricas set eso26_subgrupotce = '12' where eso26_natureza in ('1202');
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
        update esocial.esocialrubricas set eso26_subgrupotce = '01' where eso26_natureza in ('1212', '1003', '1050', '1206', '1401', '2501', '9299', '1213', '1202');
        update esocial.esocialrubricas set eso26_subgrupotce = '02' where eso26_rubrica in ('1212');
        update esocial.esocialrubricas set eso26_subgrupotce = '03' where eso26_rubrica in ('1003', '1050', '1206', '1401');
        update esocial.esocialrubricas set eso26_subgrupotce = '04' where eso26_rubrica in ('2501', '9299');
        update esocial.esocialrubricas set eso26_subgrupotce = '06' where eso26_rubrica in ('1213');
        update esocial.esocialrubricas set eso26_subgrupotce = '12' where eso26_rubrica in ('1202');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class M19967AtualizaCensoTransporteAquaviario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update censotipotransporte set ed312_descricao = 'AQUAVIARIO/EMBARCACAO - ATÉ 5 PESSOAS' where ed312_sequencial = 7;
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
        update censotipotransporte set ed312_descricao = 'AQUAVIARIO/EMBARCACAO - ATÃ	 5 PESSOAS' where ed312_sequencial = 7;
SQL
    );
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21520AlteracaoDoTipoPregoeiroESuplenteConformeLicitaconTce extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update liccomissaocgm set l31_tipo = 'G' where l31_tipo = '2';
update liccomissaocgm set l31_tipo = 'M' where l31_tipo = '3';

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
update liccomissaocgm set l31_tipo = '2' where l31_tipo = 'G';
update liccomissaocgm set l31_tipo = '3' where l31_tipo = 'M';

SQL
        );
    }
}

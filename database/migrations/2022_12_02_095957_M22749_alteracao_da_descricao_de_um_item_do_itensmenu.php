<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22749AlteracaoDaDescricaoDeUmItemDoItensmenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update db_itensmenu set descricao = 'Aditamentos / Apostilamentos', desctec = 'Aditamentos / Apostilamentos' where id_item = 8568;
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
update db_itensmenu set descricao = 'Aditamentos', desctec = '' where id_item = 8568;
SQL
);
    }
}

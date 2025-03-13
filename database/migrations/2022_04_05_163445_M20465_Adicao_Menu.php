<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20465AdicaoMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<SQL
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9589 ,7887 ,4 ,7159 );
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
        DB::statement(<<<SQL
delete from db_menu where id_item_filho = 7887 AND modulo = 7159;
SQL
        );
    }
}

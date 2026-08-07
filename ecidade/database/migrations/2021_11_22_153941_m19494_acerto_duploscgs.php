<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19494AcertoDuploscgs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("insert into db_sysindices values(1008696,'cgs_und_ext_i_cgsund_in',3839,'1');");
        DB::statement("insert into db_syscadind values(1008696,1008844,1);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('delete from db_syscadind where codind = 1008696;');
        DB::statement('delete from db_sysindices where codind = 1008696;');
    }
}

<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19641CorrecaoCgsUndalt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("update db_syscampo set conteudo = 'varchar(60)', tamanho = 60 where codcam = 1008970;");
        DB::statement("update db_syscampo set conteudo = 'varchar(40)', tamanho = 40 where codcam = 1008969;");
        DB::statement("update db_syscampo set conteudo = 'varchar(60)', tamanho = 60 where codcam = 1008967;");
        DB::statement(<<<SQL
            ALTER TABLE ambulatorial.cgs_undalt 
                ALTER COLUMN z33_v_ender TYPE VARCHAR(60),
                ALTER COLUMN z33_v_compl TYPE VARCHAR(40),
                ALTER COLUMN z33_v_bairro TYPE VARCHAR(60);
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
        return;
    }
}

<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22977RelatorioUsersv2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                             values ( 228825 ,'Relação de usuários versão 2' ,'Relação de usuários versão 2' ,
                                      'con2_relatoriov2users_001.php' ,'1' ,'1' ,'Relação de usuários que usam a versão 2 do e-cidade' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,228825 ,850 ,1 );
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

            delete from db_menu where id_item_filho = 228825 AND modulo = 1;
            delete from db_itensmenu where id_item = 228825;
SQL
        );
    }
}

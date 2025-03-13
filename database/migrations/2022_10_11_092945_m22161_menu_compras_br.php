<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22161MenuComprasBr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228782 ,'Integração pregão Compras BR' ,'Integração pregão Compras BR' ,'lic4_integracaocomprasbr.php' ,'1' ,'1' ,'Integração Pregão Eletrônico e-Cidade x Compras BR' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1818 ,228782 ,142 ,381 );
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
            delete from db_menu where id_item_filho = 228782 AND modulo = 381;
            delete from db_itensmenu where id_item = 228782;
SQL
        );
    }
}

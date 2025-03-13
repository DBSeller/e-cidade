<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21315CriandoNovoMenuConciliacaoBancaria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228695 ,'Controle de Conciliação Bancária' ,'Controle de Conciliação Bancária' ,'cai2_controleconciliacaobancaria001.php' ,'1' ,'1' ,'Controle de Conciliação Bancária' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3951 ,228695 ,15 ,39 );
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
            delete from db_menu where id_item_filho = 228695;
            delete from db_itensmenu where id_item = 228695;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

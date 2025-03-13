<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21772InclusaoItensMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228748 ,'Importar dados do arquivo' ,'Importar dados do arquivo' ,'pes4_importaarquivoponto001.php' ,'1' ,'1' ,'Importar dados do arquivo' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4504 ,228748 ,9 ,952 );
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
        //
        $sql = <<<SQL
        delete from db_menu where id_item_filho = 228748;
        delete from db_itensmenu where id_item = 228748;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

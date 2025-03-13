<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20087Menus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sSql = <<<SQL
insert into db_itensmenu (id_item,descricao,help,funcao,itemativo,manutencao,desctec,libcliente) 
                  values (228630,
                          'Menutenção Local de Trabalho do Servidor' ,
                          'Menutenção Local de Trabalho do Servidor' ,
                          'pes1_manutencaoRhPesLocalTrab001.php' ,
                          '1' ,
                          '1' ,
                          'Menutenção Local de Trabalho do Servidor' ,
                          'true');
                          
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4354 ,228630 ,10 ,952 );
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sSql = <<<SQL
delete from db_menu where id_item_filho = 228630 AND modulo = 952;
delete from db_itensmenu where id_item = 228630;
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }
}

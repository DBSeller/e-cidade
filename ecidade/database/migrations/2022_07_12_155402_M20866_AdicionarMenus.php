<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M20866AdicionarMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(
            <<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228696 ,' Processamento das Concessões' ,' Processa todas datas de concessões' ,'rec3_consvantagem001.php' ,'1' ,'1' ,' Processa todas datas de concessões' ,'true' );
delete from db_menu where id_item_filho = 228696 AND modulo = 2323;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10250 ,228696 ,5 ,2323 );
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228697 ,'Tipos de Concessões' ,'Parâmetros para o calculo das concessões' ,'rec4_concessaoconfig001.php' ,'1' ,'1' ,'Parâmetros para o calculo das concessões' ,'true' );
delete from db_menu where id_item_filho = 228697 AND modulo = 2323;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10250 ,228697 ,6 ,2323 );
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228698 ,'Consulta Concessões' ,'Consulta Concessões do Servidor' ,'rec3_concultarconsvantagem001.php' ,'1' ,'1' ,'Consulta Concessões já calculadas do Servidor' ,'true' );
delete from db_menu where id_item_filho = 228698 AND modulo = 2323;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10250 ,228698 ,7 ,2323 );
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228699 ,'Autoriza Concessões' ,'Gera Concessão por Portaria' ,'rec3_gerarconcport001.php' ,'1' ,'1' ,'Gera Concessão por Portaria' ,'true' );
delete from db_menu where id_item_filho = 228699 AND modulo = 2323;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10250 ,228699 ,8 ,2323 );
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228700 ,'Relatório de Concessão' ,'Relatório de Concessão' ,'rec3_relatorioconcessao001.php' ,'1' ,'1' ,'Relatório de Concessão' ,'true' );
delete from db_menu where id_item_filho = 228700 AND modulo = 2323;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10250 ,228700 ,9 ,2323 );
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
        DB::connection()->getPdo()->exec(
            <<<SQL
delete from db_menu where id_item_filho = 228696 AND modulo = 2323;
delete from db_itensmenu where id_item = 228696;
delete from db_menu where id_item_filho = 228697 AND modulo = 2323;
delete from db_itensmenu where id_item = 228697;
delete from db_menu where id_item_filho = 228698 AND modulo = 2323;
delete from db_itensmenu where id_item = 228698;
delete from db_menu where id_item_filho = 228699 AND modulo = 2323;
delete from db_itensmenu where id_item = 228699;
delete from db_menu where id_item_filho = 228700 AND modulo = 2323;
delete from db_itensmenu where id_item = 228700;
SQL
);
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22263MenuConfiguracoesGeraisEfdreinf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228783 ,'Configurações Gerais' ,'Configurações Gerais' ,'efd4_efdreinfconfiguracao001.php' ,'1' ,'1' ,'Configurações Gerais do EFD-REINF' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228083 ,228783 ,3 ,228077 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228797 ,'Unidade Responsável' ,'Cadastro de Unidade Responsável' ,'efd4_unidaderesponsavel001.php' ,'1' ,'1' ,'Lista de contribuintes quando filtro de órgão unidade está ativo.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228083 ,228797 ,4 ,228077 );
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
            delete from db_menu where id_item_filho = 228783 AND modulo = 228077;
            delete from db_itensmenu where id_item = 228783;
            delete from db_menu where id_item_filho = 228797 AND modulo = 228077;
            delete from db_itensmenu where id_item = 228797;
SQL
        );
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20567AjusteItensDeMenuProcesso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

        delete from db_menu where id_item_filho = 1699 AND modulo = 604;
        delete from db_menu where id_item_filho = 1698 AND modulo = 604;
        delete from db_menu where id_item_filho = 1700 AND modulo = 604;

        update db_itensmenu set id_item = 1698 , descricao = 'Inclusão' , help = 'Inclusão' , funcao = 'pro1_tipoproc001.php.old' , itemativo = '1' , manutencao = '1' , desctec = 'Inclusão' , libcliente = 'false' where id_item = 1698;
        update db_itensmenu set id_item = 1699 , descricao = 'Alteração' , help = 'Alteração' , funcao = 'pro1_tipoproc002.php.old' , itemativo = '1' , manutencao = '1' , desctec = 'Cadastro >> Tipo Processo >> Alteração' , libcliente = 'false' where id_item = 1699;
        update db_itensmenu set id_item = 1700 , descricao = 'Exclusão' , help = 'Exclusão' , funcao = 'pro1_tipoproc003.php.old' , itemativo = '1' , manutencao = '1' , desctec = 'Cadastro >> Tipo Processo >> Exclusão' , libcliente = 'false' where id_item = 1700;
        update db_itensmenu set id_item = 7851 , descricao = 'Inclusão' , help = 'https://e-cidade.wiki.br/patrimonial/protocolo/#!cadastros_tipo_de_processo.md' , funcao = 'ouv1_tipoproc001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Ouvidoria >> Cadastro >> Tipo de Processo >> Inclusão' , libcliente = 'true' where id_item = 7851;
        update db_itensmenu set id_item = 7852 , descricao = 'Alteração' , help = 'https://e-cidade.wiki.br/patrimonial/protocolo/#!cadastros_tipo_de_processo.md' , funcao = 'ouv1_tipoproc002.php' , itemativo = '1' , manutencao = '1' , desctec = 'Ouvidoria >> Cadastro >> Tipo de Processo >> Alteração' , libcliente = 'true' where id_item = 7852;
        update db_itensmenu set id_item = 7853 , descricao = 'Exclusão' , help = 'https://e-cidade.wiki.br/patrimonial/protocolo/#!cadastros_tipo_de_processo.md' , funcao = 'ouv1_tipoproc003.php' , itemativo = '1' , manutencao = '1' , desctec = 'Ouvidoria >> Cadastro >> Tipo de Processo >> Exclusão' , libcliente = 'true' where id_item = 7853;

        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1697 ,7851 ,7 ,604 );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1697 ,7852 ,8 ,604 );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1697 ,7853 ,9 ,604 );
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

        delete from db_menu where id_item_filho = 7851 AND modulo = 604;
        delete from db_menu where id_item_filho = 7852 AND modulo = 604;
        delete from db_menu where id_item_filho = 7853 AND modulo = 604;

        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1697 ,1698 ,7 ,604 );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1697 ,1699 ,8 ,604 );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1697 ,1700 ,9 ,604 );

        update db_itensmenu set id_item = 1698 , descricao = 'Inclusão' , help = 'Inclusão' , funcao = 'pro1_tipoproc001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Inclusão' , libcliente = 'true' where id_item = 1698;
        update db_itensmenu set id_item = 1699 , descricao = 'Alteração' , help = 'Alteração' , funcao = 'pro1_tipoproc002.php' , itemativo = '1' , manutencao = '1' , desctec = 'Cadastro >> Tipo Processo >> Alteração' , libcliente = 'true' where id_item = 1699;
        update db_itensmenu set id_item = 1700 , descricao = 'Exclusão' , help = 'Exclusão' , funcao = 'pro1_tipoproc003.php' , itemativo = '1' , manutencao = '1' , desctec = 'Cadastro >> Tipo Processo >> Exclusão' , libcliente = 'true' where id_item = 1700;


        update db_itensmenu set id_item = 7851 , descricao = 'Inclusão' , help = 'Inclusão' , funcao = 'ouv1_tipoproc001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Ouvidoria >> Cadastro >> Tipo de Processo >> Inclusão' , libcliente = 'true' where id_item = 7851;
        update db_itensmenu set id_item = 7852 , descricao = 'Alteração' , help = 'Alteração' , funcao = 'ouv1_tipoproc002.php' , itemativo = '1' , manutencao = '1' , desctec = 'Ouvidoria >> Cadastro >> Tipo de Processo >> Alteração' , libcliente = 'true' where id_item = 7852;
        update db_itensmenu set id_item = 7853 , descricao = 'Exclusão' , help = 'Exclusão' , funcao = 'ouv1_tipoproc003.php' , itemativo = '1' , manutencao = '1' , desctec = 'Ouvidoria >> Cadastro >> Tipo de Processo >> Exclusão' , libcliente = 'true' where id_item = 7853;
SQL
        );
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21905EstruturaMenuProgramaDeTrabalho extends Migration
{
    public function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228773 ,'Programa de Trabalho' ,'Programa de Trabalho' ,'orc2_programatrabalho001.php' ,'1' ,'1' ,'Programa de Trabalho' ,'true' );
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228774 ,'Proposta Orçamentária' ,'Proposta Orçamentária' ,'' ,'1' ,'1' ,'Proposta Orçamentária' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4150 ,228774 ,12 ,116 );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228774 ,228773 ,1 ,116 );
SQL
        );
            
        
    }

    public function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_menu where id_item_filho = 228773;
delete from db_itensmenu where id_item = 228773;
delete from db_menu where id_item_filho = 228774;
delete from db_itensmenu where id_item = 228774;
SQL
        );
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
    }
}

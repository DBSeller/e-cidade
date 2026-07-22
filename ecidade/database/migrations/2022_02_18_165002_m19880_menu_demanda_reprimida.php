<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M19880MenuDemandaReprimida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228631 ,'Demanda Reprimida' ,'Cadastro de Demanda Reprimida' ,'far1_demanda_reprimida.php' ,'1' ,'1' ,'Cadastro de demanda dos medicamentos no qual a farmácia não possui estoque (demanda reprimida).' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3470 ,228631 ,45 ,6877 );
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
            delete from db_menu where id_item_filho = 228631;
            delete from db_itensmenu where id_item = 228631;
SQL
        );
    }
}

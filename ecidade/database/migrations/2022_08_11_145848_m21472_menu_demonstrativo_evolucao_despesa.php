<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21472MenuDemonstrativoEvolucaoDespesa extends Migration
{
    public function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228742 ,'Demonstrativo da Evolução da Despesa' ,'Demonstrativo da Evolução da Despesa' ,'con2_demonstrativoevolucaodespesa_001.php' ,'1' ,'1' ,'Demonstrativo da Evolução da Despesa' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3331 ,228742 ,58 ,209 );
SQL
        );
    }

    public function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_menu where id_item_filho = 228742;
delete from db_itensmenu where id_item = 228742;
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

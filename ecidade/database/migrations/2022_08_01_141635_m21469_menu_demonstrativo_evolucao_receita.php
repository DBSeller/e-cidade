<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21469MenuDemonstrativoEvolucaoReceita extends Migration
{
    public function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228738 ,'Demonstrativo da Evolução da Receita' ,'Demonstrativo da Evolução da Receita' ,'con2_demonstrativoevolucaoreceita_001.php' ,'1' ,'1' ,'Demonstrativo da Evolução da Receita' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3331 ,228738 ,57 ,209 );
SQL
        );
    }

    public function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_menu where id_item_filho = 228738;
delete from db_itensmenu where id_item = 228738;
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

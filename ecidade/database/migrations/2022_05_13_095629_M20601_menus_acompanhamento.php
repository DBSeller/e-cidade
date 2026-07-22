<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20601MenusAcompanhamento extends Migration
{
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
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_menu where id_item_filho in (228662, 228663, 228664);
delete from db_itensmenu where id_item in (228662, 228663, 228664);
SQL
        );
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values ( 228662 ,'Avaliação de Metas' ,'Avaliação de Metas' ,'' ,'1' ,'1' ,'Avaliação de Metas' ,'true' ),
       ( 228663 ,'Despesa' ,'Despesa' ,'orc4_acompanhamentodespesa001.php' ,'1' ,'1' ,'Acompanhamento do cronograma de desembolso da despesa' ,'true' ),
       ( 228664 ,'Receita' ,'Receita' ,'orc4_acompanhamentoreceita001.php' ,'1' ,'1' ,'Acompanhamento do cronograma da receita' ,'true' );

insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
values ( 32 ,228662 ,551 ,116 ),
       ( 228662 ,228663 ,1 ,116 ),
       ( 228662 ,228664 ,2 ,116 );
SQL
        );
    }
}

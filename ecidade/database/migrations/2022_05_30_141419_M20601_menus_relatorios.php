<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20601MenusRelatorios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
values ( 228670 ,'Acompanhamento do Cronograma' ,'Acompanhamento do Cronograma' ,'' ,'1' ,'1' ,'Acompanhamento do Cronograma' ,'true' ),
       ( 228671 ,'Metas de Arrecadação' ,'Metas de Arrecadação' ,'orc2_metas_arrecadacao001.php' ,'1' ,'1' ,'Metas de Arrecadação' ,'true' ),
       ( 228672 ,'Cotas Mensais da Despesa' ,'Cotas Mensais da Despesa' ,'orc2_cotas_despesa001.php' ,'1' ,'1' ,'Cotas Mensais da Despesa' ,'true' ),
       ( 228673 ,'Metas de Receita X Cotas de Despesa' ,'Metas de Receita X Cotas de Despesa' ,'orc2_receita_despesa001.php' ,'1' ,'1' ,'Metas de Receita X Cotas de Despesa' ,'true' );

insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
values ( 30 ,228670 ,847 ,116 ),
       ( 228670 ,228671 ,1 ,116 ),
       ( 228670 ,228672 ,2 ,116 ),
       ( 228670 ,228673 ,3 ,116 );
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
delete from db_menu where modulo = 116 and id_item_filho in (228670, 228671, 228672, 228673);
delete from db_itensmenu where id_item in (228670, 228671, 228672, 228673);
SQL
        );
    }
}

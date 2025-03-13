<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22205AlteracaoMenuPedidosEncerrados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("update db_itensmenu set id_item = 9813 , descricao = 'Pedidos Encerrados' , help = 'Relatуrio de pedidos encerrados' , funcao = 'tfd2_porpedido001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Relatуrio de pedidos encerrados' , libcliente = 'true' where id_item = 9813;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("update db_itensmenu set id_item = 9813 , descricao = 'Por Pedido' , help = 'Por Pedido' , funcao = 'tfd2_porpedido001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Por Pedido' , libcliente = 'true' where id_item = 9813;");
    }

    /* alteraзгo para entrega continua */
}

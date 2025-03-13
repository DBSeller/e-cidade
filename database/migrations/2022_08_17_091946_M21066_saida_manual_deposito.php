<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class M21066SaidaManualDeposito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                 values (228744,
                         'Saída manual por depósito',
                         'Saída manual por depósito',
                         'mat1_matestoquesaidep001.php',
                         '1',
                         '1',
                         'Saída manual de itens por depósito', 'true');

insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (32, 228744, 561, 480);
sql
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
delete from db_menu where id_item_filho = 228744 AND modulo = 480;
delete from db_itensmenu where id_item = 228744;
sql
        );
    }
}

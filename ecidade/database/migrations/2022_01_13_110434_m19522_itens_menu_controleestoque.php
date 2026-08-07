<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19522ItensMenuControleestoque extends Migration
{
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
            update db_itensmenu set descricao = 'Controle de Estoque (Desativado)',
                                    help = 'Controle de Estoque (Desativado)',
                                    desctec = 'Controle de Estoque - Desativado',
                                    libcliente = 'false'
                where id_item = 4805;

            insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente )
                values (228615,
                        'Controle de Estoque (Movimentações)',
                        'Controle de Estoque (Movimentações)',
                        'mat2_controleestoque.php',
                        '1',
                        '1',
                        'Relatório Controle de Estoque com movimentações do item',
                        'true');

            insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (8787, 228615, 21, 480);
sql
        );
    }

    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
            update db_itensmenu set descricao = 'Controle de Estoque',
                                    help = 'Controle de Estoque',
                                    desctec = 'Controle de Estoque',
                                    libcliente = 'false'
                where id_item = 4805;
            delete from db_menu where id_item_filho = 228615 AND modulo = 480;
            delete from db_itensmenu where id_item = 228615;
sql
        );
    }
}

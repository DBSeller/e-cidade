<?php

use Illuminate\Database\Migrations\Migration;

class M20028ItemMenuResumoContabil extends Migration
{
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
            insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (
                        228623,
                        'Resumo contábil de estoque',
                        'Resumo contábil de estoque',
                        'mat2_resumocontabilestoque.php',
                        '1',
                        '1',
                        'Resumo contábil de estoque',
                        'true'
                        );
            insert into db_menu (id_item, id_item_filho, menusequencia, modulo)
                values (8787, 228623, 22, 480);
sql
        );
    }

    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
            delete from db_menu where id_item_filho = 228623;
            delete from db_itensmenu where id_item = 228623;
sql
        );
    }
}

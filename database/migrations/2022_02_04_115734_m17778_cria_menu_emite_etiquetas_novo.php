<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M17778CriaMenuEmiteEtiquetasNovo extends Migration
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
            values (228622, 'Emite Etiquetas (novo)', 'Emite Etiquetas (novo)', 'pat4_emiteetiquetas.php', '1', '1',
        'Rotina para emissão de etiquetas patrimoniais.', 'false');
            insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
            values (32, 228622, 549, 439);

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
        delete from db_menu where id_item_filho = 228622 AND modulo = 439;
        delete from db_itensmenu where id_item = 228622;
SQL
        );
    }


}

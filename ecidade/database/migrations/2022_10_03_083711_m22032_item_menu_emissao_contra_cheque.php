<?php

use Illuminate\Database\Migrations\Migration;

class M22032ItemMenuEmissaoContraCheque extends Migration
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
    values (228772,
            'Emissão Contra Cheques App',
            '#',
            'pes4_parametroscontracheques.php',
            '1',
            '1',
            'Rotina para parametrizar disponibilidade dos contra cheques no aplicativo',
            'true');
insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (5110, 228772, 9, 952);
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
delete from db_menu where id_item_filho = 228772;
delete from db_itensmenu where id_item = 228772;
SQL
        );
    }
}

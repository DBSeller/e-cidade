<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20733InclusaoDeAlteracaoOuCessaoContratado extends Migration
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
values (
        228758,
        'Alteração ou Cessão de Contratado',
        'https://e-cidade.wiki.br/patrimonial/contratos/#!tutorial_alteracao_cessao_contratado.md',
        'con4_alteracessacontratado001.php',
        '1',
        '1',
        'Faz a alteração ou cessão do contratado.',
        'true'
        );
        insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (8568, 228758, 8, 8251);
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
        delete from db_menu where id_item_filho = 228758 AND modulo = 8251;

        delete from db_itensmenu where id_item = 228758;

SQL
        );
    }
}

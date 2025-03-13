<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ItemMenuRelatorioRecadastramento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        insert into configuracoes.db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
        values (228604, 'Recadastramento', 'Recadastramento', 'rh2_recadastramento001.php', '1', '1', 'Agrupa os relatórios do Cronograma de Desembolso', 'true');

        insert into configuracoes.db_menu(id_item, id_item_filho, menusequencia, modulo)
        values (30 ,228604,841,2323);
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
        DELETE FROM  configuracoes.db_itensmenu where id_item = 228604;
        DELETE FROM configuracoes.db_menu WHERE id_item_filho = 228604;
SQL
        );
    }
}

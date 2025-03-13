<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19350RelatorioMetaArrecadacao extends Migration
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
values (228585, 'Cronograma de Desembolso', 'Cronograma de Desembolso', '', '1', '1', 'Agrupa os relatórios do Cronograma de Desembolso', 'true'),
       (228586, 'Metas de Arrecadação', 'Metas de Arrecadação', 'pla2_metas_arrecadacao.php', '1', '1', 'Metas de Arrecadação','true');

insert into configuracoes.db_menu(id_item, id_item_filho, menusequencia, modulo)
values (228363, 228585, 4, 228358),
       (228585, 228586, 1, 228358);
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
        DB::table('configuracoes.db_menu')->whereIn('id_item_filho', [228585, 228586])
            ->where('modulo', '=', 228358)
            ->delete();

        DB::table('configuracoes.db_itensmenu')->whereIn('id_item', [228585, 228586])
            ->delete();
    }
}

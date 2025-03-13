<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M193512MenuRelatorioMetaXCotas extends Migration
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
values (228591, 'Metas de Receita X Cotas de Despesa', 'Metas de Receita X Cotas de Despesa', 'pla2_metas_x_cotas.php', '1', '1', 'Metas de Receita X Cotas de Despesa', 'true');
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values (228585, 228591, 3, 228358);
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
delete from db_menu where id_item_filho = 228591 AND modulo = 228358;
delete from db_itensmenu where id_item = 228591;
SQL
        );
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19351MenuRelatorioCotasDespesa extends Migration
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
values (228588, 'Cotas Mensais da Despesa', 'Cotas Mensais da Despesa', 'pla2_cotas_mensais.php', '1', '1', 'Cotas Mensais da Despesa', 'true');

insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (228585, 228588, 2, 228358);
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
delete from db_menu where id_item_filho = 228588 AND modulo = 228358;
delete from db_itensmenu where id_item = 228588;
SQL
        );
    }
}

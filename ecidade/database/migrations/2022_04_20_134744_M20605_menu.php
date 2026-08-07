<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20605Menu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu (id_item,descricao,help,funcao,itemativo,manutencao,desctec,libcliente)
values (228646, 'Atributos Plano Contas MSC', 'Atributos Plano Contas MSC', 'con2_atributoscontasmsc001.php', '1', '1', 'Atributos Plano Contas MSC', 'true');
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4189 ,228646 ,16 ,209 );
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
delete from db_menu where id_item_filho = 228646 AND modulo = 209;
delete from db_itensmenu where id_item = 228646;
SQL
        );
    }
}

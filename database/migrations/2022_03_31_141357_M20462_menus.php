<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20462Menus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
values (228643, 'Anexo V - Demonstrativo Da Disponibilidade De Caixa E Dos Restos A Pagar', 'Anexo V', 'pla2_abas_rgf.php?anexo=5', '1', '1', 'Anexo V - Demonstrativo Da Disponibilidade De Caixa E Dos Restos A Pagar', 'true');

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228640 ,228643 ,3 ,209 );
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
delete from db_menu where id_item_filho = 228643 AND modulo = 209;
delete from db_itensmenu where id_item = 228643;
SQL
        );
    }
}

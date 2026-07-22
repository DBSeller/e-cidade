<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M16456 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu(
    id_item ,
    descricao,
    help,
    funcao,
    itemativo,
    manutencao,
    desctec,
    libcliente)
values (
    228589 ,
    'Excluir Aditamento',
    'Excluir Aditamento',
    'aco4_excluiraditamento001.php',
    '1',
    '1',
    'Excluir Aditamento',
    'true');

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228589 ,546 ,8251 );
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
delete from db_itensmenu where id_item = 228589;
delete from db_menu where id_item_filho = 228589 AND modulo = 8251;
SQL
);
    }
}

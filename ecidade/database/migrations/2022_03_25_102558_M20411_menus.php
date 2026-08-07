<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20411Menus extends Migration
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
values (228639, 'Anteriores a 11ª ed', 'Anteriores a 11ª ed', '', '1', '1', 'Anteriores a 11ª ed', 'true'),
       (228640, 'A partir da 11ª ed', 'A partir da 11ª ed', '', '1', '1', 'A partir da 11ª ed', 'true'),
       (228641, 'Anexo II - Dem. da Dívida Cons. Líquida', 'Anexo II - Dem. da Dívida Cons. Líquida' ,'pla2_abas_rgf.php?anexo=2' ,'1' ,'1' ,'Anexo II - Dem. da Dívida Cons. Líquida' ,'true');

update db_itensmenu set descricao = 'Anexo I - Demons. da Despesa com Pessoal', help = 'Anexo I - Demons. da Despesa com Pessoal', desctec = 'Anexo I - Demonstrativo da Despesa com Pessoal' where id_item = 228602;

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo)
values (8113, 228639, 1, 209),
       (8113, 228640, 2, 209);

delete from db_menu
where id_item = 8113
  and id_item_filho in (
    8114, 8115, 8121, 8124, 10187, 8701, 10077, 8700, 8125, 228602
);

insert into db_menu (id_item, id_item_filho, menusequencia, modulo)
values (228639, 8114,  1,  209),
       (228639, 8115,  2,  209),
       (228639, 8121,  3,  209),
       (228639, 8124,  4,  209),
       (228639, 10187, 5,  209),
       (228639, 10077, 6,  209);

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
values (228640, 228602, 1, 209),
       (228640, 228641, 2, 209);
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
delete from db_menu where id_item_filho in (228639, 228640) AND modulo = 209;
delete from db_menu where id_item in (228639, 228640) AND modulo = 209;
delete from db_itensmenu where id_item in (228639, 228640, 228641);

insert into db_menu (id_item, id_item_filho, menusequencia, modulo)
values (8113, 8114, 1, 209),
(8113, 8115, 2, 209),
(8113, 8121, 3, 209),
(8113, 8124, 4, 209),
(8113, 10187, 5, 209),
(8113, 8701, 6, 209),
(8113, 10077, 7, 209),
(8113, 8700, 8, 209),
(8113, 8125, 8, 209),
(8113, 228602, 10, 209);

update db_itensmenu set descricao = '[Ed 11] Anexo I - Demons. da Despesa com Pessoal', help = '[Ed 11] Anexo I - Demons. da Despesa com Pessoal', desctec = '[Ed 11] Anexo I - Demonstrativo da Despesa com Pessoal' where id_item = 228602;
SQL
        );
    }
}

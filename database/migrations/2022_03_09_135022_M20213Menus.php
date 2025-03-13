<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20213Menus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values ( 228633 ,'Anteriores a 11ª ed' ,'Anteriores a 11ª ed' ,'' ,'1' ,'1' ,'Anteriores a 11ª ed' ,'true' ),
       ( 228634 ,'A partir da 11ª ed' ,'A partir da 11ª ed' ,'' ,'1' ,'1' ,'A partir da 11ª ed' ,'true' ),
       ( 228635 ,'Anexo VI - Demonstrativo dos Resultados Primário e Nominal' ,'Demonstrativo dos Resultados Primário e Nominal' ,'pla2_abas_rreo.php?anexo=6' ,'1' ,'1' ,'Anexo VI - Demonstrativo dos Resultados Primário e Nominal' ,'true' );

update db_itensmenu set descricao = 'Anexo III - Dem. Receita Corrente Líquida' , help = 'Anexo III - Dem. Receita Corrente Líquida' , desctec = 'Anexo III - Dem. Receita Corrente Líquida'  where id_item = 228598;
update db_itensmenu set descricao = 'Anexo IV - Dem.das Rec e Desp do RPPS' , help = 'Anexo IV - Dem.das Rec e Desp do RPPS' , desctec = 'Anexo IV'  where id_item = 228475;
update db_itensmenu set descricao = 'Anexo VIII - Dem. Rec. e Desp. MDE (FUNDEB)' , help = 'Anexo VIII - Dem. Rec. e Desp. MDE (FUNDEB)' , desctec = 'Receitas e Despesas com MDE FUNDEB'  where id_item = 228476;
update db_itensmenu set descricao = 'Anexo XIV - Dem. Simplificado do RREO' , help = 'Anexo XIV - Dem. Simplificado do RREO' , desctec = 'Anexo XIV - Demostrativo simplificado'  where id_item = 228478;

insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
values ( 8033 ,228633 ,21 ,209 ),
       ( 8033 ,228634 ,22 ,209 );

delete from db_menu
 where id_item = 8033
   and id_item_filho in (
   8037, 8061, 9359, 8063, 8064, 8070, 8062, 10500, 8065, 8034, 8703, 8699, 8704, 8066, 8067, 8079, 228475, 228476, 228478, 228598
);

insert into db_menu (id_item, id_item_filho, menusequencia, modulo)
values (228633, 8037,   1,  209),
       (228633, 9359,   2,  209),
       (228633, 8063,   3,  209),
       (228633, 8064,   4,  209),
       (228633, 8070,   5,  209),
       (228633, 10500,  6,  209),
       (228633, 8065,   7,  209),
       (228633, 8034,   8,  209),
       (228633, 8703,   9,  209),
       (228633, 8699,   10, 209),
       (228633, 8704,   11, 209),
       (228633, 8066,   12, 209),
       (228633, 8067,   13, 209),
       (228633, 8079,   14, 209);

insert into db_menu (id_item, id_item_filho, menusequencia, modulo)
values
       (228634, 228598, 3, 209),
       (228634, 228475, 4, 209),
       (228634, 228635, 6, 209),
       (228634, 228476, 8, 209),
       (228634, 228478, 14, 209);
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

delete from db_menu where id_item_filho in (228633, 228634) AND modulo = 209;
delete from db_menu where id_item in (228633, 228634) AND modulo = 209;
delete from db_itensmenu where id_item in (228633, 228634, 228635);

update db_itensmenu set descricao = '[Ed 11] Anexo III - Dem. Receita Corrente Líquida' , help = '[Ed 11] Anexo III - Dem. Receita Corrente Líquida' , desctec = '[Ed 11] Anexo III - Dem. Receita Corrente Líquida'  where id_item = 228598;
update db_itensmenu set descricao = '[Ed 11] Anexo IV - Dem.das Rec e Desp do RPPS' , help = '[Ed 11] Anexo IV - Dem.das Rec e Desp do RPPS' , desctec = '[Ed 11] Anexo IV'  where id_item = 228475;
update db_itensmenu set descricao = '[Ed 11] Anexo VIII - Dem. Rec. e Desp. MDE (FUNDEB)' , help = '[Ed 11] Anexo VIII - Dem. Rec. e Desp. MDE (FUNDEB)' , desctec = '[Ed 11] Receitas e Despesas com MDE FUNDEB'  where id_item = 228476;
update db_itensmenu set descricao = '[Ed 11] Anexo XIV - Dem. Simplificado do RREO' , help = '[Ed 11] Anexo XIV - Dem. Simplificado do RREO' , desctec = '[Ed 11] Anexo XIV - Demostrativo simplificado'  where id_item = 228478;

insert into db_menu (id_item, id_item_filho, menusequencia, modulo)
values (8033, 8037,   1,  209),
       (8033, 8061,   2,  209),
       (8033, 9359,   3,  209),
       (8033, 8063,   4,  209),
       (8033, 8064,   5,  209),
       (8033, 8070,   6,  209),
       (8033, 8062,   7,  209),
       (8033, 10500,  8,  209),
       (8033, 8065,   9,  209),
       (8033, 8034,   10, 209),
       (8033, 8703,   11, 209),
       (8033, 8699,   12, 209),
       (8033, 8704,   13, 209),
       (8033, 8066,   14, 209),
       (8033, 8067,   15, 209),
       (8033, 8079,   16, 209),
       (8033, 228475, 17, 209),
       (8033, 228476, 18, 209),
       (8033, 228478, 19, 209),
       (8033, 228598, 20, 209);
SQL
        );
    }
}

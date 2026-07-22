<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20948Menus extends Migration
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
values (228727, 'Plano de Contas' ,'Plano de Contas' ,'' ,'1' ,'1' ,'Plano de Contas' ,'true' ),
       (228728, 'PCASP' ,'PCASP' ,'' ,'1' ,'1' ,'Árvore de menus reservadas para manutenção e atualização do plano PCASP' ,'true' ),
       (228729, 'Manutenção' ,'Manutenção' ,'con1_manutencaopcasp001.php' ,'1' ,'1' ,'Manutenção' ,'false' ),
       (228730, 'Importar' ,'Importar' ,'con1_importacaopcasp001.php' ,'1' ,'1' ,'Importar planos do governo' ,'true' ),
       (228731, 'Mapear' ,'Mapear' ,'con1_mapearpcasp001.php' ,'1' ,'1' ,'Mapear plano PCASP' ,'true' ),

       (228732, 'Exclusão' ,'Exclusão' ,'con1_exclusaogeralpcasp001.php' ,'1' ,'1' ,'Exclusão do plano PCASP' ,'true' ),
       (228733, 'Orçamentário' ,'Orçamentário' ,'' ,'1' ,'1' ,'Árvore de menus para manutenção do Plano Orçamentário' ,'true' ),
       (228734, 'Manutenção' ,'Manutenção' ,'con1_manutencaoplanoorcamentario.php' ,'1' ,'1' ,'Manutenção do plano orçamentário' ,'false' ),
       (228735, 'Importar' ,'Importar' ,'con1_importarorcamentario001.php' ,'1' ,'1' ,'Importação do plano orçamentário' ,'true' ),

       (228736, 'Mapear Despesa' ,'Mapear Despesa' ,'' ,'1' ,'1' ,'Realizar o mapeamento do plano orçamentário do e-cidade com o plano PCASP' ,'true'  ),
       (228740, 'Mapear Receita' ,'Mapear Receita' ,'' ,'1' ,'1' ,'Realizar o mapeamento do plano orçamentário do e-cidade com o plano PCASP' ,'true' ),

       (228787 ,'Geral' ,'Geral' ,'con1_mapearorcamentarioreceita001.php' ,'1' ,'1' ,'Mapeamento Geral' ,'true' ),
       (228788 ,'Por Conta' ,'Por Conta' ,'con1_mapearorcamentarioreceitaporconta001.php' ,'1' ,'1' ,'Mapeamento da Receita por conta' ,'true' ),
       (228789 ,'Geral' ,'Geral' ,'con1_mapearorcamentariodespesa001.php' ,'1' ,'1' ,'Mapeamento geral da despesa' ,'true' ),
       (228790 ,'Por Conta' ,'Por Conta' ,'con1_mapearorcamentariodespesaporconta001.php' ,'1' ,'1' ,'Mapeamento da despesa por conta' ,'true' ),
       (228737, 'Exclusão Geral' ,'Exclusão Geral' ,'con1_exclusaogeralorcamentario001.php' ,'1' ,'1' ,'Exclusão Geral do plano orçamentário' ,'true' );

insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
values (29, 228727, 309, 209),
       (228727, 228728, 1, 209),
       (228728, 228729, 1, 209),
       (228728, 228730, 2, 209),
       (228728, 228731, 3, 209),
       (228728, 228732, 4, 209),
       (228727, 228733, 2, 209),
       (228733, 228734, 1, 209),
       (228733, 228735, 2, 209),
       (228733, 228736, 3, 209),
       (228733, 228740, 4, 209),
       (228740, 228787, 1, 209),
       (228740, 228788, 2, 209),
       (228736, 228789, 1, 209),
       (228736, 228790, 2, 209),
       (228733, 228737, 5, 209);
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
delete from db_menu where id_item in (228727, 228728, 228733, 228740, 228736);
delete from db_menu where id_item_filho = 228727 AND modulo = 209;
delete from db_itensmenu where id_item in (228727, 228728, 228729, 228730, 228731, 228732, 228733, 228734, 228735, 228736, 228737, 228740, 228787, 228788, 228789, 228790);
SQL
        );
    }
}

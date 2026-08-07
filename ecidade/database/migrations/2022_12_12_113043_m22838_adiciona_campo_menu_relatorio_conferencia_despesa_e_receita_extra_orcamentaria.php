<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22838AdicionaCampoMenuRelatorioConferenciaDespesaEReceitaExtraOrcamentaria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228819 ,'Relatório Conferência Despesa Extra Orçamentária  (novo)' ,'Relatório Conferência Despesa Extra Orçamentária (novo)' ,'emp2_relconferenciadespesaexorc.php' ,'1' ,'1' ,'Relatório Conferência Despesa Extra Orçamentária' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5603 ,228819 ,15 ,398 );
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228820 ,'Relatório Conferência Receita Extra Orçamentária (novo)' ,'Relatório Conferência Receita Extra Orçamentária (novo)' ,'emp2_relconferenciareceitaexorc.php' ,'1' ,'1' ,'Relatório Conferência Receita Extra Orçamentária (novo)' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5603 ,228820 ,16 ,398 );
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
delete from configuracoes.db_menu where id_item_filho = 228820 AND modulo = 398;
delete from configuracoes.db_menu where id_item_filho = 228819 AND modulo = 398;
delete from configuracoes.db_itensmenu where id_item = 228820;
delete from configuracoes.db_itensmenu where id_item = 228819;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

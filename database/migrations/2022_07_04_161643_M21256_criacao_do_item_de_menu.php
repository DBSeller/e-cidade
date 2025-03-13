<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21256CriacaoDoItemDeMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228683 ,'Manutenção das Retenções' ,'Manutenção das Retenções (INSS-PJ)' ,'emp4_manutencaoretencoes001.php' ,'1' ,'1' ,'Rotina para ajuste/consulta das retenções de contribuição previdenciária.' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228683 ,555 ,398 );
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

        delete from db_menu where id_item_filho = 228683 AND modulo = 398;
        delete from db_itensmenu where id_item = 228683;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22759MenuRelatorioRetencoesEfdreinf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228823 ,'Retenções da Efd-Reinf' ,'Retenções da Efd-Reinf' ,'emp2_conferetencoesefdreinf001.php' ,'1' ,'1' ,'Retenções da Efd-Reinf' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5603 ,228823 ,17 ,398 );
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
            delete from db_menu where id_item_filho = 228823 AND modulo = 398;
            delete from db_itensmenu where id_item = 228823;
SQL
        );
    }
}

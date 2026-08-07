<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M16377ExtratoArrecadacaoContribuinte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

        -- Cria item de menu Extrato do Contribuinte, no submenu Relatórios do módulo Arrecadação
        INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228337 ,'Extrato do Contribuinte' ,'Extrato do Contribuinte' ,'arr2_extratocontribuinte001.php' ,'1' ,'1' ,'Extrato da Arrecadação de 1 ou mais contribuintes de acordo com o filtro, seja cgm, numpre ou matric' ,'true' );
        INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 30 ,228337 ,827 ,1985522 );

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
        -- Remove item de menu Extrato do Contribuinte, do submenu Relatórios do módulo Arrecadação
        DELETE FROM db_menu WHERE id_item_filho = 228337 AND modulo = 1985522;
        DELETE FROM db_itensmenu WHERE id_item = 228337;
SQL
      );

    }
}

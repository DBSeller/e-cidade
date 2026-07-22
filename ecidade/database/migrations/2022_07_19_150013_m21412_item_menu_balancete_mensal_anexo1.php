<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21412ItemMenuBalanceteMensalAnexo1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
          insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values
            ( 228724 ,'Balancetes Mensais' ,'Balancetes Mensais' ,'' ,'1' ,'1' ,'Balancetes Mensais' ,'false' ),
            ( 228725 ,'Anexo I - Balancete Financeiro' ,'Anexo I - Balancete Financeiro' ,'con4_balancete_mensal_anexo_1001.php' ,'1' ,'1' ,'Anexo I - Balancete Financeiro' ,'false' );

          insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values
            ( 3331 ,228724 ,56 ,209 ),
            ( 228724 ,228725 ,1 ,209 );

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

          delete from db_menu where id_item_filho in ( 228724, 228725)  AND modulo = 209;
          delete from db_itensmenu where id_item in (228724, 228725);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

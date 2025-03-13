<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M18456ItemMenuRastreabilidadeMedicamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228644 ,'Rastreabilidade' ,'Relatуrio de Rastreabilidade do medicamento.' ,'far2_rastreabilidademedicamento.php' ,'1' ,'1' ,'Relatуrio exibe a localizaзгo do medicamento dentro do(s) depуsito(s).' ,'true' );");
        DB::statement("INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 7071 ,228644 ,7 ,6877 );");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DELETE FROM db_menu WHERE id_item_filho = 228644;");
        DB::statement("DELETE FROM db_itensmenu WHERE id_item = 228644;");
    }
}

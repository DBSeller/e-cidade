<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20152AlteracaoRollbackControleParcelamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();  

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
  
    }

    private function upDicionario()
    {
        // itens menu
        DB::statement("update db_itensmenu set id_item = 228611 , descricao = 'Retorna Vencimentos Originais' , help = 'Retorna Vencimentos Originais' , funcao = 'arr4_rollback_parc_venc.php' , itemativo = '1' , manutencao = '1' , desctec = 'Rotina para reversão de parcelamentos do controle de parcelamentos vencidos' , libcliente = 'true' where id_item = 228611;");
    }

    private function downDicionario()
    {
        // itens menu
    }
}
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M20363EmissaoGeralCertidaoIsencao extends Migration
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
        DB::statement("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228679 ,'Emissão Geral de Certidão de Isenção' ,'Emissão Geral de Certidão de Isenção' ,'cad4_emissaogeralisencao.php' ,'1' ,'1' ,'Gera layout para emissão de Certidão de Isenção do IPTU/Taxas' ,'true' );");
        DB::statement("insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228679 ,554 ,578 );");
    }   

    private function downDicionario()
    {
        // itens menu
        DB::statement("delete from db_itensmenu where id_item in (228679);");
        DB::statement("delete from db_menu where id_item_filho in (228679, 578);");
    }
}

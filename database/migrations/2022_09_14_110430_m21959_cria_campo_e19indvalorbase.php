<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M21959CriaCampoE19indvalorbase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    /**
     * Dic. de dados
     */
    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_syscampo values(1014491,'e19_indvalorbase','bool','Indicativo se deve usar o valor base como referência para cálculo dos 11%','false', 'Referência para cálculo',1,'t','f','f',5,'text','Referência para cálculo');
            insert into db_sysarqcamp values(1010362,1014491,10,0);
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            delete from db_sysarqcamp where codarq = 1010362 and codcam = 1014491;
            delete from db_syscampo where codcam = 1014491;
SQL
        );
    }

    /**
     * Estrutura
     */
    private function upEstrutura()
    {
        DB::statement("ALTER TABLE empenho.retencaoreceitasadicionais ADD e19_indvalorbase bool NULL DEFAULT false;");
    }

    private function downEstrutura()
    {
        DB::statement("ALTER TABLE empenho.retencaoreceitasadicionais DROP COLUMN e19_indvalorbase;");
    }
}

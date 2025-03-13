<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21746AdicionaParametroConfirmaDataAntesDaEscrituracao extends Migration
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

    public function upDicionario()
    {
        $sql = <<<SQL
            insert into db_syscampo values(1014456,'c90_confirmadata','bool','Confirma data antes da escrituração','f', 'Confirma data antes da escrituração',1,'f','f','f',5,'text','Confirma data antes da escrituração');
            insert into db_sysarqcamp values(784,1014456,6,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
    public function downDicionario()
    {
        $sql = <<<SQL
            delete from db_sysarqcamp where codcam = 1014456;
            delete from db_syscampo where codcam = 1014456;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function upEstrutura()
    {
        $sql = <<<SQL
            alter table contabilidade.conparametro add column c90_confirmadata boolean;
            update contabilidade.conparametro set c90_confirmadata = 'f';
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
    public function downEstrutura()
    {
        $sql = <<<SQL
            alter table contabilidade.conparametro drop column c90_confirmadata;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

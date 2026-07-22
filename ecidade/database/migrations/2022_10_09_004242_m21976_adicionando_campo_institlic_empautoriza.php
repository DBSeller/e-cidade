<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21976AdicionandoCampoInstitlicEmpautoriza extends Migration
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

    public function upDicionario() {
        $sql = <<<SQL
            insert into db_syscampo values(1014515,'e54_institlic','int4','Instituicao da Licitacao escolhida para autorizacao de empenho','0', 'Instituicao da Licitacao da autorizacao',2,'t','f','f',1,'text','Instituicao da Licitacao da autorizacao');
            insert into db_sysarqcamp values(810,1014515,25,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function upEstrutura() {
        $sql = <<<SQL
            alter table empautoriza add column e54_institlic int default null;
SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    public function downDicionario () {
        $sql = <<<SQL
            delete from db_sysarqcamp where codcam = 1014515;
            delete from db_syscampo where codcam = 1014515;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downEstrutura() {
        $sql = <<<SQL
            alter table empautoriza drop column e54_institlic;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

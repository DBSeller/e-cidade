<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20010TipoSegregacao extends Migration
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

    private function upDicionario()
    {
        $sSql = <<<SQL
        insert into configuracoes.db_syscampo values(1013710,'r33_tiposegregacao',
                                       'int4','Tipo de plano de segregação da massa. ',
                                       '0', 'Campo Tipo Segregação',4,'t','f','f',1,'text','Campo Tipo Segregação');
        insert into configuracoes.db_sysarqcamp values(561,1013710,24,0);
SQL;
        DB::connection()->getPdo()->exec($sSql);

    }

    private function downDicionario()
    {
        $sSql = <<<SQL
        delete from configuracoes.db_sysarqcamp where codcam = 1013710;
        delete from configuracoes.db_syscampo where codcam = 1013710;
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

    public function upEstrutura() {

        $sSql = <<<SQL
alter table pessoal.inssirf add column r33_tiposegregacao int4;
SQL;
        DB::connection()->getPdo()->exec($sSql);

    }

    public function downEstrutura() {

        $sSql = <<<SQL
alter table pessoal.inssirf drop column r33_tiposegregacao;
SQL;
        DB::connection()->getPdo()->exec($sSql);

    }
}

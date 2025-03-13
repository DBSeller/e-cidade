<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19626CampoDataAbonoPermanencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upAdicionaCampo();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downExcluiCampo();
    }

    private function upDicionario() {
        $sql = <<<SQL
            insert into configuracoes.db_syscampo values(1013571,'rh02_dataabonopermanencia','date','Data de início abono permanência','null', 'Data de início abono permanência',10,'t','f','f',1,'text','Data de início abono permanência');
            insert into configuracoes.db_sysarqcamp values(1158,1013571,35,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario() {
        $sql = <<<SQL
            delete from configuracoes.db_sysarqcamp where codarq = 1158 and codcam = 1013571;
            delete from configuracoes.db_syscampo where codcam = 1013571;
SQL;

        DB::connection()->getPdo()->exec($sql);
    }

    private function upAdicionaCampo() {
        $sql = <<<SQL
        ALTER TABLE pessoal.rhpessoalmov ADD COLUMN rh02_dataabonopermanencia date  DEFAULT null;
SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    private function downExcluiCampo() {
        $sql = <<<SQL
        ALTER TABLE pessoal.rhpessoalmov DROP COLUMN rh02_dataabonopermanencia;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

}

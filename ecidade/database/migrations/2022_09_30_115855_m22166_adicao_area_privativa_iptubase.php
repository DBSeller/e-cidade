<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22166AdicaoAreaPrivativaIptubase extends Migration
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

    private function upEstrutura() {
        $sql = <<<SQL
        ALTER TABLE cadastro.iptubase ADD COLUMN j01_areaprivativa double precision default 0
SQL;
    DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura() {
        $sql = <<<SQL
        ALTER TABLE cadastro.iptubase DROP COLUMN j01_areaprivativa
SQL;
    DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionario() {
        $sql = <<<SQL
            insert into configuracoes.db_syscampo values(1014504,'j01_areaprivativa','float8','Área privativa do lote para condôminios','0', 'Area Privativa do Lote',15,'t','f','f',0,'text','Area Privativa do Lote');
            insert into configuracoes.db_sysarqcamp values(27,1014504,19,0);
SQL;
    DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario() {
        $sql = <<<SQL
            delete from configuracoes.db_sysarqcamp where codarq = 27 and codcam = 1014504;
            delete from configuracoes.db_syscampo where codcam = 1014504;
SQL;
    DB::connection()->getPdo()->exec($sql);
    }
}

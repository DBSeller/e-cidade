<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20175TipoDeProvimentoTipoDeAdmissao extends Migration
{

    public function up()
    {
        $this->upProvimentoAdmissao();
    }

    public function down()
    {
        $this->downProvimentoAdmissao();
    }

    public function upProvimentoAdmissao()
    {
    $sql=<<<SQL
        INSERT INTO configuracoes.db_syscampo VALUES(1013757,'rh30_provimento','int4','Preencher com o tipo de provimento do trabalhador estatutário.','0', 'Tipo de Provimento',2,'t','f','f',1,'text','Tipo de Provimento');
        INSERT INTO configuracoes.db_syscampo VALUES(1013758,'rh30_admissao','int4','Informações do trabalhador celetista do tipo de admissão do trabalhador','0', 'Tipo de Admissão',2,'t','f','f',1,'text','Tipo de Admissão');
        INSERT INTO configuracoes.db_sysarqcamp VALUES(1183,1013758,13,0);
        INSERT INTO configuracoes.db_sysarqcamp VALUES(1183,1013757,14,0);
        ALTER TABLE pessoal.rhregime ADD rh30_admissao int4 NULL;
        ALTER TABLE pessoal.rhregime ADD rh30_provimento int4 NULL;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downProvimentoAdmissao()
    {
        $sql=<<<SQL
            ALTER TABLE pessoal.rhregime DROP COLUMN rh30_admissao;
            ALTER TABLE pessoal.rhregime DROP COLUMN rh30_provimento;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam = 1013757;
            DELETE FROM configuracoes.db_sysarqcamp WHERE codcam = 1013758;
            DELETE FROM configuracoes.db_syscampo WHERE codcam = 1013757;
            DELETE FROM configuracoes.db_syscampo WHERE codcam = 1013758;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}


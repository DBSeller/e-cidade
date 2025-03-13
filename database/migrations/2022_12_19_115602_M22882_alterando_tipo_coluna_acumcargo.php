<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22882AlterandoTipoColunaAcumcargo extends Migration
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
        $sql = <<<SQL
        -- Deletando dado criado no dicionário local.
        delete from configuracoes.db_sysarqcamp where codarq = 1174 and codcam = 1059584;
        delete from configuracoes.db_syscampo where codcam = 1059584;

        -- Insere os dados de acordo com o dicionário de dados.
        insert into configuracoes.db_syscampo values(1014661,'rh37_acumcargo','varchar(6)','Informação que define se o cargo é acumulável','', 'Cargo acumulável',6,'t','t','f',0,'text','Cargo acumulável');
        insert into configuracoes.db_sysarqcamp values(1174,1014661,15,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario() {
        $sql = <<<SQL
        -- Remove os dados de acordo com o dicionário de dados.
        delete from configuracoes.db_sysarqcamp where codarq = 1174 and codcam = 1014661;
        delete from configuracoes.db_syscampo where codcam = 1014661;

        -- Insert dado referente a rh37_acumcargo inserido no dicionário local.
        insert into configuracoes.db_syscampo values(1059584,'rh37_acumcargo','bool','Informação que define se o cargo é acumulável','f', 'Cargo acumulável',1,'f','t','f',0,'text','Cargo acumulável');
        insert into configuracoes.db_sysarqcamp values(1174,1059584,15,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstrutura() {
        $sql = <<<SQL
        ALTER TABLE pessoal.rhfuncao ALTER COLUMN rh37_acumcargo TYPE varchar(6);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura() {
        $sql = <<<SQL
        ALTER TABLE pessoal.rhfuncao 
	        ALTER COLUMN rh37_acumcargo DROP DEFAULT,
            ALTER COLUMN rh37_acumcargo TYPE BOOLEAN USING rh37_acumcargo::BOOLEAN,
            ALTER COLUMN rh37_acumcargo SET DEFAULT FALSE;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20295AdicaoTipoparentesco extends Migration
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
        $this->upDePara();
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
        $sSql = <<<SQL
            insert into configuracoes.db_syscampo values(1013791,'rh31_tipoparentesco','char(2)','tipo de parentesco relacionado ao esocial.','', 'tipo de parentesco esocial',2,'t','f','f',0,'text','tipo de parentesco esocial');
            insert into configuracoes.db_sysarqcamp values(1186,1013791,10,0);
SQL;
            DB::connection()->getPdo()->exec($sSql);
    }

    public function downDicionario(){
        $sSql = <<<SQL
            delete from configuracoes.db_sysarqcamp where codcam = 1013791;
            delete from configuracoes.db_syscampo where codcam = 1013791;
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

    public function upEstrutura()
    {
     $sSql = <<<SQL
            alter table pessoal.rhdepend add column rh31_tipoparentesco char(2);
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

    public function downEstrutura()
    {
        $sSql = <<<SQL
            alter table pessoal.rhdepend drop column rh31_tipoparentesco;
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }
    
    private function upDePara()
    {
       DB::update("UPDATE pessoal.rhdepend SET rh31_tipoparentesco =
        CASE
            WHEN rh31_gparen ='C' THEN '01'
            WHEN rh31_gparen ='F' THEN '03'
            WHEN rh31_gparen IN ('P','M','A') THEN '09'
            ELSE '99'
        END");
    }
}
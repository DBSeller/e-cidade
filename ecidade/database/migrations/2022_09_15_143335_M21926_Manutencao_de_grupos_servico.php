<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21926ManutencaoDeGruposServico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

  ALTER TABLE issqn.issconfiguracaogruposervico ADD COLUMN  IF NOT EXISTS q136_deducao BOOLEAN DEFAULT FALSE;
  ALTER TABLE issconfiguracaogruposervico ADD COLUMN   IF NOT EXISTS q136_retencao BOOLEAN DEFAULT FALSE;

    insert into db_syscampo values(1014492,'q136_deducao','bool','Deduz Valor Nota','f', 'Deduz Valor Nota',1,'f','f','f',5,'text','Deduz Valor Nota');
    insert into db_syscampo values(1014493,'q136_retencao','bool','Retenção p/ Prestação Fora do Município','f', 'Retenção p/ Prestação Fora do Município',1,'f','f','f',5,'text','Retenção p/ Prestação Fora do Município');
    insert into db_sysarqcamp values(3430,1014493,7,0);
    insert into db_sysarqcamp values(3430,1014492,8,0);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL

ALTER TABLE issconfiguracaogruposervico DROP COLUMN  IF EXISTS q136_deducao;
ALTER TABLE issconfiguracaogruposervico DROP COLUMN IF EXISTS  q136_retencao;
DELETE FROM db_sysarqcamp WHERE codcam = 1014492;
DELETE FROM db_sysarqcamp WHERE codcam = 1014493;
DELETE FROM db_syscampo WHERE codcam = 1014492;
DELETE FROM db_syscampo WHERE codcam = 1014493;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

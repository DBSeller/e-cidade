<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22254QuadroGeralHorarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
ALTER TABLE escola.caddisciplina ADD COLUMN ed232_corhtml CHARACTER VARYING(7) NOT NULL default '#FFFFFF';
ALTER TABLE secretariadeeducacao.sec_parametros ADD COLUMN ed290_cordisciplinaquadro INTEGER NOT NULL DEFAULT 1;

INSERT INTO db_syscampo VALUES (1014611, 'ed232_corhtml', 'varchar(7)','Cor HTML','f', 'Cor HTML', 1 , 'f', 'f', 'f', 3 ,'text', 'Cor HTML');
INSERT INTO db_syscampo VALUES (1014612, 'ed290_cordisciplinaquadro', 'int4','Aplicar Cor Disciplinas / Quadro de Horarios. (0 - Descrio Disciplina, 1 - Quadro de Horrios)','f', 'Aplicar Cor Disciplinas / Quadro de Horrios', 1 , 'f', 'f', 'f', 1 ,'text', 'Aplicar Cor Disciplina/Quadro');

INSERT INTO db_syscampodef VALUES (1014612,'0','Descrio Disciplina/Professor');
INSERT INTO db_syscampodef VALUES (1014612,'1','Quadro de Horrios da Disciplina');

INSERT INTO db_sysarqcamp VALUES (2017,1014611,6,0);
INSERT INTO db_sysarqcamp VALUES (3180,1014612,5,0);
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
DELETE FROM db_sysarqcamp WHERE codcam IN (1014611,1014612);
DELETE FROM db_syscampodef WHERE codcam IN (1014612);
DELETE FROM db_syscampo WHERE codcam IN (1014611,1014612);
ALTER TABLE escola.caddisciplina DROP COLUMN ed232_corhtml;
ALTER TABLE secretariadeeducacao.sec_parametros DROP COLUMN ed290_cordisciplinaquadro;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

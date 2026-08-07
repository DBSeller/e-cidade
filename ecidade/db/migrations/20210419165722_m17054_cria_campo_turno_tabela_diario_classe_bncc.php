<?php

use Classes\PostgresMigration;

class M17054CriaCampoTurnoTabelaDiarioClasseBncc extends PostgresMigration
{
    public function up()
    {
        $this->execute("ALTER TABLE diario_classe_bncc ADD COLUMN ed155_turmaturnoreferente int4");
        $this->execute("ALTER TABLE diario_classe_bncc ADD CONSTRAINT diario_classe_bncc_turmaturnoreferente_fk
                            FOREIGN KEY (ed155_turmaturnoreferente) REFERENCES turmaturnoreferente(ed336_codigo);");
        $this->dicionarioUp();

    }

    public function down()
    {
        $this->execute("ALTER TABLE diario_classe_bncc DROP COLUMN ed155_turmaturnoreferente");
        $this->dicionarioDown();
    }

    private function dicionarioUp()
    {
        $sql = "insert into db_syscampo values(1013167,'ed155_turmaturnoreferente','int4','Turma Turno Referente','0', 'TurmaTurnoReferente',10,'t','f','f',1,'text','TurmaTurnoReferente');
               insert into db_sysarqcamp values(1010520,1013167,6,0);
               insert into db_sysforkey values(1010520,1013167,1,3680,0);";

        $this->execute($sql);
    }

    private function dicionarioDown()
    {
        $this->execute("delete from db_sysforkey where codcam = 1013167");
        $this->execute("delete from db_sysarqcamp where codcam = 1013167");
        $this->execute("delete from db_syscampo where codcam = 1013167");
    }
}

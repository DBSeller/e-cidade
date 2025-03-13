<?php

use Classes\PostgresMigration;

class M13295AdicionarCampoTurno extends PostgresMigration
{
    public function up()
    {
        $this->cadastraDicionarioDeDados();
        $this->alteraTabelaUp();
    }

    public function down()
    {
        $this->removeDicionarioDeDados();
        $this->alteraTabelaDown();
    }

    public function alteraTabelaUp()
    {
        $this->execute(<<<SQL
            alter table turmaatividadecomplementar add column ed146_turnoreferente int4 NOT NULL;
SQL
        );
    }

    public function alteraTabelaDown()
    {
        $this->execute(<<<SQL
            alter table turmaatividadecomplementar drop column ed146_turnoreferente;
SQL
        );
    }

    public function cadastraDicionarioDeDados()
    {
        $this->execute(<<<SQL
            insert into db_syscampo values(1010458,'ed146_turnoreferente','int4','Turno Referente','0', 'Turno Referente',10,'f','f','f',1,'text','Turno Referente');
            insert into db_sysarqcamp values(1010443,1010458,9,0);
            insert into db_sysforkey values(1010443,1010458,1,2015,1);
SQL
        );
    }

    public function removeDicionarioDeDados()
    {
        $this->execute(<<<SQL
            delete from db_sysforkey where codarq = 1010443 AND codcam = 1010458 AND sequen = 1 AND referen = 2015;
            delete from db_sysarqcamp where codcam = 1010458;
            delete from db_syscampo where codcam = 1010458;
SQL
        );
    }
}

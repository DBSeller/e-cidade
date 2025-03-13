<?php

use Classes\PostgresMigration;

class M13414CampoPermiteDuplicata extends PostgresMigration
{
    public function up()
    {
        $this->criaDicionarioDados();
        $this->modificaTabela();
    }

    public function down()
    {
        $this->removeDicionarioDados();
        $this->reverteTabela();
    }

    private function criaDicionarioDados()
    {
        $sql = "
            INSERT INTO db_syscampo VALUES(1010654,'h12_permiteduplicar','bool','Campo que verifica se o tipo de assentamento pode ser duplicado.','f', 'Permite duplicar',1,'f','f','f',5,'text','Permite duplicar');
            INSERT INTO db_syscampodef VALUES(1010654,'false','');
            INSERT INTO db_sysarqcamp VALUES(596,1010654,18,0);
        ";
        $this->execute($sql);
    }

    private function modificaTabela()
    {
        $sql = "
            ALTER TABLE tipoasse ADD COLUMN h12_permiteduplicar BOOLEAN NOT NULL DEFAULT FALSE;
        ";
        $this->execute($sql);
    }

    private function removeDicionarioDados()
    {
        $sql = "
            DELETE FROM db_sysarqcamp WHERE codcam = 1010654;
            DELETE FROM db_syscampodef WHERE  codcam = 1010654;
            DELETE FROM db_syscampo WHERE codcam = 1010654;
        ";
        $this->execute($sql);
    }

    private function reverteTabela()
    {
        $sql = "ALTER TABLE tipoasse DROP COLUMN h12_permiteduplicar";
        $this->execute($sql);
    }
}

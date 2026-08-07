<?php

use Classes\PostgresMigration;

class M18250CriaCampoHorasTabelaRegimeTrabalho extends PostgresMigration
{

    public function up()
    {
        $this->dicionarioUp();
        $this->estruturaUp();
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->estruturaDown();
    }

    private function dicionarioUp()
    {
        $sql = "
        insert into db_syscampo values(1013277,'ed24_horas','char(10)','Cadastro de horas','', 'Horas',10,'f','f','f',0,'text','Horas');
        insert into db_sysarqcamp values(1010093,1013277,3,0);
        ";
        $this->execute($sql);
    }

    private function dicionarioDown()
    {
        $sql = "
        delete from db_sysarqcamp where codcam = 1013277;
        delete from db_syscampo where codcam = 1013277;
        ";

        $this->execute($sql);
    }

    private function estruturaUp()
    {
       $sql = "alter table regimetrabalho add column ed24_horas char(10);";
       $this->execute($sql);
    }

    private function estruturaDown()
    {
        $sql = "alter table regimetrabalho drop column ed24_horas;";
        $this->execute($sql);
    }
}

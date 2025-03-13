<?php

use Classes\PostgresMigration;

class AntendimentoAnterior extends PostgresMigration
{
    public function up()
    {
        $this->upDicionarioDeDados();
        $this->upAddColumn();
    }

    public function down()
    {
        $this->downDicionarioDeDados();
        $this->downAddColumn();
    }

    public function upAddColumn()
    {
        $this->execute(<<<SQL
            ALTER TABLE
            ouvidoria.ouvidoriaatendimentoprocessoeletronico
            ADD COLUMN ov33_ouvidoriaatendimento_anterior int8;
SQL
        );
    }

    public function downAddColumn()
    {
        $this->execute(<<<SQL
            ALTER TABLE
            ouvidoria.ouvidoriaatendimentoprocessoeletronico
            DROP COLUMN ov33_ouvidoriaatendimento_anterior;
SQL
        );
    }


    public function upDicionarioDeDados()
    {

        $this->execute(<<<SQL
            insert into db_syscampo values(1011943,'ov33_ouvidoriaatendimento_anterior','int8','Atendimento anterior','0', 'Atendimento anterior',14,'t','f','f',1,'text','Atendimento anterior');
            insert into db_sysarqcamp values(1010472,1011943,4,0);
SQL
        );
    }

    public function downDicionarioDeDados()
    {
        $this->execute(<<<SQL
            DELETE FROM db_sysarqcamp  WHERE codcam  = 1011943;
            DELETE FROM db_syscampo  WHERE  codcam = 101194314;

SQL
        );

    }


}

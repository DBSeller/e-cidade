<?php

use Classes\PostgresMigration;

class M17021AddCampoInterfaceadoNaTabelaLabLaboratorio extends PostgresMigration
{
    public function up()
    {
        $this->execute(
<<<SQL
            insert into db_syscampo values(1011921,'la02_interfaceado','bool','Este campo é para informar se o laboratório é configurado para ser interfaceado ou não.','f', 'Interfaceado',1,'t','f','f',5,'text','Interfaceado');
            insert into db_sysarqcamp values(2753,1011921,10,0);

            alter table lab_laboratorio add column la02_interfaceado boolean default false;
SQL
        );
    }

    public function down()
    {
        $this->execute(
<<<SQL
            delete from db_sysarqcamp where codcam = 1011921;
            delete from db_syscampo where codcam = 1011921;

            alter table lab_laboratorio drop column la02_interfaceado;
SQL
        );
    }
}

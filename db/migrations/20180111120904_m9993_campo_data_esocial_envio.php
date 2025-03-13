<?php

use Classes\PostgresMigration;

class M9993CampoDataEsocialEnvio extends PostgresMigration
{
    public function up()
    {
        $sSql = "
            insert into db_syscampo values(1009604,'rh213_data','varchar(20)','Data','', 'Data',20,'f','f','f',0,'text','Data');
            insert into db_sysarqcamp values(1010244,1009604,8,0);
            alter table esocialenvio add column rh213_data timestamp;
            alter table esocialenvio alter column rh213_data set default now();
        ";
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "
            delete from db_sysarqcamp where codcam = 1009604;
            delete from db_syscampo where codcam = 1009604;
            alter table esocialenvio drop column rh213_data;
        ";
        $this->execute($sSql);
    }
}

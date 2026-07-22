<?php

use Classes\PostgresMigration;

class M15690AdicionaCampoAchadoCovid extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
        insert into db_syscampo values(1011169,'sd29_t_achado','text','campo outros achados','', 'outros achados',1,'t','t','f',0,'text','outros achados');
        insert into db_sysarqcamp values(1006042,1011169,13,0);
        alter table ambulatorial.prontproced add column sd29_t_achado TEXT;
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
        delete from db_sysarqcamp where codcam = 1011169;
        delete from db_syscampo where codcam = 1011169;
        alter table ambulatorial.prontproced drop column sd29_t_achado;
SQL;
        $this->execute($sql);
    }
}

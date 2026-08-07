<?php

use Classes\PostgresMigration;

class M17966BancoTef extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_syscampo values(1013214,'k15_bancotef','char(1)','Define se esse banco é para operações TEF.','', 'Banco TEF',1,'t','f','f',0,'text','Banco TEF');
            insert into db_sysarqcamp values(116,1013214,60,0);

            alter table caixa.cadban add column k15_bancotef boolean default 'f';
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codcam = 1013214;
            delete from db_syscampo where codcam = 1013214;

            alter table caixa.cadban drop column k15_bancotef;
SQL
        );
    }
}

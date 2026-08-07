<?php

use Classes\PostgresMigration;

class M17905ParametroTefAutenticadora extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_syscampo values(1013169,'k11_tef','char(1)','Esse campo deve ser marcado caso este caixa seja para transações TEF','', 'TEF',1,'t','f','f',0,'text','TEF');
            insert into db_sysarqcamp values(199,1013169,18,0);

            alter table caixa.cfautent add column k11_tef boolean default 'f';
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codcam = 1013169;
            delete from db_syscampo where codcam = 1013169;

            alter table caixa.cfautent drop column k11_tef;
SQL
        );
    }
}

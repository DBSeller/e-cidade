<?php

use Classes\PostgresMigration;

class ItemMenuTipoProcesso extends PostgresMigration
{
    public function up(){
        $this->execute(<<<SQL
       insert into db_syscampo values(1013158,'p51_itemmenu','varchar(100)','Menu exibido no ambiente processo eletrônico ','', 'p51_itemmenu',100,'t','t','f',0,'text','p51_itemmenu');
       insert into db_sysarqcamp values(393,1013158,9,0);
       ALTER TABLE protocolo.tipoproc ADD COLUMN  p51_itemmenu varchar(100);
SQL
        );
    }

    public function down(){
        $this->execute(<<<SQL
            DELETE FROM db_sysarqcamp WHERE codcam = 1013158;
            DELETE FROM db_syscampo WHERE codcam = 1013158;
            ALTER TABLE protocolo.tipoproc  DROP COLUMN IF EXISTS p51_itemmenu;
SQL
        );

    }
}

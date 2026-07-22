<?php

use Classes\PostgresMigration;

class M18918 extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL
            insert into db_syscampo values(1013406,'p51_mensagem','varchar(250)','Mensagem padrão enviada para atendimento do processo eletrônico ','', 'p51_mensagem',250,'t','f','f',0,'text','p51_mensagem');
            insert into db_sysarqcamp values(393,1013406,10,0);
            ALTER TABLE protocolo.tipoproc  ADD COLUMN p51_mensagem varchar(250);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
        DELETE FROM db_sysarqcamp WHERE codcam = 1013406 AND codarq = 393;
        DELETE FROM db_syscampo WHERE codcam = 1013406;
        ALTER TABLE protocolo.tipoproc  DROP COLUMN p51_mensagem;
SQL
        );
    }
}

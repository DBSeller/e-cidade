<?php

use Classes\PostgresMigration;

class M17839LinkTipoProcesso extends PostgresMigration
{

    public function up(){
        $this->execute("
            insert into db_syscampo values(1013145,'p51_linksaibamais','varchar(255)','link exibido no frontend processo eletrônico','', 'p51_linksaibamais',255,'t','t','f',0,'text','p51_linksaibamais');
            insert into db_sysarqcamp values(393,1013145,8,0);
        ");

        $this->table('protocolo.tipoproc')->addColumn('p51_linksaibamais','string',['null'=>true])->save();
    }

    public function down(){
        $this->execute("
            DELETE FROM db_sysarqcamp WHERE codarq = 393 AND codcam = 1013145;
            DELETE FROM configuracoes.db_syscampo  WHERE codcam = 1013145;
        ");
        $this->table('protocolo.tipoproc')->removeColumn('p51_linksaibamais')->save();
    }
}

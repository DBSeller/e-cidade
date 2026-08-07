<?php

use Classes\PostgresMigration;

class M11599CompensacaoFase1 extends PostgresMigration
{
    public function up()
    {
        $sql = "
            alter table regracompensacao add column k155_database date;
            insert into db_syscampo values(1010005,'k155_database','date','Data base a ser usada na compensação automática.','null', 'Data base',10,'t','f','f',1,'text','Data base');
            delete from db_sysarqcamp where codarq = 3476;
            insert into db_sysarqcamp values(3476,19555,1,2354);
            insert into db_sysarqcamp values(3476,19556,2,0);
            insert into db_sysarqcamp values(3476,19576,3,0);
            insert into db_sysarqcamp values(3476,19558,4,0);
            insert into db_sysarqcamp values(3476,19560,5,0);
            insert into db_sysarqcamp values(3476,19562,6,0);
            insert into db_sysarqcamp values(3476,19564,7,0);
            insert into db_sysarqcamp values(3476,19568,8,0);
            insert into db_sysarqcamp values(3476,19575,9,0);
            insert into db_sysarqcamp values(3476,19569,10,0);
            insert into db_sysarqcamp values(3476,1010005,11,0);
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            alter table regracompensacao drop column k155_database;
            delete from db_sysarqcamp where codarq = 3476;
            delete from db_syscampo where codcam = 1010005;
            insert into db_sysarqcamp values(3476,19555,1,2354);
            insert into db_sysarqcamp values(3476,19556,2,0);
            insert into db_sysarqcamp values(3476,19576,3,0);
            insert into db_sysarqcamp values(3476,19558,4,0);
            insert into db_sysarqcamp values(3476,19560,5,0);
            insert into db_sysarqcamp values(3476,19562,6,0);
            insert into db_sysarqcamp values(3476,19564,7,0);
            insert into db_sysarqcamp values(3476,19568,8,0);
            insert into db_sysarqcamp values(3476,19575,9,0);
            insert into db_sysarqcamp values(3476,19569,10,0);
        ";
        $this->execute($sql);
    }
}

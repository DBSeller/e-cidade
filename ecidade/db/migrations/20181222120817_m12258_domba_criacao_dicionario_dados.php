<?php

use Classes\PostgresMigration;

class M12258DombaCriacaoDicionarioDados extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

insert into db_syscampo values(1010244,'c63_reduz','int4','Reduzido','0', 'Reduzido',10,'f','f','f',1,'text','Reduzido');
insert into db_sysarqcamp values(2741,15633,5,0);
insert into db_sysarqcamp values(813,1010244,11,0);

delete from db_sysforkey where codarq = 2741 and referen = 0;
insert into db_sysforkey values(2741,15633,1,773,0);
insert into db_sysforkey values(2741,15630,2,773,0);

delete from db_sysindices where codind = 2301;
insert into db_syscadind values(2301,15633,4);


insert into db_sysindices values(1008411,'conplanocontabancaria_reduz_in',2741,'0');
insert into db_syscadind values(1008411,15633,1);
delete from db_sysforkey where codarq = 813 and referen = 0;
insert into db_sysforkey values(813,1010244,1,773,0);
insert into db_sysforkey values(813,8061,2,773,0);


SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

delete from db_sysforkey where codcam in (1010244, 8061) and codarq = 813;
delete from db_syscadind where codind = 1008411;
delete from db_syscadind where codcam = 15633 and codind = 2301;
delete from db_sysindices where codind = 1008411;
delete from db_sysforkey where codarq = 2741 and codcam in (15633, 15630);
delete from db_sysarqcamp where codarq = 813 and codcam = 1010244;
delete from db_sysarqcamp where codarq = 2741 and codcam = 15633;
delete from db_syscampo where codcam = 1010244;



SQL_DOWN
);
    }

}

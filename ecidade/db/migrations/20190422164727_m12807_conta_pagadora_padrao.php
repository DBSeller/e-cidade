<?php

use Classes\PostgresMigration;

class M12807ContaPagadoraPadrao extends PostgresMigration
{

    public function up()
    {
        $sqlDD = <<<SQL
insert into db_syscampo values(1010437,'k29_contapadraoslip','int4','Conta padrão do slip','0', 'Conta padrão do slip',5,'f','f','f',1,'text','Conta padrão do slip');
insert into db_syscampodep values(1010437,'1173');
insert into db_sysarqcamp values(1503,1010437,12,0);
insert into db_sysforkey values(1503,1010437,1,212,0);
SQL;
        $this->execute($sqlDD);

        $sqlDML = <<<SQL
alter table caiparametro add k29_contapadraoslip integer;
alter table caiparametro add constraint caiparametro_contapadraoslip_fk foreign key (k29_contapadraoslip) references saltes;
SQL;
        $this->execute($sqlDML);

    }

    public function down()
    {
        $sqlDD = <<<SQL
delete from db_sysforkey   where codarq = 1503 and referen = 0;
delete from db_sysarqcamp  where codarq = 1503 and codcam = 1010437;
delete from db_syscampodep where codcam = 1010437;
delete from db_sysforkey   where codarq = 1503 and codcam = 1010437;
delete from db_syscampo    where codcam = 1010437;
SQL;
        $this->execute($sqlDD);

        $sqlDML = <<<SQL
alter table caiparametro drop k29_contapadraoslip;
SQL;
        $this->execute($sqlDML);

    }


}

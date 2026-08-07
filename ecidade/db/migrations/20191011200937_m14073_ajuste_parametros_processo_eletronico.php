<?php

use Classes\PostgresMigration;

class M14073AjusteParametrosProcessoEletronico extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upDdl();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downDdl();
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
            insert into db_syscampo values(1010765,'q150_alvarabaixorisco','int4','Codigo do alvara de baixo risco','0', 'Alvara de Baixo Risco',1,'f','f','f',1,'text','Alvara de Baixo Risco');
            insert into db_syscampo values(1010766,'q150_alvaramediorisco','int4','Código do alvara de médio risco','0', 'Alvara de Médio Risco',1,'f','f','f',1,'text','Alvara de Médio Risco');
            insert into db_syscampo values(1010767,'q150_alvaraaltorisco','int4','Código do alvará de alto risco','0', 'Alvara de Alto Risco',1,'f','f','f',1,'text','Alvara de Alto Risco');
            delete from db_sysarqcamp where codarq = 1010473;
            insert into db_sysarqcamp values(1010473,1010762,1,0);
            insert into db_sysarqcamp values(1010473,1010759,2,0);
            insert into db_sysarqcamp values(1010473,1010760,3,0);
            insert into db_sysarqcamp values(1010473,1010761,4,0);
            insert into db_sysarqcamp values(1010473,1010765,5,0);
            insert into db_sysarqcamp values(1010473,1010766,6,0);
            insert into db_sysarqcamp values(1010473,1010767,7,0);
            delete from db_sysprikey where codarq = 1010473;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010473,1010762,1,1010762);
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_sysprikey where codarq = 1010473;
            delete from db_sysarqcamp where codarq = 1010473;
            delete from db_syscampo where codcam in (1010765, 1010766, 1010767);
SQL
        );
    }

    public function upDdl()
    {
        $this->execute(<<<SQL
            ALTER TABLE issqn.parametroprocessoeletronico
              ADD COLUMN q150_alvarabaixorisco integer,
              ADD COLUMN q150_alvaramediorisco integer,
              ADD COLUMN q150_alvaraaltorisco integer,
             DROP COLUMN q150_tipoalvaraprovisorio;
SQL
        );
    }

    public function downDdl()
    {
        $this->execute(<<<SQL
            ALTER TABLE issqn.parametroprocessoeletronico
             DROP COLUMN q150_alvarabaixorisco,
             DROP COLUMN q150_alvaramediorisco,
             DROP COLUMN q150_alvaraaltorisco,
              ADD COLUMN q150_tipoalvaraprovisorio integer;
SQL
        );
    }
}

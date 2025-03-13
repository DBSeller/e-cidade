<?php

use Classes\PostgresMigration;

class M9811AssentamentoAbonoAfastamento extends PostgresMigration
{
    public function up() 
    {
        $sql = "
            INSERT INTO db_sysarquivo VALUES (1010243, 'assentamentoabonofalta', 'Tabela criarda para guardar o tempo de afastamento', '', '2017-11-30', '', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (29,1010243);
            INSERT INTO db_syscampo VALUES(1009540,'rh213 _codigo','int4','Sequencial do assenta','0', 'Código',10,'f','f','f',1,'text','');
            INSERT INTO db_syscampo VALUES(1009541,'rh213 _horainicio','varchar(10)','horário de inicio do rubrica do tipo afastamento','', 'Horário de início ',10,'f','t','f',0,'text','');
            INSERT INTO db_syscampo VALUES(1009542,'rh213 _horafim','varchar(10)','Horário de fim do assetamento da rubrica afastamento','', 'Horário de fim ',10,'f','t','f',0,'text','');
            INSERT INTO db_sysforkey VALUES(1010243,1009540,1,528,0);
                INSERT INTO db_sysarqcamp values(1010243,1009540,1,0);
                INSERT INTO db_sysarqcamp values(1010243,1009542,2,0);
                INSERT INTO db_sysarqcamp values(1010243,1009541,3,0);
                INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) values(1010243,1009540,1,1009540);

            CREATE TABLE recursoshumanos.assentamentoabonofalta(
               rh213_codigo       integer NOT NULL,
               rh213_horafim      varchar(10) NOT NULL,
               rh213_horainicio   varchar(10) NOT NULL 
            );

            ALTER TABLE recursoshumanos.assentamentoabonofalta
            ADD CONSTRAINT assentamentoabonofalta_codigo_fk FOREIGN KEY (rh213_codigo)
            REFERENCES assenta;
        ";

        $this->execute($sql); 
    }

    public function down() 
    {
        $sql = "
            DELETE FROM db_sysforkey  WHERE codarq =   1010243; 
            DELETE FROM db_sysprikey where codarq = 1010243;
            DELETE from db_sysarqcamp where codarq = 1010243;
            DELETE from db_syscampo where codcam IN (1009540, 1009541, 1009542);
            DELETE FROM db_sysarqmod  WHERE codarq =   1010243; 
            DELETE FROM db_sysarquivo  WHERE codarq =   1010243; 
            
            DROP TABLE IF EXISTS recursoshumanos.assentamentoabonofalta;
        ";

        $this->execute($sql);   
    }
}


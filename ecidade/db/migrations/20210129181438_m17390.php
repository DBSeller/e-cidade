<?php

use Classes\PostgresMigration;

class M17390 extends PostgresMigration
{  
    public function up()
    {
        $this->execute("CREATE TABLE cadastro.condominioprocesso (j179_sequencial INT PRIMARY KEY, j179_condominio INT, j179_processo INT) ");

        $this->execute("CREATE SEQUENCE if not exists cadastro.condominioprocesso_j179_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1");

        $this->execute("ALTER TABLE cadastro.condominioprocesso ADD CONSTRAINT condominioprocesso_j179_condominio_fk FOREIGN KEY (j179_condominio) REFERENCES cadastro.condominio(j107_sequencial)");
        $this->execute("ALTER TABLE cadastro.condominioprocesso ADD CONSTRAINT condominioprocesso_j179_processo_fk FOREIGN KEY (j179_processo) REFERENCES protocolo.protprocesso(p58_codproc)");

        $this->execute("insert into db_sysarquivo values (1010669, 'condominioprocesso', 'condominio condominio > condominiosgm condominio > condominioprocesso', 'j179', '2021-01-29', 'condominioprocesso', 0, 'f', 'f', 'f', 'f' )");
        $this->execute("insert into db_sysarqmod values (2,1010669)");
        $this->execute("insert into db_syscampo values(1012013,'j179_sequencial','int4','j179_sequencial','0', 'j179_sequencial',20,'f','f','t',1,'text','j179_sequencial')");
        $this->execute("insert into db_syscampo values(1012014,'j179_condominio','int4','j179_condominio','0', 'j179_condominio',20,'f','f','f',1,'text','j179_condominio')");
        $this->execute("insert into db_syscampo values(1012015,'j179_processo','int4','j179_processo','0', 'j179_processo',20,'f','f','f',1,'text','j179_processo')");
        $this->execute("insert into db_sysarqcamp values(1010669,1012013,1,0)");
        $this->execute("insert into db_sysarqcamp values(1010669,1012014,2,0)");
        $this->execute("insert into db_sysarqcamp values(1010669,1012015,3,0)");
        $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010669,1012013,1,1012013)");
        $this->execute("insert into db_sysforkey values(1010669,1012014,1,2533,1)");
        $this->execute("insert into db_sysforkey values(1010669,1012015,1,403,1)");
    }

    public function down(){
        $sql = <<<SQL
            delete from db_sysarqmod where codarq = 1010669;
            delete from db_sysarqcamp where codarq = 1010669;
            delete from db_sysforkey where codarq = 1010669;
            delete from db_syscampo where codcam = 1012013;
            delete from db_syscampo where codcam = 1012014;
            delete from db_syscampo where codcam = 1012015;
            delete from db_sysarquivo where  codarq = 1010669;
            delete from db_sysprikey where codarq = 1010669;
SQL;

        $sql2 = <<<SQL
            DROP TABLE cadastro.condominioprocesso;
SQL;
        $this->execute($sql);
        $this->execute($sql2);
    }
}



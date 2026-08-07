<?php

use Classes\PostgresMigration;

class M14073TabelaGrauRisco extends PostgresMigration
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
            insert into db_sysarquivo values (1010474, 'processoeletronicograurisco', 'Tabela que guarda o grau de risco selecionado em um processo', 'q151', '2019-10-14', 'Grau de risco processo eletronico', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010474);
            insert into db_syscampo values(1010768,'q151_sequencial','int8','Sequencial da tabela','0', 'Sequencial',1,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1010769,'q151_processo','int8','Sequencial do processo','0', 'Processo',1,'f','f','f',1,'text','Processo');
            insert into db_syscampo values(1010770,'q151_graurisco','char(1)','Grau de risco do processo','', 'Grau de Risco',1,'f','t','f',0,'text','Grau de Risco');
            delete from db_sysarqcamp where codarq = 1010474;
            insert into db_sysarqcamp values(1010474,1010768,1,0);
            insert into db_sysarqcamp values(1010474,1010769,2,0);
            insert into db_sysarqcamp values(1010474,1010770,3,0);
            delete from db_sysprikey where codarq = 1010474;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010474,1010768,1,1010768);
            delete from db_sysforkey where codarq = 1010474 and referen = 0;
            insert into db_sysforkey values(1010474,1010769,1,403,0);
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_sysforkey where codarq = 1010474;
            delete from db_sysprikey where codarq = 1010474;
            delete from db_sysarqcamp where codarq = 1010474;
            delete from db_syscampo where codcam in (1010768,1010769,1010770);
            delete from db_sysarqmod where codarq = 1010474;
            delete from db_sysarquivo where codarq = 1010474;
SQL
        );
    }

    public function upDdl()
    {
        $this->execute(<<<SQL
            CREATE SEQUENCE issqn.processoeletronicograurisco_q151_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE issqn.processoeletronicograurisco (
                q151_sequencial integer not null,
                q151_processo integer not null,
                q151_graurisco char(1) not null,
                CONSTRAINT processoeletronicograurisco_sequ_pk PRIMARY KEY (q151_sequencial)
            );

            ALTER TABLE processoeletronicograurisco
              ADD CONSTRAINT processoeletronicograurisco_processo_fk FOREIGN KEY (q151_processo)
       REFERENCES protprocesso;
SQL
        );
    }

    public function downDdl()
    {
        $this->execute(<<<SQL
            drop sequence issqn.processoeletronicograurisco_q151_sequencial_seq;
            drop table issqn.processoeletronicograurisco;
SQL
        );
    }
}

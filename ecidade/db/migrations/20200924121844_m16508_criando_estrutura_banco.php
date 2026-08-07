<?php

use Classes\PostgresMigration;

class M16508CriandoEstruturaBanco extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upDDL();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downDDL();
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL

            insert into db_sysarquivo values (1010618, 'isssetorfiscal', 'tabela de ligacao issbase setorfical', 'q177', '2020-09-24', 'Alvara - Setor Fiscal', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (3,1010618);
            insert into db_syscampo values(1011822,'q177_sequencial','int8','Sequencial da tabela','0', 'Sequencial',1,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1011823,'q177_issbase','int8','Inscricao do registro (issbase)','0', 'Inscricao',1,'f','f','f',1,'text','Inscricao');
            insert into db_syscampo values(1011824,'q177_setorfiscal','int8','Referencia para registro da tabela setorfiscal','0', 'Setor Fiscal',1,'f','f','f',1,'text','Setor Fiscal');
            insert into db_syscampo values(1011825,'q02_protocolojuntacomercial','int8','Protocolo da juntar comercial','0', 'Prot. Junta',1,'f','f','f',1,'text','Protocolo Junta Comercial');
            update db_syscampo set nomecam = 'q02_protocolojuntacomercial', conteudo = 'varchar(100)', descricao = 'Protocolo da juntar comercial', valorinicial = '0', rotulo = 'Prot. Junta', nulo = 'f', tamanho = 100, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Protocolo Junta Comercial' where codcam = 1011825;
            delete from db_syscampodep where codcam = 1011825;
            delete from db_syscampodef where codcam = 1011825;
            insert into db_syscampo values(1011826,'q02_tempofuncionamento','float8','Tempo de funcionamento em horas','0', 'Tempo Funcionamento',100,'f','f','f',4,'text','Tempo Funcionamento (horas)');
            delete from db_sysarqcamp where codarq = 41;
            insert into db_sysarqcamp values(41,203,1,80);
            insert into db_sysarqcamp values(41,204,2,0);
            insert into db_sysarqcamp values(41,212,3,0);
            insert into db_sysarqcamp values(41,209,4,0);
            insert into db_sysarqcamp values(41,210,5,0);
            insert into db_sysarqcamp values(41,208,6,0);
            insert into db_sysarqcamp values(41,207,7,0);
            insert into db_sysarqcamp values(41,211,8,0);
            insert into db_sysarqcamp values(41,213,9,0);
            insert into db_sysarqcamp values(41,214,10,0);
            insert into db_sysarqcamp values(41,206,11,0);
            insert into db_sysarqcamp values(41,2485,12,0);
            insert into db_sysarqcamp values(41,6141,13,0);
            insert into db_sysarqcamp values(41,6142,14,0);
            insert into db_sysarqcamp values(41,6303,15,0);
            insert into db_sysarqcamp values(41,1011655,16,0);
            insert into db_sysarqcamp values(41,1011825,17,0);
            insert into db_sysarqcamp values(41,1011826,18,0);
            delete from db_sysarqcamp where codarq = 1010618;
            insert into db_sysarqcamp values(1010618,1011822,1,0);
            insert into db_sysarqcamp values(1010618,1011823,2,0);
            insert into db_sysarqcamp values(1010618,1011824,3,0);
            delete from db_sysprikey where codarq = 1010618;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010618,1011822,1,1011822);
            delete from db_sysforkey where codarq = 1010618 and referen = 0;
            insert into db_sysforkey values(1010618,1011823,1,41,0);
            delete from db_sysforkey where codarq = 1010618 and referen = 0;
            insert into db_sysforkey values(1010618,1011824,1,1300,0);
            insert into db_sysindices values(1008613,'setorfiscal_issbase_unq',1010618,'1');
            insert into db_syscadind values(1008613,1011823,1);

SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL

            DELETE FROM db_syscadind where codcam = 1011823;
            DELETE FROM db_sysindices where codind = 1008613;
            DELETE FROM db_sysforkey where codarq = 1010618;
            DELETE FROM db_sysprikey where codarq = 1010618;
            delete from db_sysarqcamp where codarq = 1010618;
            delete from db_sysarqcamp where codcam in (1011825, 1011826);
            DELETE FROM db_syscampo where codcam in (1011822, 1011823, 1011824, 1011825, 1011826);
            DELETE FROM db_sysarqmod where codarq = 1010618;
            DELETE FROM db_sysarquivo where codarq = 1010618;

SQL
        );
    }

    private function upDDL()
    {
        $this->execute(<<<SQL

            CREATE SEQUENCE issqn.isssetorfiscal_q177_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE issqn.isssetorfiscal (
                q177_sequencial integer NOT NULL DEFAULT nextval('issqn.isssetorfiscal_q177_sequencial_seq'),
                q177_issbase integer NOT NULL,
                q177_setorfiscal integer NOT NULL,
                CONSTRAINT setorfiscal_issbase_unq UNIQUE(q177_issbase),
                CONSTRAINT isssetorfiscal_sequ_pk PRIMARY KEY(q177_sequencial)
            );

            ALTER TABLE issqn.isssetorfiscal
            ADD CONSTRAINT isssetorfiscal_issbase_fk FOREIGN KEY (q177_issbase)
            REFERENCES issqn.issbase;

            ALTER TABLE issqn.isssetorfiscal
            ADD CONSTRAINT isssetorfiscal_setorfiscal_fk FOREIGN KEY (q177_setorfiscal)
            REFERENCES cadastro.setorfiscal;

            ALTER TABLE issqn.issbase 
              ADD COLUMN q02_protocolojuntacomercial VARCHAR(100);

            ALTER TABLE issqn.issbase 
              ADD COLUMN q02_tempofuncionamento DECIMAL(4, 2);

SQL
        );
    }

    private function downDDL()
    {
        $this->execute(<<<SQL

            DROP TABLE issqn.isssetorfiscal;

            DROP SEQUENCE issqn.isssetorfiscal_q177_sequencial_seq;

            ALTER TABLE issqn.issbase 
             DROP COLUMN q02_protocolojuntacomercial;

            ALTER TABLE issqn.issbase 
             DROP COLUMN q02_tempofuncionamento;

SQL
        );
    }
}

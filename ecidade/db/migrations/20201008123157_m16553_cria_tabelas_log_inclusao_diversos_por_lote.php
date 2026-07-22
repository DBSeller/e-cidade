<?php

use Classes\PostgresMigration;

class M16553CriaTabelasLogInclusaoDiversosPorLote extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
        -- Cria tabela diversoslotelog e atribui ao módulo diversos
        INSERT INTO db_sysarquivo VALUES (1010625, 'diversoslotelog', 'Diversos Lote Log', 'dv06', '2020-10-08', 'Diversos Lote Log', 0, 'f', 'f', 'f', 'f' );
        INSERT INTO db_sysarqmod VALUES (27,1010625);
        
        -- Cria tabela diversoslotelogreg e atribui ao módulo diversos
        INSERT INTO db_sysarquivo VALUES (1010626, 'diversoslotelogreg', 'Diversos Lote Log Registro', 'dv07', '2020-10-08', 'Diversos Lote Log Registro', 0, 'f', 'f', 'f', 'f' );
        INSERT INTO db_sysarqmod VALUES (27,1010626);

        -- Cria campos da tabela diversoslotelog
        INSERT INTO db_syscampo VALUES(1011852,'dv06_sequencial','int4','Campo sequencial da tabela diversoslotelog','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
        INSERT INTO db_syscampo VALUES(1011853,'dv06_usuario','int4','Usuario atual na tabela diversoslotelog','0', 'Usuario',10,'f','f','f',1,'text','Usuario');
        INSERT INTO db_syscampo VALUES(1011854,'dv06_datainclusao','date','Data Inclusão de diversos em lote tabela diversoslotelog','null', 'Data Inclusão',10,'f','f','f',1,'text','Data Inclusão');
        INSERT INTO db_syscampo VALUES(1011855,'dv06_horainclusao','date','Hora Inclusão tabela diversoslotelog','null', 'Hora Inclusão',10,'f','f','f',1,'text','Hora Inclusão');
        INSERT INTO db_syscampo VALUES(1011856,'dv06_arquivocsv','varchar(255)','Nome Arquivo CSV com planilha de dados na tabela diversoslotelog','', 'Nome Arquivo',255,'f','t','f',0,'text','Nome Arquivo');

        -- Cria campos da tabela diversoslotelogreg
        INSERT INTO db_syscampo VALUES(1011857,'dv07_sequencial','int4','Campo sequencial da tabela diversoslotelogreg','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
        INSERT INTO db_syscampo VALUES(1011858,'dv07_diversoslotelog','int4','Chave estrangeira que referencia a tabela diversoslotelog','0', 'diversoslotelog',10,'f','f','f',1,'text','diversoslotelog');
        INSERT INTO db_syscampo VALUES(1011859,'dv07_tipodado','varchar(255)','Tipo de Dado passado pela planilha csv na tabela diversoslotelogreg','', 'Tipo de Dado',255,'f','t','f',0,'text','Tipo de Dado');
        INSERT INTO db_syscampo VALUES(1011860,'dv07_dadoplanilha','int4','Dado da Planilha CSV da inclusão de diversos em lote na tabela diversoslotelogreg','0', 'Dado Planilha',10,'f','f','f',1,'text','Dado Planilha');
        INSERT INTO db_syscampo VALUES(1011861,'dv07_coddiv','int4','Chave estrangeira que referencia a tabela diversos','0', 'coddiv',10,'f','f','f',1,'text','coddiv');

        -- Vincula campos da tabela diversoslotelog
        INSERT INTO db_sysarqcamp VALUES(1010625,1011852,1,0);
        INSERT INTO db_sysarqcamp VALUES(1010625,1011853,2,0);
        INSERT INTO db_sysarqcamp VALUES(1010625,1011854,3,0);
        INSERT INTO db_sysarqcamp VALUES(1010625,1011855,4,0);
        INSERT INTO db_sysarqcamp VALUES(1010625,1011856,5,0);

        -- Vincula campos da tabela diversoslotelogreg
        INSERT INTO db_sysarqcamp VALUES(1010626,1011857,1,0);
        INSERT INTO db_sysarqcamp VALUES(1010626,1011858,2,0);
        INSERT INTO db_sysarqcamp VALUES(1010626,1011859,3,0);
        INSERT INTO db_sysarqcamp VALUES(1010626,1011860,4,0);
        INSERT INTO db_sysarqcamp VALUES(1010626,1011861,5,0);

        -- Cria PKs das tabelas
        INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010625,1011852,1,1011852);
        INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010626,1011857,1,1011857);

        -- Cria FKs da tabela diversoslotelog
        INSERT INTO db_sysforkey VALUES(1010625,1011853,1,109,0);

        -- Cria FKs da tabela diversoslotelogreg
        INSERT INTO db_sysforkey VALUES(1010626,1011858,1,1010625,0);
        INSERT INTO db_sysforkey VALUES(1010626,1011861,1,372,0);

        -- Cria Sequences e atribui nas tabelas
        INSERT INTO db_syssequencia VALUES(1000975, 'diversoslotelog_dv06_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        UPDATE db_sysarqcamp SET codsequencia = 1000975 WHERE codarq = 1010625 AND codcam = 1011852;

        INSERT INTO db_syssequencia VALUES(1000976, 'diversoslotelogreg_dv07_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        UPDATE db_sysarqcamp SET codsequencia = 1000976 WHERE codarq = 1010626 AND codcam = 1011857;

SQL
        );
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
        -- Cria tabela para registro do arquivo .CSV de inclusão de diversos em lote
        CREATE TABLE diversos.diversoslotelog (
            dv06_sequencial SERIAL PRIMARY KEY,
            dv06_usuario INT,
            dv06_datainclusao DATE,
            dv06_horainclusao TIME,
            dv06_arquivocsv VARCHAR(255)
        );

        ALTER TABLE diversos.diversoslotelog ADD CONSTRAINT "diversoslotelog_dv06_usuario_fk" FOREIGN KEY ("dv06_usuario") REFERENCES "db_usuarios"("id_usuario");

        -- Cria tabela para registro dos dados do arquivo .CSV de inclusão de diversos em lote
        CREATE TABLE diversos.diversoslotelogreg (
            dv07_sequencial SERIAL PRIMARY KEY,
            dv07_diversoslotelog INT,
            dv07_tipodado VARCHAR(255),
            dv07_dadoplanilha INT,
            dv07_coddiv INT
        );

        ALTER TABLE diversos.diversoslotelogreg ADD CONSTRAINT "diversoslotelogreg_dv07_diversoslotelog_fk" FOREIGN KEY ("dv07_diversoslotelog") REFERENCES "diversoslotelog"("dv06_sequencial");
        ALTER TABLE diversos.diversoslotelogreg ADD CONSTRAINT "diversoslotelogreg_dv07_coddiv_fk" FOREIGN KEY ("dv07_coddiv") REFERENCES "diversos"("dv05_coddiver");
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
        -- Remove Sequences e vinculos das tabelas
        DELETE FROM db_sysarqcamp WHERE codsequencia = 1000976 AND codarq = 1010626 AND codcam = 1011857;
        DELETE FROM db_sysarqcamp WHERE codsequencia = 1000975 AND codarq = 1010625 AND codcam = 1011852;

        DELETE FROM db_syssequencia WHERE codsequencia = 1000976;
        DELETE FROM db_syssequencia WHERE codsequencia = 1000975;

        -- Remove FKs da tabela diversoslotelog
        DELETE FROM db_sysforkey WHERE codarq = 1010625;

        -- Remove FKs da tabela diversoslotelogreg
        DELETE FROM db_sysforkey WHERE codarq = 1010626;

        -- Remove PKs das tabelas
        DELETE FROM db_sysprikey WHERE codarq = 1010626;
        DELETE FROM db_sysprikey WHERE codarq = 1010625;

        -- Remove vinculo de campos das tabelas
        DELETE FROM db_sysarqcamp WHERE codarq = 1010626;
        DELETE FROM db_sysarqcamp WHERE codarq = 1010625;

        -- Remove campos da tabela diversoslotelogreg
        DELETE FROM db_syscampo WHERE codcam IN (1011857,1011858,1011859,1011860,1011861);

        -- Remove campos da tabela diversoslotelog
        DELETE FROM db_syscampo WHERE codcam IN (1011852,1011853,1011854,1011855,1011856);

        -- Remove vinculo ao módulo diversos e remove tabela diversoslotelogreg
        DELETE FROM db_sysarqmod WHERE codmod = 27 AND codarq = 1010626;
        DELETE FROM db_sysarquivo WHERE codarq = 1010626;

        -- Remove vinculo ao módulo diversos e remove tabela diversoslotelog
        DELETE FROM db_sysarqmod WHERE codmod = 27 AND codarq = 1010625;
        DELETE FROM db_sysarquivo WHERE codarq = 1010625;

SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
        DROP TABLE diversos.diversoslotelog CASCADE;
        DROP TABLE diversos.diversoslotelogreg CASCADE;
SQL
        );
    }
}

<?php

use Classes\PostgresMigration;

class M12070InformacaoDiaria extends PostgresMigration
{
    public function up()
    {
        $this->dicionarioDadosUp();
        $this->alteraTabelaUp();
        $this->criaTabelaUp();
    }

    public function down()
    {
        $this->dicionarioDadosDown();
        $this->alteraTabelaDown();
        $this->criaTabelaDown();
    }

    public function dicionarioDadosUp()
    {
        $sql = <<<SQL
            insert into db_syscampo values(1010019,'e44_diaria','bool','Campo identificador se o evento é do tipo diária.','f', 'Diária',1,'t','f','f',5,'text','Diária');
            insert into db_syscampo values(1010020,'e446_sequencial','int4','Código sequencial da tabela','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');
            insert into db_syscampo values(1010021,'e446_empprestaitem','int4','Código de vinculo da prestação de contas','0', 'Código do item da prestação',10,'f','f','f',1,'text','Código do item da prestação');
            insert into db_syscampo values(1010022,'e446_regist','int4','Código da Matrícula','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
            insert into db_syscampo values(1010023,'e446_datainicio','date','Período inicial','null', 'Período Inicial',10,'f','f','f',1,'text','Período Inicial');
            insert into db_syscampo values(1010024,'e446_datafim','date','Período Final','null', 'Período Final',10,'f','f','f',1,'text','Período Final');
            insert into db_syscampo values(1010025,'e446_motivo','text','Motivo da diária','', 'Motivo',1,'f','f','f',0,'text','Motivo');
            insert into db_syscampo values(1010026,'e446_destino','text','Destino da diária','', 'Destino',1,'f','f','f',0,'text','Destino');
            insert into db_syscampo values(1010027,'e446_quantidade','int4','Quantidade de diárias dentro do empenho.','0', 'Quantidade',10,'t','f','f',1,'text','Quantidade');
            insert into db_sysarquivo values (1010329, 'empprestaitemdiaria', 'Tabela de informações complementares de prestação de contas quando a prestação for de diárias', 'e446', '2018-10-17', 'empprestaitemdiaria', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (38,1010329);
            delete from db_sysarqcamp where codarq = 1010329;
            insert into db_sysarqcamp values(1010329,1010020,1,0);
            insert into db_sysarqcamp values(1010329,1010021,2,0);
            insert into db_sysarqcamp values(1010329,1010022,3,0);
            insert into db_sysarqcamp values(1010329,1010023,4,0);
            insert into db_sysarqcamp values(1010329,1010024,5,0);
            insert into db_sysarqcamp values(1010329,1010025,6,0);
            insert into db_sysarqcamp values(1010329,1010026,7,0);
            insert into db_sysarqcamp values(1010329,1010027,8,0);
            delete from db_sysprikey where codarq = 1010329;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010329,1010020,1,1010020);
            delete from db_sysforkey where codarq = 1010329 and referen = 0;
            insert into db_sysforkey values(1010329,1010021,1,1037,0);
            insert into db_syssequencia values(1000773, 'empprestaitemdiaria_e446_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000773 where codarq = 1010329 and codcam = 1010020;
            delete from db_sysarqcamp where codarq = 1038;
            insert into db_sysarqcamp values(1038,6355,1,211);
            insert into db_sysarqcamp values(1038,6356,2,0);
            insert into db_sysarqcamp values(1038,6357,3,0);
            insert into db_sysarqcamp values(1038,20881,4,0);
            insert into db_sysarqcamp values(1038,1010019,5,0);
            delete from db_sysarqcamp where codarq = 1010329;
            insert into db_sysarqcamp values(1010329,1010020,1,1000773);
            insert into db_sysarqcamp values(1010329,1010021,2,0);
            insert into db_sysarqcamp values(1010329,1010022,3,0);
            insert into db_sysarqcamp values(1010329,1010023,4,0);
            insert into db_sysarqcamp values(1010329,1010024,5,0);
            insert into db_sysarqcamp values(1010329,1010025,6,0);
            insert into db_sysarqcamp values(1010329,1010026,7,0);
            insert into db_sysarqcamp values(1010329,1010027,8,0);
            insert into db_syscampo values(1010028,'e446_movimento','int4','Código do movimento','0', 'Código do movimento',10,'f','f','f',1,'text','Código do movimento');
            insert into db_syscampo values(1010029,'e446_tipodiaria','varchar(50)','Tipo de diária','', 'Tipo de Diária',50,'f','f','f',0,'text','Tipo de Diária');
            delete from db_sysarqcamp where codarq = 1010329;
            insert into db_sysarqcamp values(1010329,1010020,1,1000773);
            insert into db_sysarqcamp values(1010329,1010021,2,0);
            insert into db_sysarqcamp values(1010329,1010022,3,0);
            insert into db_sysarqcamp values(1010329,1010023,4,0);
            insert into db_sysarqcamp values(1010329,1010024,5,0);
            insert into db_sysarqcamp values(1010329,1010025,6,0);
            insert into db_sysarqcamp values(1010329,1010026,7,0);
            insert into db_sysarqcamp values(1010329,1010027,8,0);
            insert into db_sysarqcamp values(1010329,1010028,9,0);
            insert into db_sysarqcamp values(1010329,1010029,10,0);

SQL;
        $this->execute($sql);
    }

    public function dicionarioDadosDown()
    {
        $sql = <<<SQL
            delete from db_sysarqcamp where codarq = 1010329;
            delete from db_sysarqcamp where codarq = 1038;
            insert into db_sysarqcamp values(1038,6355,1,211);
            insert into db_sysarqcamp values(1038,6356,2,0);
            insert into db_sysarqcamp values(1038,6357,3,0);
            insert into db_sysarqcamp values(1038,20881,4,0);
            delete from db_syssequencia where codsequencia = 1000773;
            delete from db_sysprikey where codarq = 1010329;
            delete from db_sysarqcamp where codarq = 1010329;
            delete from db_sysarqmod where codarq = 1010329;
            delete from db_sysforkey where codarq = 1010329;
            delete from db_sysarquivo where codarq = 1010329;
        
            delete from db_syscampo where codcam =1010019;
            delete from db_syscampo where codcam =1010020;
            delete from db_syscampo where codcam =1010021;
            delete from db_syscampo where codcam =1010022;
            delete from db_syscampo where codcam =1010023;
            delete from db_syscampo where codcam =1010024;
            delete from db_syscampo where codcam =1010025;
            delete from db_syscampo where codcam =1010026;
            delete from db_syscampo where codcam =1010027;
            delete from db_syscampo where codcam =1010028;
            delete from db_syscampo where codcam =1010029;
SQL;
        $this->execute($sql);
    }

    public function alteraTabelaUp()
    {
        $sql = <<<SQL
        alter table empenho.empprestatip add column e44_diaria boolean default false;
SQL;
        $this->execute($sql);
    }

    public function alteraTabelaDown()
    {
        $sql = <<<SQL
        alter table empenho.empprestatip drop column e44_diaria;
SQL;
        $this->execute($sql);
    }

    public function criaTabelaUp()
    {
        $sql = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE empprestaitemdiaria_e446_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            -- TABELAS E ESTRUTURA
            -- Módulo: empenho
            CREATE TABLE empenho.empprestaitemdiaria(
                e446_sequencial     int4 NOT NULL default 0,
                e446_empprestaitem      int4 NOT NULL default 0,
                e446_regist     int4 NOT NULL default 0,
                e446_datainicio     date NOT NULL default null,
                e446_datafim        date NOT NULL default null,
                e446_motivo     text NOT NULL ,
                e446_destino        text NOT NULL ,
                e446_quantidade     int4 default 0,
                e446_movimento      int4 NOT NULL default 0,
                e446_tipodiaria     varchar(50) ,
                CONSTRAINT empprestaitemdiaria_sequ_pk PRIMARY KEY (e446_sequencial)
            );

            ALTER TABLE empprestaitemdiaria
            ADD CONSTRAINT empprestaitemdiaria_empprestaitem_fk FOREIGN KEY (e446_empprestaitem)
            REFERENCES empprestaitem;
SQL;
        $this->execute($sql);
    }

    public function criaTabelaDown()
    {
        $sql = <<<SQL
        DROP TABLE empenho.empprestaitemdiaria;
        DROP SEQUENCE empprestaitemdiaria_e446_sequencial_seq;
SQL;
        $this->execute($sql);
    }
}

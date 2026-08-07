<?php

use Classes\PostgresMigration;

class M15810IntegracaoVisualizadorDocumentos extends PostgresMigration
{
    public function up() 
    {
        $sql = <<<SQL

            -- Adicionando campo ordem na tabela protprocessodocumento
            insert into db_syscampo values(1011248,'p01_ordem','int4','ordenação dos documentos do processo.','0', 'ordem',10,'t','f','f',1,'text','ordem');
            insert into db_sysarqcamp values(3649,1011248,10,0);
    
            ALTER TABLE protocolo.protprocessodocumento ADD COLUMN p01_ordem int default 0;

            -- Adicionando tabela tipo processo
            insert into db_sysarquivo values (1010559, 'tipoprocesso', 'tipos de processos. inicialmente manual, eletrônico e ouvidoria.', 'p109', '2020-05-05', 'tipo de processo', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (4,1010559);
            insert into db_syscampo values(1011254,'p109_sequencial','int4','código sequencial da tabela.','0', 'código',10,'f','f','f',1,'text','código');
            insert into db_syscampo values(1011255,'p109_nome','varchar(255)','nome do tipo de processo.','', 'nome',255,'f','t','f',0,'text','nome');
            insert into db_sysarqcamp values(1010559,1011254,1,0);
            insert into db_sysarqcamp values(1010559,1011255,2,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010559,1011254,1,1011255);
            insert into db_syssequencia values(1000908, 'tipoprocesso_p109_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000908 where codarq = 1010559 and codcam = 1011254;

            CREATE SEQUENCE protocolo.tipoprocesso_p109_sequencial_seq 
                    INCREMENT 1
                    MINVALUE 1
                    MAXVALUE 9223372036854775807
                    START 1
                    CACHE 1;

            CREATE TABLE protocolo.tipoprocesso(
                    p109_sequencial int default 0,
                    p109_nome varchar(255) not null,
                    CONSTRAINT tipoprocesso_sequ_pk PRIMARY KEY (p109_sequencial));

            INSERT INTO protocolo.tipoprocesso values (1, 'MANUAL');
            INSERT INTO protocolo.tipoprocesso values (2, 'ELETRONICO');
            INSERT INTO protocolo.tipoprocesso values (3, 'OUVIDORIA');


            -- Adicionado campo tipoprocesso na tabela protprocesso
            insert into db_syscampo values(1011256,'p58_tipoprocesso','int4','vínculo com o tabela tipoprocesso.','0', 'tipo de processo',10,'f','f','f',1,'text','tipo de processo');
            insert into db_sysarqcamp values(403,1011256,17,0);
            insert into db_sysforkey values(403,1011256,1,1010559,0);

            ALTER TABLE protocolo.protprocesso ADD COLUMN p58_tipoprocesso int default 1;
            ALTER TABLE protocolo.protprocesso
                    ADD CONSTRAINT protprocesso_p58_tipoprocesso_fk FOREIGN KEY (p58_tipoprocesso)
                    REFERENCES tipoprocesso;
SQL;
        $this->execute($sql);
    }

    public function down() 
    {
        $sql = <<<SQL
            delete from db_syssequencia where codsequencia = 1000908;
            delete from db_sysprikey where codarq = 1010559;
            delete from db_sysforkey where codcam = 1011256;
            delete from db_sysarqcamp where codcam in (1011248, 1011254, 1011255, 1011256);
            delete from db_syscampo where codcam in (1011248, 1011254, 1011255, 1011256);
            delete from db_sysarqmod where codarq in (1010559);
            delete from db_sysarquivo where codarq in (1010559);

            ALTER TABLE protocolo.protprocessodocumento DROP COLUMN p01_ordem;
            ALTER TABLE protocolo.protprocesso DROP COLUMN p58_tipoprocesso;
            
            DROP TABLE protocolo.tipoprocesso;
            DROP SEQUENCE protocolo.tipoprocesso_p109_sequencial_seq;
SQL;
        $this->execute($sql);
    }

}

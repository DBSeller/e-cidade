<?php

use Classes\PostgresMigration;

class CategoriasTipoProcesso extends PostgresMigration
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

    public function upDicionario()
    {
        $sql = <<<SQL_UP
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228152 ,'Categoria de Tipo de Processo' ,'Categoria de Tipo de Processo' ,'ouv1_categoriatipoprocesso001.php' ,'1' ,'1' ,'Agrupamento dos tipos de processo' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 7842 ,228152 ,7 ,7837 );

            insert into db_sysarquivo values (1010459, 'categoriatipoproc', 'Guarda as categorias de tipo de processo.', 'p104', '2019-07-24', 'Categoria de Tipo de Processo', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (4,1010459);
            insert into db_syscampo values(1010627,'p104_sequencial','int4','Sequencial da tabela categoriatipoproc.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1010628,'p104_nome','varchar(100)','Nome da categoria.','', 'Nome',100,'f','t','f',0,'text','Nome');
            insert into db_syscampo values(1010638,'p104_descricao','text','Descrição detalhada sobre a categoria criada.','', 'Descrição da Categoria',1,'t','t','f',0,'text','Descrição da Categoria');
            insert into db_sysarqcamp values(1010459,1010627,1,0);
            insert into db_sysarqcamp values(1010459,1010628,2,0);
            insert into db_sysarqcamp values(1010459,1010638,3,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010459,1010627,1,1010628);
            insert into db_syssequencia values(1000844, 'categoriatipoproc_p104_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000844 where codarq = 1010459 and codcam = 1010627;
            
            insert into db_sysarquivo values (1010460, 'categoriatipoprocvinculo', 'Vínculo dos tipos de processo com a categoria.', 'p105', '2019-07-24', 'Categoria de Tipo de Processo Vínculo', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (4,1010460);
            insert into db_syscampo values(1010629,'p105_sequencial','int4','Sequencial da tabela categoriatipoprocvinculo.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1010630,'p105_categoriatipoproc','int4','Categoria de Tipo de Processo.','0', 'Categoria de Tipo de Processo',10,'f','f','f',1,'text','Categoria de Tipo de Processo');
            insert into db_syscampo values(1010631,'p105_tipoproc','int4','Tipo de Processo.','0', 'Tipo de Processo',10,'f','f','f',1,'text','Tipo de Processo');
            insert into db_sysarqcamp values(1010460,1010629,1,0);
            insert into db_sysarqcamp values(1010460,1010630,2,0);
            insert into db_sysarqcamp values(1010460,1010631,3,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010460,1010630,1,1010629);
            insert into db_sysforkey values(1010460,1010630,1,1010459,0);
            insert into db_sysforkey values(1010460,1010631,1,393,0);
            insert into db_syssequencia values(1000845, 'categoriatipoprocvinculo_p105_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000845 where codarq = 1010460 and codcam = 1010629;
            insert into db_sysindices values(1008483,'categoriatipoprocvinculo_categoriatipoproc_tipoproc_in',1010460,'1');
            insert into db_syscadind values(1008483,1010630,1);
            insert into db_syscadind values(1008483,1010631,2);
SQL_UP;

        $this->execute($sql);
    }

    public function upDDL()
    {
        $sql = <<<SQL_UP
            -- Criando  sequences
            CREATE SEQUENCE protocolo.categoriatipoproc_p104_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            
            CREATE SEQUENCE protocolo.categoriatipoprocvinculo_p105_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            
            -- TABELAS E ESTRUTURA
            
            -- Módulo: protocolo
            CREATE TABLE protocolo.categoriatipoproc(
            p104_sequencial		int4 default nextval('protocolo.categoriatipoproc_p104_sequencial_seq'),
            p104_nome varchar(100) not null,
            p104_descricao text,
            CONSTRAINT categoriatipoproc_nome_pk PRIMARY KEY (p104_sequencial));
            
            
            -- Módulo: protocolo
            CREATE TABLE protocolo.categoriatipoprocvinculo(
            p105_sequencial		int4 default nextval('protocolo.categoriatipoprocvinculo_p105_sequencial_seq'),
            p105_categoriatipoproc int4 not null,
            p105_tipoproc int4 not null,
            CONSTRAINT categoriatipoprocvinculo_cate_pk PRIMARY KEY (p105_sequencial));
            
            
            
            
            -- CHAVE ESTRANGEIRA
            
            
            ALTER TABLE categoriatipoprocvinculo
            ADD CONSTRAINT categoriatipoprocvinculo_categoriatipoproc_tipoproc_fk FOREIGN KEY (p105_categoriatipoproc)
            REFERENCES categoriatipoproc;
            
            ALTER TABLE categoriatipoprocvinculo
            ADD CONSTRAINT categoriatipoprocvinculo_tipoproc_fk FOREIGN KEY (p105_tipoproc)
            REFERENCES tipoproc;
            
            CREATE UNIQUE INDEX categoriatipoprocvinculo_categoriatipoproc_tipoproc_in ON categoriatipoprocvinculo(p105_categoriatipoproc,p105_tipoproc);
SQL_UP;

        $this->execute($sql);
    }

    public function downDicionario()
    {
        $sql = <<<SQL_DOWN
            delete from db_menu where id_item_filho = 228152 AND modulo = 7837;
            delete from db_itensmenu where id_item = 228152;

            delete from db_syssequencia where codsequencia = 1000844;
            delete from db_sysprikey where codarq = 1010459;
            delete from db_sysarqcamp where codarq = 1010459;
            delete from db_syscampo where codcam in(1010627, 1010628, 1010638);
            delete from db_sysarqmod where codmod = 4 and codarq = 1010459;
            delete from db_sysarquivo where codarq = 1010459;
            
            delete from db_syssequencia where codsequencia = 1000845;
            delete from db_sysforkey where codarq = 1010460;
            delete from db_sysprikey where codarq = 1010460;
            delete from db_sysarqcamp where codarq = 1010460;
            delete from db_syscampo where codcam in(1010629, 1010630, 1010631);
            delete from db_sysarqmod where codmod = 4 and codarq = 1010460;
            delete from db_sysarquivo where codarq = 1010460;
            delete from db_syscadind where codind = 1008483;
            delete from db_sysindices where codind = 1008483;
SQL_DOWN;

        $this->execute($sql);
    }

    public function downDDL()
    {
        $sql = <<<SQL_DOWN
            --DROP TABLE:
            DROP TABLE IF EXISTS categoriatipoproc CASCADE;
            DROP TABLE IF EXISTS categoriatipoprocvinculo CASCADE;
            --Criando drop sequences
            DROP SEQUENCE IF EXISTS categoriatipoproc_p104_sequencial_seq;
            DROP SEQUENCE IF EXISTS categoriatipoprocvinculo_p105_sequencial_seq;
            
            DROP INDEX categoriatipoprocvinculo_categoriatipoproc_tipoproc_in;
SQL_DOWN;

        $this->execute($sql);
    }
}

<?php

use Classes\PostgresMigration;

class M13397IncorporacaoPluginsPontoProcessamentoCadastroRelogio extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->createTableDicionarioDados();
        $this->createEstrutura();
    }

    public function down()
    {
        $this->dropTableDicionarioDados();
        $this->dropEstrutura();
    }    
       
    public function createTableDicionarioDados()
    {
        $sql = <<<SQL

            insert into db_sysarquivo values (1010446, 'relogioponto', 'Cadastro dos relogios do registros do ponto.', 'rh227', '2019-05-23', 'relogioponto', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (29,1010446);
            insert into db_sysarquivo values (1010447, 'pontoeletronicoarquivoimportacao', 'Guarda os dados de importação de arquivo do ponto eletronico.', 'rh228', '2019-05-23', 'pontoeletronicoarquivoimportacao', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (29,1010447);
            insert into db_sysarquivo values (1010448, 'pontoeletronicoarquivoimportacaoregistro', 'Guarda cada registro de batida dessa importação.', 'rh229', '2019-05-23', 'pontoeletronicoarquivoimportacaoregistro', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (29,1010448);
            update db_sysarquivo set nomearq = 'relogioponto', descricao = 'Cadastro dos relogios do registros do ponto.', sigla = 'rh227', dataincl = '2019-05-23', rotulo = 'relogioponto', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1010446;
            insert into db_sysarqarq values(0,1010446);
            insert into db_syscampo values(1010488,'rh227_sequencial','int4','Chave primaria que guarda o sequencial do campo.','0', 'sequencial',10,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1010489,'rh227_fabricante','varchar(255)','Guarda o nome do fabricante.','', 'fabricante',255,'f','f','f',0,'text','fabricantefabricante');
            insert into db_syscampo values(1010492,'rh227_modelo','varchar(255)','Guarda o modelo do ponto.','', 'rh227_modelo',255,'t','f','f',0,'text','rh227_modelo');
            insert into db_syscampo values(1010493,'rh227_numero_serie','varchar(255)','Guarda o numero de serie do ponto.','', 'rh227_numero_serie',255,'f','t','f',0,'text','rh227_numero_serie');
            insert into db_sysarqcamp values(1010446,1010488,1,0);
            insert into db_sysarqcamp values(1010446,1010489,2,0);
            insert into db_sysarqcamp values(1010446,1010492,3,0);
            insert into db_sysarqcamp values(1010446,1010493,4,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010446,1010488,1,1010488);
            insert into db_sysindices values(1008459,'relogioponto_sequencial_in',1010446,'0');
            insert into db_syscadind values(1008459,1010488,1);
            insert into db_syssequencia values(1000835, 'relogioponto_rh227_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);            
            update db_sysarqcamp set codsequencia = 1000835 where codarq = 1010446 and codcam = 1010488;
            insert into db_sysindices values(1008460,'relogioponto_numero_serie_un_in',1010446,'1');
            insert into db_syscadind values(1008460,1010493,1);
            insert into db_syscampo values(1010494,'rh228_sequencial','int4','Guarda o numero do sequencial.','0', 'rh228_sequencial',10,'f','f','f',1,'text','rh228_sequencial');
            insert into db_syscampo values(1010495,'rh228_instituicao','int4','Guarda a instituicao','0', 'rh228_instituicao',10,'f','f','f',1,'text','rh228_instituicao');
            insert into db_syscampo values(1010496,'rh228_arquivo','oid','Campo identificador do objeto.','', 'rh228_arquivo',1,'t','f','f',1,'text','rh228_arquivo');
            insert into db_syscampo values(1010499,'rh228_serial','text','Guarda o serial do campo.','', 'rh228_serial',1,'f','f','f',0,'text','rh228_serial');
            insert into db_syscampo values(1010500,'rh228_data_inicio','date','Guarda data inicial.','null', 'rh228_data_inicio',10,'f','f','f',1,'text','');
            insert into db_syscampo values(1010501,'rh228_data_fim','date','Guarda data final.','null', 'rh228_data_fim',10,'f','f','f',1,'text','');
            insert into db_syscampo values(1010503,'rh228_compilado','bool','Guarda data final.','null', 'rh228_compilado',1,'f','f','f',5,'text','');
            insert into db_syscampodef values(1010503,'false','');
            insert into db_sysarqcamp values(1010447,1010494,1,0);
            insert into db_sysarqcamp values(1010447,1010495,2,0);
            insert into db_sysarqcamp values(1010447,1010496,3,0);
            insert into db_sysarqcamp values(1010447,1010499,4,0);
            insert into db_sysarqcamp values(1010447,1010500,5,0);
            insert into db_sysarqcamp values(1010447,1010501,6,0);
            insert into db_sysarqcamp values(1010447,1010503,7,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010447,1010494,1,1010494);
            insert into db_sysindices values(1008461,'pontoeletronicoarquivoimportacao_sequencial_in',1010447,'0');
            insert into db_syscadind values(1008461,1010494,1);
            insert into db_syssequencia values(1000836, 'pontoeletronicoarquivoimportacao_rh228_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000836 where codarq = 1010447 and codcam = 1010494;
            insert into db_syscampo values(1010520,'rh229_sequencial','int4','Guarda chave primaria e sequencial.','0', 'rh229_sequencial',10,'f','f','f',1,'text','');
            insert into db_syscampo values(1010524,'rh229_pontoeletronicoarquivoimportacao','int4','Guarda importacao do arquivo ponto eletronico. ','0', 'rh229_pontoeletronicoarquivoimportacao',10,'f','f','f',1,'text','');
            insert into db_syscampo values(1010528,'rh229_pis','varchar(100)','Guardo o pis.','', 'rh229_pis',100,'t','t','f',0,'text','');
            insert into db_syscampo values(1010529,'rh229_matricula','int4','Campo responsavel por guardar a matricula. ','0', 'rh229_matricula',10,'t','f','f',1,'text','');
            insert into db_syscampo values(1010530,'rh229_data','date','Campo responsavel por guardar a data.','null', 'rh229_data',10,'t','f','f',1,'text','');
            insert into db_syscampo values(1010531,'rh229_hora','char(10)','Campo responsavel por guardar a hora.','', 'rh229_hora',10,'t','f','f',0,'text','');
            insert into db_syscampo values(1010532,'rh229_serial','text','Campo responsavel por guardar o serial.','', 'rh229_serial',1,'f','t','f',0,'text','');
            insert into db_sysarqcamp values(1010448,1010520,1,0);
            insert into db_sysarqcamp values(1010448,1010524,2,0);
            insert into db_sysarqcamp values(1010448,1010528,3,0);
            insert into db_sysarqcamp values(1010448,1010529,4,0);
            insert into db_sysarqcamp values(1010448,1010530,5,0);
            insert into db_sysarqcamp values(1010448,1010531,6,0);
            insert into db_sysarqcamp values(1010448,1010532,7,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010448,1010520,1,1010520);
            insert into db_sysindices values(1008464,'pontoeletronicoarquivoimportacaoregistro_sequencial_in',1010448,'0');
            insert into db_syscadind values(1008464,1010520,1);

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228122 ,'Relógio Ponto' ,'Relógio Ponto' ,'rec2_relatoriorelogioponto001.php' ,'1' ,'1' ,'Relógio Ponto' ,'true' );
            delete from db_menu where id_item_filho = 228122 AND modulo = 2323;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10384 ,228122 ,14 ,2323 );

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228123 ,'Registrar Horário Manual' ,'Registrar Horário Manual' ,'rec4_registrarhorariomanualmente.php' ,'1' ,'1' ,'Registrar Horário Manual' ,'true' );
            delete from db_menu where id_item_filho = 228123 AND modulo = 2323;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10384 ,228123 ,15 ,2323 );

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228124 ,'Importação de Arquivos por Relógio' ,'Importação de Arquivos por Relógio' ,'rec4_pontoeletronicoimportarcompilararquivo.php' ,'1' ,'1' ,'Importação de Arquivos por Relógio' ,'true' );
            delete from db_menu where id_item_filho = 228124 AND modulo = 2323;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10384 ,228124 ,16 ,2323 );

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228125 ,'Processamento Ponto Eletrônico' ,'Processamento Ponto Eletrônico' ,'rec4_processamentopontoeletronico.php' ,'1' ,'1' ,'Processamento Ponto Eletrônico' ,'true' );
            delete from db_menu where id_item_filho = 228125 AND modulo = 2323;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10384 ,228125 ,17 ,2323 );

SQL;
        $this->execute($sql);
    }
        
    public function createEstrutura()
    {
        $sql = <<<SQL

            CREATE SEQUENCE IF NOT EXISTS recursoshumanos.relogioponto_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE SEQUENCE IF NOT EXISTS recursoshumanos.pontoeletronicoarquivoimportacao_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE SEQUENCE IF NOT EXISTS recursoshumanos.pontoeletronicoarquivoimportacaoregistro_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE IF NOT EXISTS recursoshumanos.relogioponto (
                rh227_sequencial     int4 NOT NULL DEFAULT nextval('recursoshumanos.relogioponto_sequencial_seq'),
                rh227_fabricante     varchar(255) NOT NULL,
                rh227_modelo         varchar(255),
                rh227_numero_serie   varchar(255) NOT NULL,
                CONSTRAINT relogioponto_seq_pk PRIMARY KEY (rh227_sequencial)
            );

            CREATE UNIQUE INDEX IF NOT EXISTS numero_serie_un_in ON recursoshumanos.relogioponto (rh227_numero_serie);

            create table IF NOT EXISTS recursoshumanos.pontoeletronicoarquivoimportacao (
                rh228_sequencial      int4 NOT NULL DEFAULT nextval('recursoshumanos.pontoeletronicoarquivoimportacao_sequencial_seq'),
                rh228_instituicao     integer not null,
                rh228_arquivo         oid,
                rh228_serial          text    not null,
                rh228_data_inicio     date    not null,
                rh228_data_fim        date    not null,
                rh228_compilado       boolean not null default false,
                CONSTRAINT pontoeletronicoarquivoimportacao_seq_pk primary key (rh228_sequencial)
            );

            create table IF NOT EXISTS recursoshumanos.pontoeletronicoarquivoimportacaoregistro (

                rh229_sequencial                        int4 NOT NULL DEFAULT nextval('recursoshumanos.pontoeletronicoarquivoimportacaoregistro_sequencial_seq'),
                rh229_pontoeletronicoarquivoimportacao  integer not null,
                rh229_pis                               varchar,
                rh229_matricula                         integer,
                rh229_data                              date,
                rh229_hora                              time,
                rh229_serial                            text    not null,
                CONSTRAINT pontoeletronicoarquivoimportacaoregistro_seq_pk primary key (rh229_sequencial),
                CONSTRAINT pontoeletronicoarquivoimportacaoregistro_pontoeletronicoarquivoimportacao_fk foreign key (rh229_pontoeletronicoarquivoimportacao) references recursoshumanos.pontoeletronicoarquivoimportacao
            );

            create index IF NOT EXISTS pontoeletronicoarquivoimportacao_instituicao_in on recursoshumanos.pontoeletronicoarquivoimportacao (rh228_instituicao);
            create index IF NOT EXISTS pontoeletronicoarquivoimportacao_serial_in on recursoshumanos.pontoeletronicoarquivoimportacao (rh228_serial);
            create index IF NOT EXISTS ptoeletroarqimportacaoregistro_data_in on recursoshumanos.pontoeletronicoarquivoimportacaoregistro (rh229_data);
            create index IF NOT EXISTS ptoeletroarqimportacaoregistro_hora_in on recursoshumanos.pontoeletronicoarquivoimportacaoregistro (rh229_hora);
            create index IF NOT EXISTS ptoeletroarqimportacaoregistro_matricula_in on recursoshumanos.pontoeletronicoarquivoimportacaoregistro (rh229_matricula);
            create index IF NOT EXISTS ptoeletroarqimportacaoregistro_pontoeletronicoarquivoimportacaoregistro_in on recursoshumanos.pontoeletronicoarquivoimportacaoregistro (rh229_pontoeletronicoarquivoimportacao);

SQL;
        $this->execute($sql);
    }

    public function dropTableDicionarioDados()
    {
        $sql = <<<SQL
        
            delete from db_sysarqarq where codarq = 1010446;
            delete from db_sysarqcamp where codarq = 1010446;
            delete from db_sysprikey where codarq = 1010446;
            delete from db_sysarqarq where codarq = 1010446;
            delete from db_sysarqcamp where codarq = 1010446;
            delete from db_sysprikey where codarq = 1010446;
            delete from db_sysarqcamp where codarq = 1010447;
            delete from db_sysprikey where codarq = 1010447;
            delete from db_sysprikey where codarq = 1010447;
            delete from db_sysarqcamp where codarq = 1010448;
            delete from db_sysprikey where codarq = 1010448;

            delete from db_syscadind where codind in (1008459, 1008460, 1008461, 1008464);
            delete from db_sysindices where codind in (1008459, 1008460, 1008461, 1008464);
            delete from db_syssequencia where codsequencia in (1000835, 1000836);

            delete from db_syscampodef where codcam = 1010503;
            delete from db_syscampo where codcam in (1010488, 1010489, 1010492, 1010493, 1010494, 1010495, 1010496, 1010499, 1010500, 1010501, 1010503, 1010520, 1010524, 1010528, 1010529, 1010530, 1010531, 1010532);

            delete from db_sysarqmod where codarq in (1010446,1010447, 1010448);
            delete from db_sysarquivo where codarq in (1010446,1010447, 1010448);

            delete from db_menu where id_item_filho = 228122 AND modulo = 2323;
            delete from db_itensmenu where id_item = 228122;

            delete from db_menu where id_item_filho = 228123 AND modulo = 2323;
            delete from db_itensmenu where id_item = 228123;

            delete from db_menu where id_item_filho = 228124 AND modulo = 2323;
            delete from db_itensmenu where id_item = 228124;

            delete from db_menu where id_item_filho = 228125 AND modulo = 2323;
            delete from db_itensmenu where id_item = 228125;

SQL;
        $this->execute($sql);
    }

    public function dropEstrutura()
    {
        $sql = <<<SQL
        
            DROP TABLE IF EXISTS recursoshumanos.pontoeletronicoarquivoimportacaoregistro;
            DROP TABLE IF EXISTS recursoshumanos.pontoeletronicoarquivoimportacao;
            DROP TABLE IF EXISTS recursoshumanos.relogioponto;
            DROP SEQUENCE IF EXISTS recursoshumanos.relogioponto_sequencial_seq;
            DROP SEQUENCE IF EXISTS recursoshumanos.pontoeletronicoarquivoimportacao_sequencial_seq;
            DROP SEQUENCE IF EXISTS recursoshumanos.pontoeletronicoarquivoimportacaoregistro_sequencial_seq;

SQL;
        $this->execute($sql);
    }
}

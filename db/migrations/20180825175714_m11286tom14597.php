<?php

use Classes\PostgresMigration;

class M11286ToM14597 extends PostgresMigration
{


    public function up()
    {

        $sSql = <<<STRING
        
        
            -- cria itens de menu
           
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
                values ( 10571 ,'Assinatura Digital de Portaria' ,'Assinatura Digital de Portaria' ,'rh_processaassinaturadigital.php' ,'1' ,'1' ,'Assinatura Digital de Portaria' ,'true' );
           
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10571 ,502 ,2323 );
           
          
            -- cria tabela e vincula  modulos
            
            insert into db_sysarquivo values (1010307, 'assinaturaportaria', 'Tabela para guardar vinculo portaria com assinatura digital', '', '2018-08-27', '', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (29,1010307);
            insert into db_sysarquivo values (1010308, 'assinaturadocumento', 'Tabela que guarda os documentos assinados', '', '2018-08-27', 'assinaturadocumento', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (7,1010308);
            insert into db_sysarquivo values (1010309, 'assinaturaassinates', 'tabela para guardar os assinates da assinatura digital', '', '2018-08-27', 'assinaturaassinates', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (7,1010309);
            
           
            insert into db_syscampo values(1009907,'h15_portaria','int8','Portaria','0', 'Portaria',11,'f','f','f',1,'text','Portaria');
            insert into db_syscampo values(1009908,'h15_assinatura','int8','Assinatura','0', 'Assinatura',11,'f','f','f',1,'text','Assinatura');
            insert into db_syscampo values(1009923,'h15_assinaturaportaria_sequencial','int8','h15_sequencial','0', 'Sequencial',11,'f','f','f',1,'text','');
            
            delete from db_sysarqcamp where codarq = 1010307;
            
            insert into db_sysarqcamp values(1010307,1009908,1,0);
            insert into db_sysarqcamp values(1010307,1009907,2,0);
            insert into db_sysarqcamp values(1010307,1009923,3,0);
            
            
            insert into db_syscampo values(1009911,'assinatura_sequencial','int8','sequencial da assinatura','0', 'sequencial',11,'f','f','f',1,'text','');
            insert into db_syscampo values(1009912,'assinatura_status','int8','status da assinatura','0', 'status',11,'f','f','f',1,'text','');
            insert into db_syscampo values(1009913,'assinatura_hash','text','Hash do arquivo assinado','', 'hash',1,'f','f','f',0,'text','');
            insert into db_syscampo values(1009914,'assinatura_documento','oid','Binarios do documento','', 'Documento',1,'f','f','f',1,'text','');
            insert into db_syscampo values(1009915,'assinatura_versao','float4','Versao do documento assinado','0', 'Versão',11,'f','f','f',4,'text','');
            insert into db_syscampo values(1009916,'assinatura_data','date','Data da Assinatura','null', 'Data da Assinatura',10,'f','f','f',1,'text','');
           
           
            delete from db_sysarqcamp where codarq = 1010308;
           
            insert into db_sysarqcamp values(1010308,1009911,1,0);
            insert into db_sysarqcamp values(1010308,1009912,2,0);
            insert into db_sysarqcamp values(1010308,1009914,3,0);
            insert into db_sysarqcamp values(1010308,1009913,4,0);
            insert into db_sysarqcamp values(1010308,1009915,5,0);
            insert into db_sysarqcamp values(1010308,1009916,6,0);

            insert into db_syscampo values(1009917,'assinates_sequencial','int8','Sequencial do assinante','0', 'Sequencial',11,'f','f','f',1,'text','');
            insert into db_syscampo values(1009918,'assinates_id_usuario','int8','usuario assinante','0', 'Usuario',11,'f','f','f',1,'text','');
            insert into db_syscampo values(1009919,'assinates_status','int8','status do assinante para ver se ja foi assinado por usuario','0', 'status',11,'f','f','f',1,'text','');
            
            
            
            insert into db_sysarqcamp values(1010309,1009917,1,0);
            insert into db_sysarqcamp values(1010309,1009918,2,0);
            insert into db_sysarqcamp values(1010309,1009919,3,0);
            
            
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010308,1009911,1,1009911);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010309,1009917,1,1009917);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010307,1009923,1,1009908);
           
            insert into db_syssequencia values(1000757, 'assinaturadocumento_assinatura_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000757 where codarq = 1010308 and codcam = 1009911;
           
            insert into db_syssequencia values(1000758, 'assinaturaassinates_assinates_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000758 where codarq = 1010309 and codcam = 1009917;
           
            insert into db_syssequencia values(1000759, 'assinaturaportaria_h15_assinaturaportaria_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000759 where codarq = 1010307 and codcam = 1009923;


            CREATE SEQUENCE recursoshumanos.assinaturaportaria_h15_assinaturaportaria_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;  

            CREATE SEQUENCE configuracoes.assinaturadocumento_assinatura_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;            
                
            CREATE SEQUENCE configuracoes.assinaturaassinates_assinates_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;    
    
            CREATE TABLE recursoshumanos.assinaturaportaria(
                h15_assinaturaportaria_sequencial bigint not null,
                h15_portaria bigint not null,
                h15_assinatura bigint not null    
            );
           
           
            CREATE TABLE configuracoes.assinaturadocumento(
                assinatura_sequencial             bigint not null,
                assinatura_status                 boolean not null,
                assinatura_hash                   text not null,
                assinatura_documento              oid not null,
                assinatura_versao                 double precision not null,
                assinatura_data                   date  not null
            );
    
            CREATE TABLE configuracoes.assinaturaassinates(
                assinates_sequencial   bigint not null,
                assinates_assinatura   bigint not null,
                assinates_id_usuario   bigint not null,   
                assinates_status       boolean not null                 
            );

           
            ALTER TABLE   assinaturadocumento                         
            ADD CONSTRAINT assinaturadocumento_sequencial_pk PRIMARY KEY (assinatura_sequencial);
           
            ALTER TABLE   assinaturaassinates                         
            ADD CONSTRAINT assinaturaassinates_sequencial_pk PRIMARY KEY (assinates_sequencial);
            
            ALTER TABLE assinaturaassinates
                ADD CONSTRAINT assinaturadocumento_sequencial_fk FOREIGN KEY (assinates_assinatura)
                REFERENCES assinaturadocumento;
           
            ALTER TABLE assinaturaportaria
                ADD CONSTRAINT assinaturadocumento_sequencial_fk FOREIGN KEY (h15_assinaturaportaria_sequencial)
                REFERENCES assinaturadocumento;
           
            ALTER TABLE assinaturaportaria
                ADD CONSTRAINT portaria_sequencial_fk FOREIGN KEY (h15_portaria)
                REFERENCES portaria; 


STRING;
        $this->execute($sSql);

        $this->upDDL();
        $this->upDicionarioDados();
    }

    public function upDDL()
    {
        $sql = <<<STRING
            ---------------- ESTRUTURA NOVA ----------------
            CREATE TABLE configuracoes.arquivoestorage (
                db177_idestorage                    int not null PRIMARY KEY,
                db177_descricao                     text default null,
                db177_datadocumento                 date not null,
                db177_url                           varchar(256) default null,
                db177_idestorage_arquivoanterior    int
            );
            ALTER TABLE configuracoes.arquivoestorage 
                ADD CONSTRAINT arquivoestorage_idestorage_arquivoanterior_fk 
                FOREIGN KEY (db177_idestorage_arquivoanterior)
                REFERENCES configuracoes.arquivoestorage (db177_idestorage);
            CREATE INDEX arquivoestorage_db177_descricao_in ON configuracoes.arquivoestorage (db177_descricao);

            CREATE SEQUENCE configuracoes.assinaturasdocumento_db178_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            CREATE TABLE configuracoes.assinaturasdocumento (
                db178_sequencial                int not null default nextval('assinaturasdocumento_db178_sequencial_seq') PRIMARY KEY,
                db178_nome                      varchar(256) not null,
                db178_cpf                       varchar(11) not null,
                db178_metadados                 json default null,
                db178_imagem                    int default null
            );
            CREATE INDEX assinatura_db178_nome_in ON configuracoes.assinaturasdocumento (db178_nome);
            CREATE UNIQUE INDEX assinatura_db178_cpf_in_u ON configuracoes.assinaturasdocumento (db178_cpf);

            CREATE SEQUENCE configuracoes.arquivoestorageassinaturas_db179_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            CREATE TABLE configuracoes.arquivoestorageassinaturas (
                db179_sequencial                int not null default nextval('arquivoestorageassinaturas_db179_sequencial_seq') PRIMARY KEY,
                db179_arquivo                   int not null,
                db179_assinatura                int not null,
                db179_dataassinatura            date not null
            );
            ALTER TABLE configuracoes.arquivoestorageassinaturas 
                ADD CONSTRAINT arquivoassinatura_arquivo_fk 
                FOREIGN KEY (db179_arquivo)
                REFERENCES configuracoes.arquivoestorage (db177_idestorage);
            ALTER TABLE configuracoes.arquivoestorageassinaturas 
                ADD CONSTRAINT arquivoassinatura_assinatura_fk
                FOREIGN KEY (db179_assinatura)
                REFERENCES configuracoes.assinaturasdocumento (db178_sequencial);

            CREATE SEQUENCE configuracoes.assinaturadocumentodesignacao_db59_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            CREATE TABLE configuracoes.assinaturadocumentodesignacao (
                db59_codigo int8 default nextval('assinaturadocumentodesignacao_db59_codigo_seq') PRIMARY KEY,
                db59_relatorio int8 NOT NULL,
                db59_usuario int8 NOT NULL,
                db59_datainicio date NOT NULL default current_date
            );
            ALTER TABLE configuracoes.assinaturadocumentodesignacao
                ADD CONSTRAINT assinaturadocumentodesignacao_relatorio_fk 
                 FOREIGN KEY (db59_relatorio) REFERENCES configuracoes.db_relatorio (db63_sequencial)
            ;
            ALTER TABLE assinaturadocumentodesignacao
                ADD CONSTRAINT assinaturadocumentodesignacao_usuario_fk 
                 FOREIGN KEY (db59_usuario) REFERENCES db_usuarios (id_usuario)
            ;

            CREATE TYPE recursoshumanos.situacao_portaria AS ENUM (
                 'C' -- Criada
                ,'O' -- Conferido
                ,'D' -- Devolvido para abertura
                ,'A' -- Aguarda assinatura
                ,'F' -- Devolvido para conferência
                ,'S' -- Assinado; 
                ,'I' -- Impresso
            );
            CREATE SEQUENCE recursoshumanos.portariaassentasituacao_rh236_codigo_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            CREATE TABLE recursoshumanos.portariaassentasituacao (
                rh236_codigo int8 default nextval('recursoshumanos.portariaassentasituacao_rh236_codigo_seq') PRIMARY KEY,
                rh236_portariaassenta int8 NOT NULL,
                rh236_situacao situacao_portaria NOT NULL default 'C',
                rh236_momento integer NOT NULL
            );
            ALTER TABLE recursoshumanos.portariaassentasituacao
                ADD CONSTRAINT portariaassentasituacao_fk 
                FOREIGN KEY (rh236_portariaassenta) REFERENCES recursoshumanos.portariaassenta (h33_sequencial)
            ;


            ---------------- ALTERANDO ESTRUTURA ANTERIOR ----------------
            DROP SEQUENCE recursoshumanos.assinaturaportaria_h15_assinaturaportaria_sequencial_seq;
            CREATE SEQUENCE recursoshumanos.documentoportaria_rh235_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            ALTER TABLE recursoshumanos.assinaturaportaria RENAME TO documentoportaria;
            ALTER TABLE recursoshumanos.documentoportaria 
                DROP CONSTRAINT assinaturadocumento_sequencial_fk
            ;
            ALTER TABLE recursoshumanos.documentoportaria 
                RENAME COLUMN h15_portaria TO rh235_portaria
            ;
            ALTER TABLE recursoshumanos.documentoportaria 
                RENAME COLUMN h15_assinatura TO rh235_documento
            ;
            ALTER TABLE recursoshumanos.documentoportaria 
                RENAME COLUMN h15_assinaturaportaria_sequencial TO rh235_sequencial
            ;
            ALTER TABLE recursoshumanos.documentoportaria 
                ALTER COLUMN rh235_sequencial SET default nextval('recursoshumanos.documentoportaria_rh235_sequencial_seq')
            ;
            ALTER TABLE recursoshumanos.documentoportaria 
                ADD CONSTRAINT documentoportaria_documento_fk
                FOREIGN KEY (rh235_documento) 
                REFERENCES configuracoes.arquivoestorage (db177_idestorage);

            ---------------- REMOVENDO ESTRUTURA ANTERIOR ----------------
            DROP TABLE configuracoes.assinaturaassinates;
            DROP TABLE configuracoes.assinaturadocumento;
            DROP SEQUENCE configuracoes.assinaturadocumento_assinatura_sequencial_seq;
            DROP SEQUENCE configuracoes.assinaturaassinates_assinates_sequencial_seq;
STRING;

        $this->execute($sql);
    }

    public function upDicionarioDados()
    {
        $sql = <<<STRING
            ---------------- ESTRUTURA NOVA ----------------
            INSERT INTO db_sysarquivo VALUES (1010481, 'arquivoestorage', 'Tabela que guarda a referência dos arquivos no e-Storage', 'db177', '2019-11-25', 'Arquivo e-Storage', 0, 'f', 'f', 't', 't' );
            INSERT INTO db_sysarqmod VALUES (7,1010481);
            
            INSERT INTO db_syscampo VALUES(1010802,'id_storage','int8','Identificador do arquivo no e-Storage','0', 'ID e-Storage',19,'t','f','f',1,'text','ID e-Storage');
            update db_syscampo set nomecam = 'db177_id_storage', conteudo = 'int8', descricao = 'Identificador do arquivo no e-Storage', valorinicial = '0', rotulo = 'ID e-Storage', nulo = 'f', tamanho = 19, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'ID e-Storage' where codcam = 1010802;
            update db_syscampo set nomecam = 'db177_idestorage', conteudo = 'int8', descricao = 'Identificador do arquivo no e-Storage', valorinicial = '0', rotulo = 'ID e-Storage', nulo = 'f', tamanho = 19, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'ID e-Storage' where codcam = 1010802;
            INSERT INTO db_syscampo VALUES(1010804,'db177_descricao','text','Informações a respeito do arquivo','', 'Descrição',256,'t','t','f',0,'text','Descrição');
            update db_syscampo set nomecam = 'db177_descricao', conteudo = 'text', descricao = 'Informações a respeito do arquivo, arquivo do tipo JSON para tornar livre a informação que se deseja guardar a respeito do arquivo', valorinicial = '', rotulo = 'Descrição do arquivo', nulo = 't', tamanho = 256, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Descrição do arquivo' where codcam = 1010804;
            INSERT INTO db_syscampo VALUES(1010805,'db177_url','varchar(256)','Url do arquivo no e-Storage quando tratar-se de documento de domínio público','', 'URL',256,'t','t','f',0,'text','URL');
            INSERT INTO db_syscampo VALUES(1010815,'db177_datadocumento','date','Data do arquivo/documento','null', 'Data do arquivo/documento',10,'f','f','f',1,'text','Data do arquivo/documento');
            INSERT INTO db_syscampo VALUES(1010816,'db177_idestorage_arquivoanterior','int8','ID do e-Storage do arquivo anterior','0', 'ID e-Storage Arquivo Anterior',19,'t','f','f',1,'text','ID e-Storage Arquivo Anterior');
            
            INSERT INTO db_sysarqcamp VALUES(1010481,1010802,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010481,1010804,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010481,1010815,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010481,1010805,4,0);
            INSERT INTO db_sysarqcamp VALUES(1010481,1010816,5,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) values(1010481,1010802,1,1010802);
            INSERT INTO db_sysindices VALUES(1008500,'arquivoestorage_db177_descricao_in',1010481,'0');
            INSERT INTO db_syscadind VALUES(1008500,1010804,1);
            INSERT INTO db_sysforkey VALUES(1010481,1010816,1,1010481,0);




            INSERT INTO db_sysarquivo values (1010482, 'assinaturasdocumento', 'Tabela que guarda informações a respeito de quem assinou um determinado documento.', 'db178', '2019-11-25', 'Assinatura', 0, 'f', 'f', 't', 't' );
            INSERT INTO db_sysarqmod values (7,1010482);

            INSERT INTO db_syscampo values(1010806,'db178_codigo','int8','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código');
            INSERT INTO db_syscampo values(1010807,'db178_nome','text','Nome de quem está assinando o arquivo.','', 'Nome',256,'f','t','f',0,'text','Nome');
            INSERT INTO db_syscampo values(1010808,'db178_cpf','text','Cpf de quem está assinando o arquivo','', 'CPF',11,'f','t','f',0,'text','CPF');
            INSERT INTO db_syscampo values(1010809,'db178_metadados','text','Campo tipo JSON para receber demais informações que possam ser úteis.','', 'Demais informações',256,'t','t','f',0,'text','Demais informações');
            INSERT INTO db_syscampo values(1010810,'db178_imagem','int8','Imagem da assinatura, para que possa ser utilizada nos documentos quando houver necessidade.','0', 'Imagem',19,'t','f','f',1,'text','Imagem');
            update db_syscampo set nomecam = 'db178_sequencial', conteudo = 'int8', descricao = 'Código sequencial da tabela', valorinicial = '0', rotulo = 'Código', nulo = 'f', tamanho = 19, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Código' where codcam = 1010806;
            
            INSERT INTO db_sysarqcamp values(1010482,1010806,1,0);
            INSERT INTO db_sysarqcamp values(1010482,1010807,2,0);
            INSERT INTO db_sysarqcamp values(1010482,1010808,3,0);
            INSERT INTO db_sysarqcamp values(1010482,1010809,4,0);
            INSERT INTO db_sysarqcamp values(1010482,1010810,5,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) values(1010482,1010806,1,1010806);
            
            INSERT INTO db_sysindices values(1008501,'assinatura_db178_nome_in',1010482,'0');
            INSERT INTO db_syscadind values(1008501,1010807,1);
            INSERT INTO db_sysindices values(1008502,'assinatura_db178_cpf_in_u',1010482,'1');
            INSERT INTO db_syscadind values(1008502,1010808,1);
            
            INSERT INTO db_syssequencia values(1000855, 'assinaturasdocumento_db178_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000855 where codarq = 1010482 and codcam = 1010806;



    
            INSERT INTO db_sysarquivo values (1010483, 'arquivoestorageassinaturas', 'Tabela de vínculo entre arquivos do e-Storage e assinaturas destes arquivos', 'db179', '2019-11-25', 'Assinatura de Arquivo do e-Storage', 0, 'f', 'f', 't', 't' );
            INSERT INTO db_sysarqmod values (7,1010483);
            
            INSERT INTO db_syscampo values(1010811,'db179_codigo','int8','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código');
            INSERT INTO db_syscampo values(1010812,'db179_arquivo','int8','ID de referência do arquivo no e-Storage','0', 'Arquivo e-Storage',19,'f','f','f',1,'text','Arquivo e-Storage');
            INSERT INTO db_syscampo values(1010813,'db179_assinatura','int8','Assinatura no documento','0', 'Assinatura',19,'f','f','f',1,'text','Assinatura');
            INSERT INTO db_syscampo values(1010814,'db179_dataassinatura','date','Data da assinatura no documento','null', 'Data da Assinatura',10,'f','f','f',1,'text','Data da Assinatura');
            update db_syscampo set nomecam = 'db179_sequencial', conteudo = 'int8', descricao = 'Código sequencial da tabela', valorinicial = '0', rotulo = 'Código', nulo = 'f', tamanho = 19, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Código' where codcam = 1010811;

            INSERT INTO db_sysarqcamp values(1010483,1010811,1,0);
            INSERT INTO db_sysarqcamp values(1010483,1010812,2,0);
            INSERT INTO db_sysarqcamp values(1010483,1010813,3,0);
            INSERT INTO db_sysarqcamp values(1010483,1010814,4,0);
            
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) values(1010483,1010811,1,1010811);

            INSERT INTO db_sysforkey values(1010483,1010812,1,1010481,0);
            INSERT INTO db_sysforkey values(1010483,1010813,1,1010482,0);    
            
            INSERT INTO db_syssequencia values(1000856, 'arquivoestorageassinaturas_db179_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000856 where codarq = 1010483 and codcam = 1010811;




            INSERT INTO db_sysarquivo VALUES (1010491, 'assinaturadocumentodesignacao', 'Tabela que informa quem deve assinar um documento, para que seja possível sua correta exibição para assinatura', 'db59', '2019-12-23', 'Assinaturas do documento', 0, 'f', 'f', 't', 't' );
            INSERT INTO db_sysarqmod VALUES (7,1010491);

            INSERT INTO db_syscampo VALUES(1010853,'db59_codigo','int8','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código');
            INSERT INTO db_syscampo VALUES(1010854,'db59_relatorio','int8','Documento a ser assinado','0', 'Documento',34,'f','f','f',1,'text','Documento');
            INSERT INTO db_syscampo VALUES(1010855,'db59_usuario','int8','Usuário a assinar o documento','0', 'Usuário',34,'f','f','f',1,'text','Usuário');
            INSERT INTO db_syscampo VALUES(1010856,'db59_datainicio','date','Data a partir determinado usuário está responsável por assinar um documento','null', 'Data de Início',10,'f','f','f',1,'text','Data de Início');

            INSERT INTO db_sysarqcamp VALUES (1010491,1010853,1,0);
            INSERT INTO db_sysarqcamp VALUES (1010491,1010854,2,0);
            INSERT INTO db_sysarqcamp VALUES (1010491,1010855,3,0);
            INSERT INTO db_sysarqcamp VALUES (1010491,1010856,4,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES (1010491,1010853,1,1010853);

            INSERT INTO db_sysforkey VALUES (1010491,1010854,1,518,0);
            INSERT INTO db_sysforkey VALUES (1010491,1010855,1,109,0);
            INSERT INTO db_syssequencia VALUES(1000863, 'assinaturadocumentodesignacao_db59_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = 1000863 WHERE codarq = 1010491 AND codcam = 1010853;





            INSERT INTO db_sysarquivo VALUES  (1010492, 'portariaassentasituacao', 'Tabela para guarda a situação de uma portaria', 'rh236', '2019-12-23', 'Portaria assentamento situcao', 0, 'f', 'f', 't', 't' );
            INSERT INTO db_sysarqmod VALUES  (29,1010492);

            INSERT INTO db_syscampo VALUES (1010857,'rh236_codigo','int8','Código sequencial da tabela','0', 'Código',34,'f','f','f',1,'text','Código');
            INSERT INTO db_syscampo VALUES (1010858,'rh236_status','char(1)','Campo que contém o status da assinatura do documento','', 'Status',1,'f','t','f',0,'text','Status');
            INSERT INTO db_syscampo VALUES (1010859,'rh236_momento','int8','Momento em que ocorreu a troca de situação, data e hora','0', 'Momento da movimentação',34,'f','f','f',1,'text','Momento da movimentação');
            INSERT INTO db_syscampo VALUES (1010860,'rh236_portariaassenta','int8','Vinculo da situação com a portaria','0', 'Portaria',34,'f','f','f',1,'text','Portaria');

            INSERT INTO db_sysarqcamp VALUES (1010492,1010857,1,0);
            INSERT INTO db_sysarqcamp VALUES (1010492,1010860,2,0);
            INSERT INTO db_sysarqcamp VALUES (1010492,1010858,3,0);
            INSERT INTO db_sysarqcamp VALUES (1010492,1010859,4,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES (1010492,1010857,1,1010857);

            INSERT INTO db_sysindices VALUES (1008503,'portariaassentasituacao_rh236_situacao_in',1010492,'0');
            INSERT INTO db_syscadind VALUES (1008503,1010858,1);
            INSERT INTO db_sysforkey VALUES (1010492,1010860,1,1743,0);
            UPDATE db_syscampo SET 
                nomecam = 'rh236_situacao', 
                conteudo = 'char(1)', 
                descricao = 'Campo que contém a situação da assinatura do documento default C (C-Criada; - O-Conferido / D-Devolvido para abertura; - A-Aguarda assinatura / F-Devolvido para conferência; - S-Assinado; I-Impresso)', 
                valorinicial = '',
                rotulo = 'Situação',
                nulo = 'f',
                tamanho = 1,
                maiusculo = 't',
                autocompl = 'f',
                aceitatipo = 0,
                tipoobj = 'text',
                rotulorel = 'Situação'
            WHERE codcam = 1010858;
            INSERT INTO db_syssequencia VALUES(1000864, 'portariaassentasituacao_rh236_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = 1000864 WHERE codarq = 1010492 AND codcam = 1010857;




            ---------------- ALTERANDO ESTRUTURA ANTERIOR ----------------
            UPDATE db_sysarquivo SET sigla = 'rh235', nomearq = 'documentoportaria' WHERE codarq = 1010307;
            UPDATE db_syscampo SET nomecam = 'rh235_portaria' WHERE codcam = 1009907;
            UPDATE db_syscampo SET nomecam = 'rh235_documento', descricao = 'Documento', rotulo = 'Documento' WHERE codcam = 1009908;
            UPDATE db_syscampo SET nomecam = 'rh235_sequencial', descricao = 'Sequencial da tabela' WHERE codcam = 1009923;
            UPDATE db_syssequencia SET nomesequencia = 'documentoportaria_rh235_sequencial_seq' WHERE codsequencia = 1000759;

            INSERT INTO db_sysforkey VALUES (1010307,1009907,1,1741,0);
            INSERT INTO db_sysforkey VALUES (1010307,1009908,1,1010481,0);

            ---------------- REMOVENDO ESTRUTURA ANTERIOR ----------------
            UPDATE db_sysarqcamp SET codsequencia = null WHERE codarq = 1010308 AND codcam = 1009911;
            DELETE FROM db_syssequencia WHERE codsequencia = 1000757;
             
            UPDATE db_sysarqcamp SET codsequencia = null WHERE codarq = 1010309 AND codcam = 1009917;
            DELETE FROM db_syssequencia WHERE codsequencia = 1000758;

            DELETE FROM db_sysprikey WHERE codarq IN (1010308, 1010309);
            DELETE FROM db_sysarqcamp WHERE codarq IN (1010308, 1010309);
            DELETE FROM db_syscampo WHERE codcam IN (1009911, 1009912, 1009913, 1009914, 1009915, 1009916);
            DELETE FROM db_syscampo WHERE codcam IN (1009917, 1009918, 1009919);
            DELETE FROM db_sysarqmod WHERE codarq IN (1010308, 1010309);
            DELETE FROM db_sysarquivo WHERE codarq IN (1010308, 1010309);
STRING;

        $this->execute($sql);
    }

    public function down()
    {
        $this->downDDL();
        $this->downDicionarioDados();
    }
    
    public function downDDL()
    {
        $sSql = <<<STRING
            DROP TABLE      recursoshumanos.documentoportaria;
            DROP SEQUENCE   recursoshumanos.documentoportaria_rh235_sequencial_seq;

            DROP TABLE configuracoes.arquivoestorageassinaturas;
            DROP TABLE configuracoes.assinaturasdocumento;
            DROP TABLE configuracoes.arquivoestorage;
            DROP TABLE IF EXISTS configuracoes.assinaturadocumentodesignacao;
            DROP TABLE IF EXISTS recursoshumanos.portariaassentasituacao;

            DROP SEQUENCE   configuracoes.arquivoestorageassinaturas_db179_sequencial_seq;
            DROP SEQUENCE   configuracoes.assinaturasdocumento_db178_sequencial_seq;
            DROP SEQUENCE IF EXISTS configuracoes.assinaturadocumentodesignacao_db59_codigo_seq;
            DROP SEQUENCE IF EXISTS recursoshumanos.portariaassentasituacao_rh236_codigo_seq;

            DROP TYPE IF EXISTS situacao_portaria;
            
STRING;
        $this->execute($sSql);
    }

    public function downDicionarioDados()
    {
        $sSql = <<<STRING
        
            DELETE FROM db_itensmenu  WHERE  id_item = 10571;
            DELETE FROM db_menu  WHERE  id_item = 32  and  id_item_filho= 10571;

            DELETE FROM db_syssequencia WHERE codsequencia IN (1000759);
            DELETE FROM db_sysforkey where codarq = 1010307;
            DELETE FROM db_sysprikey WHERE codarq = 1010307;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010307;

            DELETE FROM db_syscampo WHERE codcam IN (1009907, 1009908, 1009923);

            DELETE FROM db_sysarqmod  WHERE codarq = 1010307;     
            DELETE FROM db_sysarquivo WHERE codarq = 1010307;


            DELETE FROM db_syscadind WHERE codind = 1008500 AND codcam = 1010804 AND sequen = 1;
            DELETE FROM db_sysindices WHERE codind = 1008500;
            DELETE FROM db_sysforkey WHERE codarq = 1010481;
            DELETE FROM db_sysprikey WHERE codarq = 1010481;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010481;
            DELETE FROM db_syscampo WHERE codcam IN (1010802, 1010804, 1010805, 1010815, 1010816);
            DELETE FROM db_sysarqmod WHERE codarq IN (1010481);
            DELETE FROM db_sysarquivo WHERE codarq IN (1010481);

            DELETE FROM db_syssequencia WHERE codsequencia IN (1000855);
            DELETE FROM db_syscadind WHERE codind = 1008501 AND codcam = 1010807 AND sequen = 1;
            DELETE FROM db_syscadind WHERE codind = 1008502 AND codcam = 1010808 AND sequen = 1;
            DELETE FROM db_sysindices WHERE codind IN (1008501, 1008502);
            DELETE FROM db_sysforkey WHERE codarq = 1010482;
            DELETE FROM db_sysprikey WHERE codarq = 1010482;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010482;
            DELETE FROM db_syscampo WHERE codcam IN (1010806, 1010807, 1010808, 1010809, 1010810);
            DELETE FROM db_sysarqmod WHERE codarq IN (1010482);
            DELETE FROM db_sysarquivo WHERE codarq IN (1010482);

            DELETE FROM db_syssequencia WHERE codsequencia = 1000856;
            DELETE FROM db_sysforkey WHERE codarq = 1010483;
            DELETE FROM db_sysprikey WHERE codarq = 1010483;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010483;
            DELETE FROM db_syscampo WHERE codcam IN (1010811, 1010812, 1010813, 1010814);
            DELETE FROM db_sysarqmod WHERE codarq IN (1010483);
            DELETE FROM db_sysarquivo WHERE codarq IN (1010483);

            DELETE FROM db_sysforkey WHERE codarq = 1010491;
            DELETE FROM db_sysprikey WHERE codarq = 1010491;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010491;
            DELETE FROM db_syscampo WHERE codcam IN (1010853, 1010854, 1010855, 1010856);
            DELETE FROM db_syssequencia WHERE codsequencia = 1000863;
            DELETE FROM db_sysarqmod WHERE codarq = 1010491;
            DELETE FROM db_sysarquivo WHERE codarq = 1010491;

            DELETE FROM db_syscampodef WHERE codcam = 1010858;
            DELETE FROM db_syscampodep WHERE codcam = 1010858;
            DELETE FROM db_syscadind WHERE codind = 1008503 AND codcam = 1010858;
            DELETE FROM db_sysindices WHERE codind IN (1008503);
            DELETE FROM db_sysforkey WHERE codarq = 1010492;
            DELETE FROM db_sysprikey WHERE codarq = 1010492;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010492;
            DELETE FROM db_syscampo WHERE codcam IN (1010857,1010858,1010859,1010860);
            DELETE FROM db_syssequencia WHERE codsequencia = 1000864;
            DELETE FROM db_sysarqmod WHERE codarq = 1010492;
            DELETE FROM db_sysarquivo WHERE codarq = 1010492;

STRING;

        $this->execute($sSql);
    }
}

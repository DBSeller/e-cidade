<?php

use Classes\PostgresMigration;

class M16990AssinantesDocumentos extends PostgresMigration
{
    public function up()
    {
        $this->upDDL();
        $this->upDicionarioDados();
    }

    public function down()
    {
        $this->downDDL();
        $this->downDicionarioDados();
    }

    public function upDDL()
    {
        $sql = <<<SQL
            CREATE TYPE permissao_assinante AS ENUM ('ASSINANTE', 'ADMIN');

            CREATE SEQUENCE configuracoes.assinantesdocumentos_db67_codigo_seq;

            CREATE TABLE configuracoes.assinantesdocumentos (
                db67_codigo      int8 default nextval('assinantesdocumentos_db67_codigo_seq'),
                db67_nome        varchar(250) not null,
                db67_tipo        char(2) default 'PF',
                db67_cpf_cnpj    varchar(14) not null,
                db67_id_usuario  int8 not null,
                db67_permissao   permissao_assinante default 'ASSINANTE'
            );

            ALTER TABLE configuracoes.assinantesdocumentos
                ADD CONSTRAINT assinantes_db_usuarios_fk FOREIGN KEY ("db67_id_usuario") REFERENCES "db_usuarios"("id_usuario");

            CREATE UNIQUE INDEX assinantesdocumentos_db67_cpf_cnpj_un_in ON configuracoes.assinantesdocumentos(db67_cpf_cnpj);
SQL;
        $this->execute($sql);
    }

    public function upDicionarioDados()
    {
        $sql = <<<SQL
            insert into db_sysarquivo values (1010637, 'assinantesdocumentos', 'Tabela para guardar permissão dos assinantes de documentos, se ADMIN ou ASSINANTE.', 'db67', '2020-12-07', '', 0, 'f', 'f', 't', 't' );
            insert into db_sysarqmod values (7,1010637);
            
            insert into db_syscampo values(1011915,'db67_codigo','int8','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1011916,'db67_nome','varchar(250)','Nome do assinante','', 'Nome',250,'f','t','f',0,'text','Nome');
            insert into db_syscampo values(1011917,'db67_tipo','char(2)','Tipo de assinante, se PF ou PJ','', 'Tipo',2,'f','t','f',0,'text','Tipo');
            insert into db_syscampodef values(1011917,'PF','');
            insert into db_syscampo values(1011918,'db67_cpf_cnpj','varchar(14)','CPF ou CNPJ do assinante.','', 'CPF/CNPJ',14,'f','t','f',0,'text','CPF/CNPJ');
            insert into db_syscampo values(1011919,'db67_id_usuario','int8','ID do usuario ao qual o assinante está vinculado','0', 'Usuario',19,'f','f','f',1,'text','Usuario');
            insert into db_syscampo values(1011920,'db67_permissao','varchar(9)','Permissão do assinante, se ADMIN ou ASSINANTE. O perfil de ADMIN pode dar manutenção nos documentos a assinar.','', 'Permissão',9,'f','t','f',0,'text','Permissão');
            insert into db_syscampodef values(1011920,'ASSINANTE','');
            
            delete from db_sysarqcamp where codarq = 1010637;
            insert into db_sysarqcamp values(1010637,1011915,1,0);
            insert into db_sysarqcamp values(1010637,1011916,2,0);
            insert into db_sysarqcamp values(1010637,1011917,3,0);
            insert into db_sysarqcamp values(1010637,1011918,4,0);
            insert into db_sysarqcamp values(1010637,1011919,5,0);
            insert into db_sysarqcamp values(1010637,1011920,6,0);
            
            delete from db_sysprikey where codarq = 1010637;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010637,1011915,1,1011915);
            
            delete from db_sysforkey where codarq = 1010637 and referen = 0;
            insert into db_sysforkey values(1010637,1011919,1,109,0);
            insert into db_sysindices values(1008615,'assinantesdocumentos_db67_cpf_cnpj_in',1010637,'0');
            
            insert into db_syscadind values(1008615,1011918,1);
            update db_sysindices set nomeind = 'assinantesdocumentos_db67_cpf_cnpj_un_in',campounico = '1' where codind = 1008615;

            insert into db_syssequencia values(1000981, 'assinantesdocumentos_db67_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000981 where codarq = 1010637 and codcam = 1011915;
                       
            /**
             * @todo FIX id_item esta dando conflito
             */
            -- insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228345 ,'Assinatura Digital' ,'Menu para manutenção de permissões de assinantes de documentos digitais' ,'' ,'1' ,'1' ,'Árvore de menu para manutenção de permissão dos assinantes de documentos digitais' ,'true' );
            -- insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228346 ,'Assinantes' ,'Menu para manutenção de permissões de assinantes' ,'con4_assinaturadigital_administradores.php' ,'1' ,'1' ,'Manu de manutenção das permissões dos assinantes de documentos digitais' ,'true' );
            -- insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (32, 228345, 1, 1);
            -- insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (228345, 228346, 1, 1);
SQL;

        $this->execute($sql);
    }
    
    public function downDDL()
    {
        $sql = <<<SQL_DDL
            DROP TABLE configuracoes.assinantesdocumentos;
            DROP SEQUENCE IF EXISTS configuracoes.assinantesdocumentos_db67_codigo_seq;

            DROP TYPE permissao_assinante;
SQL_DDL;
        $this->execute($sql);
    }

    public function downDicionarioDados()
    {
        $sql = <<<SQL
            --DELETE FROM db_menu WHERE id_item IN (xxx, xxx);
            --DELETE FROM db_itensmenu WHERE id_item IN (xxxx, xxx);

            DELETE FROM db_syssequencia WHERE codsequencia = 1000981;

            DELETE FROM db_syscadind WHERE codind = 1008615;
            DELETE FROM db_sysindices WHERE codind = 1008615;

            DELETE FROM db_sysforkey WHERE codarq = 1010637;
            DELETE FROM db_sysprikey WHERE codarq = 1010637;

            DELETE FROM db_sysarqcamp WHERE codarq = 1010637;
            DELETE FROM db_syscampodef WHERE codcam IN (1011920, 1011917);
            DELETE FROM db_syscampo WHERE codcam IN (1011915, 1011916, 1011917, 1011918, 1011919, 1011920);

            DELETE FROM db_sysarqmod WHERE codarq = 1010637;
            DELETE FROM db_sysarquivo WHERE codarq = 1010637;
SQL;

        $this->execute($sql);
    }
}

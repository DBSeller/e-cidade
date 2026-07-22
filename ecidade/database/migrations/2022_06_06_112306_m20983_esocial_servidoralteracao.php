<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20983EsocialServidoralteracao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            insert into configuracoes.db_sysarquivo values (1010939, 'servidoralteracao', 'Tabela de registros de alteracao/envio do servidor em diferentes layout. Essa tabela é responsavel em salvar a data de alteração para a geração do evento.', 'eso38', '2022-05-30', 'Alterações do Servidor', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (81,1010939);
            insert into configuracoes.db_syscampo values(1014178,'eso38_sequencial','int4','Código Sequencial da tabela','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');
            insert into configuracoes.db_syscampo values(1014179,'eso38_s2205data','date','Data de alteração de dados para o evento S2205 ','null', 'Data S2205',10,'t','f','f',1,'text','Data S2205');
            insert into configuracoes.db_syscampo values(1014180,'eso38_s2205processado','bool','Define se o evento S2205 dessa alteração foi processado','f', 'Processamento S2205',1,'f','f','f',5,'text','Processamento S2205');
            insert into configuracoes.db_syscampodef values(1014180,'f','');
            insert into configuracoes.db_syscampo values(1014181,'eso38_s2206data','date','Data da alteração para o evento S2206','null', 'Data S2206',10,'t','f','f',1,'text','Data S2206');
            insert into configuracoes.db_syscampo values(1014182,'eso38_s2206processado','bool','Informa se foi realizado o processamento do S2206 desse registro','f', 'Processamento S2206',1,'f','f','f',5,'text','Processamento S2206');
            insert into configuracoes.db_syscampodef values(1014182,'f','');
            insert into configuracoes.db_syscampo values(1014183,'eso38_s2306data','date','Data de alteração para o evento S2306','null', 'Data S2306',10,'t','f','f',1,'text','Data S2306');
            insert into configuracoes.db_syscampo values(1014184,'eso38_s2306processado','bool','Informa se foi realizado o processamento do S2306','f', 'Processamento S2306',1,'f','f','f',5,'text','Processamento S2306');
            insert into configuracoes.db_syscampodef values(1014184,'f','');
            insert into configuracoes.db_syscampo values(1014185,'eso38_s2405data','date','Data de alteração para o evento S2405','null', 'Data S2405',10,'t','f','f',1,'text','Data S2405');
            insert into configuracoes.db_syscampo values(1014186,'eso38_s2405processado','bool','Informa se o evento S2405 foi processado','f', 'Processamento S2405',1,'f','f','f',5,'text','Processamento S2405');
            insert into configuracoes.db_syscampodef values(1014186,'f','');
            insert into configuracoes.db_syscampo values(1014187,'eso38_s2416data','date','Data de alteração do evento S2416','null', 'Data S2416',10,'t','f','f',1,'text','Data S2416');
            insert into configuracoes.db_syscampo values(1014188,'eso38_s2416processado','bool','Informa se foi processado o evento S2416','f', 'Processamento S2416',1,'f','f','f',5,'text','Processamento S2416');
            insert into configuracoes.db_syscampodef values(1014188,'f','');
            insert into configuracoes.db_syscampo values(1014189,'eso38_matricula','int4','Matrícula do servidor','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
            insert into configuracoes.db_sysarqcamp values(1010939,1014178,1,0);
            insert into configuracoes.db_sysarqcamp values(1010939,1014189,2,0);
            insert into configuracoes.db_sysarqcamp values(1010939,1014179,3,0);
            insert into configuracoes.db_sysarqcamp values(1010939,1014180,4,0);
            insert into configuracoes.db_sysarqcamp values(1010939,1014181,5,0);
            insert into configuracoes.db_sysarqcamp values(1010939,1014182,6,0);
            insert into configuracoes.db_sysarqcamp values(1010939,1014183,7,0);
            insert into configuracoes.db_sysarqcamp values(1010939,1014184,8,0);
            insert into configuracoes.db_sysarqcamp values(1010939,1014185,9,0);
            insert into configuracoes.db_sysarqcamp values(1010939,1014186,10,0);
            insert into configuracoes.db_sysarqcamp values(1010939,1014187,11,0);
            insert into configuracoes.db_sysarqcamp values(1010939,1014188,12,0);
            delete from configuracoes.db_sysprikey where codarq = 1010939;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010939,1014178,1,1014178);
            insert into configuracoes.db_sysforkey values(1010939,1014189,1,1153,0);
            insert into configuracoes.db_syssequencia values(1001066, 'servidoralteracao_eso38_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001066 where codarq = 1010939 and codcam = 1014178;

            -- Criando  sequences
            CREATE SEQUENCE esocial.servidoralteracao_eso38_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            -- TABELAS E ESTRUTURA

            -- Módulo: esocial
            CREATE TABLE esocial.servidoralteracao(
                eso38_sequencial int4 NOT NULL default 0,
                eso38_matricula int4 NOT NULL default 0,
                eso38_s2205data date default null,
                eso38_s2205processado bool NOT NULL default 'f',
                eso38_s2206data date default null,
                eso38_s2206processado bool NOT NULL default 'f',
                eso38_s2306data date default null,
                eso38_s2306processado bool NOT NULL default 'f',
                eso38_s2405data date default null,
                eso38_s2405processado bool NOT NULL default 'f',
                eso38_s2416data date default null,
                eso38_s2416processado bool default 'f',
                CONSTRAINT servidoralteracao_sequ_pk PRIMARY KEY (eso38_sequencial)
            );

            -- CHAVE ESTRANGEIRA
            ALTER TABLE esocial.servidoralteracao
                ADD CONSTRAINT servidoralteracao_matricula_fk FOREIGN KEY (eso38_matricula)
                REFERENCES rhpessoal;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
            delete from configuracoes.db_syssequencia where codsequencia = 1001066;
            delete from configuracoes.db_sysforkey where codarq = 1010939;
            delete from configuracoes.db_sysprikey where codarq = 1010939;
            delete from configuracoes.db_sysarqcamp where codarq = 1010939;
            delete from configuracoes.db_syscampodef where codcam in (1014188, 1014186, 1014184, 1014182, 1014180);
            delete from configuracoes.db_syscampo where codcam between 1014178 and 1014189;
            delete from configuracoes.db_sysarqmod where codarq = 1010939;
            delete from configuracoes.db_sysarquivo where codarq = 1010939;

            drop table esocial.servidoralteracao;
            drop sequence esocial.servidoralteracao_eso38_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

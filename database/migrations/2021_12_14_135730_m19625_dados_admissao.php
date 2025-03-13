<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19625DadosAdmissao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function upEstrutura()
    {
        $sql = <<<SQL
        -- Criando  sequences
        CREATE SEQUENCE recursoshumanos.rhadmissaodado_h25_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;

        -- TABELAS E ESTRUTURA

        -- Módulo: recursoshumanos
        CREATE TABLE IF not exists recursoshumanos.rhadmissaodado(
        h25_sequencial int4 default nextval('rhadmissaodado_h25_sequencial_seq'),
        h25_nrdispositivo varchar(80)  default '',
        h25_nomeacao date default null,
        h25_irfonte int4 default 0,
        h25_referenciair varchar(20) default '',
        h25_portariaaposentadoria varchar(20) default '',
        h25_dataaposentadoria date default null,
        h25_contaraposentadoria date default null,
        h25_processoaposentadoria int4 default 0,
        h25_nrprocessoaposentadoria varchar(20) default '',
        h25_anoprocessoaposentadoria int4 default 0,
        h25_portariaexoneracao varchar(20) default '',
        h25_dataexoneracao date default null,
        h25_contarexoneracao date default null,
        h25_processoexoneracao int4 default 0,
        h25_nrprocessoexoneracao varchar(20) default '',
        h25_anoprocessoexoneracao int4 default 0,
        h25_portariareintegracao varchar(20) default '',
        h25_datareintegracao date  default null,
        h25_processoreintegracao int4 default 0,
        h25_nrprocessoreintegracao varchar(20) default '',
        h25_anoprocessoreintegracao int4 default 0,
        h25_regist int4 default 0,
        h25_instit int4 default 0,
        h25_publicacaoexoneracao date default null,
        h25_hipleg int4  default 0,
        h25_dtbase int4 default 0,
        CONSTRAINT rhadmissaodado_sequ_pk PRIMARY KEY (h25_sequencial));

        -- CHAVE ESTRANGEIRA

        ALTER TABLE recursoshumanos.rhadmissaodado
        ADD CONSTRAINT rhadmissaodado_regist_fk FOREIGN KEY (h25_regist)
        REFERENCES admissao;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function upDicionario() {
        $sql = <<<SQL
            insert into configuracoes.db_sysarquivo values (1010841, 'rhadmissaodado', 'Dados de Admissão', 'h25', '2021-12-14', 'Dados de Admissão', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1010841);
            insert into configuracoes.db_sysarqarq values (524,1010841);
            insert into configuracoes.db_syscampo values(1013515,'h25_sequencial','int4','Código Sequencial','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');
            insert into configuracoes.db_syscampo values(1013516,'h25_nrdispositivo','varchar(80)','Número do Dispositivo','', 'Número do Dispositivo',80,'f','t','f',0,'text','Número do Dispositivo');
            insert into configuracoes.db_syscampo values(1013517,'h25_nomeacao','date','Nomeação','null', 'Nomeação',10,'f','f','f',1,'text','Nomeação');
            insert into configuracoes.db_syscampo values(1013518,'h25_irfonte','int4','Declaração de IR na fonte','0', 'Declaração de IR na fonte',10,'f','f','f',1,'text','Declaração de IR na fonte');
            insert into configuracoes.db_syscampo values(1013519,'h25_referenciair','varchar(20)','Referência','', 'Referência',20,'f','t','f',0,'text','Referência');
            insert into configuracoes.db_syscampo values(1013520,'h25_portariaaposentadoria','varchar(20)','Portaria da Aposentadoria','', 'Portaria da Aposentadoria',20,'f','t','f',0,'text','Portaria da Aposentadoria');
            insert into configuracoes.db_syscampo values(1013521,'h25_dataaposentadoria','date','Data da Aposentadoria','null', 'Data da Aposentadoria',10,'f','f','f',1,'text','Data da Aposentadoria');
            insert into configuracoes.db_syscampo values(1013522,'h25_contaraposentadoria','date','Data que começa a contar a aposentadoria','null', 'A contar de',10,'f','f','f',1,'text','A contar de');
            insert into configuracoes.db_syscampo values(1013523,'h25_processoaposentadoria','int4','Indica se a aposentadoria possui processo.','0', 'Processo Aposentadoria',10,'f','f','f',1,'text','Processo Aposentadoria');
            insert into configuracoes.db_syscampo values(1013524,'h25_nrprocessoaposentadoria','varchar(20)','Número do Processo da Aposentadoria','', 'Número do Processo',20,'f','t','f',0,'text','Número do Processo');
            insert into configuracoes.db_syscampo values(1013525,'h25_anoprocessoaposentadoria','int4','Ano do processo de Aposentadoria','0', 'Ano',10,'f','f','f',1,'text','Ano');
            insert into configuracoes.db_syscampo values(1013526,'h25_portariaexoneracao','varchar(20)','Portaria da Exoneração','', 'Portaria da Exoneração',20,'f','t','f',0,'text','Portaria da Exoneração');
            insert into configuracoes.db_syscampo values(1013527,'h25_dataexoneracao','date','Data da Exoneração','null', 'Data da Exoneração',10,'f','f','f',1,'text','Data da Exoneração');
            insert into configuracoes.db_syscampo values(1013528,'h25_contarexoneracao','date','Data que inicia a exoneração','null', 'A contar de',10,'f','f','f',1,'text','A contar de');
            insert into configuracoes.db_syscampo values(1013529,'h25_processoexoneracao','int4','Indica se existe processo de exoneração.','0', 'Processo Exoneração',10,'f','f','f',1,'text','Processo Exoneração');
            insert into configuracoes.db_syscampo values(1013530,'h25_nrprocessoexoneracao','varchar(20)','Número do Processo de Exoneração','', 'Número do Processo',20,'f','t','f',0,'text','Número do Processo');
            insert into configuracoes.db_syscampo values(1013531,'h25_anoprocessoexoneracao','int4','Ano do processo de exoneração','0', 'Ano',10,'f','f','f',1,'text','Ano');
            insert into configuracoes.db_syscampo values(1013532,'h25_portariareintegracao','varchar(20)','Portaria da Reintegração','', 'Portaria da Reintegração',20,'f','t','f',0,'text','Portaria da Reintegração');
            insert into configuracoes.db_syscampo values(1013533,'h25_datareintegracao','date','Data da Reintegração','null', 'Data da Reintegração',10,'f','f','f',1,'text','Data da Reintegração');
            insert into configuracoes.db_syscampo values(1013534,'h25_processoreintegracao','int4','Processo Reintegração','0', 'Processo Reintegração',10,'f','f','f',1,'text','Processo Reintegração');
            insert into configuracoes.db_syscampo values(1013535,'h25_numeroprocessoreintegracao','varchar(20)','Número do Processo da Reintegração','', 'Número do Processo',20,'f','t','f',0,'text','Número do Processo');
            insert into configuracoes.db_syscampo values(1013536,'h25_anoprocessoreintegracao','int4','Ano do processo de reintegração','0', 'Ano',10,'f','f','f',1,'text','Ano');
            insert into configuracoes.db_syscampo values(1013537,'h25_admissao','int4','Código da Admissão','0', 'Código da Admissão',10,'f','f','f',1,'text','Código da Admissão');
            insert into configuracoes.db_syscampo values(1013538,'h25_publicacaoexoneracao','date','Data da Publicação da Exoneração','null', 'Data da Publicação',10,'f','f','f',1,'text','Data da Publicação');
            delete from configuracoes.db_sysarqcamp where codarq = 1010841;
            insert into configuracoes.db_sysarqcamp values(1010841,1013515,1,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013516,2,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013517,3,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013518,4,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013519,5,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013520,6,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013521,7,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013522,8,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013523,9,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013524,10,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013525,11,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013526,12,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013527,13,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013528,14,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013529,15,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013530,16,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013531,17,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013532,18,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013533,19,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013534,20,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013535,21,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013536,22,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013537,23,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013538,24,0);
            insert into configuracoes.db_syssequencia values(1001023, 'rhadmissaodado_h25_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001023 where codarq = 1010841 and codcam = 1013515;
            delete from configuracoes.db_sysprikey where codarq = 1010841;
            delete from configuracoes.db_sysprikey where codarq = 1010841;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010841,1013515,1,1013515);
            delete from configuracoes.db_sysarqcamp where codarq = 1010841;
            insert into configuracoes.db_sysarqcamp values(1010841,1013515,1,1001023);
            insert into configuracoes.db_sysarqcamp values(1010841,1013516,2,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013517,3,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013518,4,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013519,5,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013520,6,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013521,7,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013522,8,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013523,9,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013524,10,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013525,11,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013526,12,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013527,13,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013528,14,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013529,15,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013530,16,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013531,17,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013532,18,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013533,19,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013534,20,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013535,21,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013536,22,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013537,23,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013538,24,0);
            delete from configuracoes.db_sysforkey where codarq = 1010841 and referen = 0;
            insert into configuracoes.db_sysforkey values(1010841,1013537,1,524,0);
            update configuracoes.db_syscampo set nomecam = 'h25_regist', conteudo = 'int4', descricao = 'Código da Matrícula', valorinicial = '0', rotulo = 'Código da Matrícula', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Código da Matrícula' where codcam = 1013537;
            delete from configuracoes.db_syscampodep where codcam = 1013537;
            delete from configuracoes.db_syscampodef where codcam = 1013537;

            -- atualizando o dicionario devido os campos cadastrados como obrigatorios
            update configuracoes.db_syscampo set nomecam = 'h25_nrdispositivo', conteudo = 'varchar(80)', descricao = 'Número do Dispositivo', valorinicial = '', rotulo = 'Número do Dispositivo', nulo = 't', tamanho = 80, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Número do Dispositivo' where codcam = 1013516;
            update configuracoes.db_syscampo set nomecam = 'h25_nomeacao', conteudo = 'date', descricao = 'Data de Nomeação', valorinicial = 'null', rotulo = 'Nomeação', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Nomeação' where codcam = 1013517;
            update configuracoes.db_syscampo set nomecam = 'h25_irfonte', conteudo = 'int4', descricao = 'Entregou declaração de IR Fonte?', valorinicial = '0', rotulo = 'Declaração de IR na fonte', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Declaração de IR na fonte' where codcam = 1013518;
            update configuracoes.db_syscampo set nomecam = 'h25_referenciair', conteudo = 'varchar(20)', descricao = 'Referência', valorinicial = '', rotulo = 'Referência', nulo = 't', tamanho = 20, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Referência' where codcam = 1013519;
            update configuracoes.db_syscampo set nomecam = 'h25_portariaaposentadoria', conteudo = 'varchar(20)', descricao = 'Portaria da Aposentadoria', valorinicial = '', rotulo = 'Portaria da Aposentadoria', nulo = 't', tamanho = 20, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Portaria da Aposentadoria' where codcam = 1013520;
            update configuracoes.db_syscampo set nomecam = 'h25_dataaposentadoria', conteudo = 'date', descricao = 'Data da Aposentadoria', valorinicial = 'null', rotulo = 'Data da Aposentadoria', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data da Aposentadoria' where codcam = 1013521;
            update configuracoes.db_syscampo set nomecam = 'h25_contaraposentadoria', conteudo = 'date', descricao = 'Data que começa a contar a aposentadoria', valorinicial = 'null', rotulo = 'A contar de', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'A contar de' where codcam = 1013522;
            update configuracoes.db_syscampo set nomecam = 'h25_processoaposentadoria', conteudo = 'int4', descricao = 'Indica se a aposentadoria possui processo.', valorinicial = '0', rotulo = 'Processo Aposentadoria', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Processo Aposentadoria' where codcam = 1013523;
            update configuracoes.db_syscampo set nomecam = 'h25_nrprocessoaposentadoria', conteudo = 'varchar(20)', descricao = 'Número do Processo da Aposentadoria', valorinicial = '', rotulo = 'Número do Processo', nulo = 't', tamanho = 20, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Número do Processo' where codcam = 1013524;
            update configuracoes.db_syscampo set nomecam = 'h25_anoprocessoaposentadoria', conteudo = 'int4', descricao = 'Ano do processo de Aposentadoria', valorinicial = '0', rotulo = 'Ano', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Ano' where codcam = 1013525;
            update configuracoes.db_syscampo set nomecam = 'h25_portariaexoneracao', conteudo = 'varchar(20)', descricao = 'Portaria da Exoneração', valorinicial = '', rotulo = 'Portaria da Exoneração', nulo = 't', tamanho = 20, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Portaria da Exoneração' where codcam = 1013526;
            update configuracoes.db_syscampo set nomecam = 'h25_dataexoneracao', conteudo = 'date', descricao = 'Data da Exoneração', valorinicial = 'null', rotulo = 'Data da Exoneração', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data da Exoneração' where codcam = 1013527;
            update configuracoes.db_syscampo set nomecam = 'h25_contarexoneracao', conteudo = 'date', descricao = 'Data que inicia a exoneração', valorinicial = 'null', rotulo = 'A contar de', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'A contar de' where codcam = 1013528;
            update configuracoes.db_syscampo set nomecam = 'h25_processoexoneracao', conteudo = 'int4', descricao = 'Indica se existe processo de exoneração.', valorinicial = '0', rotulo = 'Processo Exoneração', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Processo Exoneração' where codcam = 1013529;
            update configuracoes.db_syscampo set nomecam = 'h25_nrprocessoexoneracao', conteudo = 'varchar(20)', descricao = 'Número do Processo de Exoneração', valorinicial = '', rotulo = 'Número do Processo', nulo = 't', tamanho = 20, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Número do Processo' where codcam = 1013530;
            update configuracoes.db_syscampo set nomecam = 'h25_anoprocessoexoneracao', conteudo = 'int4', descricao = 'Ano do processo de exoneração', valorinicial = '0', rotulo = 'Ano', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Ano' where codcam = 1013531;
            update configuracoes.db_syscampo set nomecam = 'h25_portariareintegracao', conteudo = 'varchar(20)', descricao = 'Portaria da Reintegração', valorinicial = '', rotulo = 'Portaria da Reintegração', nulo = 't', tamanho = 20, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Portaria da Reintegração' where codcam = 1013532;
            update configuracoes.db_syscampo set nomecam = 'h25_datareintegracao', conteudo = 'date', descricao = 'Data da Reintegração', valorinicial = 'null', rotulo = 'Data da Reintegração', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data da Reintegração' where codcam = 1013533;
            update configuracoes.db_syscampo set nomecam = 'h25_processoreintegracao', conteudo = 'int4', descricao = 'Processo Reintegração', valorinicial = '0', rotulo = 'Processo Reintegração', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Processo Reintegração' where codcam = 1013534;
            update configuracoes.db_syscampo set nomecam = 'h25_nrprocessoreintegracao', conteudo = 'varchar(20)', descricao = 'Número do Processo da Reintegração', valorinicial = '', rotulo = 'Número do Processo', nulo = 't', tamanho = 20, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Número do Processo' where codcam = 1013535;
            update configuracoes.db_syscampo set nomecam = 'h25_anoprocessoreintegracao', conteudo = 'int4', descricao = 'Ano do processo de reintegração', valorinicial = '0', rotulo = 'Ano', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Ano' where codcam = 1013536;
            update configuracoes.db_syscampo set nomecam = 'h25_publicacaoexoneracao', conteudo = 'date', descricao = 'Data da Publicação da Exoneração', valorinicial = 'null', rotulo = 'Data da Publicação', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Data da Publicação' where codcam = 1013538;

            insert into configuracoes.db_syscampo values(1013550,'h25_instit','int4','Código da Instituição','0', 'Código da Instituição',10,'f','f','f',1,'text','Código da Instituição');
            delete from configuracoes.db_sysarqcamp where codarq = 1010841;
            insert into configuracoes.db_sysarqcamp values(1010841,1013515,1,1001023);
            insert into configuracoes.db_sysarqcamp values(1010841,1013516,2,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013517,3,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013518,4,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013519,5,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013520,6,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013521,7,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013522,8,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013523,9,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013524,10,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013525,11,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013526,12,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013527,13,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013528,14,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013529,15,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013530,16,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013531,17,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013532,18,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013533,19,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013534,20,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013535,21,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013536,22,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013537,23,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013538,24,0);
            insert into configuracoes.db_sysarqcamp values(1010841,1013550,25,0);

            insert into db_syscampo values(1013551,'h25_dtbase','int4','Data base adicionada devido ao eSocial.','0', 'Data Base',10,'t','f','f',1,'text','Data Base');
            insert into db_syscampo values(1013555,'h25_hipleg','int4','Hipótese legal para contratação de trabalhador temporário. Campo para envio do eSocial.','0', 'Hipótese contratação temporária',10,'t','f','f',1,'text','Hipótese contratação temporária');
            delete from db_sysarqcamp where codarq = 1010841;
            insert into db_sysarqcamp values(1010841,1013515,1,1001023);
            insert into db_sysarqcamp values(1010841,1013516,2,0);
            insert into db_sysarqcamp values(1010841,1013517,3,0);
            insert into db_sysarqcamp values(1010841,1013518,4,0);
            insert into db_sysarqcamp values(1010841,1013519,5,0);
            insert into db_sysarqcamp values(1010841,1013520,6,0);
            insert into db_sysarqcamp values(1010841,1013521,7,0);
            insert into db_sysarqcamp values(1010841,1013522,8,0);
            insert into db_sysarqcamp values(1010841,1013523,9,0);
            insert into db_sysarqcamp values(1010841,1013524,10,0);
            insert into db_sysarqcamp values(1010841,1013525,11,0);
            insert into db_sysarqcamp values(1010841,1013526,12,0);
            insert into db_sysarqcamp values(1010841,1013527,13,0);
            insert into db_sysarqcamp values(1010841,1013528,14,0);
            insert into db_sysarqcamp values(1010841,1013529,15,0);
            insert into db_sysarqcamp values(1010841,1013530,16,0);
            insert into db_sysarqcamp values(1010841,1013531,17,0);
            insert into db_sysarqcamp values(1010841,1013532,18,0);
            insert into db_sysarqcamp values(1010841,1013533,19,0);
            insert into db_sysarqcamp values(1010841,1013534,20,0);
            insert into db_sysarqcamp values(1010841,1013535,21,0);
            insert into db_sysarqcamp values(1010841,1013536,22,0);
            insert into db_sysarqcamp values(1010841,1013537,23,0);
            insert into db_sysarqcamp values(1010841,1013538,24,0);
            insert into db_sysarqcamp values(1010841,1013550,25,0);
            insert into db_sysarqcamp values(1010841,1013555,26,0);
            insert into db_sysarqcamp values(1010841,1013551,27,0);

            update db_syscampo set nomecam = 'h07_tipadm', conteudo = 'varchar(2)', descricao = 'tipo de admissao', valorinicial = '', rotulo = 'tipo de admissao', nulo = 't', tamanho = 2, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'tipo de admissao' where codcam = 3623;
            update db_syscampo set nomecam = 'h07_nrato', conteudo = 'varchar(12)', descricao = 'Número do ato.', valorinicial = '', rotulo = 'No. do Ato', nulo = 't', tamanho = 12, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'No. do Ato' where codcam = 4612;
            update db_syscampo set nomecam = 'h07_icon', conteudo = 'varchar(1)', descricao = 'Concurso', valorinicial = '', rotulo = 'Concurso', nulo = 't', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Concurso' where codcam = 3628;
            update db_syscampo set nomecam = 'h07_dpubl', conteudo = 'date', descricao = 'Data da publicação no imprensa oficial.', valorinicial = 'null', rotulo = 'Publicação', nulo = 't', tamanho = 8, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Publicação' where codcam = 4615;
            update db_syscampo set nomecam = 'h07_fundam', conteudo = 'int4', descricao = 'Fundamentação legal.', valorinicial = 'null', rotulo = 'Fundamentação', nulo = 't', tamanho = 5, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Fundamentação' where codcam = 4616;
            update db_syscampo set nomecam = 'h07_dato', conteudo = 'date', descricao = 'Data do Ato', valorinicial = 'null', rotulo = 'Data do Ato', nulo = 't', tamanho = 8, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Data do Ato' where codcam = 3624;
            update db_syscampo set nomecam = 'h07_cant', conteudo = 'varchar(5)', descricao = 'Cargo Anterior', valorinicial = '', rotulo = 'Cargo Anterior', nulo = 't', tamanho = 5, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Cargo Anterior' where codcam = 3625;
            update db_syscampo set nomecam = 'h07_ddem', conteudo = 'date', descricao = 'Data de Demissão', valorinicial = 'null', rotulo = 'Demissão', nulo = 't', tamanho = 8, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Demissão' where codcam = 3627;
            update db_syscampo set nomecam = 'h07_class', conteudo = 'int4', descricao = 'Classificação', valorinicial = 'null', rotulo = 'Classificação', nulo = 't', tamanho = 5, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Classificação' where codcam = 3630;
            update db_syscampo set nomecam = 'h07_termin', conteudo = 'date', descricao = 'Término do contrato, caso seja temporário.', valorinicial = 'null', rotulo = 'Término', nulo = 't', tamanho = 8, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Término' where codcam = 4619;
            update db_syscampo set nomecam = 'h07_refe', conteudo = 'int4', descricao = 'Referência (Concurso)', valorinicial = 'null', rotulo = 'Referência (Concurso)', nulo = 't', tamanho = 5, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Referência (Concurso)' where codcam = 3631;
            update db_syscampo set nomecam = 'h07_area', conteudo = 'int4', descricao = 'Codigo da Area', valorinicial = 'null', rotulo = 'Codigo da Area', nulo = 't', tamanho = 5, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Codigo da Area' where codcam = 3632;
            update db_syscampo set nomecam = 'h07_justif', conteudo = 'varchar(100)', descricao = 'Justificativa para a contratação, no caso de contrato temporário.', valorinicial = '', rotulo = 'Justificativa', nulo = 't', tamanho = 100, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Justificativa' where codcam = 4620;
            update db_syscampo set nomecam = 'h07_tempor', conteudo = 'bool', descricao = 'Contrato temporário (S/N).', valorinicial = 'f', rotulo = 'Temporário', nulo = 't', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 5, tipoobj = 'text', rotulorel = 'Temporário' where codcam = 4618;
            update db_syscampo set nomecam = 'h07_nrfich', conteudo = 'varchar(6)', descricao = 'Número da ficha funcional.', valorinicial = '', rotulo = 'Ficha', nulo = 't', tamanho = 6, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Ficha' where codcam = 4613;
            update db_syscampo set nomecam = 'h07_defet', conteudo = 'date', descricao = 'Data da efetivação.', valorinicial = 'null', rotulo = 'Efetivação', nulo = 't', tamanho = 8, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Efetivação' where codcam = 4617;
            update db_syscampo set nomecam = 'h07_impofi', conteudo = 'varchar(30)', descricao = 'Órgão onde foi notificado o concurso.', valorinicial = '', rotulo = 'Imprensa Oficial', nulo = 't', tamanho = 30, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Imprensa Oficial' where codcam = 4614;
            update db_syscampo set nomecam = 'h07_ires', conteudo = 'varchar(1)', descricao = 'Responsavel', valorinicial = '', rotulo = 'Responsavel', nulo = 't', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Responsavel' where codcam = 3629;
            update db_syscampo set nomecam = 'h07_fundam', conteudo = 'int4', descricao = 'Fundamentação legal.', valorinicial = 'null', rotulo = 'Fundamentação', nulo = 't', tamanho = 5, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Fundamentação' where codcam = 4616;

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
        $this->downDicionario();
        $this->dowEstrutura();
    }

    public function downDicionario()
    {
        $sql = <<<SQL
        delete from configuracoes.db_sysforkey where codarq = 1010841;
        delete from configuracoes.db_sysarqcamp where codarq = 1010841;
        delete from configuracoes.db_sysprikey where codarq = 1010841;
        delete from configuracoes.db_sysarqcamp where codarq = 1010841;
        delete from configuracoes.db_syssequencia where codsequencia = 1001023;
        delete from configuracoes.db_syscampo where codcam between 1013515 and 1013538;
        delete from configuracoes.db_syscampo where codcam in (1013550,1013551,1013555);
        delete from configuracoes.db_sysarqarq where codarqpai = 524 and codarq = 1010841;
        delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1010841;
        delete from configuracoes.db_sysarquivo where codarq = 1010841;

        update db_syscampo set nomecam = 'h07_tipadm', conteudo = 'varchar(2)', descricao = 'tipo de admissao', valorinicial = '', rotulo = 'tipo de admissao', nulo = 'f', tamanho = 2, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'tipo de admissao' where codcam = 3623;
        update db_syscampo set nomecam = 'h07_nrato', conteudo = 'varchar(12)', descricao = 'Número do ato.', valorinicial = '', rotulo = 'No. do Ato', nulo = 'f', tamanho = 12, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'No. do Ato' where codcam = 4612;
        update db_syscampo set nomecam = 'h07_icon', conteudo = 'varchar(1)', descricao = 'Concurso', valorinicial = '', rotulo = 'Concurso', nulo = 'f', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Concurso' where codcam = 3628;
        update db_syscampo set nomecam = 'h07_dpubl', conteudo = 'date', descricao = 'Data da publicação no imprensa oficial.', valorinicial = 'null', rotulo = 'Publicação', nulo = 'f', tamanho = 8, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Publicação' where codcam = 4615;
        update db_syscampo set nomecam = 'h07_fundam', conteudo = 'int4', descricao = 'Fundamentação legal.', valorinicial = '0', rotulo = 'Fundamentação', nulo = 'f', tamanho = 5, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Fundamentação' where codcam = 4616;
        update db_syscampo set nomecam = 'h07_dato', conteudo = 'date', descricao = 'Data do Ato', valorinicial = 'null', rotulo = 'Data do Ato', nulo = 'f', tamanho = 8, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Data do Ato' where codcam = 3624;
        update db_syscampo set nomecam = 'h07_cant', conteudo = 'varchar(5)', descricao = 'Cargo Anterior', valorinicial = '', rotulo = 'Cargo Anterior', nulo = 'f', tamanho = 5, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Cargo Anterior' where codcam = 3625;
        update db_syscampo set nomecam = 'h07_ddem', conteudo = 'date', descricao = 'Data de Demissão', valorinicial = 'null', rotulo = 'Demissão', nulo = 'f', tamanho = 8, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Demissão' where codcam = 3627;
        update db_syscampo set nomecam = 'h07_class', conteudo = 'int4', descricao = 'Classificação', valorinicial = '0', rotulo = 'Classificação', nulo = 'f', tamanho = 5, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Classificação' where codcam = 3630;
        update db_syscampo set nomecam = 'h07_termin', conteudo = 'date', descricao = 'Término do contrato, caso seja temporário.', valorinicial = 'null', rotulo = 'Término', nulo = 'f', tamanho = 8, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Término' where codcam = 4619;
        update db_syscampo set nomecam = 'h07_refe', conteudo = 'int4', descricao = 'Referência (Concurso)', valorinicial = '0', rotulo = 'Referência (Concurso)', nulo = 'f', tamanho = 5, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Referência (Concurso)' where codcam = 3631;
        update db_syscampo set nomecam = 'h07_area', conteudo = 'int4', descricao = 'Codigo da Area', valorinicial = '0', rotulo = 'Codigo da Area', nulo = 'f', tamanho = 5, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Codigo da Area' where codcam = 3632;
        update db_syscampo set nomecam = 'h07_justif', conteudo = 'varchar(100)', descricao = 'Justificativa para a contratação, no caso de contrato temporário.', valorinicial = '', rotulo = 'Justificativa', nulo = 'f', tamanho = 100, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Justificativa' where codcam = 4620;
        update db_syscampo set nomecam = 'h07_tempor', conteudo = 'bool', descricao = 'Contrato temporário (S/N).', valorinicial = 'f', rotulo = 'Temporário', nulo = 'f', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 5, tipoobj = 'text', rotulorel = 'Temporário' where codcam = 4618;
        update db_syscampo set nomecam = 'h07_nrfich', conteudo = 'varchar(6)', descricao = 'Número da ficha funcional.', valorinicial = '', rotulo = 'Ficha', nulo = 'f', tamanho = 6, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Ficha' where codcam = 4613;
        update db_syscampo set nomecam = 'h07_defet', conteudo = 'date', descricao = 'Data da efetivação.', valorinicial = 'null', rotulo = 'Efetivação', nulo = 'f', tamanho = 8, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Efetivação' where codcam = 4617;
        update db_syscampo set nomecam = 'h07_impofi', conteudo = 'varchar(30)', descricao = 'Órgão onde foi notificado o concurso.', valorinicial = '', rotulo = 'Imprensa Oficial', nulo = 'f', tamanho = 30, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Imprensa Oficial' where codcam = 4614;
        update db_syscampo set nomecam = 'h07_ires', conteudo = 'varchar(1)', descricao = 'Responsavel', valorinicial = '', rotulo = 'Responsavel', nulo = 'f', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Responsavel' where codcam = 3629;
        update db_syscampo set nomecam = 'h07_fundam', conteudo = 'int4', descricao = 'Fundamentação legal.', valorinicial = '0', rotulo = 'Fundamentação', nulo = 'f', tamanho = 5, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Fundamentação' where codcam = 4616;        
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function dowEstrutura()
    {
        $sql = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhadmissaodado CASCADE;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhadmissaodado_h25_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M23143TributosProcessosTrabalhista extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $this->upItemMenu();

        // $this->upDicionarioTabelaRhpessoalProcessoJudicialEsocial();
        // $this->upEstruturaTabelaRhpessoalProcessoJudicialEsocial();

        // $this->upDicionarioTabelaRhProcessoRubrica();
        // $this->upEstruturaTabelaRhProcessoRubrica();

        // $this->upDicionarioTabelaRhprocessoTributoBase();
        // $this->upEstruturaTabelaRhprocessoTributoBase();

        // $this->upEstruturaTabelaRhProcessoTributoContribuicao();
        // $this->upDicionarioTabelaRhProcessoTributoContribuicao();

        // $this->upDicionarioTabelaRhProcessoTributoIrrf();
        // $this->upEstruturaTabelaRhProcessoTributoIrrf();

        // $this->upDicionarioTabelaThprocessoexclusao();
        // $this->upEstruturaTabelaRhprocessoexclusao();

        // $this->upDicionarioTabelaRhProcessoAdvogado();
        // $this->upEstruturaTabelaRhProcessoAdvogado();

        // $this->upDicionarioTabelaRhProcessoDependente();
        // $this->upEstruturaTabelaRhProcessoDependente();

        // $this->upDicionarioTabelaRhProcessoPensao();
        // $this->upEstruturaTabelaRhProcessoPensao();

        // $this->upDicionarioTabelaRhProcessoRetencao();
        // $this->upEstruturaTabelaRhProcessoRetencao();

        // $this->upDicionarioTabelaRhProcessoValorRetencao();
        // $this->upEstruturaTabelaRhProcessoValorRetencao();

        // $this->upDicionarioTabelaRhProcessoDeducaoSuspensa();
        // $this->upEstruturaTabelaRhProcessoDeducaoSuspensa();

        // $this->upDicionarioTabelaRhProcessoSuspensaoPensao();
        // $this->upEstruturaTabelaRhProcessoSuspensaoPensao();

        // $this-> upDicionarioTabelaRhProcessoIRRFComp();
        // $this-> upEstruturaTabelaRhProcessoIRRFComp();

        // $this->upTipoEsocial();
        // $this->upEventoEsocial();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    //    $this->downItemMenu();

    //    $this->downEstruturaTabelaRhpessoalProcessoJudicialEsocial();
    //    $this->downDicionarioTabelaRhpessoalProcessoJudicialEsocial();

    //    $this->downEstruturaTabelaRhProcessoRubrica();
    //    $this->downDicionarioTabelaRhProcessoRubrica();

    //    $this->downDicionarioTabelaRhProcessoAdvogado();
    //    $this->downEstruturaTabelaRhProcessoAdvogado();

    //    $this->downDicionarioTabelaRhProcessoDependente();
    //    $this->downEstruturaTabelaRhProcessoDependente();

    //    $this->downDicionarioTabelaRhProcessoPensao();
    //    $this->downEstruturaTabelaRhProcessoPensao();

    //    $this->downDicionarioTabelaRhProcessoRetencao();
    //    $this->downEstruturaTabelaRhProcessoRetencao();

    //    $this->downDicionarioTabelaRhProcessoValorRetencao();
    //    $this->downEstruturaTabelaRhProcessoValorRetencao();

    //    $this->downDicionarioTabelaRhProcessoDeducaoSuspensa();
    //    $this->downEstruturaTabelaRhProcessoDeducaoSuspensa();

    //    $this->downDicionarioTabelaRhProcessoSuspensaoPensao();
    //    $this->downEstruturaTabelaRhProcessoSuspensaoPensao();

    //    $this->downEstruturaTabelaRhProcessoTributoIrrf();
    //    $this->downDicionarioTabelaRhProcessoTributoIrrf();

    //    $this->downEstruturaTabelaRhProcessoTributoContribuicao();
    //    $this->downDicionarioTabelaRhProcessoTributoContribuicao();
        
    //    $this->downEstruturaTabelaRhprocessoTributoBase();
    //    $this->downDicionarioTabelaRhprocessoTributoBase();

    //    $this->downEstruturaTabelaRhprocessoexclusao();
    //    $this->downDicionarioTabelaThprocessoexclusao();

    //    $this-> downDicionarioTabelaRhProcessoIRRFComp();
    //    $this-> downEstruturaTabelaRhProcessoIRRFComp();

    //    $this->downEventoEsocial();
    //    $this->downTipoEsocial();
    }

    private function upDicionarioTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            update configuracoes.db_syscampo set nomecam = 'rh270_tpccp', conteudo = 'int4', descricao = 'Indica o âmbito de celebração do acordo.', valorinicial = '0', rotulo = 'Âmbito Acordo', nulo = 't', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Âmbito Acordo' where codcam = 1014813;
            delete from configuracoes.db_syscampodep where codcam = 1014813;
            delete from configuracoes.db_syscampodef where codcam = 1014813;
            delete from configuracoes.db_syscampodef where codcam = 1014816;
            delete from configuracoes.db_syscampodep where codcam = 1014816;
            delete from configuracoes.db_syscampodef where codcam = 1014817;
            delete from configuracoes.db_syscampodep where codcam = 1014817;
            delete from configuracoes.db_sysarqcamp where codcam = 1014816;
            delete from configuracoes.db_syscampo where codcam = 1014816;
            delete from configuracoes.db_sysarqcamp where codcam = 1014817;
            delete from configuracoes.db_syscampo where codcam = 1014817;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            insert into configuracoes.db_syscampo values(1014816,'rh270_compini','varchar(7)','Competência inicial a que se refere o processo ou conciliação, no formato AAAA-MM.','', 'Competência Inicial',7,'t','t','f',0,'text','Competência Inicial');
            insert into configuracoes.db_syscampo values(1014817,'rh270_compfim','varchar(7)','Competência final a que se refere o processo ou conciliação, no formato AAAA-MM.','', 'Competência Final ',7,'t','t','f',0,'text','Competência Final ');
            insert into configuracoes.db_sysarqcamp values(1011031,1014816,12,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014817,13,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            ALTER TABLE recursoshumanos.rhpessoalprocessojudicialesocial DROP COLUMN IF EXISTS rh270_compini;
            ALTER TABLE recursoshumanos.rhpessoalprocessojudicialesocial DROP COLUMN IF EXISTS rh270_compfim;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function downEstruturaTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            ALTER TABLE recursoshumanos.rhpessoalprocessojudicialesocial ADD IF NOT EXISTS rh270_compini varchar(7) NULL DEFAULT ''::character varying;
            ALTER TABLE recursoshumanos.rhpessoalprocessojudicialesocial ADD IF NOT EXISTS rh270_compfim varchar(7) NULL DEFAULT ''::character varying;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoRubrica() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011100, 'rhprocessorubrica', 'Rubrica vinculada ao processo processo judicial', 'rh287', '2023-06-16', 'Rubrica vinculada processo', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011100);
            insert into configuracoes.db_sysarqarq values (1011032,1011100);
            insert into configuracoes.db_syscampo values(1015175,'rh287_sequencial','int4','Registro único da tabela','0', 'Número Sequencial',10,'f','f','t',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1015176,'rh287_sequencialprocessoservidor','int4','Identificação única de processo (FK) da tabela rhpessoalprocessoservidor','0', 'Identificação única de servidor',10,'t','t','f',1,'text','Identificação única de servidor');
            insert into configuracoes.db_syscampo values(1015177,'rh287_rubrica','varchar(4)','Rubrica vinculada ao servidor em processo judicial','', 'Rubrica',4,'t','t','f',0,'text','Rubrica');
            insert into configuracoes.db_syscampo values(1015178,'rh287_competencia','varchar(7)','Mês/ano em que é devida a obrigação de pagar a parcela prevista no acordo/sentença.','', 'Competência',7,'t','t','f',0,'text','Competência');
            insert into configuracoes.db_syscampo values(1015179,'rh287_quantidade','float4','Quantidade rubrica','0', 'Quantidade',10,'t','f','f',4,'text','Quantidade');
            insert into configuracoes.db_syscampo values(1015180,'rh287_valor','float4','Valor da rubrica','0', 'Valor Rubrica',10,'t','f','f',4,'text','Valor Rubrica');
            insert into configuracoes.db_syscampo values(1015181,'rh287_evento','varchar(7)','Evento do eSocial vinculado','', 'Evento',7,'t','t','f',0,'text','Evento');
            delete from configuracoes.db_sysarqcamp where codarq = 1011100;
            insert into configuracoes.db_sysarqcamp values(1011100,1015175,1,0);
            insert into configuracoes.db_sysarqcamp values(1011100,1015176,2,0);
            insert into configuracoes.db_sysarqcamp values(1011100,1015177,3,0);
            insert into configuracoes.db_sysarqcamp values(1011100,1015178,4,0);
            insert into configuracoes.db_sysarqcamp values(1011100,1015179,5,0);
            insert into configuracoes.db_sysarqcamp values(1011100,1015180,6,0);
            insert into configuracoes.db_sysarqcamp values(1011100,1015181,7,0);
            delete from configuracoes.db_sysprikey where codarq = 1011100;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011100,1015175,1,1015175);
            delete from configuracoes.db_sysforkey where codarq = 1011100 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011100,1015176,1,1011032,0);
            insert into configuracoes.db_syssequencia values(1001133, 'rhprocessorubrica_rh287_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001133 where codarq = 1011100 and codcam = 1015175;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function downDicionarioTabelaRhProcessoRubrica() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysarqcamp where codarq = 1011100 and codcam = 1015175 and codsequencia = 1001133;
            delete from configuracoes.db_syssequencia where codsequencia = 1001133;
            delete from configuracoes.db_sysforkey where codarq = 1011100; 
            delete from configuracoes.db_sysarqcamp where codarq = 1011100;
            delete from configuracoes.db_syscampo where codcam in (1015175, 1015176, 1015177, 1015178, 1015179, 1015180, 1015181);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011032 and codarq = 1011100;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011100;
            delete from configuracoes.db_sysarquivo where codarq = 1011100;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
    
    private function upEstruturaTabelaRhProcessoRubrica() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessorubrica_rh287_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessorubrica(
            rh287_sequencial		          int4 NOT NULL default nextval('rhprocessorubrica_rh287_sequencial_seq'),
            rh287_sequencialprocessoservidor  int4 default 0,
            rh287_rubrica		              varchar(4) default '',
            rh287_competencia		          varchar(7) default '',
            rh287_quantidade		          float4  default 0,
            rh287_valor		                  float4  default 0,
            rh287_evento		              varchar(7) default '',
            CONSTRAINT rhprocessorubrica_sequ_pk PRIMARY KEY (rh287_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessorubrica
            ADD CONSTRAINT rhprocessorubrica_sequencialprocessoservidor_fk FOREIGN KEY (rh287_sequencialprocessoservidor)
            REFERENCES recursoshumanos.rhpessoalprocessoservidor;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function downEstruturaTabelaRhProcessoRubrica() {
        $sql  = <<<SQL
            DROP TABLE IF EXISTS recursoshumanos.rhprocessorubrica;
            DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessorubrica_rh287_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhprocessoTributoBase() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011101, 'rhprocessotributobase', 'Informações de Tributos Decorrentes de Processo Trabalhista', 'rh288', '2023-06-19', 'Tributos de Processo', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011101);
            insert into configuracoes.db_sysarqarq values(1011032,1011101);
            insert into configuracoes.db_syscampo values(1015182,'rh288_sequencial','int4','Registro único da tabela.','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1015183,'rh288_sequencialprocessoservidor','int4','Identificação única de processo (FK) da tabela rhpessoalprocessoservidor','0', 'Identificação única de servidor',10,'f','f','f',1,'text','Identificação única de servidor');
            insert into configuracoes.db_syscampo values(1015184,'rh288_peref','varchar(7)','Informar o mês/ano (formato AAAA-MM) de referência das informações.','', 'Competência',7,'t','f','f',0,'text','Competência');
            insert into configuracoes.db_syscampo values(1015185,'rh288_vrbccpmensal','float4','Valor da base de cálculo da contribuição previdenciária sobre a remuneração mensal do trabalhador.','0', 'Cálculo da contribuição previdenciária',10,'t','f','f',4,'text','Cálculo da contribuição previdenciária');
            insert into configuracoes.db_syscampo values(1015188,'rh288_vrbccp13','float4','Valor da base de cálculo da contribuição previdenciária sobre a remuneração do trabalhador referente ao 13º salário.','0', 'Contribuição previdenciária 13',10,'t','f','f',4,'text','Contribuição previdenciária 13');
            insert into configuracoes.db_syscampo values(1015189,'rh288_vrrendirrf','float4','Valor do rendimento tributável do Imposto de Renda.','0', 'Rendimento tributável',10,'t','f','f',4,'text','Rendimento tributável');
            insert into configuracoes.db_syscampo values(1015190,'rh288_vrrendirrf13','float4','Valor do rendimento tributável do Imposto de Renda referente ao 13º salário - Tributação exclusiva.','0', 'Rendimento tributável 13',10,'t','f','f',4,'text','Rendimento tributável 13');
            insert into configuracoes.db_syscampo values(1015234,'rh288_pagamento','varchar(7)','Mês/ano em que é devida a obrigação de pagar a parcela prevista no acordo/sentença.','', 'Mês/ano pagamento',7,'f','t','f',0,'text','Mês/ano pagamento');
            insert into configuracoes.db_syscampo values(1015235,'rh288_observacao','text','Observação referente ao pagamento de parcela prevista no acordo/sentença.','', 'Observação',1,'f','t','f',0,'text','Observação');
            delete from configuracoes.db_sysarqcamp where codarq = 1011101;
            insert into configuracoes.db_sysarqcamp values(1011101,1015182,1,0);
            insert into configuracoes.db_sysarqcamp values(1011101,1015183,2,0);
            insert into configuracoes.db_sysarqcamp values(1011101,1015184,3,0);
            insert into configuracoes.db_sysarqcamp values(1011101,1015185,4,0);
            insert into configuracoes.db_sysarqcamp values(1011101,1015188,5,0);
            insert into configuracoes.db_sysarqcamp values(1011101,1015189,6,0);
            insert into configuracoes.db_sysarqcamp values(1011101,1015190,7,0);
            insert into configuracoes.db_sysarqcamp values(1011101,1015234,8,0);
            insert into configuracoes.db_sysarqcamp values(1011101,1015235,9,0);
            delete from configuracoes.db_sysprikey where codarq = 1011101;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011101,1015182,1,1015182);
            delete from configuracoes.db_sysforkey where codarq = 1011101 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011101,1015183,1,1011032,0);
            insert into configuracoes.db_syssequencia values(1001134, 'rhprocessotributobase_rh288_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001134 where codarq = 1011101 and codcam = 1015182;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhprocessoTributoBase() {
        $sql  = <<<SQL
        delete from configuracoes.db_sysarqcamp where codarq = 1011101 and codcam = 1015182;
        delete from configuracoes.db_syssequencia where codsequencia = 1001134;
        delete from configuracoes.db_sysforkey where codarq = 1011101;
        delete from configuracoes.db_sysprikey where codarq = 1011101;
        delete from configuracoes.db_sysarqcamp where codarq = 1011101;
        delete from configuracoes.db_syscampo where codcam in (1015182, 1015183, 1015184, 1015185, 1015188, 1015189, 1015190, 1015234, 1015235);
        delete from configuracoes.db_sysarqarq where codarqpai = 1011032 and codarq = 1011101;
        delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011101;
        delete from configuracoes.db_sysarquivo where codarq = 1011101;


SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhprocessoTributoBase() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessotributobase_rh288_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessotributobase(
            rh288_sequencial		            int4 NOT NULL default nextval('rhprocessotributobase_rh288_sequencial_seq'),
            rh288_sequencialprocessoservidor    int4 NOT NULL default 0,
            rh288_peref		                    varchar(7)   default '',
            rh288_pagamento		                varchar(7)   default '',
            rh288_observacao                    text   default '',
            rh288_vrbccpmensal		            float4  default 0,
            rh288_vrbccp13		                float4  default 0,
            rh288_vrrendirrf		            float4  default 0,
            rh288_vrrendirrf13		            float4 default 0,
            CONSTRAINT rhprocessotributobase_sequ_pk PRIMARY KEY (rh288_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessotributobase
            ADD CONSTRAINT rhprocessotributobase_sequencialprocessoservidor_fk FOREIGN KEY (rh288_sequencialprocessoservidor)
            REFERENCES recursoshumanos.rhpessoalprocessoservidor;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function downEstruturaTabelaRhprocessoTributoBase() {
        $sql  = <<<SQL
            DROP TABLE IF EXISTS recursoshumanos.rhprocessotributobase;
            DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessotributobase_rh288_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoTributoContribuicao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011102, 'rhprocessotributocontribuicao', 'Informações das contribuições sociais devidas à Previdência Social e Outras Entidades e Fundos, por Código de Receita - CR.', 'rh298', '2023-06-19', 'Tributos previdência', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011102);
            insert into configuracoes.db_sysarqarq values (1011101,1011102);
            insert into configuracoes.db_syscampo values(1015191,'rh298_sequencial','int4','Registro único da tabela','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1015192,'rh298_sequencialtributobase','int4','Identificação única de processo (FK) da tabela rhprocessotributobase','0', 'Identificação única de base',10,'f','f','f',1,'text','Identificação única de base');
            insert into configuracoes.db_syscampo values(1015193,'rh298_tpcr','int4',' Códigos de Receita - Reclamatória Trabalhista','0', 'Reclamatória Trabalhista',10,'t','f','f',1,'text','Reclamatória Trabalhista');
            insert into configuracoes.db_syscampo values(1015194,'rh298_vrcr','float4','Valor correspondente ao Código de Receita - CR.','0', 'Valor Contribuição',10,'t','f','f',4,'text','Valor Contribuição');
            insert into configuracoes.db_syssequencia values(1001135, 'rhprocessotributocontribuicao_rh298_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001135 where codarq = 1011102 and codcam = 1015191;
            delete from configuracoes.db_sysarqcamp where codarq = 1011102;
            insert into configuracoes.db_sysarqcamp values(1011102,1015191,1,0);
            insert into configuracoes.db_sysarqcamp values(1011102,1015192,2,0);
            insert into configuracoes.db_sysarqcamp values(1011102,1015193,3,0);
            insert into configuracoes.db_sysarqcamp values(1011102,1015194,4,0);
            delete from configuracoes.db_sysprikey where codarq = 1011102;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011102,1015191,1,1015191);
            delete from configuracoes.db_sysforkey where codarq = 1011102 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011102,1015192,1,1011101,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function downDicionarioTabelaRhProcessoTributoContribuicao() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011102 and codcam = 1015192 and referen = 1011101;
            delete from configuracoes.db_sysarqcamp where codarq = 1011102;
            delete from configuracoes.db_syscampo where codcam in (1015191, 1015192, 1015193, 1015194);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011101 and codarq = 1011102;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011102;
            delete from configuracoes.db_sysarquivo where codarq = 1011102;
            delete from configuracoes.db_syssequencia where codsequencia = 1001135;
SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    private function upEstruturaTabelaRhProcessoTributoContribuicao() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessotributocontribuicao_rh298_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessotributocontribuicao(
            rh298_sequencial		int4 NOT NULL default nextval('rhprocessotributocontribuicao_rh298_sequencial_seq'),
            rh298_sequencialtributobase		int4 NOT NULL default 0,
            rh298_tpcr		int4  default 0,
            rh298_vrcr		float4 default 0,
            CONSTRAINT rhprocessotributocontribuicao_sequ_pk PRIMARY KEY (rh298_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessotributocontribuicao
            ADD CONSTRAINT rhprocessotributocontribuicao_sequencialtributobase_fk FOREIGN KEY (rh298_sequencialtributobase)
            REFERENCES recursoshumanos.rhprocessotributobase;
SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    private function downEstruturaTabelaRhProcessoTributoContribuicao() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhprocessotributocontribuicao;
            DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessotributocontribuicao_rh298_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);

    }
    private function upItemMenu() {
        $sql  = <<<SQL
            UPDATE configuracoes.db_itensmenu
            SET funcao='pes1_rhpessoalprocessosjudiciaisesocialtributos001.php'
            WHERE id_item=228875;
            insert into configuracoes.db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228959 ,'Exclusão de Eventos - Processo Trabalhista (S-3500)' ,'Utilizado para tornar sem efeito um evento S-2500 ou S-2501 enviado indevidamente.' ,'pes1_rhpessoalprocessosjudiciaisesocialexclusao001.php' ,'1' ,'1' ,'Utilizado para tornar sem efeito um evento S-2500 ou S-2501 enviado indevidamente.' ,'true' );
            delete from configuracoes.db_menu where id_item_filho = 228959 AND modulo = 952;
            insert into configuracoes.db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228873 ,228959 ,4 ,952 ); 
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downItemMenu() {
        $sql  = <<<SQL
            UPDATE configuracoes.db_itensmenu
            SET funcao='pes1_rhpessoalprocessosjudiciaisfolha001.php'
            WHERE id_item=228875;
            delete from configuracoes.db_itensmenu where id_item = 228959;
            delete from configuracoes.db_menu where id_item_filho = 228959 AND modulo = 952;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upTipoEsocial() {
        $sql  = <<<SQL
            insert into esocialformulariotipo values 
                (50, 'S-2501 - Informações de Tributos Decorrentes de Processo Trabalhista');
            insert into esocialformulariotipo values 
                (51, 'S-3500 - Exclusão de Eventos - Processo Trabalhista');
SQL;

        DB::connection()->getPdo()->exec($sql);
    }

    private function downTipoEsocial() {
        $sql  = <<<SQL
            DELETE FROM esocialformulariotipo where rh209_sequencial = 50;
            DELETE FROM esocialformulariotipo where rh209_sequencial = 51;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function upEventoEsocial() {
        $sql  = <<<SQL
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000017, 5);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000018, 6);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000015, 1);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000019, 7);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000014, 4);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000013, 3);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000016, 2);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000020, 8);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000023, 12);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000025, 13);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000027, 15);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000021, 9);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000022, 11);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000030, 18);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000029, 16);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000028, 17);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000031, 19);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000032, 20);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000026, 14);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000033, 21);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000036, 24);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000041, 28);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 3000044, 34);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000103, 35);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000104, 36);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000105, 37);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000106, 31);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000108, 39);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000107, 38);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000109, 40);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000111, 42);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000114, 45);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000112, 43);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000113, 44);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000115, 46);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000110, 41);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000116, 48);
            INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) VALUES((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.2', 4000117, 49);

            insert into habitacao.avaliacao values (4000118, 5, 'S-2501 - Informações de Tributos Decorrentes de Processo Trabalhista', 'S-2501 - Informações de Tributos Decorrentes de Processo Trabalhista', true, 's2501_tributo_trabalhista', null, false);
            SELECT setval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq', (select max(rh211_sequencial) + 1 from recursoshumanos.esocialversaoformulario), false);
            insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.1', 4000118, 50);

            insert into habitacao.avaliacao values (4000119, 5, 'S-3500 - Exclusão de Eventos - Processo Trabalhista', 'S-3500 - Exclusão de Eventos - Processo Trabalhista', true, 's3500_exclusao_trabalhista', null, false);
            SELECT setval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq', (select max(rh211_sequencial) + 1 from recursoshumanos.esocialversaoformulario), false);
            insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.2', 4000119, 51);

            INSERT INTO recursoshumanos.esocialversao (rh210_sequencial, rh210_versao) VALUES (5, 'S1.2');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downEventoEsocial()
    {
        $sql = <<<SQL
            delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 50;
            delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 51;
            delete from recursoshumanos.esocialversaoformulario where rh211_versao = 'S1.2' and rh211_avaliacao != 3000015;
            delete from habitacao.avaliacao where db101_sequencial in (4000118, 4000119);
            delete from recursoshumanos.esocialversao where rh210_sequencial = 5;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function upDicionarioTabelaThprocessoexclusao()
    {
        $sql = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011128, 'rhprocessoexclusao', 'S-3500 - Exclusão de Eventos - Processo Trabalhista', 'rh300', '2023-08-08', 'Exclusão processos judiciais', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011128);
            insert into configuracoes.db_sysarqarq values (1011032,1011128);
            insert into configuracoes.db_syscampo values(1015306,'rh300_sequencial','int4','Código único da tabela','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1015307,'rh300_sequencialprocessoservidor','int4','Código que relaciona a tabela rhprocessoservidor (FK)','0', 'Código referente servidor',10,'f','f','f',1,'text','Código referente servidor');
            insert into configuracoes.db_syscampo values(1015308,'rh300_tpevento','varchar(6)','Tipo de evento (S-2500 ou S-2501).','', 'Tipo de evento',6,'t','t','f',0,'text','Tipo de evento');
            insert into configuracoes.db_syscampo values(1015309,'rh300_nrrecevt','varchar(23)','Número do recibo do evento que será excluído.','', 'Número do recibo',23,'t','t','f',0,'text','Número do recibo');
            insert into configuracoes.db_syscampo values(1015310,'rh300_nrproctrab','varchar(20)','Número do processo trabalhista, da ata ou número de identificação da conciliação.','', 'Número do Processo Trabalhista',20,'t','t','f',0,'text','Número do Processo Trabalhista');
            insert into configuracoes.db_syscampo values(1015311,'rh300_cpftrab','varchar(11)','Número do CPF do trabalhador.','', 'CPF',11,'t','t','f',0,'text','CPF');
            insert into configuracoes.db_syscampo values(1015312,'rh300_perapurpgto','varchar(7)','Mês/ano em que é devida a obrigação de pagar a parcela prevista no acordo/sentença.','', 'Pagamento',7,'t','t','f',0,'text','Pagamento');
            insert into configuracoes.db_syscampo values(1015333,'rh300_dataexclusao','date','Data de exclusão do evento','null', 'Data Exclusão',10,'f','f','t',0,'text','Data Exclusão');            delete from configuracoes.db_sysarqcamp where codarq = 1011128;
            insert into configuracoes.db_syscampo values(1015337,'rh300_referencia','varchar(255)','Referência do evento no eSocial.','', 'Referência',255,'t','t','f',0,'text','Referência');            insert into configuracoes.db_sysarqcamp values(1011128,1015306,1,0);
            insert into configuracoes.db_sysarqcamp values(1011128,1015307,2,0);
            insert into configuracoes.db_sysarqcamp values(1011128,1015308,3,0);
            insert into configuracoes.db_sysarqcamp values(1011128,1015309,4,0);
            insert into configuracoes.db_sysarqcamp values(1011128,1015310,5,0);
            insert into configuracoes.db_sysarqcamp values(1011128,1015311,6,0);
            insert into configuracoes.db_sysarqcamp values(1011128,1015312,7,0);
            insert into configuracoes.db_sysarqcamp values(1011128,1015333,8,0);
            insert into configuracoes.db_sysarqcamp values(1011128,1015337,9,0);
            delete from configuracoes.db_sysprikey where codarq = 1011128;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011128,1015306,1,1015306);
            delete from configuracoes.db_sysforkey where codarq = 1011128 and referen = 0;
            delete from configuracoes.db_sysforkey where codarq = 1011128 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011128,1015307,1,1011032,0);
            insert into configuracoes.db_syssequencia values(1001154, 'rhprocessoexclusao_rh300_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001154 where codarq = 1011128 and codcam = 1015306;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downDicionarioTabelaThprocessoexclusao()
    {
        $sql = <<<SQL
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011128 and codcam = 1015306;
            delete from configuracoes.db_syssequencia where codsequencia = 1001154;
            delete from configuracoes.db_sysprikey where codarq = 1011128;
            delete from configuracoes.db_sysforkey where codarq = 1011128;
            delete from configuracoes.db_sysarqcamp where codarq = 1011128;
            delete from configuracoes.db_syscampo where codcam in (1015306, 1015307, 1015308, 1015309, 1015310, 1015311, 1015312, 1015333, 1015337);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011032 and codarq = 1011128;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011128;
            delete from configuracoes.db_sysarquivo where codarq = 1011128;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhprocessoexclusao() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessoexclusao_rh300_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessoexclusao(
            rh300_sequencial		int4 NOT NULL default nextval('rhprocessoexclusao_rh300_sequencial_seq'),
            rh300_sequencialprocessoservidor		int4 NOT NULL default 0,
            rh300_tpevento		varchar(6)   default '',
            rh300_nrrecevt		varchar(23)   default '',
            rh300_nrproctrab		varchar(20)   default '',
            rh300_cpftrab		varchar(11)   default '',
            rh300_perapurpgto		varchar(7)  default '',
            rh300_dataexclusao		date default now()::date,
            rh300_referencia		varchar(255)  default '',
            CONSTRAINT rhprocessoexclusao_sequ_pk PRIMARY KEY (rh300_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessoexclusao
            ADD CONSTRAINT rhprocessoexclusao_sequencialprocessoservidor_fk FOREIGN KEY (rh300_sequencialprocessoservidor)
            REFERENCES recursoshumanos.rhpessoalprocessoservidor;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhprocessoexclusao() {
        $sql  = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhprocessoexclusao;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessoexclusao_rh300_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoAdvogado() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011137, 'rhprocessoadvogado', 'Identificação dos advogados', 'rh303', '2023-09-14', 'Identificação dos advogados', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011137);
            insert into configuracoes.db_sysarqarq values (1011103,1011137);
            insert into configuracoes.db_syscampo values(1015412,'rh303_sequencial','int8','Sequencial único da tabela','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into configuracoes.db_syscampo values(1015413,'rh303_sequencialtributoirrf','int8','Sequencial vinculo tabela RHPROCESSOTRIBUTOIRRF (FK)','0', 'Sequencial vinculo tributo',10,'f','f','f',1,'text','Sequencial vinculo tributo');
            insert into configuracoes.db_syscampo values(1015414,'rh303_tpInsc','int4','Preencher com o código correspondente ao tipo de inscrição do advogado.','0', 'Tipo de inscrição',10,'t','f','f',1,'text','Tipo de inscrição');
            insert into configuracoes.db_syscampo values(1015415,'rh303_nrInsc','varchar(14)','Informar o número de inscrição do advogado.','', 'Número de inscrição',14,'t','t','f',0,'text','Número de inscrição');
            insert into configuracoes.db_syscampo values(1015416,'rh303_vlradv','float4','Valor da despesa com o advogado,.','0', 'Valor despesa',15,'t','f','f',4,'text','Valor despesa');
            delete from configuracoes.db_sysarqcamp where codarq = 1011137;
            insert into configuracoes.db_sysarqcamp values(1011137,1015412,1,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015413,2,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015414,3,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015415,4,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015416,5,0);
            delete from configuracoes.db_sysprikey where codarq = 1011137;
            delete from configuracoes.db_sysarqcamp where codarq = 1011137;
            insert into configuracoes.db_sysarqcamp values(1011137,1015412,1,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015413,2,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015414,3,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015415,4,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015416,5,0);
            delete from configuracoes.db_sysprikey where codarq = 1011137;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011137,1015412,1,1015412);
            delete from configuracoes.db_sysarqcamp where codarq = 1011137;
            insert into configuracoes.db_sysarqcamp values(1011137,1015412,1,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015413,2,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015414,3,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015415,4,0);
            insert into configuracoes.db_sysarqcamp values(1011137,1015416,5,0);
            delete from configuracoes.db_sysforkey where codarq = 1011137 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011137,1015413,1,1011103,0);
            insert into configuracoes.db_syssequencia values(1001163, 'rhprocessoadvogado_rh303_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001163 where codarq = 1011137 and codcam = 1015412;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function downDicionarioTabelaRhProcessoAdvogado() {
        $sql  = <<<SQL
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011137 and codcam = 1015412;
            delete from configuracoes.db_syssequencia where codsequencia = 1001163;
            delete from configuracoes.db_sysprikey where codarq = 1011137;
            delete from configuracoes.db_sysforkey where codarq = 1011137;
            delete from configuracoes.db_sysarqcamp where codarq = 1011137;
            delete from configuracoes.db_syscampo where codcam in (1015412, 1015413, 1015414, 1015415, 1015416);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011103 and codarq = 1011137;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011137;
            delete from configuracoes.db_sysarquivo where codarq = 1011137;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoAdvogado() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessoadvogado_rh303_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessoadvogado(
            rh303_sequencial		    int8 NOT NULL default nextval('rhprocessoadvogado_rh303_sequencial_seq'),
            rh303_sequencialtributoirrf	int8 NOT NULL default 0,
            rh303_tpInsc		        int4  default 0,
            rh303_nrInsc		        varchar(14)   default '',
            rh303_vlradv		        float4 default 0,
            CONSTRAINT rhprocessoadvogado_sequ_pk PRIMARY KEY (rh303_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessoadvogado
            ADD CONSTRAINT rhprocessoadvogado_sequencialtributoirrf_fk FOREIGN KEY (rh303_sequencialtributoirrf)
            REFERENCES recursoshumanos.rhprocessotributoirrf;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoAdvogado() {
        $sql  = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhprocessoadvogado;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessoadvogado_rh303_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoDependente() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011138, 'rhprocessodependente', 'Dedução do rendimento tributável relativa a dependentes.', 'rh304', '2023-09-14', 'Tributável dependentes', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011138);
            insert into configuracoes.db_sysarqarq values (1011103,1011138);
            insert into configuracoes.db_syscampo values(1015417,'rh304_sequencial','int4','Sequencial único da tabela','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into configuracoes.db_syscampo values(1015418,'rh304_sequencialtributoirrf','int4','Sequencial que vincula a tabela RHPROCESSOTRIBUTOIRRF','0', 'Sequencial vinculo tributo',10,'f','f','f',1,'text','Sequencial vinculo tributo');
            insert into configuracoes.db_syscampo values(1015419,'rh304_tprend','int4','Tipo de rendimento tributável relativo a dependentes','0', 'Tipo de rendimento',10,'t','f','f',1,'text','Tipo de rendimento');
            insert into configuracoes.db_syscampo values(1015420,'rh304_cpfdep','varchar(11)','Número de inscrição do dependente no CPF.','', 'CPF',11,'t','t','f',0,'text','CPF');
            insert into configuracoes.db_syscampo values(1015421,'rh304_vlrdeducao','float4','Valor da dedução da base de cálculo.','0', 'Valor da dedução',15,'t','f','f',4,'text','Valor da dedução');
            delete from configuracoes.db_sysarqcamp where codarq = 1011138;
            insert into configuracoes.db_sysarqcamp values(1011138,1015417,1,0);
            insert into configuracoes.db_sysarqcamp values(1011138,1015418,2,0);
            insert into configuracoes.db_sysarqcamp values(1011138,1015419,3,0);
            insert into configuracoes.db_sysarqcamp values(1011138,1015420,4,0);
            insert into configuracoes.db_sysarqcamp values(1011138,1015421,5,0);
            delete from configuracoes.db_sysprikey where codarq = 1011138;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011138,1015417,1,1015417);
            delete from configuracoes.db_sysforkey where codarq = 1011138 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011138,1015418,1,1011103,0);
            insert into configuracoes.db_syssequencia values(1001164, 'rhprocessodependente_rh304_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001164 where codarq = 1011138 and codcam = 1015417;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoDependente() {
        $sql  = <<<SQL
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011138 and codcam = 1015417;
            delete from configuracoes.db_syssequencia where codsequencia = 1001164;
            delete from configuracoes.db_sysprikey where codarq = 1011138;
            delete from configuracoes.db_sysforkey where codarq = 1011138;
            delete from configuracoes.db_sysarqcamp where codarq = 1011138;
            delete from configuracoes.db_syscampo where codcam in (1015417, 1015418, 1015419, 1015420, 1015421);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011103 and codarq = 1011138;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011138;
            delete from configuracoes.db_sysarquivo where codarq = 1011138;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoDependente() {
        $sql  = <<<SQL
        -- Criando  sequences
        CREATE SEQUENCE recursoshumanos.rhprocessodependente_rh304_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
        -- TABELAS E ESTRUTURA
        -- Módulo: recursoshumanos
        CREATE TABLE recursoshumanos.rhprocessodependente(
        rh304_sequencial		    int4 NOT NULL default nextval('rhprocessodependente_rh304_sequencial_seq'),
        rh304_sequencialtributoirrf int4 NOT NULL default 0,
        rh304_tprend		        int4  default 0,
        rh304_cpfdep		        varchar(11)   default '',
        rh304_vlrdeducao		    float4 default 0,
        CONSTRAINT rhprocessodependente_sequ_pk PRIMARY KEY (rh304_sequencial));
        -- CHAVE ESTRANGEIRA
        ALTER TABLE recursoshumanos.rhprocessodependente
        ADD CONSTRAINT rhprocessodependente_sequencialtributoirrf_fk FOREIGN KEY (rh304_sequencialtributoirrf)
        REFERENCES recursoshumanos.rhprocessotributoirrf;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoDependente() {
        $sql  = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhprocessodependente;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessodependente_rh304_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoPensao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011140, 'rhprocessopensao', 'Informação dos beneficiários da pensão alimentícia.', 'rh305', '2023-09-14', 'Pensão alimentícia', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011140);
            insert into configuracoes.db_sysarqarq values (1011103,1011140);
            insert into configuracoes.db_syscampo values(1015422,'rh305_sequencial','int4','Sequencial único da tabela.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into configuracoes.db_syscampo values(1015423,'rh305_sequencialtributoirrf','int4','Sequencial que vincula a tabela RHPROCESSOTRIBUTOIRRF','0', 'Sequencial vinculo tributo',10,'f','f','f',4,'text','Sequencial vinculo tributo');
            insert into configuracoes.db_syscampo values(1015424,'rh305_tprend','int4','Tipo de rendimento dos beneficiários da pensão alimentícia.','0', 'Tipo de rendimento',10,'t','f','f',1,'text','Tipo de rendimento');
            insert into configuracoes.db_syscampo values(1015425,'rh305_cpfdep','varchar(11)','Número do CPF do dependente/beneficiário da pensão alimentícia.','', 'CPF',11,'t','t','f',0,'text','CPF');
            insert into configuracoes.db_syscampo values(1015426,'rh305_vlrpensao','float4','Valor relativo à dedução do rendimento tributável correspondente a pagamento de pensão alimentícia.','0', 'Valor pensão',15,'t','f','f',4,'text','Valor pensão');
            insert into configuracoes.db_sysarqcamp values(1011140,1015422,1,0);
            insert into configuracoes.db_sysarqcamp values(1011140,1015423,2,0);
            insert into configuracoes.db_sysarqcamp values(1011140,1015424,3,0);
            insert into configuracoes.db_sysarqcamp values(1011140,1015425,4,0);
            insert into configuracoes.db_sysarqcamp values(1011140,1015426,5,0);
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011140,1015422,1,1015422);
            insert into configuracoes.db_sysforkey values(1011140,1015423,1,1011103,0);
            insert into configuracoes.db_syssequencia values(1001165, 'rhprocessopensao_rh305_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001165 where codarq = 1011140 and codcam = 1015422;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoPensao() {
        $sql  = <<<SQL
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011140 and codcam = 1015422;
            delete from configuracoes.db_syssequencia where codsequencia = 1001165;
            delete from configuracoes.db_sysprikey where codarq = 1011140;
            delete from configuracoes.db_sysforkey where codarq = 1011140;
            delete from configuracoes.db_sysarqcamp where codarq = 1011140;
            delete from configuracoes.db_syscampo where codcam in (1015422, 1015423, 1015424, 1015425, 1015426);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011103 and codarq = 1011140;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011140;
            delete from configuracoes.db_sysarquivo where codarq = 1011140;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoPensao() {
        $sql  = <<<SQL
        -- Criando  sequences
        CREATE SEQUENCE recursoshumanos.rhprocessopensao_rh305_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
        -- TABELAS E ESTRUTURA
        -- Módulo: recursoshumanos
        CREATE TABLE recursoshumanos.rhprocessopensao(
        rh305_sequencial		    int4 NOT NULL default nextval('rhprocessopensao_rh305_sequencial_seq'),
        rh305_sequencialtributoirrf	int4 NOT NULL default 0,
        rh305_tprend		        int4  default 0,
        rh305_cpfdep		        varchar(11) default '',
        rh305_vlrpensao		        float4 default 0,
        CONSTRAINT rhprocessopensao_sequ_pk PRIMARY KEY (rh305_sequencial));
        -- CHAVE ESTRANGEIRA
        ALTER TABLE recursoshumanos.rhprocessopensao
        ADD CONSTRAINT rhprocessopensao_sequencialtributoirrf_fk FOREIGN KEY (rh305_sequencialtributoirrf)
        REFERENCES recursoshumanos.rhprocessotributoirrf;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoPensao() {
        $sql  = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhprocessopensao;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessopensao_rh305_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoRetencao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011141, 'rhprocessoretencao', 'Informações de processos relacionados a não retenção de tributos ou a depósitos judiciais.', 'rh306', '2023-09-15', 'Retenção de tributos', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011141);
            insert into configuracoes.db_sysarqarq values (1011103,1011141);
            insert into configuracoes.db_syscampo values(1015427,'rh306_sequencial','int4','Sequencial único da tabela','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into configuracoes.db_syscampo values(1015428,'rh306_sequencialtributoirrf','int4','Sequencial que vincula a tabela RHPROCESSOTRIBUTOIRRF','0', 'Sequencial vinculo tributo',10,'f','f','f',1,'text','Sequencial vinculo tributo');
            insert into configuracoes.db_syscampo values(1015429,'rh306_tpprocret','int4','Código correspondente ao tipo de processo.','0', 'Tipo de processo',1,'t','f','f',1,'text','Tipo de processo');
            insert into configuracoes.db_syscampo values(1015430,'rh306_nrprocret','varchar(21)','Número do processo administrativo/judicial.','', 'Número do processo',21,'t','t','f',0,'text','Número do processo');
            insert into configuracoes.db_syscampo values(1015431,'rh306_codsusp','varchar(14)','Código do indicativo da suspensão, atribuído pelo empregador em S-1070.','', 'Indicativo da suspensão',14,'t','t','f',0,'text','Indicativo da suspensão');
            delete from configuracoes.db_sysarqcamp where codarq = 1011141;
            insert into configuracoes.db_sysarqcamp values(1011141,1015427,1,0);
            insert into configuracoes.db_sysarqcamp values(1011141,1015428,2,0);
            insert into configuracoes.db_sysarqcamp values(1011141,1015429,3,0);
            insert into configuracoes.db_sysarqcamp values(1011141,1015430,4,0);
            insert into configuracoes.db_sysarqcamp values(1011141,1015431,5,0);
            delete from configuracoes.db_sysprikey where codarq = 1011141;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011141,1015427,1,1015427);
            delete from configuracoes.db_sysforkey where codarq = 1011141 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011141,1015428,1,1011103,0);
            insert into configuracoes.db_syssequencia values(1001166, 'rhprocessoretencao_rh306_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001166 where codarq = 1011141 and codcam = 1015427;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoRetencao() {
        $sql  = <<<SQL
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011140 and codcam = 1015427;
            delete from configuracoes.db_syssequencia where codsequencia = 1001166;
            delete from configuracoes.db_sysprikey where codarq = 1011141;
            delete from configuracoes.db_sysforkey where codarq = 1011141;
            delete from configuracoes.db_sysarqcamp where codarq = 1011141;
            delete from configuracoes.db_syscampo where codcam in (1015427, 1015428, 1015429, 1015430, 1015431);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011103 and codarq = 1011141;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011141;
            delete from configuracoes.db_sysarquivo where codarq = 1011141;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoRetencao() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessoretencao_rh306_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessoretencao(
            rh306_sequencial		    int4 NOT NULL default nextval('rhprocessoretencao_rh306_sequencial_seq'),
            rh306_sequencialtributoirrf int4 NOT NULL default 0,
            rh306_tpprocret		        int4  default 0,
            rh306_nrprocret		        varchar(21)   default '',
            rh306_codsusp		        varchar(14)  default '',
            CONSTRAINT rhprocessoretencao_sequ_pk PRIMARY KEY (rh306_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessoretencao
            ADD CONSTRAINT rhprocessoretencao_sequencialtributoirrf_fk FOREIGN KEY (rh306_sequencialtributoirrf)
            REFERENCES recursoshumanos.rhprocessotributoirrf;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoRetencao() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhprocessoretencao CASCADE;
            --Criando drop sequences
            DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessoretencao_rh306_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoValorRetencao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011142, 'rhprocessovalorretencao', 'Informações de valores relacionados a não retenção de tributos ou a depósitos judiciais.', 'rh307', '2023-09-15', 'Valores retenção de tributos', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011142);
            insert into configuracoes.db_sysarqarq values (1011141,1011142);
            insert into configuracoes.db_syscampo values(1015432,'rh307_sequencial','int4','Sequencial único da tabela','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into configuracoes.db_syscampo values(1015433,'rh307_sequencialretencao','int4','Sequencial que vincula a tabela RHPROCESSORETENCAO','0', 'Sequencial vinculo retencao',10,'f','f','f',1,'text','Sequencial vinculo retencao');
            insert into configuracoes.db_syscampo values(1015434,'rh307_indapuracao','int4','Indicativo de período de apuração. Valores válidos: 1 - Mensal 2 - Anual (13° salário)','0', 'Período de apuração',1,'t','f','f',1,'text','Período de apuração');
            insert into configuracoes.db_syscampo values(1015435,'rh307_vlrnretido','float4','Valor da retenção que deixou de ser efetuada em função de processo administrativo ou judicial','0', 'Valor da retenção',15,'t','f','f',4,'text','Valor da retenção');
            insert into configuracoes.db_syscampo values(1015436,'rh307_vlrdepjud','float4','Valor do depósito judicial em função de processo administrativo ou judicial.','0', 'Valor do depósito judicial',15,'t','f','f',4,'text','Valor do depósito judicial');
            insert into configuracoes.db_syscampo values(1015437,'rh307_vlrcmpanocal','float4','Valor da compensação relativa ao ano calendário em função de processo judicial.','0', 'Valor da compensação',15,'t','f','f',4,'text','Valor da compensação');
            insert into configuracoes.db_syscampo values(1015438,'rh307_vlrcmpanoant','float4','Valor da compensação relativa a anos anteriores em função de processo judicial.','0', 'Valor da compensação',15,'t','f','f',4,'text','Valor da compensação');
            insert into configuracoes.db_syscampo values(1015439,'rh307_vlrrendsusp','float4','Valor do rendimento com exigibilidade suspensa.','0', 'Exigibilidade suspensa',15,'t','f','f',4,'text','Exigibilidade suspensa');
            delete from configuracoes.db_sysarqcamp where codarq = 1011142;
            insert into configuracoes.db_sysarqcamp values(1011142,1015432,1,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015433,2,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015434,3,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015435,4,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015436,5,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015437,6,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015438,7,0);
            insert into configuracoes.db_sysarqcamp values(1011142,1015439,8,0);
            delete from configuracoes.db_sysprikey where codarq = 1011142;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011142,1015432,1,1015432);
            delete from configuracoes.db_sysforkey where codarq = 1011142 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011142,1015433,1,1011141,0);
            insert into configuracoes.db_syssequencia values(1001167, 'rhprocessovalorretencao_rh307_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001167 where codarq = 1011142 and codcam = 1015432;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoValorRetencao() {
        $sql  = <<<SQL
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011140 and codcam = 1015427;
            delete from configuracoes.db_syssequencia where codsequencia = 1001167;
            delete from configuracoes.db_sysprikey where codarq = 1011142;
            delete from configuracoes.db_sysforkey where codarq = 1011142;
            delete from configuracoes.db_sysarqcamp where codarq = 1011142;
            delete from configuracoes.db_syscampo where codcam in (1015432, 1015433, 1015434, 1015435, 1015436, 1015437, 1015438, 1015439);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011141 and codarq = 1011142;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011142;
            delete from configuracoes.db_sysarquivo where codarq = 1011142;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoValorRetencao() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessovalorretencao_rh307_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessovalorretencao(
            rh307_sequencial		    int4 NOT NULL default nextval('rhprocessovalorretencao_rh307_sequencial_seq'),
            rh307_sequencialretencao    int4 NOT NULL default 0,
            rh307_indapuracao		    int4  default 0,
            rh307_vlrnretido		    float4  default 0,
            rh307_vlrdepjud		        float4  default 0,
            rh307_vlrcmpanocal		    float4  default 0,
            rh307_vlrcmpanoant		    float4  default 0,
            rh307_vlrrendsusp		    float4 default 0,
            CONSTRAINT rhprocessovalorretencao_sequ_pk PRIMARY KEY (rh307_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessovalorretencao
            ADD CONSTRAINT rhprocessovalorretencao_sequencialretencao_fk FOREIGN KEY (rh307_sequencialretencao)
            REFERENCES recursoshumanos.rhprocessoretencao;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoValorRetencao() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhprocessovalorretencao CASCADE;
            --Criando drop sequences
            DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessovalorretencao_rh307_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoDeducaoSuspensa() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011143, 'rhprocessoreducaosuspensa', 'Detalhamento das deduções com exigibilidade suspensa.', 'rh308', '2023-09-15', 'Exigibilidade suspensa.', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011143);
            insert into configuracoes.db_sysarqarq values (1011142,1011143);
            insert into configuracoes.db_syscampo values(1015440,'rh308_sequencial','int4','Sequencial único da tabela','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into configuracoes.db_syscampo values(1015441,'rh308_sequencialvalorretencao','int4','Sequencial que vincula a tabela RHPROCESSOVALORRETENCAO','0', 'Sequencial vinculo valor retenção',10,'f','f','f',1,'text','Sequencial vinculo valor retenção');
            insert into configuracoes.db_syscampo values(1015442,'rh308_indtpdeducao','int4','Indicativo do tipo de dedução.','0', 'Tipo de dedução',10,'t','f','f',1,'text','Tipo de dedução');
            insert into configuracoes.db_syscampo values(1015443,'rh308_vlrdedsusp','float4','Valor da dedução da base de cálculo do imposto de renda com exigibilidade suspensa.','0', 'Valor da dedução',15,'f','f','f',4,'text','Valor da dedução');
            delete from configuracoes.db_sysarqcamp where codarq = 1011143;
            insert into configuracoes.db_sysarqcamp values(1011143,1015440,1,0);
            insert into configuracoes.db_sysarqcamp values(1011143,1015441,2,0);
            insert into configuracoes.db_sysarqcamp values(1011143,1015442,3,0);
            insert into configuracoes.db_sysarqcamp values(1011143,1015443,4,0);
            delete from configuracoes.db_sysprikey where codarq = 1011143;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011143,1015440,1,1015440);
            delete from configuracoes.db_sysforkey where codarq = 1011143 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011143,1015441,1,1011142,0);
            insert into configuracoes.db_syssequencia values(1001168, 'rhprocessoreducaosuspensa_rh308_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001168 where codarq = 1011143 and codcam = 1015440;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoDeducaoSuspensa() {
        $sql  = <<<SQL
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011143 and codcam = 1015440;
            delete from configuracoes.db_syssequencia where codsequencia = 1001168;
            delete from configuracoes.db_sysprikey where codarq = 1011143;
            delete from configuracoes.db_sysforkey where codarq = 1011143;
            delete from configuracoes.db_sysarqcamp where codarq = 1011143;
            delete from configuracoes.db_syscampo where codcam in (1015440, 1015441, 1015442, 1015443);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011142 and codarq = 1011143;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011143;
            delete from configuracoes.db_sysarquivo where codarq = 1011143;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoDeducaoSuspensa() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessoreducaosuspensa_rh308_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessoreducaosuspensa(
            rh308_sequencial		        int4 NOT NULL default nextval('rhprocessoreducaosuspensa_rh308_sequencial_seq'),
            rh308_sequencialvalorretencao   int4 NOT NULL default 0,
            rh308_indtpdeducao		        int4  default 0,
            rh308_vlrdedsusp		        float4 default 0,
            CONSTRAINT rhprocessoreducaosuspensa_sequ_pk PRIMARY KEY (rh308_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessoreducaosuspensa
            ADD CONSTRAINT rhprocessoreducaosuspensa_sequencialvalorretencao_fk FOREIGN KEY (rh308_sequencialvalorretencao)
            REFERENCES recursoshumanos.rhprocessovalorretencao;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoDeducaoSuspensa() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhprocessoreducaosuspensa CASCADE;
            --Criando drop sequences
            DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessoreducaosuspensa_rh308_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoSuspensaoPensao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011144, 'rhprocessosuspensapensao', 'Informação das deduções suspensas por dependentes e beneficiários da pensão alimentícia', 'rh309', '2023-09-15', 'Deduções suspensas pensão', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011144);
            insert into configuracoes.db_sysarqarq values (1011143,1011144);
            insert into configuracoes.db_syscampo values(1015444,'rh309_sequencial','int4','Sequencial único da tabela','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into configuracoes.db_syscampo values(1015445,'rh309_sequencialreducaosuspensa','int4','Sequencial que vincula a tabela RHPROCESSOREDUCAOSUSPENSA','0', 'Sequencial vinculo',10,'f','f','f',1,'text','Sequencial vinculo');
            insert into configuracoes.db_syscampo values(1015446,'rh309_cpfdep','varchar(11)','Número de inscrição no CPF.','', 'CPF',11,'t','t','f',0,'text','CPF');
            insert into configuracoes.db_syscampo values(1015447,'rh309_vlrdepensusp','float4','Valor da dedução relativa a dependentes ou a pensão alimentícia com exigibilidade suspensa.','0', 'Valor da dedução ',15,'t','f','f',4,'text','Valor da dedução ');
            delete from configuracoes.db_sysarqcamp where codarq = 1011144;
            insert into configuracoes.db_sysarqcamp values(1011144,1015444,1,0);
            insert into configuracoes.db_sysarqcamp values(1011144,1015445,2,0);
            insert into configuracoes.db_sysarqcamp values(1011144,1015446,3,0);
            insert into configuracoes.db_sysarqcamp values(1011144,1015447,4,0);
            delete from configuracoes.db_sysprikey where codarq = 1011144;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011144,1015444,1,1015444);
            delete from configuracoes.db_sysforkey where codarq = 1011144 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011144,1015445,1,1011143,0);
            insert into configuracoes.db_syssequencia values(1001169, 'rhprocessosuspensapensao_rh309_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001169 where codarq = 1011144 and codcam = 1015444;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoSuspensaoPensao() {
        $sql  = <<<SQL
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011144 and codcam = 1015440;
            delete from configuracoes.db_syssequencia where codsequencia = 1001169;
            delete from configuracoes.db_sysprikey where codarq = 1011144;
            delete from configuracoes.db_sysforkey where codarq = 1011144;
            delete from configuracoes.db_sysarqcamp where codarq = 1011144;
            delete from configuracoes.db_syscampo where codcam in (1015444, 1015445, 1015446, 1015447);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011143 and codarq = 1011144;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011144;
            delete from configuracoes.db_sysarquivo where codarq = 1011144;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoSuspensaoPensao() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessosuspensapensao_rh309_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessosuspensapensao(
            rh309_sequencial		        int4 NOT NULL default nextval('rhprocessosuspensapensao_rh309_sequencial_seq'),
            rh309_sequencialreducaosuspensa int4 NOT NULL default 0,
            rh309_cpfdep		            varchar(11)   default '',
            rh309_vlrdepensusp		        float4 default 0,
            CONSTRAINT rhprocessosuspensapensao_sequ_pk PRIMARY KEY (rh309_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessosuspensapensao
            ADD CONSTRAINT rhprocessosuspensapensao_sequencialreducaosuspensa_fk FOREIGN KEY (rh309_sequencialreducaosuspensa)
            REFERENCES recursoshumanos.rhprocessoreducaosuspensa;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoSuspensaoPensao() {
        $sql  = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhprocessosuspensapensao CASCADE;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessosuspensapensao_rh309_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoTributoIrrf() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011103, 'rhprocessotributoirrf', 'Informações de Imposto de Renda Retido na Fonte, por Código de Receita - CR.', 'rh299', '2023-06-19', 'Tributos IRRF', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011103);
            insert into configuracoes.db_sysarqarq values (1011032,1011103);
            insert into configuracoes.db_syscampo values(1015195,'rh299_sequencial','int4','Registro único da tabela','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1015196,'rh299_sequencialprocessoservidor','int4','Identificação única de processo (FK) da tabela rhpessoalprocessoservidor','0', 'Identificação única de servidor',10,'f','f','f',1,'text','Identificação única de servidor');
            insert into configuracoes.db_syscampo values(1015197,'rh299_tpcr','int4','Código de Receita - CR relativo a Imposto de Renda Retido na Fonte.','0', 'Relativo IRRF',10,'f','f','f',1,'text','Relativo IRRF');
            insert into configuracoes.db_syscampo values(1015198,'rh299_vcr','float4','Valor correspondente ao Código de Receita - CR','0', 'Valor IRRF',10,'t','f','f',4,'text','Valor IRRF');
            insert into configuracoes.db_syscampo values(1015294,'rh299_pagamento','varchar(7)','Mês/ano em que é devida a obrigação de pagar a parcela prevista no acordo/sentença.','', 'Data Contemplado',7,'t','t','f',0,'text','Data Contemplado');            delete from configuracoes.db_sysarqcamp where codarq = 1011103;
            insert into configuracoes.db_syscampo values(1015400,'rh299_vrrendtrib','float4','Valor do rendimento tributável mensal do Imposto de Renda.','0', 'Rendimento tributável',15,'t','f','f',4,'text','Rendimento tributável');
            insert into configuracoes.db_syscampo values(1015401,'rh299_vrrendtrib13','float4','Valor do rendimento tributável do Imposto de Renda referente ao 13º salário - Tributação exclusiva.','0', 'Rendimento tributável 13',15,'t','f','f',4,'text','Rendimento tributável 13');
            insert into configuracoes.db_syscampo values(1015402,'rh299_vrrendmolegrave','float4','Valor do rendimento isento por ser portador de moléstia grave atestada por laudo médico.','0', 'Valor moléstia grave',15,'t','f','f',4,'text','Valor moléstia grave');
            insert into configuracoes.db_syscampo values(1015403,'rh299_vrrendIsen65','float4','Valor de parcela isenta de aposentadoria para beneficiário de 65 anos ou mais.','0', 'Aposentadoria 65 anos',15,'t','f','f',4,'text','Aposentadoria 65 anos');
            insert into configuracoes.db_syscampo values(1015404,'rh299_vrjurosmora','float4','Juros de mora recebidos, devidos pelo atraso no pagamento de remuneração por exercício de emprego, cargo ou função.','0', 'Juros de mora',15,'t','f','f',4,'text','Juros de mora');
            insert into configuracoes.db_syscampo values(1015405,'rh299_vrrendIsenntrib','float4','Valor de outros rendimentos isentos ou não tributáveis.','0', 'Rendimentos isentos',15,'t','f','f',4,'text','Rendimentos isentos');
            insert into configuracoes.db_syscampo values(1015406,'rh299_descIsenntrib','varchar(60)','Descrição do rendimento isento ou não tributável informado','', 'Rendimento isento',60,'t','t','f',0,'text','Rendimento isento');
            insert into configuracoes.db_syscampo values(1015407,'rh299_vrprevoficial','float4','Valor referente à previdência oficial.','0', 'Previdência oficial',15,'t','f','f',4,'text','Previdência oficial');
            insert into configuracoes.db_syscampo values(1015408,'rh299_descrra','varchar(50)','Descrição dos Rendimentos Recebidos Acumuladamente - RRA.','', 'Rendimentos Recebidos Acumuladamente',50,'t','t','f',0,'text','Rendimentos Recebidos Acumuladamente');
            insert into configuracoes.db_syscampo values(1015409,'rh299_qtdmesesrra','int4','Número de meses relativo aos Rendimentos Recebidos Acumuladamente - RRA.','0', 'Número de meses',10,'t','f','f',1,'text','Número de meses');
            insert into configuracoes.db_syscampo values(1015410,'rh299_vlrdespcustas','float4','Preencher com o valor das despesas com custas judiciais.','0', 'Custas judiciais',15,'t','f','f',4,'text','Custas judiciais');
            insert into configuracoes.db_syscampo values(1015411,'rh299_vlrdespadvogados','float4','Preencher com o valor total das despesas com advogado(s).','0', 'Despesas com advogado(s)',15,'t','f','f',4,'text','Despesas com advogado(s)');
            insert into configuracoes.db_sysarqcamp values(1011103,1015195,1,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015196,2,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015197,3,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015198,4,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015294,5,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015400,6,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015401,7,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015402,8,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015403,9,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015404,10,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015405,11,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015406,12,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015407,13,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015408,14,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015409,15,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015410,16,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015411,17,0);
            delete from configuracoes.db_sysprikey where codarq = 1011103;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011103,1015195,1,1015195);
            delete from configuracoes.db_sysforkey where codarq = 1011103 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011103,1015196,1,1011032,0);
            insert into configuracoes.db_syssequencia values(1001136, 'rhprocessotributoirrf_rh299_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001136 where codarq = 1011103 and codcam = 1015195;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoTributoIrrf() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011103;
            delete from configuracoes.db_sysprikey where codarq = 1011103;
            delete from configuracoes.db_syssequencia where codsequencia = 1001136;
            delete from configuracoes.db_sysarqcamp where codarq = 1011103;
            delete from configuracoes.db_syscampo where codcam in (1015195, 1015196, 1015197, 1015198, 1015294, 1015400, 1015401, 1015402, 1015403, 1015404, 1015405, 1015406, 1015407, 1015408, 1015409, 1015410, 1015411);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011032 and codarq = 1011103;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011103;
            delete from configuracoes.db_sysarquivo where codarq = 1011103;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoTributoIrrf() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessotributoirrf_rh299_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessotributoirrf(
            rh299_sequencial		            int4 NOT NULL default nextval('rhprocessotributoirrf_rh299_sequencial_seq'),
            rh299_sequencialprocessoservidor	int4 NOT NULL default 0,
            rh299_tpcr		                    int4 NOT NULL default 0,
            rh299_vcr		                    float4 default 0,
            rh299_pagamento		                varchar(7)   default '',
            rh299_vrrendtrib		            float4  default 0,
            rh299_vrrendtrib13		            float4  default 0,
            rh299_vrrendmolegrave		        float4  default 0,
            rh299_vrrendIsen65		            float4  default 0,
            rh299_vrjurosmora		            float4  default 0,
            rh299_vrrendIsenntrib		        float4  default 0,
            rh299_descIsenntrib		            varchar(60) default '',
            rh299_vrprevoficial		            float4 default 0,
            rh299_descrra		                varchar(50) default '',
            rh299_qtdmesesrra		            int4 default 0,
            rh299_vlrdespcustas		            float4 default 0,
            rh299_vlrdespadvogados		        float4 default 0,
            CONSTRAINT rhprocessotributoirrf_sequ_pk PRIMARY KEY (rh299_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessotributoirrf
            ADD CONSTRAINT rhprocessotributoirrf_sequencialprocessoservidor_fk FOREIGN KEY (rh299_sequencialprocessoservidor)
            REFERENCES recursoshumanos.rhpessoalprocessoservidor;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoTributoIrrf() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhprocessotributoirrf CASCADE;
            --Criando drop sequences
            DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessotributoirrf_rh299_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhProcessoIRRFComp() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011147, 'rhprocessoirrfcomp', 'Informações relacionadas à retenção na fonte, aos rendimentos tributáveis e não tributáveis, deduções e/ou isenções, etc., de acordo com a legislação aplicada ao imposto de renda.', 'rh310', '2023-09-18', 'IRRF complementar', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011147);
            insert into configuracoes.db_sysarqarq values (1011032,1011147);
            insert into configuracoes.db_syscampo values(1015455,'rh310_sequencial','int4','Sequencial único da tabela.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            insert into configuracoes.db_syscampo values(1015456,'rh310_sequencialprocessoservidor','int4','Sequencial que vincula a tabela RHPESSOALPROCESSOSERVIDOR','0', 'Sequencial vínculo servidor',10,'f','f','f',1,'text','Sequencial vínculo servidor');
            insert into configuracoes.db_syscampo values(1015457,'rh310_dtlaudo','date','Data da moléstia grave atribuída pelo laudo.','null', 'Data laudo',10,'t','f','f',1,'text','Data laudo');
            insert into configuracoes.db_syscampo values(1015458,'rh310_cpfdep','varchar(11)','Número de inscrição no CPF.','', 'CPF',11,'t','t','f',0,'text','CPF');
            insert into configuracoes.db_syscampo values(1015459,'rh310_dtnascto','date','Preencher com a data de nascimento.','null', 'Data de nascimento',10,'t','f','f',1,'text','Data de nascimento');
            insert into configuracoes.db_syscampo values(1015460,'rh310_nome','varchar(70)','Nome do dependente.','', 'Nome do dependente',70,'t','t','f',0,'text','Nome do dependente');
            insert into configuracoes.db_syscampo values(1015461,'rh310_depirrf','varchar(1)','Somente informar este campo em caso de dependente do trabalhador para fins de dedução de seu rendimento tributável pelo Imposto de Renda.','', 'Dependente rendimento tributável',1,'t','t','f',0,'text','Dependente rendimento tributável');
            insert into configuracoes.db_syscampo values(1015462,'rh310_tpdep','varchar(2)','Tipo de dependente.','', 'Tipo de dependente',2,'t','t','f',0,'text','Tipo de dependente');
            insert into configuracoes.db_syscampo values(1015463,'rh310_descrdep','varchar(100)','Informar a descrição da dependência.','', 'Descrição da dependência',100,'t','t','f',0,'text','Descrição da dependência');
            delete from configuracoes.db_sysarqcamp where codarq = 1011147;
            insert into configuracoes.db_sysarqcamp values(1011147,1015455,1,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015456,2,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015457,3,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015458,4,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015459,5,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015460,6,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015461,7,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015462,8,0);
            insert into configuracoes.db_sysarqcamp values(1011147,1015463,9,0);
            delete from configuracoes.db_sysprikey where codarq = 1011147;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011147,1015455,1,1015455);
            delete from configuracoes.db_sysforkey where codarq = 1011147 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011147,1015456,1,1011032,0);
            insert into configuracoes.db_syssequencia values(1001170, 'rhprocessoirrfcomp_rh310_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001170 where codarq = 1011147 and codcam = 1015455;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhProcessoIRRFComp() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011147;
            delete from configuracoes.db_sysprikey where codarq = 1011147;
            delete from configuracoes.db_syssequencia where codsequencia = 1001170;
            delete from configuracoes.db_sysarqcamp where codarq = 1011147;
            delete from configuracoes.db_syscampo where codcam in (1015455, 1015456, 1015457, 1015458, 1015459, 1015460, 1015461, 1015462, 1015463);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011032 and codarq = 1011147;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011147;
            delete from configuracoes.db_sysarquivo where codarq = 1011147;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhProcessoIRRFComp() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhprocessoirrfcomp_rh310_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhprocessoirrfcomp(
            rh310_sequencial		            int4 NOT NULL default nextval('rhprocessoirrfcomp_rh310_sequencial_seq'),
            rh310_sequencialprocessoservidor	int4 NOT NULL default 0,
            rh310_dtlaudo		                date  default null,
            rh310_cpfdep		                varchar(11)   default '',
            rh310_dtnascto		                date  default null,
            rh310_nome		                    varchar(70)   default '',
            rh310_depirrf		                varchar(1)   default '',
            rh310_tpdep		                    varchar(2)   default '',
            rh310_descrdep		                varchar(100)  default '',
            CONSTRAINT rhprocessoirrfcomp_sequ_pk PRIMARY KEY (rh310_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhprocessoirrfcomp
            ADD CONSTRAINT rhprocessoirrfcomp_sequencialprocessoservidor_fk FOREIGN KEY (rh310_sequencialprocessoservidor)
            REFERENCES recursoshumanos.rhpessoalprocessoservidor;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhProcessoIRRFComp() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhprocessoirrfcomp CASCADE;
            --Criando drop sequences
            DROP SEQUENCE IF EXISTS recursoshumanos.rhprocessoirrfcomp_rh310_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

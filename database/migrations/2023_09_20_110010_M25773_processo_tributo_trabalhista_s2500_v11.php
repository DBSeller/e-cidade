<?php

use Illuminate\Database\Migrations\Migration;

class M25773ProcessoTributoTrabalhistaS2500V11 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upItemMenu();

        $this->upDicionarioTabelaRhpessoalProcessoJudicialEsocial();
        $this->upEstruturaTabelaRhpessoalProcessoJudicialEsocial();

        $this->upDicionarioTabelaRhProcessoRubrica();
        $this->upEstruturaTabelaRhProcessoRubrica();

        $this->upDicionarioTabelaRhprocessoTributoBase();
        $this->upEstruturaTabelaRhprocessoTributoBase();

        $this->upEstruturaTabelaRhProcessoTributoContribuicao();
        $this->upDicionarioTabelaRhProcessoTributoContribuicao();

        $this->upDicionarioTabelaRhProcessoTributoIrrf();
        $this->upEstruturaTabelaRhProcessoTributoIrrf();

        $this->upDicionarioTabelaThprocessoexclusao();
        $this->upEstruturaTabelaRhprocessoexclusao();

        $this->upTipoEsocial();
        $this->upEventoEsocial();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       $this->downItemMenu();

       $this->downEstruturaTabelaRhpessoalProcessoJudicialEsocial();
       $this->downDicionarioTabelaRhpessoalProcessoJudicialEsocial();

       $this->downEstruturaTabelaRhProcessoRubrica();
       $this->downDicionarioTabelaRhProcessoRubrica();

       $this->downEstruturaTabelaRhProcessoTributoIrrf();
       $this->downDicionarioTabelaRhProcessoTributoIrrf();

       $this->downEstruturaTabelaRhProcessoTributoContribuicao();
       $this->downDicionarioTabelaRhProcessoTributoContribuicao();
        
       $this->downEstruturaTabelaRhprocessoTributoBase();
       $this->downDicionarioTabelaRhprocessoTributoBase();

       $this->downEstruturaTabelaRhprocessoexclusao();
       $this->downDicionarioTabelaThprocessoexclusao();

       $this->downEventoEsocial();
       $this->downTipoEsocial();
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
            insert into configuracoes.db_sysarqcamp values(1011103,1015195,1,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015196,2,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015197,3,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015198,4,0);
            insert into configuracoes.db_sysarqcamp values(1011103,1015294,5,0);
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
            delete from configuracoes.db_syscampo where codcam in (1015195, 1015196, 1015197, 1015198, 1015294);
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
            DROP TABLE IF EXISTS rhprocessotributoirrf;
            --Criando drop sequences
            DROP SEQUENCE IF EXISTS rhprocessotributoirrf_rh299_sequencial_seq;
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

            insert into habitacao.avaliacao values (4000123, 5, 'S-2501 - Informações de Tributos Decorrentes de Processo Trabalhista', 'S-2501 - Informações de Tributos Decorrentes de Processo Trabalhista', true, 's2501_tributo_trabalhista', null, false);
            SELECT setval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq', (select max(rh211_sequencial) + 1 from recursoshumanos.esocialversaoformulario), false);
            insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.1', 4000123, 50);

            insert into habitacao.avaliacao values (4000124, 5, 'S-3500 - Exclusão de Eventos - Processo Trabalhista', 'S-3500 - Exclusão de Eventos - Processo Trabalhista', true, 's3500_exclusao_trabalhista', null, false);
            SELECT setval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq', (select max(rh211_sequencial) + 1 from recursoshumanos.esocialversaoformulario), false);
            insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.1', 4000124, 51);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downEventoEsocial()
    {
        $sql = <<<SQL
            delete from recursoshumanos.esocialversaoformulario where rh211_versao = 'S1.1'  and rh211_esocialformulariotipo = 50;
            delete from recursoshumanos.esocialversaoformulario where rh211_versao = 'S1.1'  and rh211_esocialformulariotipo = 51;
            DELETE FROM habitacao.avaliacao WHERE db101_sequencial=4000123;
            DELETE FROM habitacao.avaliacao WHERE db101_sequencial=4000124;
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
}
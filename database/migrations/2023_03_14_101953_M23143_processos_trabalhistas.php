<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M23143ProcessosTrabalhistas extends Migration
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

        // $this->upDicionarioTabelaRhpessoalProcessoServidor();
        // $this->upEstruturaTabelaRhpessoalProcessoServidor();

        // $this->upDicionarioTabelaRhpessoalProcessoRemuneracao();
        // $this->upEstruturaTabelaRhpessoalProcessoRemuneracao();

        // $this->upDicionarioTabelaRhpessoalProcessoContrato();
        // $this->upEstruturaTabelaRhpessoalProcessoContrato();

        // $this->upDicionarioTabelaRhpessoalProcessoVinculo();
        // $this->upEstruturaTabelaRhpessoalProcessoVinculo();

        // $this->upDicionarioTabelaRhpessoalProcessoTermTSVE();
        // $this->upEstruturaTabelaRhpessoalProcessoTermTSVE();

        // $this->upDicionarioTabelaRhpessoalProcessoDuracao();
        // $this->upEstruturaTabelaRhpessoalProcessoDuracao();

        // $this->upDicionarioTabelaRhpessoalProcessoObservacao();
        // $this->upEstruturaTabelaRhpessoalProcessoObservacao();

        // $this->upDicionarioTabelaRhpessoalProcessoEstatutario();
        // $this->upEstruturaTabelaRhpessoalProcessoEstatutario();

        // $this->upDicionarioTabelaRhpessoalProcessoDesligamento();
        // $this->upEstruturaTabelaRhpessoalProcessoDesligamento();

        // $this->upDicionarioTabelaRhpessoalProcessoMudanca();
        // $this->upEstruturaTabelaRhpessoalProcessoMudanca();

        // $this->upDicionarioTabelaRhpessoalProcessoUnicidade();
        // $this->upEstruturaTabelaRhpessoalProcessoUnicidade();

        // $this->upDicionarioTabelaRhpessoalProcessoPeriodo();
        // $this->upEstruturaTabelaRhpessoalProcessoPeriodo();

        // $this->upDicionarioTabelaRhpessoalProcessoAbono();
        // $this->upEstruturaTabelaRhpessoalProcessoAbono();

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
        // $this->downItemMenu();

        // $this->downDicionarioTabelaRhpessoalProcessoAbono();
        // $this->downEstruturaTabelaRhpessoalProcessoAbono();

        // $this->downEstruturaTabelaRhpessoalProcessoPeriodo();
        // $this->downDicionarioTabelaRhpessoalProcessoPeriodo();

        // $this->downEstruturaTabelaRhpessoalProcessoUnicidade();
        // $this->downDicionarioTabelaRhpessoalProcessoUnicidade();

        // $this->downEstruturaTabelaRhpessoalProcessoMudanca();
        // $this->downDicionarioTabelaRhpessoalProcessoMudanca();

        // $this->downEstruturaTabelaRhpessoalProcessoDesligamento();
        // $this->downDicionarioTabelaRhpessoalProcessoDesligamento();

        // $this->downEstruturaTabelaRhpessoalProcessoEstatutario();
        // $this->downDicionarioTabelaRhpessoalProcessoEstatutario();

        // $this->downEstruturaTabelaRhpessoalProcessoObservacao();
        // $this->downDicionarioTabelaRhpessoalProcessoObservacao();

        // $this->downEstruturaTabelaRhpessoalProcessoDuracao();
        // $this->downDicionarioTabelaRhpessoalProcessoDuracao();

        // $this->downEstruturaTabelaRhpessoalProcessoTermTSVE(); 
        // $this->downDicionarioTabelaRhpessoalProcessoTermTSVE();

        // $this->downDicionarioTabelaRhpessoalProcessoVinculo();
        // $this->downEstruturaTabelaRhpessoalProcessoVinculo();

        // $this->downEstruturaTabelaRhpessoalProcessoContrato();
        // $this->downDicionarioTabelaRhpessoalProcessoContrato();

        // $this->downEstruturaTabelaRhpessoalProcessoRemuneracao();
        // $this->downDicionarioTabelaRhpessoalProcessoRemuneracao();
        
        // $this->downEstruturaTabelaRhpessoalProcessoServidor();
        // $this->downDicionarioTabelaRhpessoalProcessoServidor();
        
        // $this->downEstruturaTabelaRhpessoalProcessoJudicialEsocial();
        // $this->downDicionarioTabelaRhpessoalProcessoJudicialEsocial();

        // $this->downEventoEsocial();
        // $this->downTipoEsocial();

    }

    private function upItemMenu() {
        $sql  = <<<SQL
            insert into configuracoes.db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228873 ,'Processos Judiciais da Folha / Trabalhista eSocial' ,'Processos judiciais do eSocial' ,'' ,'1' ,'1' ,'Processos judiciais do eSocial' ,'true' );
            delete from configuracoes.db_menu where id_item_filho = 228873 AND modulo = 952;
            insert into configuracoes.db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4354 ,228873 ,13 ,952 );
            insert into configuracoes.db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228874 ,'Informações Processo Trabalhista (S-2500)' ,'Informações para o envio do evento eSocial S-2500' ,'pes1_rhpessoalprocessosjudiciaisesocial001.php' ,'1' ,'1' ,' Informações decorrentes de processos trabalhistas perante a Justiça do Trabalho e de acordos celebrados no âmbito das Comissões de Conciliação Prévia - CCP e dos Núcleos Intersindicais - Ninter. Neste evento são prestadas informações cadastrais e contratuais relativas ao vínculo, as bases de cálculo para recolhimento de FGTS e da contribuição previdenciária do RGPS.' ,'true' );
            delete from configuracoes.db_menu where id_item_filho = 228874 AND modulo = 952;
            insert into configuracoes.db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228873 ,228874 ,1 ,952 );
            insert into configuracoes.db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228875 ,'Informaçõe de Tributos de Processos trabalhistas (S-2501)' ,'Informaçõe de Tributos de Processos trabalhistas' ,'pes1_rhpessoalprocessosjudiciaisfolha001.php' ,'1' ,'1' ,'Informar os valores do imposto sobre a renda da pessoa física e das contribuições sociais previdenciárias, inclusive as destinadas a Terceiros, incidentes sobre as base de cálculo constantes das decisões condenatórias e homologatórias de acordo proferidas nos processos trabalhistas perante a Justiça do Trabalho e nos acordos celebrados no âmbito das Comissões de Conciliação Prévia - CCP e dos Núcleos Intersindicais - Ninter, que foram informados no evento S-2500.' ,'true' );
            delete from configuracoes.db_menu where id_item_filho = 228875 AND modulo = 952;
            insert into configuracoes.db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228873 ,228875 ,2 ,952 );
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downItemMenu() {
        $sql  = <<<SQL
            delete from configuracoes.db_menu where id_item_filho = 228873 AND modulo = 952;
            delete from configuracoes.db_itensmenu where id_item = 228873; 
            delete from configuracoes.db_menu where id_item_filho = 228874 AND modulo = 952;
            delete from configuracoes.db_itensmenu where id_item = 228874; 
            delete from configuracoes.db_menu where id_item_filho = 228875 AND modulo = 952;
            delete from configuracoes.db_itensmenu where id_item = 228875; 
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011031, 'rhpessoalprocessojudicialesocial', 'Registros do evento S-2500 - Processo Trabalhista', 'rh270', '2023-03-16', 'Processo Trabalhista', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011031);
            
            insert into configuracoes.db_syscampo values(1014804,'rh270_sequencial','int4','úmero único da tabela.','0', 'Número Sequencial',4,'f','f','t',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014805,'rh270_nrproctrab','varchar(20)','Número do processo trabalhista, da ata ou número de identificação da conciliação.','', 'Número do Processo Trabalhista',20,'t','f','f',0,'text','Número do Processo Trabalhista');
            insert into configuracoes.db_syscampo values(1014806,'rh270_obsproctrab','text','Observações relacionadas ao processo judicial ou à demanda submetida à CCP ou ao NINTER.','', 'Observações',10,'t','f','f',0,'text','Observações');
            insert into configuracoes.db_syscampo values(1014807,'rh270_dtsent','varchar(20)','Data da Sentença do processo','null', 'Data da Sentença',10,'t','f','f',1,'text','Data da Sentença');
            insert into configuracoes.db_syscampo values(1014808,'rh270_ufvara','varchar(2)','Sigla da Unidade da Federação onde está localizada a Vara em que o processo tramitou.','', 'Unidade da Federação',2,'t','f','f',0,'text','Unidade da Federação');
            insert into configuracoes.db_syscampo values(1014809,'rh270_codmunic','int4','Código do município, conforme tabela do IBGE.','0', 'Código Município (IBGE)',7,'t','f','f',1,'text','Código Município (IBGE)');
            insert into configuracoes.db_syscampo values(1014810,'rh270_idvara','int4','Código de identificação da Vara em que o processo tramitou.','0', 'Vara',4,'t','f','f',1,'text','Vara');
            insert into configuracoes.db_syscampo values(1014811,'rh270_origem','int4','Origem do Processo','0', 'Origem do Processo',1,'t','f','f',1,'text','Origem do Processo');
            insert into configuracoes.db_syscampo values(1014812,'rh270_dtccp','date','Data da celebração do acordo celebrado perante CCP ou Ninter.','null', 'Data Acordo',10,'t','f','f',1,'text','Data Acordo');
            insert into configuracoes.db_syscampo values(1014813,'rh270_tpccp','int4','Indica o âmbito de celebração do acordo.','0', 'Âmbito Acordo',1,'t','f','f',1,'text','Âmbito Acordo');
            insert into configuracoes.db_syscampo values(1014814,'rh270_cnpjccp','varchar(14)','CNPJ do sindicato representativo do trabalhador, no âmbito da CCP ou NINTER.','', 'CNPJ do Sindicato',14,'t','t','f',0,'text','CNPJ do Sindicato');
            insert into configuracoes.db_syscampo values(1014816,'rh270_compini','varchar(7)','Competência inicial a que se refere o processo ou conciliação, no formato AAAA-MM.','', 'Competência Inicial',7,'t','t','f',0,'text','Competência Inicial');
            insert into configuracoes.db_syscampo values(1014817,'rh270_compfim','varchar(7)','Competência final a que se refere o processo ou conciliação, no formato AAAA-MM.','', 'Competência Final ',7,'t','t','f',0,'text','Competência Final ');
            delete from configuracoes.db_sysarqcamp where codarq = 1011031;
            insert into configuracoes.db_sysarqcamp values(1011031,1014804,1,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014805,2,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014806,3,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014807,4,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014808,5,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014809,6,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014810,7,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014811,8,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014812,9,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014813,10,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014814,11,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014816,12,0);
            insert into configuracoes.db_sysarqcamp values(1011031,1014817,13,0);
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011031,1014804,1,1014804);
            insert into configuracoes.db_syssequencia values(1001119, 'rhpessoalprocessojudicialesocial_rh270_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001119 where codarq = 1011031 and codcam = 1014804;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysprikey where codarq = 1011031;
            delete from configuracoes.db_sysarqcamp where codarq = 1011031;
            delete from configuracoes.db_syscampo where codcam in (1014804, 1014805, 1014806, 1014807, 1014808, 1014809, 1014810, 1014811, 1014812, 1014813, 1014814, 1014816, 1014817);
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011031;
            delete from configuracoes.db_sysarquivo where codarq = 1011031;
            delete from configuracoes.db_syssequencia where codsequencia = 1001119;
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011031 and codcam = 1014804;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessojudicialesocial_rh270_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            CREATE TABLE IF NOT EXISTS recursoshumanos.rhpessoalprocessojudicialesocial (
                rh270_sequencial  serial4 NOT NULL,
                rh270_nrproctrab  varchar(20) NULL,
                rh270_obsproctrab text NULL,
                rh270_dtsent	  date  default null,
                rh270_ufvara	  varchar(2)   default '',
                rh270_codmunic	  int4  default 0,
                rh270_idvara	  int4  default 0,
                rh270_origem	  int4  default 0,
                rh270_dtccp		  date  default null,
                rh270_tpccp		  int4  default 0,
                rh270_cnpjccp	  varchar(14)   default '',
                rh270_compini	  varchar(7)   default '',
                rh270_compfim	  varchar(7)   default '',
            CONSTRAINT rhpessoalprocessojudicialesocial_sequ_pk PRIMARY KEY (rh270_sequencial)

            );
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoJudicialEsocial() {
        $sql  = <<<SQL
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessojudicialesocial;
            DROP SEQUENCE recursoshumanos.rhpessoalprocessojudicialesocial_rh270_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoServidor() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011032, 'rhpessoalprocessoservidor', 'Consta registro do servidor em relação ao processos trabalhista do eSocial (rhpessoalprocessojudicialesocial).', 'rh271', '2023-03-20', 'Processo Trabalhista Servidor', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011032);
            insert into configuracoes.db_sysarqarq values (1011031,1011032);
            insert into configuracoes.db_syscampo values(1014819,'rh271_sequencial','int4','Número sequencial único de identificação do registro.','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014820,'rh271_sequencialprocesso','int4','Identificação única de processo (FK) da tabela rhpessoalprocessojudicialesocial','0', 'Identificação única de processo',10,'f','f','f',1,'text','Identificação única de processo');
            insert into configuracoes.db_syscampo values(1014821,'rh271_matricula','int4','Identificação única do servidor','0', 'Matrícula servidor',10,'t','f','f',1,'text','Matrícula servidor');
            insert into configuracoes.db_syscampo values(1014823,'rh271_codcateg','int4','Código de categoria do servidor.','0', 'Código Categoria',10,'t','f','f',1,'text','Código Categoria');
            insert into configuracoes.db_syscampo values(1014929,'rh271_instit','int4','Código de instituição.','0', 'Instituição',10,'t','f','f',1,'text','Instituição');
            delete from configuracoes.db_sysarqcamp where codarq = 1011032;
            insert into configuracoes.db_sysarqcamp values(1011032,1014819,1,0);
            insert into configuracoes.db_sysarqcamp values(1011032,1014820,2,0);
            insert into configuracoes.db_sysarqcamp values(1011032,1014821,3,0);
            insert into configuracoes.db_sysarqcamp values(1011032,1014823,4,0);
            insert into configuracoes.db_sysarqcamp values(1011032,1014929,5,0);
            delete from configuracoes.db_sysprikey where codarq = 1011032;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011032,1014819,1,1014819);
            delete from configuracoes.db_sysforkey where codarq = 1011032 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011032,1014820,1,1011031,0);
            insert into configuracoes.db_syssequencia values(1001120, 'rhpessoalprocessoservidor_rh271_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001120 where codarq = 1011032 and codcam = 1014819;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoServidor() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011032 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011032;
            delete from configuracoes.db_sysarqcamp where codarq = 1011032;
            delete from configuracoes.db_sysforkey where codcam = 1014820;
            delete from configuracoes.db_syscampo where codcam in (1014819, 1014820, 1014821, 1014823, 1014929);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011031 and codarq = 1011032;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011032;
            delete from configuracoes.db_sysarquivo where codarq = 1011032;
            delete from configuracoes.db_syssequencia where codsequencia = 1001120;
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011032 and codcam = 1014819;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoServidor() {
        $sql  = <<<SQL
              CREATE SEQUENCE recursoshumanos.rhpessoalprocessoservidor_rh271_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE IF NOT EXISTS recursoshumanos.rhpessoalprocessoservidor(
            rh271_sequencial		int4 NOT NULL DEFAULT nextval('rhpessoalprocessoservidor_rh271_sequencial_seq'),
            rh271_sequencialprocesso		int4 NOT NULL default 0,
            rh271_matricula		int4  default 0,
            rh271_codcateg		int4 default 0,
            rh271_instit		int4 default 0,
            CONSTRAINT rhpessoalprocessoservidor_sequ_pk PRIMARY KEY (rh271_sequencial));
           -- CHAVE ESTRANGEIRA
            ALTER TABLE rhpessoalprocessoservidor
            ADD CONSTRAINT rhpessoalprocessoservidor_sequencialprocesso_fk FOREIGN KEY (rh271_sequencialprocesso)
            REFERENCES rhpessoalprocessojudicialesocial;

       
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoServidor() {
        $sql  = <<<SQL
            --DROP TABLE. :
            DROP TABLE  IF EXISTS recursoshumanos.rhpessoalprocessoservidor;
            DROP SEQUENCE recursoshumanos.rhpessoalprocessoservidor_rh271_sequencial_seq;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoRemuneracao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011033, 'rhpessoalprocessoremuneracao', 'Informações da remuneração e periodicidade de pagamento de servidor no eSocial.', 'rh272', '2023-03-20', 'Remuneração do servidor', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011033);
            insert into configuracoes.db_sysarqarq values (1011032,1011033);
            insert into configuracoes.db_syscampo values(1014824,'rh272_sequencial','int4','Código sequencial único da tabela.','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014825,'rh271_sequencialprocessoservidor','int4','Identificação única de servidor processado (FK) da tabela rhpessoalprocessoservidor','0', 'Sevidor Processado',10,'f','f','f',1,'text','Sevidor Processado');
            insert into configuracoes.db_syscampo values(1014826,'rh272_dtremun','date','Data a partir da qual as informações de remuneração e periodicidade de pagamento estão vigentes.','null', 'Data Remuneração',10,'f','f','f',1,'text','Data Remuneração');
            insert into configuracoes.db_syscampo values(1014827,'rh272_vrsalfx','float4','Salário base do trabalhador, correspondente à parte fixa da remuneração.','0', 'Salário base',10,'f','f','f',4,'text','Salário base');
            insert into configuracoes.db_syscampo values(1014828,'rh272_undSalFixo','int4','Unidade de pagamento da parte fixa da remuneração.','0', 'Unidade de pagamento',1,'t','f','f',1,'text','Unidade de pagamento');
            insert into configuracoes.db_syscampo values(1014829,'rh272_dscSalVar','text','Descrição do salário por tarefa ou variável e como este é calculado. Ex.: Comissões pagas no percentual de 10% sobre as vendas.','', 'Descrição',1,'t','t','f',0,'text','Descrição');
            delete from configuracoes.db_sysarqcamp where codarq = 1011033;
            insert into configuracoes.db_sysarqcamp values(1011033,1014824,1,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014825,2,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014826,3,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014827,4,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014828,5,0);
            insert into configuracoes.db_sysarqcamp values(1011033,1014829,6,0);
            delete from configuracoes.db_sysprikey where codarq = 1011033;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011033,1014824,1,1014824);
            delete from configuracoes.db_sysforkey where codarq = 1011033 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011033,1014825,1,1011032,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoRemuneracao() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011033 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011033;
            delete from configuracoes.db_sysarqcamp where codarq = 1011033;
            delete from configuracoes.db_sysforkey where codcam = 1014825;
            delete from configuracoes.db_syscampo where codcam in (1014824, 1014825, 1014826, 1014827, 1014828, 1014829);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011032 and codarq = 1011033;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011033;
            delete from configuracoes.db_sysarquivo where codarq = 1011033;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoRemuneracao() {
        $sql  = <<<SQL
            -- Criando  sequences
              CREATE SEQUENCE recursoshumanos.rhpessoalprocessoservidor_rh272_sequencial
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;           
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE IF NOT EXISTS recursoshumanos.rhpessoalprocessoremuneracao(
            rh272_sequencial		int4 NOT NULL default nextval('rhpessoalprocessoservidor_rh272_sequencial'),
            rh271_sequencialprocessoservidor		int4 NOT NULL default 0,
            rh272_dtremun		date NOT NULL default null,
            rh272_vrsalfx		float4 NOT NULL default 0,
            rh272_undSalFixo		int4  default 0,
            rh272_dscSalVar		text  default '',
            CONSTRAINT rhpessoalprocessoremuneracao_sequ_pk PRIMARY KEY (rh272_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE rhpessoalprocessoremuneracao
            ADD CONSTRAINT rhpessoalprocessoremuneracao_sequencialprocessoservidor_fk FOREIGN KEY (rh271_sequencialprocessoservidor)
            REFERENCES rhpessoalprocessoservidor;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoRemuneracao() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessoremuneracao;
            DROP SEQUENCE recursoshumanos.rhpessoalprocessoservidor_rh272_sequencial ;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoContrato() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011034, 'rhpessoalprocessocontrato', 'Informações do contrato de trabalho. Vinculada a tabela rhpessoalprocessoservidor.', 'rh273', '2023-03-21', 'Contrato de Trabalho', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011034);
            insert into configuracoes.db_sysarqarq values (1011032,1011034);
            insert into configuracoes.db_syscampo values(1014830,'rh273_sequencial','int4','Código único da tabela','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014831,'rh273_sequencialprocessoservidor','int4','Identificação única de processo do servidor.','0', 'Identificação única de processo',10,'f','f','f',1,'text','Identificação única de processo');
            insert into configuracoes.db_syscampo values(1014832,'rh273_tpcontr ','int4','Tipo de contrato a que se refere o processo judicial ou a demanda submetida à CCP ou ao NINTER.','0', 'Tipo de contrato',1,'t','f','f',1,'text','Tipo de contrato');
            insert into configuracoes.db_syscampo values(1014833,'rh273_indcontr ','varchar(1)','Indicativo se o contrato possui informação no evento S-2190, S-2200 ou S-2300 no declarante.','', 'Indicativo de contrato',1,'t','t','f',0,'text','Indicativo de contrato');
            insert into configuracoes.db_syscampo values(1014834,'rh273_dtadmorig ','date','Data de admissão original do vínculo (data de admissão antes da alteração).','null', 'Data de admissão ',10,'t','f','f',1,'text','Data de admissão ');
            insert into configuracoes.db_syscampo values(1014835,'rh273_indreint ','varchar(1)','Indicativo de reintegração do empregado.','', 'Indicativo de reintegração',1,'t','t','f',0,'text','Indicativo de reintegração');
            insert into configuracoes.db_syscampo values(1014836,'rh273_indcateg','varchar(1)','Indicativo se houve reconhecimento de categoria do trabalhador diferente da informada (no eSocial ou na GFIP) pelo declarante.','', 'Categoria diferente',1,'t','t','f',0,'text','Categoria diferente');
            insert into configuracoes.db_syscampo values(1014837,'rh273_indnatativ','varchar(1)','Indicativo se houve reconhecimento de natureza da atividade diferente da cadastrada pelo declarante.','', 'Natureza da atividade',1,'t','t','f',0,'text','Natureza da atividade');
            insert into configuracoes.db_syscampo values(1014838,'rh273_indmotdeslig ','varchar(2)','Indicativo se houve reconhecimento de motivo de desligamento diferente do informado pelo declarante.','', 'Motivo Desligamento',1,'f','t','f',0,'text','Motivo Desligamento');
            insert into configuracoes.db_syscampo values(1014840,'rh273_dinicio ','date','Data de início de TSVE','null', 'Início de TSVE',10,'t','f','f',1,'text','Início de TSVE');
            insert into configuracoes.db_syscampo values(1014841,'rh273_codcbo','varchar(6)','Classificação Brasileira de Ocupações - CBO.','', 'Código CBO',6,'t','t','f',0,'text','Código CBO');
            insert into configuracoes.db_syscampo values(1014842,'rh273_natatividade','int4','Natureza da atividade','0', 'Natureza da atividade',1,'t','f','f',1,'text','Natureza da atividade');
            insert into configuracoes.db_syscampo values(1014843,'rh273_tplnsc ','int4','Código correspondente ao tipo de inscrição.Valores válidos: 1 - CNPJ; 2 - CPF; 5 - CGC; 6 - CEI','0', 'Tipo de inscrição',1,'t','f','f',1,'text','Tipo de inscrição');
            insert into configuracoes.db_syscampo values(1014844,'rh273_nrlnsc ','varchar(14)','Número de inscrição do empregador anterior, de acordo com o tipo de inscrição.','', 'Número de inscrição',14,'t','t','f',0,'text','Número de inscrição');
            insert into configuracoes.db_syscampo values(1014845,'rh273_compini','varchar(7)','Competência inicial a que se refere o processo ou conciliação, no formato AAAA-MM.','', 'Competência inicial ',7,'t','t','f',0,'text','Competência inicial ');
            insert into configuracoes.db_syscampo values(1014846,'rh273_compfim ','varchar(7)','Competência final a que se refere o processo ou conciliação, no formato AAAA-MM.','', 'Competência final ',7,'t','t','f',0,'text','Competência final ');
            insert into configuracoes.db_syscampo values(1015363,'rh273_indreperc','int4','Indicativo de repercussão do processo trabalhista ou de demanda submetida à CCP ou ao NINTER. Valores válidos: 1 - Decisão com repercussão tributária e/ou FGTS 2 - Decisão sem repercussão tributária ou FGTS 3 - Decisão com repercussão exclusiva para declaração de rendimentos para fins de Imposto de Renda','0', 'Indicativo de repercussão',10,'t','f','f',1,'text','Indicativo de repercussão');
            insert into configuracoes.db_syscampo values(1015364,'rh273_indensd','varchar(2)','Houve decisão para pagamento da indenização substitutiva do seguro-desemprego? Valores válidos: S - Sim','', 'Pagamento da indenização',2,'t','t','f',0,'text','Pagamento da indenização');
            insert into configuracoes.db_syscampo values(1015365,'rh273_indenabono','varchar(2)','Houve decisão para pagamento da indenização substitutiva de abono salarial? Valores válidos: S - Sim','', 'Indenização abono salarial',2,'t','t','f',0,'text','Indenização abono salarial');
            delete from configuracoes.db_sysarqcamp where codarq = 1011034;
            insert into configuracoes.db_sysarqcamp values(1011034,1014830,1,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014831,2,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014832,3,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014833,4,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014834,5,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014835,6,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014836,7,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014837,8,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014838,9,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014840,10,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014841,11,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014842,12,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014845,13,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1014846,14,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1015365,15,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1015364,16,0);
            insert into configuracoes.db_sysarqcamp values(1011034,1015363,17,0);
            delete from configuracoes.db_sysprikey where codarq = 1011034;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011034,1014830,1,1014830);
            delete from configuracoes.db_sysforkey where codarq = 1011034 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011034,1014831,1,1011032,0);

            insert into configuracoes.db_syssequencia values(1001121, 'rhpessoalprocessocontrato_rh273_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001121 where codarq = 1011034 and codcam = 1014830;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoContrato() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011034 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011034;
            delete from configuracoes.db_sysarqcamp where codarq = 1011034;
            delete from configuracoes.db_sysforkey where codcam = 1014831;
            delete from configuracoes.db_syscampodef where codcam in (1014830, 1014831, 1014832, 1014833, 1014834, 1014835, 1014836, 1014837, 1014838, 1014840, 1014841, 1014842, 1014843, 1014844, 1014845, 1014846, 1015365, 1015364, 1015363);
            delete from configuracoes.db_syscampodep where codcam in (1014830, 1014831, 1014832, 1014833, 1014834, 1014835, 1014836, 1014837, 1014838, 1014840, 1014841, 1014842, 1014843, 1014844, 1014845, 1014846, 1015365, 1015364, 1015363);
            delete from configuracoes.db_syscampo where codcam in (1014830, 1014831, 1014832, 1014833, 1014834, 1014835, 1014836, 1014837, 1014838, 1014840, 1014841, 1014842, 1014843, 1014844, 1014845, 1014846, 1015365, 1015364, 1015363);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011032 and codarq = 1011034;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011034;
            delete from configuracoes.db_sysarquivo where codarq = 1011034;
            delete from configuracoes.db_syssequencia where codsequencia = 1001121;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoContrato() {
        $sql  = <<<SQL

            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessocontrato_rh273_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            -- 
            CREATE TABLE recursoshumanos.rhpessoalprocessocontrato(
            rh273_sequencial		int4 NOT NULL default nextval('rhpessoalprocessocontrato_rh273_sequencial_seq'),
            rh273_sequencialprocessoservidor		int4 NOT NULL default 0,
            rh273_tpcontr		int4  default 0,
            rh273_indcontr		varchar(1)   default '',
            rh273_dtadmorig		date  default null,
            rh273_indreint		varchar(1)   default '',
            rh273_indcateg		varchar(1)   default '',
            rh273_indnatativ		varchar(1)   default '',
            rh273_indmotdeslig		varchar(2)   default '',
            rh273_dinicio		date  default null,
            rh273_codcbo		varchar(6)   default '',
            rh273_natatividade		int4  default 0,
            rh273_compini		varchar(7)   default '',
            rh273_compfim		varchar(7)   default '',
            rh273_indreperc 		int4  default 0,
            rh273_indensd		varchar(1)   default '',
            rh273_indenabono		varchar(1)   default '',
            CONSTRAINT rhpessoalprocessocontrato_sequ_pk PRIMARY KEY (rh273_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhpessoalprocessocontrato
            ADD CONSTRAINT rhpessoalprocessocontrato_sequencialprocessoservidor_fk FOREIGN KEY (rh273_sequencialprocessoservidor)
            REFERENCES rhpessoalprocessoservidor;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoContrato() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessocontrato;
            DROP SEQUENCE recursoshumanos.rhpessoalprocessocontrato_rh273_sequencial_seq ;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoVinculo() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011036, 'rhpessoalprocessovinculo', 'Informações sobre o vínculo trabalhista.', 'rh274', '2023-03-21', 'Informações sobre o vínculo trabalhista.', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011036);
            insert into configuracoes.db_sysarqarq values (3786,1011036);
            insert into configuracoes.db_syscampo values(1014858,'rh274_sequencial','int4','Código único sequencial da tabela.','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014859,'rh274_sequencialprocessoservidor','int4','Servidor Processado (FK) ','0', 'Servidor Processado',10,'f','f','f',1,'text','Servidor Processado');
            insert into configuracoes.db_syscampo values(1014860,'rh274_tpregtrab','int4','Tipo de regime trabalhista.','0', 'Tipo de regime',1,'t','f','f',1,'text','Tipo de regime');
            insert into configuracoes.db_syscampo values(1014861,'rh274_tpregprev','int4','Tipo de regime previdenciário','0', 'Tipo de regime previdenciário',1,'t','f','f',1,'text','Tipo de regime previdenciário');
            insert into configuracoes.db_syscampo values(1014862,'rh274_dtadm','date','Data de admissão do trabalhador.','null', 'Data de admissão',10,'t','f','f',1,'text','Data de admissão');
            insert into configuracoes.db_syscampo values(1014863,'rh274_tmpparc','int4','Código relativo ao tipo de contrato em tempo parcial.','0', 'Tipo de contrato',1,'t','f','f',1,'text','Tipo de contrato');
            insert into configuracoes.db_sysarqcamp values(1011036,1014858,1,0);
            insert into configuracoes.db_sysarqcamp values(1011036,1014859,2,0);
            insert into configuracoes.db_sysarqcamp values(1011036,1014860,3,0);
            insert into configuracoes.db_sysarqcamp values(1011036,1014861,4,0);
            insert into configuracoes.db_sysarqcamp values(1011036,1014862,5,0);
            insert into configuracoes.db_sysarqcamp values(1011036,1014863,6,0);
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011036,1014858,1,1014858);
            insert into configuracoes.db_sysforkey values(1011036,1014859,1,1011032,0);
            insert into configuracoes.db_syssequencia values(1001157, 'rhpessoalprocessovinculo_rh274_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001157 where codarq = 1011036 and codcam = 1014858;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoVinculo() {
        $sql  = <<<SQL
            delete from configuracoes.db_syssequencia where codsequencia = 1001157;
            delete from configuracoes.db_sysforkey where codarq = 1011036 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011036;
            delete from configuracoes.db_sysarqcamp where codarq = 1011036;
            delete from configuracoes.db_sysforkey where codcam = 1014859;
            delete from configuracoes.db_syscampodef where codcam in (1014858, 1014859, 1014860, 1014861, 1014862, 1014863);
            delete from configuracoes.db_syscampodep where codcam in (1014858, 1014859, 1014860, 1014861, 1014862, 1014863);
            delete from configuracoes.db_syscampo where codcam in (1014858, 1014859, 1014860, 1014861, 1014862, 1014863);
            delete from configuracoes.db_sysarqarq where codarqpai = 3786 and codarq = 1011036;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011036;
            delete from configuracoes.db_sysarquivo where codarq = 1011036;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoVinculo() {
        $sql  = <<<SQL

            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessovinculo_rh274_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhpessoalprocessovinculo(
            rh274_sequencial		int4 NOT NULL default nextval('rhpessoalprocessovinculo_rh274_sequencial_seq'),
            rh274_sequencialprocessoservidor		int4 NOT NULL default 0,
            rh274_tpregtrab		int4  default 0,
            rh274_tpregprev		int4  default 0,
            rh274_dtadm		date  default null,
            rh274_tmpparc		int4 default 0,
            CONSTRAINT rhpessoalprocessovinculo_sequ_pk PRIMARY KEY (rh274_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE rhpessoalprocessovinculo
            ADD CONSTRAINT rhpessoalprocessovinculo_sequencialprocessoservidor_fk FOREIGN KEY (rh274_sequencialprocessoservidor)
            REFERENCES rhpessoalprocessoservidor;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoVinculo() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessovinculo;
            DROP SEQUENCE recursoshumanos.rhpessoalprocessovinculo_rh274_sequencial_seq ;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }    

    private function upDicionarioTabelaRhpessoalProcessoTermTSVE() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011038, 'rhpessoalprocessotermtsve', 'Informações de término de TSVE.', 'rh275', '2023-03-21', 'Informações de término de TSVE', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011038);
            insert into configuracoes.db_sysarqarq values (1011032,1011038);
            insert into configuracoes.db_syscampo values(1014864,'rh275_sequencial','int4','Código único sequencial da tabela','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014865,'rh275_sequencialprocessoservidor','int4','Servidor Processado (FK)','0', 'Servidor Processado',10,'f','f','f',1,'text','Servidor Processado');
            insert into configuracoes.db_syscampo values(1014866,'rh275_dtterm ','date','Data do término.','null', 'Data do término',10,'t','f','f',1,'text','Data do término');
            insert into configuracoes.db_syscampo values(1014867,'rh275_mtvdesligtsv','varchar(2)','Motivo do término do diretor não empregado, com FGTS.','', 'Motivo do término',2,'t','t','f',0,'text','Motivo do término');
            insert into configuracoes.db_sysarqcamp values(1011038,1014864,1,0);
            insert into configuracoes.db_sysarqcamp values(1011038,1014865,2,0);
            insert into configuracoes.db_sysarqcamp values(1011038,1014866,3,0);
            insert into configuracoes.db_sysarqcamp values(1011038,1014867,4,0);
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011038,1014864,1,1014864);
            delete from configuracoes.db_sysforkey where codarq = 1011038 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011038,1014865,1,1011032,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoTermTSVE() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011038 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011038;
            delete from configuracoes.db_sysarqcamp where codarq = 1011038;
            delete from configuracoes.db_sysforkey where codcam = 1014865;
            delete from configuracoes.db_syscampodef where codcam in (1014864, 1014865, 1014866, 1014867);
            delete from configuracoes.db_syscampodep where codcam in (1014864, 1014865, 1014866, 1014867);
            delete from configuracoes.db_syscampo where codcam in (1014864, 1014865, 1014866, 1014867);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011032 and codarq = 1011038;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011038;
            delete from configuracoes.db_sysarquivo where codarq = 1011038;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoTermTSVE() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessotermtsve_rh275_sequencial
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhpessoalprocessotermtsve(
            rh275_sequencial		int4 NOT NULL default nextval('rhpessoalprocessotermtsve_rh275_sequencial'),
            rh275_sequencialprocessoservidor		int4 NOT NULL default 0,
            rh275_dtterm		date  default null,
            rh275_mtvdesligtsv		varchar(2)  default '',
            CONSTRAINT rhpessoalprocessotermtsve_sequ_pk PRIMARY KEY (rh275_sequencial));

            ALTER TABLE recursoshumanos.rhpessoalprocessotermtsve
            ADD CONSTRAINT rhpessoalprocessotermtsve_sequencialprocessoservidor_fk FOREIGN KEY (rh275_sequencialprocessoservidor)
            REFERENCES recursoshumanos.rhpessoalprocessoservidor;


SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoTermTSVE() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessotermtsve;
            DROP SEQUENCE recursoshumanos.rhpessoalprocessotermtsve_rh275_sequencial ;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }    

    private function upDicionarioTabelaRhpessoalProcessoDuracao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011040, 'rhpessoalprocessoduracao', 'Duração do contrato de trabalho.', 'rh276', '2023-03-21', 'Duração do contrato de trabalho.', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011040);
            insert into configuracoes.db_sysarqarq values (1011036,1011040);
            insert into configuracoes.db_syscampo values(1014868,'rh276_sequencial','int4','Código sequencial único da tabela.','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014869,'rh276_sequencialprocessovinculo','int4','Código sequencial que vincula a tabela rhpessoalprocessovinculo.','0', 'Processo vinculo',10,'f','f','f',1,'text','Processo vinculo');
            insert into configuracoes.db_syscampo values(1014870,'rh276_tpcontr ','int4','Tipo de contrato de trabalho.','0', 'Tipo de contrato',1,'t','f','f',1,'text','Tipo de contrato');
            insert into configuracoes.db_syscampo values(1014871,'rh276_dtterm ','date','Data do término do contrato por prazo determinado.','null', 'Data do término',10,'t','f','f',1,'text','Data do término');
            insert into configuracoes.db_syscampo values(1014872,'rh276_clauassec','varchar(1)','Indicar se o contrato por prazo determinado contém cláusula assecuratória do direito recíproco de rescisão antes da data de seu término.','', 'Cláusula assecuratória',1,'f','t','f',0,'text','Cláusula assecuratória');
            insert into configuracoes.db_syscampo values(1014873,'rh276_objdet ','varchar(255)','objeto determinante ','', 'Objeto determinante ',255,'f','t','f',0,'text','Objeto determinante ');
            insert into configuracoes.db_sysarqcamp values(1011040,1014868,1,0);
            insert into configuracoes.db_sysarqcamp values(1011040,1014869,2,0);
            insert into configuracoes.db_sysarqcamp values(1011040,1014870,3,0);
            insert into configuracoes.db_sysarqcamp values(1011040,1014871,4,0);
            insert into configuracoes.db_sysarqcamp values(1011040,1014872,5,0);
            insert into configuracoes.db_sysarqcamp values(1011040,1014873,6,0);
            delete from configuracoes.db_sysprikey where codarq = 1011040;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011040,1014868,1,1014868);
            delete from configuracoes.db_sysforkey where codarq = 1011040 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011040,1014869,1,1011036,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoDuracao() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011040 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011040;
            delete from configuracoes.db_sysarqcamp where codarq = 1011040;
            delete from configuracoes.db_sysforkey where codcam = 1014869;
            delete from configuracoes.db_syscampodef where codcam in (1014868, 1014869, 1014870, 1014871, 1014872, 1014873);
            delete from configuracoes.db_syscampodep where codcam in (1014868, 1014869, 1014870, 1014871, 1014872, 1014873);
            delete from configuracoes.db_syscampo where codcam in (1014868, 1014869, 1014870, 1014871, 1014872, 1014873);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011036 and codarq = 1011040;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011040;
            delete from configuracoes.db_sysarquivo where codarq = 1011040;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoDuracao() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessoduracao_rh276_sequencial
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhpessoalprocessoduracao(
            rh276_sequencial		int4 NOT NULL default nextval('rhpessoalprocessoduracao_rh276_sequencial'),
            rh276_sequencialprocessovinculo		int4 NOT NULL default 0,
            rh276_tpcontr		int4  default 0,
            rh276_dtterm		date  default null,
            rh276_clauassec		varchar(1) NOT NULL  default '',
            rh276_objdet		varchar(255)  default '',
            CONSTRAINT rhpessoalprocessoduracao_sequ_pk PRIMARY KEY (rh276_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhpessoalprocessoduracao
            ADD CONSTRAINT rhpessoalprocessoduracao_sequencialprocessovinculo_fk FOREIGN KEY (rh276_sequencialprocessovinculo)
            REFERENCES recursoshumanos.rhpessoalprocessovinculo;


SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoDuracao() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessoduracao;
            DROP SEQUENCE recursoshumanos.rhpessoalprocessoduracao_rh276_sequencial ;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoObservacao() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011041, 'rhpessoalprocessoobservacao', 'Observações do contrato de trabalho.', 'rh277', '2023-03-21', 'Observações do contrato de trabalho', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011041);
            insert into configuracoes.db_sysarqarq values (1011036,1011041);
            insert into configuracoes.db_syscampo values(1014874,'rh277_sequencial','int4','Código único sequencial da tabela','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014875,'rh277_sequencialprocessovinculo','int4','Código que vincula a tabela rhpessoalprocessovinculo (FK).','0', 'Processo vinculo',10,'f','f','f',1,'text','Processo vinculo');
            insert into configuracoes.db_syscampo values(1014876,'rh277_observacao','varchar(255)','Observação relacionada ao contrato de trabalho.','', 'Observação',255,'f','t','f',0,'text','Observação');
            insert into configuracoes.db_sysarqcamp values(1011041,1014874,1,0);
            insert into configuracoes.db_sysarqcamp values(1011041,1014875,2,0);
            insert into configuracoes.db_sysarqcamp values(1011041,1014876,3,0);
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011041,1014874,1,1014874);
            insert into configuracoes.db_sysforkey values(1011041,1014875,1,1011036,0);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoObservacao() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011041 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011041;
            delete from configuracoes.db_sysarqcamp where codarq = 1011041;
            delete from configuracoes.db_sysforkey where codcam = 1014875;
            delete from configuracoes.db_syscampodef where codcam in (1014874, 1014875, 1014876);
            delete from configuracoes.db_syscampodep where codcam in (1014874, 1014875, 1014876);
            delete from configuracoes.db_syscampo where codcam in (1014874, 1014875, 1014876);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011036 and codarq = 1011041;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011041;
            delete from configuracoes.db_sysarquivo where codarq = 1011041;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoObservacao() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessoobservacao_rh277_sequencial
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhpessoalprocessoobservacao(
            rh277_sequencial		int4 NOT NULL default nextval('rhpessoalprocessoobservacao_rh277_sequencial'),
            rh277_sequencialprocessovinculo		int4 NOT NULL default 0,
            rh277_observacao		varchar(255)  default '',
            CONSTRAINT rhpessoalprocessoobservacao_sequ_pk PRIMARY KEY (rh277_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhpessoalprocessoobservacao
            ADD CONSTRAINT rhpessoalprocessoobservacao_sequencialprocessovinculo_fk FOREIGN KEY (rh277_sequencialprocessovinculo)
            REFERENCES recursoshumanos.rhpessoalprocessovinculo;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoObservacao() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessoobservacao;
            DROP SEQUENCE recursoshumanos.rhpessoalprocessoobservacao_rh277_sequencial ;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }


    private function upDicionarioTabelaRhpessoalProcessoEstatutario() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011042, 'rhpessoalprocessoestatutario', 'Grupo de informações da sucessão de vínculo trabalhista/estatutário.', 'rh278', '2023-03-21', 'Sucessão de vínculo', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011042);
            insert into configuracoes.db_sysarqarq values (1011036,1011042);
            insert into configuracoes.db_syscampo values(1014877,'rh278_sequencial','int4','Código único sequencial da tabela.','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014878,'rh278_sequencialprocessovinculo','int4','Código que vincula a tabela rhpessoalprocessovinculo (FK).','0', 'Processo vínculo',10,'f','f','f',1,'text','Processo vínculo');
            insert into configuracoes.db_syscampo values(1014879,'rh278_tplnsc','int4','Código correspondente ao tipo de inscrição','0', 'Tipo de inscrição',1,'t','f','f',1,'text','Tipo de inscrição');
            insert into configuracoes.db_syscampo values(1014880,'rh278_nrlnsc','varchar(14)','Informar o número de inscrição do empregador anterior.','', 'Inscrição do empregador',14,'t','f','f',0,'text','Inscrição do empregador');
            insert into configuracoes.db_syscampo values(1014881,'rh278_matricant','varchar(30)','Matrícula do trabalhador no empregador anterior.','', 'Matrícula Anterior',30,'t','f','f',0,'text','Matrícula Anterior');
            insert into configuracoes.db_syscampo values(1014882,'rh278_dttransf','date','Data da transferência do empregado para o empregador declarante.','null', 'Data da transferência',10,'t','f','f',1,'text','Data da transferência');
            insert into configuracoes.db_syscampo values(1015361,'rh278_dtterm','date','Data do término de TSVE.','null', 'Data do término',10,'t','f','f',1,'text','Data do término');
            insert into configuracoes.db_syscampo values(1015362,'rh278_mtvdesligtsv','varchar(2)','Motivo do término do diretor não empregado, com FGTS. Valores válidos: 01 - Exoneração do diretor não empregado sem justa causa, por deliberação da assembleia, dos sócios cotistas ou da autoridade competente 02 - Término de mandato do diretor não empregado que não tenha sido reconduzido ao cargo 03 - Exoneração a pedido de diretor não empregado 04 - Exoneração do diretor não empregado por culpa recíproca ou força maior 05 - Morte do diretor não empregado 06 - Exoneração do diretor não empregado por falência, encerramento ou supressão de parte da empresa 99 - Outros','', 'Código motivo',2,'t','t','f',0,'text','Código motivo');
            insert into configuracoes.db_sysarqcamp values(1011042,1014877,1,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014878,2,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014879,3,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014880,4,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014881,5,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1014882,6,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1015362,7,0);
            insert into configuracoes.db_sysarqcamp values(1011042,1015361,8,0);
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011042,1014877,1,1014877);
            insert into configuracoes.db_sysforkey values(1011042,1014878,1,1011036,0);
            insert into configuracoes.db_syssequencia values(1001158, 'rhpessoalprocessoestatutario_rh278_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001158 where codarq = 1011042 and codcam = 1014877;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoEstatutario() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011042 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011042;
            delete from configuracoes.db_sysarqcamp where codarq = 1011042;
            delete from configuracoes.db_sysforkey where codcam = 1014878;
            delete from configuracoes.db_syscampodef where codcam in (1014877, 1014878, 1014879, 1014880, 1014881, 1014882, 1015361, 1015362);
            delete from configuracoes.db_syscampodep where codcam in (1014877, 1014878, 1014879, 1014880, 1014881, 1014882, 1015361, 1015362);
            delete from configuracoes.db_syscampo where codcam in (1014877, 1014878, 1014879, 1014880, 1014881, 1014882, 1015361, 1015362);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011036 and codarq = 1011042;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011042;
            delete from configuracoes.db_sysarquivo where codarq = 1011042;
            delete from configuracoes.db_syssequencia where nomesequencia = 'rhpessoalprocessoestatutario_rh278_sequencial_seq';
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoEstatutario() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessoestatutario_rh278_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhpessoalprocessoestatutario(
            rh278_sequencial		int4 NOT NULL default nextval('rhpessoalprocessoestatutario_rh278_sequencial_seq'),
            rh278_sequencialprocessovinculo		int4 NOT NULL default 0,
            rh278_tplnsc		int4  default 0,
            rh278_nrlnsc		varchar(14) default '',
            rh278_matricant		varchar(30) default '',
            rh278_dttransf		date default null,
            rh278_dtterm		date default null,
            rh278_mtvdesligtsv	varchar(2) default '',
            CONSTRAINT rhpessoalprocessoestatutario_sequ_pk PRIMARY KEY (rh278_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE rhpessoalprocessoestatutario
            ADD CONSTRAINT rhpessoalprocessoestatutario_sequencialprocessovinculo_fk FOREIGN KEY (rh278_sequencialprocessovinculo)
            REFERENCES rhpessoalprocessovinculo;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoEstatutario() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessoestatutario;
            DROP SEQUENCE recursoshumanos.rhpessoalprocessoestatutario_rh278_sequencial_seq;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoDesligamento() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011046, 'rhpessoalprocessodesligamento', 'Informações do desligamento.', 'rh279', '2023-03-21', 'Informações do desligamento.', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011046);
            insert into configuracoes.db_sysarqarq values (1011036,1011046);
            insert into configuracoes.db_syscampo values(1014883,'rh279_sequencial','int4','Código único sequencial da tabela.','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014884,'rh279_sequencialprocessovinculo','int4','Código que vincula a tabela rhpessoalprocessovinculo (FK).','0', 'Processo vínculo',10,'f','f','f',1,'text','Processo vínculo');
            insert into configuracoes.db_syscampo values(1014885,'rh279_dtdeslig','date','Data de desligamento do vínculo (último dia trabalhado).','null', 'Data de desligamento',10,'t','f','f',1,'text','Data de desligamento');
            insert into configuracoes.db_syscampo values(1014886,'rh279_mtvdeslig','varchar(2)','Código de motivo do desligamento.','', 'Motivo do desligamento',2,'t','t','f',0,'text','Motivo do desligamento');
            insert into configuracoes.db_syscampo values(1014887,'rh279_dtprojfimapi','date','Data projetada para o término do aviso prévio indenizado.','null', 'Término do aviso',10,'t','f','f',1,'text','Término do aviso');
            insert into configuracoes.db_syscampo values(1015340,'rh279_pensalim','int4','Indicativo de pensão alimentícia para fins de retenção de FGTS. 0 - Não existe pensão alimentícia 1 - Percentual de pensão alimentícia 2 - Valor de pensão alimentícia 3 - Percentual e valor de pensão alimentícia','0', 'Indicativo de pensão',1,'t','f','f',1,'text','Indicativo de pensão');
            insert into configuracoes.db_syscampo values(1015341,'rh279_percaliment','float4','Percentual a ser destinado a pensão alimentícia.','0', 'Percentual pensão',10,'t','f','f',4,'text','Percentual pensão');
            insert into configuracoes.db_syscampo values(1015342,'rh279_vlralim','float4','Valor da pensão alimentícia.','0', 'Valor da pensão',10,'t','f','f',4,'text','Valor da pensão');
            delete from configuracoes.db_sysarqcamp where codarq = 1011046;
            insert into configuracoes.db_sysarqcamp values(1011046,1014883,1,0);
            insert into configuracoes.db_sysarqcamp values(1011046,1014884,2,0);
            insert into configuracoes.db_sysarqcamp values(1011046,1014885,3,0);
            insert into configuracoes.db_sysarqcamp values(1011046,1014886,4,0);
            insert into configuracoes.db_sysarqcamp values(1011046,1014887,5,0);
            insert into configuracoes.db_sysarqcamp values(1011046,1015340,6,0);
            insert into configuracoes.db_sysarqcamp values(1011046,1015341,7,0);
            insert into configuracoes.db_sysarqcamp values(1011046,1015342,8,0);
            delete from configuracoes.db_sysprikey where codarq = 1011046;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011046,1014883,1,1014883);
            delete from configuracoes.db_sysforkey where codarq = 1011046 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011046,1014884,1,1011036,0);
            insert into configuracoes.db_syssequencia values(1001159, 'rhpessoalprocessodesligamento_rh279_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001159 where codarq = 1011046 and codcam = 1014883;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoDesligamento() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011046 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011046;
            delete from configuracoes.db_sysarqcamp where codarq = 1011046;
            delete from configuracoes.db_sysforkey where codcam = 1014884;
            delete from configuracoes.db_syscampodef where codcam in (1014883, 1014884, 1014885, 1014886, 1014887, 1015340, 1015341, 1015342);
            delete from configuracoes.db_syscampodep where codcam in (1014883, 1014884, 1014885, 1014886, 1014887, 1015340, 1015341, 1015342);
            delete from configuracoes.db_syscampo where codcam in (1014883, 1014884, 1014885, 1014886, 1014887, 1015340, 1015341, 1015342);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011036 and codarq = 1011046;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011046;
            delete from configuracoes.db_sysarquivo where codarq = 1011046;
            delete from configuracoes.db_syssequencia where nomesequencia = 'rhpessoalprocessodesligamento_rh279_sequencial_seq';
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoDesligamento() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessodesligamento_rh279_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            -- Criando  sequences
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhpessoalprocessodesligamento(
            rh279_sequencial		int4 NOT NULL default nextval('rhpessoalprocessodesligamento_rh279_sequencial_seq'),
            rh279_sequencialprocessovinculo		int4 NOT NULL default 0,
            rh279_dtdeslig		date  default null,
            rh279_mtvdeslig		varchar(2)   default '',
            rh279_dtprojfimapi		date default null,
            rh279_pensalim		int4  default 0,
            rh279_percaliment		float4  default 0,
            rh279_vlralim		float4 default 0,
            CONSTRAINT rhpessoalprocessodesligamento_sequ_pk PRIMARY KEY (rh279_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhpessoalprocessodesligamento
            ADD CONSTRAINT rhpessoalprocessodesligamento_sequencialprocessovinculo_fk FOREIGN KEY (rh279_sequencialprocessovinculo)
            REFERENCES recursoshumanos.rhpessoalprocessovinculo;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoDesligamento() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessodesligamento;
            DROP SEQUENCE IF EXISTS recursoshumanos.rhpessoalprocessodesligamento_rh279_sequencial_seq;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoMudanca() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011047, 'rhpessoalprocessomudanca', 'Informação do novo código de categoria e/ou da nova natureza da atividade, no caso de reconhecimento judicial nesse sentido.', 'rh280', '2023-03-22', 'Informação do novo código de categoria ', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011047);
            insert into configuracoes.db_sysarqarq values (1011034,1011047);
            insert into configuracoes.db_syscampo values(1014888,'rh280_sequencial','int4','Código único sequencial da tabela.','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014889,'rh280_sequencialprocessocontrato','int4','Código que vincula a tabela rhpessoalprocessocontrato (FK).','0', 'Processo contrato',10,'f','f','f',1,'text','Processo contrato');
            insert into configuracoes.db_syscampo values(1014890,'rh280_codcateg','int4','Código da categoria do trabalhador.','0', 'Código da categoria',3,'t','f','f',1,'text','Código da categoria');
            insert into configuracoes.db_syscampo values(1014891,'rh280_natividade','int4','Natureza da atividade.','0', 'Natureza da atividade',1,'t','f','f',1,'text','Natureza da atividade');
            insert into configuracoes.db_syscampo values(1014892,'rh280_dtmudcategativ ','date','Data a partir da qual foi reconhecida a nova categoria e/ou a nova natureza da atividade.','null', 'Data mudança',10,'t','f','f',1,'text','Data mudança');
            delete from configuracoes.db_sysarqcamp where codarq = 1011047;
            insert into configuracoes.db_sysarqcamp values(1011047,1014888,1,0);
            insert into configuracoes.db_sysarqcamp values(1011047,1014889,2,0);
            insert into configuracoes.db_sysarqcamp values(1011047,1014890,3,0);
            insert into configuracoes.db_sysarqcamp values(1011047,1014891,4,0);
            insert into configuracoes.db_sysarqcamp values(1011047,1014892,5,0);
            delete from configuracoes.db_sysprikey where codarq = 1011047;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011047,1014888,1,1014888);
            delete from configuracoes.db_sysforkey where codarq = 1011047 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011047,1014889,1,1011034,0);

            insert into configuracoes.db_syssequencia values(1001122, 'rhpessoalprocessomudanca_rh280_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001122 where codarq = 1011047 and codcam = 1014888;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoMudanca() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011047 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011047;
            delete from configuracoes.db_sysarqcamp where codarq = 1011047;
            delete from configuracoes.db_sysforkey where codcam = 1014889;
            delete from configuracoes.db_syscampodef where codcam in (1014888, 1014889, 1014890, 1014891, 1014892);
            delete from configuracoes.db_syscampodep where codcam in (1014888, 1014889, 1014890, 1014891, 1014892);
            delete from configuracoes.db_syscampo where codcam in (1014888, 1014889, 1014890, 1014891, 1014892);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011034 and codarq = 1011047;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011047;
            delete from configuracoes.db_sysarquivo where codarq = 1011047;

            delete from configuracoes.db_syssequencia where codsequencia = 1001122;
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011047 and codcam = 1014888;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoMudanca() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessomudanca_rh280_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhpessoalprocessomudanca(
            rh280_sequencial		int4 NOT NULL default nextval('rhpessoalprocessomudanca_rh280_sequencial_seq'),
            rh280_sequencialprocessocontrato		int4 NOT NULL default 0,
            rh280_codcateg		int4  default 0,
            rh280_natividade		int4  default 0,
            rh280_dtmudcategativ		date default null,
            CONSTRAINT rhpessoalprocessomudanca_sequ_pk PRIMARY KEY (rh280_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhpessoalprocessomudanca
            ADD CONSTRAINT rhpessoalprocessomudanca_sequencialprocessocontrato_fk FOREIGN KEY (rh280_sequencialprocessocontrato)
            REFERENCES recursoshumanos.rhpessoalprocessocontrato;


SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoMudanca() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessomudanca;
            DROP SEQUENCE recursoshumanos.rhpessoalprocessomudanca_rh280_sequencial_seq;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoUnicidade() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011048, 'rhpessoalprocessounicidade', 'Informações dos vínculos/contratos incorporados, no caso de reconhecimento de unicidade contratual.', 'rh281', '2023-03-22', 'unicidade contratual', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011048);
            insert into configuracoes.db_sysarqarq values (1011034,1011048);
            insert into configuracoes.db_syscampo values(1014893,'rh281_sequencial','int4','Código único sequencial da tabela.','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014894,'rh281_sequencialprocessocontrato','int4','Código que vincula a tabela rhpessoalprocessocontrato (FK).','0', 'Processo Contrato',10,'f','f','f',1,'text','Processo Contrato');
            insert into configuracoes.db_syscampo values(1014895,'rh281_matunic','varchar(30)','Informar a matrícula incorporada (matrícula cujo vínculo/contrato passou a integrar período de unicidade contratual reconhecido judicialmente).','', 'Matricula',30,'f','t','f',0,'text','Matricula');
            insert into configuracoes.db_syscampo values(1014896,'rh281_codcateg','int4','Código da categoria do trabalhador (código de categoria cujo contrato passou a integrar período de unicidade contratual reconhecido judicialmente).','0', 'Código da categoria',3,'f','f','f',1,'text','Código da categoria');
            insert into configuracoes.db_syscampo values(1014897,'rh281_dtinicio','date','Data de início de TSVE (data de início cujo contrato passou a integrar período de unicidade contratual reconhecido judicialmente).','null', 'Data de início',10,'t','f','f',1,'text','Data de início');
            delete from configuracoes.db_sysarqcamp where codarq = 1011048;
            insert into configuracoes.db_sysarqcamp values(1011048,1014893,1,0);
            insert into configuracoes.db_sysarqcamp values(1011048,1014894,2,0);
            insert into configuracoes.db_sysarqcamp values(1011048,1014895,3,0);
            insert into configuracoes.db_sysarqcamp values(1011048,1014896,4,0);
            insert into configuracoes.db_sysarqcamp values(1011048,1014897,5,0);
            delete from configuracoes.db_sysprikey where codarq = 1011048;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011048,1014893,1,1014893);
            delete from configuracoes.db_sysforkey where codarq = 1011048 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011048,1014894,1,1011034,0);

            insert into configuracoes.db_syssequencia values(1001123, 'rhpessoalprocessounicidade_rh281_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001123 where codarq = 1011048 and codcam = 1014893;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoUnicidade() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011048 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011048;
            delete from configuracoes.db_sysarqcamp where codarq = 1011048;
            delete from configuracoes.db_sysforkey where codcam = 1014894;
            delete from configuracoes.db_syscampodef where codcam in (1014893, 1014894, 1014895, 1014896, 1014897);
            delete from configuracoes.db_syscampodep where codcam in (1014893, 1014894, 1014895, 1014896, 1014897);
            delete from configuracoes.db_syscampo where codcam in (1014893, 1014894, 1014895, 1014896, 1014897);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011034 and codarq = 1011048;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011048;
            delete from configuracoes.db_sysarquivo where codarq = 1011048;

            delete from configuracoes.db_syssequencia where codsequencia = 1001123;
            update configuracoes.db_sysarqcamp set codsequencia = 0 where codarq = 1011048 and codcam = 1014893;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoUnicidade() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessounicidade_rh281_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhpessoalprocessounicidade(
            rh281_sequencial		int4 NOT NULL default nextval('rhpessoalprocessounicidade_rh281_sequencial_seq'),
            rh281_sequencialprocessocontrato		int4 NOT NULL default 0,
            rh281_matunic		varchar(30) default '',
            rh281_codcateg		int4 default 0,
            rh281_dtinicio		date default null,
            CONSTRAINT rhpessoalprocessounicidade_sequ_pk PRIMARY KEY (rh281_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhpessoalprocessounicidade
            ADD CONSTRAINT rhpessoalprocessounicidade_sequencialprocessocontrato_fk FOREIGN KEY (rh281_sequencialprocessocontrato)
            REFERENCES recursoshumanos.rhpessoalprocessocontrato;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoUnicidade() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessounicidade;
            DROP SEQUENCE recursoshumanos.rhpessoalprocessounicidade_rh281_sequencial_seq;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }


    private function upDicionarioTabelaRhpessoalProcessoPeriodo() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011050, 'rhpessoalprocessoperiodo', 'Identificação do período ao qual se referem as bases de cálculo.', 'rh282', '2023-03-22', 'Identificação do período', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011050);
            insert into configuracoes.db_sysarqarq values (1011034,1011050);
            insert into configuracoes.db_syscampo values(1014898,'rh282_sequencial','int4','Código único sequencial da tabela.','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1014899,'rh282_sequencialprocessocontrato','int4','Código que vincula a tabela rhpessoalprocessocontrato (FK).','0', 'Processo contrato',10,'f','f','f',1,'text','Processo contrato');
            insert into configuracoes.db_syscampo values(1014900,'rh282_perref ','varchar(7)','Informar o mês/ano (formato AAAA-MM) de referência das informações.','', 'Competência',7,'t','t','f',0,'text','Competência');
            insert into configuracoes.db_syscampo values(1014901,'rh282_vrbccpmensal ','float4','Valor da base de cálculo da contribuição previdenciária sobre a remuneração mensal do trabalhador.','0', 'Valor da base',14,'t','f','f',4,'text','Valor da base');
            insert into configuracoes.db_syscampo values(1014902,'rh282_vrbccp13 ','float4','Valor da base de cálculo da contribuição previdenciária sobre a remuneração do trabalhador referente ao 13º salário.','0', 'Valor da base de cálculo 13º',14,'t','f','f',4,'text','Valor da base de cálculo 13º');
            insert into configuracoes.db_syscampo values(1014905,'rh282_grauexp ','int4','Preencher com o código que representa o grau de exposição a agentes nocivos','0', 'Grau Exposição',1,'t','f','f',1,'text','Grau Exposição');
            insert into configuracoes.db_syscampo values(1014909,'rh282_codcateg','int4','Código da categoria do trabalhador declarado no período de referência.','0', 'Código da categoria',3,'t','f','f',1,'text','Código da categoria');
            insert into configuracoes.db_syscampo values(1014910,'rh282_vrbccprev','float4','Valor da remuneração do trabalhador a ser considerada para fins previdenciários declarada em GFIP ou em S-1200 de trabalhador sem cadastro no S-2300.','0', 'Remuneração do trabalhador',14,'t','f','f',4,'text','Remuneração do trabalhador');
            insert into configuracoes.db_syscampo values(1015366,'rh282_vrbcfgtsproctrab','float4','Valor da base de cálculo de FGTS ainda não declarada em SEFIP ou no eSocial, inclusive de verba reconhecida no processo trabalhista.','0', 'Valor da base FGTS sem SEFIP',10,'t','f','f',4,'text','Valor da base FGTS sem SEFIP');
            insert into configuracoes.db_syscampo values(1015367,'rh282_vrbcfgtssefip','float4','Valor da base de cálculo de FGTS declarada apenas em SEFIP (não informada no eSocial) e ainda não recolhida.','0', 'Valor da base FGTS com SEFIP',10,'t','f','f',4,'text','Valor da base FGTS com SEFIP');
            insert into configuracoes.db_syscampo values(1015368,'rh282_vrbcfgtsdecant','float4','Valor da base de cálculo de FGTS declarada anteriormente no eSocial e ainda não recolhida.','0', 'Valor da base FGTS não recolhida',10,'t','f','f',4,'text','Valor da base FGTS não recolhida');
            delete from configuracoes.db_sysarqcamp where codarq = 1011050;
            insert into configuracoes.db_sysarqcamp values(1011050,1014898,1,1001124);
            insert into configuracoes.db_sysarqcamp values(1011050,1014899,2,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014900,3,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014902,4,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014901,5,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014905,8,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014909,12,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1014910,13,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1015366,14,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1015367,15,0);
            insert into configuracoes.db_sysarqcamp values(1011050,1015368,16,0);
            delete from configuracoes.db_sysprikey where codarq = 1011050;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011050,1014898,1,1014898);
            delete from configuracoes.db_sysforkey where codarq = 1011050 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011050,1014899,1,1011034,0);

            insert into configuracoes.db_syssequencia values(1001124, 'rhpessoalprocessoperiodo_rh282_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001124 where codarq = 1011050 and codcam = 1014898;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoPeriodo() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011050 and referen = 0;
            delete from configuracoes.db_sysprikey where codarq = 1011050;
            delete from configuracoes.db_sysarqcamp where codarq = 1011050;
            delete from configuracoes.db_sysforkey where codcam = 1014899;
            delete from configuracoes.db_syscampodef where codcam in (1014898, 1014899, 1014900, 1014901, 1014902, 1014905, 1014909, 1014910, 1015366, 1015367, 1015368);
            delete from configuracoes.db_syscampodep where codcam in (1014898, 1014899, 1014900, 1014901, 1014902, 1014905, 1014909, 1014910, 1015366, 1015367, 1015368);
            delete from configuracoes.db_syscampo where codcam in (1014898, 1014899, 1014900, 1014901, 1014902, 1014905, 1014909, 1014910, 1015366, 1015367, 1015368);
            delete from configuracoes.db_sysarqarq where codarqpai = 1011034 and codarq = 1011050;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011050;
            delete from configuracoes.db_sysarquivo where codarq = 1011050;

            delete from configuracoes.db_syssequencia where codsequencia = 1001124;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoPeriodo() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessoperiodo_rh282_sequencial_seq
              INCREMENT 1
              MINVALUE 1
              MAXVALUE 9223372036854775807
              START 1
              CACHE 1;
            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhpessoalprocessoperiodo(
            rh282_sequencial		int4 NOT NULL default nextval('rhpessoalprocessoperiodo_rh282_sequencial_seq'),
            rh282_sequencialprocessocontrato		int4 NOT NULL default 0,
            rh282_perref		varchar(7)   default '',
            rh282_vrbccp13		float4  default 0,
            rh282_vrbccpmensal		float4  default 0,
            rh282_grauexp		int4  default 0,
            rh282_codcateg		int4  default 0,
            rh282_vrbccprev		float4 default 0,
            rh282_vrbcfgtsproctrab 		float4  default 0,
            rh282_vrbcfgtssefip		float4  default 0,
            rh282_vrbcfgtsdecant		float4  default 0,
            CONSTRAINT rhpessoalprocessoperiodo_sequ_pk PRIMARY KEY (rh282_sequencial));
            -- CHAVE ESTRANGEIRA
            ALTER TABLE recursoshumanos.rhpessoalprocessoperiodo
            ADD CONSTRAINT rhpessoalprocessoperiodo_sequencialprocessocontrato_fk FOREIGN KEY (rh282_sequencialprocessocontrato)
            REFERENCES recursoshumanos.rhpessoalprocessocontrato;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstruturaTabelaRhpessoalProcessoPeriodo() {
        $sql  = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessoperiodo;
            DROP SEQUENCE IF EXISTS recursoshumanos.rhpessoalprocessoperiodo_rh282_sequencial_seq;
SQL;
      
        DB::connection()->getPdo()->exec($sql);
    }

    private function upTipoEsocial() {
        $sql  = <<<SQL
            insert into esocialformulariotipo values(49, 'S-2500 - Processo Trabalhista');
SQL;

        DB::connection()->getPdo()->exec($sql);
    }

    private function downTipoEsocial() {
        $sql  = <<<SQL
            DELETE FROM esocialformulariotipo where rh209_sequencial = 49;
SQL;
    
        DB::connection()->getPdo()->exec($sql);
    }

    private function upDicionarioTabelaRhpessoalProcessoAbono() {
        $sql  = <<<SQL
            insert into configuracoes.db_sysarquivo values (1011134, 'rhpessoalprocessoabono', 'Identificação do(s) ano(s)-base em que houve indenização substitutiva de abono salarial.', 'rh302', '2023-09-06', 'Ano Abono', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (29,1011134);
            insert into configuracoes.db_sysarqarq values (1011036,1011134);
            insert into configuracoes.db_syscampo values(1015373,'rh302_sequencial','int8','Sequencial único da tabela','0', 'Número Sequencial',10,'f','f','f',1,'text','Número Sequencial');
            insert into configuracoes.db_syscampo values(1015374,'rh302_sequencialprocessovinculo','int8','Sequencial que referencia a tabela RHPESSOALPROCESSOVINCULO','0', 'Sequencial vinculo',10,'f','f','f',1,'text','Sequencial vinculo');
            insert into configuracoes.db_syscampo values(1015375,'rh302_anobase','varchar(4)','Ano-base em que houve indenização substitutiva do abono salarial.','', 'Ano abono',4,'f','t','f',0,'text','Ano abono');
            delete from configuracoes.db_sysarqcamp where codarq = 1011134;
            insert into configuracoes.db_sysarqcamp values(1011134,1015373,1,0);
            insert into configuracoes.db_sysarqcamp values(1011134,1015374,2,0);
            insert into configuracoes.db_sysarqcamp values(1011134,1015375,3,0);
            delete from configuracoes.db_sysprikey where codarq = 1011134;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1011134,1015373,1,1015373);
            delete from configuracoes.db_sysarqcamp where codarq = 1011134;
            insert into configuracoes.db_sysarqcamp values(1011134,1015373,1,0);
            insert into configuracoes.db_sysarqcamp values(1011134,1015374,2,0);
            insert into configuracoes.db_sysarqcamp values(1011134,1015375,3,0);
            insert into configuracoes.db_syssequencia values(1001162, 'rhpessoalprocessoabono_rh302_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001162 where codarq = 1011134 and codcam = 1015373;
            delete from configuracoes.db_sysforkey where codarq = 1011134 and referen = 0;
            insert into configuracoes.db_sysforkey values(1011134,1015374,1,1011036,0);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionarioTabelaRhpessoalProcessoAbono() {
        $sql  = <<<SQL
            delete from configuracoes.db_sysforkey where codarq = 1011134;
            delete from configuracoes.db_syssequencia where codsequencia = 1001162;
            delete from configuracoes.db_sysprikey where codarq = 1011134;
            delete from configuracoes.db_sysarqcamp where codarq = 1011134;
            delete from configuracoes.db_sysarqarq where codarqpai = 1011036 and codarq = 1011134;
            delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1011134;
            delete from configuracoes.db_sysarquivo where codarq = 1011134;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstruturaTabelaRhpessoalProcessoAbono() {
        $sql  = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE recursoshumanos.rhpessoalprocessoabono_rh302_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            -- TABELAS E ESTRUTURA
            -- Módulo: recursoshumanos
            CREATE TABLE recursoshumanos.rhpessoalprocessoabono(
            rh302_sequencial		int8 NOT NULL default 0,
            rh302_sequencialprocessovinculo		int8 NOT NULL default 0,
            rh302_anobase		varchar(4)  default '',
            CONSTRAINT rhpessoalprocessoabono_sequ_pk PRIMARY KEY (rh302_sequencial));
            -- CHAVE ESTRANGEIRA

            ALTER TABLE recursoshumanos.rhpessoalprocessoabono
            ADD CONSTRAINT rhpessoalprocessoabono_sequencialprocessovinculo_fk FOREIGN KEY (rh302_sequencialprocessovinculo)
            REFERENCES rhpessoalprocessovinculo;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }


    private function downEstruturaTabelaRhpessoalProcessoAbono() {
        $sql  = <<<SQL
        DROP TABLE IF EXISTS recursoshumanos.rhpessoalprocessoabono;
        DROP SEQUENCE IF EXISTS recursoshumanos.rhpessoalprocessoabono_rh302_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEventoEsocial() {
        $sql  = <<<SQL
            SELECT setval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq', (select max(rh211_sequencial) + 1 from recursoshumanos.esocialversaoformulario), false);
            insert into habitacao.avaliacao values (4000117, 5, 'S-2500 - Processo Trabalhista', 'S-2500 - Processo Trabalhista', true, 's2500_processo_trabalhista', null, false);
            insert into recursoshumanos.esocialversaoformulario values (nextval('recursoshumanos.esocialversaoformulario_rh211_sequencial_seq'), 'S1.1', 4000117, 49);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downEventoEsocial()
    {
        $sql = <<<SQL
            delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 49;
            delete from habitacao.avaliacao where db101_sequencial = 4000117;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}

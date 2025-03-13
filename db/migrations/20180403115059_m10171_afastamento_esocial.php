<?php

use Classes\PostgresMigration;

class M10171AfastamentoEsocial extends PostgresMigration
{
    public function up(){   
        $this->addDicionario();
        $this->addTabelas();
        $this->inserirMotivosAfastamento();
        $this->addMenus();
    }

    public function down(){   
        $this->removerDicionario();
        $this->removerMotivosAfastamento();
        $this->removerTabelas();
        $this->removerMenus();
    }

    private function addDicionario(){
        // Tabela afastamentosesocial
        $sql  = " insert into db_sysarquivo values (1010268, 'afastamentosesocial', 'Configuração de afastamentos que são considerados para o eSocial.', 'rh215', '2018-04-03', 'Afastamentos do eSocial', 0, 'f', 'f', 'f', 'f' ); ";
        $sql .= " insert into db_sysarqmod values (29,1010268); ";
        $sql .= " insert into db_syscampo values(1009674,'rh215_sequencial','int4','Código sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código'); ";
        $sql .= " insert into db_syscampo values(1009675,'rh215_tipoasse','int4','Vínculo com o tipo de assentamento.','0', 'Tipo de Assentamento',10,'f','f','f',1,'text','Tipo de Assentamento'); ";
        $sql .= " insert into db_sysarqcamp values(1010268,1009674,1,0); ";
        $sql .= " insert into db_sysarqcamp values(1010268,1009675,2,0); ";
        $sql .= " insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010268,1009674,1,1009675); ";
        $sql .= " insert into db_sysforkey values(1010268,1009675,1,596,0); ";
        $sql .= " insert into db_sysindices values(1008263,'afastamentosesocial_eso08_tipoasse_in',1010268,'0'); ";
        $sql .= " insert into db_syscadind values(1008263,1009675,1); ";
        $sql .= " insert into db_syssequencia values(1000723, 'afastamentosesocial_eso08_sequencial_seq', 1, 1, 9223372036854775807, 1, 1); ";
        $sql .= " update db_sysarqcamp set codsequencia = 1000723 where codarq = 1010268 and codcam = 1009674; ";

        // Tabela motivoafastamentoesocial
        $sql .= " insert into db_sysarquivo values (1010269, 'motivoafastamentoesocial', 'Motivos de afastamento do eSocial.', 'rh216', '2018-04-03', 'Motivo de Afastamento', 0, 'f', 'f', 'f', 'f' ); ";
        $sql .= " insert into db_sysarqmod values (29,1010269); ";
        $sql .= " insert into db_syscampo values(1009676,'rh216_sequencial','int4','Código sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código'); ";
        $sql .= " insert into db_syscampo values(1009677,'rh216_descricao','text','Descrição do motivo de afastamento.','', 'Descrição',1,'f','t','f',0,'text','Descrição'); ";
        $sql .= " insert into db_sysarqcamp values(1010269,1009676,1,0); ";
        $sql .= " insert into db_sysarqcamp values(1010269,1009677,2,0); ";
        $sql .= " insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010269,1009676,1,1009676); ";

        // Tabela retificacaoafastamento
        $sql .= " insert into db_sysarquivo values (1010273, 'retificacaoafastamento', 'Retificação de afastamento para o eSocial.', 'rh220', '2018-04-03', 'Retificação de Afastamento', 0, 'f', 'f', 'f', 'f' ); ";
        $sql .= " insert into db_sysarqmod values (29,1010273); ";
        $sql .= " insert into db_syscampo values(1009687,'rh220_sequencial','int4','Código sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código'); ";
        $sql .= " insert into db_syscampo values(1009688,'rh220_novoafastamento','int4','Vínculo com o novo assentamento.','0', 'Novo Afastamento',10,'f','f','f',1,'text','Novo Afastamento'); ";
        $sql .= " insert into db_syscampo values(1009690,'rh220_origemretificacao','int4','Origem da retificação do afastamento.','0', 'Origem de retificação',10,'f','f','f',1,'text','Origem de retificação'); ";
        $sql .= " insert into db_syscampo values(1009691,'rh220_tipoprocesso','int4','Tipo de processo.','0', 'Tipo de processo',10,'t','f','f',1,'text','Tipo de processo'); ";
        $sql .= " insert into db_syscampo values(1009692,'rh220_numeroprocesso','text','Número do Processo.','', 'Número do Processo',1,'t','t','f',0,'text','Número do Processo'); ";
        $sql .= " insert into db_sysarqcamp values(1010273,1009687,1,1000727); ";
        $sql .= " insert into db_sysarqcamp values(1010273,1009688,2,0); ";
        $sql .= " insert into db_sysarqcamp values(1010273,1009690,3,0); ";
        $sql .= " insert into db_sysarqcamp values(1010273,1009691,4,0); ";
        $sql .= " insert into db_sysarqcamp values(1010273,1009692,5,0); ";
        $sql .= " insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010273,1009687,1,1009687); ";
        $sql .= " insert into db_sysforkey values(1010273,1009688,1,528,0); ";
        $sql .= " insert into db_sysindices values(1008268,'retificacaoafastamento_rh220_assenta_in',1010273,'0'); ";
        $sql .= " insert into db_syscadind values(1008268,1009688,1); ";
        $sql .= " insert into db_syssequencia values(1000727, 'retificacaoafastamento_rh220_sequencial_seq', 1, 1, 9223372036854775807, 1, 1); ";
        $sql .= " update db_sysarqcamp set codsequencia = 1000727 where codarq = 1010273 and codcam = 1009687; ";

        //Alterando para o schema esocial
        $sql .= " update db_sysarquivo set nomearq = 'afastamentosesocial', descricao = 'Configuração de afastamentos que são considerados para o eSocial.', sigla = 'eso08', dataincl = '2018-04-03', rotulo = 'Afastamentos do eSocial', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1010268; ";
        $sql .= " update db_syscampo set nomecam = 'eso08_sequencial', conteudo = 'int4', descricao = 'Código sequencial da tabela.', valorinicial = '0', rotulo = 'Código', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Código' where codcam = 1009674; ";
        $sql .= " update db_syscampo set nomecam = 'eso08_tipoasse', conteudo = 'int4', descricao = 'Vínculo com o tipo de assentamento.', valorinicial = '0', rotulo = 'Tipo de Assentamento', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Tipo de Assentamento' where codcam = 1009675; ";
        $sql .= " insert into db_syscampo values(1009700,'eso08_grupomotivoafastamentoesocial','int4','Vínculo com o grupo de afastamento do eSocial.','0', 'Grupo de motivo de afastamento',10,'f','f','f',1,'text','Grupo de motivo de afastamento'); ";
        $sql .= " insert into db_sysarqcamp values(1010268,1009700,3,0); ";
        $sql .= " insert into db_sysforkey values(1010268,1009700,1,1010274,0); ";
        $sql .= " insert into db_sysindices values(1008273,'afastamentosesocial_eso08_grupomotivoafastamentoesocial_in',1010268,'0'); ";

        $sql .= " update db_sysarquivo set nomearq = 'motivoafastamentoesocial', descricao = 'Motivos de afastamento do eSocial.', sigla = 'eso09', dataincl = '2018-04-03', rotulo = 'Motivo de Afastamento', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1010269; ";
        $sql .= " update db_syscampo set nomecam = 'eso09_sequencial', conteudo = 'int4', descricao = 'Código sequencial da tabela.', valorinicial = '0', rotulo = 'Código', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Código' where codcam = 1009676; ";
        $sql .= " update db_syscampo set nomecam = 'eso09_descricao', conteudo = 'text', descricao = 'Descrição do motivo de afastamento.', valorinicial = '', rotulo = 'Descrição', nulo = 'f', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Descrição' where codcam = 1009677; ";
        $sql .= " update db_sysarqmod set codmod = 81 where codarq in (1010268, 1010269); ";

        //Ajustando tabela retificacaoafastamento
        $sql .= " update db_syscampo set nomecam = 'rh220_assenta', conteudo = 'int4', descricao = 'Vínculo com o novo assentamento.', valorinicial = '0', rotulo = 'Afastamento', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Afastamento' where codcam = 1009688; ";
        $sql .= " update db_syscampo set nomecam = 'rh220_origemretificacao', conteudo = 'int4', descricao = 'Origem da retificação do afastamento.', valorinicial = '0', rotulo = 'Origem de retificação', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Origem de retificação' where codcam = 1009690; ";

        // Tabela grupomotivoafastamentoesocial
        $sql .= " insert into db_sysarquivo values (1010274, 'grupomotivoafastamentoesocial', 'Grupo de motivos de afastamentos considerados para o eSocial.', 'eso10', '2018-04-09', 'Grupo de motivos de afastamento', 0, 'f', 'f', 'f', 'f' ); ";
        $sql .= " insert into db_sysarqmod values (81,1010274); ";
        $sql .= " insert into db_syscampo values(1009693,'eso10_sequencial','int4','Código sequencial da tabela.','0', 'Código',10,'f','f','f',1,'text','Código'); ";
        $sql .= " insert into db_syscampo values(1009694,'eso10_descricao','varchar(255)','Descrição do grupo de motivos do eSocial.','', 'Descrição',255,'f','t','f',0,'text','Descrição'); ";
        $sql .= " insert into db_syscampo values(1009695,'eso10_db_cadattdinamico','int4','Vínculo com a tabela de grupo de atributos dinâmicos.','0', 'Grupo de atributos dinâmicos',10,'f','f','f',1,'text','Grupo de atributos dinâmicos'); ";
        $sql .= " insert into db_sysarqcamp values(1010274,1009693,1,0); ";
        $sql .= " insert into db_sysarqcamp values(1010274,1009694,2,0); ";
        $sql .= " insert into db_sysarqcamp values(1010274,1009695,3,0); ";
        $sql .= " insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010274,1009693,1,1009694); ";
        $sql .= " insert into db_sysforkey values(1010274,1009695,1,3162,0); ";
        $sql .= " insert into db_sysindices values(1008270,'grupomotivoafastamentoesocial_eso10_db_cadattdinamico_in',1010274,'0'); ";
        $sql .= " insert into db_syscadind values(1008270,1009695,1); ";
        $sql .= " insert into db_syssequencia values(1000728, 'grupomotivoafastamentoesocial_eso10_sequencial_seq', 1, 1, 9223372036854775807, 1, 1); ";
        $sql .= " update db_sysarqcamp set codsequencia = 1000728 where codarq = 1010274 and codcam = 1009693; ";
        
        // Tabela db_cadattdinamicoatributos - Adicionado campos
        $sql .= " insert into db_syscampo values(1009697,'db109_db_formulas','int4','Vínculo do atributo dinâmico com a fórmula.','0', 'Fórmula',10,'t','f','f',1,'text','Fórmula'); ";
        $sql .= " insert into db_syscampo values(1009698,'db109_fixo','bool','Define o atributo dinâmico como fixo, não podendo ser alterado/excluído pelo usuário.','false', 'Fixo',1,'f','f','f',5,'text','Fixo'); ";
        $sql .= " insert into db_sysarqcamp values(3163,1009697,10,0); ";
        $sql .= " insert into db_sysarqcamp values(3163,1009698,11,0); ";
        $sql .= " insert into db_sysforkey values(3163,1009697,1,3820,0); ";
        $sql .= " insert into db_sysindices values(1008271,'db_cadattdinamicoatributos_db109_db_formulas_in',3163,'0'); ";

        // Tabela motivoafastamentoesocial - Adicionado campos       
        $sql .= " insert into db_syscampo values(1009699,'eso09_grupomotivoafastamentoesocial','int4','Vínculo com o grupo de motivo de afastamento.','0', 'Grupo de motivo de afastamento',10,'f','f','f',1,'text','Grupo de motivo de afastamento'); ";
        $sql .= " insert into db_sysarqcamp values(1010269,1009699,3,0); ";
        $sql .= " insert into db_sysforkey values(1010269,1009699,1,1010274,0); ";
        $sql .= " insert into db_sysindices values(1008272,'motivoafastamentoesocial_eso09_grupomotivoafastamentoesocial_in',1010269,'0'); ";

        // Tabela mapeamentoatributosesocial
        $sql .= " insert into db_sysarquivo values (1010275, 'mapeamentoatributosesocial', 'Tabela para mapear os atributos do eSocial para outro grupo da db_cadattdinamico.', 'db39', '2018-04-13', 'mapeamentoatributosesocial', 0, 'f', 'f', 'f', 'f' ); ";
        $sql .= " insert into db_sysarqmod values (7,1010275); ";

        $sql .= " insert into db_syscampo values(1009701,'db39_campoorigem','int4','Sequencial de origem do atributo eSocial','0', 'db39_campoorigem',10,'f','f','f',1,'text','db39_campoorigem'); ";
        $sql .= " insert into db_syscampo values(1009702,'db39_camponovo','int4','Sequencial novo para os atributos do eSocial.','0', 'db39_camponovo',10,'f','f','f',1,'text','db39_camponovo'); ";

        $sql .= " insert into db_sysarqcamp values(1010275,1009702,1,0); ";
        $sql .= " insert into db_sysarqcamp values(1010275,1009701,2,0); ";
        $sql .= " insert into db_sysforkey values(1010275,1009702,1,3163,0); ";
        $sql .= " insert into db_sysforkey values(1010275,1009701,1,3163,0); ";

        $sql .= " insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010275,1009702,1,1009702); ";
        $sql .= " insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010275,1009701,2,1009702); ";

        $this->execute($sql);
    }

    private function removerDicionario(){

        $sql  = " delete from db_acount where codcam in (1009687,1009688,1009690,1009691,1009692, 1009693,1009694,1009695, 1009674,1009675, 1009700, 1009676,1009677, 1009699, 1009697,1009698); ";

        // Tabela grupomotivoafastamentoesocial
        $sql .= " delete from db_syscadind where codcam in (1009695); ";
        $sql .= " delete from db_sysindices where codarq = 1010274; ";
        $sql .= " delete from db_sysforkey where codarq = 1010274; ";
        $sql .= " delete from db_sysprikey where codarq = 1010274; ";
        $sql .= " delete from db_sysarqcamp where codarq = 1010274; ";
        $sql .= " delete from db_syssequencia where codsequencia = 1000728; ";
        $sql .= " delete from db_syscampo where codcam in (1009693,1009694,1009695); ";
        $sql .= " delete from db_sysarqmod where codarq = 1010274; ";
        $sql .= " delete from db_sysarquivo where codarq = 1010274; ";  

        // Tabela db_cadattdinamicoatributos
        $sql .= " delete from db_sysindices where codarq = 3163 AND codind = 1008271; ";
        $sql .= " delete from db_sysforkey where codarq = 3163 AND codcam = 1009697; ";
        $sql .= " delete from db_sysarqcamp where codarq = 3163 AND codcam in (1009697,1009698); ";
        $sql .= " delete from db_syscampo where codcam in (1009697,1009698); ";

        // Tabela retificacaoafastamento
        $sql .= " delete from db_syscadind where codcam in (1009688); ";
        $sql .= " delete from db_sysindices where codarq = 1010273; ";
        $sql .= " delete from db_sysforkey where codarq = 1010273; ";
        $sql .= " delete from db_sysprikey where codarq = 1010273; ";
        $sql .= " delete from db_sysarqcamp where codarq = 1010273; ";
        $sql .= " delete from db_syssequencia where codsequencia = 1000727; ";
        $sql .= " delete from db_syscampo where codcam in (1009687,1009688,1009690,1009691,1009692); ";
        $sql .= " delete from db_sysarqmod where codarq = 1010273; ";
        $sql .= " delete from db_sysarquivo where codarq = 1010273; ";

        // Tabela motivoafastamentoesocial
        $sql .= " delete from db_sysindices where codarq = 1010269; ";
        $sql .= " delete from db_sysforkey where codarq = 1010269; ";
        $sql .= " delete from db_sysprikey where codarq = 1010269; ";
        $sql .= " delete from db_sysarqcamp where codarq = 1010269; ";
        $sql .= " delete from db_syscampo where codcam in (1009676,1009677, 1009699); ";
        $sql .= " delete from db_sysarqmod where codarq = 1010269; ";
        $sql .= " delete from db_sysarquivo where codarq = 1010269; ";

        // Tabela afastamentosesocial
        $sql .= " delete from db_syscadind where codcam in (1009675); ";
        $sql .= " delete from db_sysindices where codarq = 1010268; ";
        $sql .= " delete from db_sysforkey where codarq = 1010268; ";
        $sql .= " delete from db_sysprikey where codarq = 1010268; ";
        $sql .= " delete from db_sysarqcamp where codarq = 1010268; ";
        $sql .= " delete from db_syssequencia where codsequencia = 1000723; ";
        $sql .= " delete from db_syscampo where codcam in (1009674,1009675, 1009700); ";
        $sql .= " delete from db_sysarqmod where codarq = 1010268; ";
        $sql .= " delete from db_sysarquivo where codarq = 1010268; ";

        // Tabela mapeamentoatributosesocial
        $sql .= " delete from db_sysprikey where codarq = 1010275; ";
        $sql .= " delete from db_sysforkey where  codarq = 1010275; ";
        $sql .= " delete from db_sysarqcamp where  codarq = 1010275; ";
        $sql .= " delete from db_syscampo where  codcam in (1009702, 1009701); ";
        $sql .= " delete from db_sysarqmod where  codarq  = 1010275; ";
        $sql .= " delete from db_sysarquivo where  codarq  = 1010275; ";


        $this->execute($sql);
    }

    private function addTabelas(){
        // Criando  sequences
        $sql  = " CREATE SEQUENCE esocial.afastamentosesocial_eso08_sequencial_seq ";
        $sql .= " INCREMENT 1 ";
        $sql .= " MINVALUE 1 ";
        $sql .= " MAXVALUE 9223372036854775807 ";
        $sql .= " START 1 ";
        $sql .= " CACHE 1; ";

        $sql .= " CREATE SEQUENCE recursoshumanos.retificacaoafastamento_rh220_sequencial_seq ";
        $sql .= " INCREMENT 1 ";
        $sql .= " MINVALUE 1 ";
        $sql .= " MAXVALUE 9223372036854775807 ";
        $sql .= " START 1 ";
        $sql .= " CACHE 1; ";

        $sql .= " CREATE SEQUENCE esocial.grupomotivoafastamentoesocial_eso10_sequencial_seq ";
        $sql .= " INCREMENT 1 ";
        $sql .= " MINVALUE 1 ";
        $sql .= " MAXVALUE 9223372036854775807 ";
        $sql .= " START 1 ";
        $sql .= " CACHE 1; ";

        // TABELAS E ESTRUTURA
        $sql .= " CREATE TABLE esocial.afastamentosesocial( ";
        $sql .= " eso08_sequencial        int4 NOT NULL default 0, ";
        $sql .= " eso08_tipoasse      int4 default 0, ";
        $sql .= " eso08_grupomotivoafastamentoesocial int4 NOT NULL, ";
        $sql .= " CONSTRAINT afastamentosesocial_sequ_pk PRIMARY KEY (eso08_sequencial)); ";

        $sql .= " CREATE TABLE esocial.motivoafastamentoesocial( ";
        $sql .= " eso09_sequencial                    int4 NOT NULL default 0, ";
        $sql .= " eso09_descricao                     text, ";
        $sql .= " eso09_grupomotivoafastamentoesocial int4 NOT NULL, ";
        $sql .= " CONSTRAINT motivoafastamentoesocial_sequ_pk PRIMARY KEY (eso09_sequencial)); ";

        $sql .= " CREATE TABLE recursoshumanos.retificacaoafastamento( ";
        $sql .= " rh220_sequencial        int4 NOT NULL default 0, ";
        $sql .= " rh220_assenta           int4 NOT NULL default 0, ";
        $sql .= " rh220_origemretificacao int4, ";
        $sql .= " rh220_tipoprocesso      int4, ";
        $sql .= " rh220_numeroprocesso    text, ";
        $sql .= " CONSTRAINT retificacaoafastamento_sequ_pk PRIMARY KEY (rh220_sequencial)); ";

         $sql .= " CREATE TABLE esocial.grupomotivoafastamentoesocial( ";
         $sql .= " eso10_sequencial        int4 NOT NULL default 0, ";
         $sql .= " eso10_descricao     varchar(255) NOT NULL , ";
         $sql .= " eso10_db_cadattdinamico     int4 default 0, ";
         $sql .= " CONSTRAINT grupomotivoafastamentoesocial_sequ_pk PRIMARY KEY (eso10_sequencial)); ";

         $sql .= " CREATE TABLE configuracoes.mapeamentoatributosesocial( ";
         $sql .= " db39_camponovo       int4 NOT NULL default 0, ";
         $sql .= " db39_campoorigem     int4 NOT NULL default 0, ";
         $sql .= " CONSTRAINT mapeamentoatributosesocial_camponovo_campoorigem_pk PRIMARY KEY (db39_camponovo,db39_campoorigem)); ";

        //CHAVE ESTRANGEIRA
        $sql .= " ALTER TABLE esocial.afastamentosesocial ";
        $sql .= " ADD CONSTRAINT afastamentosesocial_tipoasse_fk FOREIGN KEY (eso08_tipoasse) ";
        $sql .= " REFERENCES tipoasse; ";

        $sql .= " ALTER TABLE recursoshumanos.retificacaoafastamento ";
        $sql .= " ADD CONSTRAINT retificacaoafastamento_novoafastamento_fk FOREIGN KEY (rh220_assenta) ";
        $sql .= " REFERENCES assenta; ";

        $sql .= " ALTER TABLE esocial.grupomotivoafastamentoesocial ";
        $sql .= " ADD CONSTRAINT grupomotivoafastamentoesocial_cadattdinamico_fk FOREIGN KEY (eso10_db_cadattdinamico) ";
        $sql .= " REFERENCES db_cadattdinamico; ";

        $sql .= " ALTER TABLE esocial.motivoafastamentoesocial ";
        $sql .= " ADD CONSTRAINT motivoafastamentoesocial_grupomotivoafastamentoesocial_fk FOREIGN KEY (eso09_grupomotivoafastamentoesocial) ";
        $sql .= " REFERENCES grupomotivoafastamentoesocial; ";

        $sql .= " ALTER TABLE esocial.afastamentosesocial ";
        $sql .= " ADD CONSTRAINT afastamentosesocial_grupomotivoafastamentoesocial_fk FOREIGN KEY (eso08_grupomotivoafastamentoesocial) ";
        $sql .= " REFERENCES grupomotivoafastamentoesocial; ";

        $sql .= " ALTER TABLE configuracoes.mapeamentoatributosesocial ";
        $sql .= " ADD CONSTRAINT mapeamentoatributosesocial_campoorigem_campoorigem_fk FOREIGN KEY (db39_campoorigem) ";
        $sql .= " REFERENCES db_cadattdinamicoatributos; ";

        $sql .= " ALTER TABLE configuracoes.mapeamentoatributosesocial ";
        $sql .= " ADD CONSTRAINT mapeamentoatributosesocial_campoorigem_camponovo_fk FOREIGN KEY (db39_camponovo) ";
        $sql .= " REFERENCES db_cadattdinamicoatributos; ";

        //INDICES
        $sql .= " CREATE INDEX afastamentosesocial_eso08_tipoasse_in ON esocial.afastamentosesocial(eso08_tipoasse); ";
        $sql .= " CREATE INDEX retificacaoafastamento_rh220_assenta_in ON recursoshumanos.retificacaoafastamento(rh220_assenta); ";
        $sql .= " CREATE INDEX grupomotivoafastamentoesocial_eso10_db_cadattdinamico_in ON esocial.grupomotivoafastamentoesocial(eso10_db_cadattdinamico); ";
        $sql .= " CREATE INDEX motivoafastamentoesocial_eso09_grupomotivoafastamentoesocial_in ON esocial.motivoafastamentoesocial(eso09_grupomotivoafastamentoesocial); ";
        $sql .= " CREATE INDEX afastamentosesocial_eso08_grupomotivoafastamentoesocial_in ON esocial.afastamentosesocial(eso08_grupomotivoafastamentoesocial); ";

        // Tabela db_cadattdinamicoatributos - Adicionando colunas
        $sql .= " ALTER TABLE configuracoes.db_cadattdinamicoatributos ADD COLUMN db109_fixo BOOL DEFAULT false NOT NULL; ";
        $sql .= " ALTER TABLE configuracoes.db_cadattdinamicoatributos ADD COLUMN db109_db_formulas INT4; ";
        $sql .= " ALTER TABLE configuracoes.db_cadattdinamicoatributos ";
        $sql .= " ADD CONSTRAINT db_cadattdinamicoatributos_db_formulas_fk FOREIGN KEY (db109_db_formulas) ";
        $sql .= " REFERENCES db_formulas; ";
        $sql .= " CREATE INDEX db_cadattdinamicoatributos_db109_db_formulas_in ON configuracoes.db_cadattdinamicoatributos(db109_db_formulas); ";

        $this->execute($sql);
    }

    private function removerTabelas(){
        $sql  = " DROP TABLE IF EXISTS esocial.afastamentosesocial CASCADE; ";
        $sql .= " DROP TABLE IF EXISTS esocial.motivoafastamentoesocial CASCADE; ";
        $sql .= " DROP TABLE IF EXISTS recursoshumanos.retificacaoafastamento CASCADE; ";
        $sql .= " DROP TABLE IF EXISTS grupomotivoafastamentoesocial CASCADE; ";
        $sql .= " DROP TABLE IF EXISTS mapeamentoatributosesocial CASCADE; ";
        $sql .= " DROP SEQUENCE IF EXISTS afastamentosesocial_eso08_sequencial_seq; ";
        $sql .= " DROP SEQUENCE IF EXISTS retificacaoafastamento_rh220_sequencial_seq; ";
        $sql .= " DROP SEQUENCE IF EXISTS grupomotivoafastamentoesocial_eso10_sequencial_seq; ";
        $sql .= " ALTER TABLE configuracoes.db_cadattdinamicoatributos DROP COLUMN db109_fixo; ";
        $sql .= " ALTER TABLE configuracoes.db_cadattdinamicoatributos DROP COLUMN db109_db_formulas; ";

        $this->execute($sql);
    }

    private function inserirMotivosAfastamento() {
        // Fórmula para validar o CNPJ informado com o CNPJ da lotação
        $sql  = "insert into db_formulas values ( ";
        $sql .= "     6676,  ";
        $sql .= "     'ESOCIAL_CNPJ_EMPREGADOR',  ";
        $sql .= "     'Retorna se o CNPJ informado é igual ao do empregador vinculado a lotação.',  ";
        $sql .= "     'SELECT CASE \' (\'||cgm.z01_cgccpf||\') \'  ";
        $sql .= "              WHEN \'[CNPJ_ENTIDADE_ESOCIAL]\'::varchar";
        $sql .= "                THEN TRUE ";
        $sql .= "                ELSE FALSE ";
        $sql .= "            END AS validacao,";
        $sql .= "            \'CNPJ informado deve ser diferente do qual o servidor está alocado.\' AS mensagem";
        $sql .= "       FROM rhpessoal ";
        $sql .= "       INNER JOIN rhpessoalmov ON rhpessoalmov.rh02_regist = rhpessoal.rh01_regist  ";
        $sql .= "                              AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(\'db_instit\')::integer)  ";
        $sql .= "                              AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(\'db_instit\')::integer) ";
        $sql .= "       INNER JOIN rhlota ON rhlota.r70_codigo = rhpessoalmov.rh02_lota ";
        $sql .= "       INNER JOIN db_config ON db_config.codigo = rhlota.r70_instit ";
        $sql .= "       INNER JOIN cgm ON cgm.z01_numcgm = db_config.numcgm ";
        $sql .= "       WHERE rh01_regist in [H16_REGIST]',  ";
        $sql .= "     false); ";

        //Acidente/Doença do trabalho - Motivo 1
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Acidente/Doença do trabalho' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 1, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Tipo de acidente de trânsito', '', 6, 'tipo_acidente_transito_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 0, 'Nenhum'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 1, 'Atropelamento'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 2, 'Colisão'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 3, 'Outros'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'CID', '', 1, 'cid_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Nome do médico/dentista', '', 1, 'nome_medico_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Número de inscrição do órgão de classe', '', 1, 'tipo_orgao_medico_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Órgão de classe', '', 6, 'orgao_classe_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 1, 'Conselho Regional de Medicina (CRM)'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 2, 'Conselho Regional de Odontologia (CRO)'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 3, 'Registro do Ministério da Saúde (RMS)'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), 15845,   'UF do órgão de classe', '', 1, 'uf_orgao_classe_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Acidente/Doença do trabalho', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (1, 'Acidente/Doença do trabalho', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Acidente/Doença não relacionada ao trabalho - Motivo 3
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Acidente/Doença não relacionada ao trabalho' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 3, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Acidente/Doença não relacionada ao trabalho', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (3, 'Acidente/Doença não relacionada ao trabalho', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Afastamento/licença Motivos 5, 7, 8, 10 e 16
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Afastamento/licença' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', '', 6, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 5, 'Afastamento/licença prevista em regime próprio (estatuto), sem remuneração'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 7, 'Acompanhamento: Licença para acompanhamento de membro da família enfermo'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 8, 'Afastamento do empregado para participar de atividade do Conselho Curador do FGTS'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 10, 'Afastamento/licença prevista em regime próprio (estatuto), com remuneração'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 16, 'Licença remunerada'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Afastamento/licença', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (5, 'Afastamento/licença prevista em regime próprio (estatuto), sem remuneração', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (7, 'Acompanhamento - Licença para acompanhamento de membro da família enfermo', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (8, 'Afastamento do empregado para participar de atividade do Conselho Curador do FGTS - art. 65, §6o, Dec. 99.684/90 (Regulamento do FGTS)', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (10, 'Afastamento/licença prevista em regime próprio (estatuto), com remuneração', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (16, 'Licença remunerada - Lei, liberalidade da empresa ou Acordo/Convenção Coletiva de Trabalho', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Licença não remunerada ou Sem Vencimento - Motivo 21
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Licença não remunerada ou Sem Vencimento' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 21, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 't', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Licença não remunerada ou Sem Vencimento', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (21, 'Licença não remunerada ou Sem Vencimento', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Aposentadoria por invalidez - Motivo 6
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Aposentadoria por invalidez' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 6, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Aposentadoria por invalidez', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (6, 'Aposentadoria por invalidez', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Cárcere - Motivo 11
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Cárcere' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 11, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Cárcere', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (11, 'Cárcere', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Cargo Eletivo - Motivo 12 e 13
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Cargo Eletivo' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', '', 6, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 12, 'Candidato a cargo eletivo: Lei 7.664/1988. art. 25°'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 13, 'Candidato a cargo eletivo: Lei Complementar 64/1990. art. 1°'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Cargo Eletivo', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (12, 'Cargo Eletivo - Candidato a cargo eletivo - Lei 7.664/1988. art. 25°, parágrafo único - Celetistas em geral', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (13, 'Cargo Eletivo - Candidato a cargo eletivo - Lei Complementar 64/1990. art. 1°, inciso II', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Cessão/Requisição - Motivo 14
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Cessão/Requisição' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 14, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'CNPJ órgão/entidade', '', 1, 'cnpj_entidade_esocial', 't', 't', 't', 6676); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Ônus da cessão', '', 6, 'tipo_cessao_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 1, 'Ônus do Cedente'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 2, 'Ônus do Cessionário'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 3, 'Ônus do Cedente e Cessionário'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Cessão/Requisição', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (14, 'Cessão / Requisição', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Gozo de férias - Motivo 15
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Gozo de férias' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 15, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Gozo de férias', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (15, 'Gozo de férias ou recesso - Afastamento temporário para o gozo de férias ou recesso', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Licença Maternidade - Motivos 17, 18, 19, 20 e 33
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Licença Maternidade' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', '', 6, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 17, 'Licença Maternidade: 120 dias e suas prorrogações/antecipações'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 18, 'Licença Maternidade: 120 dias a 180 dias'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 19, 'Licença Maternidade: Afastamento temporário por motivo de aborto não criminoso'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 20, 'Licença Maternidade: Afastamento temporário por adoção ou guarda judicial'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 33, 'Licença Maternidade: De 180 dias'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Licença Maternidade', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (17, 'Licença Maternidade - 120 dias e suas prorrogações/antecipações, inclusive para o cônjuge sobrevivente', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (18, 'Licença Maternidade - 121 dias a 180 dias, Lei 11.770/2008 (Empresa Cidadã), inclusive para o cônjuge sobrevivente', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (19, 'Licença Maternidade - Afastamento temporário por motivo de aborto não criminoso', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (20, 'Licença Maternidade - Afastamento temporário por motivo de licença-maternidade decorrente de adoção ou guarda judicial de criança, inclusive para o cônjuge sobrevivente', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (33, 'Licença Maternidade - de 180 dias, Lei 13.301/2016', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Mandato - Motivos 22 e 23
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Mandato' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', '', 6, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 22, 'Afastamento temporário para o exercício de mandato eleitoral, sem remuneração'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 23, 'Afastamento temporário para o exercício de mandato eleitoral, com remuneração'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Mandato', currval('db_cadattdinamico_db118_sequencial_seq')); ";

        //Mandado Sindical - Motivo 24
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Mandado Sindical' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 24, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'CNPJ do sindicato', '', 1, 'cnpj_entidade_esocial', 't', 't', 't', 6676); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Ônus da remuneração', '', 6, 'tipo_onus_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 1, 'Apenas do Empregador'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 2, 'Apenas do Sindicato'); ";
        $sql .= " insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), currval('db_cadattdinamicoatributos_db109_sequencial_seq'), 3, 'Parte do Empregador, sendo a diferença e/ou complementação salarial paga pelo Sindicato');   ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Mandato Sindical', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (22, 'Mandato Eleitoral - Afastamento temporário para o exercício de mandato eleitoral, sem remuneração', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq'));  ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (23, 'Mandato Eleitoral - Afastamento temporário para o exercício de mandato eleitoral, com remuneração', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq'));  ";

        //Mulher vítima de violência - Motivo 25
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Mulher vítima de violência' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 25, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Mulher vítima de violência', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (25, 'Mulher vítima de violência - Lei 11.340/2006 - art. 9o §2o, II - Lei Maria da Penha', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Participação de empregado no Conselho Nacional de Previdência - Motivo 26
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Participação de empregado no Conselho Nacional de Previdência' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 26, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Participação de empregado no Conselho Nacional de Previdência', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (26, 'Participação de empregado no Conselho Nacional de Previdência Social-CNPS (art. 3o, Lei 8.213/1991)', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq'));  ";

        //Qualificação  Afastamento por suspensão do contrato de acordo - Motivo 27
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Qualificação  Afastamento por suspensão do contrato de acordo' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 27, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Qualificação  Afastamento por suspensão do contrato de acordo', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (27, 'Qualificação - Afastamento por suspensão do contrato de acordo com o art 476-A da CLT', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Representante Sindical - Motivo 28
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Representante Sindical' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 28, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Representante Sindical', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (28, 'Representante Sindical', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Serviço Militar - Motivo 29
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Serviço Militar' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 29, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Serviço Militar', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (29, 'Serviço Militar - Afastamento temporário para prestar serviço militar obrigatório', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Suspensão disciplinar - Motivo 30
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Suspensão disciplinar' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 30, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Suspensão disciplinar', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (30, 'Suspensão disciplinar - CLT, art. 474', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Servidor Público em Disponibilidade - Motivo 31
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Servidor Público em Disponibilidade' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 31, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Servidor Público em Disponibilidade', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (31, 'Servidor Público em Disponibilidade', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        //Inatividade do trabalhador avulso por período superior a 90 dias - Motivo 34
        $sql .= " insert into db_cadattdinamico values (nextval('db_cadattdinamico_db118_sequencial_seq'), 'Inatividade do trabalhador avulso por período superior a 90 dias' ); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Motivo', 34, 7, 'motivo_esocial', 't', 't', 't'); ";
        $sql .= " insert into db_cadattdinamicoatributos values (nextval('db_cadattdinamicoatributos_db109_sequencial_seq'), currval('db_cadattdinamico_db118_sequencial_seq'), null,   'Observações', '', 1, 'observacoes_esocial', 'f', 't', 't'); ";
        $sql .= " insert into esocial.grupomotivoafastamentoesocial values (nextval('grupomotivoafastamentoesocial_eso10_sequencial_seq'), 'Inatividade do trabalhador avulso por período superior a 90 dias', currval('db_cadattdinamico_db118_sequencial_seq')); ";
        $sql .= " insert into esocial.motivoafastamentoesocial values (34, 'Inatividade do trabalhador avulso (portuário ou não portuário) por período superior a 90 dias', currval('grupomotivoafastamentoesocial_eso10_sequencial_seq')); ";

        $this->execute($sql);
    }

    private function removerMotivosAfastamento(){
        $sql  = " create table w_m10171_db_cadattdinamico as select eso10_db_cadattdinamico as codigo from grupomotivoafastamentoesocial; ";
        $sql .= " delete from esocial.motivoafastamentoesocial; ";
        $sql .= " delete from esocial.grupomotivoafastamentoesocial; ";

        $sql .= " delete from db_cadattdinamicoatributosopcoes  ";
        $sql .= "     using db_cadattdinamicoatributos, w_m10171_db_cadattdinamico ";
        $sql .= "     where db_cadattdinamicoatributosopcoes.db18_cadattdinamicoatributos = db_cadattdinamicoatributos.db109_sequencial ";
        $sql .= "       and db_cadattdinamicoatributos.db109_db_cadattdinamico = w_m10171_db_cadattdinamico.codigo; ";

        $sql .= " delete from db_cadattdinamicoatributos ";
        $sql .= "           using w_m10171_db_cadattdinamico ";
        $sql .= "           where w_m10171_db_cadattdinamico.codigo = db_cadattdinamicoatributos.db109_db_cadattdinamico; ";

        $sql .= " delete from db_cadattdinamico where db118_sequencial in (select codigo from w_m10171_db_cadattdinamico); ";
        $sql .= " delete from db_formulas where db148_sequencial = 6676; ";
        $sql .= " drop table w_m10171_db_cadattdinamico; ";

        $this->execute($sql);
    }

    private function addMenus() {
        $sql  = " insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10515 ,'Vínculo de Afastamento' ,'Vínculo de Afastamento' ,'eso4_vinculoafastamentos001.php' ,'1' ,'1' ,'Tela de vínculo dos assentamentos que são considerados como afastamentos para o eSocial.' ,'true' ); ";
        $sql .= " insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10475 ,10515 ,3 ,10216 ); ";
        $this->execute($sql);
    }

    private function removerMenus() {
        $sql  = " delete from db_menu where id_item_filho = 10515 AND modulo = 10216; ";
        $sql .= " delete from db_itensmenu where id_item in (10515); ";
        $this->execute($sql);
    }
}

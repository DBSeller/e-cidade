<?php

use Classes\PostgresMigration;

class M10577IncorporacaoBem extends PostgresMigration
{
    public function up()
    {
        $this->dicionario();
        $this->estrutura();
        $this->menu();
        $this->documentos();
    }

    private function documentos()
    {
        $this->execute(
            <<<SQL_UP_DOCUMENTO
insert into conhistdoc values (705, upper('Incorporação de Bens em Estoque'), 700);
insert into conhistdoc values (706, upper('Estorno da Incorporação de Bens em Estoque'), 701);
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 705, 706);
insert into conhistdoc values (216, 'CONTROLE DE DESPESA EM LIQUIDAÇÃO - RP', 200);
insert into conhistdoc values (217, 'ESTORNO CONTROLE DE DESPESA EM LIQUIDAÇÃO - RP', 201);
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 216, 217);

insert into conhistdoc values (707, upper('Incorporação de Materiais a Bem Permanente'), 700);
insert into conhistdoc values (708, upper('Estorno da Incorporação de Materiais a Bem Permanente'), 701);
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 707, 708);

insert into conhistdoc values (709, upper('Incorporação de Serviços a Bem Permanente'), 700);
insert into conhistdoc values (710, upper('Estorno da Incorporação de Serviços a Bem Permanente'), 701);
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 709, 710);
SQL_UP_DOCUMENTO
        );
    }

    private function dicionario()
    {
        $this->execute(<<<SQL
            insert into db_sysarquivo values (1010288, 'bempendenteincorporacao', 'bempendenteincorporacao', 't12', '2018-06-13', 'bempendenteincorporacao', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (18,1010288);
            insert into db_syscampo values(1009772,'t12_sequencial','int4','Pk','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1009773,'t12_matestoqueinimei','int4','matestoqueinimei','0', 'matestoqueinimei',10,'f','f','f',1,'text','matestoqueinimei');
            insert into db_syscampo values(1009774,'t12_servico','bool','se é um serviço','f', 'Serviço',1,'f','f','f',5,'text','Serviço');
            insert into db_syscampo values(1009775,'t12_valorunitario','float4','Valor unitário','0', 'Valor',10,'f','f','f',4,'text','Valor');
            insert into db_syscampo values(1009805,'t12_empenho','int4','Empenho','0', 'Empenho',10,'f','f','f',1,'text','Empenho');
            insert into db_sysarqcamp values(1010288,1009772,1,0);
            insert into db_sysarqcamp values(1010288,1009773,2,0);
            insert into db_sysarqcamp values(1010288,1009774,3,0);
            insert into db_sysarqcamp values(1010288,1009775,4,0);
            insert into db_sysarqcamp values(1010288,1009805,5,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010288,1009772,1,1009772);
            insert into db_sysforkey values(1010288,1009773,1,1135,0);
            insert into db_sysforkey values(1010288,1009805,1,889,0);
            insert into db_sysindices values(1008289,'bempendenteincorporacao_matestoqueinimei_in',1010288,'0');
            insert into db_syscadind values(1008289,1009773,1);
            insert into db_sysindices values(1008296,'bempendenteincorporacao_empenho_in',1010288,'0');
            insert into db_syscadind values(1008296,1009805,1);
            insert into db_syssequencia values(1000739, 'bempendenteincorporacao_t12_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000739 where codarq = 1010288 and codcam = 1009772;
            
            insert into db_sysarquivo values (1010289, 'bemincorporado', 'Materia / Serviço incorporado a um bem patrimonial', 't13', '2018-06-13', 'bemincorporado', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (18,1010289);
            insert into db_syscampo values(1009776,'t13_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1009777,'t13_bens','int4','Bem','0', 'Bem',10,'f','f','f',1,'text','Bem');
            insert into db_syscampo values(1009778,'t13_bempendenteincorporacao','int4','Bem Incorporado','0', 'bemincorporado',10,'f','f','f',1,'text','Bem Incorporado');
            insert into db_syscampo values(1009779,'t13_data','date','Data','null', 'Data',10,'f','f','f',1,'text','Data');
            insert into db_syscampo values(1009780,'t13_reavaliacao','bool','Se o bem sofreu reavaliação patrimonial','f', 'Reavaliação',1,'f','f','f',5,'text','Reavaliação');
            insert into db_syscampo values(1009801,'t13_quantidade','float4','Quantidade','0', 'Quantidade',10,'f','f','f',4,'text','Quantidade');
            insert into db_syscampo values(1009806,'t13_ativo','bool','Incorporação ativa','f', 'Incorporação ativa',1,'f','f','f',5,'text','Incorporação ativa');
            insert into db_sysarqcamp values(1010289,1009776,1,0);
            insert into db_sysarqcamp values(1010289,1009777,2,0);
            insert into db_sysarqcamp values(1010289,1009778,3,0);
            insert into db_sysarqcamp values(1010289,1009779,4,0);
            insert into db_sysarqcamp values(1010289,1009780,5,0);
            insert into db_sysarqcamp values(1010289,1009801,6,0);
            insert into db_sysarqcamp values(1010289,1009806,7,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010289,1009776,1,1009776);
            insert into db_sysforkey values(1010289,1009777,1,914,0);
            insert into db_sysforkey values(1010289,1009778,1,1010288,0);
            insert into db_sysindices values(1008290,'bemincorporado_bem_in',1010289,'0');
            insert into db_syscadind values(1008290,1009777,1);
            insert into db_sysindices values(1008291,'bemincorporado_bempendenteincorporacao_in',1010289,'0');
            insert into db_syscadind values(1008291,1009778,1);
            insert into db_sysindices values(1008297,'bemincorporado_ativo_in',1010289,'0');
            insert into db_syscadind values(1008297,1009806,1);
            insert into db_syssequencia values(1000740, 'bemincorporado_t13_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000740 where codarq = 1010289 and codcam = 1009776;
            
            insert into db_sysarquivo values (1010292, 'matordemprocessoentrada', 'Processo de entrada da ordem de compra', 'm57', '2018-06-28', 'matordemprocessoentrada', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (13,1010292);
            insert into db_syscampo values(1009802,'m57_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1009803,'m57_processoentrada','int4','Processo de Entrada da Nota','0', 'Processo de Entrada da Nota',10,'f','f','f',1,'text','Processo de Entrada da Nota');
            insert into db_syscampodef values(1009803,'1','Entrada de Serviços Prestados');
            insert into db_syscampodef values(1009803,'2','Entrada de Material de Consumo no Almoxarifado');
            insert into db_syscampodef values(1009803,'3','Entrada de Bens Permanentes');
            insert into db_syscampodef values(1009803,'4','Materiais Incorporáveis a Bens Permanentes');
            insert into db_syscampodef values(1009803,'5','Serviços Incorporáveis a Bens Permanentes');
            delete from db_sysarqcamp where codarq = 1010292;
            insert into db_sysarqcamp values(1010292,1009802,1,0);
            insert into db_sysarqcamp values(1010292,1009803,2,0);
            delete from db_sysprikey where codarq = 1010292;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010292,1009802,1,1009803);
            insert into db_syscampo values(1009804,'m57_matordem','int4','Ordem de Compra','0', 'Ordem de Compra',1,'f','f','f',1,'text','Ordem de Compra');
            delete from db_sysarqcamp where codarq = 1010292;
            insert into db_sysarqcamp values(1010292,1009802,1,0);
            insert into db_sysarqcamp values(1010292,1009804,2,0);
            insert into db_sysarqcamp values(1010292,1009803,3,0);
            delete from db_sysforkey where codarq = 1010292 and referen = 0;
            insert into db_sysforkey values(1010292,1009804,1,1007,0);
            insert into db_sysindices values(1008295,'matordemprocessoentrada_matordem_in',1010292,'1');
            insert into db_syscadind values(1008295,1009804,1);
            insert into db_syssequencia values(1000743, 'matordemprocessoentrada_m57_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000743 where codarq = 1010292 and codcam = 1009802;
SQL
        );
    }

    private function estrutura()
    {
        $this->execute(<<<SQL
            CREATE SEQUENCE patrimonio.bemincorporado_t13_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            CREATE SEQUENCE patrimonio.bempendenteincorporacao_t12_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            
            CREATE TABLE patrimonio.bemincorporado(
              t13_sequencial int4 NOT NULL default 0,
              t13_bens int4 NOT NULL default 0,
              t13_bempendenteincorporacao int4 NOT NULL default 0,
              t13_data date NOT NULL default null,
              t13_reavaliacao bool default 'f',
              t13_quantidade numeric,
              t13_ativo bool default 't',
              CONSTRAINT bemincorporado_sequ_pk PRIMARY KEY (t13_sequencial)
            );
            
            CREATE TABLE patrimonio.bempendenteincorporacao(
              t12_sequencial int4 NOT NULL default 0,
              t12_matestoqueinimei int4 NOT NULL default 0,
              t12_servico bool NOT NULL default 'f',
              t12_valorunitario	numeric default 0,
              t12_empenho int4 NOT NULL default 0,
              CONSTRAINT bempendenteincorporacao_sequ_pk PRIMARY KEY (t12_sequencial)
            );
            
            ALTER TABLE patrimonio.bemincorporado ADD CONSTRAINT bemincorporado_bens_fk FOREIGN KEY (t13_bens) REFERENCES bens;
            ALTER TABLE patrimonio.bemincorporado ADD CONSTRAINT bemincorporado_bempendenteincorporacao_fk FOREIGN KEY (t13_bempendenteincorporacao) REFERENCES patrimonio.bempendenteincorporacao;
            ALTER TABLE patrimonio.bempendenteincorporacao ADD CONSTRAINT bempendenteincorporacao_matestoqueinimei_fk FOREIGN KEY (t12_matestoqueinimei) REFERENCES matestoqueinimei;
            ALTER TABLE patrimonio.bempendenteincorporacao ADD CONSTRAINT bempendenteincorporacao_empempenho_fk FOREIGN KEY (t12_empenho ) REFERENCES empempenho;
            
            CREATE INDEX bemincorporado_bem_in ON patrimonio.bemincorporado(t13_bens);
            CREATE INDEX bemincorporado_bempendenteincorporacao_in ON patrimonio.bemincorporado(t13_bempendenteincorporacao);
            CREATE INDEX bempendenteincorporacao_matestoqueinimei_in ON patrimonio.bempendenteincorporacao(t12_matestoqueinimei);
            CREATE INDEX bempendenteincorporacao_empenho_in ON patrimonio.bempendenteincorporacao(t12_empenho);
            CREATE INDEX bemincorporado_ativo_in ON patrimonio.bemincorporado(t13_ativo);
            
            CREATE SEQUENCE matordemprocessoentrada_m57_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            
            CREATE TABLE matordemprocessoentrada(
            m57_sequencial   int4 NOT NULL default 0,
            m57_matordem     int4 NOT NULL default 0,
            m57_processoentrada int4 default 0,
            CONSTRAINT matordemprocessoentrada_sequ_pk PRIMARY KEY (m57_sequencial));
            
            ALTER TABLE matordemprocessoentrada ADD CONSTRAINT matordemprocessoentrada_matordem_fk FOREIGN KEY (m57_matordem) REFERENCES matordem;
            CREATE UNIQUE INDEX matordemprocessoentrada_matordem_in ON matordemprocessoentrada(m57_matordem);
SQL
        );
    }

    private function menu()
    {
        $this->execute(<<<SQL
        insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10535 ,'Implantação da Incorporação de Bens' ,'Implantação da Incorporação de Bens' ,'pat4_implantacaoincorporacaobem001.php' ,'1' ,'1' ,'Implantação da incorporação de bem' ,'true' );
        insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10536 ,'Incorporação de Bens' ,'Incorporação de Bens' ,'pat4_incorporacaobem001.php' ,'1' ,'1' ,'Incorporação de Bens' ,'false' );
        insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10537 ,'Patrimônio' ,'Patrimônio' ,'' ,'1' ,'1' ,'Patrimônio' ,'true' );
        insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10540 ,'Desprocessar Incorporação de Bens' ,'Desprocessar Incorporação de Bens' ,'pat4_desprocessarincorporacaobem001.php' ,'1' ,'1' ,'Desprocessar Incorporação de Bens' ,'false' );
        
        insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10536 ,499 ,439 );
        insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10537 ,500 ,1 );
        insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10537 ,10535 ,1 ,1 );
        insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10540 ,501 ,439 );
SQL
        );
    }

    public function down()
    {
        $this->execute(
            <<<SQL
            delete from db_sysprikey where codarq in (1010288, 1010289, 1010292);
            delete from db_sysforkey where codarq in (1010288, 1010289, 1010292);
            delete from db_syssequencia where codsequencia in (1000740, 1000739, 1000743);
            delete from db_syscadind where codind in (1008291, 1008290, 1008289, 1008295, 1008296, 1008297);
            delete from db_sysindices where codind in (1008291, 1008290, 1008289, 1008295, 1008296, 1008297);
            delete from db_sysarqcamp where codarq in (1010288, 1010289, 1010292);
            delete from db_syscampodef where codcam in (1009803);
            delete from db_syscampo where codcam in (1009776, 1009777, 1009778, 1009779, 1009780, 1009772, 1009773, 1009774, 1009775, 1009801, 1009802, 1009803, 1009804, 1009805, 1009806);
            delete from db_sysarqmod where codarq in (1010288, 1010289, 1010292);
            delete from db_sysarquivo where codarq in (1010288, 1010289, 1010292);

            DROP TABLE IF EXISTS patrimonio.bemincorporado CASCADE;
            DROP TABLE IF EXISTS patrimonio.bempendenteincorporacao CASCADE;
            DROP SEQUENCE IF EXISTS patrimonio.bemincorporado_t13_sequencial_seq;
            DROP SEQUENCE IF EXISTS patrimonio.bempendenteincorporacao_t12_sequencial_seq;
            delete from db_menu where id_item_filho in (10536, 10537, 10535, 10540);
            delete from db_itensmenu where id_item in (10536, 10537, 10535, 10540);

            DROP TABLE IF EXISTS matordemprocessoentrada CASCADE;
            DROP SEQUENCE IF EXISTS matordemprocessoentrada_m57_sequencial_seq;
            delete from vinculoeventoscontabeis where c115_conhistdocinclusao in (705, 216, 707, 709);
            delete from conhistdoc where c53_coddoc in (705,706, 707, 708, 216, 217, 709, 710);
SQL
        );

    }
}

---
--- Alterações de estrutura
select fc_executa_ddl('
	CREATE SEQUENCE pessoal.rhconsignadomovimentomanual_rh182_sequencial_seq
		INCREMENT 1
		MINVALUE 1
		MAXVALUE 9223372036854775807
		START 1
		CACHE 1;
');

CREATE TABLE IF NOT EXISTS pessoal.rhconsignadomovimentomanual(
	rh182_sequencial                    int4    NOT NULL,
	rh182_rhconsignadomovimento         int4    NOT NULL,
	rh182_rhconsignadomovimentoservidor int4    NOT NULL,
	rh182_processado                    boolean NOT NULL default false,
	rh182_ano                           int4    NOT NULL,
	rh182_mes                           int4    NOT NULL,
	CONSTRAINT rhconsignadomovimentomanual_seq_pk PRIMARY KEY (rh182_sequencial),
	CONSTRAINT rhconsignadomovimento_fk FOREIGN KEY (rh182_rhconsignadomovimento) REFERENCES rhconsignadomovimento,
	CONSTRAINT rhconsignadomovimentoservidor_fk FOREIGN KEY (rh182_rhconsignadomovimentoservidor) REFERENCES rhconsignadomovimentoservidor
);
select fc_executa_ddl('
	CREATE UNIQUE INDEX rhconsignadomovimentomanual_un_in ON pessoal.rhconsignadomovimentomanual(rh182_rhconsignadomovimento, rh182_rhconsignadomovimentoservidor, rh182_ano, rh182_mes);
');

select fc_executa_ddl('ALTER TABLE pessoal.rhconsignadomovimento ADD COLUMN rh151_tipoconsignado char(1) NULL;');
select fc_executa_ddl('ALTER TABLE pessoal.rhconsignadomovimento ADD COLUMN rh151_consignadoorigem int4 NULL;');
select fc_executa_ddl('ALTER TABLE pessoal.rhconsignadomovimento ADD COLUMN rh151_situacao char(1) NULL;');

---
--- Alterações de Dicionário de dados
-- Menus
update db_itensmenu set id_item = 10232 , descricao = 'Manutenção de Empréstimos Consignados' where id_item = 10232;
delete from db_menu where id_item_filho = 10232;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values (1818, 10232, 10, 952);

insert into db_itensmenu select 10257 ,'Convênios' ,'Outras integrações de consignados' ,'' ,'1' ,'1' ,'Outras integrações de consignados, exemplo: consignet e e-consig' ,'true' from db_itensmenu where not exists (select 1 from db_itensmenu where id_item = 10257) limit 1;
delete from db_menu where id_item_filho = 10257 AND modulo = 952;
insert into db_menu select 10232 ,10257 ,5 ,952 from db_menu where not exists (select 1 from db_menu where id_item = 10232 and id_item_filho = 10257 and menusequencia = 5 and modulo = 952) limit 1;
delete from db_menu where id_item_filho = 10049 AND modulo = 952;
insert into db_menu select 10257, 10049, 1, 952 from db_menu where not exists (select 1 from db_menu where id_item = 10257 and id_item_filho = 10049 and menusequencia = 1 and modulo = 952) limit 1;
delete from db_menu where id_item_filho = 9866 AND modulo = 952;
insert into db_menu select 10257, 9866, 2, 952 from db_menu where not exists (select 1 from db_menu where id_item = 10257 and id_item_filho = 9866 and menusequencia = 2 and modulo = 952) limit 1;

insert into db_itensmenu select 10258 ,'Gestão de Consignados' ,'Gestão de Consignados' ,'' ,'1' ,'1' ,'Menu para a gerência/gestão de contratos consignados que não são realizados via importação de arquivo.' ,'true' from db_itensmenu where not exists (select 1 from db_itensmenu where id_item = 10258) limit 1;
delete from db_menu where id_item_filho = 10258 AND modulo = 952;
insert into db_menu select 10232, 10258, 6, 952 from db_menu where not exists (select 1 from db_menu where id_item = 10232 and id_item_filho = 10258 and menusequencia = 6 and modulo = 952) limit 1;
insert into db_itensmenu select 10259 ,'Manutenção de Contratos' ,'Manutenção de Contratos' ,'pes4_manutencaocontratosconsignados.php' ,'1' ,'1' ,'Rotina para gerenciar manualmente a inclusão de descontos consignados.' ,'true' from db_itensmenu where not exists (select 1 from db_itensmenu where id_item = 10259) limit 1;
delete from db_menu where id_item_filho = 10259 AND modulo = 952;
insert into db_menu select 10258, 10259, 1, 952 from db_menu where not exists (select 1 from db_menu where id_item = 10258 and id_item_filho = 10259 and menusequencia = 1 and modulo = 952) limit 1;
delete from db_menu where id_item_filho = 10238 AND modulo = 952;
insert into db_menu select 10258, 10238, 2, 952 from db_menu where not exists (select 1 from db_menu where id_item = 10258 and id_item_filho = 10238 and menusequencia = 2 and modulo = 952) limit 1;

insert into db_itensmenu select 10260 ,'Arquivos' ,'Importação de arquivos consignados' ,'' ,'1' ,'1' ,'Importação de arquivos consignados' ,'true' from db_itensmenu where not exists (select 1 from db_itensmenu where id_item = 10260) limit 1;
delete from db_menu where id_item_filho = 10260 AND modulo = 952;
insert into db_menu select 10232, 10260, 7, 952 from db_menu where not exists (select 1 from db_menu where id_item = 10232 and id_item_filho = 10260 and menusequencia = 7 and modulo = 952) limit 1;
delete from db_menu where id_item_filho = 10234 AND modulo = 952;
insert into db_menu select 10260, 10234, 1, 952 from db_menu where not exists (select 1 from db_menu where id_item = 10260 and id_item_filho = 10234 and menusequencia = 1 and modulo = 952) limit 1;
update db_itensmenu set id_item = 10235 , descricao = 'Exportar' where id_item = 10235;
delete from db_menu where id_item_filho = 10235 AND modulo = 952;
insert into db_menu select 10260, 10235, 2, 952 from db_menu where not exists (select 1 from db_menu where id_item = 10260 and id_item_filho = 10235 and menusequencia = 2 and modulo = 952) limit 1;

insert into db_itensmenu select 10261 ,'Parâmetros' ,'Parâmetros' ,'' ,'1' ,'1' ,'Parâmetros de configuração para importação de arquivos, rubrica layout e banco são configurados.' ,'true' from db_itensmenu where not exists (select 1 from db_itensmenu where id_item = 10261) limit 1;
delete from db_menu where id_item_filho = 10261 AND modulo = 952;
insert into db_menu select 10232, 10261, 8, 952 from db_menu where not exists (select 1 from db_menu where id_item = 10232 and id_item_filho = 10261 and menusequencia = 8 and modulo = 952) limit 1;
delete from db_menu where id_item_filho = 10231 AND modulo = 952;
insert into db_menu select 10261, 10231, 1, 952 from db_menu where not exists (select 1 from db_menu where id_item = 10261 and id_item_filho = 10231 and menusequencia = 1 and modulo = 952) limit 1;


update db_itensmenu set id_item = 10232 , descricao = 'Consignados' where id_item = 10232;
delete from db_menu where id_item_filho IN (10032, 10066, 10059);
delete from db_menu where id_item_filho IN (10232, 9866, 10049, 10238, 10234, 10235, 10231);

insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (5106, 10049, 17, 952);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (5106, 10232, 18, 952);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (5106,  9866, 15, 952);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (10232, 10234, 1, 952);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (10232, 10238, 2, 952);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (10232, 10235, 3, 952);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (1818, 10032, 105, 952);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (1818, 10059, 106, 952);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (1818, 10066, 107, 952);
insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values (3516, 10231, 14, 952);

insert into db_itensmenu select 10270,'Manutenção de Empréstimos Consignados','Rotina para manutenção de empréstimos consignados.','','1','1','Rotina para manutenção de empréstimos consignados.','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10270) limit 1;
delete from db_menu where id_item_filho = 10270 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1818 ,10270 ,10 ,952 );

insert into db_itensmenu select 10271,'Consignet','Rotinas de arquivos de consignação para DB1','','1','1','Menu com as rotinas de geração, importação e exportação de arquivos para o consignet da empresa DB1','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10271) limit 1;
delete from db_menu where id_item_filho = 10271 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10257 ,10271 ,3 ,952 );

insert into db_itensmenu select 10272,'E-Consig','E-Consig','','1','1','Menu criado para geração de arquivo do e-consig','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10272) limit 1;
delete from db_menu where id_item_filho = 10272 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10257 ,10272 ,4 ,952 );

insert into db_itensmenu select 10273,'Conferência de Dados','Conferência de Dados','pes4_conferenciaconsignados001.php','1','1','Conferência de Dados','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10273) limit 1;
delete from db_menu where id_item_filho = 10273 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10258 ,10273 ,3 ,952 );

insert into db_itensmenu select 10274,'Importar','Importar dados do arquivo','pes4_importararquivoconsignado001.php','1','1','Importar dados do arquivo','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10274) limit 1;
delete from db_menu where id_item_filho = 10274 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10260 ,10274 ,3 ,952 );

insert into db_itensmenu select 10275,'Exportar','Exportar os dados do arquivo','pes4_exportararquivoconsignado001.php','1','1','Exportar os dados do arquivo','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10275) limit 1;
delete from db_menu where id_item_filho = 10275 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10260 ,10275 ,4 ,952 );

insert into db_itensmenu select 10276,'Configuração Consignados','Configuração Consignados','pes4_configurararquivoconsignado001.php','1','1','Configuraçao das consignaçoes em folha','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10276) limit 1;
delete from db_menu where id_item_filho = 10276 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10261 ,10276 ,2 ,952 );


delete from db_menu where id_item_filho = 10257 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10270 ,10257 ,1 ,952 );
delete from db_menu where id_item_filho = 10258 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10270 ,10258 ,2 ,952 );
delete from db_menu where id_item_filho = 10260 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10270 ,10260 ,3 ,952 );
delete from db_menu where id_item_filho = 10261 AND modulo = 952;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10270 ,10261 ,4 ,952 );

insert into db_itensmenu select 10277,'Processamento de Dados do Ponto','Processamento de Dados do Ponto','pes4_processamentodadosponto001.php','1','1','Rotina responsavél pelo lançamento dos dados nas tabelas do ponto.','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10277) limit 1;
delete from db_menu where id_item_filho = 10277 AND modulo = 952;
insert into db_menu (id_item ,id_item_filho,menusequencia, modulo) values (4504, 10277, 6, 952);
insert into db_itensmenu select 10278,'Registros do Ponto em Lote','Lançar rubricas em lote','','1','1','Menu para lançamento de rúbricas em lote, lançamento pode ser feito por rúbrica ou por servidor.','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10278) limit 1;
delete from db_menu where id_item_filho = 10278 AND modulo = 952;
insert into db_menu (id_item ,id_item_filho,menusequencia, modulo) values (4504, 10278, 7, 952);
insert into db_itensmenu select 10279,'Manutenção do Lote','Manutenção do Lote','pes4_manutencaolotesinicio001.php','1','1','Menu para criar e fechar lotes e lancar, alterar e excluir registros de um lote. Registro seria um lançamento do ponto.','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10279) limit 1;
delete from db_menu where id_item_filho = 10279 AND modulo = 952;
insert into db_menu (id_item ,id_item_filho,menusequencia, modulo) values (10278, 10279, 1, 952);
insert into db_itensmenu select 10280,'Processar Lote','Processar Lote','pes4_processamento_loteregistroponto.php','1','1','Menu utilizado para confirmar, cancelar, excluir um lote de registros do ponto.','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10280) limit 1;
delete from db_menu where id_item_filho = 10280 AND modulo = 952;
insert into db_menu (id_item ,id_item_filho,menusequencia, modulo) values (10278, 10280, 2, 952);
insert into db_itensmenu select 10281,'Lançamento de Assentamentos no Ponto','Lançamento de Assentamentos no Ponto','pes4_assentaloteregistroponto001.php','1','1','Menu utilizado para selecionar os assentamentos que serão pagos.','true' from db_itensmenu where NOT EXISTS (select 1 from db_itensmenu where id_item = 10281) limit 1;
delete from db_menu where id_item_filho = 10281 AND modulo = 952;
insert into db_menu (id_item ,id_item_filho,menusequencia, modulo) values (4504, 10281, 8, 952);

update db_itensmenu set funcao = funcao||'?menuDepreciado=true' where id_item IN (10061,10032,10060,10234,10235,10231,10238,10066);

	

--Tabelas
insert into db_sysarquivo select 3956, 'rhconsignadomovimentomanual', 'Tabela para guardar os contratos consignados incluídos manualmente sem rotina de importação', 'rh182', '2016-08-11', 'rhconsignadomovimentomanual', 0, 'f', 'f', 'f', 'f' from db_sysarquivo where NOT EXISTS (select 1 from db_sysarquivo where codarq = 3956) limit 1;
delete from db_sysarqmod where codmod = 28 and codarq = 3956;
insert into db_sysarqmod values (28,3956);
insert into db_syscampo select 21869,'rh151_arquivo','oid','Conteudo do Arquivo','','Conteudo do Arquivo',1,'true','false','false',1,'text','Conteudo do Arquivo' from db_syscampo where NOT EXISTS (select 1 from db_syscampo where codcam = 21869) limit 1;
insert into db_syscampo select 21870,'rh151_banco','varchar(10)','Banco','','Banco',10,'true','false','false',0,'text','Banco' from db_syscampo where NOT EXISTS (select 1 from db_syscampo where codcam = 21870) limit 1;
insert into db_syscampo select 21978,'rh182_rhconsignadomovimento','int4','Sequencial da tabela rhconsignadomovimento','0', 'Sequencial Contrato',19,'f','f','f',1,'text','Sequencial Contrato' from db_syscampo where NOT EXISTS (select 1 from db_syscampo where codcam = 21978) limit 1;
insert into db_syscampo select 21979,'rh182_rhconsignadomovimentoservidor','int4','Sequencial da tabela rhconsignadomovimentoservidor','0', 'Sequencial da Parcela',19,'f','f','f',1,'text','Sequencial da Parcela' from db_syscampo where NOT EXISTS (select 1 from db_syscampo where codcam = 21979) limit 1;
insert into db_syscampo select 21980,'rh182_processado','bool','Campo que determina se a parcela foi ou não processada.','f', 'Flag de processamento',1,'f','f','f',5,'text','Flag de processamento' from db_syscampo where NOT EXISTS (select 1 from db_syscampo where codcam = 21980) limit 1;
insert into db_syscampo select 21981,'rh182_ano','int4','Ano da competência','0', 'Ano da competência',4,'f','f','f',1,'text','Ano da competência' from db_syscampo where NOT EXISTS (select 1 from db_syscampo where codcam = 21981) limit 1;
insert into db_syscampo select 21982,'rh182_mes','int4','Mês da competência','0', 'Mês da competência',2,'f','f','f',1,'text','Mês da competência' from db_syscampo where NOT EXISTS (select 1 from db_syscampo where codcam = 21982) limit 1;
insert into db_syscampo select 21983,'rh182_sequencial','int4','Sequencial da tabela para falicitar manutenção','0', 'Sequencial',19,'f','f','f',1,'text','Sequencial' from db_syscampo where NOT EXISTS (select 1 from db_syscampo where codcam = 21983) limit 1;
insert into db_syscampodef select 21980,'0','' from db_syscampodef where NOT EXISTS (select 1 from db_syscampodef where codcam = 21980) limit 1;
delete from db_sysarqcamp where codarq = 3956;
insert into db_sysarqcamp values(3956,21983,1,0);
insert into db_sysarqcamp values(3956,21978,2,0);
insert into db_sysarqcamp values(3956,21979,3,0);
insert into db_sysarqcamp values(3956,21980,4,0);
insert into db_sysarqcamp values(3956,21981,5,0);
insert into db_sysarqcamp values(3956,21982,6,0);
delete from db_sysprikey where codarq = 3956 and codcam = 21983 and sequen = 1;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3956,21983,1,21983);
delete from db_sysforkey where codarq = 3956;
insert into db_sysforkey values(3956,21978,1,3785,0);
insert into db_sysforkey values(3956,21979,1,3786,0);
insert into db_sysindices select 4374,'rhconsignadomovimentomanual_un_in',3956,'1' from db_sysindices where NOT EXISTS (select 1 from db_sysindices where codind = 4374) limit 1;
delete from db_syscadind where codind = 4374;
insert into db_syscadind values(4374,21978,1);
insert into db_syscadind values(4374,21979,2);
insert into db_syscadind values(4374,21981,3);
insert into db_syscadind values(4374,21982,4);
insert into db_syssequencia select 1000591, 'rhconsignadomovimentomanual_rh182_sequencial_seq', 1, 1, 9223372036854775807, 1, 1 from db_syssequencia where NOT EXISTS (select 1 from db_syssequencia where codsequencia = 1000591) limit 1;
update db_sysarqcamp set codsequencia = 1000591 where codarq = 3956 and codcam = 21983;

insert into db_syscampo select 21984,'rh151_tipoconsignado','char(1)','Informa se o tipo de consignado é de origem de importação de arquivo ou incluído manualmente.','', 'Tipo do Consignado',1,'t','t','f',0,'text','Tipo do Consignado' from db_syscampo where NOT EXISTS (select 1 from db_syscampo where codcam = 21984) limit 1;
insert into db_syscampo select 21985,'rh151_consignadoorigem','int4','Campo utilizado para referenciar quem é o consignado que deu origem a este, utilizado em casos de refinanciamentos e portabilidades.','0', 'Código do consignado de origem',19,'t','f','f',1,'text','Código do consignado de origem' from db_syscampo where NOT EXISTS (select 1 from db_syscampo where codcam = 21985) limit 1;
insert into db_syscampo select 21986,'rh151_situacao','char(1)','Informa se é um consignado normal, ou refinanciamento ou portabilidade ou se foi cancelado.','', 'Situação do consignado',1,'t','t','f',0,'text','Situação do consignado' from db_syscampo where NOT EXISTS (select 1 from db_syscampo where codcam = 21986) limit 1;
delete from db_sysarqcamp where codarq = 3785;
insert into db_sysarqcamp values(3785,21005,1,1000441);
insert into db_sysarqcamp values(3785,21006,2,0);
insert into db_sysarqcamp values(3785,21007,3,0);
insert into db_sysarqcamp values(3785,21008,4,0);
insert into db_sysarqcamp values(3785,21009,5,0);
insert into db_sysarqcamp values(3785,21010,6,0);
insert into db_sysarqcamp values(3785,21011,7,0);
insert into db_sysarqcamp values(3785,21869,8,0);
insert into db_sysarqcamp values(3785,21870,9,0);
insert into db_sysarqcamp values(3785,21984,10,0);
insert into db_sysarqcamp values(3785,21985,11,0);
insert into db_sysarqcamp values(3785,21986,12,0);
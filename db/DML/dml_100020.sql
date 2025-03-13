
select fc_executa_ddl('alter table rhpessoalmov add column rh02_cedencia char(1) NOT NULL default ''X'';');
select fc_executa_ddl('alter table rhpessoalmov drop column rh02_cendencia');

update db_syscampo set nomecam = 'rh02_cedencia' where codcam = 21934;
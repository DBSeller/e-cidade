/**
 * PRE
 */

/*
 * Novos campos para a tabela rhpessoalmov referente as horas diárias e os campos referente a cedência.
 */
insert into db_syscampo values(21933,'rh02_horasdiarias','int4','Número de horas diárias, caso o campo tipo de folha seja diário.','0', 'Horas Diárias',20,'t','f','f',1,'text','Horas Diárias');
insert into db_syscampo values(21934,'rh02_cendencia','char(1)','Tipo da cedência, pode ser Cedido, Adido ou Não se Aplica, sendo não se aplica a opção default.','X', 'Tipo',1,'f','t','f',0,'text','Tipo Cedência');
insert into db_syscampodef values(21934,'C','Cedido');
insert into db_syscampodef values(21934,'A','Adido');
insert into db_syscampodef values(21934,'X','Não se aplica');
insert into db_syscampo values(21935,'rh02_onus','char(1)','Informa se existe onus na cedência de um servidor. Especifica se o Onus é da origem ou destino.','X', 'Ônus',1,'f','t','f',0,'text','Onus');
insert into db_syscampodef values(21935,'X','Não se Aplica');
insert into db_syscampodef values(21935,'S','Ônus para origem');
insert into db_syscampodef values(21935,'N','Ônus para destino');
insert into db_syscampo values(21936,'rh02_ressarcimento','char(1)','Campo que informa se a cedência possui ressarcimento.','X', 'Ressarcimento',1,'f','t','f',0,'text','Ressarcimento');
insert into db_syscampodef values(21936,'X','Não se aplica');
insert into db_syscampodef values(21936,'S','Sim');
insert into db_syscampodef values(21936,'N','Não');
insert into db_syscampo   values(21937,'rh02_datacedencia','date','Data emq ue ocorreu o cadastro da cedência','null', 'Data Movimentação',10,'t','f','f',1,'text','Data Movimentação');
insert into db_syscampo   values(21938,'rh02_cnpjcedencia','varchar(20)','CNPJ da Origem/Destino da Cedência. Armazena o CNPj para qual o servidor foi cedido, ou o cnpj do orgão que o Servidor foi Adido.','0', 'CNPJ Origem/Destino',20,'f','f','f',1,'text','CNPJ Origem/Destino');
insert into db_sysarqcamp values(1158,21933,28,0);
insert into db_sysarqcamp values(1158,21934,29,0);
insert into db_sysarqcamp values(1158,21935,30,0);
insert into db_sysarqcamp values(1158,21936,31,0);
insert into db_sysarqcamp values(1158,21937,32,0);
insert into db_sysarqcamp values(1158,21938,33,0);


/**
 * Atualizando layout do pad referente ao arquivo 4820.
 */
insert into db_layoutcampos SELECT 13751 ,138 ,'carga_horaria' ,'CARGA HORARIA' ,2 ,348 ,'00000000' ,3 ,'f' ,'t' ,'e' ,'' ,0 FROM db_layoutcampos WHERE NOT EXISTS(SELECT 1 FROM db_layoutcampos WHERE db52_codigo = 13751) limit 1;
insert into db_layoutcampos select 13752 ,138 ,'tipo_carga_horaria' ,'TIPO DA CARGA HORARIA' ,1 ,351 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 FROM db_layoutcampos WHERE NOT EXISTS(SELECT 1 FROM db_layoutcampos WHERE db52_codigo = 13752) limit 1;
insert into db_layoutcampos select 13753 ,138 ,'tipo_cedencia' ,'TIPO DA CEDENCIA' ,1 ,352 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 FROM db_layoutcampos WHERE NOT EXISTS(SELECT 1 FROM db_layoutcampos WHERE db52_codigo = 13753) limit 1;
insert into db_layoutcampos select 13754 ,138 ,'onus_origem' ,'ONUS PARA A ORIGEM' ,1 ,353 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 FROM db_layoutcampos WHERE NOT EXISTS(SELECT 1 FROM db_layoutcampos WHERE db52_codigo = 13754) limit 1;
insert into db_layoutcampos select 13755 ,138 ,'ressarcimento' ,'RESSARCIMENTO' ,1 ,354 ,'' ,1 ,'f' ,'t' ,'d' ,'' ,0 FROM db_layoutcampos WHERE NOT EXISTS(SELECT 1 FROM db_layoutcampos WHERE db52_codigo = 13755) limit 1;
insert into db_layoutcampos select 13756 ,138 ,'data_movimentacao_cedencia' ,'DATA DE MOVIMENTACAO' ,4 ,355 ,'' ,8 ,'f' ,'t' ,'e' ,'' ,0 FROM db_layoutcampos WHERE NOT EXISTS(SELECT 1 FROM db_layoutcampos WHERE db52_codigo = 13756) limit 1;
insert into db_layoutcampos select 13757 ,138 ,'cnpj_origem_destino' ,'CNPJ ORGÃO ORIGEM/DESTINO' ,7 ,363 ,'' ,14 ,'f' ,'t' ,'e' ,'' ,0 FROM db_layoutcampos WHERE NOT EXISTS(SELECT 1 FROM db_layoutcampos WHERE db52_codigo = 13757) limit 1;

/**
 * DDL
 */
alter table rhpessoalmov add column rh02_horasdiarias int4  default 0;
alter table rhpessoalmov add column rh02_cendencia    char(1) NOT NULL default 'X';
alter table rhpessoalmov add column rh02_onus         char(1) NOT NULL default 'X';
alter table rhpessoalmov add column rh02_ressarcimento char(1) NOT NULL default 'X';
alter table rhpessoalmov add column rh02_datacedencia date  default null;
alter table rhpessoalmov add column rh02_cnpjcedencia varchar(20) default 0;
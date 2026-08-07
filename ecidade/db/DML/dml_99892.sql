
insert into db_sysarquivo values (3944, 'cgmestrangeiro', 'Informações para um CGM estrangeiro', 'z09', '2016-06-10', 'CGM Estrangeiro', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (4,3944);
insert into db_syscampo values(21907,'z09_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(21908,'z09_numcgm','int4','Código do CGM','0', 'Código do CGM',10,'f','f','f',1,'text','Código do CGM');
insert into db_syscampo values(21909,'z09_documento','varchar(30)','Documento','', 'Documento',30,'f','t','f',0,'text','Documento');
delete from db_sysarqcamp where codarq = 3944;
insert into db_sysarqcamp values(3944,21907,1,0);
insert into db_sysarqcamp values(3944,21908,2,0);
insert into db_sysarqcamp values(3944,21909,3,0);
delete from db_sysforkey where codarq = 3944 and referen = 0;
insert into db_sysforkey values(3944,21908,1,42,0);
delete from db_sysprikey where codarq = 3944;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3944,21907,1,21908);
insert into db_sysindices values(4358,'cgmestrangeiro_numcgm_in',3944,'0');
insert into db_syscadind values(4358,21908,1);
insert into db_syssequencia values(1000579, 'cgmestrangeiro_z09_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000579 where codarq = 3944 and codcam = 21907;

DROP TABLE IF EXISTS cgmestrangeiro CASCADE;
DROP SEQUENCE IF EXISTS cgmestrangeiro_z09_sequencial_seq;

CREATE SEQUENCE cgmestrangeiro_z09_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE cgmestrangeiro(
z09_sequencial  int4 NOT NULL default 0,
z09_numcgm      int4 NOT NULL default 0,
z09_documento   varchar(30) not null,
CONSTRAINT cgmestrangeiro_sequ_pk PRIMARY KEY (z09_sequencial));

ALTER TABLE cgmestrangeiro ADD CONSTRAINT cgmestrangeiro_numcgm_fk FOREIGN KEY (z09_numcgm) REFERENCES cgm;
CREATE INDEX cgmestrangeiro_numcgm_in ON cgmestrangeiro(z09_numcgm);

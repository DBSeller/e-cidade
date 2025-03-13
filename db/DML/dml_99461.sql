
select fc_executa_ddl('insert into db_sysarquivo values (3899, \'cadenderestadosistema\', \'Codigo do Estado em um sistema Externo\', \'db300\', \'2016-01-05\', \'Codigo do Estado Sistema Externo\', 0, \'f\', \'f\', \'f\', \'f\' );');
select fc_executa_ddl('insert into db_sysarqmod  values (7,3899);');
select fc_executa_ddl('insert into db_syscampo   values(21691,\'db300_sequencial\',\'int4\',\'Código pk\',\'0\', \'Código\',10,\'f\',\'f\',\'f\',1,\'text\',\'Código\');');
select fc_executa_ddl('insert into db_syscampo   values(21692,\'db300_db_sistemaexterno\',\'int4\',\'Código do sistema externo\',\'0\', \'Tipo Sistema\',10,\'f\',\'f\',\'f\',1,\'text\',\'Tipo Sistema\');');
select fc_executa_ddl('insert into db_syscampo   values(21693,\'db300_cadenderestado\',\'int4\',\'Estado\',\'0\', \'Estado\',10,\'f\',\'f\',\'f\',1,\'text\',\'Estado\');');
select fc_executa_ddl('insert into db_syscampo   values(21694,\'db300_codigo\',\'varchar(50)\',\'Código real no sistema externo\',\'\', \'Código no sistema externo\',50,\'f\',\'t\',\'f\',0,\'text\',\'Código no sistema externo\');');
select fc_executa_ddl('insert into db_sysarqcamp values(3899,21691,1,0);');
select fc_executa_ddl('insert into db_sysarqcamp values(3899,21692,2,0);');
select fc_executa_ddl('insert into db_sysarqcamp values(3899,21693,3,0);');
select fc_executa_ddl('insert into db_sysarqcamp values(3899,21694,4,0);');
select fc_executa_ddl('insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3899,21691,1,21691);');
select fc_executa_ddl('insert into db_sysforkey  values(3899,21692,1,3291,0);');
select fc_executa_ddl('insert into db_sysforkey  values(3899,21693,1,2780,0);');
select fc_executa_ddl('insert into db_sysindices values(4317,\'cadenderestadosistema_db_sistemaexterno_in\',3899,\'0\');');
select fc_executa_ddl('insert into db_syscadind  values(4317,21692,1);');
select fc_executa_ddl('insert into db_sysindices values(4318,\'cadenderestadosistema_cadenderestado_in\',3899,\'0\');');
select fc_executa_ddl('insert into db_syscadind  values(4318,21693,1);');
select fc_executa_ddl('insert into db_syssequencia values(1000544, \'cadenderestadosistema_db300_sequencial_seq\', 1, 1, 9223372036854775807, 1, 1);');

select fc_executa_ddl('insert into db_sistemaexterno values(8, \'DNE\');');

select fc_executa_ddl('CREATE SEQUENCE cadenderestadosistema_db300_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;');
select fc_executa_ddl('CREATE TABLE cadenderestadosistema(
                         db300_sequencial    int4 NOT NULL default 0,
                         db300_db_sistemaexterno   int4 NOT NULL default 0,
                         db300_cadenderestado    int4 NOT NULL default 0,
                         db300_codigo    varchar(50) ,
                         CONSTRAINT cadenderestadosistema_sequ_pk PRIMARY KEY (db300_sequencial));
                      ');

select fc_executa_ddl('ALTER TABLE cadenderestadosistema ADD CONSTRAINT cadenderestadosistema_sistemaexterno_fk FOREIGN KEY (db300_db_sistemaexterno) REFERENCES db_sistemaexterno;');
select fc_executa_ddl('ALTER TABLE cadenderestadosistema ADD CONSTRAINT cadenderestadosistema_cadenderestado_fk FOREIGN KEY (db300_cadenderestado) REFERENCES cadenderestado;');
select fc_executa_ddl('CREATE  INDEX cadenderestadosistema_cadenderestado_in ON cadenderestadosistema(db300_cadenderestado);');
select fc_executa_ddl('CREATE  INDEX cadenderestadosistema_db_sistemaexterno_in ON cadenderestadosistema(db300_db_sistemaexterno);');

select fc_executa_ddl('create temp table w_estados (
                        dne    int,
                        estado varchar(255));
                      ');

select fc_executa_ddl('insert into w_estados values (1,  \'ACRE\');');
select fc_executa_ddl('insert into w_estados values (2,  \'ALAGOAS\');');
select fc_executa_ddl('insert into w_estados values (3,  \'AMAPÁ\');');
select fc_executa_ddl('insert into w_estados values (4,  \'AMAZONAS\');');
select fc_executa_ddl('insert into w_estados values (5,  \'BAHIA\');');
select fc_executa_ddl('insert into w_estados values (6,  \'CEARÁ\');');
select fc_executa_ddl('insert into w_estados values (7,  \'DISTRITO FEDERAL\');');
select fc_executa_ddl('insert into w_estados values (8,  \'ESPÍRITO SANTO\');');
select fc_executa_ddl('insert into w_estados values (9,  \'RORAIMA\');');
select fc_executa_ddl('insert into w_estados values (10, \'GOIÁS\');');
select fc_executa_ddl('insert into w_estados values (11, \'MARANHÃO\');');
select fc_executa_ddl('insert into w_estados values (12, \'MATO GROSSO\');');
select fc_executa_ddl('insert into w_estados values (13, \'MATO GROSSO DO SUL\');');
select fc_executa_ddl('insert into w_estados values (14, \'MINAS GERAIS\');');
select fc_executa_ddl('insert into w_estados values (15, \'PARÁ\');');
select fc_executa_ddl('insert into w_estados values (16, \'PARAÍBA\');');
select fc_executa_ddl('insert into w_estados values (17, \'PARANÁ\');');
select fc_executa_ddl('insert into w_estados values (18, \'PERNAMBUCO\');');
select fc_executa_ddl('insert into w_estados values (19, \'PIAUÍ\');');
select fc_executa_ddl('insert into w_estados values (20, \'RIO DE JANEIRO\');');
select fc_executa_ddl('insert into w_estados values (21, \'RIO GRANDE DO NORTE\');');
select fc_executa_ddl('insert into w_estados values (22, \'RIO GRANDE DO SUL\');');
select fc_executa_ddl('insert into w_estados values (23, \'RONDÔNIA\');');
select fc_executa_ddl('insert into w_estados values (24, \'TOCANTINS\');');
select fc_executa_ddl('insert into w_estados values (25, \'SANTA CATARINA\');');
select fc_executa_ddl('insert into w_estados values (26, \'SÃO PAULO\');');
select fc_executa_ddl('insert into w_estados values (27, \'SERGIPE\');');

select fc_executa_ddl('insert into cadenderestadosistema
                       select nextval(\'cadenderestadosistema_db300_sequencial_seq\'), 8, db71_sequencial, dne
                         from w_estados
                        inner join cadenderestado         on fc_remove_acentos(trim(estado)) = fc_remove_acentos(trim(db71_descricao))
                        left  join cadenderestadosistema  on db300_cadenderestado = db71_sequencial
                                                         and db300_codigo = dne::varchar
                        where db300_sequencial is null;
                      ');

insert into db_syscampo values(22090,'ed01_atividadeescolar','bool','Profissional possui alguma atividade escolar sem ser um regente. ','f', 'Atividade Escolar sem Regência',1,'f','f','f',5,'text','Atividade Escolar sem Regência');
insert into db_sysarqcamp values(1010095,22090,10,0);

alter table atividaderh add column ed01_atividadeescolar bool default 'f';
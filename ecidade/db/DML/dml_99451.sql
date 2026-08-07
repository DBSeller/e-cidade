select fc_executa_ddl('update avaliacaoperguntaopcao set db104_descricao = \'Alta Administrativa da AD\', db104_peso = \'3\' where db104_sequencial = 3000637;');
select fc_executa_ddl('update avaliacaoperguntaopcao set db104_peso = \'1\' where db104_sequencial = 3000638;');
select fc_executa_ddl('update avaliacaoperguntaopcao set db104_peso = \'2\' where db104_sequencial = 3000639;');
select fc_executa_ddl('update avaliacaoperguntaopcao set db104_descricao = \'Urgência / Emergência\', db104_peso = \'4\' where db104_sequencial = 3000640;');
select fc_executa_ddl('update avaliacaoperguntaopcao set db104_peso = \'5\' where db104_sequencial = 3000641;');
select fc_executa_ddl('update avaliacaoperguntaopcao set db104_peso = \'6\' where db104_sequencial = 3000642;');

alter table prontuarios alter column sd24_setorambulatorial drop default;
alter table prontuarios alter column sd24_setorambulatorial set not null;

delete from atendcadareamod where at26_id_item = 10191;
delete from db_modulos where id_item = 10191;
delete from db_itensmenu where id_item = 10191;
select fc_executa_ddl('
  insert into db_sysarquivo values (3898, \'db_pluginmodulos\', \'Plugin Módulos\', \'db152\', \'2016-01-05\', \'Plugin Módulos\', 0, \'f\', \'f\', \'f\', \'f\' );
  insert into db_sysarqmod values (7,3898);
  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21686 ,\'db152_sequencial\' ,\'int4\' ,\'Código sequencial da tabela\' ,\'\' ,\'Código\' ,10 ,\'false\' ,\'false\' ,\'false\' ,1 ,\'text\' ,\'Código\' );
  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3898 ,21686 ,1 ,0 );
  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21687 ,\'db152_db_plugin\' ,\'int4\' ,\'Código do Plugin\' ,\'\' ,\'Código do Plugin\' ,10 ,\'false\' ,\'false\' ,\'false\' ,1 ,\'text\' ,\'Código do Plugin\' );
  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3898 ,21687 ,2 ,0 );
  insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 21688 ,\'db152_db_modulo\' ,\'int4\' ,\'Código do Módulo\' ,\'\' ,\'Código do Módulo\' ,10 ,\'false\' ,\'false\' ,\'false\' ,1 ,\'text\' ,\'Código do Módulo\' );
  insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 3898 ,21688 ,3 ,0 );
  insert into db_syssequencia values(1000543, \'db_pluginmodulos_db152_sequencial_seq\', 1, 1, 9223372036854775807, 1, 1);
  update db_sysarqcamp set codsequencia = 1000543 where codarq = 3898 and codcam = 21686;
  insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3898,21686,1,21686);
  insert into db_sysforkey values(3898,21687,1,3672,0);
  insert into db_sysforkey values(3898,21688,1,168,0);
  insert into db_sysindices values(4315,\'db_pluginmodulos_db152_db_plugin_in\',3898,\'0\');
  insert into db_syscadind values(4315,21687,1);
  insert into db_sysindices values(4316,\'db_pluginmodulos_db152_db_modulo_in\',3898,\'0\');
  insert into db_syscadind values(4316,21688,1);
');

select fc_executa_ddl('
  CREATE SEQUENCE atendcadareamod_at26_sequencia_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 2000000
  CACHE 1;
');

select fc_executa_ddl('select setval(\'atendcadareamod_at26_sequencia_seq\', 2000000);');
  
select fc_executa_ddl('
  CREATE SEQUENCE db_pluginmodulos_db152_sequencial_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
  
  CREATE TABLE db_pluginmodulos(
  db152_sequencial    int4 NOT NULL  default nextval(\'db_pluginmodulos_db152_sequencial_seq\'),
  db152_db_plugin   int4 NOT NULL ,
  db152_db_modulo   int4 ,
  CONSTRAINT db_pluginmodulos_sequ_pk PRIMARY KEY (db152_sequencial));
  
  ALTER TABLE db_pluginmodulos
  ADD CONSTRAINT db_pluginmodulos_plugin_fk FOREIGN KEY (db152_db_plugin)
  REFERENCES db_plugin;
  
  ALTER TABLE db_pluginmodulos
  ADD CONSTRAINT db_pluginmodulos_modulo_fk FOREIGN KEY (db152_db_modulo)
  REFERENCES db_modulos;
  
  CREATE INDEX db_pluginmodulos_db152_db_modulo_in ON db_pluginmodulos(db152_db_modulo);
  CREATE INDEX db_pluginmodulos_db152_db_plugin_in ON db_pluginmodulos(db152_db_plugin);
');

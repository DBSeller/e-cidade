  -- avaliacaotipo
    insert into avaliacaotipo select 6 ,'Questionario DBSeller' where not exists (select 1 from avaliacaotipo where db100_sequencial = 6);
  --

  -- avaliacaoquestionariointerno
    insert into db_sysarquivo select 3964, 'avaliacaoquestionariointerno', 'Tabela de questionários gerados pela dbseller', 'db170', '2016-09-05', 'avaliacaoquestionariointerno', 0, 'f', 'f', 'f', 'f' where not exists(select 1 from db_sysarquivo where codarq = 3964);
    insert into db_sysarqmod  select 7,3964 where not exists(select 1 from db_sysarqmod where codarq = 3964);
    insert into db_syscampo   select 22024,'db170_sequencial','int4','Código sequencial','0', 'Cadastro sequencial',10,'f','f','f',1,'text','Cadastro sequencial' where not exists(select 1 from db_syscampo where codcam = 22024);
    insert into db_syscampo   select 22025,'db170_avaliacao','int4','Código da Avaliação','0', 'Código da Avaliação',10,'f','f','f',1,'text','Código da Avaliação' where not exists(select 1 from db_syscampo where codcam = 22025);
    insert into db_syscampo   select 22023,'db170_transmitido','bool','Determina se a avaliação foi transmitida.','f', 'Transmitido',1,'f','f','f',5,'text','Transmitido' where not exists(select 1 from db_syscampo where codcam = 22023);
    insert into db_syscampo   select 22022,'db170_ativo','bool','Determina se a avaliação foi transmitida.','f', 'Ativo',1,'f','f','f',5,'text','Ativo' where not exists(select 1 from db_syscampo where codcam = 22022);
    insert into db_syscampo   select 22045,'db170_codigo','int4','Codigo do Questionario Externo','0', 'Codigo do Questionario Externo',10,'t','f','f',1,'text','Codigo do Questionario Externo' where not exists(select 1 from db_syscampo where codcam = 22045);

    insert into db_syssequencia select 1000598, 'avaliacaoquestionariointerno_db170_sequencial_seq', 1, 1, 9223372036854775807, 1, 1 where not exists(select 1 from db_syssequencia where codsequencia = 1000598);

    insert into db_sysarqcamp select 3964,22024,1,1000598 where not exists(select 1 from db_sysarqcamp where codcam = 22024);
    insert into db_sysarqcamp select 3964,22025,2,0 where not exists(select 1 from db_sysarqcamp where codcam = 22025);
    insert into db_sysarqcamp select 3964,22023,3,0 where not exists(select 1 from db_sysarqcamp where codcam = 22023);
    insert into db_sysarqcamp select 3964,22022,4,0 where not exists(select 1 from db_sysarqcamp where codcam = 22022);
    insert into db_sysarqcamp select 3964,22045,5,0 where not exists(select 1 from db_sysarqcamp where codcam = 22045);

    insert into db_sysforkey select 3964,22025,1,2980,0 where not exists(select 1 from db_sysforkey where codcam = 22025);
    insert into db_sysprikey select 3964,22024,1,22024 where not exists(select 1 from db_sysprikey where codarq = 3964);
  --

  -- avaliacaoquestionariointernomenu

    insert into db_sysarquivo select 3965, 'avaliacaoquestionariointernomenu', 'Tabela de vinculo das avaliações geradas pela dbseller com os menus que serão exibidas', 'db171', '2016-09-05', 'avaliacaoquestionariointernomenu', 0, 'f', 'f', 'f', 'f' where not exists(select 1 from db_sysarquivo where codarq = 3965);
    insert into db_sysarqmod  select 7,3965 where not exists(select 1 from db_sysarqmod where codarq = 3965);
    insert into db_syscampo   select 22027,'db171_sequencial','int4','Código sequencial','0', 'Cadastro sequencial',10,'f','f','f',1,'text','Cadastro sequencial' where not exists(select 1 from db_syscampo where codcam = 22027);
    insert into db_syscampo   select 22028,'db171_questionario','int4','Código do Questionário interno','0', 'Questionário Interno',10,'f','f','f',1,'text','Questionário Interno' where not exists(select 1 from db_syscampo where codcam = 22028);
    insert into db_syscampo   select 22026,'db171_menu', 'int4', 'Código do Menu', '0', 'Código do Menu', 10, 'f','f','f',1,'text','Código do Menu' where not exists(select 1 from db_syscampo where codcam = 22026);
    insert into db_syscampo   select 22029,'db171_modulo','int4','Código do Módulo','0', 'Código do Módulo',10,'f','f','f',1,'text','Código do Módulo' where not exists(select 1 from db_syscampo where codcam = 22029);

    insert into db_syssequencia select 1000599, 'avaliacaoquestionariointernomenu_db171_sequencial_seq', 1, 1, 9223372036854775807, 1, 1 where not exists(select 1 from db_syssequencia where codsequencia = 1000599);

    insert into db_sysarqcamp select 3965,22027,1,1000599 where not exists(select 1 from db_sysarqcamp where codcam = 22027);
    insert into db_sysarqcamp select 3965,22028,2,0 where not exists(select 1 from db_sysarqcamp where codcam = 22028);
    insert into db_sysarqcamp select 3965,22026,3,0 where not exists(select 1 from db_sysarqcamp where codcam = 22026);
    insert into db_sysarqcamp select 3965,22029,4,0 where not exists(select 1 from db_sysarqcamp where codcam = 22029);

    insert into db_sysforkey select 3965,22028,1,3964,0 where not exists(select 1 from db_sysforkey where codcam = 22028);
    insert into db_sysprikey select 3965,22027,1,22027  where not exists(select 1 from db_sysprikey where codarq = 3965);
  --

  -- itens de menu

    insert into db_itensmenu select 10267 ,'Cadastro de Questionários' ,'Cadastro de Questionários' ,'' ,'1' ,'1' ,'Cadastros de Questionários' ,'false' where not exists(select 1 from db_itensmenu where id_item = 10267);
    insert into db_menu select 32 ,10267 ,473 ,1 where not exists(select 1 from db_menu where id_item_filho = 10267 AND modulo = 1);
    insert into db_itensmenu select 10268 ,'Inclusão' ,'Novo Questionário' ,'con4_questionario001.php' ,'1' ,'1' ,'Cadastro de questionários que serão enviados aos usuários do sistema' ,'false' where not exists(select 1 from db_itensmenu where id_item = 10268);
    insert into db_menu select 10267 ,10268 ,1 ,1 where not exists(select 1 from db_menu where id_item_filho = 10268 AND modulo = 1);
    insert into db_itensmenu select 10269 ,'Alteração' ,'Alteração de Questionário' ,'con4_questionario002.php' ,'1' ,'1' ,'Alteração de Questionários' ,'false' where not exists(select 1 from db_itensmenu where id_item = 10269);
    insert into db_menu select 10267 ,10269 ,2 ,1 where not exists(select 1 from db_menu where id_item_filho = 10269 AND modulo = 1);
  --


  select fc_executa_ddl('CREATE SEQUENCE configuracoes.avaliacaoquestionariointerno_db170_sequencial_seq
                          INCREMENT 1
                          MINVALUE 1
                          MAXVALUE 9223372036854775807
                          START 1
                          CACHE 1;');

  CREATE TABLE IF NOT EXISTS configuracoes.avaliacaoquestionariointerno(
    db170_sequencial    int4 NOT NULL default 0,
    db170_avaliacao   int4 NOT NULL default 0,
    db170_transmitido   bool  default 'f',
    db170_ativo   bool  default '1',
    db170_codigo    int4 default 0,
    CONSTRAINT avaliacaoquestionariointerno_sequ_pk PRIMARY KEY (db170_sequencial)
  );

  select fc_executa_ddl('ALTER TABLE configuracoes.avaliacaoquestionariointerno
                          ADD CONSTRAINT avaliacaoquestionariointerno_avaliacao_fk FOREIGN KEY (db170_avaliacao)
                          REFERENCES avaliacao;');
--

-- avaliacaoquestionariointernomenu
  select fc_executa_ddl('CREATE SEQUENCE configuracoes.avaliacaoquestionariointernomenu_db171_sequencial_seq
                          INCREMENT 1
                          MINVALUE 1
                          MAXVALUE 9223372036854775807
                          START 1
                          CACHE 1;');

  CREATE TABLE IF NOT EXISTS configuracoes.avaliacaoquestionariointernomenu(
    db171_sequencial    int4 NOT NULL default 0,
    db171_questionario    int4 NOT NULL default 0,
    db171_menu    int4 NOT NULL default 0,
    db171_modulo    int4 default 0,
    CONSTRAINT avaliacaoquestionariointernomenu_sequ_pk PRIMARY KEY (db171_sequencial)
  );

  select fc_executa_ddl('ALTER TABLE configuracoes.avaliacaoquestionariointernomenu
                          ADD CONSTRAINT avaliacaoquestionariointernomenu_questionario_fk FOREIGN KEY (db171_questionario)
                          REFERENCES avaliacaoquestionariointerno;');
--
-- Ajusta Permissoes
  select fc_grant_revoke('grant', 'plugin', 'select', '%', '%');
--
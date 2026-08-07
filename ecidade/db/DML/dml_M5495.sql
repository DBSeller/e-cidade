insert into db_sysarquivo values (3960, 'tipoassunto', 'Cadastro para os tipos de assunto da biblioteca', 'bi30', '2016-08-31', 'Tipo de Assunto', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (1008002,3960);
insert into db_syscampo values(22011,'bi30_sequencial','int4','PK','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(22012,'bi30_descricao','text','Descrição do assunto.','', 'Descrição',1,'f','t','f',0,'text','Descrição');
insert into db_sysarqcamp values(3960,22011,1,0);
insert into db_sysarqcamp values(3960,22012,2,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(3960,22011,1,22012);
insert into db_sysindices values(4378,'tipoassunto_descricao_in',3960,'0');
insert into db_syscadind values(4378,22012,1);
insert into db_syssequencia values(1000595, 'tipoassunto_bi30_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000595 where codarq = 3960 and codcam = 22011;
-- menu
insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
values (10282, 'Tipos de Assunto', 'Tipos de Assunto', '', '1', 1, 'Cadastro de Tipos de Assunto', 't'),
       (10283, 'Inclusão', 'Inclusão de Tipos de Assunto', 'bib1_tipoassunto001.php', '1', 1, 'Inclusão de Tipos de Assunto', 't'),
       (10284, 'Alteração', 'Alteração de Tipos de Assunto', 'bib1_tipoassunto002.php', '1', 1, 'Alteração de Tipos de Assunto', 't'),
       (10285, 'Exclusão', 'Exclusão de Tipos de Assunto', 'bib1_tipoassunto003.php', '1', 1, 'Exclusão de Tipos de Assunto', 't');

insert into db_menu (id_item, id_item_filho, menusequencia, modulo)
values (3470, 10282, 1, 1100625),
       (10282, 10283, 1, 1100625),
       (10282, 10284, 1, 1100625),
       (10282, 10285, 1, 1100625);

insert into db_processa (codarq, id_item)
values (3960, 10285),
       (3960, 10282),
       (3960, 10283),
       (3960, 10284);

insert into db_arquivos
values (5858, 'bib1_tipoassunto001.php', 'Inclusão: Cadastro para os tipos de assunto da biblioteca'),
       (5860, 'bib1_tipoassunto003.php', 'Inclusão: Cadastro para os tipos de assunto da biblioteca'),
       (5861, 'db_func_tipoassunto.php', 'Arquivo com os campos para a função da tabela : Tipos de Assunto'),
       (5862, 'func_tipoassunto.php', 'Função de consulta aos dados da tabela : Tipos de Assunto'),
       (5863, 'db_frmtipoassunto.php', 'Formulario utilizado para a tabela : Tipos de Assunto'),
       (5859, 'bib1_tipoassunto002.php', 'Inclusão: Cadastro para os tipos de assunto da biblioteca');

insert into db_itensfilho
values (10285, 5860),
       (10283, 5858),
       (10284, 5859),
       (10283, 5861),
       (10284, 5861),
       (10285, 5861),
       (10283, 5862),
       (10284, 5862),
       (10285, 5862),
       (10283, 5863),
       (10284, 5863),
       (10285, 5863);

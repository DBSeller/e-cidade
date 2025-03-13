insert into db_itensmenu select 8843 ,'Cadastro de Organograma' ,'Cadastro de Organograma' ,'', '1', '1', 'Cadastro de Organograma.', 'true' where not exists (select 1 from db_itensmenu where id_item = 8843);
insert into db_itensmenu select 8847 ,'Inclusão' ,'Inclusão do Organograma' ,'con1_organograma001.php', '1', '1', 'Tela de Inclusão do primeiro elemento do organograma.', 'true' where not exists (select 1 from db_itensmenu where id_item = 8847);
insert into db_itensmenu select 8848 ,'Alteração' ,'Alteração do Organograma' ,'con1_organograma002.php', '1', '1', 'Tela de alteração do organograma.', 'true' where not exists (select 1 from db_itensmenu where id_item = 8848);
insert into db_itensmenu select 8849 ,'Relatório de Organograma' ,'Relatório de Organograma' ,'con2_organograma.php', '1', '1', 'Tela de alteração do organograma.', 'true' where not exists (select 1 from db_itensmenu where id_item = 8849);
insert into db_itensmenu select 8871 ,'Configurações Gerais' ,'Configurações Gerais' ,'con4_configuracoesgerais.php', '1', '1', 'Configurações Gerais.', 'true' where not exists (select 1 from db_itensmenu where id_item = 8871);

update db_itensmenu set id_item = 8843 , descricao = 'Cadastro de Organograma' , help = 'Cadastro de Organograma' , itemativo = '1' , manutencao = '1' , desctec = 'Cadastro de Organograma' , libcliente = 'true' where id_item = 8843;
update db_itensmenu set id_item = 8847 , descricao = 'Inclusão' , help = 'Inclusão do Organograma' , funcao = 'con1_organograma001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Tela de Inclusão do primeiro elemento do organograma.' , libcliente = 'true' where id_item = 8847;
update db_itensmenu set id_item = 8848 , descricao = 'Alteração' , help = 'Alteração do Organograma' , funcao = 'con1_organograma002.php' , itemativo = '1' , manutencao = '1' , desctec = 'Tela de alteração do organograma' , libcliente = 'true' where id_item = 8848;
update db_itensmenu set id_item = 8849 , descricao = 'Relatório de Organograma' , help = 'Relatório de Organograma' , funcao = 'con2_organograma.php' , itemativo = '1' , manutencao = '1' , desctec = 'Tela com a imagem do organograma' , libcliente = 'true' where id_item = 8849;
update db_itensmenu set id_item = 8871 , descricao = 'Configurações Gerais' , help = 'Configurações Gerais' , funcao = 'con4_configuracoesgerais.php' , itemativo = '1' , manutencao = '1' , desctec = 'Configurações Gerais' , libcliente = 'true' where id_item = 8871;

delete from db_menu where id_item_filho = 8848 AND modulo = 1;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8843 ,8848 ,2 ,1 );
delete from db_menu where id_item_filho = 8847 AND modulo = 1;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8843 ,8847 ,3 ,1 );
delete from db_menu where id_item_filho = 8848 AND modulo = 1;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8843 ,8848 ,4 ,1 );
delete from db_menu where id_item_filho = 8849 AND modulo = 1;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,8849 ,457 ,1 );
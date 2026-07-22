select fc_executa_ddl('insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10252 ,\'Escolas da Rede\' ,\'Atestado para alunos da Rede\' ,\'\' ,\'1\' ,\'1\' ,\'Gera um atestado para alunos da Rede.\' ,\'true\' )');
select fc_executa_ddl('delete from db_menu where id_item_filho = 10252 AND modulo = 1100747');
select fc_executa_ddl('insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1101103 ,10252 ,3 ,1100747 )');
select fc_executa_ddl('insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10253 ,\'Outras Escolas\' ,\'Atestado para alunos de fora da Rede.\' ,\'edu4_atestadovagafora001.php\' ,\'1\' ,\'1\' ,\'Gera um atestado para alunos de fora da Rede.\' ,\'true\' )');
select fc_executa_ddl('delete from db_menu where id_item_filho = 10253 AND modulo = 1100747');
select fc_executa_ddl('insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1101103 ,10253 ,4 ,1100747 )');

select fc_executa_ddl('delete from db_menu where id_item_filho = 6981 AND modulo = 1100747');
select fc_executa_ddl('insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10252 ,6981 ,1 ,1100747 )');

select fc_executa_ddl('delete from db_menu where id_item_filho = 6982 AND modulo = 1100747');
select fc_executa_ddl('insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10252 ,6982 ,2 ,1100747 )');
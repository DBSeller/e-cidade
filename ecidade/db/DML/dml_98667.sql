--Exclui vínculo do menu de consulta ao cadastro do servidor do módulo pessoal com módulo RH
delete from db_menu where id_item_filho = 2464 AND modulo = 2323;

--Insere novo menu de consulta ao cadastro do servidor no módulo RH
select fc_executa_ddl('insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10155 ,''Cadastro de Servidores'' ,''Cadastro de Servidores'' ,''pes3_conspessoal001.php?modulo=rh'' ,''1'' ,''1'' ,''Novo menu para o RH que pesquisa informações cadastradas no departamento pessoal.'' ,''true'' );');
select fc_executa_ddl('insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 31 ,10155 ,1 ,2323 );');
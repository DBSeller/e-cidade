
insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
     values ( 10246 ,'Manutenção de Licitações Enviadas' ,'Manutenção de Licitações Enviadas' ,'lic4_manutencaolicitacoesenviadas001.php' ,'1' ,'1' ,'Manutenção de Licitações Enviadas' ,'true' );
delete from db_menu where id_item_filho = 10246 AND modulo = 381;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10212 ,10246 ,3 ,381 );

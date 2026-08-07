insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
     select 10167 ,'Excluir Crédito' ,'Excluir Crédito' ,'arr4_excluiCredito001.php' ,'1' ,'1' ,'Exclusão de Crédito' ,'true'
      where not exists (select 1
                          from db_itensmenu
                         where id_item = 10167 );

insert into db_menu ( id_item, id_item_filho, menusequencia, modulo )
     select 9625, 10167, 4, 1985522
      where not exists (select  1
                          from db_menu
                         where id_item       = 9625
                           and id_item_filho = 10167);
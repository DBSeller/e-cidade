<?php

use Classes\PostgresMigration;

class M18002Menus extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
        values ( 228554 ,'Portal Compras Públicas' ,'Portal Compras Públicas' ,'' ,'1' ,'1' ,'Portal Compras Públicas' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) 
        values ( 1818 ,228554 ,138 ,381 );
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
        values ( 228570 ,'Integração' ,'Integração' ,'lic4_integracaocompraspublicas.php' ,'1' ,'1' ,'Integração com Portal de Compras Públicas' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) 
        values ( 228554 ,228570 ,1 ,381 );
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
        values ( 228571 ,'Configuração' ,'Configuração' ,'lic4_configuracaocaompraspublicas.php' ,'1' ,'1' ,'Configuração para identificar o comprador no Portal do Compras Públicas' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) 
        values ( 228554 ,228571 ,2 ,381 );
SQL
    );
    } 
    
    public function down()
    {
        $this->execute(<<<SQL
         delete 
           from db_menu 
          where id_item_filho = 228554 
            AND modulo = 381;
         delete 
           from db_menu 
          where id_item_filho = 228570 
            AND modulo = 381;
         delete 
           from db_menu 
          where id_item_filho = 228571 
            AND modulo = 381;   
         delete 
           from db_itensmenu 
          where id_item in (228554, 228570, 228571);
SQL
        );
    }
}

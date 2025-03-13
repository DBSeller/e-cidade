<?php

use Classes\PostgresMigration;

class M10439GuiaItbidbpref extends PostgresMigration
{
    public function up()
    {

        $this->execute(
            <<<SQL_UP
  
  insert into db_itensmenu 
       select 10516, 'Guia ITBI PAGA', 'Guia ITBI PAGA', 'itbi_guiapaga.php', 1,1,'Guia ITBI PAGA', true;
       
  insert into db_menu      
       select 5469, 10516 , 4, 5457; 
       
    insert into db_permissao 
         select 325,  
                10516, 
                1, 
                (select cast(extract(year from current_date) as int)), 
                (select cast(codigo as int) from db_config where prefeitura is true limit 1),
                5457;

    update db_itensmenu set libcliente = true where  id_item = 5469;
    
    insert into db_itensmenu 
         select 10517, 
                'ITBI PAGA', 
                'ITBI PAGA', 
                'pre4_mensagens001.php?Cab=itbipaga_cab&Rod=itbipaga_rod', 
                1,
                1,
                'ITBI PAGA', 
                true;
                                    
    insert into db_menu 
         select 411, 10517 , 15, 394;       
    
    insert into db_confmensagem 
         select 'itbipaga_cab' , 
                '<font style="font-size: 15px;" color="Navy" face="Arial, Helvetica, sans-serif">Digite o Número da Guia PAGA no campo abaixo</font>' , 
                null, 
                (select cast(codigo as int) from db_config where prefeitura is true limit 1);

    insert into db_permissao 
          select 245, 5469, 1, ( select cast(extract(year from current_date) as int)), (select cast(codigo as int) 
            from db_config 
           where prefeitura is true limit 1), 5457;
SQL_UP

        );
    }

    public function down()
    {

        $this->execute(
            <<<SQL_DOWN
delete from db_permissao where id_item = 5469 and id_modulo = 5457 and id_usuario = 245;
delete from db_permissao where id_item = 10516 and id_modulo = 5457 and id_usuario = 325;
delete from db_confmensagem where cod = 'itbipaga_cab';
delete from db_menu where id_item_filho in (10516, 10517);
delete from db_itensmenu where id_item in (10516, 10517);

SQL_DOWN

        );
    }
}

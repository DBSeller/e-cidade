<?php

use Classes\PostgresMigration;

class M14900CriacaoMenuImportacaoPlanilhaMsc extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
  values ( 228209 ,'Importação do Saldo Inicial' ,'Importação do saldo inicial originado de um sistema externo.' ,'con4_importacaosaldoinicialmsc001.php' ,'1' ,'1' ,'Importação do saldo inicial originado de um sistema externo.' ,'false' );

delete from db_menu where id_item_filho = 228209 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10512 ,228209 ,2 ,209 );

SQL_UP
);
    }



    public function down()
    {

        $this->execute(<<<SQL_DOWN

delete from db_menu where id_item_filho = 228209 AND modulo = 209;
delete from db_itensmenu where id_item = 228209;

SQL_DOWN
        );
    }
}

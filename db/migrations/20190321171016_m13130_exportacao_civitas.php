<?php

use Classes\PostgresMigration;

class M13130ExportacaoCivitas extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            insert into db_itensmenu (id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
                values ( 228104 ,'Civitas' ,'Civitas' ,'' ,'1' ,'1' ,'Civitas' ,'true' ),
                       ( 228105 ,'Exportação' ,'Exportação' ,'cad4_exportacaocivitas004.php' ,'1' ,'1' ,'Exportação do arquivo.' ,'true' );
            
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) 
                values ( 32 ,228104 ,511 ,578 ),
                       ( 228104 ,228105 ,1 ,578 );
        ");
    }

    public function down()
    {
        $this->execute("
            delete from db_menu where id_item_filho in (228104, 228105) AND modulo = 578;
            delete from db_itensmenu where id_item in (228104, 228105);
        ");
    }

}

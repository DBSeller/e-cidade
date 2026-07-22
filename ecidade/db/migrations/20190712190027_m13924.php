<?php

use Classes\PostgresMigration;

class M13924 extends PostgresMigration
{
    public function up()
    {
        $this->execute("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
            values ( 228148 ,'Manutenção nos Horários da Regência' ,'Manutenção nos Horários da Regência' ,'edu1_manutencao_horarios_regencia.php' ,'1' ,'1' ,'Manutenção nos Horários da Regência' ,'false' );
            
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1100873 ,228148 ,8 ,1100747 );
        ");
    }

    public function down()
    {
        $this->execute("
            delete from db_menu where id_item_filho = 228148 AND modulo = 1100747;
            delete from db_itensmenu where id_item = 228148;
        ");
    }
}

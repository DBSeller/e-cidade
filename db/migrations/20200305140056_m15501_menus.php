<?php

use Classes\PostgresMigration;

class M15501Menus extends PostgresMigration
{
    public function up()
    {
        $this->execute("
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
        values ( 228239 ,'Base - Definir Procedimento por Disciplina' ,'Base - Definir Procedimento por Disciplina' ,'edu1_base_procedimento001.php' ,'1' ,'1' ,'Possibilita informar um procedimento de avaliação para cada etapa e disciplina da base currícular.' ,'true' ),
               ( 228240 ,'Procedimento por Área de Conhecimento' ,'Procedimento por Área de Conhecimento' ,'edu1_area_procedimento001.php' ,'1' ,'1' ,'Configura um procedimento por área de conhecimento' ,'false' );
        ");
        $this->execute("
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
        values ( 1100865 ,228239 ,8 ,1100747 ),
               ( 1100865 ,228240 ,9 ,1100747 );
        ");
    }

    public function down()
    {
        $this->execute("
        delete from db_menu where id_item_filho in (228239, 228240);
        delete from db_itensmenu where id_item in (228239, 228240);
        ");
    }
}

<?php

use Classes\PostgresMigration;

class M10183CargaDadosMenu extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP
       insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
          values ( 10586 ,'Trabalhador Sem Vínculo' ,'Trabalhador Sem Vínculo' ,'con4_cargaformulariotermino001.php' ,'1' ,'1' ,'Carga de dados para o Trabalhador Sem Vínculo' ,'true' );
      insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10569 ,10586 ,1 ,10216 );

SQL_UP
);
    }

    public function down()
    {
        $this->execute(<<<SQL_DOWN
           
           DELETE FROM db_menu WHERE   id_item_filho = 10586;
           DELETE FROM db_itensmenu WHERE id_item = 10586;
SQL_DOWN
);
    }

}

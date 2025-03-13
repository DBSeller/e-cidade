<?php

use Classes\PostgresMigration;

class M18585MenuRelatorio extends PostgresMigration
{
    public function up() 
    {
        $this->upDicionario();
        
    }

    public function down()
    {
        
        $this->downDicionario();
       
    }
    
    public function upDicionario() 
    {
        $this->execute(<<<SQL

           insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228533 ,'Movimentação de Estoque(Regra Antiga)' ,'Movimentação de Estoque(Regra Antiga)' ,'mat2_movimentacaoestoqueregraantiga001.php' ,'1' ,'1' ,'Movimentação de Estoque na regra anterior a 31/05/2021' ,'true' );
           
           insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8787 ,228533 ,20 ,480 );               
SQL
);          
    }

    public function downDicionario() 
    {
        $this->execute(<<<SQL
        delete from db_menu where id_item_filho = 228533 AND modulo = 480;
        delete from db_itensmenu where id_item = 228533;
SQL
);          
    }

    
}

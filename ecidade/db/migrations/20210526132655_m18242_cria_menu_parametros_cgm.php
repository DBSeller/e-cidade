<?php

use Classes\PostgresMigration;

class M18242CriaMenuParametrosCgm extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
                VALUES ( 228510 ,'Parâmetros CGM' ,'Parâmetros CGM' ,'pro1_protparametrocgm.php' ,'1' ,'1' ,'Parâmetros de validação dos campos CGM.' ,'true' );
            DELETE FROM db_menu WHERE id_item_filho = 228510 AND modulo = 604;
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 8880 ,228510 ,7 ,604 );
SQL;
        
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            DELETE FROM db_itensmenu WHERE id_item = 228510;
            DELETE FROM db_menu WHERE id_item_filho = 228510 AND modulo = 604;
SQL;

        $this->execute($sql);
    }
}

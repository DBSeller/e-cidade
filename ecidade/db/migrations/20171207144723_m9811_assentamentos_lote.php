<?php

use Classes\PostgresMigration;

class M9811AssentamentosLote extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10481 ,'Lançamento de Autorização de HE em lote' ,'Lançamento de Autorização de HE em lote' ,'rec4_autorizacaohelote.php' ,'1' ,'1' ,'Menu para autorização de horas extras em lote.' ,'true' );
            DELETE FROM db_menu where id_item_filho = 10481 AND modulo = 2323;
            INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10384 ,10481 ,7 ,2323 );
SQL;

        $this->execute($sql);
    }
    
    public function down()
    {
        $sql = <<<SQL
            DELETE FROM db_menu where id_item_filho = 10481 AND modulo = 2323;
            DELETE FROM db_itensmenu WHERE id_item IN (10481);
SQL;

        $this->execute($sql);
    }
}

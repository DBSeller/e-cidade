<?php

use Classes\PostgresMigration;

class M16901CreditoExtraordinarioPorReducao extends PostgresMigration
{

    public function up()
    {

        $sSql = <<<SQL
insert into db_documentotemplatetipo (db80_sequencial, db80_descricao) select 58, 'CRÉDITO EXTRAORDINÁRIO POR REDUÇÃO';
SQL;
        
       $this->execute($sSql);

    }


    public function down()
    {

        $sSql = <<<SQL

delete from db_documentotemplatetipo where db80_sequencial = 58 and db80_descricao = 'CRÉDITO EXTRAORDINÁRIO POR REDUÇÃO';
SQL;
        
        $this->execute($sSql);
    }
    
}

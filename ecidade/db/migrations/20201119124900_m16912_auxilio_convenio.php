<?php

use Classes\PostgresMigration;

class M16912AuxilioConvenio extends PostgresMigration
{

    public function up()
    {

        $sSql = <<<SQL
insert into db_documentotemplatetipo (db80_sequencial, db80_descricao) select 59, 'AUXÍLIO A CONVÊNIOS';
SQL;
        
       $this->execute($sSql);

    }


    public function down()
    {

        $sSql = <<<SQL

delete from db_documentotemplatetipo where db80_sequencial = 59 and db80_descricao = 'AUXÍLIO A CONVÊNIOS';
SQL;
        
        $this->execute($sSql);
    }
    
}

<?php

use Classes\PostgresMigration;

class M18864AtualizaDataCadastroCgmVazia extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            UPDATE cgm SET z01_cadast = '2021-08-18' WHERE z01_cadast IS NULL AND z01_ultalt = '2021-08-18';
            UPDATE cgm SET z01_cadast = '2021-08-17' WHERE z01_cadast IS NULL AND z01_ultalt = '2021-08-17';
        ");
    }

    public function down()
    {
        
    }
}

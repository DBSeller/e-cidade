<?php

use Classes\PostgresMigration;

class M16273Correcoes extends PostgresMigration
{
    public function up()
    {
        $this->execute("
        update db_itensmenu
           set descricao = 'Anexo 3 - A',
               help = 'TCE/RO - Anexo 3 - A',
               desctec = 'TCE/RO - Anexo 3 - A'
         where id_item = 228226;
        ");
    }
    public function down()
    {
        $this->execute("
        update db_itensmenu
           set descricao = 'Anexo 3',
               help = 'TCE/RO - Anexo 3',
               desctec = 'TCE/RO - Anexo 3'
         where id_item = 228226;
        ");
    }
}

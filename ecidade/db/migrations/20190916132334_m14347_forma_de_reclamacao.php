<?php

use Classes\PostgresMigration;

class M14347FormaDeReclamacao extends PostgresMigration
{
    public function up()
    {
        $this->execute('insert into formareclamacao values(5, \'Alvará Expresso\', null, null);');
    }

    public function down()
    {
        $this->execute('delete from formareclamacao where db54_sequencial = 5;');
    }
}

<?php

use Classes\PostgresMigration;

class M12289UpdateDescricaoCbo extends PostgresMigration
{
    public function up()
    {
        $this->execute("UPDATE rhcbo set rh70_descr = 'VENDEDOR AMBULANTE' where rh70_estrutural = '524305' and rh70_sequencial = 580;");
    }

    public function down()
    {
        $this->execute("UPDATE rhcbo set rh70_descr = 'AMBULANTE' where rh70_estrutural = '524305' and rh70_sequencial = 580;");
    }
}

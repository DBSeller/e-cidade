<?php

use Classes\PostgresMigration;

class M12882AlteraTipoPoder extends PostgresMigration
{
    public function up()
    {
        $this->execute("delete from db_syscampodef where codcam = 17759");
    }

    public function down()
    {

    }
}

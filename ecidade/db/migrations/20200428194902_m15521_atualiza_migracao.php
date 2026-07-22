<?php

use Classes\PostgresMigration;

class M15521AtualizaMigracao extends PostgresMigration
{
    public function up()
    {
        $this->execute("insert into db_syscampo values(1011242,'ed164_amparado','bool','se o resultado esta amparado','f', 'amparo',1,'f','f','f',5,'text','amparo')");
        $this->execute("insert into db_sysarqcamp values(1010540,1011242,9,0)");
    }

    public function down()
    {
        $this->execute("delete from db_sysarqcamp where codcam = 1011242");
        $this->execute("delete from db_syscampo where codcam = 1011242");
    }
}

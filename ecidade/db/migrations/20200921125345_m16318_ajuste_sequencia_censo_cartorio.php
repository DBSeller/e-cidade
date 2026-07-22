<?php

use Classes\PostgresMigration;

class M16318AjusteSequenciaCensoCartorio extends PostgresMigration
{


    public function up()
    {
     $this->execute("select setval('censocartorio_ed291_i_codigo_seq', (select max (ed291_i_codigo) from censocartorio));");
    }
    public function down()
    {

    }
}

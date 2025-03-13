<?php

use Classes\PostgresMigration;

class M13207 extends PostgresMigration
{
    public function up()
    {
        $this->execute("update esocialversaoformulario set rh211_versao ='2.4' where rh211_avaliacao = 4000103;");
    }

    public function down()
    {

    }
}

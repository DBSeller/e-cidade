<?php

use Classes\PostgresMigration;

class M13583AlteraEscolaDiretor extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_syscampo set valorinicial = null where nomecam = 'ed254_i_atolegal';");
    }

    public function down()
    {
        $this->execute("update db_syscampo set valorinicial = 0 where nomecam = 'ed254_i_atolegal';");
    }
}

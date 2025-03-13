<?php

use Classes\PostgresMigration;

class M13105AjusteSequenceSanitario extends PostgresMigration
{
    public function up()
    {
        $this->execute("select setval('sanitario_y80_codsani_seq', (select max(y80_codsani) from sanitario))");
    }

    public function down() {}
}

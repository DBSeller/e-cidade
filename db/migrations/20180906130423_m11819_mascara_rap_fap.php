<?php

use Classes\PostgresMigration;

class M11819MascaraRapFap extends PostgresMigration
{
    public function up()
    {
        $sql = "UPDATE avaliacaopergunta SET db103_tipo = 1 WHERE db103_sequencial IN (3000922, 3000923)";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "UPDATE avaliacaopergunta SET db103_tipo = 8 WHERE db103_sequencial IN (3000922, 3000923)";
        $this->execute($sql);
    }
}

<?php

use Classes\PostgresMigration;

class M5515AjustaSiglasAcervo extends PostgresMigration
{
    public function up()
    {
        $sql = "UPDATE db_syscampo SET rotulo = 'ISBN' WHERE codcam = 1008116;";
        $sql .= "UPDATE db_syscampo SET rotulo = 'Classificação CDD' WHERE codcam = 1008115;";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "UPDATE db_syscampo SET rotulo = 'I.S.B.N' WHERE codcam = 1008116;";
        $sql .= "UPDATE db_syscampo SET rotulo = 'Classificação C.D.D' WHERE codcam = 1008115;";

        $this->execute($sql);
    }
}

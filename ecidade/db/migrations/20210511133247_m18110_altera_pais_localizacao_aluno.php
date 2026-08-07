<?php

use Classes\PostgresMigration;

class M18110AlteraPaisLocalizacaoAluno extends PostgresMigration
{

    public function up()
    {
        $sql = "update aluno set ed47_localizacaodiferenciada = 7, ed47_paisresidencia = 10
                where ed47_localizacaodiferenciada isnull and ed47_paisresidencia isnull";
        $this->execute($sql);
    }

    public function down()
    {

    }

}

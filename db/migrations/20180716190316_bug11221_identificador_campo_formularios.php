<?php

use Classes\PostgresMigration;

class Bug11221IdentificadorCampoFormularios extends PostgresMigration
{

    public function up()
    {
        $this->execute("update avaliacaopergunta set db103_identificador = replace(db103_identificador, '-', '_');");
    }

    public function down()
    {

    }
}

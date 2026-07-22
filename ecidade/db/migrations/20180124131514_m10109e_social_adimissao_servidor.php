<?php

use Classes\PostgresMigration;

class M10109eSocialAdimissaoServidor extends PostgresMigration
{
    public function up()
    {
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = db104_valorresposta::int - 1 where db104_sequencial in (3004028, 3004029, 3004030, 3004031);");
    }

    public function down()
    {
        $this->execute("update avaliacaoperguntaopcao set db104_valorresposta = db104_valorresposta::int + 1 where db104_sequencial in (3004028, 3004029, 3004030, 3004031);");
    }
}

<?php

use Classes\PostgresMigration;

class M18279TipoDespachoMensagemPrefeitura extends PostgresMigration
{

    public function up()
    {
        $this->execute("INSERT INTO protocolo.tipodespacho  (p100_sequencial,p100_descricao)  VALUES  (1003,'Mensagem Prefeitura')");
    }

    public function down()
    {
        $this->execute("DELETE FROM protocolo.tipodespacho WHERE p100_sequencial = 1003");
    }
}

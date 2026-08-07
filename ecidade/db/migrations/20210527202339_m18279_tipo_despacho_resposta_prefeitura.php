<?php

use Classes\PostgresMigration;

class M18279TipoDespachoRespostaPrefeitura extends PostgresMigration
{

    public function up()
    {
        $this->execute("INSERT INTO protocolo.tipodespacho  (p100_sequencial,p100_descricao)  VALUES  (1002,'Resposta Prefeitura')");
    }

    public function down()
    {
        $this->execute("DELETE FROM protocolo.tipodespacho WHERE p100_sequencial = 1002");
    }
}

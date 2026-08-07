<?php

use Classes\PostgresMigration;

class InclusaoFormaReclamacaoPrimeiroAcesso extends PostgresMigration
{

    public function up(){
        $this->execute("INSERT INTO  ouvidoria.formareclamacao  (p42_sequencial,p42_descricao) VALUES (9,'PRIMEIRO ACESSO')");
    }

    public function down(){
        $this->execute("DELETE FROM ouvidoria.formareclamacao   WHERE p42_sequencial = 9");
    }
}


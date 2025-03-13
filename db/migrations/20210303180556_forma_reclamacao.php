<?php

use Classes\PostgresMigration;

class FormaReclamacao extends PostgresMigration
{
    public function up(){
        $this->execute("INSERT INTO  ouvidoria.formareclamacao  (p42_sequencial,p42_descricao) VALUES (8,'ACESSO A INFORMAÇÃO')");
    }

    public function down(){
        $this->execute("DELETE FROM ouvidoria.formareclamacao   WHERE p42_sequencial = 8");
    }
}


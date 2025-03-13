<?php

use Classes\PostgresMigration;

class M13204AtualizaSiglaModalidadeLicitacao extends PostgresMigration
{
    public function up() {

        $sqls = array(
            "UPDATE pctipocompratribunal SET l44_sigla = 'NSA' WHERE l44_sequencial = 21 AND l44_uf = 'RS';",
            "UPDATE pctipocompratribunal SET l44_sigla = 'DPV' WHERE l44_sequencial = 51 AND l44_uf = 'RS';",
            "INSERT INTO pctipocompratribunal (l44_sequencial, l44_codigotribunal, l44_descricao, l44_uf, l44_sigla) 
                  VALUES ( nextval('pctipocompratribunal_l44_sequencial_seq'),99,'Processo de Dispensa Eletrônica','RS','PDE');"
        );

        foreach($sqls as $sql) {
            $this->exec($sql);
        }

    }

    public function down() {

        $sqls = array(
            "UPDATE pctipocompratribunal SET l44_sigla = 'PRD' WHERE l44_sequencial = 21 AND l44_uf = 'RS';",
            "UPDATE pctipocompratribunal SET l44_sigla = 'PRD' WHERE l44_sequencial = 51 AND l44_uf = 'RS';",
            "DELETE FROM pctipocompratribunal WHERE l44_sigla = 'PDE' AND l44_uf = 'RS';"
        );

        foreach($sqls as $sql) {
            $this->exec($sql);
        }

    }

    private function exec($sql) {
        $this->execute($sql);
    }
}

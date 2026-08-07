<?php

use Classes\PostgresMigration;

class M13134GrupoSelecaoCalculoFolha extends PostgresMigration
{
    public function up()
    {
        $sql = "INSERT INTO gruposelecao VALUES ((SELECT max(rh122_sequencial)+1 FROM gruposelecao), 'Regra de Cálculo da Folha')";
        $this->exec($sql);
    }

    public function down()
    {
        $sqls = array(
            "DELETE FROM selecao WHERE r44_gruposelecao IN(SELECT rh122_sequencial FROM gruposelecao WHERE rh122_sequencial > 2 and rh122_sequencial = (SELECT max(rh122_sequencial) FROM gruposelecao));",
            "DELETE FROM gruposelecao WHERE rh122_sequencial > 2 and rh122_sequencial = (SELECT max(rh122_sequencial) FROM gruposelecao);",
        );

        foreach($sqls as $sql) {
            $this->exec($sql);
        }
    }

    private function exec($sql)
    {
        $this->execute($sql);
    }
}

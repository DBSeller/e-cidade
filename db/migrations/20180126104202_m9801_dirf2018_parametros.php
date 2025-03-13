<?php

use Classes\PostgresMigration;

class M9801Dirf2018Parametros extends PostgresMigration
{
    public function up()
    {
        $stmt = $this->query('SELECT * FROM rhdirfparametros WHERE rh132_anobase = 2017');
        $rows = $stmt->fetchAll();

        if (empty($rows)) {
            $sql = "INSERT INTO rhdirfparametros VALUES (nextval('rhdirfparametros_rh132_sequencial_seq'), 2017, 28559.70, 'Q84FV63')";
            $this->execute($sql);
        }
    }

    public function down()
    {
        $sSql = "DELETE FROM rhdirfparametros WHERE rh132_anobase = 2017 AND rh132_codigoarquivo = 'Q84FV63'";
        $this->execute($sSql);
    }
}

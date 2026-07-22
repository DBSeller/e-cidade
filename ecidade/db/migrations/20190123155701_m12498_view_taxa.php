<?php

use Classes\PostgresMigration;

class M12498ViewTaxa extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            CREATE VIEW cadastro.v_iptubasetaxa AS
            SELECT
                *
            FROM
                iptutaxanump as txn
            INNER JOIN
                iptucadtaxaexe as txexe on txexe.j08_iptucadtaxaexe = txn.j151_iptucadtaxaexe
            INNER JOIN
                iptutaxacalv as txcv ON txcv.j152_iptutaxanump = txn.j151_codigo;
        ");
    }

    public function down()
    {
        $this->execute("DROP VIEW cadastro.v_iptubasetaxa;");
    }
}

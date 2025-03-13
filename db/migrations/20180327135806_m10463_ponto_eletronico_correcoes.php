<?php

use Classes\PostgresMigration;

class M10463PontoEletronicoCorrecoes extends PostgresMigration
{
    function up()
    {
        $this->execute("
            INSERT INTO naturezatipoassentamento VALUES (9, 'Abono Falta');
            SELECT SETVAL('naturezatipoassentamento_rh159_sequencial_seq', (SELECT MAX(rh159_sequencial) FROM naturezatipoassentamento));
        ");
    }

    function down()
    {
        $this->execute("
            DELETE FROM naturezatipoassentamento WHERE rh159_sequencial = 9;
            SELECT SETVAL('naturezatipoassentamento_rh159_sequencial_seq', (SELECT MAX(rh159_sequencial) FROM naturezatipoassentamento));
        ");
    }
}

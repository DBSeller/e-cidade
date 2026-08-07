<?php

use Classes\PostgresMigration;

class M16995CriaSequencesFases extends PostgresMigration
{
    public function up()
    {
        $sqlExists = "SELECT EXISTS (SELECT FROM information_schema.tables WHERE  table_schema = 'plugins' AND table_name = 'fase'); ";
        $stmt = $this->query($sqlExists);
        $exists = $stmt->fetch()[0];
        if (!$exists) {
            return;
        }

        $sqlBuscaFases = "select mo04_codigo, mo04_desc from plugins.fase order by 1;";
        $stmt = $this->query($sqlBuscaFases);
        while ($fase = $stmt->fetch()) {
            $sqlUltimoSequencial = "select split_part(mo12_protocolo, '-', 2) as sequencia from plugins.basefase where mo12_fase = {$fase['mo04_codigo']} order by 1 desc limit 1; ";
            $stmt2 = $this->query($sqlUltimoSequencial);

            $sequence = "plugins.protocolo_fase{$fase['mo04_codigo']}_seq";
            $sqlSequence = "CREATE SEQUENCE IF NOT EXISTS {$sequence} MINVALUE 1 MAXVALUE 99999;";
            $this->execute($sqlSequence);

            $ultimoSequencial = $stmt2->fetch()['sequencia'];
            if (!is_null($ultimoSequencial)) {
                $sqlSetval = "SELECT setval('{$sequence}', {$ultimoSequencial})";
                $this->execute($sqlSetval);
            }
        }
    }

    public function down()
    {
        $sqlListSequences = "select * from information_schema.sequences where sequence_schema = 'plugins' and sequence_name ilike 'protocolo_fase%_seq';";
        $stmt = $this->query($sqlListSequences);
        while ($sequence = $stmt->fetch()) {
            $sqlDrop = "DROP SEQUENCE IF EXISTS plugins.{$sequence['sequence_name']};";
            $this->execute($sqlDrop);
        }
    }
}

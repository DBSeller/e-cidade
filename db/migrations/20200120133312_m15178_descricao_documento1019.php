<?php

use Classes\PostgresMigration;

class M15178DescricaoDocumento1019 extends PostgresMigration
{
    function up()
    {
        $sql = <<<SQL
update conhistdoc set c53_descr = 'ENCERRAMENTO DE CONTROLE DA EXECUÇÃO DA DESPESA' where c53_coddoc = 1019;
SQL;
        $this->execute($sql);

    }
}

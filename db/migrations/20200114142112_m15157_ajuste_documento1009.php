<?php

use Classes\PostgresMigration;

class M15157AjusteDocumento1009 extends PostgresMigration
{
    public function up()
    {


        $row = $this->fetchRow("select * from conhistdocregra where c92_conhistdoc = 1009");
        if (!empty($row)) {

        $this->execute(<<<SQL_UP

            delete from conhistdocregra where c92_conhistdoc = 1009;
            insert into conhistdocregra
                 values (nextval('conhistdocregra_c92_sequencial_seq'),
                         1009,
                         'REGRA DO DOCUMENTO 1009',
                         'select * from fc_doc_encerramento_2019(fc_getsession(\'DB_anousu\')::int, fc_getsession(\'DB_instit\')::int) where valor > 0;',
                         2019);

SQL_UP
);

        }

    }


    public function down()
    {

    }
}

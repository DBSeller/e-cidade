<?php

use Classes\PostgresMigration;

class M15086AcertoDocumento1009 extends PostgresMigration
{
    public function up()
    {

        $linha = $this->fetchRow("select * from conhistdoc where c53_coddoc = 1009;");

        if (!empty($linha)) {

            $this->execute(<<<SQL_up

    delete from conhistdocregra where c92_conhistdoc = 1009;
    insert into conhistdocregra
         values (nextval('conhistdocregra_c92_sequencial_seq'),
                 1009,
                 'DOCUMENTO 1009',
                 'select * from fc_doc_encerramento_2019(fc_getsession(\'DB_anousu\')::int, fc_getsession(\'DB_instit\')::int);',
                 2019);

SQL_up
            );
        }

    }

    public function down()
    {
    }
}

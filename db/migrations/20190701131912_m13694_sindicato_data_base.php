<?php

use Classes\PostgresMigration;

/**
 * Class M13694SindicatoDataBase
 */
class M13694SindicatoDataBase extends PostgresMigration
{
    /**
     *
     */
    public function up()
    {
        $sql = "
            ALTER TABLE pessoal.rhsindicato
                ADD rh116_mesdatabase INT;

            INSERT INTO db_syscampo
            VALUES (1010584, 'rh116_mesdatabase', 'int4', 'Mês relativo à data base da categoria profissional do trabalhador', '0',
                    'Mês da Data Base', 2, 't', 'f', 'f', 1, 'text', 'Mês da Data Base');

            INSERT INTO db_sysarqcamp
            VALUES (3481, 1010584, 5, 0);
        ";

        $this->execute($sql);
    }

    /**
     *
     */
    public function down()
    {
        $sql = "
            DELETE
            FROM db_sysarqcamp
            WHERE codcam = 1010584 AND
                  codarq = 3481 AND
                  seqarq = 5;

            DELETE
            FROM db_syscampo
            WHERE codcam = 1010584;

            ALTER TABLE pessoal.rhsindicato
                DROP COLUMN rh116_mesdatabase;
        ";

        $this->execute($sql);
    }
}

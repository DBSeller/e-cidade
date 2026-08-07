<?php

use Classes\PostgresMigration;

class M13694ServidorMovimentacaoRegimeJornadaTrabalho extends PostgresMigration
{
    public function up()
    {
        $sql = "
            ALTER TABLE pessoal.rhpessoalmov
                ADD rh02_regimejornadatrabalho INT DEFAULT 0;

            INSERT INTO db_syscampo
            VALUES (1010558,
                    'rh02_regimejornadatrabalho',
                    'int8',
                    'Regime da Jornada de Trabalho',
                    '0',
                    'Regime da Jornada de Trabalho',
                    8,
                    't',
                    'f',
                    'f',
                    1,
                    'text',
                    'Regime da Jornada de Trabalho');

            INSERT INTO db_sysarqcamp
            VALUES (1158, 1010558, 34, 0);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE
            FROM db_sysarqcamp
            WHERE codcam = 1010558 AND 
                  codarq = 1158 AND 
                  seqarq = 34;

            DELETE
            FROM db_syscampo
            WHERE codcam = 1010558;

            ALTER TABLE pessoal.rhpessoalmov
                DROP COLUMN rh02_regimejornadatrabalho;
        ";

        $this->execute($sql);
    }
}

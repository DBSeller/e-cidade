<?php

use Classes\PostgresMigration;

class M18053NovoAtributoComplementarLicitacao extends PostgresMigration
{
    public function up()
    { return false;
        $sql = <<<SQL
            INSERT INTO db_cadattdinamicoatributos
                VALUES(
                    nextval('db_cadattdinamicoatributos_db109_sequencial_seq'),
                    2,
                    null,
                    'Orçamento Sigiloso',
                    'f',
                    5,
                    'orcamentosigiloso',
                    'f',
                    't',
                    'f',
                    null
                );
SQL;

        $this->execute($sql);
    }

    public function down()
    { return false;
        $sql = <<<SQL
            DELETE FROM db_cadattdinamicoatributos WHERE db109_nome = 'orcamentosigiloso';
SQL;
        $this->execute($sql);
    }
}

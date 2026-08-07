<?php

use Classes\PostgresMigration;

class M18141AjusteMigrationNovoAtributoComplementarLicitacao extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            INSERT INTO db_cadattdinamicoatributos
                VALUES(
                    nextval('db_cadattdinamicoatributos_db109_sequencial_seq'),
                    (SELECT db17_cadattdinamico FROM db_cadattdinamicosysarquivo WHERE db17_sysarquivo = 1260),
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
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            DELETE FROM db_cadattdinamicoatributos WHERE db109_nome = 'orcamentosigiloso';
SQL
        );
    }
}

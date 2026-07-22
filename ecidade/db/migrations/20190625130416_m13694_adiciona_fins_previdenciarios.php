<?php

use Classes\PostgresMigration;

class M13694AdicionaFinsPrevidenciarios extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL

            alter table rhdepend add rh31_fins_previdenciarios bool not null default 'f';

            insert into
                db_syscampo
            values
                (
                    1010557,
                    'rh31_fins_previdenciarios',
                    'bool',
                    'Dependente para fins previdenciários',
                    'f',
                    'Dependente para fins previdenciários',
                    1,
                    'f',
                    'f',
                    'f',
                    5,
                    'text',
                    'Dependente para fins previdenciários'
                );

            insert into db_sysarqcamp values(1186,1010557,9,0);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

            alter table rhdepend drop rh31_fins_previdenciarios;

            delete from db_sysarqcamp where codcam = 1010557;
            delete from db_syscampo where codcam = 1010557;
SQL
        );
    }
}

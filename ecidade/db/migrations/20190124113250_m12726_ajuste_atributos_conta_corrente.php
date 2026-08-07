<?php

use Classes\PostgresMigration;

class M12726AjusteAtributosContaCorrente extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP
update conplanoinfocomplementar set c121_sql = 'select null' where trim(c121_sql) = '';

update conplanoinfocomplementar set c121_sql = c121_sql||' limit 1 '
 where c121_sequencial in (select c121_sequencial
                             from conplanoinfocomplementar
                            where c121_sql <> '' and c121_sql not ilike '%limit%');
SQL_UP
);

    }

    public function down()
    {

    }

}

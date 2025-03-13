<?php

use Classes\PostgresMigration;

class M15212CriacaoAtributoMatriz extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP
insert into conplanoinfocomplementar values (53, 'CF', 'Complemento da Fonte', 'select null', '', 'atributo_cf', null);
SQL_UP
);
    }

    public function down()
    {
        $this->execute(<<<SQL_DOWN
delete from conplanoinfocomplementar where c121_sequencial = 53;
SQL_DOWN
);
    }

}

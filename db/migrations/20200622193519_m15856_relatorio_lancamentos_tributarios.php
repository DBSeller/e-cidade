<?php

use Classes\PostgresMigration;

class M15856RelatorioLancamentosTributarios extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_sysindices values(1008584,'diverimportaold_numpre_in',3295,'0');
insert into db_syscadind values(1008584,18615,1);

create index if not exists diverimportaold_numpre_in on diverimportaold (dv13_numpre);
SQL
);
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_syscadind where codind = 1008584;
delete from db_sysindices where codind = 1008584;
SQL
);
    }
}

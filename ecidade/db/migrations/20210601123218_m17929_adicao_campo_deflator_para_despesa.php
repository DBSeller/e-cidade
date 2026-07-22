<?php

use Classes\PostgresMigration;

class M17929AdicaoCampoDeflatorParaDespesa extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_sysarqcamp values(1010707,1013219,9,0);
alter table planejamento.fatorcorrecaodespesa add column deflator boolean default false;
SQL
        );
    }
    public function down()
    {
        $this->execute(<<<SQL
delete from db_sysarqcamp where codarq =1010707 and codcam = 1013219;
alter table planejamento.fatorcorrecaodespesa drop column deflator;
SQL
        );

    }

}

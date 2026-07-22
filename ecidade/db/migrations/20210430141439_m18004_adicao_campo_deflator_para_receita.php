<?php

use Classes\PostgresMigration;

class M18004AdicaoCampoDeflatorParaReceita extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_syscampo values(1013219,'deflator','bool','Indica que o percentual é negativo','f', 'deflator',1,'f','f','f',5,'text','deflator');
insert into db_sysarqcamp values(1010722,1013219,9,0);

alter table planejamento.fatorcorrecaoreceita add column deflator boolean default false;

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codcam = 1013219;
            delete from db_syscampo where codcam = 1013219;

            alter table planejamento.fatorcorrecaoreceita drop column deflator;
SQL
        );

    }
}

<?php

use Classes\PostgresMigration;

class M18103CriaGrauRiscoIssquant extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_syscampo values(1013278,'q30_graurisco','char(1)','Informa o Grau de Risco da inscrição.','', 'Grau de Risco',1,'f','t','f',0,'text','Grau de Risco');
            insert into db_sysarqcamp values(47,1013278,8,0);

            alter table issquant add column q30_graurisco char(1);

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codcam = 1013278;
            delete from db_syscampo where codcam = 1013278;

            alter table issquant drop column q30_graurisco;
SQL
        );
    }
}

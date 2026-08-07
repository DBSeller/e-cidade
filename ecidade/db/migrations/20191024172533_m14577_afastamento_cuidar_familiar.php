<?php

use Classes\PostgresMigration;

class M14577AfastamentoCuidarFamiliar extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            insert into db_syscampo values(1010771,'r33_rubfamiliar','varchar(4)','Código da rubrica para o afastamento para cuidar de familiar.','0', 'Rubrica Cuidar de Familiar',10,'t','f','f',1,'text','Rubrica Cuidar de Familiar');
            insert into db_sysarqcamp values(561,1010771,22,0);

            alter table pessoal.inssirf add column r33_rubfamiliar varchar(4) default null;
            INSERT INTO situacaoafastamento VALUES(10, 'Licença para cuidar de familiar');

SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            delete from db_sysarqcamp where codcam = 1010771;
            delete from db_syscampo where codcam = 1010771;

            alter table pessoal.inssirf drop column r33_rubfamiliar;
            delete from situacaoafastamento where rh166_sequencial = 10;
SQL;

        $this->execute($sql);
    }

}

<?php

use Classes\PostgresMigration;

class M15633CgsDuplos extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
update db_syscampo set conteudo = 'varchar(15)', tamanho = 15 where codcam = 1008979;
update db_syscampo set conteudo = 'varchar(255)', tamanho = 255 where codcam = 1008980;

alter table cgs_undalt alter column z33_v_telcel type varchar(15);
alter table cgs_undalt alter column z33_v_email type varchar(255);
SQL;

        $this->execute($sql);
    }

    public function down()
    {
    }
}

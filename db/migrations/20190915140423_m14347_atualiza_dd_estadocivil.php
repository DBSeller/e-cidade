<?php

use Classes\PostgresMigration;

class M14347AtualizaDdEstadocivil extends PostgresMigration
{
    public function up()
    {

        $sql = <<<SQL
insert into db_syscampodef (codcam, defcampo, defdescr) values (240, '1', 'Solteiro');
insert into db_syscampodef (codcam, defcampo, defdescr) values (240, '2', 'Casado');
insert into db_syscampodef (codcam, defcampo, defdescr) values (240, '3', 'Viúvo');
insert into db_syscampodef (codcam, defcampo, defdescr) values (240, '4', 'Divorciado');
insert into db_syscampodef (codcam, defcampo, defdescr) values (240, '5', 'Separado Consensual');
insert into db_syscampodef (codcam, defcampo, defdescr) values (240, '6', 'Separado Judicial');
insert into db_syscampodef (codcam, defcampo, defdescr) values (240, '7', 'União Estavel');
SQL;
        $this->execute($sql);

    }

    public function down()
    {
        $sql = <<<SQL
        delete from db_syscampodef where codcam = 240;
SQL;
        $this->execute($sql);
    }
}

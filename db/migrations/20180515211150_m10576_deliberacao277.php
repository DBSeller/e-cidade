<?php

use Classes\PostgresMigration;

class M10576Deliberacao277 extends PostgresMigration
{

    public function up()
    {
        $this->execute(
            <<<SQL_UP

drop index if exists concilia_k68_contabancaria_in;
drop index if exists concilia_contabancaria_in;
create index concilia_k68_contabancaria_in on concilia (k68_contabancaria);
SQL_UP

        );
    }

    public function down()
    {
        $this->execute(
            <<<SQL_UP
drop index if exists concilia_k68_contabancaria_in;
SQL_UP

        );
    }
}

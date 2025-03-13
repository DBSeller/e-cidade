<?php

use Classes\PostgresMigration;

class M13107AjusteRecibopagaold extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
        create table w1307_recibopagaold as select * from recibopagaold;
        drop table recibopagaold;
        create table caixa.recibopagaold as select * from w1307_recibopagaold;
        drop table w1307_recibopagaold;
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
        create table w1307_recibopagaold as select * from caixa.recibopagaold;
        drop table caixa.recibopagaold;
        create table recibopagaold as select * from w1307_recibopagaold;
        drop table w1307_recibopagaold;
SQL;
        $this->execute($sql);
    }
}

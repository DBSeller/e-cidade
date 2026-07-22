<?php

use Classes\PostgresMigration;

class M13822AcertoSaldoContinuoPadrao extends PostgresMigration
{

    public function up()
    {

        $sql = <<<SQL
update conplano
set c60_saldocontinuo = (case
                           when         ( substr(c60_estrut, 1, 1)::integer in (1, 2, 7, 8) or
                                          substr(c60_estrut, 1, 2)::integer in (53, 63) )
                                and not substr(c60_estrut, 1, 5) ilike '82114%'
                                then true
                           when substr(c60_estrut, 1, 5) ilike '82114%' then false
                           else false
                         end)
where  c60_anousu >= 2019;
SQL;
        $this->execute($sql);

    }

    public function down()
    {
        $sql = <<<SQL
update conplano
set c60_saldocontinuo = null
where  c60_anousu >= 2019;

SQL;
        $this->execute($sql);

    }
}

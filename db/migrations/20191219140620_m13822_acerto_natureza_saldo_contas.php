<?php

use Classes\PostgresMigration;

class M13822AcertoNaturezaSaldoContas extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP

update conplano set c60_naturezasaldo = 1 where c60_anousu >= 2019 and substring(c60_estrut, 1, 1)::int = 5;
update conplano set c60_naturezasaldo = 2 where c60_anousu >= 2019 and substring(c60_estrut, 1, 1)::int = 6;
update conplano set c60_naturezasaldo = 1 where c60_anousu >= 2019 and substring(c60_estrut, 1, 4) = '6213';
update conplano set c60_naturezasaldo = 2 where c60_anousu >= 2019 and substring(c60_estrut, 1, length('52112'))     = '52112';
update conplano set c60_naturezasaldo = 2 where c60_anousu >= 2019 and substring(c60_estrut, 1, length('5212103'))   = '5212103';
update conplano set c60_naturezasaldo = 2 where c60_anousu >= 2019 and substring(c60_estrut, 1, length('5221309'))   = '5221309';
update conplano set c60_naturezasaldo = 2 where c60_anousu >= 2019 and substring(c60_estrut, 1, length('522190109')) = '522190109';
update conplano set c60_naturezasaldo = 2 where c60_anousu >= 2019 and substring(c60_estrut, 1, length('522190209')) = '522190209';
update conplano set c60_naturezasaldo = 2 where c60_anousu >= 2019 and substring(c60_estrut, 1, length('5221904'))   = '5221904';
update conplano set c60_naturezasaldo = 2 where c60_anousu >= 2019 and substring(c60_estrut, 1, length('522220109')) = '522220109';
update conplano set c60_naturezasaldo = 2 where c60_anousu >= 2019 and substring(c60_estrut, 1, length('522220209')) = '522220209';
update conplano set c60_naturezasaldo = 2 where c60_anousu >= 2019 and substring(c60_estrut, 1, length('522920103')) = '522920103';
update conplano set c60_naturezasaldo = 2 where c60_anousu >= 2019 and substring(c60_estrut, 1, length('522920104')) = '522920104';

SQL_UP
);
    }

    public function down()
    {
    }
}

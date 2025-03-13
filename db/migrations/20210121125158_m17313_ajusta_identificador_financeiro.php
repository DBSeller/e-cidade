<?php

use Classes\PostgresMigration;

class M17313AjustaIdentificadorFinanceiro extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<SQL

            create temp table w_isf as
            select *
             from conplano
                  left join conplanoreduz on c60_anousu = c61_anousu
                                         and c60_codcon = c61_codcon
            where c60_anousu >= 2019
              and c61_reduz is null;


            update conplano
              set c60_identificadorfinanceiro = 'N'
             from w_isf
            where conplano.c60_anousu = w_isf.c60_anousu
              and conplano.c60_codcon = w_isf.c60_codcon;
SQL;

        $this->execute($sSql);
    }

    public function down()
    {

        $sSql = <<<SQL

            drop table w_isf;
SQL;

        $this->execute($sSql);
    }
}

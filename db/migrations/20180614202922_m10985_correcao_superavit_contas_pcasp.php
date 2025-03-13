<?php

use Classes\PostgresMigration;

class M10985CorrecaoSuperavitContasPcasp extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            UPDATE conplano
            SET c60_identificadorfinanceiro = 'N'
            WHERE c60_codcon NOT IN (SELECT c61_codcon
                                       FROM conplanoreduz
                                 INNER JOIN conplano ON c61_codcon = c60_codcon
                                        AND c60_anousu = c61_anousu
                                      WHERE c61_anousu >= 2018)
            AND c60_anousu >= 2018
            AND c60_identificadorfinanceiro <> 'N';
        ");
    }

    public function down()
    {

    }

}

<?php

use Classes\PostgresMigration;

class M16963AtualizacaoViewPlanoContas extends PostgresMigration
{
    public function change()
    {
        $this->execute(<<<SQL
DROP VIEW vs_planocontas;

create view vs_planocontas as
SELECT *
FROM CONPLANO
     JOIN CONSISTEMA ON C60_CODSIS = C52_CODSIS
     JOIN CONCLASS ON C60_CODCLA = C51_CODCLA
     LEFT JOIN CONPLANOREDUZ ON C60_CODCON = C61_CODCON and C60_ANOUSU = C61_ANOUSU
     LEFT JOIN CONPLANOCONTA ON c63_ANOUSU = C60_ANOUSU
                            and C61_REDUZ = C63_REDUZ
     LEFT JOIN CONPLANOEXE ON C61_ANOUSU = C62_ANOUSU and C61_REDUZ = C62_REDUZ
     LEFT JOIN ORCTIPOREC ON C61_CODIGO = O15_CODIGO
     LEFT JOIN DB_CONFIG ON CODIGO = CONPLANOREDUZ.C61_INSTIT;
SQL
        );
    }
}

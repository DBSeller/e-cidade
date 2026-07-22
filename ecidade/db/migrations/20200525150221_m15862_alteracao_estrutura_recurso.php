<?php

use Classes\PostgresMigration;

class M15862AlteracaoEstruturaRecurso extends PostgresMigration
{
    public function down()
    {

    }

    public function up()
    {
        $this->execute(<<<SQL_UP



DROP VIEW if exists vs_planosistema;
DROP VIEW if exists vs_planocontas;

alter table orctiporec alter column o15_loaespecificacao type varchar;

create view vs_planosistema as
SELECT *
FROM CONPLANOSIS
         INNER JOIN CONPLANOREF             ON C65_CODPLA = C64_CODPLA
         INNER JOIN CONPLANO                ON C60_CODCON = C65_CODCON
         INNER JOIN CONPLANOREDUZ           ON C61_CODCON = C60_CODCON
         INNER JOIN CONPLANOEXE        	ON C61_REDUZ  = C62_REDUZ
         INNER JOIN ORCTIPOREC              ON C61_CODIGO = O15_CODIGO
         LEFT OUTER JOIN CONPLANOCONTA      ON C60_CODCON = C63_CODCON;


create view vs_planocontas as
SELECT *
FROM CONPLANO
         INNER JOIN CONSISTEMA             ON C60_CODSIS = C52_CODSIS
         INNER JOIN CONCLASS               ON C60_CODCLA = C51_CODCLA
         LEFT JOIN CONPLANOREDUZ           ON C60_CODCON = C61_CODCON and C60_ANOUSU =C61_ANOUSU
         LEFT  JOIN CONPLANOCONTA          ON c63_ANOUSU = C60_ANOUSU
    and C61_REDUZ = C63_REDUZ
         LEFT JOIN CONPLANOEXE             ON C61_ANOUSU = C62_ANOUSU and C61_REDUZ  = C62_REDUZ
         LEFT JOIN ORCTIPOREC              ON C61_CODIGO = O15_CODIGO
         LEFT JOIN DB_CONFIG               ON CODIGO     = CONPLANOREDUZ.C61_INSTIT



SQL_UP
);
    }
}

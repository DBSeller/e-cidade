<?php

use Classes\PostgresMigration;

class M16222AjustaDadosBrubAnt extends PostgresMigration
{


    public function up()
    {

      $sSql = <<<SQL
        DROP TABLE IF EXISTS w_desdob;
        CREATE TEMP TABLE w_desdob AS
        SELECT e60_codemp,
               e60_anousu,
               e60_numemp,
               c75_codlan,
               e64_codele,
               c67_codele,
               e62_codele,
               e70_codnota,
               e70_codele,
               e53_codord,
               e53_codele
        FROM empempenho
        INNER JOIN empelemento ON e64_numemp = e60_numemp
        INNER JOIN empempitem ON e62_numemp = e60_numemp
        INNER JOIN conlancamemp ON c75_numemp = e60_numemp
        INNER JOIN conlancamele ON c67_codlan = c75_codlan
        LEFT JOIN conlancamnota ON c66_codlan = c75_codlan
        LEFT JOIN empnotaele ON e70_codnota = c66_codnota
        LEFT JOIN conlancamord ON c80_codlan = c75_codlan
        LEFT JOIN pagordemele ON e53_codord = c80_codord
        WHERE e60_anousu = 2020
          AND ((e53_codele IS NOT NULL
                AND e53_codele <> e64_codele)
               OR (e70_codele IS NOT NULL
                   AND e70_codele <> e64_codele)
               OR (e62_codele <> e64_codele)
               OR (c67_codele <> e64_codele));


        UPDATE pagordemele
        SET e53_codele = w_desdob.e64_codele
        FROM w_desdob
        WHERE pagordemele.e53_codord = w_desdob.e53_codord;


        UPDATE empnotaele
        SET e70_codele = w_desdob.e64_codele
        FROM w_desdob
        WHERE empnotaele.e70_codnota = w_desdob.e70_codnota;


        UPDATE conlancamele
        SET c67_codele = w_desdob.e64_codele
        FROM w_desdob
        WHERE conlancamele.c67_codlan = w_desdob.c75_codlan;


        UPDATE empempitem
        SET e62_codele = w_desdob.e64_codele
        FROM w_desdob
        WHERE empempitem.e62_numemp = w_desdob.e60_numemp;

SQL;

      $this->execute($sSql);
    }



    public function down()
    {

        $sSql = <<<SQL

          DROP TABLE IF EXISTS w_desdob;
SQL;

        $this->execute($sSql);
    }
}

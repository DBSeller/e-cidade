<?php

use Classes\PostgresMigration;

class M10922AvisosPadFolha extends PostgresMigration
{
    public function up()
    {
        $plugin = $this->fetchRow("
            SELECT EXISTS(SELECT 1
                          FROM information_schema.tables
                          WHERE table_schema = 'plugins' AND table_name = 'padrsdespesafuncionarioempenhovinculo');
        ");

        if ($plugin['exists']) {
            $sql = "
            INSERT INTO plugins.padrsdespesafuncionarioempenhovinculo
              SELECT
                nextval('plugins.padrsdespesafuncionarioempenhovinculo_sequencial_seq') AS sequencial,
                e60_numemp                                                              AS empempenho,
                1                                                                       AS padrsdespesafuncionario
              FROM empenho.empempenho
                INNER JOIN orcamento.orcdotacao ON e60_coddot = o58_coddot AND e60_anousu = o58_anousu
                INNER JOIN orcamento.orcelemento ON o58_anousu = o56_anousu AND o58_codele = o56_codele
                LEFT JOIN plugins.padrsdespesafuncionarioempenhovinculo ON empempenho = e60_numemp
              WHERE e60_emiss BETWEEN '2018-01-01' AND '2018-12-31' AND substr(o56_elemento, 1, 3) = '331' AND padrsdespesafuncionario IS NULL;
            
            INSERT INTO plugins.padrsdespesafuncionarioempenhovinculo
              SELECT
                nextval('plugins.padrsdespesafuncionarioempenhovinculo_sequencial_seq') AS sequencial,
                e60_numemp                                                              AS empempenho,
                2                                                                       AS padrsdespesafuncionario
              FROM empenho.empempenho
                INNER JOIN orcamento.orcdotacao ON e60_coddot = o58_coddot AND e60_anousu = o58_anousu
                INNER JOIN orcamento.orcelemento ON o58_anousu = o56_anousu AND o58_codele = o56_codele
                LEFT JOIN plugins.padrsdespesafuncionarioempenhovinculo ON empempenho = e60_numemp
              WHERE e60_emiss BETWEEN '2018-01-01' AND '2018-12-31' AND substr(o56_elemento, 6, 2) = '93' AND padrsdespesafuncionario IS NULL;
            
            INSERT INTO plugins.padrsdespesafuncionarioempenhovinculo
              SELECT
                nextval('plugins.padrsdespesafuncionarioempenhovinculo_sequencial_seq') AS sequencial,
                e60_numemp                                                              AS empempenho,
                3                                                                       AS padrsdespesafuncionario
              FROM empenho.empempenho
                INNER JOIN orcamento.orcdotacao ON e60_coddot = o58_coddot AND e60_anousu = o58_anousu
                INNER JOIN orcamento.orcelemento ON o58_anousu = o56_anousu AND o58_codele = o56_codele
                LEFT JOIN plugins.padrsdespesafuncionarioempenhovinculo ON empempenho = e60_numemp
              WHERE
                e60_emiss BETWEEN '2018-01-01' AND '2018-12-31' AND (substr(o56_elemento, 6, 2) <> '93' AND substr(o56_elemento, 1, 3) <> '331') AND padrsdespesafuncionario IS NULL;
        ";

            $this->execute($sql);
        }
    }
}

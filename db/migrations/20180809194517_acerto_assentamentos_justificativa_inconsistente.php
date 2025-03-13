<?php

use Classes\PostgresMigration;

class AcertoAssentamentosJustificativaInconsistente extends PostgresMigration
{
    function up() 
    {
        //echo "CRIANDO tabela de inconsistencias de justificativas ". 
        $this->execute("
            CREATE TABLE w_assentamentos_justificativa_inconsistentes_clin AS
            SELECT 
              assenta.*
            FROM assenta
            INNER JOIN rhpessoal ON h16_regist = rh01_regist
            INNER JOIN tipoasse ON h12_codigo = h16_assent
            LEFT JOIN pontoeletronicojustificativatipoasse ON rh205_tipoasse = h12_codigo
            LEFT JOIN pontoeletronicojustificativa ON rh194_sequencial = rh205_pontoeletronicojustificativa
            LEFT JOIN assentamentofuncional ON rh193_assentamento_funcional = h16_codigo
            WHERE h12_natureza IN (5)
              AND NOT EXISTS (SELECT 1 FROM assentamentojustificativaperiodo WHERE rh206_codigo = h16_codigo)
            ;
        ") . PHP_EOL;
        
        //echo "INSERINDO vinculo na tabela de justificativas ". 
        $this->execute("
            INSERT INTO assentamentojustificativaperiodo
            SELECT
              h16_codigo
              ,generate_series(1,3)
            FROM
              w_assentamentos_justificativa_inconsistentes_clin
            ;
        ") . PHP_EOL;

        //echo "CRIANDO tabela de inconsistencias de abono falta ". 
        $this->execute("
            CREATE TABLE w_assentamentos_abono_falta_inconsistentes_clin AS
            SELECT 
              assenta.*
            FROM assenta
            INNER JOIN rhpessoal ON h16_regist = rh01_regist
            INNER JOIN tipoasse ON h12_codigo = h16_assent
            LEFT JOIN assentamentofuncional ON rh193_assentamento_funcional = h16_codigo
            WHERE h12_natureza IN (9)
             AND NOT EXISTS (SELECT 1 FROM assentamentoabonofalta WHERE rh213_codigo = h16_codigo)
            ;
        ") . PHP_EOL;
        
        //echo "INSERINDO vinculo na tabela de abono falta ".
        $this->execute("
            INSERT INTO assentamentoabonofalta
            SELECT
              h16_codigo
              ,h16_hora
              ,h16_hora
            FROM
              w_assentamentos_abono_falta_inconsistentes_clin
            ;
        ") . PHP_EOL;
    }

    function down()
    {
        //echo "DESFAZENDO vinculo da tabela de justificativas ". 
        $this->execute("
            DELETE FROM assentamentojustificativaperiodo
            WHERE rh206_codigo IN ( SELECT
                                      h16_codigo
                                    FROM
                                      w_assentamentos_justificativa_inconsistentes_clin
                                  )
            ;
        ") . PHP_EOL;

        //echo "EXCLUINDO tabela de inconsistencias de justificativas ". 
        $this->execute("DROP TABLE w_assentamentos_justificativa_inconsistentes_clin;") . PHP_EOL;
        
        //echo "DESFAZENDO vinculo da tabela de abono falta ". 
        $this->execute("
            DELETE FROM assentamentoabonofalta
            WHERE rh213_codigo IN ( SELECT
                                      h16_codigo
                                    FROM
                                      w_assentamentos_abono_falta_inconsistentes_clin
                                  )
            ;
        ") . PHP_EOL;

        //echo "EXCLUINDO tabela de inconsistencias de abono falta ". 
        $this->execute("DROP TABLE w_assentamentos_abono_falta_inconsistentes_clin;") . PHP_EOL;
    }
}

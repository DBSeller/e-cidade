<?php

use Classes\PostgresMigration;

/**
 * Class M15825AjusteFormulasDependentes
 */
class M15825AjusteFormulasDependentes extends PostgresMigration
{
    const MAX = 10;

    public function up() {
        $this->executaDataNascimentoDependente();
        $this->executaCpfDependente();
        $this->executaDependenteTipo();
        $this->executaIRFDependente();
        $this->executaNomeDependente();
        $this->executaSalarioFamiliaDependentes();
        $this->executaSalarioFamiliaIRRFDependentes();
        $this->executaTipoDependentes();
    }

    public function down() {
        $where = 'rh01_numcgm = [CODIGO_CGM]';
        $this->executaDataNascimentoDependente($where);
        $this->executaCpfDependente($where);
        $this->executaDependenteTipo($where);
        $this->executaIRFDependente($where);
        $this->executaNomeDependente($where);
        $this->executaSalarioFamiliaDependentes($where);
        $this->executaSalarioFamiliaIRRFDependentes($where);
        $this->executaTipoDependentes($where);
    }


    /**
     * @param string $where
     */
    public function executaDataNascimentoDependente($where='rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR]') {
        for ($i = 0; $i < self::MAX; $i++) {
            $contador = $i + 1;
            $sql = "
            update db_formulas set db148_formula = '
                SELECT rh31_dtnasc AS data_nascimento
                                FROM (SELECT DISTINCT rh31_nome,
                                                      rh31_dtnasc,
                                                      rh31_gparen,
                                                      rh31_depend,
                                                      rh31_irf,
                                                      rh31_especi
                                      FROM rhdepend
                                      WHERE (rh31_depend <> \'N\' OR rh31_irf <> \'0\') AND
                                              rh31_regist IN (SELECT rh01_regist
                FROM rhpessoal
                       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(\'db_instit\') :: INTEGER)
                                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(\'db_instit\') :: INTEGER)
                       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
                WHERE rh05_seqpes IS NULL AND " . $where . ")) AS x
                                ORDER BY rh31_nome, rh31_dtnasc
                                OFFSET " . $i . "
                                LIMIT 1' where db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_" . $contador . "';";
            $this->execute($sql);
        }
    }

    /**
     * @param string $where
     */
    public function executaCpfDependente($where='rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR]') {
        for ($i = 0; $i < self::MAX; $i++) {
            $contador = $i + 1;
            $sql = "
            update db_formulas set db148_formula = '
             SELECT (SELECT dp01_cpf
                FROM rhdependeplug
                WHERE dp01_rhdepend = (SELECT aux.rh31_codigo
                                        FROM rhdepend aux
                                        WHERE aux.rh31_regist = rh31_regist AND aux.rh31_nome = x.rh31_nome AND
                                                aux.rh31_dtnasc = x.rh31_dtnasc
                                        ORDER BY rh31_codigo DESC
                                        LIMIT 1)) AS dp01_cpf
                FROM (SELECT DISTINCT rh31_nome,
                                       rh31_dtnasc,
                                       rh31_gparen,
                                       rh31_depend,
                                       rh31_irf,
                                       rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> \'N\' OR rh31_irf <> \'0\') AND
                        rh31_regist IN (SELECT rh01_regist
            FROM rhpessoal
                INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                             AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(\'db_instit\') :: INTEGER)
                             AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(\'db_instit\') :: INTEGER)
            LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
            WHERE rh05_seqpes IS NULL AND " . $where . ")) AS x
                 ORDER BY rh31_nome, rh31_dtnasc
                 OFFSET " . $i . "
                 LIMIT 1;' 
            where db148_nome = 'ESOCIAL_DEPENDENTE_CPF_" . $contador ."';";
            $this->execute($sql);
        }
    }

    /**
     * @param string $where
     */
    public function executaDependenteTipo($where='rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR]') {
        for ($i = 0; $i < self::MAX; $i++) {
            $contador = $i + 1;
            $sql = "
            update db_formulas set db148_formula = '
                SELECT
                    CASE WHEN rh31_gparen = \'C\'
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = \'tpDep_" . $contador . "\' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = \'dependente_" . $contador . "\' AND
                           db104_identificadorcampo = \'dependente_" . $contador . "_tpDep_01\')
                    WHEN rh31_gparen = \'F\'
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = \'tpDep_" . $contador . "\' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = \'dependente_" . $contador . "\' AND
                                       db104_identificadorcampo = \'dependente_" . $contador . "_tpDep_03\')
                    WHEN rh31_gparen = \'P\'
                           OR rh31_gparen = \'M\'
                           OR rh31_gparen = \'A\'
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = \'tpDep_" . $contador . "\' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = \'dependente_" . $contador . "\' AND
                                       db104_identificadorcampo = \'dependente_" . $contador . "_tpDep_09\')
                    WHEN rh31_gparen = \'O\'
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = \'tpDep_" . $contador . "\' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = \'dependente_" . $contador . "\' AND
                                       db104_identificadorcampo = \'dependente_" . $contador . "_tpDep_99\')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> \'N\' OR rh31_irf <> \'0\') AND
                      rh31_regist IN (SELECT rh01_regist
                FROM rhpessoal
                    INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                                 AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(\'db_instit\') :: INTEGER)
                                                 AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(\'db_instit\') :: INTEGER)
                    LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
                WHERE rh05_seqpes IS NULL AND " . $where . ")) AS x
                                     ORDER BY rh31_nome, rh31_dtnasc
                     OFFSET " . $i . "
                     LIMIT 1;' 
                where db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_" . $contador ."';";
            $this->execute($sql);
        }
    }

    /**
     * @param string $where
     */
    public function executaIRFDependente($where='rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR]') {
        for ($i = 0; $i < self::MAX; $i++) {
            $contador = $i + 1;
            $sql = "
            update db_formulas set db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0 
                                  THEN (SELECT db104_sequencial 
                                        FROM avaliacaogrupopergunta 
                                               INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = 
                                                                               avaliacaogrupopergunta.db102_sequencial 
                                               INNER JOIN avaliacaoperguntaopcao 
                                                 ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial 
                                        WHERE db103_identificadorcampo = \'depIRRF_" . $contador . "\' AND db102_avaliacao = 3000013 AND 
                                                db102_identificadorcampo = \'dependente_" . $contador . "\' AND db104_valorresposta = \'S\')
                             WHEN rh31_irf :: INTEGER = 0 
                                  THEN (SELECT db104_sequencial 
                                        FROM avaliacaogrupopergunta 
                                               INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = 
                                                                               avaliacaogrupopergunta.db102_sequencial 
                                               INNER JOIN avaliacaoperguntaopcao 
                                                 ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial 
                                        WHERE db103_identificadorcampo = \'depIRRF_" . $contador . "\' AND db102_avaliacao = 3000013 AND 
                                                db102_identificadorcampo = \'dependente_" . $contador . "\' AND db104_valorresposta = \'N\')
                             ELSE NULL END 
                             FROM (SELECT DISTINCT rh31_nome,
                                                   rh31_dtnasc,
                                                   rh31_gparen,
                                                   rh31_depend,
                                                   rh31_irf,
                                                   rh31_especi 
                                   FROM rhdepend 
                                   WHERE (rh31_depend <> \'N\' OR rh31_irf <> \'0\') AND 
                                           rh31_regist IN (SELECT rh01_regist 
                 FROM rhpessoal 
                        INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist 
                                                     AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(\'db_instit\') :: INTEGER)
                                                     AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(\'db_instit\') :: INTEGER)
                        LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes 
                 WHERE rh05_seqpes IS NULL AND " . $where . ")) AS x 
                             ORDER BY rh31_nome, rh31_dtnasc 
                OFFSET " . $i . " LIMIT 1;'
                where db148_nome = 'ESOCIAL_IRF_DEPENDENTE_" . ($contador) ."';";
            $this->execute($sql);
        }
    }

    /**
     * @param string $where
     */
    public function executaNomeDependente($where='rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR]') {
        for ($i = 0; $i < self::MAX; $i++) {
            $contador = $i + 1;
            $sql = "
            update db_formulas set db148_formula = '
                SELECT 
                    rh31_nome as nome
                FROM (SELECT DISTINCT rh31_nome,
                                   rh31_dtnasc,
                                   rh31_gparen,
                                   rh31_depend,
                                   rh31_irf,
                                   rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> \'N\' OR rh31_irf <> \'0\') AND
                                           rh31_regist IN (SELECT rh01_regist
                 FROM rhpessoal
                        INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                                     AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(\'db_instit\') :: INTEGER)
                                                     AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(\'db_instit\') :: INTEGER)
                        LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
                 WHERE rh05_seqpes IS NULL AND " . $where . ")) AS x
                                             ORDER BY rh31_nome, rh31_dtnasc 
                OFFSET " . $i . "  LIMIT 1' 
                where db148_nome = 'ESOCIAL_NOME_DEPENDENTE_" . $contador ."';";
            $this->execute($sql);
        }
    }

    /**
     * @param string $where
     */
    public function executaSalarioFamiliaDependentes($where='rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR]') {
        for ($i = 0; $i < self::MAX; $i++) {
            $contador = $i + 1;
            $sql = "
            update db_formulas set db148_formula = '
                SELECT CASE WHEN rh31_depend <> \'N\'
                                             THEN (SELECT db104_sequencial 
                                                     FROM avaliacaogrupopergunta 
                                                           INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = 
                                                                                           avaliacaogrupopergunta.db102_sequencial 
                                                           INNER JOIN avaliacaoperguntaopcao 
                                                             ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial 
                                                     WHERE db103_identificadorcampo = \'depSF_" . $contador . "\' AND db102_avaliacao = 3000013 AND 
                                                            db102_identificadorcampo = \'dependente_" . $contador . "\' AND db104_valorresposta = \'S\')
                                         WHEN rh31_depend = \'N\'
                                              THEN (SELECT db104_sequencial 
                                                    FROM avaliacaogrupopergunta 
                                                           INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = 
                                                                                           avaliacaogrupopergunta.db102_sequencial 
                                                           INNER JOIN avaliacaoperguntaopcao 
                                                             ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial 
                                                    WHERE db103_identificadorcampo = \'depSF_" . $contador . "\' AND db102_avaliacao = 3000013 AND 
                                                            db102_identificadorcampo = \'dependente_" . $contador . "\' AND db104_valorresposta = \'N\')
                                             ELSE NULL END 
                             FROM (SELECT DISTINCT rh31_nome,
                                           rh31_dtnasc,
                                           rh31_gparen,
                                           rh31_depend,
                                           rh31_irf,
                                           rh31_especi 
                             FROM rhdepend 
                             WHERE (rh31_depend <> \'N\' OR rh31_irf <> \'0\') AND 
                                   rh31_regist IN (SELECT rh01_regist 
                 FROM rhpessoal 
                        INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist 
                                                     AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(\'db_instit\') :: INTEGER)
                                                     AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(\'db_instit\') :: INTEGER)
                        LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes 
                 WHERE rh05_seqpes IS NULL AND " . $where . ")) AS x 
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET " . $i . "
                LIMIT 1' where db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_" . $contador . "';";
            $this->execute($sql);
        }
    }

    /**
     * @param string $where
     */
    public function executaSalarioFamiliaIRRFDependentes($where='rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR]') {
        $avaliacoes = array(
            0 => array(0 => 3001079, 1 => 3001081),
            1 => array(0 => 3001121, 1 => 3001120),
            2 => array(0 => 3001138, 1 => 3001137),
            3 => array(0 => 3001155, 1 => 3001154),
            4 => array(0 => 3001172, 1 => 3001171),
            5 => array(0 => 3001189, 1 => 3001188),
            6 => array(0 => 3001206, 1 => 3001205),
            7 => array(0 => 3001223, 1 => 3001222),
            8 => array(0 => 3001240, 1 => 3001239),
            9 => array(0 => 3001257, 1 => 3001256),
        );
        for ($i = 0; $i < self::MAX; $i++) {
            $contador = $i + 1;
            $sql = "
            update db_formulas set db148_formula = '
                select * from (select unnest(regexp_split_to_array(rh31_depend_rh31_irf, \',\')) as salario_familia_irrf_dependente from (select case when rh31_depend <> \'N\' then " . $avaliacoes[$i][0] . " else 0 end||\',\'||case when rh31_irf <> \'0\' then " . $avaliacoes[$i][1] . " else 0 end as rh31_depend_rh31_irf from rhdepend where (rh31_depend <> \'N\' or rh31_irf <> \'0\') and rh31_regist in (select rh01_regist from rhpessoal where " . $where . ") order by rh31_codigo OFFSET " . $i . " limit 1 ) as dados ) as salario_familia_irrf where salario_familia_irrf_dependente::int > 0'
            where db148_nome = 'ESOCIAL_SALARIO_FAMILIA_IRRF_DEPENDENTE_" . $contador . "';";
            $this->execute($sql);
        }
    }

    /**
     * @param string $where
     */
    public function executaTipoDependentes($where='rh01_regist = [ESOCIAL_MATRICULA_SERVIDOR]') {
        $avaliacoes = array(
            0 => array(0 => 3001088, 1 => 3001090, 2 => 3001106, 3 => 3001107, 4 => 3001100, 5 => 3001101, 6 => 3001103, 7 => 3001085, 8 => 3001086),
            1 => array(0 => 3001131, 1 => 3001130, 2 => 3001123, 3 => 3001122, 4 => 3001127, 5 => 3001126, 6 => 3001125, 7 => 3001133, 8 => 3001132),
            2 => array(0 => 3001148, 1 => 3001147, 2 => 3001140, 3 => 3001139, 4 => 3001144, 5 => 3001143, 6 => 3001142, 7 => 3001150, 8 => 3001149),
            3 => array(0 => 3001165, 1 => 3001164, 2 => 3001157, 3 => 3001156, 4 => 3001161, 5 => 3001160, 6 => 3001159, 7 => 3001167, 8 => 3001166),
            4 => array(0 => 3001182, 1 => 3001181, 2 => 3001174, 3 => 3001173, 4 => 3001178, 5 => 3001177, 6 => 3001176, 7 => 3001184, 8 => 3001183),
            5 => array(0 => 3001199, 1 => 3001198, 2 => 3001191, 3 => 3001190, 4 => 3001195, 5 => 3001194, 6 => 3001193, 7 => 3001201, 8 => 3001200),
            6 => array(0 => 3001216, 1 => 3001215, 2 => 3001208, 3 => 3001207, 4 => 3001212, 5 => 3001211, 6 => 3001210, 7 => 3001218, 8 => 3001217),
            7 => array(0 => 3001233, 1 => 3001232, 2 => 3001225, 3 => 3001224, 4 => 3001229, 5 => 3001228, 6 => 3001227, 7 => 3001235, 8 => 3001234),
            8 => array(0 => 3001250, 1 => 3001249, 2 => 3001242, 3 => 3001241, 4 => 3001246, 5 => 3001245, 6 => 3001244, 7 => 3001252, 8 => 3001251),
            9 => array(0 => 3001267, 1 => 3001266, 2 => 3001259, 3 => 3001258, 4 => 3001263, 5 => 3001262, 6 => 3001261, 7 => 3001269, 8 => 3001268),
        );

        for ($i = 0; $i < self::MAX; $i++) {
            $contador = $i + 1;
            $sql = "
            update db_formulas set db148_formula = '
                select case rh31_irf when \'2\' then \'" . $avaliacoes[$i][0] . "\' when \'3\' then \'" . $avaliacoes[$i][1] . "\' when \'4\' then \'" . $avaliacoes[$i][2] . "\' when \'5\' then \'" . $avaliacoes[$i][3] . "\' when \'6\' then \'" . $avaliacoes[$i][4] . "\' when \'7\' then \'" . $avaliacoes[$i][5] . "\' when \'8\' then \'" . $avaliacoes[$i][6] . "\' else case when rh31_gparen = \'C\' then \'" . $avaliacoes[$i][7] . "\' when rh31_irf = \'1\' then \'" . $avaliacoes[$i][8] . "\' else null end end as tipo_dependente from rhdepend where (rh31_depend <> \'N\' or rh31_irf <> \'0\') and rh31_regist in (select rh01_regist from rhpessoal where " . $where . ") order by rh31_codigo OFFSET " . $i . " limit 1;'
            where db148_nome = 'ESOCIAL_TIPO_DEPENDENTE_" . $contador . "';";
            $this->execute($sql);
        }
    }
}

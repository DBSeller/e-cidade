<?php

use Classes\PostgresMigration;

class M12211AtualizacaoFormulaDependentes extends PostgresMigration
{
    public function up()
    {
        $this->atualizaFormulaTipo();
        $this->atualizaFormulaNome();
        $this->atualizaFormulaDataNascimento();
        $this->atualizaFormulaCpf();
        $this->atualizaFormulaImpostoRenda();
        $this->atualizaFormulaSalarioFamilia();
    }

    public function down()
    {
        $this->reverteFormulaTipo();
        $this->reverteFormulaNome();
        $this->reverteFormulaDataNascimento();
        $this->reverteFormulaCpf();
        $this->reverteFormulaImpostoRenda();
        $this->reverteFormulaSalarioFamilia();
    }

    private function atualizaFormulaTipo()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
                        SELECT CASE WHEN rh31_gparen = ''C''
                        THEN (SELECT db104_sequencial
                        FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                        WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND
                               db104_identificadorcampo = ''dependente_1_tpDep_01'')
                        WHEN rh31_gparen = ''F''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_03'')
                        WHEN rh31_gparen = ''P''
                               OR rh31_gparen = ''M''
                               OR rh31_gparen = ''A''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_09'')
                        WHEN rh31_gparen = ''O''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_99'')
                        END
                        FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                        FROM rhdepend
                        WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 0
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 1
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 2
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 3
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 4
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 5
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 6
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                        THEN (SELECT db104_sequencial
                        FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                        WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND
                               db104_identificadorcampo = ''dependente_1_tpDep_01'')
                        WHEN rh31_gparen = ''F''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_03'')
                        WHEN rh31_gparen = ''P''
                               OR rh31_gparen = ''M''
                               OR rh31_gparen = ''A''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_09'')
                        WHEN rh31_gparen = ''O''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_99'')
                        END
                        FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                        FROM rhdepend
                        WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 7
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 8
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 9
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_10'");
    }

    private function atualizaFormulaNome()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 0 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 1 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 2 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 3 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 4 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 5 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 6 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 7 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 8 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 9 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_10'");
    }

    private function atualizaFormulaDataNascimento()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 0
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 1
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 2
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 3
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 4
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 5
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 6
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 7
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 8
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 9
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_10'");
    }

    private function atualizaFormulaCpf()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 0
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 1
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 2
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 3
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 4
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 5
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 6
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 7
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 8
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 9
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_10'");
    }

    private function atualizaFormulaImpostoRenda()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 0
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 1
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 2
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 3
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 4
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 5
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 6
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 7
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 8
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 9
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_10'");
    }

    private function atualizaFormulaSalarioFamilia()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 0
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 1
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 2
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 3
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 4
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 5
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 6
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 7
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 8
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist
FROM rhpessoal
       INNER JOIN rhpessoalmov ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
                                    AND rhpessoalmov.rh02_anousu = fc_anofolha(fc_getsession(''db_instit'') :: INTEGER)
                                    AND rhpessoalmov.rh02_mesusu = fc_mesfolha(fc_getsession(''db_instit'') :: INTEGER)
       LEFT JOIN rhpesrescisao ON rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
WHERE rh05_seqpes IS NULL AND rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 9
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_10'");
    }

    private function reverteFormulaTipo()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
                        SELECT CASE WHEN rh31_gparen = ''C''
                        THEN (SELECT db104_sequencial
                        FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                        WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND
                               db104_identificadorcampo = ''dependente_1_tpDep_01'')
                        WHEN rh31_gparen = ''F''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_03'')
                        WHEN rh31_gparen = ''P''
                               OR rh31_gparen = ''M''
                               OR rh31_gparen = ''A''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_09'')
                        WHEN rh31_gparen = ''O''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_99'')
                        END
                        FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                        FROM rhdepend
                        WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 0
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 1
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 2
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 3
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 4
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 5
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 6
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                        THEN (SELECT db104_sequencial
                        FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                        WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND
                               db104_identificadorcampo = ''dependente_1_tpDep_01'')
                        WHEN rh31_gparen = ''F''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_03'')
                        WHEN rh31_gparen = ''P''
                               OR rh31_gparen = ''M''
                               OR rh31_gparen = ''A''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_09'')
                        WHEN rh31_gparen = ''O''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND
                                           db104_identificadorcampo = ''dependente_1_tpDep_99'')
                        END
                        FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                        FROM rhdepend
                        WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 7
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 8
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_gparen = ''C''
                    THEN (SELECT db104_sequencial
                    FROM avaliacaogrupopergunta
                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                          avaliacaogrupopergunta.db102_sequencial
                          INNER JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                    WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                           db102_identificadorcampo = ''dependente_1'' AND
                           db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                           OR rh31_gparen = ''M''
                           OR rh31_gparen = ''A''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O''
                         THEN (SELECT db104_sequencial
                               FROM avaliacaogrupopergunta
                                      INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                      avaliacaogrupopergunta.db102_sequencial
                                      INNER JOIN avaliacaoperguntaopcao
                                        ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                               WHERE db103_identificadorcampo = ''tpDep_1'' AND db102_avaliacao = 3000013 AND
                                       db102_identificadorcampo = ''dependente_1'' AND
                                       db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
                    FROM (SELECT DISTINCT rh31_nome,
                              rh31_dtnasc,
                              rh31_gparen,
                              rh31_depend,
                              rh31_irf,
                              rh31_especi
                    FROM rhdepend
                    WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                    ORDER BY rh31_nome, rh31_dtnasc
                    OFFSET 9
                    LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_TIPO_10'");
    }

    private function reverteFormulaNome()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 0 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 1 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 2 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 3 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 4 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 5 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 6 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 7 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 8 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = 'SELECT rh31_nome as nome
                            FROM (SELECT DISTINCT rh31_nome,
                                                  rh31_dtnasc,
                                                  rh31_gparen,
                                                  rh31_depend,
                                                  rh31_irf,
                                                  rh31_especi
                                  FROM rhdepend
                                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 9 limit 1' WHERE db148_nome = 'ESOCIAL_NOME_DEPENDENTE_10'");
    }

    private function reverteFormulaDataNascimento()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 0
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 1
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 2
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 3
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 4
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 5
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 6
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 7
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 8
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT rh31_dtnasc AS data_nascimento
                FROM (SELECT DISTINCT rh31_nome,
                                      rh31_dtnasc,
                                      rh31_gparen,
                                      rh31_depend,
                                      rh31_irf,
                                      rh31_especi
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                              rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 9
                LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DATA_NASCIMENTO_DEPENDENTE_10'");
    }

    private function reverteFormulaCpf()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 0
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 1
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 2
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 3
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 4
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 5
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 6
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 7
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 8
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
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
                              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 9
                        LIMIT 1;' WHERE db148_nome = 'ESOCIAL_DEPENDENTE_CPF_10'");
    }

    private function reverteFormulaImpostoRenda()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 0
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 1
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 2
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 3
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 4
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 5
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 6
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 7
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 8
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_irf :: INTEGER <> 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
            WHEN rh31_irf :: INTEGER = 0
                 THEN (SELECT db104_sequencial
                       FROM avaliacaogrupopergunta
                              INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                              avaliacaogrupopergunta.db102_sequencial
                              INNER JOIN avaliacaoperguntaopcao
                                ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                       WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                               db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                                  rh31_dtnasc,
                                  rh31_gparen,
                                  rh31_depend,
                                  rh31_irf,
                                  rh31_especi
                  FROM rhdepend
                  WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 9
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_IRF_DEPENDENTE_10'");
    }

    private function reverteFormulaSalarioFamilia()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
            SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 0
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_1'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 1
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_2'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 2
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_3'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 3
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_4'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 4
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_5'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 5
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_6'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 6
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_7'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 7
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_8'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 8
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_9'");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN rh31_depend <> ''N''
                            THEN (SELECT db104_sequencial
                                    FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                    WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                        WHEN rh31_depend = ''N''
                             THEN (SELECT db104_sequencial
                                   FROM avaliacaogrupopergunta
                                          INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                          avaliacaogrupopergunta.db102_sequencial
                                          INNER JOIN avaliacaoperguntaopcao
                                            ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                   WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                           db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                            ELSE NULL END
            FROM (SELECT DISTINCT rh31_nome,
                          rh31_dtnasc,
                          rh31_gparen,
                          rh31_depend,
                          rh31_irf,
                          rh31_especi
            FROM rhdepend
            WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') AND
                  rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 9
            LIMIT 1;
            ' WHERE db148_nome = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_10'");
    }
}

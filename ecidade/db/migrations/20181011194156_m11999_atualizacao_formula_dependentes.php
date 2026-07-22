<?php

use Classes\PostgresMigration;

class M11999AtualizacaoFormulaDependentes extends PostgresMigration
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
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 0
                        LIMIT 1;' WHERE db148_sequencial = 6697");
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
                    LIMIT 1;' WHERE db148_sequencial = 6701");
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
                    LIMIT 1;' WHERE db148_sequencial = 6705");
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
                    LIMIT 1;' WHERE db148_sequencial = 6709");
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
                    LIMIT 1;' WHERE db148_sequencial = 6713");
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
                    LIMIT 1;' WHERE db148_sequencial = 6717");
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
                    LIMIT 1;' WHERE db148_sequencial = 6721");
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
                        LIMIT 1;' WHERE db148_sequencial = 6725");
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
                    LIMIT 1;' WHERE db148_sequencial = 6729");
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
                    LIMIT 1;' WHERE db148_sequencial = 6733");
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
                                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                            ORDER BY rh31_nome, rh31_dtnasc offset 0 limit 1' WHERE db148_sequencial = 18");
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
                            ORDER BY rh31_nome, rh31_dtnasc offset 1 limit 1' WHERE db148_sequencial = 19");
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
                            ORDER BY rh31_nome, rh31_dtnasc offset 2 limit 1' WHERE db148_sequencial = 20");
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
                            ORDER BY rh31_nome, rh31_dtnasc offset 3 limit 1' WHERE db148_sequencial = 21");
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
                            ORDER BY rh31_nome, rh31_dtnasc offset 4 limit 1' WHERE db148_sequencial = 22");
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
                            ORDER BY rh31_nome, rh31_dtnasc offset 5 limit 1' WHERE db148_sequencial = 23");
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
                            ORDER BY rh31_nome, rh31_dtnasc offset 6 limit 1' WHERE db148_sequencial = 24");
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
                            ORDER BY rh31_nome, rh31_dtnasc offset 7 limit 1' WHERE db148_sequencial = 25");
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
                            ORDER BY rh31_nome, rh31_dtnasc offset 8 limit 1' WHERE db148_sequencial = 26");
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
                            ORDER BY rh31_nome, rh31_dtnasc offset 9 limit 1' WHERE db148_sequencial = 27");
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
                              rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                ORDER BY rh31_nome, rh31_dtnasc
                OFFSET 0
                LIMIT 1;' WHERE db148_sequencial = 28");
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
                LIMIT 1;' WHERE db148_sequencial = 29");
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
                LIMIT 1;' WHERE db148_sequencial = 30");
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
                LIMIT 1;' WHERE db148_sequencial = 31");
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
                LIMIT 1;' WHERE db148_sequencial = 32");
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
                LIMIT 1;' WHERE db148_sequencial = 33");
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
                LIMIT 1;' WHERE db148_sequencial = 34");
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
                LIMIT 1;' WHERE db148_sequencial = 35");
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
                LIMIT 1;' WHERE db148_sequencial = 36");
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
                LIMIT 1;' WHERE db148_sequencial = 37");
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
                                      rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
                        ORDER BY rh31_nome, rh31_dtnasc
                        OFFSET 0
                        LIMIT 1;' WHERE db148_sequencial = 6698");
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
                        LIMIT 1;' WHERE db148_sequencial = 6702");
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
                        LIMIT 1;' WHERE db148_sequencial = 6706");
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
                        LIMIT 1;' WHERE db148_sequencial = 6710");
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
                        LIMIT 1;' WHERE db148_sequencial = 6714");
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
                        LIMIT 1;' WHERE db148_sequencial = 6718");
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
                        LIMIT 1;' WHERE db148_sequencial = 6722");
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
                        LIMIT 1;' WHERE db148_sequencial = 6726");
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
                        LIMIT 1;' WHERE db148_sequencial = 6730");
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
                        LIMIT 1;' WHERE db148_sequencial = 6734");
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
                          rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 0
            LIMIT 1;
            ' WHERE db148_sequencial = 6695");
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
            ' WHERE db148_sequencial = 6699");
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
            ' WHERE db148_sequencial = 6703");
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
            ' WHERE db148_sequencial = 6707");
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
            ' WHERE db148_sequencial = 6711");
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
            ' WHERE db148_sequencial = 6715");
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
            ' WHERE db148_sequencial = 6719");
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
            ' WHERE db148_sequencial = 6723");
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
            ' WHERE db148_sequencial = 6727");
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
            ' WHERE db148_sequencial = 6731");
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
                  rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])) AS x
            ORDER BY rh31_nome, rh31_dtnasc
            OFFSET 0
            LIMIT 1;
            ' WHERE db148_sequencial = 6696");
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
            ' WHERE db148_sequencial = 6700");
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
            ' WHERE db148_sequencial = 6704");
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
            ' WHERE db148_sequencial = 6708");
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
            ' WHERE db148_sequencial = 6712");
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
            ' WHERE db148_sequencial = 6716");
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
            ' WHERE db148_sequencial = 6720");
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
            ' WHERE db148_sequencial = 6724");
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
            ' WHERE db148_sequencial = 6728");
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
            ' WHERE db148_sequencial = 6732");
    }

    private function reverteFormulaTipo()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT
                    CASE
                    WHEN rh31_gparen = ''C'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_1''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_1''
                                                      AND db104_identificadorcampo = ''dependente_1_tpDep_01'')
                    WHEN rh31_gparen = ''F'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_1''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_1''
                                                      AND db104_identificadorcampo = ''dependente_1_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                      OR rh31_gparen = ''M''
                      OR rh31_gparen = ''A'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_1''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_1''
                                                      AND db104_identificadorcampo = ''dependente_1_tpDep_09'')
                    WHEN rh31_gparen = ''O'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_1''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_1''
                                                      AND db104_identificadorcampo = ''dependente_1_tpDep_99'')
                    END
              FROM rhdepend
              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
              ORDER BY rh31_codigo
              OFFSET 0
              LIMIT 1;' WHERE db148_sequencial = 6697");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT
                    CASE
                    WHEN rh31_gparen = ''C'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_2''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_2''
                                                      AND db104_identificadorcampo = ''dependente_2_tpDep_01'')
                    WHEN rh31_gparen = ''F'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_2''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_2''
                                                      AND db104_identificadorcampo = ''dependente_2_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                      OR rh31_gparen = ''M''
                      OR rh31_gparen = ''A'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_2''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_2''
                                                      AND db104_identificadorcampo = ''dependente_2_tpDep_09'')
                    WHEN rh31_gparen = ''O'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_2''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_2''
                                                      AND db104_identificadorcampo = ''dependente_2_tpDep_99'')
                    END
              FROM rhdepend
              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
              ORDER BY rh31_codigo
              OFFSET 1
              LIMIT 1;' WHERE db148_sequencial = 6701");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT
                    CASE
                    WHEN rh31_gparen = ''C'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_3''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_3''
                                                      AND db104_identificadorcampo = ''dependente_3_tpDep_01'')
                    WHEN rh31_gparen = ''F'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_3''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_3''
                                                      AND db104_identificadorcampo = ''dependente_3_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                      OR rh31_gparen = ''M''
                      OR rh31_gparen = ''A'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_3''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_3''
                                                      AND db104_identificadorcampo = ''dependente_3_tpDep_09'')
                    WHEN rh31_gparen = ''O'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_3''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_3''
                                                      AND db104_identificadorcampo = ''dependente_3_tpDep_99'')
                    END
              FROM rhdepend
              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
              ORDER BY rh31_codigo
              OFFSET 2
              LIMIT 1;' WHERE db148_sequencial = 6705");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT
                    CASE
                    WHEN rh31_gparen = ''C'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_4''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_4''
                                                      AND db104_identificadorcampo = ''dependente_4_tpDep_01'')
                    WHEN rh31_gparen = ''F'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_4''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_4''
                                                      AND db104_identificadorcampo = ''dependente_4_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                      OR rh31_gparen = ''M''
                      OR rh31_gparen = ''A'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_4''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_4''
                                                      AND db104_identificadorcampo = ''dependente_4_tpDep_09'')
                    WHEN rh31_gparen = ''O'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_4''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_4''
                                                      AND db104_identificadorcampo = ''dependente_4_tpDep_99'')
                    END
              FROM rhdepend
              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
              ORDER BY rh31_codigo
              OFFSET 3
              LIMIT 1;' WHERE db148_sequencial = 6709");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT
                    CASE
                    WHEN rh31_gparen = ''C'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_5''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_5''
                                                      AND db104_identificadorcampo = ''dependente_5_tpDep_01'')
                    WHEN rh31_gparen = ''F'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_5''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_5''
                                                      AND db104_identificadorcampo = ''dependente_5_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                      OR rh31_gparen = ''M''
                      OR rh31_gparen = ''A'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_5''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_5''
                                                      AND db104_identificadorcampo = ''dependente_5_tpDep_09'')
                    WHEN rh31_gparen = ''O'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_5''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_5''
                                                      AND db104_identificadorcampo = ''dependente_5_tpDep_99'')
                    END
              FROM rhdepend
              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
              ORDER BY rh31_codigo
              OFFSET 4
              LIMIT 1;' WHERE db148_sequencial = 6713");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT
                    CASE
                    WHEN rh31_gparen = ''C'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_6''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_6''
                                                      AND db104_identificadorcampo = ''dependente_6_tpDep_01'')
                    WHEN rh31_gparen = ''F'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_6''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_6''
                                                      AND db104_identificadorcampo = ''dependente_6_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                      OR rh31_gparen = ''M''
                      OR rh31_gparen = ''A'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_6''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_6''
                                                      AND db104_identificadorcampo = ''dependente_6_tpDep_09'')
                    WHEN rh31_gparen = ''O'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_6''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_6''
                                                      AND db104_identificadorcampo = ''dependente_6_tpDep_99'')
                    END
              FROM rhdepend
              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
              ORDER BY rh31_codigo
              OFFSET 5
              LIMIT 1;' WHERE db148_sequencial = 6717");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT
                    CASE
                    WHEN rh31_gparen = ''C'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_7''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_7''
                                                      AND db104_identificadorcampo = ''dependente_7_tpDep_01'')
                    WHEN rh31_gparen = ''F'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_7''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_7''
                                                      AND db104_identificadorcampo = ''dependente_7_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                      OR rh31_gparen = ''M''
                      OR rh31_gparen = ''A'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_7''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_7''
                                                      AND db104_identificadorcampo = ''dependente_7_tpDep_09'')
                    WHEN rh31_gparen = ''O'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_7''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_7''
                                                      AND db104_identificadorcampo = ''dependente_7_tpDep_99'')
                    END
              FROM rhdepend
              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
              ORDER BY rh31_codigo
              OFFSET 6
              LIMIT 1;' WHERE db148_sequencial = 6721");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT
                    CASE
                    WHEN rh31_gparen = ''C'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_8''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_8''
                                                      AND db104_identificadorcampo = ''dependente_8_tpDep_01'')
                    WHEN rh31_gparen = ''F'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_8''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_8''
                                                      AND db104_identificadorcampo = ''dependente_8_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                      OR rh31_gparen = ''M''
                      OR rh31_gparen = ''A'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_8''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_8''
                                                      AND db104_identificadorcampo = ''dependente_8_tpDep_09'')
                    WHEN rh31_gparen = ''O'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_8''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_8''
                                                      AND db104_identificadorcampo = ''dependente_8_tpDep_99'')
                    END
              FROM rhdepend
              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
              ORDER BY rh31_codigo
              OFFSET 7
              LIMIT 1;' WHERE db148_sequencial = 6725");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT
                    CASE
                    WHEN rh31_gparen = ''C'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_9''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_9''
                                                      AND db104_identificadorcampo = ''dependente_9_tpDep_01'')
                    WHEN rh31_gparen = ''F'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_9''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_9''
                                                      AND db104_identificadorcampo = ''dependente_9_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                      OR rh31_gparen = ''M''
                      OR rh31_gparen = ''A'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_9''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_9''
                                                      AND db104_identificadorcampo = ''dependente_9_tpDep_09'')
                    WHEN rh31_gparen = ''O'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_9''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_9''
                                                      AND db104_identificadorcampo = ''dependente_9_tpDep_99'')
                    END
              FROM rhdepend
              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
              ORDER BY rh31_codigo
              OFFSET 8
              LIMIT 1;' WHERE db148_sequencial = 6729");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT
                    CASE
                    WHEN rh31_gparen = ''C'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_10''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_10''
                                                      AND db104_identificadorcampo = ''dependente_10_tpDep_01'')
                    WHEN rh31_gparen = ''F'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_10''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_10''
                                                      AND db104_identificadorcampo = ''dependente_10_tpDep_03'')
                    WHEN rh31_gparen = ''P''
                      OR rh31_gparen = ''M''
                      OR rh31_gparen = ''A'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_10''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_10''
                                                      AND db104_identificadorcampo = ''dependente_10_tpDep_09'')
                    WHEN rh31_gparen = ''O'' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = ''tpDep_10''
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = ''dependente_10''
                                                      AND db104_identificadorcampo = ''dependente_10_tpDep_99'')
                    END
              FROM rhdepend
              WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
              ORDER BY rh31_codigo
              OFFSET 9
              LIMIT 1;' WHERE db148_sequencial = 6733");
    }

    private function reverteFormulaNome()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_nome as nome from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 0 limit 1' WHERE db148_sequencial = 18");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_nome as nome from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 9 limit 1' WHERE db148_sequencial = 27");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_nome as nome from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 1 limit 1' WHERE db148_sequencial = 19");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_nome as nome from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 2 limit 1' WHERE db148_sequencial = 20");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_nome as nome from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 3 limit 1' WHERE db148_sequencial = 21");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_nome as nome from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 4 limit 1' WHERE db148_sequencial = 22");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_nome as nome from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 5 limit 1' WHERE db148_sequencial = 23");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_nome as nome from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 6 limit 1' WHERE db148_sequencial = 24");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_nome as nome from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 7 limit 1' WHERE db148_sequencial = 25");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_nome as nome from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 8 limit 1' WHERE db148_sequencial = 26");
    }

    private function reverteFormulaDataNascimento()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_dtnasc as data_nascimento from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 0 limit 1' WHERE db148_sequencial = 28");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_dtnasc as data_nascimento from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 9 limit 1' WHERE db148_sequencial = 37");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_dtnasc as data_nascimento from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 1 limit 1' WHERE db148_sequencial = 29");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_dtnasc as data_nascimento from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 2 limit 1' WHERE db148_sequencial = 30");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_dtnasc as data_nascimento from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 3 limit 1' WHERE db148_sequencial = 31");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_dtnasc as data_nascimento from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 4 limit 1' WHERE db148_sequencial = 32");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_dtnasc as data_nascimento from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 5 limit 1' WHERE db148_sequencial = 33");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_dtnasc as data_nascimento from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 6 limit 1' WHERE db148_sequencial = 34");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_dtnasc as data_nascimento from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 7 limit 1' WHERE db148_sequencial = 35");
        $this->execute("UPDATE db_formulas SET db148_formula = 'select rh31_dtnasc as data_nascimento from rhdepend where (rh31_depend <> ''N'' or rh31_irf <> ''0'') and rh31_regist in (select rh01_regist from rhpessoal where rh01_numcgm = [CODIGO_CGM]) order by rh31_codigo offset 8 limit 1' WHERE db148_sequencial = 36");
    }

    private function reverteFormulaCpf()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT dp01_cpf
                FROM rhdepend
                INNER JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
                WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') 
                  AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                ORDER BY rh31_codigo 
                OFFSET 0
                LIMIT 1;' WHERE db148_sequencial = 6698");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT dp01_cpf
                FROM rhdepend
                INNER JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
                WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') 
                  AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                ORDER BY rh31_codigo 
                OFFSET 1
                LIMIT 1;' WHERE db148_sequencial = 6702");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT dp01_cpf
                FROM rhdepend
                INNER JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
                WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') 
                  AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                ORDER BY rh31_codigo 
                OFFSET 2
                LIMIT 1;' WHERE db148_sequencial = 6706");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT dp01_cpf
                FROM rhdepend
                INNER JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
                WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') 
                  AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                ORDER BY rh31_codigo 
                OFFSET 3
                LIMIT 1;' WHERE db148_sequencial = 6710");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT dp01_cpf
                FROM rhdepend
                INNER JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
                WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') 
                  AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                ORDER BY rh31_codigo 
                OFFSET 4
                LIMIT 1;' WHERE db148_sequencial = 6714");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT dp01_cpf
                FROM rhdepend
                INNER JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
                WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') 
                  AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                ORDER BY rh31_codigo 
                OFFSET 5
                LIMIT 1;' WHERE db148_sequencial = 6718");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT dp01_cpf
                FROM rhdepend
                INNER JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
                WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') 
                  AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                ORDER BY rh31_codigo 
                OFFSET 6
                LIMIT 1;' WHERE db148_sequencial = 6722");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT dp01_cpf
                FROM rhdepend
                INNER JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
                WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') 
                  AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                ORDER BY rh31_codigo 
                OFFSET 7
                LIMIT 1;' WHERE db148_sequencial = 6726");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT dp01_cpf
                FROM rhdepend
                INNER JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
                WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') 
                  AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                ORDER BY rh31_codigo 
                OFFSET 8
                LIMIT 1;' WHERE db148_sequencial = 6730");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT dp01_cpf
                FROM rhdepend
                INNER JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
                WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'') 
                  AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                ORDER BY rh31_codigo 
                OFFSET 9
                LIMIT 1;' WHERE db148_sequencial = 6734");
    }

    private function reverteFormulaImpostoRenda()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 0
                      LIMIT 1) :: INTEGER <> 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 0
                      LIMIT 1) :: INTEGER = 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_1'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6695");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 1
                      LIMIT 1) :: INTEGER <> 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_2'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_2'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 1
                      LIMIT 1) :: INTEGER = 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_2'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_2'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6699");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 2
                      LIMIT 1) :: INTEGER <> 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_3'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_3'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 2
                      LIMIT 1) :: INTEGER = 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_3'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_3'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6703");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 3
                      LIMIT 1) :: INTEGER <> 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_4'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_4'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 3
                      LIMIT 1) :: INTEGER = 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_4'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_4'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6707");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 4
                      LIMIT 1) :: INTEGER <> 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_5'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_5'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 4
                      LIMIT 1) :: INTEGER = 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_5'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_5'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6711");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 5
                      LIMIT 1) :: INTEGER <> 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_6'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_6'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 5
                      LIMIT 1) :: INTEGER = 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_6'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_6'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6715");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 6
                      LIMIT 1) :: INTEGER <> 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_7'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_7'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 6
                      LIMIT 1) :: INTEGER = 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_7'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_7'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6719");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 7
                      LIMIT 1) :: INTEGER <> 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_8'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_8'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 7
                      LIMIT 1) :: INTEGER = 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_8'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_8'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6723");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 8
                      LIMIT 1) :: INTEGER <> 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_9'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_9'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 8
                      LIMIT 1) :: INTEGER = 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_9'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_9'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6727");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 9
                      LIMIT 1) :: INTEGER <> 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_10'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_10'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 9
                      LIMIT 1) :: INTEGER = 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depIRRF_10'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_10'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6731");
    }

    private function reverteFormulaSalarioFamilia()
    {
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 0
                      LIMIT 1) <> ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 0
                      LIMIT 1) = ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_1'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_1'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6696");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 1
                      LIMIT 1) <> ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_2'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_2'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 1
                      LIMIT 1) = ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_2'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_2'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6700");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 2
                      LIMIT 1) <> ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_3'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_3'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 2
                      LIMIT 1) = ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_3'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_3'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6704");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 3
                      LIMIT 1) <> ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_4'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_4'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 3
                      LIMIT 1) = ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_4'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_4'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6708");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 4
                      LIMIT 1) <> ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_5'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_5'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 4
                      LIMIT 1) = ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_5'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_5'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6712");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 5
                      LIMIT 1) <> ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_6'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_6'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 5
                      LIMIT 1) = ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_6'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_6'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6716");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 6
                      LIMIT 1) <> ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_7'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_7'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 6
                      LIMIT 1) = ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_7'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_7'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6720");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 7
                      LIMIT 1) <> ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_8'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_8'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 7
                      LIMIT 1) = ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_8'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_8'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6724");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 8
                      LIMIT 1) <> ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_9'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_9'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 8
                      LIMIT 1) = ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_9'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_9'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6728");
        $this->execute("UPDATE db_formulas SET db148_formula = '
                SELECT CASE WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 9
                      LIMIT 1) <> ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_10'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_10'' AND db104_valorresposta = ''S'')
                WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> ''N'' OR rh31_irf <> ''0'')
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET 9
                      LIMIT 1) = ''N''
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = ''depSF_10'' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = ''dependente_10'' AND db104_valorresposta = ''N'')
                ELSE NULL END
            ' WHERE db148_sequencial = 6732");
    }
}

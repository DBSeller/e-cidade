<?php

use Classes\PostgresMigration;

class M11094PreenchimentoFormularioServidor extends PostgresMigration
{
    public function up()
    {
        $this->upGrupoVinculoTrabalho();
        $this->upGrupoCeletista();
        $this->upGrupoDependentes();
        $this->upGrupoFGTS();
        $this->upGrupoEstatutarios();
        $this->upGrupoLocalTrabalho();
        $this->upGrupoDuracaoContratoTrabalho();
        $this->upTabelaDependentesPlug();
        $this->upDicionarioDados();
    }

    public function down()
    {   
        $this->downGrupoDuracaoContratoTrabalho();
        $this->downGrupoLocalTrabalho();
        $this->downGrupoEstatutarios();
        $this->downGrupoFGTS();
        $this->downGrupoDependentes();
        $this->downGrupoCeletista();
        $this->downGrupoVinculoTrabalho();
        $this->downTabelaDependentesPlug();
        $this->downDicionarioDados();
    }

    private function upGrupoVinculoTrabalho()
    {
        $sqlFormula  = " SELECT CASE ";
        $sqlFormula .= "            WHEN rhcadregime.rh52_regime = 2 THEN 3003376 ";
        $sqlFormula .= "            ELSE 3003377 ";
        $sqlFormula .= "        END AS regime ";
        $sqlFormula .= " FROM rhpessoalmov ";
        $sqlFormula .= " INNER JOIN rhregime ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg ";
        $sqlFormula .= " INNER JOIN rhcadregime ON rhcadregime.rh52_regime = rhregime.rh30_regime ";
        $sqlFormula .= " WHERE rhpessoalmov.rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]           ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO])  ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]); ";

        $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_CODIGO_REGIME', 'Retorna o código de regime do servidor.', '{$sqlFormula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000795);";
        $this->execute($sql);
    }

    private function downGrupoVinculoTrabalho()
    {
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta = 3000795;";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_CODIGO_REGIME';";
        $this->execute($sql);
    }

    private function upGrupoCeletista()
    {
        //Tipo de Admissão
        $sqlFormula  = " SELECT 3003385 ";
        $sqlFormula .= " FROM rhpessoalmov ";
        $sqlFormula .= " INNER JOIN rhregime ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg ";
        $sqlFormula .= " INNER JOIN rhcadregime ON rhcadregime.rh52_regime = rhregime.rh30_regime ";
        $sqlFormula .= " WHERE rhpessoalmov.rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]           ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO])  ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) ";
        $sqlFormula .= "   AND rhcadregime.rh52_regime = 2; ";

        $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_CLT_TIPO_ADMISSAO', 'Retorna o tipo de regime do servidor.', '{$sqlFormula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000800);";
        $this->execute($sql);

        //Data de Admissão
        $sqlFormula  = " SELECT rhpessoal.rh01_admiss ";
        $sqlFormula .= " FROM rhpessoalmov ";
        $sqlFormula .= " INNER JOIN rhpessoal ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist ";
        $sqlFormula .= " INNER JOIN rhregime ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg ";
        $sqlFormula .= " INNER JOIN rhcadregime ON rhcadregime.rh52_regime = rhregime.rh30_regime ";
        $sqlFormula .= " WHERE rhpessoalmov.rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]           ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO])  ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) ";
        $sqlFormula .= "   AND rhcadregime.rh52_regime = 2; ";

        $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_CLT_DATA_ADMISSAO', 'Retorna o código de regime do servidor.', '{$sqlFormula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000799);";
        $this->execute($sql);

        //Indicativo de Admissão
        $sqlFormula  = " SELECT 3003389 ";
        $sqlFormula .= " FROM rhpessoalmov ";
        $sqlFormula .= " INNER JOIN rhregime ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg ";
        $sqlFormula .= " INNER JOIN rhcadregime ON rhcadregime.rh52_regime = rhregime.rh30_regime ";
        $sqlFormula .= " WHERE rhpessoalmov.rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]           ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO])  ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) ";
        $sqlFormula .= "   AND rhcadregime.rh52_regime = 2; ";

        $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_CLT_INDICATIVO_ADMISSAO', 'Retorna o código de regime do servidor.', '{$sqlFormula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000801);";
        $this->execute($sql);

        //Regime de Jornada de Trabalho
        $sqlFormula  = " SELECT 3003392 ";
        $sqlFormula .= " FROM rhpessoalmov ";
        $sqlFormula .= " INNER JOIN rhregime ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg ";
        $sqlFormula .= " INNER JOIN rhcadregime ON rhcadregime.rh52_regime = rhregime.rh30_regime ";
        $sqlFormula .= " WHERE rhpessoalmov.rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]           ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO])  ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) ";
        $sqlFormula .= "   AND rhcadregime.rh52_regime = 2; ";

        $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_CLT_REGIME_JORNADA', 'Retorna o código de regime do servidor.', '{$sqlFormula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000802);";
        $this->execute($sql);

        //Natureza da atividade
        $sqlFormula  = " SELECT 3003395 ";
        $sqlFormula .= " FROM rhpessoalmov ";
        $sqlFormula .= " INNER JOIN rhregime ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg ";
        $sqlFormula .= " INNER JOIN rhcadregime ON rhcadregime.rh52_regime = rhregime.rh30_regime ";
        $sqlFormula .= " WHERE rhpessoalmov.rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]           ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO])  ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) ";
        $sqlFormula .= "   AND rhcadregime.rh52_regime = 2; ";

        $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_CLT_NATUREZA_ATIVIDADE', 'Retorna o código de regime do servidor.', '{$sqlFormula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000803);";
        $this->execute($sql);

        //CNPJ do sindicato representativo da categoria
        $sqlFormula  = " SELECT rh116_cnpj ";
        $sqlFormula .= " FROM rhpessoalmov ";
        $sqlFormula .= " INNER JOIN rhpessoal ON rhpessoal.rh01_regist = rhpessoalmov.rh02_regist ";
        $sqlFormula .= " INNER JOIN rhsindicato ON rhsindicato.rh116_sequencial = rhpessoal.rh01_rhsindicato ";
        $sqlFormula .= " INNER JOIN rhregime ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg ";
        $sqlFormula .= " INNER JOIN rhcadregime ON rhcadregime.rh52_regime = rhregime.rh30_regime ";
        $sqlFormula .= " WHERE rhpessoalmov.rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]           ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO])  ";
        $sqlFormula .= "   AND rhpessoalmov.rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO]) ";
        $sqlFormula .= "   AND rhcadregime.rh52_regime = 2; ";

        $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_CLT_CNPJ_SINDICATO', 'Retorna o código de regime do servidor.', '{$sqlFormula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000805);";
        $this->execute($sql);
    }

    private function downGrupoCeletista()
    {
        //Tipo de Admissão
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta = 3000800;";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_CLT_TIPO_ADMISSAO';";
        $this->execute($sql);

        //Natureza da atividade
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta = 3000805;";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_CLT_CNPJ_SINDICATO';";
        $this->execute($sql);

         //Natureza da atividade
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta = 3000803;";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_CLT_NATUREZA_ATIVIDADE';";
        $this->execute($sql);

        //Regime de Jornada de Trabalho
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta = 3000802;";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_CLT_REGIME_JORNADA';";
        $this->execute($sql);

        //Indicativo de Admissão
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta = 3000801;";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_CLT_INDICATIVO_ADMISSAO';";
        $this->execute($sql);

        //Data de Admissão
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta = 3000799;";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_CLT_DATA_ADMISSAO';";
        $this->execute($sql);
    }

    private function upGrupoDependentes()
    {
        for ($i = 0; $i < 10; $i++) {
            $x = $i+1;
            $formulaIRF = "
                SELECT CASE WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> 'N' OR rh31_irf <> '0') 
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET {$i}
                      LIMIT 1) :: INTEGER <> 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = 'depIRRF_{$x}' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = 'dependente_{$x}' AND db104_valorresposta = 'S')
                WHEN (SELECT rh31_irf
                      FROM rhdepend
                      WHERE (rh31_depend <> 'N' OR rh31_irf <> '0') 
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET {$i}
                      LIMIT 1) :: INTEGER = 0
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = 'depIRRF_{$x}' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = 'dependente_{$x}' AND db104_valorresposta = 'N')
                ELSE NULL END
            ";
            $formulaIRF = addslashes($formulaIRF);

            $nomeFormulaIRF = 'ESOCIAL_IRF_DEPENDENTE_' . $x;

            $sql = "
                INSERT INTO db_formulas 
                  (db148_sequencial, db148_nome, db148_descricao, db148_formula, db148_ambiente) VALUES 
                  ((select max(db148_sequencial) + 1 from db_formulas), '{$nomeFormulaIRF}', 'Retorna se é dependente IR.', '{$formulaIRF}', false);  
            ";

            $this->execute($sql);

            $sql = "
                INSERT INTO avaliacaoperguntadb_formulas VALUES (
                  nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), 
                  (select max(db148_sequencial) from db_formulas),
                  (
                    select db103_sequencial 
                    from avaliacaopergunta 
                      inner join avaliacaogrupopergunta on avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta 
                    where db103_identificadorcampo = 'depIRRF_{$x}' 
                      and db102_avaliacao = 3000013 
                      and db102_identificadorcampo = 'dependente_{$x}'
                  ) 
                 );
            ";

            $this->execute($sql);

            $formulaSalarioFamilia = "
                SELECT CASE WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> 'N' OR rh31_irf <> '0') 
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET {$i}
                      LIMIT 1) <> 'N'
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = 'depSF_{$x}' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = 'dependente_{$x}' AND db104_valorresposta = 'S')
                WHEN (SELECT rh31_depend
                      FROM rhdepend
                      WHERE (rh31_depend <> 'N' OR rh31_irf <> '0') 
                        AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                      ORDER BY rh31_codigo
                      OFFSET {$i}
                      LIMIT 1) = 'N'
                     THEN (SELECT db104_sequencial
                           FROM avaliacaogrupopergunta
                                  INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta =
                                                                  avaliacaogrupopergunta.db102_sequencial
                                  INNER JOIN avaliacaoperguntaopcao
                                    ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                           WHERE db103_identificadorcampo = 'depSF_{$x}' AND db102_avaliacao = 3000013 AND
                                   db102_identificadorcampo = 'dependente_{$x}' AND db104_valorresposta = 'N')
                ELSE NULL END
            ";
            $formulaSalarioFamilia = addslashes($formulaSalarioFamilia);

            $nomeFormulaSalarioFamilia = 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_' . $x;

            $sql = "
                INSERT INTO db_formulas 
                  (db148_sequencial, db148_nome, db148_descricao, db148_formula, db148_ambiente) VALUES 
                  ((select max(db148_sequencial) + 1 from db_formulas), '{$nomeFormulaSalarioFamilia}', 'Retorna se é dependente salário família.', '{$formulaSalarioFamilia}', false);  
            ";

            $this->execute($sql);

            $sql = "
                INSERT INTO avaliacaoperguntadb_formulas VALUES (
                  nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), 
                  (select max(db148_sequencial) from db_formulas),
                  (
                    select db103_sequencial 
                    from avaliacaopergunta 
                      inner join avaliacaogrupopergunta on avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta 
                    where db103_identificadorcampo = 'depSF_{$x}' 
                      and db102_avaliacao = 3000013 
                      and db102_identificadorcampo = 'dependente_{$x}'
                  ) 
                 );
            ";

            $this->execute($sql);

            $sqlFormulaTipo = "
                SELECT
                    CASE
                    WHEN rh31_gparen = 'C' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = 'tpDep_{$x}'
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = 'dependente_{$x}'
                                                      AND db104_identificadorcampo = 'dependente_{$x}_tpDep_01')
                    WHEN rh31_gparen = 'F' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = 'tpDep_{$x}'
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = 'dependente_{$x}'
                                                      AND db104_identificadorcampo = 'dependente_{$x}_tpDep_03')
                    WHEN rh31_gparen = 'P' 
                      OR rh31_gparen = 'M'
                      OR rh31_gparen = 'A' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = 'tpDep_{$x}'
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = 'dependente_{$x}'
                                                      AND db104_identificadorcampo = 'dependente_{$x}_tpDep_09')
                    WHEN rh31_gparen = 'O' THEN (  SELECT db104_sequencial
                                                    FROM avaliacaogrupopergunta
                                                    INNER JOIN avaliacaopergunta ON avaliacaopergunta.db103_avaliacaogrupopergunta = avaliacaogrupopergunta.db102_sequencial
                                                    INNER JOIN avaliacaoperguntaopcao ON avaliacaoperguntaopcao.db104_avaliacaopergunta = avaliacaopergunta.db103_sequencial
                                                    WHERE db103_identificadorcampo = 'tpDep_{$x}'
                                                      AND db102_avaliacao = 3000013
                                                      AND db102_identificadorcampo = 'dependente_{$x}'
                                                      AND db104_identificadorcampo = 'dependente_{$x}_tpDep_99')
                    END
              FROM rhdepend
              WHERE (rh31_depend <> 'N' OR rh31_irf <> '0') 
                AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
              ORDER BY rh31_codigo 
              OFFSET {$i}
              LIMIT 1;";

            $sqlFormulaTipo = addslashes($sqlFormulaTipo);

            $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_DEPENDENTE_TIPO_{$x}', 'Retorna o tipo de dependente.', '{$sqlFormulaTipo}', false);";
            $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (
                          nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), 
                          (SELECT max(db148_sequencial) FROM db_formulas), 
                          (
                            select db103_sequencial 
                            from avaliacaopergunta 
                              inner join avaliacaogrupopergunta on avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta 
                            where db103_identificadorcampo = 'tpDep_{$x}' 
                              and db102_avaliacao = 3000013 
                              and db102_identificadorcampo = 'dependente_{$x}'
                          ) 
                    );";

            $this->execute($sql);

            $sqlFormulaCpf = "
                SELECT dp01_cpf
                FROM rhdepend
                INNER JOIN rhdependeplug ON dp01_rhdepend = rh31_codigo
                WHERE (rh31_depend <> 'N' OR rh31_irf <> '0') 
                  AND rh31_regist IN (SELECT rh01_regist FROM rhpessoal WHERE rh01_numcgm = [CODIGO_CGM])
                ORDER BY rh31_codigo 
                OFFSET {$i}
                LIMIT 1;";

            $sqlFormulaCpf = addslashes($sqlFormulaCpf);

            $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_DEPENDENTE_CPF_{$x}', 'Retorna o CPF do dependente.', '{$sqlFormulaCpf}', false);";
            $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (
                          nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), 
                          (SELECT max(db148_sequencial) FROM db_formulas), 
                          (
                            select db103_sequencial 
                            from avaliacaopergunta 
                              inner join avaliacaogrupopergunta on avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta 
                            where db103_identificadorcampo = 'cpfDep_{$x}' 
                              and db102_avaliacao = 3000013 
                              and db102_identificadorcampo = 'dependente_{$x}'
                          ) 
                    );";

            $this->execute($sql);
        }
    }

    private function downGrupoDependentes()
    {
        $sql = "
          DELETE FROM avaliacaoperguntadb_formulas 
          WHERE eso01_avaliacaopergunta IN(
            select db103_sequencial 
              from avaliacaopergunta 
            where db103_avaliacaogrupopergunta >= 3000160 
              and db103_avaliacaogrupopergunta <= 3000169 
              and (db103_identificadorcampo ilike 'tpDep_%' 
                   or db103_identificadorcampo ilike 'depIRRF_%'
                   or db103_identificadorcampo ilike 'depSF_%'
                   or db103_identificadorcampo ilike 'cpfDep_%')
          )
        ";
        $this->execute($sql);

        $this->execute("DELETE FROM db_formulas WHERE db148_nome ILIKE 'ESOCIAL_IRF_DEPENDENTE_%'");
        $this->execute("DELETE FROM db_formulas WHERE db148_nome ILIKE 'ESOCIAL_SALARIO_FAMILIA_DEPENDENTE_%'");
        $this->execute("DELETE FROM db_formulas WHERE db148_nome ILIKE 'ESOCIAL_DEPENDENTE_TIPO_%'");
        $this->execute("DELETE FROM db_formulas WHERE db148_nome ILIKE 'ESOCIAL_DEPENDENTE_CPF_%'");
    }

    private function upGrupoFGTS()
    {   
        //Data de opção pelo FGTS
        $sqlFormula = "SELECT 3003399 FROM rhpesfgts WHERE rh15_regist = [ESOCIAL_MATRICULA_SERVIDOR] AND rh15_data is not null;";

        $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_FGTS_OPTANTE', 'Retorna o código de regime do servidor.', '{$sqlFormula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000806);";
        $this->execute($sql);

        //Data de opção pelo FGTS
        $sqlFormula = "SELECT rh15_data FROM rhpesfgts WHERE rh15_regist = [ESOCIAL_MATRICULA_SERVIDOR];";

        $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_FGTS_DATA', 'Retorna o código de regime do servidor.', '{$sqlFormula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000807);";
        $this->execute($sql);
    }

    private function downGrupoFGTS()
    {
        //Data de opção pelo FGTS
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta = 3000806;";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_FGTS_OPTANTE';";
        $this->execute($sql);

        //Data de opção pelo FGTS
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta = 3000807;";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_FGTS_DATA';";
        $this->execute($sql);
    }

    private function upGrupoEstatutarios()
    {
        $formula = "
            SELECT 
                CASE WHEN rh52_regime = 1 THEN
                  '3003418'
                ELSE NULL END
            FROM rhpessoalmov
                   INNER JOIN rhregime ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
                   INNER JOIN rhcadregime ON rhcadregime.rh52_regime = rhregime.rh30_regime
            WHERE rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO])
                AND rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO])
                AND rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]
        ";
        $formula = addslashes($formula);

        $sql  = "INSERT INTO db_formulas VALUES ((SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_ESTATUTARIO_INDICACAO_PROVIMENTO', 'Retorna indicação de provimento.', '{$formula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000818);";
        $this->execute($sql);

        $formula = "
            SELECT 
                CASE WHEN rh52_regime = 1 THEN
                  CASE WHEN rh71_sequencial = 2 THEN
                    '3003422'
                  ELSE
                    '3003421'
                  END
                ELSE NULL END
            FROM rhpessoalmov
                   INNER JOIN rhregime ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
                   INNER JOIN rhcadregime ON rhcadregime.rh52_regime = rhregime.rh30_regime
                   INNER JOIN rhnaturezaregime ON rhnaturezaregime.rh71_sequencial = rhregime.rh30_naturezaregime
            WHERE rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO])
                AND rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO])
                AND rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]
        ";
        $formula = addslashes($formula);

        $sql  = "INSERT INTO db_formulas VALUES ((SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_ESTATUTARIO_TIPO_PROVIMENTO', 'Retorna tipo de provimento.', '{$formula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000819);";
        $this->execute($sql);

    }

    private function downGrupoEstatutarios()
    {
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta IN(3000818, 3000819);";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_ESTATUTARIO_INDICACAO_PROVIMENTO';";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_ESTATUTARIO_TIPO_PROVIMENTO';";

        $this->execute($sql);
    }

    private function upGrupoLocalTrabalho()
    {
        $sql  = "INSERT INTO db_formulas VALUES ((SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_LOCAL_TRABALHO_TIPO_INSCRICAO', 'Retorna o tipo de inscrição.', 'SELECT 3003489', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000836);";
        $this->execute($sql);

        $formula = "
            SELECT db_config.cgc
            FROM rhpessoalmov
                INNER JOIN rhlota ON rhlota.r70_codigo = rhpessoalmov.rh02_lota
                INNER JOIN db_config ON db_config.codigo = rhlota.r70_instit
            WHERE rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO])
                AND rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO])
                AND rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]
        ";
        $formula = addslashes($formula);

        $sql  = "INSERT INTO db_formulas VALUES ((SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_LOCAL_TRABALHO_CNPJ', 'Retorna o CNPJ da instituição lotada.', '{$formula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000837);";
        $this->execute($sql);

        $formula = "
             select (case when rh55_descr <> '' and rh55_descr is not null THEN
                    rh55_descr
                    ELSE r70_descr end) as descricao
             from rhpessoalmov
                 inner join rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota
                 left join rhpeslocaltrab on rh56_seqpes = rh02_seqpes and rh56_princ is true
                 left join rhlocaltrab on rh55_codigo = rh56_localtrab
             where rh02_anousu = fc_anofolha([ESOCIAL_INSTITUICAO])
               AND rh02_mesusu = fc_mesfolha([ESOCIAL_INSTITUICAO])
               AND rh02_regist = [ESOCIAL_MATRICULA_SERVIDOR]
             limit 1;
        ";
        $formula = addslashes($formula);

        $sql  = "INSERT INTO db_formulas VALUES ((SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_LOCAL_TRABALHO_LOTACAO', 'Retorna o local de trabalho ou descrição da lotação.', '{$formula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000838);";

        $this->execute($sql);
    }

    private function downGrupoLocalTrabalho()
    {
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta IN(3000836, 3000837, 3000838);";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_LOCAL_TRABALHO_TIPO_INSCRICAO';";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_LOCAL_TRABALHO_CNPJ';";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_LOCAL_TRABALHO_LOTACAO';";

        $this->execute($sql);
    }

    private function upGrupoDuracaoContratoTrabalho()
    {
        //Tipo de contrato
        $sqlFormula = "
            SELECT CASE WHEN (SELECT 1
                      FROM rhcontratoemergencialrenovacao
                             INNER JOIN rhcontratoemergencial ON rh163_sequencial = rh164_contratoemergencial
                      WHERE rh163_matricula = [ESOCIAL_MATRICULA_SERVIDOR] LIMIT 1) = 1
                     THEN 3003485
                ELSE 3003484
            END;
        ";

        $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_DURACAO_CONTRATO_TIPO', 'Retorna o código de regime do servidor.', '{$sqlFormula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000833);";
        $this->execute($sql);

        //Data do Término
        $sqlFormula = "select rh164_datafim from rhcontratoemergencialrenovacao inner join rhcontratoemergencial on rh163_matricula = [ESOCIAL_MATRICULA_SERVIDOR] order by rh164_datafim desc limit 1;";

        $sql  = "INSERT INTO db_formulas VALUES ( (SELECT max(db148_sequencial) + 1 FROM db_formulas), 'ESOCIAL_DURACAO_CONTRATO_DATA_TERMINO', 'Retorna o código de regime do servidor.', '{$sqlFormula}', false);";
        $sql .= "INSERT INTO avaliacaoperguntadb_formulas VALUES (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (SELECT max(db148_sequencial) FROM db_formulas), 3000834);";
        $this->execute($sql);
    }

    private function downGrupoDuracaoContratoTrabalho()
    {
        //Data do Término
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta = 3000834;";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_DURACAO_CONTRATO_DATA_TERMINO';";
        $this->execute($sql);

        //Tipo de contrato
        $sql  = "DELETE FROM avaliacaoperguntadb_formulas WHERE eso01_avaliacaopergunta = 3000833;";
        $sql .= "DELETE FROM db_formulas WHERE db148_nome = 'ESOCIAL_DURACAO_CONTRATO_TIPO';";
        $this->execute($sql);
    }

    private function upTabelaDependentesPlug()
    {
        $this->table('rhdependeplug', array(
            'schema' => 'pessoal',
            'id' => 'dp01_codigo'
        ))
            ->addColumn('dp01_rhdepend', 'integer')
            ->addColumn('dp01_regist', 'integer')
            ->addColumn('dp01_processo', 'integer', array('null' => true))
            ->addColumn('dp01_instit', 'integer', array('null' => true))
            ->addColumn('dp01_cpf', 'string', array('limit' => 14, 'null' => true))
            ->addColumn('dp01_sexo', 'string', array('limit' => 1, 'null' => true))
            ->addForeignKey('dp01_rhdepend', 'rhdepend', 'rh31_codigo')
            ->addForeignKey('dp01_regist', 'rhpessoal', 'rh01_regist')
            ->addForeignKey('dp01_instit', 'db_config', 'codigo')
            ->save();
    }

    private function downTabelaDependentesPlug()
    {
        $this->table('rhdependeplug', array('schema' => 'pessoal'))
            ->drop();
    }

    private function upDicionarioDados()
    {
        $sql = "
            insert into db_sysarquivo values (1010304, 'rhdependeplug', 'Tabela com complementos de dependentes', 'dp01', '2018-08-17', 'rhdependeplug', 0, 't', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010304);
            insert into db_syscampo values(1009888,'dp01_codigo','int4','Código sequencial da tabela','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1009889,'dp01_rhdepend','int4','Código para vínculo com dependente.','0', 'Código do Dependente',10,'f','f','f',1,'text','Código do Dependente');
            insert into db_syscampo values(1009890,'dp01_regist','int4','Vínculo com o servidor.','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
            insert into db_syscampo values(1009891,'dp01_processo','int4','Processo','0', 'Processo',10,'t','f','f',1,'text','Processo');
            insert into db_syscampo values(1009892,'dp01_instit','int4','Vínculo com a instituição.','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
            insert into db_syscampo values(1009893,'dp01_cpf','varchar(14)','CPF do dependente.','0', 'CPF',10,'t','f','f',1,'text','CPF');
            insert into db_syscampo values(1009894,'dp01_sexo','varchar(1)','Sexo do dependente.','', 'Sexo',1,'t','t','f',0,'text','Sexo');
            insert into db_sysarqcamp values(1010304,1009888,1,0);
            insert into db_sysarqcamp values(1010304,1009892,2,0);
            insert into db_sysarqcamp values(1010304,1009889,3,0);
            insert into db_sysarqcamp values(1010304,1009891,4,0);
            insert into db_sysarqcamp values(1010304,1009894,5,0);
            insert into db_sysarqcamp values(1010304,1009890,6,0);
            insert into db_sysarqcamp values(1010304,1009893,7,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010304,1009888,1,1009888);
            insert into db_sysforkey values(1010304,1009890,1,1153,0);
            insert into db_sysforkey values(1010304,1009889,1,1186,0);
            insert into db_sysforkey values(1010304,1009892,1,83,0);
            insert into db_syssequencia values(1000754, 'rhdependeplug_dp01_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000754 where codarq = 1010304 and codcam = 1009888;
        ";

        $this->execute($sql);
    }

    private function downDicionarioDados()
    {
        $sql = "
            delete from db_syssequencia where codsequencia = 1000754;
            delete from db_sysforkey where codarq = 1010304;
            delete from db_sysprikey where codarq = 1010304;
            delete from db_sysarqcamp where codarq = 1010304;
            delete from db_syscampo where codcam in (1009888, 1009889, 1009890, 1009891, 1009892, 1009893, 1009894);
            delete from db_sysarqmod where codarq = 1010304;
            delete from db_sysarquivo where codarq = 1010304;
        ";

        $this->execute($sql);
    }
}

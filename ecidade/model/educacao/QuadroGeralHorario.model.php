<?php

/**
 * Classe repository para Quadro Geral de Horários
 * @author Flávio Henrique
 * @package 1.00
 */
class QuadroGeralHorarioRepository {

    /**
     * Collection de Escolas do Quadro Geral de Horário
     * @var array
     */
    public $arrEscolas = array();

    /**
     * Collection do Quadro Geral de Horário
     * @var array
     */
    public $arrQuadroGeral = array();

    /**
     * Instancia da classe
     * @var QuadroGeralHorarioRepository
     */
    private static $oInstance;

    private function __construct() {
    }

    private function __clone()
    {
    }

    /**
     * Retorna a instancia do Repositorio
     * @return QuadroGeralHorarioRepository
     */
    protected static function getInstance()
    {
      if (self::$oInstance == null) {
        self::$oInstance = new QuadroGeralHorarioRepository();
      }
        return self::$oInstance;
    }


    public static function getDadosQuadroGeralHorarios($iAnoUsu, $iEscola, $iDisciplina, $iEtapa, $iDia, $iTurno, $iVinculo, $iPeriodo, $iFuncionario) {
        $aFiltros = [];
        $aFiltrosUnion = [];
        $aTurmas = [];
        /* Flag para UNION com a tabela regenciahorariodiscsemreg (disciplina sem regente) */
        $lFazUnion = true;
        $codTurno = "";

        if (isset($iEscola) && !empty($iEscola)) {
            $aFiltros[] = " ed18_i_codigo = ($iEscola) ";
            $aFiltrosUnion[] = " ed18_i_codigo = ($iEscola) ";
        }

        if (isset($iDisciplina) && !empty($iDisciplina)) {
            $aFiltros[] = " ed232_i_codigo = ($iDisciplina) ";
            $aFiltrosUnion[] = " ed232_i_codigo = ($iDisciplina) ";
        }

        if (isset($iEtapa) && !empty($iEtapa) && $iEtapa != "null") {
            $iEtapa = str_replace("-", ",", $iEtapa);

            $aFiltros[] = " ed11_i_codigo IN ($iEtapa) ";
            $aFiltrosUnion[] = " ed11_i_codigo IN ($iEtapa) ";
        }

        if (isset($iDia) && !empty($iDia)) {
            $aFiltros[] = " ed32_i_codigo = ($iDia) ";
            $aFiltrosUnion[] = " ed32_i_codigo = ($iDia) ";
        }

        if (isset($iTurno) && !empty($iTurno) && $iTurno != "null") {
            $iTurno = str_replace("-", ",", $iTurno);
            $aWhereTurno = " where referencia_turno IN ($iTurno) ";
        }

        if (isset($iVinculo) && !empty($iVinculo)) {
            $aFiltros[] = " ed128_codigo = ($iVinculo) ";
        }

        if (isset($iPeriodo) && !empty($iPeriodo)) {
            $aFiltros[] = " ed08_i_codigo = ($iPeriodo) ";
            $aFiltrosUnion[] = " ed08_i_codigo = ($iPeriodo) ";
        }

        if (isset($iFuncionario)
            && !empty($iFuncionario)
            && $iFuncionario != 1) {
            $aFiltros[] = " rechumano.ed20_i_codigo = ($iFuncionario) ";
        }

        if ($iFuncionario == 1) {
            $aFiltros[] = " rechumano.ed20_i_codigo is null ";
        }

        $aFiltros[] = " ed58_ativo = true ";
        $aFiltros[] = " ed52_i_ano = {$iAnoUsu} ";

        $aFiltrosUnion[] = " ed52_i_ano = {$iAnoUsu} ";
        $aFiltrosUnion[] = " ed175_ativo = true ";

        $sWhere = implode(' and ', $aFiltros);
        $sWhereUnion = implode(' and ', $aFiltrosUnion);

        $sCampos = "
            DISTINCT ed57_i_codigo AS codigo_turma,
            ed57_c_descr AS descricao_turma,
            ed32_i_codigo AS codigo_diasemana,
            ed32_c_descr AS descr_semana,
            ed08_i_codigo AS codigo_periodo,
            ed08_c_descr AS descricao_periodo,
            ed15_i_codigo AS codigo_turno,
            ed15_c_nome AS descricao_turno,
            ed11_c_descr AS descricao_serie,
            ed18_i_codigo AS codigo_escola,
            ed18_c_nome AS descricao_escola,
            rechumano.ed20_i_codigo AS codigo_regente,
            ed128_codigo AS codigo_tipohora,
            ed128_descricao AS descricao_tipohora,
            ed128_abreviatura AS abreviatura_tipohora,
            CASE
                WHEN rechumano.ed20_i_tiposervidor = 1 THEN cgmrh.z01_nome
                ELSE cgmcgm.z01_nome
            END AS z01_nome,
            CASE
                WHEN rechumano.ed20_i_tiposervidor = 1 THEN rechumanopessoal.ed284_i_rhpessoal
                ELSE rechumanocgm.ed285_i_cgm
            END AS matricula,
            ed232_i_codigo AS codigo_disciplina,
            ed232_c_descr AS disciplina,
            ed232_corhtml AS corhtml,
            CASE
                WHEN rechumano.ed20_i_codigo = docenteausencia.ed321_rechumano
                AND CURRENT_DATE BETWEEN ed321_inicio
                AND COALESCE(ed321_final, CURRENT_DATE) THEN TRUE
                ELSE FALSE
            END AS ausente_hoje,
            ed321_inicio AS ausencia_inicio,
            ed321_final AS ausencia_final,
            CASE
                WHEN rechumano_substituto.ed20_i_tiposervidor = 1 THEN cgmrh_substituto.z01_nome
                ELSE cgmcgm_substituto.z01_nome
            END AS substituto,
            docentesubstituto.ed322_periodoinicial AS substituto_inicio,
            docentesubstituto.ed322_periodofinal AS substituto_final,
            (
                SELECT
                    CASE
                        WHEN array_length(contagem, 1) > 1 THEN 4
                        ELSE contagem [1]
                    END AS turnoreferente
                FROM
                    (
                        SELECT
                            ed57_i_codigo,
                            array_agg(ed336_turnoreferente) AS contagem
                        FROM
                            turmaturnoreferente
                        WHERE
                            turmaturnoreferente.ed336_turma = turma.ed57_i_codigo
                        GROUP BY
                            ed57_i_codigo,
                            ed57_c_descr
                    ) AS x
            ) AS referencia_turno
        ";

        $sCamposUnion = "
            DISTINCT ed57_i_codigo AS codigo_turma,
            ed57_c_descr AS descricao_turma,
            ed32_i_codigo AS codigo_diasemana,
            ed32_c_descr AS descr_semana,
            ed08_i_codigo AS codigo_periodo,
            ed08_c_descr AS descricao_periodo,
            ed15_i_codigo AS codigo_turno,
            ed15_c_nome AS descricao_turno,
            ed11_c_descr AS descricao_serie,
            ed18_i_codigo AS codigo_escola,
            ed18_c_nome AS descricao_escola,
            NULL :: BIGINT AS codigo_regente,
            NULL :: INTEGER AS codigo_tipohora,
            NULL AS descricao_tipohora,
            NULL AS abreviatura_tipohora,
            NULL AS z01_nome,
            NULL :: BIGINT AS matricula,
            ed232_i_codigo AS codigo_disciplina,
            ed232_c_descr AS disciplina,
            ed232_corhtml AS corhtml,
            NULL :: BOOLEAN AS ausente,
            NULL :: DATE AS ausencia_inicio,
            NULL :: DATE AS ausencia_final,
            NULL AS substituto,
            NULL :: DATE AS substituto_inicio,
            NULL :: DATE AS substituto_final,
            (
                SELECT
                    CASE
                        WHEN array_length(contagem, 1) > 1 THEN 4
                        ELSE contagem [1]
                    END AS turnoreferente
                FROM
                    (
                        SELECT
                            ed57_i_codigo,
                            array_agg(ed336_turnoreferente) AS contagem
                        FROM
                            turmaturnoreferente
                        WHERE
                            turmaturnoreferente.ed336_turma = turma.ed57_i_codigo
                        GROUP BY
                            ed57_i_codigo,
                            ed57_c_descr
                    ) AS x
            ) AS referencia_turno
        ";

        $oDaoRegenciaHorario = new cl_regenciahorario();
        $sSqlRegenciaHorario = $oDaoRegenciaHorario->sql_query_quadrogeraldehorarios(
            '',
            $sCampos,
            $sCamposUnion,
            'codigo_escola, descricao_turma, codigo_diasemana, codigo_periodo',
            $sWhere,
            $aWhereTurno,
            $sWhereUnion,
            $aWhereTurno,
            $lFazUnion
        );

        $rsSqlRegenciaHorario = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorario);
        $numRows = $oDaoRegenciaHorario->numrows;

        for ($i = 0; $i < $numRows; $i++) {
            $oRegHorario = db_utils::fieldsMemory($rsSqlRegenciaHorario, $i);

            $sSerie = $oRegHorario->descricao_serie;
            $dAusenciaInicio = empty($oRegHorario->ausencia_inicio) ? '' : DBDate::converter($oRegHorario->ausencia_inicio);
            $dAusenciaFinal = empty($oRegHorario->ausencia_final) ? '' : DBDate::converter($oRegHorario->ausencia_final);
            $dSubstitutoInicio = empty($oRegHorario->substituto_inicio) ? '' : DBDate::converter($oRegHorario->substituto_inicio);
            $dSubstitutoFinal = empty($oRegHorario->substituto_final) ? '' : DBDate::converter($oRegHorario->substituto_final);
            
            $dtEscola = new stdClass();
            $dtEscola->codEscola = $oRegHorario->codigo_escola;
            $dtEscola->nomEscola = $oRegHorario->descricao_escola;
            $dtEscola->codTurma = $oRegHorario->codigo_turma;
            $dtEscola->dscTurma = $oRegHorario->descricao_turma;
            $dtEscola->codTurno = $oRegHorario->codigo_turno;
            $dtEscola->dscTurno = $oRegHorario->descricao_turno;
            
            $dtEscola->codigo_diasemana = $oRegHorario->codigo_diasemana;
            $dtEscola->descr_semana = $oRegHorario->descr_semana;
            $dtEscola->codigo_periodo = $oRegHorario->codigo_periodo;
            $dtEscola->descricao_periodo = $oRegHorario->descricao_periodo;
            $dtEscola->codigo_disciplina = $oRegHorario->codigo_disciplina;
            $dtEscola->disciplina = $oRegHorario->disciplina;
            $dtEscola->codigo_regente = $oRegHorario->codigo_regente;
            $dtEscola->z01_nome = $oRegHorario->z01_nome;
            $dtEscola->matricula = $oRegHorario->matricula;
            $dtEscola->codigo_tipohora = $oRegHorario->codigo_tipohora;
            $dtEscola->abreviatura_tipohora = $oRegHorario->abreviatura_tipohora;
            $dtEscola->ausente_hoje = $oRegHorario->ausente_hoje;
            $dtEscola->substituto = $dAusenciaInicio;
            $dtEscola->substituto = $dAusenciaFinal;
            $dtEscola->substituto = $oRegHorario->substituto;
            $dtEscola->subtituto_inicio = $dSubstitutoInicio;
            $dtEscola->subtituto_final = $dSubstitutoFinal;
            $dtEscola->corhtml = $oRegHorario->corhtml;

            $arrQuadroGeral[$i] = $dtEscola;
        }

        return $arrQuadroGeral;
    }

    public static function getParamQuadroHorarios()
    {
        require_once(modification("classes/db_sec_parametros_classe.php"));

        $clsec_parametros = new cl_sec_parametros;

        $rsParamQuadro = $clsec_parametros->sql_record(
            $clsec_parametros->sql_query(
                "",
                "ed290_cordisciplinaquadro",
                "",
                ""
            )
        );

        $pCorQuadro = pg_result($rsParamQuadro, 0);

        return $pCorQuadro;
    }
}

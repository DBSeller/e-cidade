<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 11/12/18
 * Time: 15:18
 */

namespace ECidade\Financeiro\Orcamento\Repository;

use db_utils;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\SaldoRecurso;
use ECidade\Financeiro\Orcamento\Recurso\Recurso;
use Exception;
use Instituicao;
use setasign\Fpdi\PdfParser\Filter\Lzw;
use stdClass;

/**
 * Class RecursoRepository
 *
 * @package ECidade\Financeiro\Orcamento\Repository
 */
class RecursoRepository
{

    private $recursos = [];

    protected static $instance;

    /**
     * Retorna a instancia da classe
     * @return self
     */
    protected static function getInstance()
    {

        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param       $ano
     * @param       $mes
     * @param       $estrutural
     * @param array $instituicoes
     * @return stdClass[]
     * @throws Exception
     */
    public static function getValoresRecursosPorCompetencia(
        $ano,
        $mes,
        $estrutural,
        array $instituicoes,
        $processarSaldoAnterior = true
    ) {

        return self::getValorPorRecursoNaCompetencia($ano, $mes, $estrutural, $instituicoes, $processarSaldoAnterior);

        $tribunais = array_map(function (Instituicao $instituicao) {

            return $instituicao->getCodigoTribunal();
        }, $instituicoes);

        $tribunais = implode('|', $tribunais);

        $sqlConplanoatributosaldo = "
            SELECT
                recursos.recurso     AS codigo,
                (select o15_descr from orctiporec where o15_recurso = recurso limit 1) as descricao,
                sum(recursos.saldo)  AS total
            FROM (
                     SELECT
                         CASE
                         WHEN c125_natureza = 'C'
                             THEN c125_valor
                         WHEN c125_natureza = 'D'
                             THEN -c125_valor
                         END                AS saldo,
                         c125_natureza      AS natureza,
                         (substring(c125_hashcontaatributos
                                    FROM (position('#FR' IN c125_hashcontaatributos) - 4)
                                    FOR 4)
                         ) AS recurso
                     FROM conplanoatributosaldo
                     WHERE c125_hashcontaatributos ILIKE '%{$estrutural}%'
                           AND c125_hashcontaatributos SIMILAR TO '%({$tribunais})#PO%'
                           AND c125_hashcontaatributos ILIKE '%FR%'
                           AND c125_anousu = {$ano}
                           AND c125_mesusu = {$mes}
                           AND c125_valor <> 0
                           and c125_tiposaldo = 2
                 ) recursos
            GROUP BY recursos.recurso, orctiporec.o15_descr
            ORDER BY recursos.recurso
       ";
        if (!$resultado = db_query($sqlConplanoatributosaldo)) {
            throw new Exception('Não foi possível buscar os valores dos recursos.');
        }

        return db_utils::getCollectionByRecord($resultado);
    }


    /**
     * @param       $ano
     * @param       $mes
     * @param       $estrutural
     * @param array $instituicoes
     * @return stdClass[]
     * @throws Exception
     */
    protected static function getValorPorRecursoNaCompetencia(
        $ano,
        $mes,
        $estrutural,
        array $instituicoes,
        $processarSaldoAnterior = true
    ) {


        $codigoInstituicoes = array_map(function (Instituicao $instituicao) {

            return $instituicao->getCodigo();
        }, $instituicoes);

        $codigoInstituicoes = implode(', ', $codigoInstituicoes);

        $dataInicioAno = new \DateTime("{$ano}-01-01");
        $dataInicioCompetencia = $dataInicioAno;
        $dataFinal = new \DateTime("{$ano}-{$mes}-" . cal_days_in_month(CAL_GREGORIAN, $mes, $ano));
        $saldo = new SaldoRecurso();
        $recursos = $saldo->getRecursos(
            $instituicoes,
            $dataInicioCompetencia,
            $dataFinal,
            null,
            $estrutural,
            $processarSaldoAnterior
        );
        $rescursosParaRetorno = array();
        foreach ($recursos as $recurso) {
            $total = $recurso->natureza_saldo_final == 'D' ? $recurso->saldo_final * -1 : $recurso->saldo_final;
            $recursoStd = new \stdClass();
            $recursoStd->codigo = $recurso->recurso;
            $recursoStd->descricao = $recurso->descricao;
            $recursoStd->total = $total;
            $rescursosParaRetorno[] = $recursoStd;
        }
        return $rescursosParaRetorno;
    }

    /**
     * @param $recurso
     * @return Recurso
     */
    public static function getByCodigo($recurso)
    {
        if (!array_key_exists($recurso, self::getInstance()->recursos)) {
            self::getInstance()->recursos[$recurso] = new Recurso($recurso);
        }
        return self::getInstance()->recursos[$recurso];
    }

    /**
     * Busca os complementos existentes para o código de recurso (o15_recurso)
     * @param string $codigoRecurso
     * @return stdClass[]
     */
    public static function getComplementos($codigoRecurso)
    {
        $busca = "
            select o15_complemento as codigo, o200_sequencial||' - '||o200_descricao as descricao,
                   o15_codigo as id_recurso
              from orctiporec
              join complementofonterecurso on o200_sequencial = o15_complemento
             where o15_recurso = '{$codigoRecurso}'
             order by 1
        ";

        $res = db_query($busca);
        return db_utils::getCollectionByRecord($res);
    }

    /**
     * @param string $fonteRecurso
     * @param string $outrosFiltros
     * @return array
     * @throws Exception
     */
    public static function getRecursosValidosPorFonteRecurso($fonteRecurso, $outrosFiltros = null)
    {
        $where = " o15_recurso = '{$fonteRecurso}'";
        if (!empty($outrosFiltros)) {
            $where .= " and {$outrosFiltros} ";
        }

        $dao = new \cl_orctiporec();
        $sql = $dao->sql_query_file(null, '*', 'o15_codigo', $where);
        $rs = db_query($sql);

        if (!$rs && pg_num_rows($rs) == 0) {
            throw new Exception("Não foi encontrado um recurso para os filtros informados.");
        }

        $recursos = [];
        while ($state = pg_fetch_object($rs)) {
            $recursos[] = $state;
        }

        return $recursos;
    }

    /**
     * @param string $fonteRecurso
     * @param string $outrosFiltros
     * @return array
     * @throws Exception
     */
    public static function getIdsRecursoPorFonteRecurso($fonteRecurso, $outrosFiltros = null)
    {
        $recursos = self::getRecursosValidosPorFonteRecurso($fonteRecurso, $outrosFiltros);

        return array_map(function ($recurso) {
            return $recurso->o15_codigo;
        }, $recursos);
    }
}

<?php

namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;

use ECidade\Educacao\Escola\Censo\Censo;
use Escola;
use Exception;

/**
 * Class BuscaDadosAlunos2016
 * @package ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados
 */
class BuscaDadosAlunos2016
{
    /**
     * @var array
     */
    protected $aDados = array();

    /**
     * Busca os dados dos alunos de acordo com o ano do CENSO, Escola e alunos anteriores ou posteriores a data do CENSO
     * @param Censo $oCenso
     * @param Escola $oEscola
     * @param $aCondicoes
     * @param bool $mergeDados
     * @return void
     * @throws Exception
     */
    public function buscarAlunos(Censo $oCenso, Escola $oEscola, $aCondicoes, $mergeDados = false)
    {
        $aSituacoesMatriculaNaoPermitidas = array('MATRICULA INDEVIDA', 'MATRICULA INDEFERIDA');

        $aWhere = array();
        $aWhere[] = " ed60_c_situacao not in ('" . implode("', '", $aSituacoesMatriculaNaoPermitidas) . "') ";
        $aWhere[] = " escola.ed18_i_codigo  = {$oEscola->getCodigo()}";
        $aWhere[] = " calendario.ed52_i_ano = {$oCenso->getAno()} ";
        $aWhere[] = " ed221_c_origem = 'S' ";

        $aWhere = array_merge($aWhere, $aCondicoes);

        $oDaoMatricula = new \cl_matricula();
        $sSqlMatricula = $oDaoMatricula->sql_query_excportacao_alunos_censo($aWhere, array('codigo_turma_escola'));
        $rsMatricula = db_query($sSqlMatricula);

        if (!$rsMatricula) {
            throw new \DBException("Erro ao buscar dados dos alunos.");
        }

        if ($mergeDados) {
            $this->aDados = array_merge($this->aDados, \db_utils::getCollectionByRecord($rsMatricula));
        } else {
            $this->aDados = \db_utils::getCollectionByRecord($rsMatricula);
        }
    }

    /**
     * Objetivo do metodo é retornar os alunos que efetuaram troca de turma no período do censo
     * @param Censo $censo
     * @param Escola $escola
     * @return array
     * @throws Exception
     */
    public function identificaAlunosComTrocaDeTurma(Censo $censo, Escola $escola)
    {
        $where = array();
        $where[] = "ed221_c_origem = 'S'";
        $where[] = "calendario.ed52_i_ano = {$censo->getAno()}";
        $where[] = "turma.ed57_i_escola  = {$escola->getCodigo()}";
        $where[] = "matricula.ed60_d_datamatricula <= '" . $censo->getDataCenso()->getDate() . "'";
        $where[] = "matricula.ed60_d_datamodif <= '" . $censo->getDataCenso()->getDate() . "'";
        $where[] = "matricula.ed60_c_situacao = 'TROCA DE TURMA'";

        $dao = new \cl_matricula();
        $sql = $dao->sql_query_matriculaanual(null, "distinct ed60_i_aluno", null, implode(' and ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar alunos que tiveram movimentação de: TROCA DE TURMA");
        }

        if (pg_num_rows($rs) == 0) {
            return array();
        }

        return pg_fetch_all_columns($rs, 0);
    }

    /**
     * Objetivo do metodo é retornar a ultima posição (matrícula / turma) do aluno antes da data do censo
     * @param Censo $censo
     * @param Escola $escola
     * @param array $condicoes
     * @return array
     * @throws Exception
     */
    private function buscarUltimaPosicaoMatriculaAlunosComTrocaDeTurma(Censo $censo, Escola $escola, $condicoes = [])
    {
        $dataCenso = $censo->getDataCenso()->getDate();
        $where = $condicoes;
        $where[] = "ed221_c_origem = 'S'";
        $where[] = "calendario.ed52_i_ano = {$censo->getAno()}";
        $where[] = "turma.ed57_i_escola  = {$escola->getCodigo()}";
        $where[] = "matricula.ed60_d_datamatricula <= '{$dataCenso}'";
        $where[] = "(matricula.ed60_d_datasaida is null or matricula.ed60_d_datasaida >= '{$dataCenso}')";

        $dao = new \cl_matricula();
        $sql = $dao->sql_query_matriculaanual(
            null,
            "max(ed60_i_codigo) as matricula, ed60_i_aluno",
            null,
            implode(' and ', $where)
        );
        $sql .= " group by 2";
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar alunos que tiveram movimentação de: TROCA DE TURMA");
        }

        if (pg_num_rows($rs) == 0) {
            return array();
        }

        return pg_fetch_all_columns($rs, 0);
    }

    /**
     * @param $censo
     * @param $escola
     * @param array $condicoes
     * @throws Exception
     */
    public function buscarAlunosTrocaTurma(Censo $censo, Escola $escola, $condicoes = array())
    {
        if (empty($condicoes)) {
            return;
        }

        $matriculas = $this->buscarUltimaPosicaoMatriculaAlunosComTrocaDeTurma($censo, $escola, $condicoes);
        if (empty($matriculas)) {
            return;
        }

        $date = $censo->getDataCenso()->getDate();
        $where = array(
            "matricula.ed60_i_codigo in (" . implode(', ', $matriculas) . ")",
            "matricula.ed60_d_datamatricula <= '{$date}'",
            "(matricula.ed60_d_datasaida > '{$date}' or ed60_c_situacao = 'MATRICULADO')"
        );

        $this->buscarAlunos($censo, $escola, $where, true);
    }
}

<?php

namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;

use ECidade\Educacao\Escola\Censo\Censo;
use Escola;
use Exception;
use stdClass;

/**
 * Class BuscaDadosAlunosAntes2016
 * @package ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados
 */
class BuscaDadosAlunosAntes2016 extends BuscaDadosAlunos2016 implements BuscarDados
{
    /**
     * @var array
     */
    private $aCodigoAlunosAntes = array();

    /**
     * BuscaDadosAlunosAntes2016 constructor.
     * @param Censo $oCenso
     * @param Escola $oEscola
     * @throws Exception
     */
    public function __construct(Censo $oCenso, Escola $oEscola)
    {
        $alunosComTrocaTurma = $this->identificaAlunosComTrocaDeTurma($oCenso, $oEscola);
        $aCondicoes = array();
        $date = $oCenso->getDataCenso()->getDate();
        $aCondicoes[] = " matricula.ed60_d_datamatricula <= '{$date}'";
        $aCondicoes[] = " ( matricula.ed60_d_datasaida > '{$date}' or ed60_c_situacao = 'MATRICULADO')";

        $filtroMatriculasComTrocaTurma = array();
        if (!empty($alunosComTrocaTurma)) {
            $aCondicoes[] = "ed60_i_aluno not in(" . implode(', ', $alunosComTrocaTurma) . ")";
            $filtroMatriculasComTrocaTurma[] = "ed60_i_aluno in(" . implode(', ', $alunosComTrocaTurma) . ")";
        }

        $this->buscarAlunos($oCenso, $oEscola, $aCondicoes);
        $this->buscarAlunosTrocaTurma($oCenso, $oEscola, $filtroMatriculasComTrocaTurma);
        $this->processar($oCenso);
    }

    /**
     * Altera os dados do aluno quando o mesmo possui uma matrícula posterior no mesmo ano na mesma escola.
     * Casos como alunos que foram transferidos e retornaram para a escola no mesmo ano ou que foram avançados.
     * @param Censo $oCenso
     */
    private function processar(Censo $oCenso)
    {
        foreach ($this->aDados as $oDadosAluno) {
            if ($oDadosAluno->situacao_matricula === 'TROCA DE MODALIDADE') {
                continue;
            }

            $this->aCodigoAlunosAntes[] = $oDadosAluno->codigo_aluno_escola;
        }
    }


    /**
     * Retorna os dados dos alunos com matrículas antes da data do CENSO.
     * @return stdClass[]
     */
    public function getDados()
    {
        return $this->aDados;
    }

    /**
     * Retorna os códigos dos alunos
     * @return array
     */
    public function getCodigoAlunos()
    {
        return $this->aCodigoAlunosAntes;
    }
}

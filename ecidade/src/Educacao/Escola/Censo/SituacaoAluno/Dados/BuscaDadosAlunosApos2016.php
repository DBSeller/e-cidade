<?php

namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados;

use ECidade\Educacao\Escola\Censo\Censo;
use Escola;
use Exception;
use stdClass;

/**
 * Class BuscaDadosAlunosApos2016
 * Busca os dados dos alunos que entraram após a data do censo
 * @package ECidade\Educacao\Escola\Censo\SituacaoAluno\Dados
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.2 $
 */
class BuscaDadosAlunosApos2016 extends BuscaDadosAlunos2016
{
    /**
     * BuscaDadosAlunosApos2016 constructor.
     * @param Censo $censo
     * @param Escola $escola
     * @param $aAlunosAntesCenso
     * @throws Exception
     */
    public function __construct(Censo $censo, Escola $escola, $aAlunosAntesCenso)
    {

        $aCondicoes = array();
        $aCondicoes[] = " matricula.ed60_d_datamatricula > '" . $censo->getDataCenso()->getDate() . "'";
        $aCondicoes[] = " matricula.ed60_i_aluno not in (" . implode(', ', $aAlunosAntesCenso) . ") ";

        $this->buscarAlunos($censo, $escola, $aCondicoes);
    }

    /**
     * Retorna os dados dos alunos com matrículas após data do CENSO.
     * @return stdClass[]
     */
    public function getDados()
    {
        return $this->aDados;
    }
}

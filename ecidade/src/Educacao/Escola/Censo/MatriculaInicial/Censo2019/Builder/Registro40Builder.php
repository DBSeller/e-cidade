<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 03/05/2019
 * Time: 09:22
 */

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Builder;

use ECidade\Educacao\Escola\Censo\Helpers\Pessoa;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\DiretorCensoVO;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro40;
use ECidade\Educacao\Escola\Model\ProfissionalEscola;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service\Registro00Service;

class Registro40Builder
{

    protected static $deParaVinculo = array(
      1 => 1,
      2 => 4,
      3 => 3,
    );

    /**
     * @var ProfissionalEscola
     */
    protected $gestor;

    /**
     * @var DiretorCensoVO
     */
    protected $diretor;

    /**
     * @var Registro40
     */
    private $registro;

    /**
     * @param ProfissionalEscola $gestor
     */
    public function addProfissional(ProfissionalEscola $gestor)
    {
        $this->gestor = $gestor;
    }

    /**
     * @param DiretorCensoVO $dadosDiretor
     */
    public function addDadosDiretor($dadosDiretor)
    {
        $this->diretor = $dadosDiretor;
    }

    public function build()
    {
        $this->create();
        $this->buidGestor();

        return $this->registro;
    }

    private function create()
    {
        $this->registro = new Registro40();
    }

    private function buidGestor()
    {
        $this->registro->setCodigoInep($this->gestor->getCodigoInep());
        $this->registro->setCodigoInepEscola($this->gestor->getEscola()->getCodigoInep());
        $this->registro->setCodigoPessoa(Pessoa::buildCodigoProfissional($this->gestor->getCgm()->getCpf()));

        $this->registro->setCargo(2);
        if (!is_null($this->diretor)) {
            $this->registro->setCargo(1);
            $this->registro->setCriterioAcesso($this->diretor->getCriterioAcessoFuncao());
            $this->registro->setEspecificacaoCriterioAcesso(
                mb_strtoupper($this->diretor->getEspecificacaoCriterioOutros())
            );
            $reg00 = Registro00Service::getRegistroProcessado();
            if (in_array($reg00->getDependenciaAdministrativa(), array(1, 2, 3))) {
                $vinculo = $this->gestor->getVinculoRegimeContratacao();
                $this->registro->setRegimeContratacao(self::$deParaVinculo[$vinculo->getCodigoRegime()]);
                if ($vinculo->getNaturezaRegime() == 3) {
                    $this->registro->setRegimeContratacao(2);
                }
            }
        }
    }
}

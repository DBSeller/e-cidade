<?php

namespace ECidade\Educacao\Escola\Model;

use DisciplinaHistoricoRede;
use ECidade\Educacao\Escola\Registry\AreaConhecimentoRegistry;
use Exception;
use HistoricoEtapaRede;

/**
 * Class AreaHistoricoEtapaRede
 * @package ECidade\Educacao\Escola\Model
 */
class AreaHistoricoRede
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var HistoricoEtapaRede
     */
    private $historicoEtapaRede;
    /**
     * @var AreaConhecimento
     */
    private $areaConhecimento;
    /**
     * @var string
     */
    private $resultadoObtido;
    /**
     * @var string
     */
    private $resultadoFinal;
    /**
     * @var DisciplinaHistoricoRede[]
     */
    private $disciplinas = [];

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return HistoricoEtapaRede
     */
    public function getHistoricoEtapaRede()
    {
        return $this->historicoEtapaRede;
    }

    /**
     * @param HistoricoEtapaRede $historicoEtapaRede
     */
    public function setHistoricoEtapaRede(HistoricoEtapaRede $historicoEtapaRede)
    {
        $this->historicoEtapaRede = $historicoEtapaRede;
    }

    /**
     * @return AreaConhecimento
     */
    public function getAreaConhecimento()
    {
        return $this->areaConhecimento;
    }

    /**
     * @param AreaConhecimento $areaConhecimento
     */
    public function setAreaConhecimento(AreaConhecimento $areaConhecimento)
    {
        $this->areaConhecimento = $areaConhecimento;
    }

    /**
     * @return string
     */
    public function getResultadoObtido()
    {
        return $this->resultadoObtido;
    }

    /**
     * @param string $resultadoObtido
     */
    public function setResultadoObtido($resultadoObtido)
    {
        $this->resultadoObtido = $resultadoObtido;
    }

    /**
     * @return string
     */
    public function getResultadoFinal()
    {
        return $this->resultadoFinal;
    }

    /**
     * @param string $resultadoFinal
     */
    public function setResultadoFinal($resultadoFinal)
    {
        $this->resultadoFinal = $resultadoFinal;
    }

    /**
     * @return DisciplinaHistoricoRede[]
     */
    public function getDisciplinas()
    {
        return $this->disciplinas;
    }

    /**
     * @param DisciplinaHistoricoRede[] $disciplinas
     */
    public function setDisciplinas($disciplinas)
    {
        $this->disciplinas = $disciplinas;
    }

    /**
     * @param DisciplinaHistoricoRede $disciplinaHistoricoRede
     */
    public function addDisciplinaHistoricoRede(DisciplinaHistoricoRede $disciplinaHistoricoRede)
    {
        $this->disciplinas[] = $disciplinaHistoricoRede;
    }


    /**
     * @param array $state
     * @return AreaHistoricoRede
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed170_codigo', $state)) {
            $self->setCodigo($state['ed170_codigo']);
        }
        if (array_key_exists('ed170_historicomps', $state)) {
            $self->setHistoricoEtapaRede(new HistoricoEtapaRede($state['ed170_historicomps']));
        }
        if (array_key_exists('ed170_areaconhecimento', $state)) {
            $self->setAreaConhecimento(AreaConhecimentoRegistry::get($state['ed170_areaconhecimento']));
        }
        if (array_key_exists('ed170_resultadoobtido', $state)) {
            $self->setResultadoObtido($state['ed170_resultadoobtido']);
        }
        if (array_key_exists('ed170_resultadofinal', $state)) {
            $self->setResultadoFinal($state['ed170_resultadofinal']);
        }

        return $self;
    }
}

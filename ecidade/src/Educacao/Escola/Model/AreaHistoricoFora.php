<?php

namespace ECidade\Educacao\Escola\Model;

use DisciplinaHistoricoForaRede;
use ECidade\Educacao\Escola\Registry\AreaConhecimentoRegistry;
use Exception;
use HistoricoEtapaForaRede;

/**
 * Class AreaHistoricoFora
 * @package ECidade\Educacao\Escola\Model
 */
class AreaHistoricoFora
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var HistoricoEtapaForaRede
     */
    private $historicoEtapaForaRede;
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
     * @var DisciplinaHistoricoForaRede[]
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
     * @return HistoricoEtapaForaRede
     */
    public function getHistoricoEtapaForaRede()
    {
        return $this->historicoEtapaForaRede;
    }

    /**
     * @param HistoricoEtapaForaRede $historicoEtapaForaRede
     */
    public function setHistoricoEtapaForaRede(HistoricoEtapaForaRede $historicoEtapaForaRede)
    {
        $this->historicoEtapaForaRede = $historicoEtapaForaRede;
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
     * @return DisciplinaHistoricoForaRede[]
     */
    public function getDisciplinas()
    {
        return $this->disciplinas;
    }

    /**
     * @param DisciplinaHistoricoForaRede[] $disciplinas
     */
    public function setDisciplinas($disciplinas)
    {
        $this->disciplinas = $disciplinas;
    }

    /**
     * @param DisciplinaHistoricoForaRede $disciplinaHistoricoForaRede
     */
    public function addDisciplinaHistoricoForaRede(DisciplinaHistoricoForaRede $disciplinaHistoricoForaRede)
    {
        $this->disciplinas[] = $disciplinaHistoricoForaRede;
    }


    /**
     * @param array $state
     * @return AreaHistoricoFora
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed172_codigo', $state)) {
            $self->setCodigo($state['ed172_codigo']);
        }
        if (array_key_exists('ed172_historicompsfora', $state)) {
            $self->setHistoricoEtapaForaRede(new HistoricoEtapaForaRede($state['ed172_historicompsfora']));
        }
        if (array_key_exists('ed172_areaconhecimento', $state)) {
            $self->setAreaConhecimento(AreaConhecimentoRegistry::get($state['ed172_areaconhecimento']));
        }
        if (array_key_exists('ed172_resultadoobtido', $state)) {
            $self->setResultadoObtido($state['ed172_resultadoobtido']);
        }
        if (array_key_exists('ed172_resultadofinal', $state)) {
            $self->setResultadoFinal($state['ed172_resultadofinal']);
        }

        return $self;
    }
}

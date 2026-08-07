<?php

namespace ECidade\Educacao\Escola\Model;

use Etapa;
use Exception;
use Matricula;

/**
 * Class MatriculaEtapa
 * @package ECidade\Educacao\Escola\Model
 */
class MatriculaEtapa
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var Matricula
     */
    private $matricula;
    /**
     * @var Etapa
     */
    private $etapa;
    /**
     * @var string
     */
    private $origem;

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
     * @return Matricula
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param Matricula $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * @return Etapa
     */
    public function getEtapa()
    {
        return $this->etapa;
    }

    /**
     * @param Etapa $etapa
     */
    public function setEtapa($etapa)
    {
        $this->etapa = $etapa;
    }

    /**
     * @return string
     */
    public function getOrigem()
    {
        return $this->origem;
    }

    /**
     * @param string $origem
     */
    public function setOrigem($origem)
    {
        $this->origem = $origem;
    }

    /**
     * @param $state
     * @throws Exception
     */
    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('ed221_i_codigo', $state)) {
            $self->setCodigo($state['ed221_i_codigo']);
        }
        if (array_key_exists('ed221_i_matricula', $state)) {
            $self->setMatricula($state['ed221_i_matricula']);
        }
        if (array_key_exists('ed221_i_serie', $state)) {
            $self->setEtapa(new Etapa($state['ed221_i_serie']));
        }
        if (array_key_exists('ed221_c_origem', $state)) {
            $self->setOrigem($state['ed221_c_origem']);
        }
    }
}

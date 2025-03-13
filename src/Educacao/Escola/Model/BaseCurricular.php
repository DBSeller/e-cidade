<?php

namespace ECidade\Educacao\Escola\Model;

use ECidade\Educacao\Escola\Registry\BaseCurricularRegistry;

/**
 * Class BaseCurricular
 * @package ECidade\Educacao\Escola\Model
 */
class BaseCurricular
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var string
     */
    private $turno;
    /**
     * @var string
     */
    private $calculoFrequencia;
    /**
     * @var string
     */
    private $controleFrequencia;
    /**
     * @var string
     */
    private $observacao;
    /**
     * @var boolean
     */
    private $concluiCurso;
    /**
     * @var boolean
     */
    private $ativo = false;



    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return BaseCurricular
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     * @return BaseCurricular
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return string
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * @param string $turno
     * @return BaseCurricular
     */
    public function setTurno($turno)
    {
        $this->turno = $turno;
        return $this;
    }

    /**
     * @return string
     */
    public function getCalculoFrequencia()
    {
        return $this->calculoFrequencia;
    }

    /**
     * @param string $calculoFrequencia
     * @return BaseCurricular
     */
    public function setCalculoFrequencia($calculoFrequencia)
    {
        $this->calculoFrequencia = $calculoFrequencia;
        return $this;
    }

    /**
     * @return string
     */
    public function getControleFrequencia()
    {
        return $this->controleFrequencia;
    }

    /**
     * @param string $controleFrequencia
     * @return BaseCurricular
     */
    public function setControleFrequencia($controleFrequencia)
    {
        $this->controleFrequencia = $controleFrequencia;
        return $this;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     * @return BaseCurricular
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getConcluiCurso()
    {
        return $this->concluiCurso;
    }

    /**
     * @param boolean $concluiCurso
     * @return BaseCurricular
     */
    public function setConcluiCurso($concluiCurso)
    {
        $this->concluiCurso = $concluiCurso;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param boolean $ativo
     * @return BaseCurricular
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * @param array $state
     * @return BaseCurricular
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed31_i_codigo', $state)) {
            $self->setCodigo($state['ed31_i_codigo']);
        }
        if (array_key_exists('ed31_c_descr', $state)) {
            $self->setDescricao($state['ed31_c_descr']);
        }
        if (array_key_exists('ed31_c_turno', $state)) {
            $self->setTurno($state['ed31_c_turno']);
        }
        if (array_key_exists('ed31_c_medfreq', $state)) {
            $self->setCalculoFrequencia($state['ed31_c_medfreq']);
        }
        if (array_key_exists('ed31_c_contrfreq', $state)) {
            $self->setControleFrequencia($state['ed31_c_contrfreq']);
        }
        if (array_key_exists('ed31_t_obs', $state)) {
            $self->setObservacao($state['ed31_t_obs']);
        }
        if (array_key_exists('ed31_c_conclusao', $state)) {
            $self->setConcluiCurso($state['ed31_c_conclusao'] === 'S');
        }
        if (array_key_exists('ed31_c_ativo', $state)) {
            $self->setAtivo($state['ed31_c_ativo'] === 'S');
        }

        BaseCurricularRegistry::set($self);

        return $self;
    }
}

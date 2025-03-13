<?php

namespace ECidade\Financeiro\Orcamento\Model;

/**
 * Class NaturezaDespesa
 * @package ECidade\Financeiro\Orcamento\Model
 */
class NaturezaDespesa
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var integer
     */
    private $ano;
    /**
     * @var string
     */
    private $elemento;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var string
     */
    private $finalidade;
    /**
     * @var boolean
     */
    private $orcado;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return NaturezaDespesa
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param int $ano
     * @return NaturezaDespesa
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
        return $this;
    }

    /**
     * @return string
     */
    public function getElemento()
    {
        return $this->elemento;
    }

    /**
     * @param string $elemento
     * @return NaturezaDespesa
     */
    public function setElemento($elemento)
    {
        $this->elemento = $elemento;
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
     * @return NaturezaDespesa
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return string
     */
    public function getFinalidade()
    {
        return $this->finalidade;
    }

    /**
     * @param string $finalidade
     * @return NaturezaDespesa
     */
    public function setFinalidade($finalidade)
    {
        $this->finalidade = $finalidade;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOrcado()
    {
        return $this->orcado;
    }

    /**
     * @param bool $orcado
     * @return NaturezaDespesa
     */
    public function setOrcado($orcado)
    {
        $this->orcado = $orcado;
        return $this;
    }

    /**
     * @param array $state
     * @return NaturezaDespesa
     */
    public static function fromState(array $state)
    {
        $self = new self;
        if (array_key_exists('o56_codele', $state)) {
            $self->setCodigo($state['o56_codele']);
        }
        if (array_key_exists('o56_anousu', $state)) {
            $self->setAno($state['o56_anousu']);
        }
        if (array_key_exists('o56_elemento', $state)) {
            $self->setElemento($state['o56_elemento']);
        }
        if (array_key_exists('o56_descr', $state)) {
            $self->setDescricao($state['o56_descr']);
        }
        if (array_key_exists('o56_finali', $state)) {
            $self->setFinalidade($state['o56_finali']);
        }
        if (array_key_exists('o56_orcado', $state)) {
            $self->setOrcado($state['o56_orcado'] === 't');
        }

        return $self;
    }
}

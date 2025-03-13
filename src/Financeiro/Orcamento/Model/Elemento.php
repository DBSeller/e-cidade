<?php


namespace ECidade\Financeiro\Orcamento\Model;

/**
 * Class Elemento
 * @package ECidade\Financeiro\Orcamento\Model
 */
class Elemento
{
    private $codigo;
    private $exercicio;
    private $elemento;
    private $descricao;
    private $finalidade;
    private $orcado = false;

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     * @return Elemento
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExercicio()
    {
        return $this->exercicio;
    }

    /**
     * @param mixed $exercicio
     * @return Elemento
     */
    public function setExercicio($exercicio)
    {
        $this->exercicio = $exercicio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getElemento()
    {
        return $this->elemento;
    }

    /**
     * @param mixed $elemento
     * @return Elemento
     */
    public function setElemento($elemento)
    {
        $this->elemento = $elemento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     * @return Elemento
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFinalidade()
    {
        return $this->finalidade;
    }

    /**
     * @param mixed $finalidade
     * @return Elemento
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
     * @return Elemento
     */
    public function setOrcado($orcado)
    {
        $this->orcado = $orcado;
        return $this;
    }

    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('o56_codele', $state)) {
            $self->setCodigo($state['o56_codele']);
        }
        if (array_key_exists('o56_anousu', $state)) {
            $self->setExercicio($state['o56_anousu']);
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

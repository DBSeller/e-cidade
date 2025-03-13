<?php


namespace ECidade\Financeiro\Orcamento\Model;

use ECidade\Financeiro\Orcamento\Registry\EspecificacaoRegistry;

/**
 * Class Especificacao
 * @package ECidade\Financeiro\Orcamento\Model
 */
class Especificacao
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $codigo;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var string
     */
    private $estado;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param string $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
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
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @param array $state
     * @return Especificacao
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('o205_sequencial', $state)) {
            $self->setId($state['o205_sequencial']);
        }
        if (array_key_exists('o205_codigo', $state)) {
            $self->setCodigo($state['o205_codigo']);
        }
        if (array_key_exists('o205_descricao', $state)) {
            $self->setDescricao($state['o205_descricao']);
        }
        if (array_key_exists('o205_estado', $state)) {
            $self->setEstado($state['o205_estado']);
        }

        EspecificacaoRegistry::set($self);

        return $self;
    }
}

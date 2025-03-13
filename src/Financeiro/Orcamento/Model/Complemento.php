<?php

namespace ECidade\Financeiro\Orcamento\Model;

use ECidade\Financeiro\Orcamento\Registry\ComplementoRegistry;

/**
 * Class Complemento
 * @package ECidade\Financeiro\Orcamento\Model
 */
class Complemento
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
     * @var boolean
     */
    private $msc;

    /**
     * @var boolean
     */
    private $tribunal;

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
    public function setId($codigo)
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
     * @return boolean
     */
    public function isMsc()
    {
        return $this->msc;
    }

    /**
     * @param boolean $msc
     */
    public function setMsc($msc)
    {
        $this->msc = $msc;
    }

    /**
     * @return boolean
     */
    public function isTribunal()
    {
        return $this->tribunal;
    }

    /**
     * @param boolean $tribunal
     */
    public function setTribunal($tribunal)
    {
        $this->tribunal = $tribunal;
    }

    /**
     * @param array $state
     * @return Complemento
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('o200_sequencial', $state)) {
            $self->setId($state['o200_sequencial']);
        }
        if (array_key_exists('o200_descricao', $state)) {
            $self->setDescricao($state['o200_descricao']);
        }
        if (array_key_exists('o200_msc', $state)) {
            $self->setMsc($state['o200_msc'] == 't');
        }
        if (array_key_exists('o200_tribunal', $state)) {
            $self->setTribunal($state['o200_tribunal'] == 't');
        }

        ComplementoRegistry::set($self);

        return $self;
    }
}

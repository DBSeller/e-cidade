<?php


namespace ECidade\Educacao\Escola\Model;

use ECidade\Educacao\Escola\Registry\AreaConhecimentoRegistry;

/**
 * Class AreaConhecimento
 * @package ECidade\Educacao\Escola\Model
 */
class AreaConhecimento
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
    private $ativo;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return AreaConhecimento
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
     * @return AreaConhecimento
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param bool $ativo
     * @return AreaConhecimento
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * @param array $state
     * @return AreaConhecimento
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed293_sequencial', $state)) {
            $self->setCodigo($state['ed293_sequencial']);
        }
        if (array_key_exists('ed293_descr', $state)) {
            $self->setDescricao($state['ed293_descr']);
        }
        if (array_key_exists('ed293_ativo', $state)) {
            $self->setAtivo($state['ed293_ativo'] === 't');
        }

        AreaConhecimentoRegistry::set($self);
        return $self;
    }
}

<?php

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Cadastro\Registry\BairroRegistry;

/**
 * Class Bairro
 * @package ECidade\Tributario\Cadastro\Model
 */
class Bairro
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var string
     */
    private $nome;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return Bairro
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return Bairro
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('j13_codi', $state)) {
            $self->setCodigo($state['j13_codi']);
        }
        if (array_key_exists('j13_descr', $state)) {
            $self->setNome($state['j13_descr']);
        }

        BairroRegistry::set($self);

        return $self;
    }
}

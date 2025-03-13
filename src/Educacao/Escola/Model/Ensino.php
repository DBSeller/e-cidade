<?php

namespace ECidade\Educacao\Escola\Model;

use ECidade\Educacao\Escola\Registry\EnsinoRegistry;
use ECidade\Enum\Educacao\Escola\TipoEnsinoEnum;
use Exception;

/**
 * Class Ensino
 * @package ECidade\Educacao\Escola\Model
 */
class Ensino
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var ModalidadeEnsino
     */
    private $modalidade;
    /**
     * @var string
     */
    private $nome;
    /**
     * @var string
     */
    private $abreviatura;
    /**
     * @var integer
     */
    private $ordem;
    /**
     * @var TipoEnsinoEnum
     */
    private $tipoEnsino;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return Ensino
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return ModalidadeEnsino
     */
    public function getModalidade()
    {
        return $this->modalidade;
    }

    /**
     * @param ModalidadeEnsino $modalidade
     * @return Ensino
     */
    public function setModalidade($modalidade)
    {
        $this->modalidade = $modalidade;
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
     * @return Ensino
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * @param string $abreviatura
     * @return Ensino
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * @param int $ordem
     * @return Ensino
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
        return $this;
    }

    /**
     * @return TipoEnsinoEnum
     */
    public function getTipoEnsino()
    {
        return $this->tipoEnsino;
    }

    /**
     * @param TipoEnsinoEnum $tipoEnsino
     * @return Ensino
     */
    public function setTipoEnsino($tipoEnsino)
    {
        $this->tipoEnsino = $tipoEnsino;
        return $this;
    }

    /**
     * @param array $state
     * @return Ensino
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed10_i_codigo', $state)) {
            $self->setCodigo($state['ed10_i_codigo']);
        }

        if (array_key_exists('ed36_i_codigo', $state) &&
            array_key_exists('ed36_c_descr', $state) &&
            array_key_exists('ed36_c_abrev', $state)) {
            $self->setModalidade(ModalidadeEnsino::fromState($state));
        }
        if (array_key_exists('ed10_c_descr', $state)) {
            $self->setNome($state['ed10_c_descr']);
        }
        if (array_key_exists('ed10_c_abrev', $state)) {
            $self->setAbreviatura($state['ed10_c_abrev']);
        }
        if (array_key_exists('ed10_ordem', $state)) {
            $self->setOrdem($state['ed10_ordem']);
        }
        if (array_key_exists('ed10_tipo', $state)) {
            $self->setTipoEnsino(new TipoEnsinoEnum((int) $state['ed10_tipo']));
        }

        EnsinoRegistry::set($self);

        return $self;
    }
}

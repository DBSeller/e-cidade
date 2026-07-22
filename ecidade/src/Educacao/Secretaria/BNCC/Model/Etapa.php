<?php

namespace ECidade\Educacao\Secretaria\BNCC\Model;

use ECidade\Educacao\Secretaria\BNCC\Registry\EtapaRegistry;

/**
 * Class Etapa
 * @package ECidade\Educacao\Secretaria\BNCC\Model
 */
class Etapa
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var string
     */
    private $etapa;

    /**
     * @var string
     */
    private $ensino;

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
     * @return string
     */
    public function getEtapa()
    {
        return $this->etapa;
    }

    /**
     * @param string $etapa
     */
    public function setEtapa($etapa)
    {
        $this->etapa = $etapa;
    }

    /**
     * @return string
     */
    public function getEnsino()
    {
        return $this->ensino;
    }

    /**
     * @param string $ensino
     */
    public function setEnsino($ensino)
    {
        $this->ensino = $ensino;
    }

    /**
     * @param array $state
     * @return Etapa
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed152_sequencial', $state)) {
            $self->setCodigo($state['ed152_sequencial']);
        }
        if (array_key_exists('ed152_etapa', $state)) {
            $self->setEtapa($state['ed152_etapa']);
        }
        if (array_key_exists('ed152_ensino', $state)) {
            $self->setEnsino($state['ed152_ensino']);
        }

        EtapaRegistry::set($self);

        return $self;
    }
}

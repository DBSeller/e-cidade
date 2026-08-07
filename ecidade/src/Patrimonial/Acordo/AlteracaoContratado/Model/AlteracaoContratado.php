<?php

namespace ECidade\Patrimonial\Acordo\AlteracaoContratado\Model;

use cl_acordoalteracaocontratado;
use db_utils;

class AlteracaoContratado
{

    /**
     * Codigo da alteração do contratado
     *
     * @var integer
     */
    protected $codigoAlteracaoContratado;

    /**
     * Acordo da alteração do contratado
     *
     * @var integer
     */
    protected $codigoAcordo;


    /**
     * Posição da alteração do contratado
     *
     * @var integer
     */
    protected $posicaoAcordo;

    /**
     * Contratado anterior
     *
     * @var integer
     */
    protected $contratadoAnterior;

    /**
     * Novo Contratado
     *
     * @var integer
     */
    protected $contratadoNovo;

    /**
     * @return int|mixed
     */
    public function getCodigoAlteracaoContratado()
    {
        return $this->codigoAlteracaoContratado;
    }

    /**
     * @param int|mixed $codigoAlteracaoContratado
     */
    public function setCodigoAlteracaoContratado($codigoAlteracaoContratado)
    {
        $this->codigoAlteracaoContratado = $codigoAlteracaoContratado;
    }

    /**
     * @return int
     */
    public function getCodigoAcordo()
    {
        return $this->codigoAcordo;
    }

    /**
     * @param int $codigoAcordo
     */
    public function setCodigoAcordo($codigoAcordo)
    {
        $this->codigoAcordo = $codigoAcordo;
    }

    /**
     * @return int
     */
    public function getPosicaoAcordo()
    {
        return $this->posicaoAcordo;
    }

    /**
     * @param int $posicaoAcordo
     */
    public function setPosicaoAcordo($posicaoAcordo)
    {
        $this->posicaoAcordo = $posicaoAcordo;
    }

    /**
     * @return int
     */
    public function getContratadoAnterior()
    {
        return $this->contratadoAnterior;
    }

    /**
     * @param int $contratadoAnterior
     */
    public function setContratadoAnterior($contratadoAnterior)
    {
        $this->contratadoAnterior = $contratadoAnterior;
    }

    /**
     * @return int
     */
    public function getContratadoNovo()
    {
        return $this->contratadoNovo;
    }

    /**
     * @param int $contratadoNovo
     */
    public function setContratadoNovo($contratadoNovo)
    {
        $this->contratadoNovo = $contratadoNovo;
    }

    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ac60_sequencial', $state)) {
            $self->setCodigoAlteracaoContratado($state['ac60_sequencial']);
        }
        if (array_key_exists('ac60_acordo', $state)) {
            $self->setCodigoAcordo($state['ac60_acordo']);
        }
        if (array_key_exists('ac60_posicao', $state)) {
            $self->setPosicaoAcordo($state['ac60_posicao']);
        }
        if (array_key_exists('ac60_anterior', $state)) {
            $self->setContratadoAnterior($state['ac60_anterior']);
        }
        if (array_key_exists('ac60_novo', $state)) {
            $self->setContratadoNovo($state['ac60_novo']);
        }

        return $self;
    }

    public function toArray()
    {
        return array(
            'ac60_sequencial' => $this->getCodigoAlteracaoContratado(),
            'ac60_acordo' => $this->getCodigoAcordo(),
            'ac60_posicao' => $this->getPosicaoAcordo(),
            'ac60_anterior' => $this->getContratadoAnterior(),
            'ac60_novo' => $this->getContratadoNovo()
        );
    }
}

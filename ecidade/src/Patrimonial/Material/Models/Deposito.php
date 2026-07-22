<?php

namespace ECidade\Patrimonial\Material\Models;

use DBDepartamento;
use Exception;

class Deposito
{
    /**
     * @var Integer
     */
    private $codigo;
    /**
     * @var DBDepartamento
     */
    private $departamento;

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
     * @return DBDepartamento
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param DBDepartamento $departamento
     */
    public function setDepartamento(DBDepartamento $departamento)
    {
        $this->departamento = $departamento;
    }

    /**
     * @param array $state
     * @return Deposito
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('m91_codigo', $state)) {
            $self->setCodigo($state['m91_codigo']);
        }
        if (array_key_exists('coddepto', $state)) {
            $self->setDepartamento(new DBDepartamento($state['coddepto']));
        }
        return $self;
    }
}

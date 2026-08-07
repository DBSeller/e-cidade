<?php

namespace ECidade\RecursosHumanos\RH\Assentamento\Model;

use Exception;

class HoraExtraManual
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $codigoAssentamento;

    /**
     * @var String
     */
    private $hora;

    /**
     * @var integer
     */
    private $tipo;



    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    public function setCodigoAssentamento($codigoAssentamento)
    {
        $this->codigoAssentamento = $codigoAssentamento;
    }

    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getCodigoAssentamento()
    {
        return $this->codigoAssentamento;
    }

    public function getSequencial()
    {
        return $this->sequencial;
    }

    public function getHora()
    {
        return $this->hora;
    }

    public function getTipo()
    {
        return $this->tipo;
    }
}

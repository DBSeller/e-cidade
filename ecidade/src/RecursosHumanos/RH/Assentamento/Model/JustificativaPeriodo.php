<?php

namespace ECidade\RecursosHumanos\RH\Assentamento\Model;

use Exception;

class JustificativaPeriodo
{
    /**
     * @var integer
     */
    private $codigoAssentamento;

    /**
     * @var integer
     */
    private $periodo;

    /**
     * @return int
     */
    public function getcodigoAssentamento()
    {
        return $this->codigoAssentamento;
    }

    public function setCodigoAssentamento($codigo)
    {
        $this->codigoAssentamento = $codigo;
    }

    public function getPeriodo()
    {
        return $this->periodo;
    }

    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
    }
}

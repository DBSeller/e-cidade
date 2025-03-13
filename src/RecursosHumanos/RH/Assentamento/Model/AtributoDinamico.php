<?php

namespace ECidade\RecursosHumanos\RH\Assentamento\Model;

use Exception;

class AtributoDinamico
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $grupo;

    /**
     * @var integer
     */
    private $atributo;

    /**
     * @var string
     */
    private $valor;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return int
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * @param int $grupo
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    /**
     * @return int
     */
    public function getAtributo()
    {
        return $this->atributo;
    }

    /**
     * @param int $atributo
     */
    public function setAtributo($atributo)
    {
        $this->atributo = $atributo;
    }

    /**
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param string $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }
}

<?php

namespace ECidade\RecursosHumanos\RH\Assentamento\Model;

class AtributoDinamicoGrupo
{
    /**
     * @var integer
     */
    private $sequencial;

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
}

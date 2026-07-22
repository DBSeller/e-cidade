<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity;

use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Entity\Header as HeaderPattern;

final class Header extends HeaderPattern
{
    const NUMERO_REMESSA        = 'NUMEROREMESSA';
    const INICIO_VIGENCIA       = 'INICIOVIGENCIA';
    const FIM_VIGENCIA          = 'FIMVIGENCIA';
    const CODIGO_CLIENTE_BANCO  = 'CODIGOCLIENTEBANCO';
    const RESERVADO             = 'RESERVADO';
    const SEQUENCIAL            = 'SEQUENCIAL';

    private $numero;
    private $dataInicioVigencia;
    private $dataFimVigencia;

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * @return \DateTime
     */
    public function getDataInicioVigencia()
    {
        return $this->dataInicioVigencia;
    }

    /**
     * @param \DateTime $dataInicioVigencia
     */
    public function setDataInicioVigencia(\DateTime $dataInicioVigencia)
    {
        $this->dataInicioVigencia = $dataInicioVigencia;
    }

    /**
     * @return \DateTime
     */
    public function getDataFimVigencia()
    {
        return $this->dataFimVigencia;
    }

    /**
     * @param \DateTime $dataFimVigencia
     */
    public function setDataFimVigencia(\DateTime $dataFimVigencia)
    {
        $this->dataFimVigencia = $dataFimVigencia;
    }
}

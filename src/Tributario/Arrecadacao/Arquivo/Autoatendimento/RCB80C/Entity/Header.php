<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80C\Entity;

use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Entity\Header as HeaderPattern;
use DateTime;

final class Header extends HeaderPattern
{
    const DATA_PROCESSAMENTO    = 'DATAPROCESSAMENTO';
    const RESERVADO1            = 'RESERVADO1';
    const CODIGO_CLIENTE_BANCO  = 'CODIGOCLIENTEBANCO';
    const RESERVADO2            = 'RESERVADO2';
    const SEQUENCIAL_REGISTRO   = 'SEQUENCIALREGISTRO';
    const RESERVADO3            = 'RESERVADO3';

    private $dataProcessamento;
    private $reservado1;
    private $codigoClienteBanco;
    private $reservado2;
    private $sequencialRegistro;
    private $reservado3;

    /**
     * @return mixed
     */
    public function getDataProcessamento()
    {
        return $this->dataProcessamento;
    }

    /**
     * @param DateTime $dataProcessamento
     */
    public function setDataProcessamento(DateTime $dataProcessamento)
    {
        $this->dataProcessamento = $dataProcessamento;
    }

    /**
     * @return mixed
     */
    public function getReservado1()
    {
        return $this->reservado1;
    }

    /**
     * @param mixed $reservado1
     */
    public function setReservado1($reservado1)
    {
        $this->reservado1 = $reservado1;
    }

    /**
     * @return mixed
     */
    public function getCodigoClienteBanco()
    {
        return $this->codigoClienteBanco;
    }

    /**
     * @param mixed $codigoClienteBanco
     */
    public function setCodigoClienteBanco($codigoClienteBanco)
    {
        $this->codigoClienteBanco = $codigoClienteBanco;
    }

    /**
     * @return mixed
     */
    public function getReservado2()
    {
        return $this->reservado2;
    }

    /**
     * @param mixed $reservado2
     */
    public function setReservado2($reservado2)
    {
        $this->reservado2 = $reservado2;
    }

    /**
     * @return mixed
     */
    public function getSequencialRegistro()
    {
        return $this->sequencialRegistro;
    }

    /**
     * @param mixed $sequencialRegistro
     */
    public function setSequencialRegistro($sequencialRegistro)
    {
        $this->sequencialRegistro = $sequencialRegistro;
    }

    /**
     * @return mixed
     */
    public function getReservado3()
    {
        return $this->reservado3;
    }

    /**
     * @param mixed $reservado3
     */
    public function setReservado3($reservado3)
    {
        $this->reservado3 = $reservado3;
    }
}

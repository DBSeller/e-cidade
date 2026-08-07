<?php

namespace ECidade\Tributario\Arrecadacao\Model;

use DateTime;

/**
 * Class ReciboAvulso
 * @package ECidade\Tributario\Arrecadacao\Model
 */
class ReciboAvulso
{
    /**
     * @var int
     */
    private $codigoHistorico;

    /**
     * @var int
     */
    private $codigoReceita;

    /**
     * @var int
     */
    private $codsubrec;

    /**
     * @var DateTime
     */
    private $dataOperacao;

    /**
     * @var DateTime
     */
    private $dataVencimento;

    /**
     * @var int
     */
    private $numeroCgm;

    /**
     * @var int
     */
    private $numdig;

    /**
     * @var int
     */
    private $numnov;

    /**
     * @var int
     */
    private $numpar;

    /**
     * @var int
     */
    private $numpre;

    /**
     * @var int
     */
    private $numtot;

    /**
     * @var int
     */
    private $tipoDebito;

    /**
     * @var int
     */
    private $tipojm;

    /**
     * @var float
     */
    private $valor;

    /**
     * @return int
     */
    public function getCodigoHistorico()
    {
        return $this->codigoHistorico;
    }

    /**
     * @return int
     */
    public function getCodigoReceita()
    {
        return $this->codigoReceita;
    }

    /**
     * @return int
     */
    public function getCodsubrec()
    {
        return $this->codsubrec;
    }

    /**
     * @return DateTime
     */
    public function getDataOperacao()
    {
        return $this->dataOperacao;
    }

    /**
     * @return DateTime
     */
    public function getDataVencimento()
    {
        return $this->dataVencimento;
    }

    /**
     * @return int
     */
    public function getNumeroCgm()
    {
        return $this->numeroCgm;
    }

    /**
     * @return int
     */
    public function getNumdig()
    {
        return $this->numdig;
    }

    /**
     * @return int
     */
    public function getNumnov()
    {
        return $this->numnov;
    }

    /**
     * @return int
     */
    public function getNumpar()
    {
        return $this->numpar;
    }

    /**
     * @return int
     */
    public function getNumpre()
    {
        return $this->numpre;
    }

    /**
     * @return int
     */
    public function getNumtot()
    {
        return $this->numtot;
    }

    /**
     * @return int
     */
    public function getTipoDebito()
    {
        return $this->tipoDebito;
    }

    /**
     * @return int
     */
    public function getTipojm()
    {
        return $this->tipojm;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param int $codigoHistorico
     */
    public function setCodigoHistorico($codigoHistorico)
    {
        $this->codigoHistorico = $codigoHistorico;
    }

    /**
     * @param int $codigoReceita
     */
    public function setCodigoReceita($codigoReceita)
    {
        $this->codigoReceita = $codigoReceita;
    }

    /**
     * @param int $codsubrec
     */
    public function setCodsubrec($codsubrec)
    {
        $this->codsubrec = $codsubrec;
    }

    /**
     * @param DateTime $dataOperacao
     */
    public function setDataOperacao($dataOperacao)
    {
        $this->dataOperacao = $dataOperacao;
    }

    /**
     * @param DateTime $dataVencimento
     */
    public function setDataVencimento($dataVencimento)
    {
        $this->dataVencimento = $dataVencimento;
    }

    /**
     * @param int $numeroCgm
     */
    public function setNumeroCgm($numeroCgm)
    {
        $this->numeroCgm = $numeroCgm;
    }

    /**
     * @param int $numdig
     */
    public function setNumdig($numdig)
    {
        $this->numdig = $numdig;
    }

    /**
     * @param int $numnov
     */
    public function setNumnov($numnov)
    {
        $this->numnov = $numnov;
    }

    /**
     * @param int $numpar
     */
    public function setNumpar($numpar)
    {
        $this->numpar = $numpar;
    }

    /**
     * @param int $numpre
     */
    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    /**
     * @param int $numtot
     */
    public function setNumtot($numtot)
    {
        $this->numtot = $numtot;
    }

    /**
     * @param int $tipoDebito
     */
    public function setTipoDebito($tipoDebito)
    {
        $this->tipoDebito = $tipoDebito;
    }

    /**
     * @param int $tipojm
     */
    public function setTipojm($tipojm)
    {
        $this->tipojm = $tipojm;
    }

    /**
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }
}

<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;

class Disbanco extends Model
{
    protected $table = "disbanco";

    /**
     * @return int
     */
    public function getNumbco()
    {
        return $this->numbco;
    }

    /**
     * @param int $numbco
     * @return Disbanco
     */
    public function setNumbco($numbco)
    {
        $this->numbco = $numbco;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodbco()
    {
        return $this->codbco;
    }

    /**
     * @param int $codbco
     * @return Disbanco
     */
    public function setCodbco($codbco)
    {
        $this->codbco = $codbco;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodage()
    {
        return $this->codage;
    }

    /**
     * @param string $codage
     * @return Disbanco
     */
    public function setCodage($codage)
    {
        $this->codage = $codage;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodret()
    {
        return $this->codret;
    }

    /**
     * @param int $codret
     * @return Disbanco
     */
    public function setCodret($codret)
    {
        $this->codret = $codret;
        return $this;
    }

    /**
     * @return string
     */
    public function getDtarq()
    {
        return $this->dtarq;
    }

    /**
     * @param string $dtarq
     * @return Disbanco
     */
    public function setDtarq($dtarq)
    {
        $this->dtarq = $dtarq;
        return $this;
    }

    /**
     * @return string
     */
    public function getDtpago()
    {
        return $this->dtpago;
    }

    /**
     * @param string $dtpago
     * @return Disbanco
     */
    public function setDtpago($dtpago)
    {
        $this->dtpago = $dtpago;
        return $this;
    }

    /**
     * @return float
     */
    public function getVlrpago()
    {
        return $this->vlrpago;
    }

    /**
     * @param float $vlrpago
     * @return Disbanco
     */
    public function setVlrpago($vlrpago)
    {
        $this->vlrpago = $vlrpago;
        return $this;
    }

    /**
     * @return float
     */
    public function getVlrjuros()
    {
        return $this->vlrjuros;
    }

    /**
     * @param float $vlrjuros
     * @return Disbanco
     */
    public function setVlrjuros($vlrjuros)
    {
        $this->vlrjuros = $vlrjuros;
        return $this;
    }

    /**
     * @return float
     */
    public function getVlrmulta()
    {
        return $this->vlrmulta;
    }

    /**
     * @param float $vlrmulta
     * @return Disbanco
     */
    public function setVlrmulta($vlrmulta)
    {
        $this->vlrmulta = $vlrmulta;
        return $this;
    }

    /**
     * @return float
     */
    public function getVlracres()
    {
        return $this->vlracres;
    }

    /**
     * @param float $vlracres
     * @return Disbanco
     */
    public function setVlracres($vlracres)
    {
        $this->vlracres = $vlracres;
        return $this;
    }

    /**
     * @return float
     */
    public function getVlrdesco()
    {
        return $this->vlrdesco;
    }

    /**
     * @param float $vlrdesco
     * @return Disbanco
     */
    public function setVlrdesco($vlrdesco)
    {
        $this->vlrdesco = $vlrdesco;
        return $this;
    }

    /**
     * @return float
     */
    public function getVlrtot()
    {
        return $this->vlrtot;
    }

    /**
     * @param float $vlrtot
     * @return Disbanco
     */
    public function setVlrtot($vlrtot)
    {
        $this->vlrtot = $vlrtot;
        return $this;
    }

    /**
     * @return string
     */
    public function getCedente()
    {
        return $this->cedente;
    }

    /**
     * @param string $cedente
     * @return Disbanco
     */
    public function setCedente($cedente)
    {
        $this->cedente = $cedente;
        return $this;
    }

    /**
     * @return float
     */
    public function getVlrcalc()
    {
        return $this->vlrcalc;
    }

    /**
     * @param float $vlrcalc
     * @return Disbanco
     */
    public function setVlrcalc($vlrcalc)
    {
        $this->vlrcalc = $vlrcalc;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdret()
    {
        return $this->idret;
    }

    /**
     * @param int $idret
     * @return Disbanco
     */
    public function setIdret($idret)
    {
        $this->idret = $idret;
        return $this;
    }

    /**
     * @return bool
     */
    public function isClassi()
    {
        return $this->classi;
    }

    /**
     * @param bool $classi
     * @return Disbanco
     */
    public function setClassi($classi)
    {
        $this->classi = $classi;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumpre()
    {
        return $this->numpre;
    }

    /**
     * @param int $numpre
     * @return Disbanco
     */
    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumpar()
    {
        return $this->numpar;
    }

    /**
     * @param int $numpar
     * @return Disbanco
     */
    public function setNumpar($numpar)
    {
        $this->numpar = $numpar;
        return $this;
    }

    /**
     * @return string
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * @param string $convenio
     * @return Disbanco
     */
    public function setConvenio($convenio)
    {
        $this->convenio = $convenio;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstit()
    {
        return $this->instit;
    }

    /**
     * @param int $instit
     * @return Disbanco
     */
    public function setInstit($instit)
    {
        $this->instit = $instit;
        return $this;
    }

    /**
     * @return string
     */
    public function getDtcredito()
    {
        return $this->dtcredito;
    }

    /**
     * @param string $dtcredito
     * @return Disbanco
     */
    public function setDtcredito($dtcredito)
    {
        $this->dtcredito = $dtcredito;
        return $this;
    }

    /**
     * @return string
     */
    public function getBancopagamento()
    {
        return $this->bancopagamento;
    }

    /**
     * @param string $bancopagamento
     * @return Disbanco
     */
    public function setBancopagamento($bancopagamento)
    {
        $this->bancopagamento = $bancopagamento;
        return $this;
    }

    /**
     * @return string
     */
    public function getAgenciapagamento()
    {
        return $this->agenciapagamento;
    }

    /**
     * @param string $agenciapagamento
     * @return Disbanco
     */
    public function setAgenciapagamento($agenciapagamento)
    {
        $this->agenciapagamento = $agenciapagamento;
        return $this;
    }
}

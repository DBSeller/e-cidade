<?php

namespace ECidade\Tributario\Caixa\Model;

use DateTime;
use ECidade\Tributario\Library\Model;
use Exception;

final class Recibopaga extends Model
{
    private $numcgm;

    private $dtoper;

    private $receit;

    private $hist;

    private $valor;

    private $dtvenc;

    private $numpre;

    private $numpar;

    private $numtot;

    private $numdig;

    private $conta;

    private $dtpaga;

    private $numnov;

    public function setNumcgm($numcgm)
    {
        $this->numcgm = $numcgm;
    }

    public function setDtoper(DateTime $dtoper)
    {
        $this->dtoper = $dtoper;
    }

    public function setReceit($receit)
    {
        $this->receit = $receit;
    }

    public function setHist($hist)
    {
        $this->hist = $hist;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function setDtvenc(DateTime $dtvenc)
    {
        $this->dtvenc = $dtvenc;
    }

    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function setNumpar($numpar)
    {
        $this->numpar = $numpar;
    }

    public function setNumtot($numtot)
    {
        $this->numtot = $numtot;
    }

    public function setNumdig($numdig)
    {
        $this->numdig = $numdig;
    }

    public function setConta($conta)
    {
        $this->conta = $conta;
    }

    public function setDtpaga(DateTime $dtpaga)
    {
        $this->dtpaga = $dtpaga;
    }

    public function setNumnov($numnov)
    {
        $this->numnov = $numnov;
    }

    public function getNumcgm()
    {
        return $this->numcgm;
    }

    public function getDtoper()
    {
        return $this->dtoper;
    }

    public function getReceit()
    {
        return $this->receit;
    }

    public function getHist()
    {
        return $this->hist;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function getDtvenc()
    {
        return $this->dtvenc;
    }

    public function getNumpre()
    {
        return $this->numpre;
    }

    public function getNumpar()
    {
        return $this->numpar;
    }

    public function getNumtot()
    {
        return $this->numtot;
    }

    public function getNumdig()
    {
        return $this->numdig;
    }

    public function getConta()
    {
        return $this->conta;
    }

    public function getDtpaga()
    {
        return $this->dtpaga;
    }

    public function getNumnov()
    {
        return $this->numnov;
    }

    /**
     * @param  array $state
     * @return self
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('k00_numcgm', $state)) {
            $self->setNumcgm($state['k00_numcgm']);
        }
        if (array_key_exists('k00_dtoper', $state)) {
            $dataOperacao = new DateTime($state['k00_dtoper']);
            $self->setDtoper($dataOperacao);
        }
        if (array_key_exists('k00_receit', $state)) {
            $self->setReceit($state['k00_receit']);
        }
        if (array_key_exists('k00_hist', $state)) {
            $self->setHist($state['k00_hist']);
        }
        if (array_key_exists('k00_valor', $state)) {
            $self->setValor($state['k00_valor']);
        }
        if (array_key_exists('k00_dtvenc', $state)) {
            $dataVencimento = new DateTime($state['k00_dtvenc']);
            $self->setDtvenc($dataVencimento);
        }
        if (array_key_exists('k00_numpre', $state)) {
            $self->setNumpre($state['k00_numpre']);
        }
        if (array_key_exists('k00_numpar', $state)) {
            $self->setNumpar($state['k00_numpar']);
        }
        if (array_key_exists('k00_numtot', $state)) {
            $self->setNumtot($state['k00_numtot']);
        }
        if (array_key_exists('k00_numdig', $state)) {
            $self->setNumdig($state['k00_numdig']);
        }
        if (array_key_exists('k00_conta', $state)) {
            $self->setConta($state['k00_conta']);
        }
        if (array_key_exists('k00_dtpaga', $state)) {
            $dataPagamento = new DateTime($state['k00_dtpaga']);
            $self->setDtpaga($dataPagamento);
        }
        if (array_key_exists('k00_numnov', $state)) {
            $self->setNumnov($state['k00_numnov']);
        }

        return $self;
    }
}

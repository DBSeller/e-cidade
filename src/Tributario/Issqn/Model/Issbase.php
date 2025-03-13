<?php

namespace ECidade\Tributario\Issqn\Model;

use ECidade\Tributario\Library\Model;
use Empresa;

final class Issbase extends Model
{
    private $inscr;
    private $numcgm;
    private $memo;
    private $tiplic;
    private $regjuc;
    private $inscmu;
    private $obs;
    private $dtcada;
    private $dtinic;
    private $dtbaix;
    private $capit;
    private $cep;
    private $dtjunta;
    private $ultalt;
    private $dtalt;

    /**
     * @var Empresa
     */
    private $empresa;

    /**
     * @return mixed
     */
    public function getInscr()
    {
        return $this->inscr;
    }

    /**
     * @param mixed $inscr
     */
    public function setInscr($inscr)
    {
        $this->inscr = $inscr;
    }

    /**
     * @return mixed
     */
    public function getNumcgm()
    {
        return $this->numcgm;
    }

    /**
     * @param mixed $numcgm
     */
    public function setNumcgm($numcgm)
    {
        $this->numcgm = $numcgm;
    }

    /**
     * @return mixed
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * @param mixed $memo
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;
    }

    /**
     * @return mixed
     */
    public function getTiplic()
    {
        return $this->tiplic;
    }

    /**
     * @param mixed $tiplic
     */
    public function setTiplic($tiplic)
    {
        $this->tiplic = $tiplic;
    }

    /**
     * @return mixed
     */
    public function getRegjuc()
    {
        return $this->regjuc;
    }

    /**
     * @param mixed $regjuc
     */
    public function setRegjuc($regjuc)
    {
        $this->regjuc = $regjuc;
    }

    /**
     * @return mixed
     */
    public function getInscmu()
    {
        return $this->inscmu;
    }

    /**
     * @param mixed $inscmu
     */
    public function setInscmu($inscmu)
    {
        $this->inscmu = $inscmu;
    }

    /**
     * @return mixed
     */
    public function getObs()
    {
        return $this->obs;
    }

    /**
     * @param mixed $obs
     */
    public function setObs($obs)
    {
        $this->obs = $obs;
    }

    /**
     * @return mixed
     */
    public function getDtcada()
    {
        return $this->dtcada;
    }

    /**
     * @param mixed $dtcada
     */
    public function setDtcada($dtcada)
    {
        $this->dtcada = $dtcada;
    }

    /**
     * @return mixed
     */
    public function getDtinic()
    {
        return $this->dtinic;
    }

    /**
     * @param mixed $dtinic
     */
    public function setDtinic($dtinic)
    {
        $this->dtinic = $dtinic;
    }

    /**
     * @return mixed
     */
    public function getDtbaix()
    {
        return $this->dtbaix;
    }

    /**
     * @param mixed $dtbaix
     */
    public function setDtbaix($dtbaix)
    {
        $this->dtbaix = $dtbaix;
    }

    /**
     * @return mixed
     */
    public function getCapit()
    {
        return $this->capit;
    }

    /**
     * @param mixed $capit
     */
    public function setCapit($capit)
    {
        $this->capit = $capit;
    }

    /**
     * @return mixed
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @param mixed $cep
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    /**
     * @return mixed
     */
    public function getDtjunta()
    {
        return $this->dtjunta;
    }

    /**
     * @param mixed $dtjunta
     */
    public function setDtjunta($dtjunta)
    {
        $this->dtjunta = $dtjunta;
    }

    /**
     * @return mixed
     */
    public function getUltalt()
    {
        return $this->ultalt;
    }

    /**
     * @param mixed $ultalt
     */
    public function setUltalt($ultalt)
    {
        $this->ultalt = $ultalt;
    }

    /**
     * @return mixed
     */
    public function getDtalt()
    {
        return $this->dtalt;
    }

    /**
     * @param mixed $dtalt
     */
    public function setDtalt($dtalt)
    {
        $this->dtalt = $dtalt;
    }

    public function withEmpresa()
    {
        if ($this->empresa === null) {
            $this->empresa = new Empresa($this->getInscr());
        }

        return $this;
    }

    public function getEmpresa()
    {
        return $this->empresa;
    }
}

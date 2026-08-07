<?php

namespace ECidade\RecursosHumanos\ESocial\Entity;

/**
 * Class TrabalhoIntermitente
 * @package ECidade\RecursosHumanos\ESocial\Entity
 */
class TrabalhoIntermitente
{
    /**
     * @var int
     */
    const AVALIACAO = 3000029;
    /**
     * @var
     */
    private $cpfTrab;
    /**
     * @var
     */
    private $nisTrab;
    /**
     * @var
     */
    private $matricula;
    /**
     * @var
     */
    private $codConv;
    /**
     * @var
     */
    private $dtInicio;
    /**
     * @var
     */
    private $dtFim;
    /**
     * @var
     */
    private $codHorContrat;
    /**
     * @var
     */
    private $dscJornada;
    /**
     * @var
     */
    private $indLocal;
    /**
     * @var
     */
    private $tpLograd;
    /**
     * @var
     */
    private $dscLograd;
    /**
     * @var
     */
    private $nrLograd;
    /**
     * @var
     */
    private $complem;
    /**
     * @var
     */
    private $bairro;
    /**
     * @var
     */
    private $cep;
    /**
     * @var
     */
    private $codMunic;
    /**
     * @var
     */
    private $uf;

    /**
     * @return mixed;
     */
    public function getCpfTrab()
    {
        return $this->cpfTrab;
    }

    /**
     * @param mixed cpfTrab
     */
    public function setCpfTrab($cpfTrab)
    {
        $this->cpfTrab = $cpfTrab;
    }

    /**
     * @return mixed;
     */
    public function getNisTrab()
    {
        return $this->nisTrab;
    }

    /**
     * @param mixed nisTrab
     */
    public function setNisTrab($nisTrab)
    {
        $this->nisTrab = $nisTrab;
    }

    /**
     * @return mixed;
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param mixed matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * @return mixed;
     */
    public function getCodConv()
    {
        return $this->codConv;
    }

    /**
     * @param mixed codConv
     */
    public function setCodConv($codConv)
    {
        $this->codConv = $codConv;
    }

    /**
     * @return mixed;
     */
    public function getDtInicio()
    {
        return $this->dtInicio;
    }

    /**
     * @param mixed dtInicio
     */
    public function setDtInicio($dtInicio)
    {
        $this->dtInicio = $dtInicio;
    }

    /**
     * @return mixed;
     */
    public function getDtFim()
    {
        return $this->dtFim;
    }

    /**
     * @param mixed dtFim
     */
    public function setDtFim($dtFim)
    {
        $this->dtFim = $dtFim;
    }

    /**
     * @return mixed;
     */
    public function getCodHorContrat()
    {
        return $this->codHorContrat;
    }

    /**
     * @param mixed codHorContrat
     */
    public function setCodHorContrat($codHorContrat)
    {
        $this->codHorContrat = $codHorContrat;
    }

    /**
     * @return mixed;
     */
    public function getDscJornada()
    {
        return $this->dscJornada;
    }

    /**
     * @param mixed dscJornada
     */
    public function setDscJornada($dscJornada)
    {
        $this->dscJornada = $dscJornada;
    }

    /**
     * @return mixed;
     */
    public function getIndLocal()
    {
        return $this->indLocal;
    }

    /**
     * @param mixed indLocal
     */
    public function setIndLocal($indLocal)
    {
        $this->indLocal = $indLocal;
    }

    /**
     * @return mixed;
     */
    public function getTpLograd()
    {
        return $this->tpLograd;
    }

    /**
     * @param mixed tpLograd
     */
    public function setTpLograd($tpLograd)
    {
        $this->tpLograd = $tpLograd;
    }

    /**
     * @return mixed;
     */
    public function getDscLograd()
    {
        return $this->dscLograd;
    }

    /**
     * @param mixed dscLograd
     */
    public function setDscLograd($dscLograd)
    {
        $this->dscLograd = $dscLograd;
    }

    /**
     * @return mixed;
     */
    public function getNrLograd()
    {
        return $this->nrLograd;
    }

    /**
     * @param mixed nrLograd
     */
    public function setNrLograd($nrLograd)
    {
        $this->nrLograd = $nrLograd;
    }

    /**
     * @return mixed;
     */
    public function getComplem()
    {
        return $this->complem;
    }

    /**
     * @param mixed complem
     */
    public function setComplem($complem)
    {
        $this->complem = $complem;
    }

    /**
     * @return mixed;
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @param mixed bairro
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    /**
     * @return mixed;
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @param mixed cep
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    /**
     * @return mixed;
     */
    public function getCodMunic()
    {
        return $this->codMunic;
    }

    /**
     * @param mixed codMunic
     */
    public function setCodMunic($codMunic)
    {
        $this->codMunic = $codMunic;
    }

    /**
     * @return mixed;
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param mixed uf
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
    }
}

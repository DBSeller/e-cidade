<?php

namespace ECidade\RecursosHumanos\ESocial\Entity;

/**
 * Class ExclusaoEvento
 * @package ECidade\RecursosHumanos\ESocial\Entity
 */
class ExclusaoEvento
{
    /**
     * @var int
     */
    const AVALIACAO = 3000025;

    /**
     * @var
     */
    private $tpEvento;
    /**
     * @var
     */
    private $nrRecEvt;
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
    private $indApuracao;
    /**
     * @var
     */
    private $perApur;

    /**
     * @return mixed
     */
    public function getTpEvento()
    {
        return $this->tpEvento;
    }

    /**
     * @param mixed $tpEvento
     */
    public function setTpEvento($tpEvento)
    {
        $this->tpEvento = $tpEvento;
    }

    /**
     * @return mixed
     */
    public function getNrRecEvt()
    {
        return $this->nrRecEvt;
    }

    /**
     * @param mixed $nrRecEvt
     */
    public function setNrRecEvt($nrRecEvt)
    {
        $this->nrRecEvt = $nrRecEvt;
    }

    /**
     * @return mixed
     */
    public function getCpfTrab()
    {
        return $this->cpfTrab;
    }

    /**
     * @param mixed $cpfTrab
     */
    public function setCpfTrab($cpfTrab)
    {
        $this->cpfTrab = $cpfTrab;
    }

    /**
     * @return mixed
     */
    public function getNisTrab()
    {
        return $this->nisTrab;
    }

    /**
     * @param mixed $nisTrab
     */
    public function setNisTrab($nisTrab)
    {
        $this->nisTrab = $nisTrab;
    }

    /**
     * @return mixed
     */
    public function getIndApuracao()
    {
        return $this->indApuracao;
    }

    /**
     * @param mixed $indApuracao
     */
    public function setIndApuracao($indApuracao)
    {
        $this->indApuracao = $indApuracao;
    }

    /**
     * @return mixed
     */
    public function getPerApur()
    {
        return $this->perApur;
    }

    /**
     * @param mixed $perApur
     */
    public function setPerApur($perApur)
    {
        $this->perApur = $perApur;
    }
}
<?php

namespace ECidade\Tributario\Issqn\Model;

class Issvar
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var integer
     */
    private $numpre;

    /**
     * @var integer
     */
    private $numpar;

    /**
     * @var float
     */
    private $valor;

    /**
     * @var integer
     */
    private $ano;

    /**
     * @var integer
     */
    private $mes;

    /**
     * @var string
     */
    private $histor;

    /**
     * @var float
     */
    private $aliq;

    /**
     * @var float
     */
    private $bruto;

    /**
     * @var float
     */
    private $vlrinf;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return Issvar
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
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
     * @return Issvar
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
     * @return Issvar
     */
    public function setNumpar($numpar)
    {
        $this->numpar = $numpar;
        return $this;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     * @return Issvar
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param int $ano
     * @return Issvar
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
        return $this;
    }

    /**
     * @return int
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param int $mes
     * @return Issvar
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
        return $this;
    }

    /**
     * @return string
     */
    public function getHistor()
    {
        return $this->histor;
    }

    /**
     * @param string $histor
     * @return Issvar
     */
    public function setHistor($histor)
    {
        $this->histor = $histor;
        return $this;
    }

    /**
     * @return float
     */
    public function getAliq()
    {
        return $this->aliq;
    }

    /**
     * @param float $aliq
     * @return Issvar
     */
    public function setAliq($aliq)
    {
        $this->aliq = $aliq;
        return $this;
    }

    /**
     * @return float
     */
    public function getBruto()
    {
        return $this->bruto;
    }

    /**
     * @param float $bruto
     * @return Issvar
     */
    public function setBruto($bruto)
    {
        $this->bruto = $bruto;
        return $this;
    }

    /**
     * @return float
     */
    public function getVlrinf()
    {
        return $this->vlrinf;
    }

    /**
     * @param float $vlrinf
     * @return Issvar
     */
    public function setVlrinf($vlrinf)
    {
        $this->vlrinf = $vlrinf;
        return $this;
    }
}

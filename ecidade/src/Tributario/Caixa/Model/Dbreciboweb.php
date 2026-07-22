<?php

namespace ECidade\Tributario\Caixa\Model;

use ECidade\Tributario\Library\Model;

final class Dbreciboweb extends Model
{
    private $numpre;

    private $numpar;

    private $numpren;

    private $codbco;

    private $codage;

    private $numbco;

    private $desconto;

    private $tipo;

    private $origem;

    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function setNumpar($numpar)
    {
        $this->numpar = $numpar;
    }

    public function setNumpren($numpren)
    {
        $this->numpren = $numpren;
    }

    public function setCodbco($codbco)
    {
        $this->codbco = $codbco;
    }

    public function setCodage($codage)
    {
        $this->codage = $codage;
    }

    public function setNumbco($numbco)
    {
        $this->numbco = $numbco;
    }

    public function setDesconto($desconto)
    {
        $this->desconto = $desconto;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function setOrigem($origem)
    {
        $this->origem = $origem;
    }

    public function getNumpre()
    {
        return $this->numpre;
    }

    public function getNumpar()
    {
        return $this->numpar;
    }

    public function getNumpren()
    {
        return $this->numpren;
    }

    public function getCodbco()
    {
        return $this->codbco;
    }

    public function getCodage()
    {
        return $this->codage;
    }

    public function getNumbco()
    {
        return $this->numbco;
    }

    public function getDesconto()
    {
        return $this->desconto;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getOrigem()
    {
        return $this->origem;
    }
}

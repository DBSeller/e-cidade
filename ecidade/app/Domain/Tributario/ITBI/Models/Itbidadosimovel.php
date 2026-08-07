<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbidadosimovel extends Model
{
    protected $table = "itbidadosimovel";

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Itbidadosimovel
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getItbi()
    {
        return $this->itbi;
    }

    /**
     * @param int $itbi
     * @return Itbidadosimovel
     */
    public function setItbi($itbi)
    {
        $this->itbi = $itbi;
        return $this;
    }

    /**
     * @return int
     */
    public function getSetor()
    {
        return $this->setor;
    }

    /**
     * @param int $setor
     * @return Itbidadosimovel
     */
    public function setSetor($setor)
    {
        $this->setor = $setor;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuadra()
    {
        return $this->quadra;
    }

    /**
     * @param int $quadra
     * @return Itbidadosimovel
     */
    public function setQuadra($quadra)
    {
        $this->quadra = $quadra;
        return $this;
    }

    /**
     * @return int
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * @param int $lote
     * @return Itbidadosimovel
     */
    public function setLote($lote)
    {
        $this->lote = $lote;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescrlograd()
    {
        return $this->descrlograd;
    }

    /**
     * @param string $descrlograd
     * @return Itbidadosimovel
     */
    public function setDescrlograd($descrlograd)
    {
        $this->descrlograd = $descrlograd;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     * @return Itbidadosimovel
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompl()
    {
        return $this->compl;
    }

    /**
     * @param string $compl
     * @return Itbidadosimovel
     */
    public function setCompl($compl)
    {
        $this->compl = $compl;
        return $this;
    }

    /**
     * @return int
     */
    public function getMatricri()
    {
        return $this->matricri;
    }

    /**
     * @param int $matricri
     * @return Itbidadosimovel
     */
    public function setMatricri($matricri)
    {
        $this->matricri = $matricri;
        return $this;
    }

    /**
     * @return int
     */
    public function getSetorri()
    {
        return $this->setorri;
    }

    /**
     * @param int $setorri
     * @return Itbidadosimovel
     */
    public function setSetorri($setorri)
    {
        $this->setorri = $setorri;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuadrari()
    {
        return $this->quadrari;
    }

    /**
     * @param int $quadrari
     * @return Itbidadosimovel
     */
    public function setQuadrari($quadrari)
    {
        $this->quadrari = $quadrari;
        return $this;
    }

    /**
     * @return int
     */
    public function getLoteri()
    {
        return $this->loteri;
    }

    /**
     * @param int $loteri
     * @return Itbidadosimovel
     */
    public function setLoteri($loteri)
    {
        $this->loteri = $loteri;
        return $this;
    }
}

<?php

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;
use Lote;
use cl_iptuant;

final class Iptubase extends Model
{
    private $matric;

    private $numcgm;

    private $idbql;

    private $baixa;

    private $codave;

    private $fracao;

    /**
     * @var string
     */
    private $iptuAnterior;

    /**
     * Referência de lote com o campo j34_idbql
     *
     * @var Lote
     */
    private $lote;

    public function setMatric($matric)
    {
        $this->matric = $matric;
    }

    public function getMatric()
    {
        return $this->matric;
    }

    public function setNumcgm($numcgm)
    {
        $this->numcgm = $numcgm;
    }

    public function getNumcgm()
    {
        return $this->numcgm;
    }

    public function setIdbql($idbql)
    {
        $this->idbql = $idbql;
    }

    public function getIdbql()
    {
        return $this->idbql;
    }

    public function setBaixa($baixa)
    {
        $this->baixa = $baixa;
    }

    public function getBaixa()
    {
        return $this->baixa;
    }

    public function setCodave($codave)
    {
        $this->codave = $codave;
    }

    public function getCodave()
    {
        return $this->codave;
    }

    public function setFracao($fracao)
    {
        $this->fracao = $fracao;
    }

    public function getFracao()
    {
        return $this->fracao;
    }

    public function withLote()
    {
        if ($this->lote === null) {
            $this->lote = new Lote($this->getIdbql());
        }

        return $this;
    }

    /**
     * @return Lote
     */
    public function getLote()
    {
        return $this->lote;
    }

    public function withIptuAnterior()
    {
        if ($this->iptuAnterior === null) {
            $dao = new cl_iptuant;
            $rs = db_query($dao->sql_query_file($this->getMatric()));

            $this->iptuAnterior = pg_fetch_result($rs, 0, 'j40_refant');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getIptuAnterior()
    {
        return $this->iptuAnterior;
    }
}

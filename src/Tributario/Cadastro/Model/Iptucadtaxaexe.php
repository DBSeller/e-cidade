<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Iptucadtaxaexe extends Model 
{
    private $iptucadtaxaexe;

    private $iptucadtaxa;

    private $tabrec;

    private $valor;

    private $aliq;

    private $anousu;

    private $iptucalh;

    private $db_sysfuncoes;

    private $histisen;

    public function setIptucadtaxaexe($iptucadtaxaexe)
    {
        $this->iptucadtaxaexe = $iptucadtaxaexe;
    }

    public function setIptucadtaxa($iptucadtaxa)
    {
        $this->iptucadtaxa = $iptucadtaxa;
    }

    public function setTabrec($tabrec)
    {
        $this->tabrec = $tabrec;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function setAliq($aliq)
    {
        $this->aliq = $aliq;
    }

    public function setAnousu($anousu)
    {
        $this->anousu = $anousu;
    }

    public function setIptucalh($iptucalh)
    {
        $this->iptucalh = $iptucalh;
    }

    public function setDbsysfuncoes($db_sysfuncoes)
    {
        $this->db_sysfuncoes = $db_sysfuncoes;
    }

    public function setHistisen($histisen)
    {
        $this->histisen = $histisen;
    }

    public function getIptucadtaxaexe()
    {
        return $this->iptucadtaxaexe;
    }

    public function getIptucadtaxa()
    {
        return $this->iptucadtaxa;
    }

    public function getTabrec()
    {
        return $this->tabrec;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function getAliq()
    {
        return $this->aliq;
    }

    public function getAnousu()
    {
        return $this->anousu;
    }

    public function getIptucalh()
    {
        return $this->iptucalh;
    }

    public function getDbsysfuncoes()
    {
        return $this->db_sysfuncoes;
    }

    public function getHistisen()
    {
        return $this->histisen;
    }
}

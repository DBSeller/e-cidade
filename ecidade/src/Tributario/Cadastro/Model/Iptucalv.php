<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Iptucalv extends Model 
{
    private $anousu;

    private $matric;

    private $receit;

    private $valor;

    private $quant;

    private $codhis;

    public function setAnousu($anousu)
    {
        $this->anousu = $anousu;
    }

    public function setMatric($matric)
    {
        $this->matric = $matric;
    }

    public function setReceit($receit)
    {
        $this->receit = $receit;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    } 

    public function setQuant($quant)
    {
        $this->quant = $quant;
    } 

    public function setCodhis($codhis)
    {
        $this->codhis = $codhis;
    }

    public function getAnousu()
    {
        return $this->anousu;
    }

    public function getMatric()
    {
        return $this->matric;
    }

    public function getReceit()
    {
        return $this->receit;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function getQuant()
    {
        return $this->quant;
    }

    public function getCodhis()
    {
        return $this->codhis;
    }
}

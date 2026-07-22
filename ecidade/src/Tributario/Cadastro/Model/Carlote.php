<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Carlote extends Model 
{
    private $idbql;

    private $caract;

    private $dtlanc;

    public function setIdbql($idbql)
    {
        $this->idbql = $idbql;
    } 

    public function setCaract($caract)
    {
        $this->caract = $caract;
    }

    public function setDtlanc(\DateTime $dtlanc)
    {
        $this->dtlanc = $dtlanc;
    }

    public function getIdbql()
    {
        return $this->idbql;
    }

    public function getCaract()
    {
        return $this->caract;
    }

    public function getDtlanc()
    {
        return $this->dtlanc;
    }
}

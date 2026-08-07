<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Carfator extends Model 
{
    private $anousu;

    private $caract;

    private $fator;

    private $corrig;

    public function setAnousu($anousu)
    {
        $this->anousu = $anousu;
    }

    public function setCaract($caract)
    {
        $this->caract = $caract;
    }

    public function setFator($fator)
    {
        $this->fator = $fator;
    }

    public function setCorrig($corrig)
    {
        $this->corrig = $corrig;
    }

    public function getAnousu()
    {
        return $this->anousu;
    }

    public function getCaract()
    {
        return $this->caract;
    }

    public function getFator()
    {
        return $this->fator;
    }

    public function getCorrig()
    {
        return $this->corrig;
    }
}

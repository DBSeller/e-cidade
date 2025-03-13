<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Carconstr extends Model 
{
    private $matric;

    private $idcons;

    private $caract;

    public function setMatric($matric)
    {
        $this->matric = $matric;
    }

    public function setIdcons($idcons)
    {
        $this->idcons = $idcons;
    }

    public function setCaract($caract)
    {
        $this->caract = $caract;
    }

    public function getmatric()
    {
        return $this->matric;
    }

    public function getidcons()
    {
        return $this->idcons;
    }

    public function getcaract()
    {
        return $this->caract;
    }
}

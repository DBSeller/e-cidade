<?php 

namespace ECidade\Tributario\Cadastro\Entity;

use ECidade\Tributario\Library\Entity;

final class Matricula extends Entity
{
    private $matricula;

    private $cgm;

    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
    }

    public function getMatricula()
    {
        return $this->matricula;
    }

    public function getCgm()
    {
        return $this->cgm;
    }
}

<?php 

namespace ECidade\Tributario\Caixa\Entity;

use ECidade\Tributario\Library\Entity;
use ECidade\Tributario\Caixa\Entity\Parcela;
use ECidade\Tributario\Caixa\Entity\Collection\ParcelaCollection;

final class Debito extends Entity
{
    private $numpre;

    private $tipo;

    private $parcelas;

    public function __construct()
    {
        $this->parcelas = new ParcelaCollection();
    }

    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function addParcela(Parcela $parcela)
    {
        $this->parcelas->add($parcela);
    }

    public function getNumpre()
    {
        return $this->numpre;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getParcelas()
    {
        return $this->parcelas;
    }
}

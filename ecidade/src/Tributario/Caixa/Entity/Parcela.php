<?php 

namespace ECidade\Tributario\Caixa\Entity;

use \DateTime;
use ECidade\Tributario\Library\Entity;
use ECidade\Tributario\Caixa\Entity\Receita;
use ECidade\Tributario\Caixa\Entity\Collection\ReceitaCollection;

final class Parcela extends Entity
{
    private $numero;

    private $vencimento;

    private $receitas;

    public function __construct()
    {
        $this->receitas = new ReceitaCollection();
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function setVencimento(DateTime $vencimento)
    {
        $this->vencimento = $vencimento;
    }

    public function addReceita(Receita $receita)
    {
        $this->receitas->add($receita);
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function getVencimento()
    {
        return $this->vencimento;
    }

    public function getReceitas()
    {
        return $this->receitas;
    }
}

<?php 

namespace ECidade\Tributario\Caixa\Entity\Strategy;

use ECidade\Tributario\Library\StrategyCalculator;
use ECidade\Tributario\Library\Entity;
use ECidade\Tributario\Caixa\Entity\Recibo;

final class ReciboValorTotal implements StrategyCalculator
{
    public function calculate(Entity $recibo)
    {
        $valorTotal = 0;

        foreach ($recibo->getDebitos() as $debito) {
            foreach ($debito->getParcelas() as $parcela) {
                foreach ($parcela->getReceitas() as $receita) {
                    $valorTotal += $receita->getValor();
                }
            }
        }

        return $valorTotal;
    }
}

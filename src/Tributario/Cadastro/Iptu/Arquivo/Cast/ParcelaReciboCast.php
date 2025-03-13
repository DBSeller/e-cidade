<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Cast;

use ECidade\Tributario\Library\Cast;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ParcelaRecibo;
use ECidade\Tributario\Caixa\Entity\Collection\ReciboCollection;
use ECidade\Tributario\Caixa\Entity\Strategy\ReciboValorTotal;
use ECidade\Tributario\Library\ArrayCollection;

final class ParcelaReciboCast extends Cast
{
    private $reciboValorTotalStrategy;

    public function __construct(ReciboValorTotal $reciboValorTotal)
    {
        $this->reciboValorTotalStrategy = $reciboValorTotal;
    }

    public function arrayFromReciboCollection(ReciboCollection $reciboCollection)
    {
        $array = array();

        foreach ($reciboCollection as $i => $recibo) {
            
            $parcelaRecibo = new ParcelaRecibo();
            
            $valorTotal = $this->reciboValorTotalStrategy->calculate($recibo);

            if($recibo->getVencimento() instanceof \DateTime) {
                $parcelaRecibo->setVencimento($recibo->getVencimento());
            }
            $parcelaRecibo->setValor($valorTotal);
            $parcelaRecibo->setCodigoArrecadacao($recibo->getNumpre());
            $parcelaRecibo->setCodigoBarras($recibo->getLinhaDigitavel() . "," . $recibo->getCodigoBarras());
            $parcelaRecibo->setNumero($i+1);

            $array[] = $parcelaRecibo;
        }
        
        return $array;
    }
}

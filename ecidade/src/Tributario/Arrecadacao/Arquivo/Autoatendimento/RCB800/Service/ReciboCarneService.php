<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service;

use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Caixa\Entity\Debito;
use ECidade\Tributario\Caixa\Entity\Collection\ReciboCollection;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\ReciboService;

final class ReciboCarneService extends Service
{
    private $reciboService;

    public function __construct(ReciboService $reciboService)
    {
        $this->reciboService = $reciboService;
    }

    public function execute(Debito $debito, $datavigfinal)
    {
        $carnes = array();
        $recibos = new ReciboCollection();

        $parcelas = $debito->getParcelas();

        foreach ($parcelas as $parcela) {
            $debitoRecibo = new Debito();
            $debitoRecibo->setTipo($debito->getTipo());
            $debitoRecibo->setNumpre($debito->getNumpre());
            $debitoRecibo->addParcela($parcela);

            $recibo = $this->reciboService->execute($debitoRecibo, $datavigfinal);

            $recibos->add($recibo);
        }

        return $recibos;
    }
}

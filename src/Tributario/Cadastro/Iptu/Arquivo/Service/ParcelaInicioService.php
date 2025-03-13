<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Service;

use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ParcelaInicio;

final class ParcelaInicioService extends Service
{
    public function execute($parcelaRecibos)
    {
        $totalParcelas              = count($parcelaRecibos);
        $percentualMesJuroAtraso    = 0;
        $percentualGeralMultaAtraso = 0;

        $parcelaInicio = new ParcelaInicio;
        $parcelaInicio->setTotalParcelas($totalParcelas);
        $parcelaInicio->setExpresaoParcelados('PARCELADOS');
        $parcelaInicio->setPercentualMesJuroAtraso($percentualMesJuroAtraso);
        $parcelaInicio->setPercentualGeralMultaAtraso($percentualGeralMultaAtraso);

        return $parcelaInicio;
    }
}

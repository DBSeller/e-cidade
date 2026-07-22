<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Service;

use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Cadastro\Model\Cfiptu;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ParcelaRecibo;

final class ReciboParcelaService extends Service
{
    public function build($cfiptu, $recibos)
    {
        $parcelasRecibo = array();

        foreach ($recibos as $recibo) {

            $valor = 0;
            $numero = 0;

            foreach ($recibo->getDebitos() as $debito) {
                foreach ($debito->getParcelas() as $parcela) {

                    $numero = $parcela->getNumero();

                    foreach ($parcela->getReceitas() as $receita) {
                        $valor += $receita->getValor();
                    }
                }

            }

            $parcelaRecibo = new ParcelaRecibo();

            $parcelaRecibo->setVencimento($recibo->getVencimento());
            $parcelaRecibo->setValor($valor);
            $parcelaRecibo->setJuros($valor * $cfiptu->k02_juros);
            $parcelaRecibo->setMulta($valor * $cfiptu->k140_faixa);
            $parcelaRecibo->setCodigoArrecadacao($recibo->getNumpre());
            $parcelaRecibo->setCodigoBarras();
            $parcelaRecibo->setNumero($numero);

            $parcelasRecibo[] = $parcelaRecibo;
        }

        return $parcelasRecibo;
    }
}

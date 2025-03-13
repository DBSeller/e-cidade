<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Service;

use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Caixa\Service\ReciboService;
use ECidade\Tributario\Caixa\Entity\Debito;
use ECidade\Tributario\Caixa\Entity\Recibo;
use ECidade\Tributario\Caixa\Entity\Collection\DebitoCollection;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ParcelaRecibo;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Filtro;
use ECidade\Tributario\Caixa\Enum;
use ECidade\Tributario\Caixa\Entity\Collection\ReciboCollection;

final class ReciboCarneService extends Service
{
    private $reciboService;

    public function __construct(ReciboService $reciboService)
    {
        $this->reciboService = $reciboService;
    }

    public function execute(Filtro $filtro, DebitoCollection $debitoCollection)
    {
        $carnes = array();
        $recibos = new ReciboCollection();

        foreach ($debitoCollection as $debito) {
            foreach ($debito->getParcelas() as $parcela) {
                
                $carnes[$parcela->getNumero()][] = array(
                    'numpre' => $debito->getNumpre(),
                    'tipo' => $debito->getTipo(),
                    'parcela' => $parcela
                );
            }
        }
        
        ksort($carnes);

        foreach ($carnes as $carne) {

            $recibo = new Recibo();

            foreach ($carne as $array) {

                $debito = new Debito();
                $debito->setTipo($array['tipo']);
                $debito->setNumpre($array['numpre']);

                $parcela = $array['parcela'];

                $vencimento = $parcela->getVencimento();
                $debito->addParcela($parcela);

                $recibo->addDebito($debito);
            }

            $recibo->setOrigem(Enum\Cadtipomod::EMISSAO_GERAL_DE_IPTU);
            $recibo->setTerceiroDigito($filtro->getTerceiroDigitoParcela());
            $recibo->setVencimento($vencimento);
            $recibo->setTipo(5);
            
            $recibo = $this->reciboService->execute($recibo);

            $recibos->add($recibo);
        }

        return $recibos;
    }
}

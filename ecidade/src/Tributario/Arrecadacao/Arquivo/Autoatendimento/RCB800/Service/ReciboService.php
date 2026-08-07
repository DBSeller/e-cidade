<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service;

use \DateTime;
use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Caixa\Service\ReciboService as CaixaReciboService;
use ECidade\Tributario\Caixa\Repository\ArretipoRepository;
use ECidade\Tributario\Caixa\Entity\Debito;
use ECidade\Tributario\Caixa\Entity\Recibo;
use ECidade\Tributario\Caixa\Enum;
use ECidade\Tributario\Caixa\Entity\Collection\ReciboCollection;

final class ReciboService extends Service
{
    private $reciboService;

    public function __construct(CaixaReciboService $reciboService, ArretipoRepository $arretipoRepository)
    {
        $this->reciboService = $reciboService;
        $this->arretipoRepository = $arretipoRepository;
    }

    public function execute(Debito $debito, $datavigfinal)
    {
        $arretipo = $this->arretipoRepository->find($debito->getTipo());
        $primeiraParcela = $debito->getParcelas()->getByIndex(0);
        $recibo = new Recibo();
        $recibo->addDebito($debito);
        $recibo->setOrigem(Enum\Cadtipomod::AUTOATENDIMENTO);
        $recibo->setTerceiroDigito($arretipo->getTercdigrecnormal());

        /* VALIDA VENCIMENTO ACIMA DO DIA DA PARCELA */
        if ($datavigfinal > $primeiraParcela->getVencimento()->format('Y-m-d')) {
            $dataVenc = new DateTime($datavigfinal);
        } else {
            $dataVenc = $primeiraParcela->getVencimento();
        }

        $recibo->setVencimento($dataVenc);
        $recibo->setTipo(5);
        
        $recibo = $this->reciboService->execute($recibo);

        return $recibo;
    }
}

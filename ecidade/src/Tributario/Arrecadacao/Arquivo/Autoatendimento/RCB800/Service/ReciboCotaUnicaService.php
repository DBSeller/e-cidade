<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service;

use \DateTime;
use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\ReciboService;
use ECidade\Tributario\Caixa\Entity\Debito;
use ECidade\Tributario\Caixa\Entity\Recibo;
use ECidade\Tributario\Caixa\Entity\Parcela;
use ECidade\Tributario\Caixa\Repository\RecibounicaRepository;
use ECidade\Tributario\Caixa\Enum;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Unica;

final class ReciboCotaUnicaService extends Service
{
    private $recibounicaRepository;

    private $reciboService;

    public function __construct(RecibounicaRepository $recibounicaRepository, ReciboService $reciboService)
    {
        $this->recibounicaRepository = $recibounicaRepository;
        $this->reciboService = $reciboService;
    }

    public function execute(Debito $debito, $datalista, $datavigfinal)
    {
        $recibos = array();

        $where = "k00_numpre = {$debito->getNumpre()} AND k00_dtvenc > '{$datalista}' ";
        $unicas = $this->recibounicaRepository->findAll($where);

        if ($unicas->isEmpty()) {
            return $recibos;
        }

        foreach ($unicas as $unica) {
            $debitoCotaUnica = new Debito();
            $debitoCotaUnica->setTipo($debito->getTipo());
            $debitoCotaUnica->setNumpre($debito->getNumpre());

            $parcela = new Parcela();
            $parcela->setNumero('0');
            /* VALIDA VENCIMENTO ACIMA DO DIA DA PARCELA */
            if ($datavigfinal > $unica->getDtvenc()->format('Y-m-d')) {
                $dataVenc = new DateTime($datavigfinal);
            } else {
                $dataVenc = $unica->getDtvenc()->format('Y-m-d');
            }

            $parcela->setVencimento($dataVenc);
            $debitoCotaUnica->addParcela($parcela);
            $recibos[] = $this->reciboService->execute($debitoCotaUnica, $datavigfinal);
        }

        return $recibos;
    }
}

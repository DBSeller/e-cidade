<?php

namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Custas\Interfaces\CalculaParcelamentoHonorario;
use ECidade\Tributario\Arrecadacao\Custas\Interfaces\ParcelamentoHonorario;

use ECidade\Tributario\Juridico\Inicial\Inicial;
use ECidade\Tributario\Juridico\InicialPartilha\InicialPartilhaCustas;

use ECidade\Tributario\Juridico\ProcessoForo\ProcessoForo;
use ECidade\Tributario\Juridico\ProcessoForoPartilha\ProcessoForoPartilhaCusta;

use ECidade\Tributario\Juridico\InicialPartilha\Repository\InicialPartilha as InicialPartilhaRepository;
use ECidade\Tributario\Juridico\Inicial\Repository\Inicial as InicialRepository;

use ECidade\Tributario\Juridico\ProcessoForo\Repository\ProcessoForo as ProcessoForoRepository;
use ECidade\Tributario\Juridico\ProcessoForoPartilha\Repository\ProcessoForoPartilha as ProcessoForoPartilhaRepository;

use \Recibo as ReciboModel;

class CalculaParcelamentoHonorarioRepository
{
    /**
     * @var Inicial
     * @var ProcessoForo
     */
    private $model;

    /**
     * @var ProcessoForoPartilha
     * @var InicialPartilha
     */
    private $repository;

    /**
     * @var ReciboModel
     */
    private $recibo;

    public function __construct(
        ParcelamentoHonorario $model,
        CalculaParcelamentoHonorario $repository,
        ReciboModel $recibo
    ) {
        $this->model      = $model;
        $this->repository = $repository;
        $this->recibo     = $recibo;
    }

    /**
     * Calcula o valor total das custas de honorários de acordo com as parcelas selcionadas para a emissão do recibo
     * @param ProcessoForoPartilhaCusta $custa
     * @param InicialPartilhaCustas $custa
     * @return float
     * @throws \DBException
     */
    public function calculaValorHonorario($custa, $codigo, $isProcessoForo)
    {
        $valorParcela = null;
        $valorCusta = $custa->getValor();

        if ($custa->getTaxa()->isAplicaHonorario()
            && $this->model->getParcelasHonorarios() > 1) {
            $valorParcela = 0;
            $numpres = $this->recibo->getDebitosRecibo();

            foreach ($numpres as $numpre) {
                $valorParcela += $this->calculaValorTaxaParcela(
                    $valorCusta,
                    $numpre->k00_numpar,
                    $codigo,
                    $isProcessoForo,
                    $custa->getTaxa()
                );
            }
        } else {
            $valorCusta = $this->verificaValoresPagos(
                $valorCusta,
                $codigo,
                $isProcessoForo,
                $custa->getTaxa()
            );
        }

        if (!is_null($valorParcela)) {
            $valorCusta = $valorParcela;
        }

        return round($valorCusta, 2);
    }

    /**
     * Calcula o valor da parcela das custas de honorários
     * @param $valorCusta
     * @param $parcelaRecibo
     * @return float
     */
    public function calculaValorTaxaParcela($valorCusta, $parcelaRecibo, $codigo, $isProcessoForo, $taxa)
    {
        $valorParcela = 0;
        $valorCusta = $this->verificaValoresPagos($valorCusta, $codigo, $isProcessoForo, $taxa);

        if ($parcelaRecibo < $this->model->getParcelasHonorarios()) {
            $valorParcela += $valorCusta / $this->model->getParcelasHonorarios();
        }

        if ($parcelaRecibo == $this->model->getParcelasHonorarios()) {
            for ($parcela = 1; $parcela <= $this->model->getParcelasHonorarios(); $parcela++) {
                if ($parcela == $this->model->getParcelasHonorarios()) {
                    $valorParcela = $valorCusta - $valorParcela;
                    break;
                }

                $valorParcela += round($valorCusta / $this->model->getParcelasHonorarios(), 2);
            }
        }

        return round($valorParcela, 2);
    }

    /**
     * Verificar se existe valores pagos para a taxa de honorário e desconta
     * @param $valorCusta
     * @param $codigoInicial/$codigoProcessoForo
     * @param bool
     * @param Taxa
     * @return integer
     */
    public function verificaValoresPagos($valorCusta, $codigo, $isProcessoForo, $taxa)
    {
        if ($isProcessoForo) {
            $processoForoRepository = ProcessoForoRepository::getInstance();
            $modelValoresPagos = $processoForoRepository->getByCodigo($codigo);

            $repositoryValoresPagos = ProcessoForoPartilhaRepository::getInstance();
        } else {
            $inicialRepository = InicialRepository::getInstance();
            $modelValoresPagos = $inicialRepository->getByCode($codigo);

            $repositoryValoresPagos = InicialPartilhaRepository::getInstance();
        }

        $valorPago = $repositoryValoresPagos->getValorPago($taxa, $modelValoresPagos);
        $valorCusta -= $valorPago;

        if ($valorCusta < 0) {
            $valorCusta = 0;
        }

        return $valorCusta;
    }
}

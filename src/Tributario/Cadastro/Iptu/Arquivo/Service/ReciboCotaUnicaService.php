<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Service;

use \DateTime;
use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Cadastro\Entity\Matricula;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Filtro;
use ECidade\Tributario\Caixa\Service\ReciboService;
use ECidade\Tributario\Caixa\Entity\Collection\DebitoCollection;
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

    public function execute(Filtro $filtro, DebitoCollection $debitoCollection)
    {
        /**
         * @todo - Sera refatorado. Codigo estruturado e procedural. Sera quebrado em unicades. A regra de unica é de pluralidade.
         */
        $grupo = array();
        $grupoDate = array();
        $grupoPorcentagem = array();
        $grupoOper = array();
        $numpreTipo = array();
        $recibos = array();
        $unicas = array();

        foreach ($filtro->getCotaUnicas() as $filtroUnica) {
            foreach ($debitoCollection as $debito) {
                
                $where = "k00_numpre = {$debito->getNumpre()} and k00_dtvenc = '{$filtroUnica->getData()->format('Y-m-d')}' and k00_percdes = {$filtroUnica->getPorcentagem()}";

                $unica = $this->recibounicaRepository->findAll($where);

                if ($unica->isEmpty()) {
                    continue;
                }

                $unica = $unica->current();

                $grupo[$filtroUnica->getData()->format('Ymd')][$debito->getNumpre()] = $debito->getTipo();
                $grupoDate[$filtroUnica->getData()->format('Ymd')] = $filtroUnica->getData();
                $grupoPorcentagem[$filtroUnica->getData()->format('Ymd')] = $filtroUnica->getPorcentagem();
                $grupoOper[$filtroUnica->getData()->format('Ymd')] = $unica->getDtoper();
            }
        }

        foreach ($grupo as $data => $numpres) {

            $recibo = new Recibo();

            foreach ($numpres as $numpre => $tipo) {

                $debitoCotaUnica = new Debito();

                $debitoCotaUnica->setTipo($tipo);
                $debitoCotaUnica->setNumpre($numpre);

                $vencimento = $grupoDate[$data];

                $parcela = new Parcela();
                $parcela->setNumero('0');
                $parcela->setVencimento($vencimento);

                $debitoCotaUnica->addParcela($parcela);

                $recibo->addDebito($debitoCotaUnica);
            }

            $recibo->setOrigem(Enum\Cadtipomod::EMISSAO_GERAL_DE_IPTU);
            $recibo->setTerceiroDigito($filtro->getTerceiroDigitoUnica());

            $recibo->setVencimento($vencimento);
            $recibo->setTipo(5);

            $recibo = $this->reciboService->execute($recibo);

            $recibos[$data] = $recibo;
        }

        foreach ($grupo as $i => $value) {

            $unica = new Unica();

            $valorHist = 0;
            $dataOperacao = null;
            $dataVencimento = null;
            $porcentagem = null;
            $numpre = null;
            $codigoBarras = null;

            $reciboUnica = $recibos[$i];

            if (!empty($reciboUnica)) {

                foreach ($reciboUnica->getDebitos() as $debito) {
                    foreach ($debito->getParcelas() as $parcela) {
                        foreach ($parcela->getReceitas() as $receita) {

                            $valorTotal += $receita->getValor();

                            if ($receita->getValor() > 0) {
                                $valorHist += $receita->getValor();
                            }
                        }
                    }
                }

                $dataOperacao = $grupoOper[$i];
                $dataVencimento = $reciboUnica->getVencimento();
                $porcentagem = $grupoPorcentagem[$i];
                $numpre = $reciboUnica->getNumpre();
                $codigoBarras = $reciboUnica->getLinhaDigitavel().','.$reciboUnica->getCodigoBarras();
            }

            $unica->setDataOperacao($dataOperacao);
            $unica->setDataVencimento($dataVencimento);
            $unica->setPorcentagem($porcentagem);
            $unica->setValorHistorico($valorHist);
            $unica->setValorCorrigido(0); // nao vao usar
            $unica->setJuros(0); // nao vao usar
            $unica->setMulta(0); // nao vao usar
            $unica->setDesconto(0); // nao vao usar
            $unica->setTotal(0); // nao vao usar
            $unica->setTotalDesconto($valorTotal); // total menos desconto
            $unica->setNumpre($numpre);
            $unica->setCodigoBarra($codigoBarras);

            $unicas[] = $unica;
        }

        return $unicas;
    }
}

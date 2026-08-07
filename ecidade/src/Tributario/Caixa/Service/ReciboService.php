<?php

namespace ECidade\Tributario\Caixa\Service;

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Service\CobrancaRegistradaService;
use ECidade\Tributario\Caixa\Model\Dbreciboweb;
use ECidade\Tributario\Caixa\Model\Recibopagaboleto;
use ECidade\Tributario\Caixa\Entity\Recibo;
use ECidade\Tributario\Caixa\Repository\Sequence\NumpreSequenceRepository;
use ECidade\Tributario\Caixa\Repository\DbrecibowebRepository;
use ECidade\Tributario\Caixa\Repository\RecibopagaRepository;
use ECidade\Tributario\Caixa\Repository\RecibopagaboletoRepository;
use ECidade\Tributario\Caixa\Service\Procedure\ReciboProcedure;
use ECidade\Tributario\Caixa\Service\ConvenioService;
use ECidade\Tributario\Caixa\Service\RegraEmissaoService;
use ECidade\Tributario\Caixa\Service\ReciboFillService;
use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Library\Session;

final class ReciboService extends Service
{
    private $session;

    private $reciboProcedure;

    private $regraEmissaoService;

    private $convenioService;

    private $numpreSequenceRepository;

    private $dbrecibowebRepository;

    private $recibopagaboletoRepository;

    private $reciboFillService;

    private $cobrancaRegistradaService;

    public function __construct(
        Session $session,
        ReciboProcedure $reciboProcedure,
        RegraEmissaoService $regraEmissaoService,
        ConvenioService $convenioService,
        NumpreSequenceRepository $numpreSequenceRepository,
        DbrecibowebRepository $dbrecibowebRepository,
        RecibopagaboletoRepository $recibopagaboletoRepository,
        ReciboFillService $reciboFillService,
        CobrancaRegistradaService $cobrancaRegistradaService
    ) {
        $this->session = $session;
        $this->reciboProcedure = $reciboProcedure;
        $this->regraEmissaoService = $regraEmissaoService;
        $this->convenioService = $convenioService;
        $this->numpreSequenceRepository = $numpreSequenceRepository;
        $this->dbrecibowebRepository = $dbrecibowebRepository;
        $this->recibopagaboletoRepository = $recibopagaboletoRepository;
        $this->reciboFillService = $reciboFillService;
        $this->cobrancaRegistradaService = $cobrancaRegistradaService;
    }

    public function execute(Recibo $recibo)
    {
        $regraEmissao = $this->regraEmissaoService->execute($recibo);

        $numpre = $this->numpreSequenceRepository->next();

        $recibo->setNumpre($numpre);

        foreach ($recibo->getDebitos() as $debito) {
            $dbreciboweb = new Dbreciboweb();

            $dbreciboweb->setNumpren($numpre);
            $dbreciboweb->setNumpre($debito->getNumpre());
            $dbreciboweb->setCodbco($regraEmissao->getBanco());
            $dbreciboweb->setCodage($regraEmissao->getAgencia());
            $dbreciboweb->setNumbco($regraEmissao->getConvenioCobranca());
            $dbreciboweb->setTipo($recibo->getTipo());
            $dbreciboweb->setDesconto($recibo->getDesconto());
            $dbreciboweb->setOrigem(1);

            foreach ($debito->getParcelas() as $parcela) {
                $dbreciboweb->setNumpar($parcela->getNumero());

                $this->dbrecibowebRepository->insert($dbreciboweb);
            }
        }

        $this->reciboProcedure->execute($recibo);

        $recibopagaboleto = new Recibopagaboleto();

        $recibopagaboleto->setNumnov($numpre);
        $recibopagaboleto->setData($this->session->getData());
        $recibopagaboleto->setHora($this->session->getHora());
        $recibopagaboleto->setUsuario($this->session->getUsuarioId());

        $this->recibopagaboletoRepository->insert($recibopagaboleto);

        $recibo = $this->reciboFillService->execute($recibo);

        $recibo = $this->convenioService->execute($recibo, $regraEmissao);

        $this->cobrancaRegistradaService->execute($recibo, $regraEmissao);

        return $recibo;
    }
}

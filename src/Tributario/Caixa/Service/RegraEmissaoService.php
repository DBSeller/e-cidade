<?php

namespace ECidade\Tributario\Caixa\Service;

use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Library\Session;
use ECidade\Tributario\Caixa\Entity\Recibo;
use ECidade\Tributario\Caixa\Entity\RegraEmissao;
use ECidade\Tributario\Caixa\Repository\ArretipoRepository;
use \regraEmissao as RegraEmissaoLegacy;

final class RegraEmissaoService extends Service
{
    private $session;

    private $arretipoRepository;

    public function __construct(Session $session, ArretipoRepository $arretipoRepository)
    {
        $this->session = $session;
        $this->arretipoRepository = $arretipoRepository;
    }

    public function execute(Recibo $recibo)
    {
        $debitos = $recibo->getDebitos();

        $debito = $debitos[0];

        $tipo = $debito->getTipo();
        $origem = $recibo->getOrigem();
        $instituicao = $this->session->getInstituicao();
        $data = $this->session->getData()->format('Y-m-d');
        $ip = $this->session->getIp();
        
        $regraEmissaoLegacy = new RegraEmissaoLegacy($tipo, $origem, $instituicao, $data, $ip);

        $arretipo = $this->arretipoRepository->find($tipo);

        $regraEmissao = new RegraEmissao();
        
        $regraEmissao->setConvenio($regraEmissaoLegacy->getConvenio());
        $regraEmissao->setConvenioCobranca($regraEmissaoLegacy->getCodConvenioCobranca());
        $regraEmissao->setBanco($arretipo->getCodbco());
        $regraEmissao->setAgencia($arretipo->getCodage());
        $regraEmissao->setTerceiroDigito($arretipo->getTercdigrecnormal());

        return $regraEmissao;
    }
}

<?php

namespace ECidade\Configuracao\Workflow\Service;

use ECidade\Configuracao\Workflow\Collection\Acoes as AcoesCollection;
use ECidade\Configuracao\Workflow\Entity\Transicao as TransicaoEntity;
use ECidade\Configuracao\Workflow\Filter\Transicao as FiltroTransicao;
use ECidade\Configuracao\Workflow\Repository\Acao as AcaoRepository;
use ECidade\Tributario\Issqn\Acao\Transicao\Factory\AcaoFactory as AcoesFactory;

class Transicao
{
    private $acaoRepository;

    public function __construct(AcaoRepository $acaoRepository)
    {
        $this->acaoRepository = $acaoRepository;
    }

    public function run(FiltroTransicao $filtro)
    {
        $acoesCollection = new AcoesCollection();
        $acoes           = $this->acaoRepository->getAcoes($filtro);
        $processo        = $filtro->getProcesso();

        if (!empty($acoes)) {
            foreach ($acoes as $acao) {
                $entidadeAcao = AcoesFactory::factory($acao->db176_sequencial, $processo);
                $acoesCollection->add($entidadeAcao);
            }
        }

        $transicao = new TransicaoEntity();
        $transicao->setAcoes($acoesCollection);
        $transicao->run();

        return $transicao;
    }
}

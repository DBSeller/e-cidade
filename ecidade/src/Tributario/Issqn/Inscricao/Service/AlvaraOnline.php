<?php

namespace ECidade\Tributario\Issqn\Inscricao\Service;

use ECidade\Tributario\Library\Service as BaseService;
use ECidade\Tributario\Issqn\ParametrosProcessoEletronicoBag;
use ECidade\Patrimonial\Protocolo\Processo\AlvaraOnline\Parser\Factory\ParserAlvaraFactory;
use ECidade\Tributario\Issqn\Inscricao\Atividades\Collection\Atividades as CollectionAtividades;
use ECidade\Tributario\Issqn\Inscricao\Atividades\Repository\Atividades as RepositoryAtividades;
use ECidade\Tributario\Issqn\Inscricao\Atividades\Filter\ListagemAtividades as FiltroListagemAtividades;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Filter\ListagemProcessos as FiltroListagemProcessos;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Repository\ConsultaProcesso as RepositoryConsultaProcesso;

final class AlvaraOnline extends BaseService
{
    private $consultaProcessosRepository;
    private $atividadesRepository;
    private $collectionAtividades;

    public function __construct(
        RepositoryConsultaProcesso $consultaProcessosRepository,
        RepositoryAtividades $atividadesRepository,
        CollectionAtividades $collectionAtividades,
        ParametrosProcessoEletronicoBag $parameterBag
    ) {
        $this->consultaProcessosRepository = $consultaProcessosRepository;
        $this->atividadesRepository        = $atividadesRepository;
        $this->collectionAtividades        = $collectionAtividades;
        $this->parameterBag                = $parameterBag;
    }

    public function retornarProcessoAlvara(
        FiltroListagemProcessos $filtroProcessos,
        FiltroListagemAtividades $filtroAtividades
    ) {
        $objetoSolicitacao = $this->consultaProcessosRepository
                                  ->tipoConsulta(RepositoryConsultaProcesso::CONSULTA_OBJETO_SOLICITACAO)
                                  ->objetoSolicitacao($filtroProcessos);

        if (empty($objetoSolicitacao)) {
            return null;
        }

        $atividades = $this->atividadesRepository->listarAtividades($filtroAtividades);

        if (!empty($atividades)) {
            foreach ($atividades as $atividade) {
                $this->collectionAtividades->add($atividade->sequencial, $atividade);
            }
        }

        $parserSolicitacaoAlvara = ParserAlvaraFactory::getInstance($this->collectionAtividades)
                                                        ->create(
                                                            $filtroProcessos,
                                                            $this->parameterBag
                                                        );

        return $parserSolicitacaoAlvara->toJSON($objetoSolicitacao);
    }
}

<?php

namespace ECidade\Tributario\Issqn\Acao\Transicao\Factory;

use ECidade\Tributario\Issqn\Acao\Transicao\Entity\AlterarInscricao;
use ECidade\Tributario\Issqn\Acao\Transicao\Entity\GerarAlvara;
use ECidade\Tributario\Issqn\Acao\Transicao\Entity\GerarBoleto;
use ECidade\Tributario\Issqn\Acao\Transicao\Entity\GerarCalculo;
use ECidade\Tributario\Issqn\Acao\Transicao\Entity\GerarInscricao;
use ECidade\Tributario\Issqn\Inscricao\Atividades\Filter\ListagemAtividades as FiltroListagemAtividades;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Filter\ListagemProcessos as FiltroListagemProcessos;
use ECidade\V3\Extension\Registry;

final class AcaoFactory
{
    const
        ACAO_GERAR_INSCRICAO = 1,
        ACAO_GERAR_CALCULO   = 2,
        ACAO_GERAR_BOLETO    = 3,
        ACAO_GERAR_ALVARA    = 4,
        ACAO_ALTERAR_INSCRICAO = 5;

    /**
     * @param $tipoAcao
     * @param $processo
     * @return GerarBoleto|GerarCalculo|GerarInscricao
     */
    public static function factory($tipoAcao, $processo)
    {
        $container = Registry::get('app.container')->get('tributario.container');
        $containerPatrimonial  = Registry::get('app.container')->get('patrimonial.container');
        $issbaseRepository = $container->get('IssbaseRepository');

        switch ($tipoAcao) {
            case self::ACAO_GERAR_INSCRICAO:
                $processoEletronicoGrauRiscoRepository = $container->get('ProcessoEletronicoGrauRiscoRepository');
                $serviceProcessosAlvaraOnline = $container->get('Inscricao\Service\AlvaraOnline');
                $inclusaoCgmService           = $containerPatrimonial->get('Servicos\InclusaoCgmLegacy');
                $parameterBag                 = $container->get('ProcessoEletronicoParameterBag');
                $filtroProcesso = new FiltroListagemProcessos();
                $filtroAtividades = new FiltroListagemAtividades();

                return new GerarInscricao(
                    $processo,
                    $issbaseRepository,
                    $serviceProcessosAlvaraOnline,
                    $inclusaoCgmService,
                    $parameterBag,
                    $processoEletronicoGrauRiscoRepository,
                    $filtroProcesso,
                    $filtroAtividades
                );

                break;

            case self::ACAO_ALTERAR_INSCRICAO:
                $processoEletronicoGrauRiscoRepository = $container->get('ProcessoEletronicoGrauRiscoRepository');
                $serviceProcessosAlvaraOnline = $container->get('Inscricao\Service\AlvaraOnline');
                $inclusaoCgmService = $containerPatrimonial->get('Servicos\InclusaoCgmLegacy');
                $parameterBag = $container->get('ProcessoEletronicoParameterBag');
                $filtroProcesso = new FiltroListagemProcessos();
                $filtroAtividades = new FiltroListagemAtividades();

                return new AlterarInscricao(
                    $processo,
                    $issbaseRepository,
                    $serviceProcessosAlvaraOnline,
                    $inclusaoCgmService,
                    $parameterBag,
                    $processoEletronicoGrauRiscoRepository,
                    $filtroProcesso,
                    $filtroAtividades
                );

                break;

            case self::ACAO_GERAR_CALCULO:
                $calculoService = $container->get('Inscricao\Service\Calculo');

                return new GerarCalculo($processo, $issbaseRepository, $calculoService);
                break;

            case self::ACAO_GERAR_BOLETO:
                $reciboService = $container->get('ReciboService');
                $arretipoRepository = $container->get('ArretipoRepository');
                $inscricaoDebitoRepository = $container->get('InscricaoDebitoRepository');
                $reciboDocumentoService = $container->get('ReciboDocumentoService');

                return new GerarBoleto(
                    $processo,
                    $issbaseRepository,
                    $reciboService,
                    $arretipoRepository,
                    $inscricaoDebitoRepository,
                    $reciboDocumentoService
                );
                break;

            case self::ACAO_GERAR_ALVARA:
                $parameterBag                 = $container->get('ProcessoEletronicoParameterBag');

                return new GerarAlvara(
                    $processo,
                    $issbaseRepository,
                    $parameterBag
                );
                break;

            default:
                throw new Exception("Ação Inválida");
                break;
        }
    }
}

<?php

namespace ECidade\Tributario\Issqn;

use ECidade\Tributario\Library\Container as ContainerAbstract;

final class Container extends ContainerAbstract
{
    public function charge()
    {
        $this->content = array(
            'CadvencRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_cadvenc();

                return new \ECidade\Tributario\Issqn\Repository\CadvencRepository($dataBase, $dao);
            },
            'CadvencdescRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_cadvencdesc();

                return new \ECidade\Tributario\Issqn\Repository\ArrematricRepository($dataBase, $dao);
            },
            'IssbaseRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_issbase();

                return new \ECidade\Tributario\Issqn\Repository\IssbaseRepository($dataBase, $dao);
            },
            'Inscricao\Atividades\Repository\Atividades' => function ($container) {

                return new \ECidade\Tributario\Issqn\Inscricao\Atividades\Repository\Atividades(
                    $container->get('DataBase')
                );
            },
            'Inscricao\Atividades\Collection\Atividades' => function ($container) {

                return new \ECidade\Tributario\Issqn\Inscricao\Atividades\Collection\Atividades(
                    $container->get('DataBase')
                );
            },
            'Inscricao\Service\AlvaraOnline' => function ($container) {
                $containerPatrimonial = \ECidade\V3\Extension\Registry::get('app.container')
                    ->get('patrimonial.container');
                $repositoryConsultaProcessos = 'Processo\ProcessoEletronico\Repository\ConsultaProcessos';
                $repositoryConsultaProcesso  = $containerPatrimonial->get($repositoryConsultaProcessos);
                $repositoryAtividades        = $container->get('Inscricao\Atividades\Repository\Atividades');
                $collectionAtividades        = $container->get('Inscricao\Atividades\Collection\Atividades');
                $parameterBag                = $container->get('ProcessoEletronicoParameterBag');

                return new \ECidade\Tributario\Issqn\Inscricao\Service\AlvaraOnline(
                    $repositoryConsultaProcesso,
                    $repositoryAtividades,
                    $collectionAtividades,
                    $parameterBag
                );
            },
            'Inscricao\Service\Procedure\CalculoIssqn' => function ($container) {
                $database = $container->get('DataBase');

                return new \ECidade\Tributario\Issqn\Inscricao\Service\Procedure\CalculoIssqn(
                    $database
                );
            },
            'Inscricao\Service\Calculo' => function ($container) {
                $session = $container->get('Session');
                $calculoProcedure = $container->get('Inscricao\Service\Procedure\CalculoIssqn');

                return new \ECidade\Tributario\Issqn\Inscricao\Service\Calculo(
                    $session,
                    $calculoProcedure
                );
            },
            'InscricaoDebitoRepository' => function ($container) {
                $dataBase = $container->get("DataBase");
                $arrecadRepository = $container->get("Caixa\ArrecadRepository");
                $arrecadCollectionCast = $container->get("ArrecadCollectionCast");
                $arreinscrRepository = $container->get("ArreinscrRepository");
                $arretipoRepository = $container->get("ArretipoRepository");

                return new \ECidade\Tributario\Issqn\Repository\InscricaoDebitoRepository(
                    $dataBase,
                    $arrecadRepository,
                    $arrecadCollectionCast,
                    $arreinscrRepository,
                    $arretipoRepository
                );
            },
            'ProcessoEletronicoParameterBag' => function ($container) {
                $clparametroprocessoeletronico = new \cl_parametroprocessoeletronico();
                $entidade = new \ECidade\Tributario\Issqn\Model\ParametroProcessoEletronico();
                $repository = \ECidade\Tributario\Issqn\Repository\ParametroProcessoEletronicoRepository::getInstance(
                    $clparametroprocessoeletronico
                );

                return new \ECidade\Tributario\Issqn\ParametrosProcessoEletronicoBag($repository, $entidade);
            },
            'ProcessoEletronicoGrauRiscoRepository' => function ($container) {
                return \ECidade\Tributario\Issqn\Repository\ProcessoEletronicoGrauRiscoRepository::getInstance(
                    new \cl_processoeletronicograurisco()
                );
            },
            'IssvarRepository' => function ($container) {
                $database = $container->get('DataBase');
                $dao = new \cl_issvar();

                return new \ECidade\Tributario\Issqn\Repository\IssvarRepository(
                    $database,
                    $dao
                );
            }
        );
    }
}

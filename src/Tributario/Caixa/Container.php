<?php

namespace ECidade\Tributario\Caixa;

use ECidade\Tributario\Library\Container as ContainerAbstract;

final class Container extends ContainerAbstract
{
    public function charge()
    {
        $this->content = array(
            'ArrecadCollectionCast' => function ($container) {
                return new \ECidade\Tributario\Caixa\Cast\ArrecadCollectionCast();
            },
            'RecibopagaCollectionCast' => function ($container) {
                return new \ECidade\Tributario\Caixa\Cast\RecibopagaCollectionCast();
            },
            'DebitoRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $arrecadRepository = $container->get('Caixa\ArrecadRepository');
                $arrecadCollectionCast = $container->get('ArrecadCollectionCast');

                return new \ECidade\Tributario\Caixa\Entity\Repository\DebitoRepository(
                    $dataBase,
                    $arrecadRepository,
                    $arrecadCollectionCast
                );
            },
            'DebitoCollectionRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $arrecadRepository = $container->get('Caixa\ArrecadRepository');
                $arrecadCollectionCast = $container->get('ArrecadCollectionCast');

                return new \ECidade\Tributario\Caixa\Entity\Repository\DebitoCollectionRepository(
                    $dataBase,
                    $arrecadRepository,
                    $arrecadCollectionCast
                );
            },
            'ReciboValorTotal' => function ($container) {
                return new \ECidade\Tributario\Caixa\Entity\Strategy\ReciboValorTotal();
            },
            'NumpreSequenceRepository' => function ($container) {

                $dataBase = $container->get('DataBase');

                return new \ECidade\Tributario\Caixa\Repository\Sequence\NumpreSequenceRepository($dataBase);
            },
            'Caixa\ArrecadRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_arrecad();

                return new \ECidade\Tributario\Caixa\Repository\ArrecadRepository($dataBase, $dao);
            },
            'ArrematricRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_arrematric();

                return new \ECidade\Tributario\Caixa\Repository\ArrematricRepository($dataBase, $dao);
            },
            'ArreinscrRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_arreinscr();

                return new \ECidade\Tributario\Caixa\Repository\ArreinscrRepository($dataBase, $dao);
            },
            'ArrenumcgmRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_arrenumcgm();

                return new \ECidade\Tributario\Caixa\Repository\ArrenumcgmRepository($dataBase, $dao);
            },
            'ArretipoRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_arretipo();

                return new \ECidade\Tributario\Caixa\Repository\ArretipoRepository($dataBase, $dao);
            },
            'DbrecibowebRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_db_reciboweb();

                return new \ECidade\Tributario\Caixa\Repository\DbrecibowebRepository($dataBase, $dao);
            },
            'RecibocodbarRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_recibocodbar();

                return new \ECidade\Tributario\Caixa\Repository\RecibocodbarRepository($dataBase, $dao);
            },
            'RecibopagaRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_recibopaga();

                return new \ECidade\Tributario\Caixa\Repository\RecibopagaRepository($dataBase, $dao);
            },
            'RecibopagaboletoRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_recibopagaboleto();

                return new \ECidade\Tributario\Caixa\Repository\RecibopagaboletoRepository($dataBase, $dao);
            },
            'RecibounicaRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_recibounica();

                return new \ECidade\Tributario\Caixa\Repository\RecibounicaRepository($dataBase, $dao);
            },
            'ReciboProcedure' => function ($container) {

                $dataBase = $container->get('DataBase');
                $session = $container->get('Session');

                return new \ECidade\Tributario\Caixa\Service\Procedure\ReciboProcedure($dataBase, $session);
            },
            'ConvenioService' => function ($container) {

                $format = $container->get('Format');
                $reciboValorTotal = $container->get('ReciboValorTotal');

                return new \ECidade\Tributario\Caixa\Service\ConvenioService($format, $reciboValorTotal);
            },
            'ReciboFillService' => function ($container) {

                $recibopagaRepository = $container->get('RecibopagaRepository');
                $recibopagaCollectionCast = $container->get('RecibopagaCollectionCast');

                return new \ECidade\Tributario\Caixa\Service\ReciboFillService(
                    $recibopagaRepository,
                    $recibopagaCollectionCast
                );
            },
            'ReciboService' => function ($container) {

                $session = $container->get('Session');
                $reciboProcedure = $container->get('ReciboProcedure');
                $regraEmissaoService = $container->get('RegraEmissaoService');
                $convenioService = $container->get('ConvenioService');
                $numpreSequenceRepository = $container->get('NumpreSequenceRepository');
                $dbrecibowebRepository = $container->get('DbrecibowebRepository');
                $recibopagaboletoRepository = $container->get('RecibopagaboletoRepository');
                $reciboFillService = $container->get('ReciboFillService');
                $cobrancaRegistradaService = $container->get('CobrancaRegistradaService');

                return new \ECidade\Tributario\Caixa\Service\ReciboService(
                    $session,
                    $reciboProcedure,
                    $regraEmissaoService,
                    $convenioService,
                    $numpreSequenceRepository,
                    $dbrecibowebRepository,
                    $recibopagaboletoRepository,
                    $reciboFillService,
                    $cobrancaRegistradaService
                );
            },
            'RegraEmissaoService' => function ($container) {

                $session = $container->get('Session');
                $arretipoRepository = $container->get('ArretipoRepository');

                return new \ECidade\Tributario\Caixa\Service\RegraEmissaoService($session, $arretipoRepository);
            },
            'Model\ListaRepository' => function ($container) {
                $database = $container->get("DataBase");
                $listaDao = new \cl_lista();

                return new \ECidade\Tributario\Caixa\Repository\ListaRepository($database, $listaDao);
            },
            'ListaCast' => function ($container) {
                return new \ECidade\Tributario\Caixa\Cast\ListaCast();
            },
            'ListaRepository' => function ($container) {
                $listaRepository = $container->get("Model\ListaRepository");
                $listaCast = $container->get("ListaCast");

                return new \ECidade\Tributario\Caixa\Entity\Repository\ListaRepository($listaRepository, $listaCast);
            },
            'ListaDebitoRepository' => function ($container) {
                $dataBase = $container->get('DataBase');
                $arrecadRepository = $container->get('Caixa\ArrecadRepository');
                $arrecadCollectionCast = $container->get('ArrecadCollectionCast');

                return new \ECidade\Tributario\Caixa\Entity\Repository\ListaDebitoRepository(
                    $dataBase,
                    $arrecadRepository,
                    $arrecadCollectionCast
                );
            },
            'ListaDebitoService' => function ($container) {
                $listaRepository = $container->get('ListaRepository');
                $listaDebitoRepository = $container->get('ListaDebitoRepository');

                return new \ECidade\Tributario\Caixa\Service\ListaDebitoService(
                    $listaRepository,
                    $listaDebitoRepository
                );
            },
            'ReciboCast' => function ($container) {
                return new \ECidade\Tributario\Caixa\Cast\ReciboCast();
            },
            'ReciboDocumentoService' => function ($container) {
                $reciboCast = $container->get('ReciboCast');

                return new \ECidade\Tributario\Caixa\Service\ReciboDocumentoService($reciboCast);
            }

            // recibounica
            // arrecant
            // arrepaga
            // arreold
            // arrehist
            // recibo
        );
    }
}

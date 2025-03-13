<?php

namespace ECidade\Tributario\Divida;

use ECidade\Tributario\Library\Container as ContainerAbstract;

final class Container extends ContainerAbstract
{
    public function charge()
    {
        $this->content = array(
            'CertidaoRepository' => function ($container) {
                return \ECidade\Tributario\Divida\Certidao\Repository\Certidao::getInstance();
            },
            'CertidaoDividaRepository' => function ($container) {
                return \ECidade\Tributario\Divida\Certidao\Repository\CertidaoDivida::getInstance();
            },
            'CertidaoTermoRepository' => function ($container) {
                $dataBase = $container->get('DataBase');
                $dao = new \cl_certter();

                return new \ECidade\Tributario\Divida\Certidao\Repository\CertidaoTermo($dataBase, $dao);
            },
            'ACertidaoRepository' => function ($container) {
                $dataBase = $container->get('DataBase');
                $dao = new \cl_acertid();

                return new \ECidade\Tributario\Divida\Certidao\Repository\ACertidao($dataBase, $dao);
            },
            'ACertidaoTermoRepository' => function ($container) {
                $dataBase = $container->get('DataBase');
                $dao = new \cl_acertter();

                return new \ECidade\Tributario\Divida\Certidao\Repository\ACertidaoTermo($dataBase, $dao);
            },
            'ACertidaoDividaRepository' => function ($container) {
                $dataBase = $container->get('DataBase');
                $dao = new \cl_acertter();

                return new \ECidade\Tributario\Divida\Certidao\Repository\ACertidaoDivida($dataBase, $dao);
            },
            'ListaCDARepository' => function ($container) {
                $dataBase = $container->get('DataBase');
                $dao = new \cl_listacda();

                return new \ECidade\Tributario\Divida\Certidao\Repository\ListaCDA($dataBase, $dao);
            },
            'TermoRepository' => function ($container) {
                return \ECidade\Tributario\Divida\Termo\Repository\Termo::getInstance();
            },
            'TermoInicialRepository' => function ($container) {
                return \ECidade\Tributario\Divida\Termo\Repository\TermoInicial::getInstance();
            },
            'TermoDividaRepository' => function ($container) {
                $dataBase = $container->get('DataBase');
                $dao = new \cl_termodiv();

                return new \ECidade\Tributario\Divida\Termo\Repository\TermoDivida($dataBase, $dao);
            },
            'TermoReparcelamentoRepository' => function ($container) {
                $dataBase = $container->get('DataBase');
                $dao = new \cl_termoreparc();

                return new \ECidade\Tributario\Divida\Termo\Repository\TermoReparcelamento($dataBase, $dao);
            },
            'DividaRepository' => function ($container) {
                return  \ECidade\Tributario\Divida\Repository\Divida::getInstance();
            },
            'ParcelamentoForoParaDividaService' => function ($container) {

                $arreforoRepository = $container->get('ArreforoRepository');
                $arrecadRepository = $container->get('ArrecadRepository');
                $arreoldRepository = $container->get('ArreoldRepository');
                $certidaoRepository = $container->get('CertidaoRepository');
                $certidaoDividaRepository = $container->get('CertidaoDividaRepository');
                $certidaoTermoRepository = $container->get('CertidaoTermoRepository');
                $aCertidaoRepository = $container->get('ACertidaoRepository');
                $aCertidaoTermoRepository = $container->get('ACertidaoTermoRepository');
                $aCertidaoDividaRepository = $container->get('ACertidaoDividaRepository');
                $listaCDARepository = $container->get('ListaCDARepository');
                $termoRepository = $container->get('TermoRepository');
                $termoInicialRepository = $container->get('TermoInicialRepository');
                $termoDividaRepository = $container->get('TermoDividaRepository');
                $termoReparcelamentoRepository = $container->get('TermoReparcelamentoRepository');
                $inicialRepository = $container->get('InicialRepository');
                $inicialMovRepository = $container->get('InicialMovRepository');
                $inicialCertRepository = $container->get('InicialCertRepository');
                $inicialNumpreRepository = $container->get('InicialNumpreRepository');
                $dividaRepository = $container->get('DividaRepository');

                return new \ECidade\Tributario\Divida\Termo\Services\ParcelamentoForoParaDivida(
                    $arreforoRepository,
                    $arrecadRepository,
                    $arreoldRepository,
                    $certidaoRepository,
                    $certidaoDividaRepository,
                    $certidaoTermoRepository,
                    $aCertidaoRepository,
                    $aCertidaoTermoRepository,
                    $aCertidaoDividaRepository,
                    $listaCDARepository,
                    $termoRepository,
                    $termoInicialRepository,
                    $termoDividaRepository,
                    $termoReparcelamentoRepository,
                    $inicialRepository,
                    $inicialMovRepository,
                    $inicialCertRepository,
                    $inicialNumpreRepository,
                    $dividaRepository
                );
            },
        );
    }
}

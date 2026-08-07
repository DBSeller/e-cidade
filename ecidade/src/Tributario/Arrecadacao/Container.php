<?php

namespace ECidade\Tributario\Arrecadacao;

use ECidade\Tributario\Library\Container as ContainerAbstract;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Repository\TipoDebito as TipoDebitoRepository;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80R\Service\ArquivoTxtService as RCB80RArquivoTxtService;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\HeaderService as RCB800HeaderService;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\ReciboCotaUnicaService;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\TrailerService as RCB800TrailerService;

final class Container extends ContainerAbstract
{
    public function charge()
    {
        $this->content = array(
            'AbatimentoRepository' => function ($container) {
                $dataBase = $container->get('DataBase');

                $dao = new \cl_abatimento();

                return new \ECidade\Tributario\Arrecadacao\Repository\AbatimentoRepository($dataBase, $dao);
            },
            'Arquivo\Service\ArquivoTxtService' => function ($container) {

                return $container->get('FileService');
            },
            'Arquivo\Autoatendimento\RCB80R\Service\ArquivoTxtService' => function ($container) {

                $file = $container->get('File');
                return new RCB80RArquivoTxtService($file);
            },
            'Arquivo\Autoatendimento\RCB800\HeaderService' => function ($container) {
                $dataBase = $container->get('DataBase');

                return new RCB800HeaderService($dataBase);
            },
            'Arquivo\Autoatendimento\RCB800\DetalheService' => function ($container) {
                $dataBase               = $container->get('DataBase');
                $session                = $container->get('Session');
                $reciboCotaUnicaService = $container->get('Arquivo\Autoatendimento\RCB800\ReciboCotaUnicaService');
                $reciboCarneService     = $container->get('Arquivo\Autoatendimento\RCB800\ReciboCarneService');
                $reciboService          = $container->get('Arquivo\Autoatendimento\RCB800\ReciboService');
                $instituicaoRepository  = $container->get('InstituicaoRepository');
                $contribuinteRepository = $container->get('ContribuinteRepository');
                $tipoDebitoRepository   = $container->get('Arquivo\Autoatendimento\RCB800\Repository\TipoDebito');
                $detalheRepository      = $container->get('Arquivo\Autoatendimento\Repository\Detalhe');

                return new \ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\DetalheService(
                    $dataBase,
                    $session,
                    $reciboCotaUnicaService,
                    $reciboCarneService,
                    $reciboService,
                    $instituicaoRepository,
                    $contribuinteRepository,
                    $tipoDebitoRepository,
                    $detalheRepository
                );
            },
            'Arquivo\Autoatendimento\RCB800\TrailerService' => function ($container) {

                $dataBase           = $container->get('DataBase');

                return new RCB800TrailerService($dataBase);
            },
            'Arquivo\Autoatendimento\RCB800\EmissaoService' => function ($container) {

                $session = $container->get("Session");
                $dataBase = $container->get("DataBase");
                $dataBase->execute("select fc_startsession()");
                $dataBase->execute("select fc_putsession('DB_instit', '{$session->getInstituicao()}')");
                $dataBase->execute("select fc_putsession('DB_anousu', '{$session->getAno()}')");

                $arquivo              = $container->get('Arquivo\Service\ArquivoTxtService');
                $headerService        = $container->get('Arquivo\Autoatendimento\RCB800\HeaderService');
                $detalheService       = $container->get('Arquivo\Autoatendimento\RCB800\DetalheService');
                $trailerService       = $container->get('Arquivo\Autoatendimento\RCB800\TrailerService');
                $tipoDebitoRepository = $container->get('Arquivo\Autoatendimento\RCB800\Repository\TipoDebito');

                return new \ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\EmissaoService(
                    $dataBase,
                    $arquivo,
                    $headerService,
                    $detalheService,
                    $trailerService,
                    $tipoDebitoRepository
                );
            },
            'Arquivo\Autoatendimento\RCB800\FiltroHydrator' => function ($container) {

                $listaDebitoService = $container->get('ListaDebitoService');

                return new \ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Hydrator\FiltroHydrator(
                    $listaDebitoService
                );
            },
            'Arquivo\Autoatendimento\RCB800\ReciboService' => function ($container) {
                $reciboService = $container->get('ReciboService');
                $arretipoRepository = $container->get('ArretipoRepository');

                return new \ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\ReciboService(
                    $reciboService,
                    $arretipoRepository
                );
            },
            'Arquivo\Autoatendimento\RCB800\ReciboCarneService' => function ($container) {
                $reciboService = $container->get('Arquivo\Autoatendimento\RCB800\ReciboService');

                return new \ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Service\ReciboCarneService(
                    $reciboService
                );
            },
            'Arquivo\Autoatendimento\RCB800\ReciboCotaUnicaService' => function ($container) {
                $recibounicaRepository = $container->get('RecibounicaRepository');
                $reciboService = $container->get('Arquivo\Autoatendimento\RCB800\ReciboService');

                return new ReciboCotaUnicaService(
                    $recibounicaRepository,
                    $reciboService
                );
            },
            'ContribuinteRepository' => function ($container) {
                $database = $container->get('DataBase');
                $arrematricRepository = $container->get('ArrematricRepository');
                $arreinscrRepository = $container->get('ArreinscrRepository');
                $arrenumcgmRepository = $container->get('ArrenumcgmRepository');
                $iptubaseRepository = $container->get('IptubaseRepository');
                $issbaseRepository = $container->get('IssbaseRepository');

                return new \ECidade\Tributario\Arrecadacao\Entity\Repository\ContribuinteRepository(
                    $database,
                    $arrematricRepository,
                    $arreinscrRepository,
                    $arrenumcgmRepository,
                    $iptubaseRepository,
                    $issbaseRepository
                );
            },
            'Arquivo\Autoatendimento\RCB800\Repository\TipoDebito' => function ($container) {

                $database = $container->get('DataBase');

                return new TipoDebitoRepository($database);
            },
            'Arquivo\Autoatendimento\Repository\Detalhe' => function ($container) {

                $database = $container->get('DataBase');

                return new \ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Repository\Detalhe($database);
            },
            'Arquivo\Autoatendimento\RCB80R\Service\RetornoService' => function ($container) {

                $detalheService = $container->get('Arquivo\Autoatendimento\RCB80R\Service\DetalheService');

                return new \ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80R\Service\RetornoService(
                    $detalheService
                );
            },
            'Arquivo\Autoatendimento\RCB80R\Service\DetalheService' => function ($container) {

                $fileService = $container->get('Arquivo\Autoatendimento\RCB80R\Service\ArquivoTxtService');
                $detalheRepository = $container->get('Arquivo\Autoatendimento\Repository\Detalhe');

                return new \ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80R\Service\DetalheService(
                    $fileService,
                    $detalheRepository
                );
            },
            'CobrancaRegistradaService' => function ($container) {
                $reciboValorTotalStrategy = $container->get("ReciboValorTotal");

                return new \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Service\CobrancaRegistradaService(
                    $reciboValorTotalStrategy
                );
            },
            'ArrecadRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_arrecad();

                return new \ECidade\Tributario\Arrecadacao\Repository\Arrecad($dataBase, $dao);
            },
            'ArreoldRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_arreold();

                return new \ECidade\Tributario\Arrecadacao\Repository\Arreold($dataBase, $dao);
            },
            'ArreforoRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_arreforo();

                return new \ECidade\Tributario\Arrecadacao\Repository\ArreforoRepository($dataBase, $dao);
            }
            // abatimento
            // abatimentorecibo
            // abatimentoutilizacao
            // abatimentoutilizacaodestino
        );
    }
}

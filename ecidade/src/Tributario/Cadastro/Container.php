<?php

namespace ECidade\Tributario\Cadastro;

use ECidade\Tributario\Library\Container as ContainerAbstract;

final class Container extends ContainerAbstract
{
    public function charge()
    {
        $this->content = array(
            'Iptu\Arquivo\LoteamentoBlock' => function ($container) {
                
                $layout = $container->get('Iptu\Arquivo\LoteamentoLayout');
                $converter = $container->get('Iptu\Arquivo\LoteamentoConverter');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Block\LoteamentoBlock($layout, $converter);
            },
            'Iptu\Arquivo\LoteamentoConverter' => function ($container) {
            return new
            \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\LoteamentoConverter($container->get('Format'));
            },
            'Iptu\Arquivo\LoteamentoLayout' => function ($container) {
                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Loteamento();
            },
            'Iptu\Arquivo\ParcelaReciboCast' => function ($container) {
                
                $reciboValorTotal = $container->get('ReciboValorTotal');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Cast\ParcelaReciboCast($reciboValorTotal);
            },
            'Iptu\Arquivo\ContribuinteConverter' => function ($container) {
                
                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Contribuinte();

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ContribuinteConverter($format, $layout);
            },
            'Iptu\Arquivo\ExercicioConverter' => function ($container) {
                
                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Exercicio();

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ExercicioConverter($format, $layout);
            },
            'Iptu\Arquivo\UnicaConverter' => function ($container) {

                /**
                 * @todo um container não deve injetar configurações
                 */
                $percentualDescontoUnica = 15;
                $vencimentoUnica         = new \DateTime();
                $vencimentoUnica->setDate(11, 3, date('Y'));
                
                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Unica(
                    $percentualDescontoUnica,
                    $vencimentoUnica
                );

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\UnicaConverter($format, $layout);
            },
            'Iptu\Arquivo\ImovelConverter' => function ($container) {

                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Imovel();

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ImovelConverter($format, $layout);
            },
            'Iptu\Arquivo\ParcelaConverter' => function ($container) {

                $format = $container->get('Format');
                
                /**
                 * @todo um container não deve injetar configurações
                 */
                $receitas = array(5, 33, 100);

                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Parcela(12, $receitas);

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ParcelaConverter($format, $layout);
            },
            'Iptu\Arquivo\ParcelaInicioConverter' => function ($container) {

                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\ParcelaInicio();

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ParcelaInicioConverter($format, $layout);
            },
            'Iptu\Arquivo\ParcelaReciboConverter' => function ($container) {

                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\ParcelaRecibo(12);

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ParcelaReciboConverter($format, $layout);
            },
            'Iptu\Arquivo\ParcelaPagaConverter' => function ($container) {

                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\ParcelaPaga(12);

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ParcelaPagaConverter($format, $layout);
            },
            'Iptu\Arquivo\ImovelAnteriorConverter' => function ($container) {

                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\ImovelAnterior();

               return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\ImovelAnteriorConverter($format, $layout);
            },
            'Iptu\Arquivo\FaceConverter' => function ($container) {

                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Face();

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\FaceConverter($format, $layout);
            },
            'Iptu\Arquivo\BancoConverter' => function ($container) {

                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Banco();

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\BancoConverter($format, $layout);
            },
            'Iptu\Arquivo\LocalizacaoConverter' => function ($container) {

                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Localizacao();

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\LocalizacaoConverter($format, $layout);
            },
            'Iptu\Arquivo\UnicaIptuConverter' => function ($container) {

                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\UnicaIptu();

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\UnicaIptuConverter($format, $layout);
            },
            'Iptu\Arquivo\TaxaConverter' => function ($container) {

                $format = $container->get('Format');
                $layout = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Taxa();

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\TaxaConverter($format, $layout);
            },
            'Iptu\Arquivo\FiltroHydrator' => function ($container) {
                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Hydrator\FiltroHydrator();
            },
            'Iptu\Arquivo\ContribuinteRepository' => function ($container) {

                $dataBase = $container->get('DataBase');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\ContribuinteRepository($dataBase);
            },
            'Iptu\Arquivo\DebitoRepository' => function ($container) {

                $entityDebitoRepository = $container->get('DebitoRepository');

              return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\DebitoRepository($entityDebitoRepository);
            },
            'Iptu\Arquivo\ImovelRepository' => function ($container) {

                $dataBase = $container->get('DataBase');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\ImovelRepository($dataBase);
            },
            'Iptu\Arquivo\LoteamentoRepository' => function ($container) {

                $dataBase = $container->get('DataBase');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\LoteamentoRepository($dataBase);
            },
            'Iptu\Arquivo\ImovelAnteriorRepository' => function ($container) {

                $dataBase = $container->get('DataBase');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\ImovelAnteriorRepository($dataBase);
            },
            'Iptu\Arquivo\ExercicioRepository' => function ($container) {

                $dataBase = $container->get('DataBase');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\ExercicioRepository($dataBase);
            },
            'Iptu\Arquivo\MatriculaRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $iptubaseRepository = $container->get('IptubaseRepository');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\MatriculaRepository(
                    $dataBase,
                    $iptubaseRepository
                );
            },
            'Iptu\Arquivo\ParcelaPagaRepository' => function ($container) {

                $dataBase = $container->get('DataBase');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\ParcelaPagaRepository($dataBase);
            },
            'Iptu\Arquivo\TaxaDescricaoRepository' => function ($container) {

                $dataBase = $container->get('DataBase');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\TaxaDescricaoRepository($dataBase);
            },
            'Iptu\Arquivo\TaxaRepository' => function ($container) {

                $dataBase = $container->get('DataBase');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository\TaxaRepository($dataBase);
            },
            'Iptu\Arquivo\ArquivoTxtService' => function ($container) {

                $file = $container->get('File');
                
                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ArquivoTxtService($file);
            },
            'Iptu\Arquivo\ArquivoLayoutService' => function ($container) {

                $file = $container->get('File');
                
                /**
                 * @todo um container não deve injetar configurações
                 */
                $percentualDescontoUnica = 15;
                $vencimentoUnica         = new \DateTime();
                $vencimentoUnica->setDate(11, 3, date('Y'));
                $receitas = array(5, 33, 100);
                $path  = 'tmp/layout';
                $path .= '_' . date('Ymd');
                $path .= '.txt';
                // end TODO

                $arquivo = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ArquivoTxtService($file);
                $arquivo->create($path);

                $layoutBanco                   = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Banco();
                $layoutContribuinte            = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Contribuinte();
                $layoutExercicio               = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Exercicio();
                $layoutFace                    = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Face();
                $layoutImovelAnterior          = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\ImovelAnterior();
                $layoutImovel                  = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Imovel();
                $layoutLocalizacao             = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Localizacao();
                $layoutNossoNumero             = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\NossoNumero(12);
		$layoutNossoNumeroVersao2      =
		       	new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\NossoNumeroVersao2(12);
		$layoutNossoNumeroUnicaVersao2 =
		       	new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\NossoNumeroUnicaVersao2(1);
                $layoutParcelaInicio           = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\ParcelaInicio();
                $layoutParcelaPaga             = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\ParcelaPaga(12);
		$layoutParcela                 =
		       	new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Parcela(12, $receitas);
                $layoutParcelaRecibo           = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\ParcelaRecibo(12);
                $layoutTotalUnica              = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\TotalUnica();
                
                $layoutsNossoNumeroUnica   = array();
                $layoutsNossoNumeroUnica[] = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\NossoNumeroUnica(1);
                $layoutsNossoNumeroUnica[] = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\NossoNumeroUnica(2);
                
                $layoutsUnica = array();
                $layoutsUnica[] = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Unica(
                    $percentualDescontoUnica,
                    $vencimentoUnica
                );
                
                /**
                 * @todo um container não deve injetar configurações
                 */
                $layoutBranco86  = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Generico(
                    'BRANCO',
                    'SEM USO',
                    86
                );
                $layoutContador  = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Generico(
                    'CONTADOR',
                    'CONTADOR',
                    10
                );
                $layoutFimUnicas = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Generico(
                    'FIMUNICAS',
                    'FIM DAS UNICAS',
                    16
                );
                $layoutTotalPago = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Generico(
                    'TOTALPAGO',
                    'TOTAL PAGO DESTE REGISTRO',
                    18
                );
                
                /**
                 * @todo um container não deve injetar configurações
                 */
                $layoutsTaxa = array();
                $layoutsTaxa[] = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Taxa(1, 'IPTU');
                $layoutsTaxa[] = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Taxa(2, 'TAXA');

                /**
                 * @todo um container não deve injetar configurações
                 */
                $layoutsTaxaSegundoBloco = array();
                $layoutsTaxaSegundoBloco[] = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Taxa(1, 'IPTU');
                $layoutsTaxaSegundoBloco[] = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Taxa(
                    5,
                    'ISENCAO DE IPTU'
                );
                $layoutsTaxaSegundoBloco[] = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Taxa(
                    7,
                    'LIMPEZA'
                );
                $layoutsTaxaSegundoBloco[] = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Taxa(
                    9,
                    'ISENCAO TAXA DE LIMPEZA'
                );
                $layoutsTaxaSegundoBloco[] = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Taxa(
                    11,
                    'DESCONTO BOM PAGADOR IPTU'
                );
                $layoutsTaxaSegundoBloco[] = new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Taxa(
                    12,
                    'DESCONTO BOM PAGADOR LIMPEZA'
                );

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ArquivoLayoutService(
                    $arquivo,
                    $layoutBanco,
                    $layoutContribuinte,
                    $layoutExercicio,
                    $layoutFace,
                    $layoutImovelAnterior,
                    $layoutImovel,
                    $layoutLocalizacao,
                    $layoutNossoNumero,
                    $layoutsNossoNumeroUnica,
                    $layoutNossoNumeroVersao2,
                    $layoutNossoNumeroUnicaVersao2,
                    $layoutParcelaInicio,
                    $layoutParcelaPaga,
                    $layoutParcela,
                    $layoutParcelaRecibo,
                    $layoutTotalUnica,
                    $layoutsUnica,
                    $layoutsTaxa,
                    $layoutBranco86,
                    $layoutContador,
                    $layoutFimUnicas,
                    $layoutTotalPago,
                    $layoutsTaxaSegundoBloco
                );
            },
            'Iptu\Arquivo\DebitoService' => function ($container) {

                $debitoRepository = $container->get('DebitoRepository');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\DebitoService($debitoRepository);
            },
            'Iptu\Arquivo\EmissaoService' => function ($container) {

                $dataBase = $container->get('DataBase');
                $arquivoTxtService = $container->get('Iptu\Arquivo\ArquivoTxtService');
                $linhaService = $container->get('Iptu\Arquivo\LinhaService');
                $matriculaRepository = $container->get('Iptu\Arquivo\MatriculaRepository');
                $cfiptuRepository = $container->get('CfiptuRepository');
                $arquivoLayoutService = $container->get('Iptu\Arquivo\ArquivoLayoutService');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\EmissaoService(
                    $dataBase,
                    $arquivoTxtService,
                    $linhaService,
                    $matriculaRepository,
                    $cfiptuRepository,
                    $arquivoLayoutService
                );
            },
            'Iptu\Arquivo\LinhaConverterService' => function ($container) {

                $imovelConverter = $container->get('Iptu\Arquivo\ImovelConverter');
                $contribuinteConverter = $container->get('Iptu\Arquivo\ContribuinteConverter');
                $exercicioConverter = $container->get('Iptu\Arquivo\ExercicioConverter');
                $unicaConverter = $container->get('Iptu\Arquivo\UnicaConverter');
                $parcelaInicioConverter = $container->get('Iptu\Arquivo\ParcelaInicioConverter');
                $parcelaReciboConverter = $container->get('Iptu\Arquivo\ParcelaReciboConverter');
                // $parcelaPagaConverter = $container->get('Iptu\Arquivo\ParcelaPagaConverter');
                $imovelAnteriorConverter = $container->get('Iptu\Arquivo\ImovelAnteriorConverter');
                // $parcelaConverter = $container->get('Iptu\Arquivo\ParcelaConverter');
                // $faceConverter = $container->get('Iptu\Arquivo\FaceConverter');
                // $bancoConverter = $container->get('Iptu\Arquivo\BancoConverter');
                // $localizacaoConverter = $container->get('Iptu\Arquivo\LocalizacaoConverter');
                $taxaConverter = $container->get('Iptu\Arquivo\TaxaConverter');
                $loteamentoBlock = $container->get('Iptu\Arquivo\LoteamentoBlock');
                
                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\LinhaConverterService(
                    $imovelConverter,
                    $contribuinteConverter,
                    $exercicioConverter,
                    $unicaConverter,
                    $parcelaInicioConverter,
                    $parcelaReciboConverter,
                    // $parcelaPagaConverter,
                    $imovelAnteriorConverter,
                    $taxaConverter,
                    // $parcelaConverter <----
                    // $faceConverter,
                    // $bancoConverter,
                    // $localizacaoConverter
                    $loteamentoBlock
                );
            },
            'Iptu\Arquivo\LinhaService' => function ($container) {

                $linhaConverterService = $container->get('Iptu\Arquivo\LinhaConverterService');
                $debitoRepository = $container->get('Iptu\Arquivo\DebitoRepository');
                $reciboCotaUnicaService = $container->get('Iptu\Arquivo\ReciboCotaUnicaService');
                $reciboCarneService = $container->get('Iptu\Arquivo\ReciboCarneService');
                $reciboParcelaService = $container->get('Iptu\Arquivo\ReciboParcelaService');
                $imovelRepository = $container->get('Iptu\Arquivo\ImovelRepository');
                $contribuinteRepository = $container->get('Iptu\Arquivo\ContribuinteRepository');
                $parcelaReciboCast = $container->get('Iptu\Arquivo\ParcelaReciboCast');
                $imovelAnteriorRepository = $container->get('Iptu\Arquivo\ImovelAnteriorRepository');
                $exercicioRepository = $container->get('Iptu\Arquivo\ExercicioRepository');
                $taxaRepository = $container->get('Iptu\Arquivo\TaxaRepository');
                $taxaDescricaoRepository = $container->get('Iptu\Arquivo\TaxaDescricaoRepository');
                $parcelaInicioService = $container->get('Iptu\Arquivo\ParcelaInicioService');
                $loteamentoRepository = $container->get('Iptu\Arquivo\LoteamentoRepository');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\LinhaService(
                    $linhaConverterService,
                    $debitoRepository,
                    $reciboCotaUnicaService,
                    $reciboCarneService,
                    $reciboParcelaService,
                    $imovelRepository,
                    $contribuinteRepository,
                    $parcelaReciboCast,
                    $imovelAnteriorRepository,
                    $exercicioRepository,
                    $parcelaInicioService,
                    $taxaRepository,
                    $taxaDescricaoRepository,
                    $loteamentoRepository
                );
            },
            'Iptu\Arquivo\ReciboCotaUnicaService' => function ($container) {

                $reciboService = $container->get('ReciboService');
                $recibounicaRepository = $container->get('RecibounicaRepository');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ReciboCotaUnicaService(
                    $recibounicaRepository,
                    $reciboService
                );
            },
            'Iptu\Arquivo\ParcelaInicioService' => function ($container) {

                $reciboService = $container->get('ReciboService');
                $recibounicaRepository = $container->get('RecibounicaRepository');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ParcelaInicioService();
            },
            'Iptu\Arquivo\ReciboCarneService' => function ($container) {

                $reciboService = $container->get('ReciboService');

                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ReciboCarneService($reciboService);
            },
            'Iptu\Arquivo\ReciboParcelaService' => function ($container) {
                return new \ECidade\Tributario\Cadastro\Iptu\Arquivo\Service\ReciboParcelaService();
            },
            'CaracterRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_caracter();

                return new \ECidade\Tributario\Cadastro\Repository\CaracterRepository($dataBase, $dao);
            },
            'CarconstrRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_carconstr();

                return new \ECidade\Tributario\Cadastro\Repository\CarconstrRepository($dataBase, $dao);
            },
            'CarfatorRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_carfator();

                return new \ECidade\Tributario\Cadastro\Repository\CarfatorRepository($dataBase, $dao);
            },
            'CarloteRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_carlote();

                return new \ECidade\Tributario\Cadastro\Repository\CarloteRepository($dataBase, $dao);
            },

            'CfiptuRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_cfiptu();

                return new \ECidade\Tributario\Cadastro\Repository\CfiptuRepository($dataBase, $dao);
            },
            'IptubaseRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_iptubase();

                return new \ECidade\Tributario\Cadastro\Repository\IptubaseRepository($dataBase, $dao);
            },
            'IptucadtaxaRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_iptucadtaxa();

                return new \ECidade\Tributario\Cadastro\Repository\IptucadtaxaRepository($dataBase, $dao);
            },
            'IptucadtaxaexeRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_iptucadtaxaexe();

                return new \ECidade\Tributario\Cadastro\Repository\IptucadtaxaexeRepository($dataBase, $dao);
            },
            'IptucaleRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_iptucale();

                return new \ECidade\Tributario\Cadastro\Repository\IptucaleRepository($dataBase, $dao);
            },
            'IptucalhRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_iptucalh();

                return new \ECidade\Tributario\Cadastro\Repository\IptucalhRepository($dataBase, $dao);
            },
            'IptucalvRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_iptucalv();

                return new \ECidade\Tributario\Cadastro\Repository\IptucalvRepository($dataBase, $dao);
            },
            'IptuconstrRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_iptuconstr();

                return new \ECidade\Tributario\Cadastro\Repository\IptuconstrRepository($dataBase, $dao);
            },
            'IptuisenRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_iptuisen();

                return new \ECidade\Tributario\Cadastro\Repository\IptuisenRepository($dataBase, $dao);
            },
            'IptutaxacalvRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_iptutaxacalv();

                return new \ECidade\Tributario\Cadastro\Repository\IptutaxacalvRepository($dataBase, $dao);
            },
            'IptutaxanumpRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_iptutaxanump();

                return new \ECidade\Tributario\Cadastro\Repository\IptutaxanumpRepository($dataBase, $dao);
            },
            'IsenexeRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_isenexe();

                return new \ECidade\Tributario\Cadastro\Repository\IsenexeRepository($dataBase, $dao);
            },
            'IsenprocRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_isenproc();

                return new \ECidade\Tributario\Cadastro\Repository\IsenprocRepository($dataBase, $dao);
            },
            'IsentaxaRepository' => function ($container) {
                
                $dataBase = $container->get('DataBase');
                $dao = new \cl_isentaxa();

                return new \ECidade\Tributario\Cadastro\Repository\IsentaxaRepository($dataBase, $dao);
            }
            // iptunump
            // iptucalclogmat
            // iptucalclog
            // iptucalcconfrec
            // lote
            // carlote
            // caracter
            // cargrup
            // iptutaxamatric

        );
    }
}

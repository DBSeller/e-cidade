<?php

namespace Tests\Unit\Tributario\Agua\Repository;

use AguaLeitura;
use ECidade\Tributario\Agua\Entity\Leitura\Situacao;
use ECidade\Tributario\Agua\Repository\Leitura as LeituraRepository;
use Tests\TestCase;

/**
 * Class LeituraTest
 * @package Tests\Unit\Tributario\Agua\Repository
 */
class LeituraTest extends TestCase
{
    /**
     *
     */
    public function testDeveInstanciarRepository()
    {
        $oRepository = new LeituraRepository;
        $this->assertInstanceOf(LeituraRepository::class, $oRepository);
    }

    /**
     * @return array
     * @throws \DBException
     * @throws \ParameterException
     */
    public function ultimasLeiturasQuantidadeProvider()
    {
        return [
            [
                $oPrimeiraLeitura = $this->criarLeitura(1, 10, 2016, 1, Situacao::REGRA_NORMAL),
                $aUltimasLeituras = [
                    [
                        $this->criarLeitura(2, 10, 2017, 7, Situacao::REGRA_NORMAL),
                        $this->criarLeitura(3, 10, 2017, 6, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                        $this->criarLeitura(4, 20, 2017, 5, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                        $this->criarLeitura(5, 30, 2017, 4, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                        $this->criarLeitura(6, 30, 2017, 3, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                        $this->criarLeitura(7, 30, 2017, 2, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                        $this->criarLeitura(8, 30, 2017, 1, Situacao::REGRA_NORMAL),
                    ],
                    [
                        $this->criarLeitura(9, 15, 2016, 12, Situacao::REGRA_NORMAL),
                    ],
                ],
                $iQuantidadeEsperada = 7,
            ],

            [
                $oPrimeiraLeitura = $this->criarLeitura(1, 10, 2016, 1, Situacao::REGRA_NORMAL),
                $aUltimasLeituras = [
                    [
                        $this->criarLeitura(2, 10, 2017, 7, Situacao::REGRA_NORMAL),
                        $this->criarLeitura(3, 10, 2017, 6, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                        $this->criarLeitura(4, 20, 2017, 5, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                        $this->criarLeitura(5, 30, 2017, 4, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                        $this->criarLeitura(6, 30, 2017, 3, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                        $this->criarLeitura(7, 30, 2017, 2, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                        $this->criarLeitura(8, 30, 2017, 1, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                    ],
                    [
                        $this->criarLeitura(9, 15, 2016, 12, Situacao::REGRA_NORMAL),
                    ],
                ],
                $iQuantidadeEsperada = 8,
            ],

            [
                $oPrimeiraLeitura = $this->criarLeitura(1, 10, 2016, 1, Situacao::REGRA_NORMAL),
                $aUltimasLeituras = [
                    [
                        $this->criarLeitura(2, 10, 2017, 7, Situacao::REGRA_NORMAL),
                        $this->criarLeitura(3, 10, 2017, 6, Situacao::REGRA_NORMAL),
                        $this->criarLeitura(4, 20, 2017, 5, Situacao::REGRA_NORMAL),
                        $this->criarLeitura(5, 30, 2017, 4, Situacao::REGRA_NORMAL),
                        $this->criarLeitura(6, 30, 2017, 3, Situacao::REGRA_NORMAL),
                        $this->criarLeitura(7, 30, 2017, 2, Situacao::REGRA_NORMAL),
                        $this->criarLeitura(8, 30, 2017, 1, Situacao::REGRA_NORMAL),
                    ],
                    [
                        $this->criarLeitura(9, 15, 2016, 12, Situacao::REGRA_NORMAL),
                    ],
                ],
                $iQuantidadeEsperada = 7,
            ],
        ];
    }

    /**
     * @param $iCodigo
     * @param $iConsumo
     * @param $iAno
     * @param $iMes
     * @param $iRegra
     * @return AguaLeitura
     * @throws \DBException
     * @throws \ParameterException
     */
    private function criarLeitura($iCodigo, $iConsumo, $iAno, $iMes, $iRegra)
    {
        $oAguaLeitura = new AguaLeitura();
        $oAguaLeitura->setCodigo($iCodigo);
        $oAguaLeitura->setConsumo($iConsumo);
        $oAguaLeitura->setMes($iMes);
        $oAguaLeitura->setAno($iAno);
        $oAguaLeitura->setSituacaoLeitura($this->criarRegra($iRegra));

        return $oAguaLeitura;
    }

    /**
     * @param $iRegra
     * @return Situacao
     */
    private function criarRegra($iRegra)
    {
        $oSituacao = new Situacao();
        $oSituacao->setRegra($iRegra);

        return $oSituacao;
    }

    /**
     * @dataProvider ultimasLeiturasQuantidadeProvider
     *
     * @param AguaLeitura $oPrimeiraLeitura
     * @param array $aUltimasLeituras
     * @param integer $iQuantidadeEsperada
     */
    public function testDeveRetornarUltimasLeiturasNaQuantidadeCerta(
        AguaLeitura $oPrimeiraLeitura,
        array $aUltimasLeituras,
        $iQuantidadeEsperada
    ) {
        $oRepository = $this->getMockRepository($oPrimeiraLeitura, $aUltimasLeituras);

        $aResultado = $oRepository->findUltimas(123, 7, 2017);

        $this->assertCount($iQuantidadeEsperada, $aResultado, 'Quantidade de leituras diferente do esperado.');
    }

    /**
     * @param AguaLeitura $oPrimeiraLeitura
     * @param array $aUltimasLeituras
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockRepository(AguaLeitura $oPrimeiraLeitura, array $aUltimasLeituras)
    {
        $oRepository = $this
            ->getMockBuilder(LeituraRepository::class)
            ->setMethods(['findBy', 'findPrimeira'])
            ->getMock();

        /**
         * Mock primeira leitura
         */
        $oRepository
            ->method('findPrimeira')
            ->will($this->returnValue($oPrimeiraLeitura));

        /**
         * Mock últimas leituras
         */
        $oRepository
            ->method('findBy')
            ->will(call_user_func_array(array($this, 'onConsecutiveCalls'), $aUltimasLeituras));

        return $oRepository;
    }

    /**
     * @throws \DBException
     * @throws \ParameterException
     */
    public function testDeveRetornarUltimasLeiturasEmOrdemCronologica()
    {

        $oRepository = $this->getMockRepository(
            $oPrimeiraLeitura = $this->criarLeitura(1, 10, 2016, 1, Situacao::REGRA_NORMAL),
            $aUltimrasLeituras = [
                [
                    $this->criarLeitura(10, 10, 2017, 7, Situacao::REGRA_NORMAL),
                    $this->criarLeitura(9, 10, 2017, 6, Situacao::REGRA_NORMAL),
                    $this->criarLeitura(8, 20, 2017, 5, Situacao::REGRA_NORMAL),
                    $this->criarLeitura(7, 30, 2017, 4, Situacao::REGRA_NORMAL),
                    $this->criarLeitura(6, 30, 2017, 3, Situacao::REGRA_NORMAL),
                    $this->criarLeitura(5, 30, 2017, 2, Situacao::REGRA_NORMAL),
                    $this->criarLeitura(4, 30, 2017, 1, Situacao::REGRA_MEDIA_ULTIMOS_MESES),
                ],
                [
                    $this->criarLeitura(3, 10, 2016, 12, Situacao::REGRA_NORMAL),
                ],
            ]
        );

        $aResultado = $oRepository->findUltimas(123, 7, 2017);

        $this->assertEquals(7, $aResultado[0]->getMes());
        $this->assertEquals(2017, $aResultado[0]->getAno());

        $this->assertEquals(6, $aResultado[1]->getMes());
        $this->assertEquals(2017, $aResultado[1]->getAno());

        $this->assertEquals(5, $aResultado[2]->getMes());
        $this->assertEquals(2017, $aResultado[2]->getAno());

        $this->assertEquals(4, $aResultado[3]->getMes());
        $this->assertEquals(2017, $aResultado[3]->getAno());

        $this->assertEquals(3, $aResultado[4]->getMes());
        $this->assertEquals(2017, $aResultado[4]->getAno());

        $this->assertEquals(2, $aResultado[5]->getMes());
        $this->assertEquals(2017, $aResultado[5]->getAno());

        $this->assertEquals(1, $aResultado[6]->getMes());
        $this->assertEquals(2017, $aResultado[6]->getAno());

        $this->assertEquals(12, $aResultado[7]->getMes());
        $this->assertEquals(2016, $aResultado[7]->getAno());
    }
}

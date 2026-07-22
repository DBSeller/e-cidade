<?php

namespace Tests\Unit\Tributario\Agua;

use AguaEstruturaTarifaria;
use BusinessException;
use ECidade\Tributario\Agua\Calculo\Estrutura\Percentual as CalculoPercentual;
use Tests\TestCase;

/**
 * Class CalculoPercentualTest
 * @package Tests\Unit\Tributario\Agua
 */
class CalculoPercentualTest extends TestCase
{
    /**
     * @throws BusinessException
     */
    public function testCalculoDeveLancarExcecaoNoCasoDeValorNaoInformado()
    {
        $this->expectException(BusinessException::class);
        $oCalculoPercentual = new CalculoPercentual;
        $oCalculoPercentual->setEstruturaTarifaria($this->getEstrutura());
        $oCalculoPercentual->calcular();
    }

    /**
     * @return AguaEstruturaTarifaria
     */
    private function getEstrutura()
    {
        $oEstrutura = new AguaEstruturaTarifaria;
        $oEstrutura->setCodigoTipoEstrutura(AguaEstruturaTarifaria::TIPO_PERCENTUAL);
        $oEstrutura->setCodigoTipoConsumo(5);
        $oEstrutura->setPercentual(50);

        return $oEstrutura;
    }

    /**
     * @return array
     */
    public function calculoAcrescimoProvider()
    {
        return [
            [50, 100, 50],
            [10, 200, 20],
            [37, 115.79, 42.84],
        ];
    }

    /**
     * @dataProvider calculoAcrescimoProvider
     *
     * @param integer $iPercentual
     * @param float $nValor
     * @param float $nValorEsperado
     */
    public function testCalculoDeveRetornarAcrescimo($iPercentual, $nValor, $nValorEsperado)
    {

        $oEstrutura = new AguaEstruturaTarifaria;
        $oEstrutura->setCodigoTipoEstrutura(AguaEstruturaTarifaria::TIPO_PERCENTUAL);
        $oEstrutura->setCodigoTipoConsumo(5);
        $oEstrutura->setPercentual($iPercentual);

        $oCalculoPercentual = new CalculoPercentual;
        $oCalculoPercentual->setEstruturaTarifaria($oEstrutura);
        $oCalculoPercentual->setValor($nValor);
        $this->assertEquals($nValorEsperado, $oCalculoPercentual->calcular());
    }
}

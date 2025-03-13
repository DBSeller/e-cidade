<?php

namespace Tests\Unit\Tributario\Agua;

use AguaEstruturaTarifaria;
use ECidade\Tributario\Agua\Calculo\Estrutura\FaixaConsumo as CalculoConsumo;
use Tests\TestCase;

/**
 * Class CalculoConsumoTest
 * @package Tests\Unit\Tributario\Agua
 */
class CalculoConsumoTest extends TestCase
{
    /**
     *
     */
    public function testCalculoDeveConterConsumo()
    {
        $oCalculaConsumoAgua = new CalculoConsumo();
        $oCalculaConsumoAgua->setConsumo(10);

        $this->assertEquals(10, $oCalculaConsumoAgua->getConsumo());
    }

    /**
     * @return array
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function calculoProporcionalAoConsumoProvider()
    {
        $aFaixas = [
            // Residencial social
            ['valorInicial' => null, 'valorFinal' => 15, 'valor' => 0.75],
            ['valorInicial' => 16, 'valorFinal' => 25, 'valor' => 1.20],
            ['valorInicial' => 26, 'valorFinal' => 35, 'valor' => 1.80],
            ['valorInicial' => 36, 'valorFinal' => 45, 'valor' => 2.20],
            ['valorInicial' => 46, 'valorFinal' => 55, 'valor' => 2.80],
            ['valorInicial' => 56, 'valorFinal' => null, 'valor' => 3.00],
        ];

        $aEstruturas = $this->getEstruturasTarifarias($aFaixas);
        return array(
            array($aEstruturas[0], 5, 3.75),
            array($aEstruturas[1], 17, 2.40),
            array($aEstruturas[2], 29, 7.20),
            array($aEstruturas[3], 40, 11.00),
            array($aEstruturas[4], 49, 11.20),
            array($aEstruturas[5], 65, 30.00)
        );
    }

    /**
     * @param $aFaixas
     * @return array
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    private function getEstruturasTarifarias($aFaixas)
    {
        $aEstruturas = array();

        foreach ($aFaixas as $aFaixa) {
            $oEstrutura = new AguaEstruturaTarifaria;
            $oEstrutura->setCodigoTipoEstrutura(AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO);
            $oEstrutura->setValorInicial($aFaixa['valorInicial']);
            $oEstrutura->setValorFinal($aFaixa['valorFinal']);
            $oEstrutura->setValor($aFaixa['valor']);
            $aEstruturas[] = $oEstrutura;
        }

        return $aEstruturas;
    }

    /**
     * @dataProvider calculoProporcionalAoConsumoProvider
     */
    public function testCalculoDeveSerProporcionalAFaixaDeConsumo($oEstrutura, $iConsumo, $nValorCalculado)
    {
        $oCalculaConsumoAgua = new CalculoConsumo();
        $oCalculaConsumoAgua->setConsumo($iConsumo);
        $oCalculaConsumoAgua->setEstruturaTarifaria($oEstrutura);

        $nValor = $oCalculaConsumoAgua->calcular();

        $this->assertEquals($nValorCalculado, $nValor, '', 0.0001);
    }
}

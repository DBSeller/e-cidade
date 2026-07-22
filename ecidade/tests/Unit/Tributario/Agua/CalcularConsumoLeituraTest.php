<?php

namespace Tests\Unit\Tributario\Agua;

use ECidade\Tributario\Agua\Calculo\Consumo;
use Tests\TestCase;

/**
 * Class CalcularConsumoLeitura
 * @package Tests\Unit\Tributario\Agua
 */
class CalcularConsumoLeitura extends TestCase
{
    /**
     * @return array
     */
    public function calcularConsumoProvider()
    {
        return [
            [
                [
                    'iDigitosHidrometro' => 3,
                    'iLeituraAtual' => 55,
                    'iLeituraAnterior' => 50,
                    'iConsumoEsperado' => 5,
                ]
            ],

            [
                [
                    'iDigitosHidrometro' => 3,
                    'iLeituraAtual' => 10,
                    'iLeituraAnterior' => 999,
                    'iConsumoEsperado' => 10,
                ]
            ],

            [
                [
                    'iDigitosHidrometro' => 3,
                    'iLeituraAtual' => 10,
                    'iLeituraAnterior' => 900,
                    'iConsumoEsperado' => 109,
                ]
            ],
        ];
    }

    /**
     * @dataProvider calcularConsumoProvider
     */
    public function testDeveCalcularConsumoCorretamente($aDados)
    {

        $oHidrometro = new \AguaHidrometro;
        $oHidrometro->setQuantidadeDigitos($aDados['iDigitosHidrometro']);

        $oCalculoConsumo = new Consumo();
        $oCalculoConsumo->setHidrometro($oHidrometro);

        $nResultado = $oCalculoConsumo->calcular($aDados['iLeituraAtual'], $aDados['iLeituraAnterior']);

        $this->assertEquals($aDados['iConsumoEsperado'], $nResultado);
    }

}

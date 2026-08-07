<?php

namespace Tests\Unit\Tributario\Agua\Isencao;

use BusinessException;
use ECidade\Tributario\Agua\Calculo\Isencao\Desconto;
use Tests\TestCase;

/**
 * Class DescontoTest
 * @package Tests\Unit\Tributario\Agua\Isencao
 */
class DescontoTest extends TestCase
{
    /**
     * @throws BusinessException
     */
    public function testLancarExcecaoSeConsumoNaoInformado()
    {
        $this->expectException(BusinessException::class);
        $oDesconto = new Desconto;
        $oDesconto->calcular(0);
    }

    /**
     * @throws BusinessException
     */
    public function testLancarExcecaoSeResultadosNaoInformados()
    {
        $this->expectException(BusinessException::class);
        $oDesconto = new Desconto;
        $oDesconto->setConsumo(10);
        $oDesconto->calcular(0);
    }

    /**
     * @throws BusinessException
     */
    public function testDescontoDeveSerDadoCorretamente()
    {
        $oDesconto = new Desconto;
        $oDesconto->setConsumo(50);
        $oDesconto->setResultadosPorFaixaConsumo([
            1 => 11.25, // Desconto: 4,50
            2 => 12.00, // Desconto: 3,60
            3 => 18.00, // Desconto: 3,60
            4 => 22.00,
            5 => 28.00,
            6 => 75.00,
        ]);
        $nValorDesconto = $oDesconto->calcular(166.25);

        // Desconto esperado = 4,50 + 3,60 + 3,60 = 11,70
        $this->assertEquals(11.70, $nValorDesconto);
    }
}

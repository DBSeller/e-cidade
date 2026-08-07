<?php

namespace Tests\Unit\Tributario\Agua\Isencao;

use ECidade\Tributario\Agua\Calculo\Isencao\Imune;
use Tests\TestCase;

class ImuneTest extends TestCase
{
    public function testDeveRetornarValorTotalConsumo()
    {
        $oIsencao = new Imune;
        $nValorDesconto = $oIsencao->calcular(500);

        $this->assertEquals(500, $nValorDesconto);
        $this->assertTrue($oIsencao->temIsencaoTarifaBasicaEsgoto());
        $this->assertTrue($oIsencao->temIsencaoTarifaBasicaAgua());
    }
}

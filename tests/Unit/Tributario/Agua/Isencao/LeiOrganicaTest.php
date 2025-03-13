<?php

namespace Tests\Unit\Tributario\Agua\Isencao;

use AguaCategoriaConsumo;
use AguaEstruturaTarifaria;
use ECidade\Tributario\Agua\Calculo\Isencao\LeiOrganica;
use Exception;
use Tests\TestCase;

/**
 * Class LeiOrganicaTest
 * @package Tests\Unit\Tributario\Agua\Isencao
 */
class LeiOrganicaTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testDeveDarDescontoCorretamente()
    {
        $nValor = 2;

        $oEstrutura = new AguaEstruturaTarifaria;
        $oEstrutura->setValor($nValor);
        $oEstrutura->setCodigoTipoEstrutura(AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO);

        $oCategoriaConsumo = new AguaCategoriaConsumo;
        $oCategoriaConsumo->adicionarEstrutura($oEstrutura);

        $oLeiOrganica = new LeiOrganica;
        $oLeiOrganica->setConsumo(20);
        $oLeiOrganica->setCategoriaConsumo($oCategoriaConsumo);

        $nResultado = $oLeiOrganica->calcular(50);

        $this->assertEquals(10 * $nValor, $nResultado);
    }
}

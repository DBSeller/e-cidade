<?php

namespace Tests\Unit\Tributario\Agua;

use AguaEstruturaTarifaria;
use BusinessException;
use ECidade\Tributario\Agua\Calculo\Estrutura\ValorFixo as CalculoTarifaBasica;
use Tests\TestCase;

/**
 * Class CalculoTarifaBasicaTest
 * @package Tests\Unit\Tributario\Agua
 */
class CalculoTarifaBasicaTest extends TestCase
{
    /**
     * @throws BusinessException
     */
    public function testCalculoTarifaBasicaSemEstruturaTarifariaDeveLancarExcecao()
    {
        $oCalculoTarifaBasica = new CalculoTarifaBasica;
        $this->expectException(BusinessException::class);
        $this->assertEquals(0, $oCalculoTarifaBasica->calcular());
    }

    /**
     * @throws BusinessException
     */
    public function testCalculoTarifaBasicaConsumoVariavel()
    {
        $oCalculoTarifaBasica = new CalculoTarifaBasica;
        $oCalculoTarifaBasica->setEstruturaTarifaria($this->getEstruturasTarifarias()[0]);
        $oCalculoTarifaBasica->setConsumo(20);
        $this->assertEquals(5, $oCalculoTarifaBasica->calcular());
    }

    /**
     * @return array
     */
    private function getEstruturasTarifarias()
    {
        $aEstruturas = [];

        $oEstrutura = new AguaEstruturaTarifaria;
        $oEstrutura->setCodigoTipoEstrutura(AguaEstruturaTarifaria::TIPO_VALOR_FIXO);
        $oEstrutura->setCodigoTipoConsumo(5);
        $oEstrutura->setValor(5);

        $aEstruturas[] = $oEstrutura;

        return $aEstruturas;
    }
}

<?php

namespace Tests\Unit\Tributario\Agua;

use AguaTipoIsencao;
use BusinessException;
use ECidade\Tributario\Agua\Calculo\Isencao\Desconto;
use ECidade\Tributario\Agua\Calculo\Isencao\Imune;
use ECidade\Tributario\Agua\Calculo\Isencao\LeiOrganica;
use ECidade\Tributario\Agua\Calculo\IsencaoFactory;
use Tests\TestCase;

/**
 * Class IsencaoFactoryTest
 * @package Tests\Unit\Tributario\Agua
 */
class IsencaoFactoryTest extends TestCase
{

    /**
     * @throws BusinessException
     */
    public function testDeveRetornarLeiOrganica()
    {
        $oLeiOrganica = IsencaoFactory::getPorTipo(AguaTipoIsencao::TIPO_IDADE);
        $this->assertInstanceOf(LeiOrganica::class, $oLeiOrganica);
    }

    /**
     * @throws BusinessException
     */
    public function testDeveRetornarDesconto()
    {
        $oDesconto = IsencaoFactory::getPorTipo(AguaTipoIsencao::TIPO_DESCONTO);
        $this->assertInstanceOf(Desconto::class, $oDesconto);
    }

    /**
     * @throws BusinessException
     */
    public function testDeveRetornarImune()
    {
        $oIsencao = IsencaoFactory::getPorTipo(AguaTipoIsencao::TIPO_IMUNE);
        $this->assertInstanceOf(Imune::class, $oIsencao);

        $oIsencao = IsencaoFactory::getPorTipo(AguaTipoIsencao::TIPO_NORMAL);
        $this->assertInstanceOf(Imune::class, $oIsencao);
    }

    /**
     * @throws BusinessException
     */
    public function testDeveRetornarErro()
    {
        $this->expectException(BusinessException::class);
        IsencaoFactory::getPorTipo(9999);
    }
}

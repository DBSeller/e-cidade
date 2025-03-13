<?php

namespace Tests\Unit\RecursosHumanos\ESocial\Factory;

use ECidade\RecursosHumanos\ESocial\Factory\SugestaoPreenchimento;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Transformer\S2200;
use Exception;
use Tests\TestCase;

/**
 * Class SugestaoPreenchimentoTest
 * @package Tests
 */
class SugestaoPreenchimentoTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testDeveLevantarExcecaoQuandoPreenchimentoDoFormularioS2200SemMatricula()
    {
        $this->expectException(Exception::class);

        $sugestaoPreenchimento = new SugestaoPreenchimento();
        $sugestaoPreenchimento->porTipo(Tipo::SERVIDOR);
    }

    /**
     *
     * @throws Exception
     */
    public function testDeveRetornarParserDoPreenchimentoDoFormularioS2200()
    {
        $sugestaoPreenchimento = new SugestaoPreenchimento();
        $sugestaoPreenchimento->setMatricula($this->faker->numberBetween(1, 10000));

        $this->assertInstanceOf(S2200::class, $sugestaoPreenchimento->porTipo(Tipo::SERVIDOR));
    }
}

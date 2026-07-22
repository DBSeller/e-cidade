<?php

namespace Tests\Unit\RecursosHumanos\ESocial\Factory;

use ECidade\RecursosHumanos\ESocial\Factory\ESocialPreenchimentoValidatorFactory;
use ECidade\RecursosHumanos\ESocial\Validators\ServidorPreenchimentoValidator;
use Tests\TestCase;

class EsocialPreenchimentoValidatorFactoryTest extends TestCase
{
    public function testDeveRetornarNull()
    {
        $validator = ESocialPreenchimentoValidatorFactory::getByIdentificador('nao_existe');
        $this->assertNull($validator);
    }

    public function testDeveRetornarServidor()
    {
        $validator = ESocialPreenchimentoValidatorFactory::getByIdentificador('s22002190v23');
        $this->assertInstanceOf(ServidorPreenchimentoValidator::class, $validator);
    }
}

<?php

namespace Tests\Unit\RecursosHumanos\Pessoal\Repository;

use ECidade\RecursosHumanos\Pessoal\Repository\DependenteRepository;
use Tests\TestCase;

/**
 * Class DependenteRepositoryTest
 * @package Tests\Unit\RecursosHumanos\Pessoal\Repository
 */
class DependenteRepositoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testDeveAdicionarFiltroPorMatricula()
    {
        $matricula = $this->faker->numberBetween(1, 10000);
        $key = 'rh31_regist';

        $repository = new DependenteRepository();
        $repository->scopeMatricula($matricula);

        $this->assertArrayHasKey($key, $repository->getScopes());
        $this->assertEquals("{$key} = {$matricula}", $repository->getScope($key));
    }
}

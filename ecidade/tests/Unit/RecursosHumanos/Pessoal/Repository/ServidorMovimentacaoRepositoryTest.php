<?php

namespace Tests\Unit\RecursosHumanos\Pessoal\Repository;

use ECidade\RecursosHumanos\Pessoal\Repository\ServidorMovimentacaoRepository;
use Tests\TestCase;

class ServidorMovimentacaoRepositoryTest extends TestCase
{
    public function testDeveAdicionarFiltroPorMatricula()
    {
        $matricula = $this->faker->numberBetween(1, 10000);
        $key = 'rh02_regist';

        $repository = new ServidorMovimentacaoRepository();
        $repository->scopeMatricula($matricula);

        $this->assertArrayHasKey($key, $repository->getScopes());
        $this->assertEquals("{$key} = {$matricula}", $repository->getScope($key));
    }

    public function testDeveAdicionarFiltroPorAno()
    {
        $ano = $this->faker->year;
        $key = 'rh02_anousu';

        $repository = new ServidorMovimentacaoRepository();
        $repository->scopeAno($ano);

        $this->assertArrayHasKey($key, $repository->getScopes());
        $this->assertEquals("{$key} = {$ano}", $repository->getScope($key));
    }

    public function testDeveAdicionarFiltroPorMes()
    {
        $mes = $this->faker->month;
        $key = 'rh02_mesusu';

        $repository = new ServidorMovimentacaoRepository();
        $repository->scopeMes($mes);

        $this->assertArrayHasKey($key, $repository->getScopes());
        $this->assertEquals("{$key} = {$mes}", $repository->getScope($key));
    }
}

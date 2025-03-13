<?php

namespace Tests\Unit\Tributario\Agua\Leitura;

use AguaLeitura;
use ECidade\Tributario\Agua\Entity\Leitura\Situacao;
use ECidade\Tributario\Agua\Leitura\Regra\Media;
use ECidade\Tributario\Agua\Leitura\Regra\Penalidade;
use ECidade\Tributario\Agua\Leitura\RegraFactory;
use ECidade\Tributario\Agua\Leitura\ResumoMensal;
use Exception;
use ParameterException;
use Tests\TestCase;

/**
 * Class RegraFactoryTest
 * @package Tests\Unit\Tributario\Agua\Leitura
 */
class RegraFactoryTest extends TestCase
{
    /**
     *
     */
    public function testDeveInstanciarOFactory()
    {
        $regraFactory = new RegraFactory();
        $this->assertInstanceOf(
            RegraFactory::class,
            $regraFactory
        );
    }

    /**
     * @throws ParameterException
     */
    public function testCreateDeveLancarExcecaoSeNenhumaLeituraFoiInformada()
    {
        $this->expectException(ParameterException::class);
        $regraFactory = new RegraFactory();
        $regraFactory->create([]);
    }

    /**
     * @throws ParameterException
     */
    public function testCreateDeveLancarExcecaoSeForamInformadasInstanciasInvalidas()
    {
        $this->expectException(ParameterException::class);
        $regraFactory = new RegraFactory();
        $regraFactory->create(range(0, 6));
    }

    /**
     * @throws Exception
     */
    public function testCreateDeveRetornarRegraDeMedia()
    {
        $resumosMensais = array_map(function ($iMes) {

            $situacao = new Situacao();
            $situacao->setRegra(Situacao::REGRA_NORMAL);

            $leitura = new AguaLeitura();
            $leitura->setSituacaoLeitura($situacao);

            $resumoMensal = new ResumoMensal($iMes, 2017);
            $resumoMensal->adicionarLeitura($leitura);

            return $resumoMensal;
        }, range(1, 7));

        $situacao = new Situacao();
        $situacao->setRegra(Situacao::REGRA_SEM_LEITURA_SEM_SALDO);

        $leitura = new AguaLeitura();
        $leitura->setSituacaoLeitura($situacao);

        $resumoMensal = new ResumoMensal(7, 2017);
        $resumoMensal->adicionarLeitura($leitura);
        $resumosMensais[0] = $resumoMensal;

        $regraFactory = new RegraFactory();
        $regra = $regraFactory->create($resumosMensais);

        $this->assertInstanceOf(Media::class, $regra);
    }

    /**
     * @throws Exception
     */
    public function testCreateDeveRetornarRegraDePenalidade()
    {
        $resumosMensais = array_map(function ($iMes) {
            $situacao = new Situacao();
            $situacao->setRegra(Situacao::REGRA_MEDIA_ULTIMOS_MESES);

            $leitura = new AguaLeitura();
            $leitura->setSituacaoLeitura($situacao);

            $resumoMensal = new ResumoMensal($iMes, 2017);
            $resumoMensal->adicionarLeitura($leitura);

            return $resumoMensal;
        }, range(1, 7));

        $situacao = new Situacao();
        $situacao->setRegra(Situacao::REGRA_NORMAL);

        $leitura = new AguaLeitura();
        $leitura->setSituacaoLeitura($situacao);

        $resumoMensal = new ResumoMensal(7, 2017);
        $resumoMensal->adicionarLeitura($leitura);
        $resumosMensais[0] = $resumoMensal;

        $regraFactory = new RegraFactory();
        $regra = $regraFactory->create($resumosMensais);

        $this->assertInstanceOf(Penalidade::class, $regra);
    }

    /**
     * @throws ParameterException
     */
    public function testCreateDeveRetornarNull()
    {
        $resumosMensais = array_map(function ($iMes) {
            $situacao = new Situacao();
            $situacao->setRegra(Situacao::REGRA_NORMAL);

            $leitura = new AguaLeitura();
            $leitura->setSituacaoLeitura($situacao);

            $resumoMensal = new ResumoMensal($iMes, 2017);
            $resumoMensal->adicionarLeitura($leitura);

            return $resumoMensal;
        }, range(1, 7));

        $regraFactory = new RegraFactory();
        $regra = $regraFactory->create($resumosMensais);

        $this->assertEquals(null, $regra);
    }

    /**
     * @throws Exception
     */
    public function testCreateComUmaLeituraSemColetaDeveRetornarRegraMedia()
    {
        $situacao = new Situacao();
        $situacao->setRegra(Situacao::REGRA_SEM_LEITURA_SEM_SALDO);

        $leitura = new AguaLeitura();
        $leitura->setSituacaoLeitura($situacao);

        $resumoMensal = new ResumoMensal(1, 2017);
        $resumoMensal->adicionarLeitura($leitura);

        $regraFactory = new RegraFactory();
        $regra = $regraFactory->create([$resumoMensal]);

        $this->assertInstanceOf(Media::class, $regra);
    }

    /**
     * @throws Exception
     */
    public function testCreateComUmaLeituraComColetaDeveRetornarNull()
    {
        $situacao = new Situacao();
        $situacao->setRegra(Situacao::REGRA_NORMAL);

        $leitura = new AguaLeitura();
        $leitura->setSituacaoLeitura($situacao);

        $resumoMensal = new ResumoMensal(1, 2017);
        $resumoMensal->adicionarLeitura($leitura);

        $regraFactory = new RegraFactory();
        $regra = $regraFactory->create([$resumoMensal]);

        $this->assertEquals(null, $regra);
    }

    /**
     * @throws Exception
     */
    public function testCreateComDuasLeituraComColetaDeveRetornarNull()
    {
        $situacao = new Situacao();
        $situacao->setRegra(Situacao::REGRA_NORMAL);

        $leitura = new AguaLeitura();
        $leitura->setSituacaoLeitura($situacao);

        $resumoMensalJaneiro = new ResumoMensal(1, 2017);
        $resumoMensalJaneiro->adicionarLeitura($leitura);

        $resumoMensalFevereiro = new ResumoMensal(2, 2017);
        $resumoMensalFevereiro->adicionarLeitura($leitura);

        $regraFactory = new RegraFactory();
        $regra = $regraFactory->create([$resumoMensalJaneiro, $resumoMensalFevereiro]);

        $this->assertEquals(null, $regra);
    }

    /**
     * @throws Exception
     */
    public function testCreateLeiturasComUmaMediaEntreNormaisDeveRetornarRegraPenalidade()
    {
        $resumosMensais = array_map(function ($iMes) {
            $situacao = new Situacao();
            $situacao->setRegra(Situacao::REGRA_MEDIA_ULTIMOS_MESES);

            $leitura = new AguaLeitura();
            $leitura->setSituacaoLeitura($situacao);

            $resumoMensal = new ResumoMensal($iMes, 2017);
            $resumoMensal->adicionarLeitura($leitura);

            return $resumoMensal;
        }, range(1, 3));

        $situacao = new Situacao();
        $situacao->setRegra(Situacao::REGRA_NORMAL);

        $leitura = new AguaLeitura();
        $leitura->setSituacaoLeitura($situacao);

        $resumoMensal = new ResumoMensal(4, 2017);
        $resumoMensal->adicionarLeitura($leitura);
        $resumosMensais[count($resumosMensais) - 1] = $resumoMensal;

        $leitura = new AguaLeitura();
        $leitura->setSituacaoLeitura($situacao);

        $resumoMensal = new ResumoMensal(1, 2017);
        $resumoMensal->adicionarLeitura($leitura);
        $resumosMensais[0] = $resumoMensal;

        $regraFactory = new RegraFactory();
        $regra = $regraFactory->create($resumosMensais);

        $this->assertInstanceOf(Penalidade::class, $regra);
    }
}

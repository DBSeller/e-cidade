<?php

namespace Tests\Unit\Tributario\Agua\Processamento;

use AguaCategoriaConsumo;
use AguaEstruturaTarifaria;
use ECidade\Tributario\Agua\Calculo\Isencao\Desconto;
use ECidade\Tributario\Agua\Calculo\Isencao\Imune;
use ECidade\Tributario\Agua\Calculo\Processamento\Economia as Processamento;
use Tests\TestCase;

/**
 * Class EconomiaTest
 * @package Tests\Unit\Tributario\Agua\Processamento
 */
class EconomiaTest extends TestCase
{
    /**
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function testProcessarCalculoComValorFixo()
    {
        $oProcessamento = new Processamento;
        $oEstruturaTarifaria = new AguaEstruturaTarifaria;
        $oEstruturaTarifaria->setCodigoTipoEstrutura(AguaEstruturaTarifaria::TIPO_VALOR_FIXO);
        $oEstruturaTarifaria->setCodigoTipoConsumo(1);
        $oEstruturaTarifaria->setValor(5.55);

        $oCategoria = new AguaCategoriaConsumo;
        $oCategoria->adicionarEstrutura($oEstruturaTarifaria);
        $oProcessamento->setCategoriaConsumo($oCategoria);
        $oProcessamento->processar();

        $oResultados = $oProcessamento->getResultadoCollection();
        $aResultados = $oResultados->getPorTipoConsumo();

        $this->assertContains(5.55, $aResultados);
        $this->assertEquals(5.55, $aResultados[1]);
    }

    /**
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function testProcessarCalculoComFaixaConsumo()
    {
        $oProcessamento = new Processamento;

        $oEstruturaTarifaria = new AguaEstruturaTarifaria;
        $oEstruturaTarifaria->setCodigo(1);
        $oEstruturaTarifaria->setCodigoTipoEstrutura(AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO);
        $oEstruturaTarifaria->setCodigoTipoConsumo(1);
        $oEstruturaTarifaria->setValorInicial(null);
        $oEstruturaTarifaria->setValorFinal(15);
        $oEstruturaTarifaria->setValor(1.50);

        $oCategoria = new AguaCategoriaConsumo;
        $oCategoria->adicionarEstrutura($oEstruturaTarifaria);
        $oProcessamento->setCategoriaConsumo($oCategoria);
        $oProcessamento->setConsumo(10);
        $oProcessamento->processar();

        $oResultados = $oProcessamento->getResultadoCollection();
        $aResultados = $oResultados->getPorTipoConsumo();

        $this->assertContains(15, $aResultados);
        $this->assertEquals(15, $aResultados[1]);
    }

    /**
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function testProcessarCalculoComPercentual()
    {
        $oProcessamento = new Processamento;

        $oEstruturaTarifariaFaixa = new AguaEstruturaTarifaria;
        $oEstruturaTarifariaFaixa->setCodigo(1);
        $oEstruturaTarifariaFaixa->setCodigoTipoEstrutura(AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO);
        $oEstruturaTarifariaFaixa->setCodigoTipoConsumo(1);
        $oEstruturaTarifariaFaixa->setValorInicial(null);
        $oEstruturaTarifariaFaixa->setValorFinal(15);
        $oEstruturaTarifariaFaixa->setValor(1);

        $oEstruturaTarifariaPercentual = new AguaEstruturaTarifaria;
        $oEstruturaTarifariaPercentual->setCodigo(2);
        $oEstruturaTarifariaPercentual->setCodigoTipoEstrutura(AguaEstruturaTarifaria::TIPO_PERCENTUAL);
        $oEstruturaTarifariaPercentual->setCodigoTipoConsumo(1);
        $oEstruturaTarifariaPercentual->setPercentual(50);

        $oCategoria = new AguaCategoriaConsumo;
        $oCategoria->adicionarEstrutura($oEstruturaTarifariaPercentual);
        $oCategoria->adicionarEstrutura($oEstruturaTarifariaFaixa);

        $oProcessamento->setCategoriaConsumo($oCategoria);
        $oProcessamento->setConsumo(10);
        $oProcessamento->processar();

        $oResultados = $oProcessamento->getResultadoCollection();
        $aResultados = $oResultados->getPorTipoConsumo();

        $this->assertContains(15, $aResultados);
        $this->assertEquals(15, $aResultados[1]);
    }

    /**
     * @return array
     */
    public function calculoCompletoProvider()
    {
        return [
            [10, 14.37],
            [15, 19.06],
            [20, 26.56],
            [80, 212.81],
        ];
    }

    /**
     * @dataProvider calculoCompletoProvider
     */
    public function testProcessarCalculoCompleto($iConsumo, $nValorEsperado)
    {
        $oProcessamento = new Processamento;
        $oCategoria = new AguaCategoriaConsumo;

        foreach ($this->getEstruturasTarifarias() as $oEstruturaTarifaria) {
            $oCategoria->adicionarEstrutura($oEstruturaTarifaria);
        }

        $oProcessamento->setCategoriaConsumo($oCategoria);
        $oProcessamento->setConsumo($iConsumo);
        $oProcessamento->processar();

        $oResultados = $oProcessamento->getResultadoCollection();

        $this->assertEquals($nValorEsperado, $oResultados->getTotal());
    }

    /**
     * @return array
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    private function getEstruturasTarifarias()
    {
        $iFaixaConsumo = AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO;
        $iValorFixo = AguaEstruturaTarifaria::TIPO_VALOR_FIXO;
        $iPercentual = AguaEstruturaTarifaria::TIPO_PERCENTUAL;

        $aCategoriaConsumo = [

            // Residencial social: faixas
            [
                'inicial' => null,
                'final' => 15,
                'valor' => 0.75,
                'tipo' => $iFaixaConsumo,
                'percentual' => null,
                'tipoConsumo' => 1,
            ],
            [
                'inicial' => 16,
                'final' => 25,
                'valor' => 1.20,
                'tipo' => $iFaixaConsumo,
                'percentual' => null,
                'tipoConsumo' => 1,
            ],
            [
                'inicial' => 26,
                'final' => 35,
                'valor' => 1.80,
                'tipo' => $iFaixaConsumo,
                'percentual' => null,
                'tipoConsumo' => 1,
            ],
            [
                'inicial' => 36,
                'final' => 45,
                'valor' => 2.20,
                'tipo' => $iFaixaConsumo,
                'percentual' => null,
                'tipoConsumo' => 1,
            ],
            [
                'inicial' => 46,
                'final' => 55,
                'valor' => 2.80,
                'tipo' => $iFaixaConsumo,
                'percentual' => null,
                'tipoConsumo' => 1,
            ],
            [
                'inicial' => 56,
                'final' => null,
                'valor' => 3.00,
                'tipo' => $iFaixaConsumo,
                'percentual' => null,
                'tipoConsumo' => 1,
            ],

            // Residencial social: tarifa básica água
            [
                'inicial' => null,
                'final' => null,
                'valor' => 4.00,
                'tipo' => $iValorFixo,
                'percentual' => null,
                'tipoConsumo' => 2,
            ],

            // Residencial social: tarifa básica esgoto
            [
                'inicial' => null,
                'final' => null,
                'valor' => 1.00,
                'tipo' => $iValorFixo,
                'percentual' => null,
                'tipoConsumo' => 2,
            ],

            // Residencial social: percentual esgoto
            [
                'inicial' => null,
                'final' => null,
                'valor' => null,
                'tipo' => $iPercentual,
                'percentual' => 25,
                'tipoConsumo' => 3,
            ],
        ];

        $aEstruturasTarifarias = [];
        $iCodigo = 1;
        $iOrdem = 1;

        foreach ($aCategoriaConsumo as $aEstrutura) {
            $oEstruturaTarifaria = new AguaEstruturaTarifaria;
            $oEstruturaTarifaria->setCodigo($iCodigo);
            $oEstruturaTarifaria->setCodigoTipoEstrutura($aEstrutura['tipo']);

            if ($aEstrutura['tipo'] == AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO) {
                $oEstruturaTarifaria->setOrdem($iOrdem);
                $iOrdem++;
            }

            $oEstruturaTarifaria->setCodigoTipoConsumo($aEstrutura['tipoConsumo']);
            $oEstruturaTarifaria->setValorInicial($aEstrutura['inicial']);
            $oEstruturaTarifaria->setValorFinal($aEstrutura['final']);
            $oEstruturaTarifaria->setValor($aEstrutura['valor']);
            $oEstruturaTarifaria->setPercentual($aEstrutura['percentual']);
            $aEstruturasTarifarias[] = $oEstruturaTarifaria;
            $iCodigo++;
        }

        return $aEstruturasTarifarias;
    }

    /**
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function testDeveRetornarResultadosPorFaixaDeConsumo()
    {
        $oProcessamento = new Processamento;
        $oCategoria = new AguaCategoriaConsumo;

        foreach ($this->getEstruturasTarifarias() as $oEstruturaTarifaria) {
            $oCategoria->adicionarEstrutura($oEstruturaTarifaria);
        }

        $oProcessamento->setCategoriaConsumo($oCategoria);
        $oProcessamento->setConsumo(80);
        $oProcessamento->processar();

        $oResultados = $oProcessamento->getResultadoCollection();
        $aResultadosFaixaConsumo = $oResultados->getPorFaixaConsumo();

        $this->assertEquals(11.25, $aResultadosFaixaConsumo[1]);
        $this->assertEquals(12.00, $aResultadosFaixaConsumo[2]);
        $this->assertEquals(18.00, $aResultadosFaixaConsumo[3]);
        $this->assertEquals(22.00, $aResultadosFaixaConsumo[4]);
        $this->assertEquals(28.00, $aResultadosFaixaConsumo[5]);
        $this->assertEquals(75.00, $aResultadosFaixaConsumo[6]);
    }

    /**
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function testDeveAplicarIsencao()
    {
        $oDesconto = $this->getMockBuilder(Desconto::class)
            ->getMock();
        $oDesconto->method('calcular')
            ->willReturn(10);

        $oProcessamento = new Processamento;
        $oCategoria = new AguaCategoriaConsumo;
        foreach ($this->getEstruturasTarifarias() as $oEstruturaTarifaria) {
            $oCategoria->adicionarEstrutura($oEstruturaTarifaria);
        }
        $oProcessamento->setCategoriaConsumo($oCategoria);
        $oProcessamento->setConsumo(80);
        $oProcessamento->setIsencao($oDesconto);
        $oProcessamento->setCodigoTipoConsumoIsencao(1);
        $oProcessamento->processar();

        $oResultados = $oProcessamento->getResultadoCollection();

        // Consumo = 166,25 (consumo) - 10 (desconto) = 156,25
        // Esgoto  = 156,25 * 0.25 = 39,06
        // Tarifas = 4 água + 1 esgoto = 5
        // Total   = 156,25 + 39,06 + 5 = 200.31
        $this->assertEquals(200.31, $oResultados->getTotal());
    }

    /**
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function testDeveAplicarIsencaoImune()
    {

        $oDesconto = new Imune;

        $oProcessamento = new Processamento;
        $oCategoria = new AguaCategoriaConsumo;
        foreach ($this->getEstruturasTarifarias() as $oEstruturaTarifaria) {
            $oCategoria->adicionarEstrutura($oEstruturaTarifaria);
        }
        $oProcessamento->setCategoriaConsumo($oCategoria);
        $oProcessamento->setConsumo(80);
        $oProcessamento->setIsencao($oDesconto);
        $oProcessamento->setCodigoTipoConsumoIsencao(1);
        $oProcessamento->processar();

        $oResultados = $oProcessamento->getResultadoCollection();
        $this->assertEquals(0, $oResultados->getTotal());
    }
}

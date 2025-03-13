<?php

namespace Tests\Unit\Tributario\Agua\Processamento;

use AguaCategoriaConsumo;
use AguaContratoEconomia;
use AguaEstruturaTarifaria;
use ECidade\Tributario\Agua\Calculo\Processamento\Condominio;
use ECidade\Tributario\Agua\Repository\Isencao;
use Tests\TestCase;

/**
 * Class CondominioTest
 * @package Tests\Unit\Tributario\Agua\Processamento
 */
class CondominioTest extends TestCase
{
    /**
     *
     */
    public function testAdicionarEconomias()
    {
        $oEconomia1 = new AguaContratoEconomia;
        $oEconomia1->setCodigo(1);

        $oEconomia2 = new AguaContratoEconomia;
        $oEconomia2->setCodigo(2);

        $aEconomias = [$oEconomia1, $oEconomia2];

        $oProcessamento = new Condominio;
        $oProcessamento->adicionarEconomia($oEconomia1);
        $oProcessamento->adicionarEconomia($oEconomia2);

        $this->assertEquals($aEconomias, $oProcessamento->getEconomias());
    }

    /**
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function testCalcularConsumoComDuasEconomiasNaCategoriaResidencialSocial()
    {
        $oIsencaoRepository = $this->getMockBuilder(Isencao::class)->getMock();

        $oEconomia1 = new AguaContratoEconomia;
        $oEconomia1->setCodigo(1);
        $oEconomia1->setCodigoCgm(1);
        $oEconomia1->setCategoriaConsumo($this->getCategoriaConsumoResidencialSocial());
        $oEconomia1->setIsencaoRepository($oIsencaoRepository);

        $oEconomia2 = new AguaContratoEconomia;
        $oEconomia2->setCodigo(2);
        $oEconomia2->setCodigoCgm(2);
        $oEconomia2->setCategoriaConsumo($this->getCategoriaConsumoResidencial());
        $oEconomia2->setIsencaoRepository($oIsencaoRepository);

        $oProcessamento = new Condominio;
        $oProcessamento->adicionarEconomia($oEconomia1);
        $oProcessamento->adicionarEconomia($oEconomia2);
        $oProcessamento->setConsumo(30);

        $aResultados = $oProcessamento->processar();

        $nTotal = 0;
        foreach ($aResultados as $aResultado) {
            $nTotal += $aResultado['resultado']->getTotal();
        }

        $this->assertEquals(19.06 + 78.00, $nTotal);
    }

    /**
     * @return AguaCategoriaConsumo
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    private function getCategoriaConsumoResidencialSocial()
    {

        $oCategoriaConsumo = new AguaCategoriaConsumo;
        $oCategoriaConsumo->setCodigo(2);
        $oCategoriaConsumo->setDescricao('Residencial');
        $oCategoriaConsumo->setExercicio(2017);

        foreach ($this->getEstruturasTarifarias() as $oEstruturaTarifaria) {
            $oCategoriaConsumo->adicionarEstrutura($oEstruturaTarifaria);
        }

        return $oCategoriaConsumo;
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
                'tipoConsumo' => 3,
            ],

            // Residencial social: percentual esgoto
            [
                'inicial' => null,
                'final' => null,
                'valor' => null,
                'tipo' => $iPercentual,
                'percentual' => 25,
                'tipoConsumo' => 4,
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

            $oEstruturaTarifaria->setCodigoTipoConsumo(1);
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
     * @return AguaCategoriaConsumo
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    private function getCategoriaConsumoResidencial()
    {
        $oCategoriaConsumo = new AguaCategoriaConsumo;
        $oCategoriaConsumo->setCodigo(1);
        $oCategoriaConsumo->getDescricao('Residencial Social');
        $oCategoriaConsumo->setExercicio(2017);

        $iFaixaConsumo = AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO;
        $iValorFixo = AguaEstruturaTarifaria::TIPO_VALOR_FIXO;
        $iPercentual = AguaEstruturaTarifaria::TIPO_PERCENTUAL;

        $aEstruturas = [
            [
                'inicial' => null,
                'final' => 10,
                'valor' => 2.0,
                'tipo_consumo' => 1,
                'tipo' => $iFaixaConsumo,
                'percentual' => false
            ],
            [
                'inicial' => 11,
                'final' => 20,
                'valor' => 2.4,
                'tipo_consumo' => 1,
                'tipo' => $iFaixaConsumo,
                'percentual' => false
            ],
            [
                'inicial' => 21,
                'final' => 30,
                'valor' => 3.2,
                'tipo_consumo' => 1,
                'tipo' => $iFaixaConsumo,
                'percentual' => false
            ],
            [
                'inicial' => 31,
                'final' => 40,
                'valor' => 3.8,
                'tipo_consumo' => 1,
                'tipo' => $iFaixaConsumo,
                'percentual' => false
            ],
            [
                'inicial' => 41,
                'final' => 50,
                'valor' => 4.2,
                'tipo_consumo' => 1,
                'tipo' => $iFaixaConsumo,
                'percentual' => false
            ],
            [
                'inicial' => 51,
                'final' => null,
                'valor' => 5.24,
                'tipo_consumo' => 1,
                'tipo' => $iFaixaConsumo,
                'percentual' => false
            ],

            [
                'inicial' => null,
                'final' => null,
                'valor' => 25.0,
                'tipo_consumo' => 2,
                'tipo' => $iValorFixo,
                'percentual' => false
            ],
            [
                'inicial' => null,
                'final' => null,
                'valor' => 5.0,
                'tipo_consumo' => 3,
                'tipo' => $iValorFixo,
                'percentual' => false
            ],

            [
                'inicial' => null,
                'final' => null,
                'valor' => null,
                'tipo_consumo' => 4,
                'tipo' => $iPercentual,
                'percentual' => 50
            ],
        ];

        $iCodigo = 10;
        $iOrdem = 1;

        foreach ($aEstruturas as $aEstrutura) {
            $oEstruturaTarifaria = new AguaEstruturaTarifaria;
            $oEstruturaTarifaria->setCodigo($iCodigo);
            $oEstruturaTarifaria->setCodigoTipoEstrutura($aEstrutura['tipo']);

            if ($aEstrutura['tipo'] == AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO) {
                $oEstruturaTarifaria->setOrdem($iOrdem);
                $iOrdem++;
            }

            $oEstruturaTarifaria->setCodigoTipoConsumo($aEstrutura['tipo_consumo']);
            $oEstruturaTarifaria->setValorInicial($aEstrutura['inicial']);
            $oEstruturaTarifaria->setValorFinal($aEstrutura['final']);
            $oEstruturaTarifaria->setValor($aEstrutura['valor']);
            $oEstruturaTarifaria->setPercentual($aEstrutura['percentual']);

            $oCategoriaConsumo->adicionarEstrutura($oEstruturaTarifaria);
            $iCodigo++;
        }

        return $oCategoriaConsumo;
    }
}

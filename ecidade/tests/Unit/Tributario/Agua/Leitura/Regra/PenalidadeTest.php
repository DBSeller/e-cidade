<?php

namespace Tests\Unit\Tributario\Agua\Leitura\Regra;

use AguaLeitura;
use ECidade\Tributario\Agua\Entity\Leitura\Situacao;
use ECidade\Tributario\Agua\Leitura\Regra\Penalidade;
use ECidade\Tributario\Agua\Leitura\ResumoMensal;
use Exception;
use ParameterException;
use stdClass;
use Tests\TestCase;

class PenalidadeTest extends TestCase
{
    /**
     * Deve instanciar a penalidade
     */
    public function testDeveInstanciarAPenalidade()
    {
        $penalidade = new Penalidade(array());
        $this->assertInstanceOf(Penalidade::class, $penalidade);
    }

    /**
     * Calcular Deve lancar excecao se nenhuma leitura foi informada
     */
    public function testCalcularDeveLancarExcecaoSeNenhumaLeituraFoiInformada()
    {
        $this->expectException(ParameterException::class);
        $penalidade = new Penalidade([]);
        $penalidade->calcular();
    }

    /**
     * Calcular deve lancar excecao se foram informadas instancias invalidas
     */
    public function testCalcularDeveLancarExcecaoSeForamInformadasInstanciasInvalidas()
    {
        $this->expectException(ParameterException::class);
        $penalidade = new Penalidade(range(0, 6));
        $penalidade->calcular();
    }

    /**
     * calcular deve lancar excecao se foram informadas instancias de AguaLeitura
     */
    public function testCalcularDeveLancarExcecaoSeForamInformadasInstanciasDeAgualeitura()
    {
        $this->expectException(ParameterException::class);

        $leituras = array_map(function () {
            $situacao = new Situacao();
            $situacao->setRegra(Situacao::REGRA_NORMAL);

            $leitura = new AguaLeitura();
            $leitura->setSituacaoLeitura($situacao);

            return $leitura;
        }, range(0, 6));

        $penalidade = new Penalidade($leituras);
        $penalidade->calcular();
    }

    /**
     * Calcular deve retornar uma media por consumo alem do ja cobrado
     * L1                                 -> Leitura 1
     * L2                                 -> Leitura 2
     * M1                                 -> Media 1
     * M2...                              -> Media 2 e seguintes até a leitura 2
     * MEC = (L2 - L1)
     * MJC = (M1 + M2 + M*)
     * MJA = (MEC - MJC)                  -> Resultado final do calculo
     */
    public function testCalcularDeveRetornarUmaMediaPorConsumoAlemDoJaCobrado()
    {
        $resumosMensais = array();

        /* Leituras 5/2016 */
        $leitura = new stdClass();
        $leitura->leitura = 100;
        $leitura->consumo = 10;
        $leitura->regra = Situacao::REGRA_NORMAL;
        $resumoMensal = $this->criaLeitura(5, 2016, array($leitura));
        $resumosMensais[] = $resumoMensal;

        /* Leituras 6/2016 */
        $leitura = new stdClass();
        $leitura->leitura = 100;
        $leitura->consumo = 10;
        $leitura->regra = Situacao::REGRA_MEDIA_ULTIMOS_MESES;
        $resumoMensal = $this->criaLeitura(6, 2016, array($leitura));
        $resumosMensais[] = $resumoMensal;


        /* Leituras 7/2016 */
        $leitura = new stdClass();
        $leitura->leitura = 150;
        $leitura->consumo = 5;
        $leitura->regra = Situacao::REGRA_NORMAL;
        $resumoMensal = $this->criaLeitura(7, 2016, array($leitura));
        $resumosMensais[] = $resumoMensal;

        $resumosMensais = array_reverse($resumosMensais);

        $penalidade = new Penalidade($resumosMensais);
        $consumo = $penalidade->calcular();
        $this->assertEquals(40, $consumo);
    }

    /**
     * Cria um Resumo de Leituras para o mes/ano
     * @param integer $mes Mes
     * @param integer $ano Ano
     * @param array $leituras Array de leituras a serem criadas para o mes
     * @return ResumoMensal      Instancia de ResumoMensal
     * @throws Exception
     */
    private function criaLeitura($mes, $ano, $leituras)
    {
        $resumoMensal = new ResumoMensal($mes, $ano);

        foreach ($leituras as $oLeitura) {
            $leitura = new AguaLeitura();
            $leitura->setLeitura($oLeitura->leitura);
            $leitura->setConsumo($oLeitura->consumo);
            $leitura->setMes($mes);
            $leitura->setAno($ano);
            $leitura->setSituacaoLeitura($this->criaSituacao($oLeitura->regra));
            $resumoMensal->adicionarLeitura($leitura);
        }

        return $resumoMensal;
    }

    /**
     * Cria uma situacao para a leitura
     * @param integer $regra const de Siatuacao
     * @return Situacao       Instancia de Situacao
     */
    private function criaSituacao($regra)
    {
        $situacao = new Situacao();
        $situacao->setRegra($regra);
        return $situacao;
    }

    /**
     * Calcular deve retornar uma media por consumo menos do que ja cobrado
     * L1                                 -> Leitura 1
     * L2                                 -> Leitura 2
     * M1                                 -> Media 1
     * M2...                              -> Media 2 e seguintes até a leitura 2
     * MEC = (L2 - L1)
     * MJC = (M1 + M2 + M*)
     * MJA = (MEC - MJC) -> Se <= 0
     * MCC = (MJC)/ QUANTIDADE DE MEDIAS  -> Resultado final do calculo
     */
    public function testCalcularDeveRetornarUmaMediaPorConsumoMenosDoQueJaCobrado()
    {
        $resumosMensais = array();

        /* Leituras 5/2016 */
        $leitura = new stdClass();
        $leitura->leitura = 100;
        $leitura->consumo = 10;
        $leitura->regra = Situacao::REGRA_NORMAL;
        $resumoMensal = $this->criaLeitura(5, 2016, array($leitura));
        $resumosMensais[] = $resumoMensal;


        /* Leituras 6/2016 */
        $leitura = new stdClass();
        $leitura->leitura = 100;
        $leitura->consumo = 10;
        $leitura->regra = Situacao::REGRA_MEDIA_ULTIMOS_MESES;
        $resumoMensal = $this->criaLeitura(6, 2016, array($leitura));
        $resumosMensais[] = $resumoMensal;

        /* Leituras 7/2016 */
        $leitura = new stdClass();
        $leitura->leitura = 100;
        $leitura->consumo = 10;
        $leitura->regra = Situacao::REGRA_MEDIA_ULTIMOS_MESES;
        $resumoMensal = $this->criaLeitura(7, 2016, array($leitura));
        $resumosMensais[] = $resumoMensal;

        /* Leituras 8/2016 */
        $leitura = new stdClass();
        $leitura->leitura = 100;
        $leitura->consumo = 10;
        $leitura->regra = Situacao::REGRA_MEDIA_ULTIMOS_MESES;
        $resumoMensal = $this->criaLeitura(8, 2016, array($leitura));
        $resumosMensais[] = $resumoMensal;

        /* Leituras 9/2016 */
        $leitura = new stdClass();
        $leitura->leitura = 100;
        $leitura->consumo = 10;
        $leitura->regra = Situacao::REGRA_MEDIA_ULTIMOS_MESES;
        $resumoMensal = $this->criaLeitura(9, 2016, array($leitura));
        $resumosMensais[] = $resumoMensal;

        /* Leituras 10/2016 */
        $leitura = new stdClass();
        $leitura->leitura = 150;
        $leitura->consumo = 5;
        $leitura->regra = Situacao::REGRA_NORMAL;
        $resumoMensal = $this->criaLeitura(10, 2016, array($leitura));
        $resumosMensais[] = $resumoMensal;

        $resumosMensais = array_reverse($resumosMensais);

        $penalidade = new Penalidade($resumosMensais);
        $consumo = $penalidade->calcular();
        $this->assertEquals(10, $consumo);
    }
}

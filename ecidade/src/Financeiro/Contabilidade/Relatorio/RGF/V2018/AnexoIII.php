<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018;

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\ProcessamentoRelatorioLegal;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Linha;
use ECidade\Financeiro\Contabilidade\Calculo\ReceitaCorrenteLiquida;
use \relatorioContabil;
use \cl_assinatura;

class AnexoIII extends ProcessamentoRelatorioLegal
{
    /**
     * Código Padrão do Relatório
     * @var integer
     */
    const CODIGO_RELATORIO = 184;
    /**
     * @var int
     */
    protected $ano;

    /**
     * @var \Instituicao[]
     */
    protected $instituicoes;

    /**
     * @var ReceitaCorrenteLiquida
     */
    protected $rcl;

    protected $relatorioProcessado = false;

    /**
     * @var array
     */
    protected $periodos = array(
        12 => array(12),
        13 => array(12, 13),
        14 => array(14),
        15 => array(14, 15),
        16 => array(14, 15, 16)
    );

    /**
     * @var array
     */
    protected $aColunaRecalcularPeriodo = array(
        13 => array(array("coluna" => 1, "periodo" => "12")),
        15 => array(array("coluna" => 1, "periodo" => "14")),
        16 => array(array("coluna" => 1, "periodo" => "14"), array("coluna" => 2, "periodo" => "15")),
    );

    /**
     * AnexoIII constructor.
     * @param $ano
     * @param \Periodo $oPeriodo
     */
    public function __construct($ano, \Periodo $oPeriodo)
    {
        $this->ano = $ano;
        $this->instituicoes = \InstituicaoRepository::getInstituicoes();

        $instituicoesRCL = array();

        foreach ($this->instituicoes as $instituicao) {
            if ($instituicao->getTipo() != \Instituicao::TIPO_CAMARA) {
                $instituicoesRCL[] = $instituicao;
            }
        }

        $this->rcl = new ReceitaCorrenteLiquida($ano, $instituicoesRCL, 178);
        parent::__construct($ano, $oPeriodo, self::CODIGO_RELATORIO, $this->instituicoes);
    }

    /**
     * @return Linha[]
     */
    public function getDadosProcessados()
    {
        $oLinha = new Linha();
        $oLinha->informaMetodo("cabecalhoGarantiasConcedidas");
        $this->aLinhasProcessadas[] = $oLinha;

        $this->getDados();
        $this->adicionarLinhasConsistidasNoLayout($this->aLinhasConsistencia);
        return $this->aLinhasProcessadas;
    }

    /**
     * @return array
     */
    public function getDados($trazerConfiguracaoPadrao = true)
    {
        if (!$this->relatorioProcessado) {
            parent::getDados($trazerConfiguracaoPadrao);
            if ($this->getPeriodo()->getCodigo() != 14 && $this->getPeriodo()->getCodigo() != 12) {
                $this->recalcularSaldoExercicio();
            }

            if ($this->getPeriodo()->getCodigo() != 16 && $this->getPeriodo()->getCodigo() != 13) {
                $this->zerarTodasLinhas();
            }
            $this->calcularRCL();
            $this->processarFormulasLinhasTotalizadoras();
            $this->calcularTotalGarantiasRCL();
            $this->calcularLimiteResolucaoSenado();
            $this->calcularLimiteAlerta();
            $this->relatorioProcessado = true;
        }
    }



    /**
     * @param \PDFDocument $pdf
     */
    public function cabecalhoGarantiasConcedidas(\PDFDocument $pdf)
    {
        $alt = 4;
        $pdf->setfont('arial', 'b', 7);
        $pdf->cell(
            110,
            $alt,
            " RGF - ANEXO 3 (LRF, art. 55, inciso I, alínea \"c\" e art. 40, § 1º)",
            'B',
            0,
            "L",
            0
        );
        $pdf->cell(75, $alt, 'R$ 1,00', 'B', 1, "R", 0);
        $pdf->cell(73, $alt, "", 'R', 0, "C", 0);
        $pdf->cell(28, $alt, "SALDO DO", 'R', 0, "C", 0);
        $pdf->cell(84, $alt, "SALDO DO EXERCÍCIO  DE " . $this->ano, 'B', 1, "C", 0);
        $pdf->cell(73, $alt, "GARANTIAS CONCEDIDAS", 'BR', 0, "C", 0);
        $pdf->cell(28, $alt, "EXERCÍCIO ANTERIOR", 'RB', 0, "C", 0);

        if (in_array($this->oPeriodo->getCodigo(), array(12, 13))) {
            $pdf->cell(42, $alt, "Até o 1º Semestre", 'BR', 0, "C", 0);
            $pdf->cell(42, $alt, "Até o 2º Semestre", 'B', 1, "C", 0);
        } else {
            $pdf->cell(28, $alt, "Até o 1º Quadrimestre", 'BR', 0, "C", 0);
            $pdf->cell(28, $alt, "Até o 2º Quadrimestre", 'BR', 0, "C", 0);
            $pdf->cell(28, $alt, "Até o 3º Quadrimestre", 'B', 1, "C", 0);
        }
        $pdf->setfont('arial', '', 6);
    }

    /**
     * @param \PDFDocument $pdf
     */
    public function cabecalhoContragarantiasConcedidas(\PDFDocument $pdf)
    {
        $alt = 4;
        $pdf->ln();
        $pdf->ln();
        $pdf->setfont('arial', 'b', 7);
        $pdf->cell(73, $alt, "", 'TR', 0, "C", 0);
        $pdf->cell(28, $alt, "SALDO DO", 'TR', 0, "C", 0);
        $pdf->cell(84, $alt, "SALDO DO EXERCÍCIO  DE " . $this->ano, 'TB', 1, "C", 0);
        $pdf->cell(73, $alt, "CONTRAGARANTIAS RECEBIDAS", 'BR', 0, "C", 0);
        $pdf->cell(28, $alt, "EXERCÍCIO ANTERIOR", 'RB', 0, "C", 0);

        if (in_array($this->oPeriodo->getCodigo(), array(12, 13))) {
            $pdf->cell(42, $alt, "Até o 1º Semestre", 'BR', 0, "C", 0);
            $pdf->cell(42, $alt, "Até o 2º Semestre", 'B', 1, "C", 0);
        } else {
            $pdf->cell(28, $alt, "Até o 1º Quadrimestre", 'BR', 0, "C", 0);
            $pdf->cell(28, $alt, "Até o 2º Quadrimestre", 'BR', 0, "C", 0);
            $pdf->cell(28, $alt, "Até o 3º Quadrimestre", 'B', 1, "C", 0);
        }
        $pdf->setfont('arial', '', 6);
    }

    /**
     * @param $aLinhasConsistencia
     */
    protected function adicionarLinhasConsistidasNoLayout($aLinhasConsistencia)
    {
        foreach ($aLinhasConsistencia as $oLinhaRelatorio) {
            if ($oLinhaRelatorio->ordem != 27) {
                $oLinha = new Linha();
                $oLinha->multicell(true);
                $oLinha->lBold = $oLinhaRelatorio->totalizar;

                $oLinha->addColuna(73, $oLinhaRelatorio->descricao, 'TBR', 0, 'L', 0);
                $oLinha->addColuna(
                    28,
                    $this->formataValor($oLinhaRelatorio->saldo_exercicio_anterior),
                    1,
                    0,
                    'R',
                    0
                );

                if (in_array($this->oPeriodo->getCodigo(), array(12, 13))) {
                    $oLinha->addColuna(
                        42,
                        $this->formataValor($oLinhaRelatorio->semestre_1),
                        1,
                        0,
                        'R',
                        0
                    );
                    $oLinha->addColuna(
                        42,
                        $this->formataValor($oLinhaRelatorio->semestre_2),
                        'TBL',
                        1,
                        'R',
                        0
                    );
                } else {
                    $oLinha->addColuna(
                        28,
                        $this->formataValor($oLinhaRelatorio->ate_1_quadrimestre),
                        1,
                        0,
                        'R',
                        0
                    );
                    $oLinha->addColuna(
                        28,
                        $this->formataValor($oLinhaRelatorio->ate_2_quadrimestre),
                        1,
                        0,
                        'R',
                        0
                    );
                    $oLinha->addColuna(
                        28,
                        $this->formataValor($oLinhaRelatorio->ate_3_quadrimestre),
                        'TBL',
                        1,
                        'R',
                        0
                    );
                }

                $this->aLinhasProcessadas[] = $oLinha;
            } else {
                $aValoresColunasLinhas = $oLinhaRelatorio->oLinhaRelatorio->getValoresColunas(
                    null,
                    null,
                    $this->getInstituicoes(),
                    $this->ano
                );

                $descricao = $oLinhaRelatorio->descricao;
                foreach ($aValoresColunasLinhas as $oValores) {
                    foreach ($oValores->colunas as $oColuna) {
                        $descricao .= "\n" . $oColuna->o117_valor;
                    }
                }

                $oLinha = new Linha();
                $oLinha->multicell(true);
                $oLinhaRelatorio->medida_corretiva = empty($oLinhaRelatorio->medida_corretiva) ? ''
                    : $oLinhaRelatorio->medida_corretiva;
                $oLinha->addColuna(185, $descricao, 'TB', 0, 'L', 0);
                $this->aLinhasProcessadas[] = $oLinha;
            }


            if ($oLinhaRelatorio->ordem == 15) {
                $oLinha = new Linha();
                $oLinha->informaMetodo("cabecalhoContragarantiasConcedidas");
                $this->aLinhasProcessadas[] = $oLinha;
            }
        }

        $oLinha = new Linha();
        $oLinha->informaMetodo("imprimirNotasAssinaturas");
        $this->aLinhasProcessadas[] = $oLinha;
    }

    /**
     * @param \PDFDocument $pdf
     */
    public function imprimirNotasAssinaturas(\PDFDocument $pdf)
    {
        $oRelatorio = new relatorioContabil(self::CODIGO_RELATORIO, false);
        $classinatura = new cl_assinatura();
        $pdf->Ln();
        $oRelatorio->getNotaExplicativa($pdf, $this->oPeriodo->getCodigo(), self::CODIGO_RELATORIO);
        $pdf->Ln(25);
        \assinaturas($pdf, $classinatura, 'GF');
    }


    /**
     * Calcula o valor da linha do RCL.
     */
    protected function calcularRCL()
    {
        $rclAnterior = $this->rcl->calcularRCLAnterior();
        $this->aLinhasConsistencia[12]->saldo_exercicio_anterior = array_sum($rclAnterior);
        $periodo = $this->oPeriodo->getCodigo();

        foreach ($this->periodos[$periodo] as $codigoPeriodo) {
            $nValorRCL = $this->rcl->somaRCLPeriodo($codigoPeriodo);

            switch ($codigoPeriodo) {
                case 12:
                    $this->aLinhasConsistencia[12]->semestre_1 = $nValorRCL;
                    break;
                case 13:
                    $this->aLinhasConsistencia[12]->semestre_2 = $nValorRCL;
                    break;
                case 14:
                    $this->aLinhasConsistencia[12]->ate_1_quadrimestre = $nValorRCL;
                    break;
                case 15:
                    $this->aLinhasConsistencia[12]->ate_2_quadrimestre = $nValorRCL;
                    break;
                case 16:
                    $this->aLinhasConsistencia[12]->ate_3_quadrimestre = $nValorRCL;
                    break;
            }
        }
    }

    /**
     * Calcula Total das Garantias RCL.
     */
    protected function calcularTotalGarantiasRCL()
    {
        $linhas = $this->aLinhasConsistencia;
        if (!empty($linhas[12]->saldo_exercicio_anterior)) {
            $linhas[13]->saldo_exercicio_anterior = ($linhas[11]->saldo_exercicio_anterior /
                    $linhas[12]->saldo_exercicio_anterior) * 100;
        }
        if (!empty($linhas[12]->semestre_1)) {
            $linhas[13]->semestre_1 = ($linhas[11]->semestre_1 / $linhas[12]->semestre_1) * 100;
        }
        if (!empty($linhas[12]->semestre_2)) {
            $linhas[13]->semestre_2 = ($linhas[11]->semestre_2 / $linhas[12]->semestre_2) * 100;
        }
        if (!empty($linhas[12]->ate_1_quadrimestre)) {
            $linhas[13]->ate_1_quadrimestre = ($linhas[11]->ate_1_quadrimestre / $linhas[12]->ate_1_quadrimestre) * 100;
        }
        if (!empty($linhas[12]->ate_2_quadrimestre)) {
            $linhas[13]->ate_2_quadrimestre = ($linhas[11]->ate_2_quadrimestre / $linhas[12]->ate_2_quadrimestre) * 100;
        }
        if (!empty($linhas[12]->ate_3_quadrimestre)) {
            $linhas[13]->ate_3_quadrimestre = ($linhas[11]->ate_3_quadrimestre / $linhas[12]->ate_3_quadrimestre) * 100;
        }
    }

    /**
     * Calcula LIMITE DEFINIDO POR RESOLUÇÃO DO SENADO FEDERAL 32%
     */
    protected function calcularLimiteResolucaoSenado()
    {
        $limiteSenado = 0.32;
        $linhas = $this->aLinhasConsistencia;
        $linhas[14]->saldo_exercicio_anterior = $linhas[12]->saldo_exercicio_anterior * $limiteSenado;

        if (in_array($this->oPeriodo->getCodigo(), array(12, 13))) {
            $linhas[14]->semestre_1 = $linhas[12]->semestre_1 * $limiteSenado;
            $linhas[14]->semestre_2 = $linhas[12]->semestre_2 * $limiteSenado;
        } else {
            $linhas[14]->ate_1_quadrimestre = $linhas[12]->ate_1_quadrimestre * $limiteSenado;
            $linhas[14]->ate_2_quadrimestre = $linhas[12]->ate_2_quadrimestre * $limiteSenado;
            $linhas[14]->ate_3_quadrimestre = $linhas[12]->ate_3_quadrimestre * $limiteSenado;
        }
    }

    /**
     * Calcula LIMITE DE ALERTA (inciso III do §1o do art. 59 da LRF) 28;8%
     */
    protected function calcularLimiteAlerta()
    {
        $nLimiteAlerta = 28.8;
        $linhas = $this->aLinhasConsistencia;
        $linhas[15]->saldo_exercicio_anterior = ($linhas[12]->saldo_exercicio_anterior * $nLimiteAlerta) / 100;

        if (in_array($this->oPeriodo->getCodigo(), array(12, 13))) {
            $linhas[15]->semestre_1 = ($linhas[12]->semestre_1 * $nLimiteAlerta) / 100;
            $linhas[15]->semestre_2 = ($linhas[12]->semestre_2 * $nLimiteAlerta) / 100;
        } else {
            $linhas[15]->ate_1_quadrimestre = ($linhas[12]->ate_1_quadrimestre * $nLimiteAlerta) / 100;
            $linhas[15]->ate_2_quadrimestre = ($linhas[12]->ate_2_quadrimestre * $nLimiteAlerta) / 100;
            $linhas[15]->ate_3_quadrimestre = ($linhas[12]->ate_3_quadrimestre * $nLimiteAlerta) / 100;
        }
    }

    /**
     * @param float $sValor
     * @return array|bool|float|int|mixed|string
     */
    protected function formataValor($sValor)
    {
        $sValor = round($sValor, 2);
        $sValor = \db_formatar($sValor, 'f');
        return $sValor;
    }

    /**
     * @throws \BusinessException
     * @throws \ParameterException
     */
    protected function recalcularSaldoExercicio()
    {

        foreach ($this->aColunaRecalcularPeriodo[$this->oPeriodo->getCodigo()] as $dadosColuna) {
            $periodo = new \Periodo($dadosColuna["periodo"]);
            $this->processarBalanceteVerificacaoParaColunaPorData(
                $dadosColuna["coluna"],
                new \DBDate("01/01/{$this->ano}"),
                $periodo->getDataFinal($this->ano)
            );
        }
    }

    /**
     * Zera as colunas 3 e 4.
     */
    protected function zerarTodasLinhas()
    {
        $colunas = array(4);

        if ($this->getPeriodo()->getCodigo() == 14) {
            $colunas[] = 3;
        }

        if ($this->getPeriodo()->getCodigo() == 12) {
            $colunas = array(3);
        }

        foreach ($this->aLinhasConsistencia as $linha) {
            $this->zerarValorLinhaColuna($linha->ordem, $colunas);
        }
    }

    /**
     * Normaliza as linhas totalizadoras da relatório
     * @throws \Exception
     */
    protected function processarFormulasLinhasTotalizadoras()
    {
        $linhasTotalizadoras = array(1, 4, 7, 11, 16, 19, 22, 26);
        foreach ($linhasTotalizadoras as $linha) {
            $this->processarFormulaDaLinha($linha);
        }
    }

    /**
     * @return \stdClass
     */
    public function getDadosSimplificado()
    {
        $this->getDados();
        $stdDadosSimplificados = new \stdClass();

        $stdDadosSimplificados->total_garantia_concedida = 0;
        $stdDadosSimplificados->limite_definido_resolucao_senado = 0;

        $linhas = $this->aLinhasConsistencia;
        switch ($this->oPeriodo->getCodigo()) {
            case \Periodo::PRIMEIRO_SEMESTRE:
                $stdDadosSimplificados->total_garantia_concedida = $linhas[11]->semestre_1;
                $stdDadosSimplificados->limite_definido_resolucao_senado = $linhas[14]->semestre_1;
                break;
            case \Periodo::SEGUNDO_SEMESTRE:
                $stdDadosSimplificados->total_garantia_concedida = $linhas[11]->semestre_2;
                $stdDadosSimplificados->limite_definido_resolucao_senado = $linhas[14]->semestre_2;
                break;
            case \Periodo::PRIMEIRO_QUADRIMESTRE:
                $stdDadosSimplificados->total_garantia_concedida = $linhas[11]->ate_1_quadrimestre;
                $stdDadosSimplificados->limite_definido_resolucao_senado = $linhas[14]->ate_1_quadrimestre;
                break;
            case \Periodo::SEGUNDO_QUADRIMESTRE:
                $stdDadosSimplificados->total_garantia_concedida = $linhas[11]->ate_2_quadrimestre;
                $stdDadosSimplificados->limite_definido_resolucao_senado = $linhas[14]->ate_2_quadrimestre;
                break;
            case \Periodo::TERCEIRO_QUADRIMESTRE:
                $stdDadosSimplificados->total_garantia_concedida = $linhas[11]->ate_3_quadrimestre;
                $stdDadosSimplificados->limite_definido_resolucao_senado = $linhas[14]->ate_3_quadrimestre;
                break;
        }

        return $stdDadosSimplificados;
    }
}

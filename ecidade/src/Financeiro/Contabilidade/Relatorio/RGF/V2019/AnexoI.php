<?php
/*
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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019;

use DBDate;
use ECidade\Financeiro\Contabilidade\Relatorio\RelatoriosLegaisBaseMSC;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Linha;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018\AnexoI as AnexoI2018;
use Exception;

/**
 * Class AnexoI
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019
 */
class AnexoI extends AnexoI2018
{
    /**
     * Código Padrão do Relatório
     * @var integer
     */
    const CODIGO_RELATORIO = 197;

    const LINHA_DESPESA_BRUTA_COM_PESSOAL_I = 1;
    const LINHA_PESSOAL_ATIVO = 2;
    const LINHA_VENCIMENTOS_VANTAGENS_OUTRAS_DESPESAS_VARIAVEIS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL = 3;
    const LINHA_OBRIGACOES_PATRONAIS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE = 4;
    const LINHA_BENEFICIOS_PREVIDENCIARIOS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE = 5;
    const LINHA_PESSOAL_INATIVO_E_PENSIONISTAS = 6;
    const LINHA_APOSENTADORIAS_RESERVA_E_REFORMAS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE = 7;
    const LINHA_PENSOES_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE = 8;
    const LINHA_OUTROS_BENEFICIOS_PREVIDENCIARIOS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE = 9;
    const LINHA_OUTRAS_DESPESAS_PESSOAL_DECORRENTES_CONTRATOS_TERCEIRIZACAO_OU_CONTRATACAO_FORMA_INDIRETA = 10;
    const LINHA_DESPESAS_NAO_COMPUTADAS_II = 11;
    const LINHA_INDENIZACOES_POR_DEMISSAO_E_INCENTIVOS_A_DEMISSAO_VOLUNTARIA = 12;
    const LINHA_DECORRENTES_DE_DECISAO_JUDICIAL_DE_PERIODO_ANTERIOR_AO_DA_APURACAO = 13;
    const LINHA_DESPESAS_DE_EXERCICIOS_ANTERIORES_DE_PERIODO_ANTERIOR_AO_DA_APURACAO = 14;
    const LINHA_INATIVOS_E_PENSIONISTAS_COM_RECURSOS_VINCULADOS = 15;
    const LINHA_DESPESA_LIQUIDA_COM_PESSOAL_III = 16;
    const LINHA_DESPESA_TOTAL_COM_PESSOAL_DTP_VII = 20;

    /**
     * @throws Exception
     */
    protected function processarCalculoPorMeses()
    {
        $this->inicializaValoresDespesaPorLinhaMes();

        $linhasLiquidacao = array(
            static::LINHA_VENCIMENTOS_VANTAGENS_OUTRAS_DESPESAS_VARIAVEIS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL,
            static::LINHA_OBRIGACOES_PATRONAIS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE,
            static::LINHA_BENEFICIOS_PREVIDENCIARIOS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE,
            static::LINHA_APOSENTADORIAS_RESERVA_E_REFORMAS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE,
            static::LINHA_PENSOES_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE,
            static::LINHA_OUTROS_BENEFICIOS_PREVIDENCIARIOS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE,
            static::LINHA_OUTRAS_DESPESAS_PESSOAL_DECORRENTES_CONTRATOS_TERCEIRIZACAO_OU_CONTRATACAO_FORMA_INDIRETA,
            static::LINHA_INDENIZACOES_POR_DEMISSAO_E_INCENTIVOS_A_DEMISSAO_VOLUNTARIA,
            static::LINHA_DECORRENTES_DE_DECISAO_JUDICIAL_DE_PERIODO_ANTERIOR_AO_DA_APURACAO,
            static::LINHA_DESPESAS_DE_EXERCICIOS_ANTERIORES_DE_PERIODO_ANTERIOR_AO_DA_APURACAO,
            static::LINHA_INATIVOS_E_PENSIONISTAS_COM_RECURSOS_VINCULADOS
        );

        if (count($this->linhasMSC) > 0) {
            foreach ($this->getMesesAbrangente() as $mesCompetencia => $competencia) {
                $competencia = explode('/', $competencia);
                $anoCompetencia = $competencia[1];
                $ultimoDiaMes = cal_days_in_month(CAL_GREGORIAN, $mesCompetencia, $anoCompetencia);
                $oDataInicialPeriodo = new DBDate("01/{$mesCompetencia}/{$anoCompetencia}");
                $oDataFinalPeriodo = new DBDate("{$ultimoDiaMes}/{$mesCompetencia}/{$anoCompetencia}");

                $relatoriosLegaisBaseMSC = new RelatoriosLegaisBaseMSC(
                    $anoCompetencia,
                    static::CODIGO_RELATORIO,
                    $this->getPeriodo()->getCodigo()
                );
                $relatoriosLegaisBaseMSC->setDataInicial($oDataInicialPeriodo);
                $relatoriosLegaisBaseMSC->setDataFinal($oDataFinalPeriodo);
                $dadosLinhasMSC = $relatoriosLegaisBaseMSC->getDados();

                foreach ($this->linhasMSC as $iLinha) {
                    if (static::MODELO_DETALHAMENTO_MENSAL == $this->iModelo) {
                        $this->processarDesdobramentosPorDatas($oDataInicialPeriodo, $oDataFinalPeriodo);
                    }

                    if (in_array($iLinha, $linhasLiquidacao)) {
                        $despliq = $dadosLinhasMSC[$iLinha]->despliq;
                        $this->aValoresDespesaPorLinhaMes[$iLinha]["{$mesCompetencia}/{$anoCompetencia}"] = $despliq;
                        $this->aLinhasConsistencia[$iLinha]->despexec12 += $despliq;
                    }
                }
            }
        }

        foreach ($this->linhasMSC as $linha) {
            if ($this->lDoExercicio) {
                $this->aLinhasConsistencia[$linha]->inscrpnp = 0;
            }
        }

        $this->calculaValorManual();
        $this->processaTotalizadores($this->aLinhasConsistencia);
    }

    /**
     * @param $linhaRelatorio
     */
    protected function adicionaLinhaModeloDetalhado($linhaRelatorio)
    {
        $nivel = str_repeat(' ', $linhaRelatorio->nivel * 2);
        $descricao = "{$nivel} {$linhaRelatorio->descricao}";

        $bordas = array('R', 'LR', 'L');
        $negrito = false;

        if ($linhaRelatorio->ordem == static::LINHA_DESPESA_LIQUIDA_COM_PESSOAL_III) {
            $bordas = array('TBR', '1', 'TBL');
        }

        $totalizadoras = array(
            static::LINHA_DESPESA_BRUTA_COM_PESSOAL_I,
            static::LINHA_PESSOAL_ATIVO,
            static::LINHA_PESSOAL_INATIVO_E_PENSIONISTAS,
            static::LINHA_DESPESAS_NAO_COMPUTADAS_II,
            static::LINHA_DESPESA_LIQUIDA_COM_PESSOAL_III
        );

        if (in_array($linhaRelatorio->ordem, $totalizadoras)) {
            $negrito = true;
        }

        $configuracoes = $this->aTamanhoCelulas[static::MODELO_DETALHAMENTO_MENSAL];
        $larguraDescricao = $configuracoes['iWDescricao'];
        $larguraMes = $configuracoes['iWMes'];
        $larguraTotais = $configuracoes['iWTotais'];

        $totalUltimosDozeMeses = $this->formataValor($linhaRelatorio->despexec12);
        $inscritasEmRestosPagar = $this->formataValor($linhaRelatorio->inscrpnp);

        $linha = new Linha();
        $linha->multicell(true)->bold($negrito)->alturaLinha(4);
        $linha->addColuna($larguraDescricao, $descricao, $bordas[0], 0, 'L', 0, 4);

        $valores = $this->aValoresDespesaPorLinhaMes[$linhaRelatorio->ordem];

        foreach ($valores as $valor) {
            $linha->addColuna($larguraMes, db_formatar($valor, 'f'), $bordas[1], 0, 'R', 0, 4);
        }

        $linha->addColuna($larguraTotais, $totalUltimosDozeMeses, $bordas[1], 0, 'R', 0, 4);
        $linha->addColuna($larguraTotais, $inscritasEmRestosPagar, $bordas[2], 1, 'R', 0, 4);

        $this->aLinhasProcessadas[] = $linha;
    }

    /**
     * @throws Exception
     */
    protected function processarDetalhamentoMensal()
    {
        $this->getDados();

        $linhasTotalizadorasSoma = array(
            static::LINHA_PESSOAL_ATIVO => array(
                static::LINHA_VENCIMENTOS_VANTAGENS_OUTRAS_DESPESAS_VARIAVEIS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL,
                static::LINHA_OBRIGACOES_PATRONAIS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE,
                static::LINHA_BENEFICIOS_PREVIDENCIARIOS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE
            ),
            static::LINHA_PESSOAL_INATIVO_E_PENSIONISTAS => array(
                static::LINHA_APOSENTADORIAS_RESERVA_E_REFORMAS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE,
                static::LINHA_PENSOES_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE,
                static::LINHA_OUTROS_BENEFICIOS_PREVIDENCIARIOS_VER_EDITAR_TRANSPARENTE_EDICAO_MANUAL_TRANSPARENTE
            ),
            static::LINHA_DESPESA_BRUTA_COM_PESSOAL_I => array(
                static::LINHA_PESSOAL_ATIVO,
                static::LINHA_PESSOAL_INATIVO_E_PENSIONISTAS,
                static::LINHA_OUTRAS_DESPESAS_PESSOAL_DECORRENTES_CONTRATOS_TERCEIRIZACAO_OU_CONTRATACAO_FORMA_INDIRETA
            ),
            static::LINHA_DESPESAS_NAO_COMPUTADAS_II => array(
                static::LINHA_INDENIZACOES_POR_DEMISSAO_E_INCENTIVOS_A_DEMISSAO_VOLUNTARIA,
                static::LINHA_DECORRENTES_DE_DECISAO_JUDICIAL_DE_PERIODO_ANTERIOR_AO_DA_APURACAO,
                static::LINHA_DESPESAS_DE_EXERCICIOS_ANTERIORES_DE_PERIODO_ANTERIOR_AO_DA_APURACAO,
                static::LINHA_INATIVOS_E_PENSIONISTAS_COM_RECURSOS_VINCULADOS
            )
        );

        $linhasTotalizadorasSubtracao = array(
            static::LINHA_DESPESA_LIQUIDA_COM_PESSOAL_III => array(
                static::LINHA_DESPESA_BRUTA_COM_PESSOAL_I,
                static::LINHA_DESPESAS_NAO_COMPUTADAS_II
            )
        );

        foreach ($linhasTotalizadorasSoma as $linhaTotalizadora => $linhasSoma) {
            foreach ($this->aValoresDespesaPorLinhaMes as $linha => $valoresMesAno) {
                foreach ($valoresMesAno as $mesAno => $valor) {
                    if (in_array($linha, $linhasSoma)) {
                        $this->aValoresDespesaPorLinhaMes[$linhaTotalizadora][$mesAno] += $valor;
                    }
                }
            }
        }

        foreach ($linhasTotalizadorasSubtracao as $linhaTotalizadora => $linhasSubtrai) {
            foreach ($this->aValoresDespesaPorLinhaMes as $linha => $valoresMesAno) {
                foreach ($valoresMesAno as $mesAno => $valor) {
                    if (in_array($linha, $linhasSubtrai)) {
                        if ($linhasSubtrai[0] == $linha) {
                            $this->aValoresDespesaPorLinhaMes[$linhaTotalizadora][$mesAno] += $valor;
                        } else {
                            $this->aValoresDespesaPorLinhaMes[$linhaTotalizadora][$mesAno] -= $valor;
                        }
                    }
                }
            }
        }

        $despexec12 = $this->aLinhasConsistencia[static::LINHA_DESPESA_BRUTA_COM_PESSOAL_I]->despexec12 -
          $this->aLinhasConsistencia[static::LINHA_DESPESAS_NAO_COMPUTADAS_II]->despexec12;
        $inscrpnp = $this->aLinhasConsistencia[static::LINHA_DESPESA_BRUTA_COM_PESSOAL_I]->inscrpnp -
          $this->aLinhasConsistencia[static::LINHA_DESPESAS_NAO_COMPUTADAS_II]->inscrpnp;

        $this->aLinhasConsistencia[static::LINHA_DESPESA_LIQUIDA_COM_PESSOAL_III]->despexec12 = $despexec12;
        $this->aLinhasConsistencia[static::LINHA_DESPESA_LIQUIDA_COM_PESSOAL_III]->inscrpnp = $inscrpnp;

        $this->processarFormasDasLinhas(array(
            static::LINHA_DESPESA_TOTAL_COM_PESSOAL_DTP_VII
        ));
        $this->calculaReceitaCorrenteLiquida();
    }

    /**
     * Calcula o valor que foi informado manualmente da:
     *  - liquidação: sempre
     *  - restos a pagar: somente quando não é do exercício
     *
     * Calcula o valor da liquidação que foi informado manualmente
     */
    protected function calculaValorManual()
    {
        foreach ($this->linhasMSC as $iLinha) {
            $aLinhasManuais = $this->aLinhasConsistencia[$iLinha]->oLinhaRelatorio->getValoresColunas(
                null,
                null,
                $this->getInstituicoes(),
                $this->iAnoUsu
            );

            foreach ($this->getMesesAbrangente() as $iMes => $sCompetencia) {
                list($sMesAbreviado, $iAno) = explode('/', $sCompetencia);

                foreach ($aLinhasManuais as $oLinhaManual) {
                    if ($oLinhaManual->colunas[0]->o117_valor == $sCompetencia) {
                        $valor = $oLinhaManual->colunas[1]->o117_valor;
                        $this->aValoresDespesaPorLinhaMes[$iLinha]["{$iMes}/{$iAno}"] += $valor;
                        // Atualiza o totalizador da coluna liquidado
                        $this->aLinhasConsistencia[$iLinha]->liquidado_ultimo_ano += $valor;

                        // Calcula o valor manual pq o valor do RP é
                        // calculado na mão quando período engloba mais de um exercício
                        if (!$this->lDoExercicio) {
                            // echo "Linha: {$oLinhaManual->ordem} --- rp_nao_processado: {$this->aLinhasConsistencia
                            //[$iLinha]->rp_nao_processado} += vlr manual : {$oLinhaManual->colunas[2]->o117_valor}
                            // <br>";
                            $valor = $oLinhaManual->colunas[2]->o117_valor;
                            $this->aLinhasConsistencia[$iLinha]->rp_nao_processado += $valor;
                        }
                    }
                }
            }
        }
    }
}

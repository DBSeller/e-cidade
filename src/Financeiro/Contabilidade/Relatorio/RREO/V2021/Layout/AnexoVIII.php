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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2021\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2021\AnexoVIII as Relatorio;

class AnexoVIII
{
    const RECEITA_DE_IMPOSTOS = 1;
    const TOTAL_RECEITA_RESULTANTE_DE_IMPOSTOS = 16;

    const TOTAL_DESTINADO_FUNDEB_20 = 17;

    const VALOR_MINIMO_SER_APLICADO_ALEM_VALOR_DESTINADO = 18;

    const RECEITAS_RECEBIDAS_FUNDEB = 19;
    const RESULTADO_LIQUIDO_TRANSFERENCIAS_FUNDEB = 29;

    const TOTAL_DOS_RECURSOS_SUPERAVIT = 30;
    const SUPERAVIT_RESIDUAL_DE_OUTROS_EXERCICIOS = 32;

    const TOTAL_DOS_RECURSOS_DO_FUNDEB_DISPONIVEIS = 33;

    const PROFISSIONAIS_DA_EDUCACAO_BASICA = 34;
    const TOTAL_DAS_DESPESAS_COM_RECURSOS_DO_FUNDEB = 44;

    const TOTAL_DESPESAS_FUNDEB_PROFISSIONAIS_EDUC_BASICA = 45;
    const TOTAL_DESPESA_CUSTEADA_FUNDEB_COMPLEMENTO_UNIAO = 50;

    const MINIMO_70_FUNDEB_ED_BASICA = 51;
    const MINIMO_15_FUNDEB_DESP_CAPITAL = 53;

    const TOTAL_DA_RECEITA_RECEBIDA_NAO_APLICADA_EXERC = 54;

    const TOTAL_DAS_DESPESAS_CUSTEADAS_SUPERAVIT_FUNDEB = 55;
    const TOTAL_DAS_DESPESAS_CUSTEADAS_COMPLEMENTACAO_FUNDEB = 57;

    const EDUCACAO_INFANTIL = 58;
    const TOTAL_DAS_DESPESAS_COM_ACOES_TIPICAS_MDE = 62;

    const TOTAL_DAS_DESPESAS_DE_MDE_CUSTEADAS_COM_RECURSOS_DE_IMPOSTOS = 63;
    const TOTAL_DAS_DESPESAS_PARA_FINS_DE_LIMITE = 68;

    const APLICACAO_EM_MDE_SOBRE_A_RECEITA_RESULTANTE_DE_IMPOSTOS = 69;

    const RESTOS_PAGAR_DE_DESPESAS_COM_MDE = 70;
    const EXECUTADAS_COM_RECURSOS_DO_FUNDEB = 73;

    const RECEITA_DE_TRANSFERENCIAS_FNDE = 74;
    const TOTAL_DAS_RECEITAS_ADICIONAIS_FINANCIAMENTO_ENSINO = 84;

    const EDUCACAO_INFANTIL_85 = 85;
    const TOTAL_DAS_DESPESAS_CUSTEADAS_RECEITAS_ADICIONAIS = 92;

    const TOTAL_GERAL_DAS_DESPESAS_COM_EDUCACAO = 93;
    const OUTRAS_DESPESAS_DE_CAPITAL = 101;

    const DISPONIBILIDADE_FINANCEIRA_DEZEMBRO_EXERCICIO_ANTERIOR = 102;
    const SALDO_FINANCEIRO_CONCILIADO = 108;

    /**
     * @var \PDFDocument
     */
    protected $oPdf;

    /**
     * @var \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoIV
     */
    protected $oRelatorio;

    /**
     * @var \stdClass[]
     */
    protected $aLinhas;

    /**
     * @var array
     */
    protected $sessao = null;

    /**
     * @throws \Exception
     */
    public function emitir()
    {
        if (empty($this->oRelatorio)) {
            throw new \Exception('Não foi informado ao AnexoVIII para impressão.');
        }

        $this->aLinhas = $this->oRelatorio->getDados();

        $this->oPdf = new \PDFDocument(\PDFDocument::PRINT_LANDSCAPE);
        $this->addHeaders();

        $nLargura = ($this->oPdf->getAvailWidth() / 2);
        $this->oPdf->cell($nLargura, 4, 'RREO - ANEXO 8 (LDB, art. 72)', 0, 0);
        $this->oPdf->cell($nLargura, 4, 'R$ 1,00', 0, 1, \PDFDocument::ALIGN_RIGHT);

        foreach ($this->aLinhas as $nroLinha => $oStdLinha) {
            if (!empty($this->sessao) && !empty($this->sessao['DB_DEBUG']) && $this->sessao['DB_DEBUG'] === true) {
                if (!empty($oStdLinha) && !empty($oStdLinha->descricao)) {
                    $oStdLinha->descricao = "{$nroLinha}) ".$oStdLinha->descricao;
                }
            }

            // 1 a 16
            if (\Check::between(
                $oStdLinha->ordem,
                static::RECEITA_DE_IMPOSTOS,
                static::TOTAL_RECEITA_RESULTANTE_DE_IMPOSTOS
            )) {
                if ($oStdLinha->ordem == static::RECEITA_DE_IMPOSTOS
                    ) {
                    $sTituloColuna = 'RECEITA RESULTANTE DE IMPOSTOS (Arts. 212 e 212-A da Constituição Federal)';
                    $this->cabecalhoReceita($sTituloColuna, null, false);
                }
                $this->imprimeReceita($oStdLinha);
            }

            if ($oStdLinha->ordem == static::TOTAL_DESTINADO_FUNDEB_20) {
                $this->espaco(4);
                $this->imprimeLinhaReceitaSimples($oStdLinha);
            }

            if ($oStdLinha->ordem == static::VALOR_MINIMO_SER_APLICADO_ALEM_VALOR_DESTINADO) {
                $this->espaco(4);
                $this->imprimeLinhaReceitaComposta($oStdLinha, 4, 10);
            }

            if (\Check::between(
                $oStdLinha->ordem,
                static::RECEITAS_RECEBIDAS_FUNDEB,
                static::RESULTADO_LIQUIDO_TRANSFERENCIAS_FUNDEB
            )) {
                if ($oStdLinha->ordem == static::RECEITAS_RECEBIDAS_FUNDEB
                        ) {
                    $this->oPdf->AddPage();
                    $sTituloColuna = 'FUNDEB';
                    $this->cabecalhoReceita($sTituloColuna, 'RECEITAS RECEBIDAS DO FUNDEB NO EXERCÍCIO', false);
                }
                $this->imprimeReceita($oStdLinha);
            }

            if (\Check::between(
                $oStdLinha->ordem,
                static::TOTAL_DOS_RECURSOS_SUPERAVIT,
                static::SUPERAVIT_RESIDUAL_DE_OUTROS_EXERCICIOS
            )) {
                if ($oStdLinha->ordem == static::TOTAL_DOS_RECURSOS_SUPERAVIT
                            ) {
                    $sTituloColuna = 'FUNDEB';
                    $this->imprimeCabecalhoColunaValor(null);
                }
                $this->imprimeColunaValor($oStdLinha);
            }

            if ($oStdLinha->ordem == static::TOTAL_DOS_RECURSOS_DO_FUNDEB_DISPONIVEIS) {
                $this->imprimeColunaValor($oStdLinha);
            }

            //PROFISSIONAIS_DA_EDUCACAO_BASICA = 34;
            //TOTAL_DAS_DESPESAS_COM_RECURSOS_DO_FUNDEB = 44;

            if (\Check::between(
                $oStdLinha->ordem,
                static::PROFISSIONAIS_DA_EDUCACAO_BASICA,
                static::TOTAL_DAS_DESPESAS_COM_RECURSOS_DO_FUNDEB
            )) {
                if ($oStdLinha->ordem == static::PROFISSIONAIS_DA_EDUCACAO_BASICA
                            ) {
                    $this->espaco(4);
                    $this->cabecalhoDespesa('DESPESAS COM RECURSOS DO FUNDEB (Por Área de Atuação)6');
                }
                $this->imprimeDespesa($oStdLinha);
            }

            // TOTAL_DESPESAS_FUNDEB_PROFISSIONAIS_EDUC_BASICA = 45;
            // TOTAL_DESPESA_CUSTEADA_FUNDEB_COMPLEMENTO_UNIAO = 50;

            if (\Check::between(
                $oStdLinha->ordem,
                static::TOTAL_DESPESAS_FUNDEB_PROFISSIONAIS_EDUC_BASICA,
                static::TOTAL_DESPESA_CUSTEADA_FUNDEB_COMPLEMENTO_UNIAO
            )) {
                if ($oStdLinha->ordem == static::TOTAL_DESPESAS_FUNDEB_PROFISSIONAIS_EDUC_BASICA
                                ) {
                    $this->oPdf->AddPage();
                    $this->espaco(4);
                    $sColuna = 'DESPESAS CUSTEADAS COM RECEITAS DO FUNDEB RECEBIDAS NO EXERCÍCIO';
                    $this->cabecalhoDespesaFundeb($sColuna, 'INDICADORES DO FUNDEB');
                }
                $this->imprimeDespesaFundeb($oStdLinha);
            }

            //MINIMO_70_FUNDEB_ED_BASICA = 51;
            //MINIMO_15_FUNDEB_DESP_CAPITAL = 53;

            if (\Check::between(
                $oStdLinha->ordem,
                static::MINIMO_70_FUNDEB_ED_BASICA,
                static::MINIMO_15_FUNDEB_DESP_CAPITAL
            )) {
                if ($oStdLinha->ordem == static::MINIMO_70_FUNDEB_ED_BASICA
                                ) {
                    $this->cabecalhoIndicadoresUm();
                }
                $this->imprimeIndicadoresUm($oStdLinha);
            }

            //TOTAL_DA_RECEITA_RECEBIDA_NAO_APLICADA_EXERC
            if ($oStdLinha->ordem == static::TOTAL_DA_RECEITA_RECEBIDA_NAO_APLICADA_EXERC) {
                $this->cabecalhoIndicadoresDois();
                $this->imprimeIndicadoresTres($oStdLinha);
            }

            // TOTAL_DAS_DESPESAS_CUSTEADAS_SUPERAVIT_FUNDEB = 55;
            // TOTAL_DAS_DESPESAS_CUSTEADAS_COMPLEMENTACAO_FUNDEB = 57;

            if (\Check::between(
                $oStdLinha->ordem,
                static::TOTAL_DAS_DESPESAS_CUSTEADAS_SUPERAVIT_FUNDEB,
                static::TOTAL_DAS_DESPESAS_CUSTEADAS_COMPLEMENTACAO_FUNDEB
            )) {
                if ($oStdLinha->ordem == static::TOTAL_DAS_DESPESAS_CUSTEADAS_SUPERAVIT_FUNDEB
                                ) {
                    $this->cabecalhoIndicadoresTres();
                }
                $this->imprimeIndicadoresDois($oStdLinha);
            }

            //EDUCACAO_INFANTIL = 58;
            //TOTAL_DAS_DESPESAS_COM_ACOES_TIPICAS_MDE = 62;

            if (\Check::between(
                $oStdLinha->ordem,
                static::EDUCACAO_INFANTIL,
                static::TOTAL_DAS_DESPESAS_COM_ACOES_TIPICAS_MDE
            )) {
                if ($oStdLinha->ordem == static::EDUCACAO_INFANTIL
                                ) {
                    $this->oPdf->AddPage();
                    $sColuna1 = 'DESPESAS COM AÇÕES TÍPICAS DE MDE - RECEITAS DE IMPOSTOS - ';
                    $sColuna2 = 'EXCETO FUNDEB(Por Área de Atuação)6';

                    $sTitulo = 'DESPESAS COM MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO MDE -  ';
                    $sTitulo .= 'CUSTEADAS COM RECEITA DE IMPOSTOS (EXCETO FUNDEB)';

                    $this->cabecalhoDespesa(
                        null,
                        $sTitulo,
                        [$sColuna1, $sColuna2]
                    );
                }
                $this->imprimeDespesa($oStdLinha);
            }

            //const TOTAL_DAS_DESPESAS_DE_MDE_CUSTEADAS_COM_RECURSOS_DE_IMPOSTOS = 63;
            //const TOTAL_DAS_DESPESAS_PARA_FINS_DE_LIMITE = 68;
            if (\Check::between(
                $oStdLinha->ordem,
                static::TOTAL_DAS_DESPESAS_DE_MDE_CUSTEADAS_COM_RECURSOS_DE_IMPOSTOS,
                static::TOTAL_DAS_DESPESAS_PARA_FINS_DE_LIMITE
            )) {
                if ($oStdLinha->ordem ==
                                     static::TOTAL_DAS_DESPESAS_DE_MDE_CUSTEADAS_COM_RECURSOS_DE_IMPOSTOS
                                ) {
                    $sTituloColuna = 'FUNDEB';
                    $this->imprimeCabecalhoColunaValor(
                        'APURAÇÃO DAS DESPESAS PARA FINS DE LIMITE MÍNIMO CONSTITUCIONAL'
                    );
                }
                $this->imprimeColunaValor($oStdLinha);
            }

            //APLICACAO_EM_MDE_SOBRE_A_RECEITA_RESULTANTE_DE_IMPOSTOS = 69;

            if ($oStdLinha->ordem == static::APLICACAO_EM_MDE_SOBRE_A_RECEITA_RESULTANTE_DE_IMPOSTOS) {
                //$this->cabecalhoIndicadoresDois();
                $this->imprimeApuracaoLimiteMinimo($oStdLinha);
            }

            //RESTOS_PAGAR_DE_DESPESAS_COM_MDE = 70;
            //EXECUTADAS_COM_RECURSOS_DO_FUNDEB = 73;
            if (\Check::between(
                $oStdLinha->ordem,
                static::RESTOS_PAGAR_DE_DESPESAS_COM_MDE,
                static::EXECUTADAS_COM_RECURSOS_DO_FUNDEB
            )) {
                if ($oStdLinha->ordem ==
                                     static::RESTOS_PAGAR_DE_DESPESAS_COM_MDE
                                ) {
                    $this->espaco(4);
                    $this->cabecalhoRP();
                }
                $this->imprimeRP($oStdLinha);
            }

            //RECEITA_DE_TRANSFERENCIAS_FNDE = 74;
            //TOTAL_DAS_RECEITAS_ADICIONAIS_FINANCIAMENTO_ENSINO = 84;

            if (\Check::between(
                $oStdLinha->ordem,
                static::RECEITA_DE_TRANSFERENCIAS_FNDE,
                static::TOTAL_DAS_RECEITAS_ADICIONAIS_FINANCIAMENTO_ENSINO
            )) {
                if ($oStdLinha->ordem == static::RECEITA_DE_TRANSFERENCIAS_FNDE
                                ) {
                    $this->oPdf->AddPage();
                    $sTituloColuna = 'OUTRAS INFORMAÇÕES PARA CONTROLE';
                    $this->cabecalhoReceita(
                        $sTituloColuna,
                        'RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO',
                        false
                    );
                }
                $this->imprimeReceita($oStdLinha);
            }

            //EDUCACAO_INFANTIL_85 = 85;
            //TOTAL_DAS_DESPESAS_CUSTEADAS_RECEITAS_ADICIONAIS = 92;
            if (\Check::between(
                $oStdLinha->ordem,
                static::EDUCACAO_INFANTIL_85,
                static::TOTAL_DAS_DESPESAS_CUSTEADAS_RECEITAS_ADICIONAIS
            )) {
                if ($oStdLinha->ordem == static::EDUCACAO_INFANTIL_85
                                ) {
                    $this->espaco(4);
                    $sColuna1 = 'DESPESAS CUSTEADAS COM RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ';
                    $sColuna2 = 'ENSINO (Por Área de Atuação)6';

                    // $sTitulo  = "DESPESAS COM MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO MDE -  ";
                    // $sTitulo .= "CUSTEADAS COM RECEITA DE IMPOSTOS (EXCETO FUNDEB)";

                    $this->cabecalhoDespesa(null, null, [$sColuna1, $sColuna2]);
                }
                $this->imprimeDespesa($oStdLinha);
            }

            //TOTAL_GERAL_DAS_DESPESAS_COM_EDUCACAO = 93;
            //OUTRAS_DESPESAS_DE_CAPITAL = 101;

            if (\Check::between(
                $oStdLinha->ordem,
                static::TOTAL_GERAL_DAS_DESPESAS_COM_EDUCACAO,
                static::OUTRAS_DESPESAS_DE_CAPITAL
            )) {
                if ($oStdLinha->ordem == static::TOTAL_GERAL_DAS_DESPESAS_COM_EDUCACAO
                                ) {
                    $this->oPdf->AddPage();
                    $this->cabecalhoDespesa('TOTAL GERAL DAS DESPESAS COM EDUCAÇÃO');
                }
                $this->imprimeDespesa($oStdLinha);
            }

            //DISPONIBILIDADE_FINANCEIRA_DEZEMBRO_EXERCICIO_ANTERIOR = 102;
            //SALDO_FINANCEIRO_CONCILIADO = 108;
            if (\Check::between(
                $oStdLinha->ordem,
                static::DISPONIBILIDADE_FINANCEIRA_DEZEMBRO_EXERCICIO_ANTERIOR,
                static::SALDO_FINANCEIRO_CONCILIADO
            )) {
                if ($oStdLinha->ordem == static::DISPONIBILIDADE_FINANCEIRA_DEZEMBRO_EXERCICIO_ANTERIOR
                                ) {
                    $this->espaco(4);
                    $sTituloColuna = '';
                    $this->cabecalhoControleDisponibilidade(
                        'CONTROLE DA DISPONIBILIDADE FINANCEIRA E CONCILIAÇÃO BANCÁRIA'
                    );
                }
                $this->imprimeControleDisponibilidade($oStdLinha);
            }
        }

        $this->oPdf->SetFontSize(5);
        $this->espaco(2);
        $this->legendas();

        $this->oPdf->ln($this->oPdf->getAvailHeight() - 10);

        $oDaoAssinatura = new \cl_assinatura();
        assinaturas($this->oPdf, $oDaoAssinatura, 'LRF');

        $this->oPdf->showPDF('RREO_Anexo_VII_DemonstrativoReceitaDespesa_v2021_'.time());
    }

    protected function imprimeControleDisponibilidade(\stdClass $oStdLinha)
    {
        $iLargura = $this->oPdf->getAvailWidth();
        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }

        $sBorda = $this->getBorda($oStdLinha->ordem);

        if ($oStdLinha->ordem == static::SALDO_FINANCEIRO_CONCILIADO) {
            $sBorda .= 'B';
        }

        $iAnoAnterior = $this->oRelatorio->getExercicioAnterior();
        $sLinha = (\relatorioContabil::getIdentacao($oStdLinha->nivel)).$oStdLinha->descricao;
        $sLinha = str_replace('<EXERCÍCIO ANTERIOR>', $iAnoAnterior, $sLinha);

        $this->oPdf->cell(
            $iLargura * 0.4,
            4,
            $sLinha,
            $sBorda.'R',
            0,
            'L',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.30,
            4,
            db_formatar($oStdLinha->vlr_fundeb, 'f'),
            $sBorda.'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.30,
            4,
            db_formatar($oStdLinha->vlr_sal_educacao, 'f'),
            $sBorda.'',
            1,
            'R',
            $this->transparente($oStdLinha->ordem)
        );

        $this->oPdf->setBold(false);
    }

    protected function cabecalhoControleDisponibilidade($tituloColuna)
    {
        $legenda = !empty($tituloColuna) ?
          $tituloColuna : 'CONTROLE DA DISPONIBILIDADE FINANCEIRA E CONCILIAÇÃO BANCÁRIA';

        $lBold = $this->oPdf->getBold();
        $this->oPdf->setBold(true);
        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->cell($iLargura * 0.4, 3, "$legenda", 'T', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, 'FUNDEB', 'TLR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, 'SALÁRIO EDUCAÇÃO', 'TL', 1, 'C', 1);

        $this->oPdf->cell($iLargura * 0.4, 3, '', 'B', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, '(ae)', 'BLR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, '(af)', 'BL', 1, 'C', 1);

        $iPosicaoX = $this->oPdf->getX();
        $this->oPdf->setX($iPosicaoX);
        $this->oPdf->setbold($lBold);
    }

    protected function imprimeRP(\stdClass $oStdLinha)
    {
        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }
        $sBorda = $this->getBorda($oStdLinha->ordem);

        $iLargura = $this->oPdf->getAvailWidth();


        $nRpInicial = db_formatar($oStdLinha->saldo_rp_inicial, 'f');
        $nRpLiquidado = db_formatar($oStdLinha->rp_liquidados, 'f');
        $nRpPago = db_formatar($oStdLinha->rp_pagos, 'f');
        $nRpCancelado = db_formatar($oStdLinha->rp_cancelados, 'f');
        $nRpSaldoFinal = db_formatar($oStdLinha->rp_saldo_final, 'f');



        if ($oStdLinha->ordem == static::EXECUTADAS_COM_RECURSOS_DO_FUNDEB) {
            $sBorda .= 'B';
        }

        $lTransparencia = $this->transparente($oStdLinha->ordem);

        $this->oPdf->cell(
            $iLargura * (0.4),
            4,
            \relatorioContabil::getIdentacao($oStdLinha->nivel).$oStdLinha->descricao,
            "$sBorda R",
            0,
            'L',
            $this->transparente($oStdLinha->ordem)
        );

        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nRpInicial, "$sBorda R", 0, 'R', $lTransparencia);
        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nRpLiquidado, "$sBorda R", 0, 'R', $lTransparencia);
        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nRpPago, "$sBorda R", 0, 'R', $lTransparencia);
        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nRpCancelado, "$sBorda L", 0, 'R', $lTransparencia);
        $this->oPdf->cell($iLargura * 0.1, 4, $nRpSaldoFinal, "$sBorda L", 1, 'R', $lTransparencia);
    }

    protected function cabecalhoRP()
    {
        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->setbold(true);

        $sCabecalho = 'RESTOS A PAGAR INSCRITOS EM EXERCÍCIOS ANTERIORES COM DISPONIBILIDADE FINANCEIRA';
        $this->oPdf->cell($iLargura * (0.4), 3, $sCabecalho, 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'SALDO INICIAL', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'RP LIQUIDADOS', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'RP PAGOS', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'RP CANCELADOS', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1, 3, 'SALDO FINAL', 'TL', 1, 'C', 1);

        $this->oPdf->cell($iLargura * (0.4), 3, 'DE RECURSOS DE IMPOSTOS E DO FUNDEB8', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(z)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(aa)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(ab)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(ac)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1, 3, '(ad)', 'BL', 1, 'C', 1);

        $iPosicaoX = $this->oPdf->getX();
        $this->oPdf->setX($iPosicaoX);
        $this->oPdf->setbold(false);
    }

    protected function imprimeApuracaoLimiteMinimo(\stdClass $oStdLinha)
    {
        $legenda = !empty($tituloColuna) ?
                   $tituloColuna : 'INDICADOR - Art.25, § 3º - Lei nº 14.113, de 2020 - (Máximo de 10% de Superávit)3';

        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->setbold(true);

        $this->oPdf->cell($iLargura * (0.5) + 35, 3, 'APURAÇÃO DO LIMITE MÍNIMO CONSTITUCIONAL2 e 5', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'VALOR EXIGIDO', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'VALOR APLICADO', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '% APLICADO', 'TL', 1, 'C', 1);

        $this->oPdf->cell($iLargura * (0.5) + 35, 3, '', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(x)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(w)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(y)', 'BL', 1, 'C', 1);

        $iPosicaoX = $this->oPdf->getX();
        $this->oPdf->setX($iPosicaoX);
        $this->oPdf->setbold(false);

        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }

        $sBorda = $this->getBorda($oStdLinha->ordem);

        $this->oPdf->cell(
            $iLargura * (0.5) + 35,
            4,
            (\relatorioContabil::getIdentacao($oStdLinha->nivel)).$oStdLinha->descricao,
            $sBorda.'R',
            0,
            'L',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.1 + 7,
            4,
            db_formatar($oStdLinha->vlr_exigido, "f"),
            $sBorda.'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.1 + 7,
            4,
            db_formatar($oStdLinha->vlr_aplicado, "f"),
            $sBorda.'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.1 + 7,
            4,
            db_formatar($oStdLinha->percent_aplicado, "f"),
            $sBorda.'L',
            1,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
    }


    protected function imprimeIndicadoresTres(\stdClass $oStdLinha)
    {
        $iLargura = $this->oPdf->getAvailWidth();
        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }

        $sBorda = $this->getBorda($oStdLinha->ordem);

        $this->oPdf->cell(
            $iLargura * (0.4) + 30,
            4,
            (\relatorioContabil::getIdentacao($oStdLinha->nivel)).$oStdLinha->descricao,
            $sBorda.'R',
            0,
            'L',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.1 + 7,
            4,
            db_formatar($oStdLinha->vlr_maximo_permitido, "f"),
            $sBorda.'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.1 + 7,
            4,
            db_formatar($oStdLinha->vlr_nao_aplicado, "f"),
            $sBorda.'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.1 + 7,
            4,
            db_formatar($oStdLinha->vlr_nao_aplicado_apos_ajuste, "f"),
            $sBorda.'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.1 + 7,
            4,
            db_formatar($oStdLinha->percentual_nao_aplicado, "f"),
            $sBorda.'',
            1,
            'R',
            $this->transparente($oStdLinha->ordem)
        );

        $this->oPdf->setBold(false);
    }






    protected function imprimeIndicadoresUm(\stdClass $oStdLinha)
    {
        $iLargura = $this->oPdf->getAvailWidth();
        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }

        $sBorda = $this->getBorda($oStdLinha->ordem);


/*
vlr_exigido = $this-
vlr_aplicado = $this
vlr_consid_apos_ded
perc_aplicado = ($th

*/
        $this->oPdf->cell(
            $iLargura * (0.4) + 30,
            4,
            (\relatorioContabil::getIdentacao($oStdLinha->nivel)).$oStdLinha->descricao,
            $sBorda.'R',
            0,
            'L',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.1 + 7,
            4,
            db_formatar($oStdLinha->vlr_exigido, "f"),
            $sBorda.'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.1 + 7,
            4,
            db_formatar($oStdLinha->vlr_aplicado, "f"),
            $sBorda.'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.1 + 7,
            4,
            db_formatar($oStdLinha->vlr_consid_apos_ded, "f"),
            $sBorda.'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.1 + 7,
            4,
            db_formatar($oStdLinha->perc_aplicado, "f"),
            $sBorda.'',
            1,
            'R',
            $this->transparente($oStdLinha->ordem)
        );

        $this->oPdf->setBold(false);
    }

    protected function imprimeIndicadoresDois(\stdClass $oStdLinha)
    {
        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }

        $sBorda = $this->getBorda($oStdLinha->ordem);

        $sBordaAdd = '';
        if (in_array($oStdLinha->ordem, [
             static::TOTAL_DAS_DESPESAS_CUSTEADAS_COMPLEMENTACAO_FUNDEB,
        ])) {
            $sBordaAdd .= 'B';
        }

        $iPrimeiraColuna = 94;
        $iColunasValores = 31;

        $sNomeLinha = (\relatorioContabil::getIdentacao($oStdLinha->nivel)).$oStdLinha->descricao;
        $iTamanhoLinha = strlen($sNomeLinha);

        if ($iTamanhoLinha > 80) {
            $sNomeLinha = substr($sNomeLinha, 0, 80);
            $sRestanteLinha = substr(
                (\relatorioContabil::getIdentacao($oStdLinha->nivel)).$oStdLinha->descricao,
                80,
                $iTamanhoLinha
            );

            $this->oPdf->cell(
                $iPrimeiraColuna,
                4,
                $sNomeLinha,
                $sBorda.'R',
                0,
                'L',
                $this->transparente($oStdLinha->ordem)
            );

            $this->oPdf->cell($iColunasValores, 4, '', $sBorda.'R', 0, 'R', $this->transparente($oStdLinha->ordem));
            $this->oPdf->cell($iColunasValores, 4, '', $sBorda.'R', 0, 'R', $this->transparente($oStdLinha->ordem));
            $this->oPdf->cell($iColunasValores, 4, '', $sBorda.'R', 0, 'R', $this->transparente($oStdLinha->ordem));
            $this->oPdf->cell($iColunasValores, 4, '', $sBorda.'R', 0, 'R', $this->transparente($oStdLinha->ordem));
            $this->oPdf->cell($iColunasValores, 4, '', $sBorda.'R', 0, 'R', $this->transparente($oStdLinha->ordem));
            $this->oPdf->cell($iColunasValores, 4, '', $sBorda.'', 1, 'R', $this->transparente($oStdLinha->ordem));


            $this->oPdf->cell(
                $iPrimeiraColuna,
                4,
                $sRestanteLinha,
                $sBorda.'R'.$sBordaAdd,
                0,
                'L',
                $this->transparente($oStdLinha->ordem)
            );

            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->vlr_superavit_ex_ant, "f"),
                $sBorda.'R'.$sBordaAdd,
                0,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->vlr_naplic_ex_ant, "f"),
                $sBorda.'R'.$sBordaAdd,
                0,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->superavit_aplic_1quadr, "f"),
                $sBorda.'R'.$sBordaAdd,
                0,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->aplic_1q_limite_constitucional, "f"),
                $sBorda.'R'.$sBordaAdd,
                0,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->aplic_apos_1q, "f"),
                $sBorda.'R'.$sBordaAdd,
                0,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->vlr_nao_aplic, "f"),
                $sBorda.''.$sBordaAdd,
                1,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
        } else {
            $this->oPdf->cell(
                $iPrimeiraColuna,
                4,
                $sNomeLinha,
                $sBorda.'R',
                0,
                'L',
                $this->transparente($oStdLinha->ordem)
            );

            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->vlr_superavit_ex_ant, "f"),
                $sBorda.'R'.$sBordaAdd,
                0,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->vlr_naplic_ex_ant, "f"),
                $sBorda.'R'.$sBordaAdd,
                0,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->superavit_aplic_1quadr, "f"),
                $sBorda.'R'.$sBordaAdd,
                0,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->aplic_1q_limite_constitucional, "f"),
                $sBorda.'R'.$sBordaAdd,
                0,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->aplic_apos_1q, "f"),
                $sBorda.'R'.$sBordaAdd,
                0,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
            $this->oPdf->cell(
                $iColunasValores,
                4,
                db_formatar($oStdLinha->vlr_nao_aplic, "f"),
                $sBorda.''.$sBordaAdd,
                1,
                'R',
                $this->transparente($oStdLinha->ordem)
            );
        }

        $this->oPdf->setBold(false);
    }

    protected function cabecalhoIndicadoresTres($tituloColuna = null)
    {
        $sTitulo = 'INDICADOR - Art.25, § 3º - Lei nº 14.113, de 2020 ';
        $sTitulo .= '- (Aplicação do Superávit de Exercício Anterior)3';

        $lBold = $this->oPdf->getBold();
        $this->oPdf->setbold(true);

        $iPrimeiraColuna = 94;
        $iColunasValores = 31;
        $this->oPdf->cell($iPrimeiraColuna, 3, '', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'VALOR DE', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'VALOR NÃO', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'VALOR DE', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'VALOR APLICADO ATÉ', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'VALOR APLICADO', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, '', 'TL', 1, 'C', 1);

        $this->oPdf->cell($iPrimeiraColuna, 3, '', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'SUPERÁVIT PERMITIDO', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'APLICADO NO', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'SUPERÁVIT APLICADO', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'O PRIMEIRO QUADRIMESTRE', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'APÓS  O PRIMEIRO', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'VALOR NÃO', 'L', 1, 'C', 1);

        $this->oPdf->cell($iPrimeiraColuna, 3, 'INDICADOR - Art.25, § 3º - Lei nº 14.113, de 2020', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'NO EXERCÍCIO', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'EXERCÍCIO ANTERIOR', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'ATÉ O PRIMEIRO', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'QUE INTEGRARÁ o ', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'QUADRIMESTRE', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'APLICADO', 'L', 1, 'C', 1);

        $this->oPdf->cell($iPrimeiraColuna, 3, '- (Aplicação do Superávit de Exercício Anterior)3', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'ANTERIOR', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, '', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'QUADRIMESTRE', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, 'LIMITE CONSTITUCIONAL', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, '', 'R', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, '', 'L', 1, 'C', 1);

        $this->oPdf->cell($iPrimeiraColuna, 3, '', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, '(q)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, '(r)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, '(s)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, '(t)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, '(u)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iColunasValores, 3, '(v)', 'BL', 1, 'C', 1);

        $iPosicaoX = $this->oPdf->getX();
        $this->oPdf->setX($iPosicaoX);
        $this->oPdf->setbold($lBold);
    }

    protected function cabecalhoIndicadoresDois($tituloColuna = null)
    {
        $legenda = !empty($tituloColuna) ?
                   $tituloColuna : 'INDICADOR - Art.25, § 3º - Lei nº 14.113, de 2020 - (Máximo de 10% de Superávit)3';

        $lBold = $this->oPdf->getBold();

        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->setbold(true);

        $this->oPdf->cell($iLargura * (0.4) + 30, 3, '', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'VALOR MÁXIMO', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'VALOR NÃO', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'VALOR NÃO APLICADO', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '% NÃO APLICADO', 'TL', 1, 'C', 1);

        $this->oPdf->cell($iLargura * (0.4) + 30, 3, "$legenda", 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'PERMITIDO', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'APLICADO', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'APÓS AJUSTE', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '', 'L', 1, 'C', 1);

        $this->oPdf->cell($iLargura * (0.4) + 30, 3, '', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(m)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(n)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(o)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(p)', 'BL', 1, 'C', 1);

        $iPosicaoX = $this->oPdf->getX();
        $this->oPdf->setX($iPosicaoX);
        $this->oPdf->setbold($lBold);
    }

    protected function cabecalhoIndicadoresUm($tituloColuna = null)
    {
        $legenda = !empty($tituloColuna) ?
                $tituloColuna : 'INDICADORES - Art. 212-A, inciso XI e § 3º - Constituição Federal2';

        $lBold = $this->oPdf->getBold();

        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->setbold(true);

        $this->oPdf->cell($iLargura * (0.4) + 30, 3, '', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'VALOR EXIGIDO', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'VALOR APLICADO', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'VALOR CONSIDERADO', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '% APLICADO', 'TL', 1, 'C', 1);

        $this->oPdf->cell($iLargura * (0.4) + 30, 3, "$legenda", 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'APÓS DEDUÇÕES', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '', 'L', 1, 'C', 1);

        $this->oPdf->cell($iLargura * (0.4) + 30, 3, '', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(i)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(j)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(k)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(l)', 'BL', 1, 'C', 1);

        $iPosicaoX = $this->oPdf->getX();
        $this->oPdf->setX($iPosicaoX);
        $this->oPdf->setbold($lBold);
    }

    protected function imprimeDespesaFundeb(\stdClass $oStdLinha)
    {
        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }
        $sBorda = $this->getBorda($oStdLinha->ordem);

        $lUltimoPeriodo = $this->oRelatorio->getPeriodo()->getCodigo() == 11;

        $lQuebra = 1;
        $iLarguraAdicional = 28;
        if ($lUltimoPeriodo) {
            $iLarguraAdicional = 0;
            $lQuebra = 0;
        }

        $iLargura = $this->oPdf->getAvailWidth();

        $nDotacaoAtual = db_formatar($oStdLinha->emp_atebim, 'f');
        $nEmpAteBin = db_formatar($oStdLinha->liq_atebim, 'f');
        $nLiqAteBin = db_formatar($oStdLinha->desppag, 'f');
        $nDespPaga = db_formatar($oStdLinha->rp_nproc, 'f');
        $nRpNProcessado = db_formatar($oStdLinha->rpnp_sem_dc, 'f');
        if ($lUltimoPeriodo) {
            $nRpNProcessado = db_formatar(abs($oStdLinha->rp_nprocexant), 'f');
        }

        $lTransparencia = $this->transparente($oStdLinha->ordem);

        $this->oPdf->cell(
            ($iLargura + 15) * (0.4) + $iLarguraAdicional,
            4,
            \relatorioContabil::getIdentacao($oStdLinha->nivel).$oStdLinha->descricao,
            "$sBorda R",
            0,
            'L',
            $this->transparente($oStdLinha->ordem)
        );

        $this->oPdf->cell(($iLargura - 7.5) * 0.1 + 7, 4, $nDotacaoAtual, "$sBorda R", 0, 'R', $lTransparencia);
        $this->oPdf->cell(($iLargura - 7.5) * 0.1 + 7, 4, $nEmpAteBin, "$sBorda R", 0, 'R', $lTransparencia);
        $this->oPdf->cell(($iLargura - 30) * 0.1 + 7, 4, $nLiqAteBin, "$sBorda R", 0, 'R', $lTransparencia);
        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nDespPaga, "$sBorda L", $lQuebra, 'R', $lTransparencia);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 4, $nRpNProcessado, "$sBorda L", 1, 'R', $lTransparencia);
        }

        $this->oPdf->setBold(false);
    }

    /**
     * Imprime o cabeçalho da despesa.
     */
    protected function cabecalhoDespesaFundeb($legendaQuadro = null, $sTitulo = null)
    {
        $legenda = 'DESPESAS PREVIDENCIÁRIAS - RPPS (FUNDO EM CAPITALIZAÇÃO)';
        if (!empty($legendaQuadro)) {
            $legenda = $legendaQuadro;
        }
        $lUltimoPeriodo = $this->oRelatorio->getPeriodo()->getCodigo() == 11;

        $lQuebra = 1;
        $iLarguraAdicional = 28;
        if ($lUltimoPeriodo) {
            $iLarguraAdicional = 0;
            $lQuebra = 0;
        }

        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->setBold(true);

        if ($sTitulo != null) {
            $this->oPdf->setBold(true);
            $this->oPdf->cell($this->oPdf->getAvailWidth() + 2, 4, $sTitulo, 'TB', 1, \PDFDocument::ALIGN_CENTER, 1);
        }

        $this->oPdf->cell(($iLargura + 15) * (0.4) + $iLarguraAdicional, 3, '', 'TR', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 7.5) * 0.1 + 7, 3, 'DESPESAS', 'TR', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 7.5) * 0.1 + 7, 3, 'DESPESAS', 'TR', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 30) * 0.1 + 7, 3, 'DESPESAS', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'INCRITAS EM RESTOS', 'TL', $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, 'INSCRITAS EM RESTOS ', 'TL', 1, 'C', 1);
        }

        $this->oPdf->cell(($iLargura + 15) * (0.4) + $iLarguraAdicional, 3, $legenda, 'R', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 7.5) * 0.1 + 7, 3, 'EMPENHADAS', 'R', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 7.5) * 0.1 + 7, 3, 'LIQUIDADAS', 'R', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 30) * 0.1 + 7, 3, 'PAGAS', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'A PAGAR', 'L', $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, 'A PAGAR NÃO PROCES', 'L', 1, 'C', 1);
        }

        $this->oPdf->cell(($iLargura + 15) * (0.4) + $iLarguraAdicional, 3, '', 'R', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 7.5) * 0.1 + 7, 3, 'Até o Bimestre', 'R', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 7.5) * 0.1 + 7, 3, 'Até o Bimestre', 'R', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 30) * 0.1 + 7, 3, 'Até o Bimestre', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'NÃO PROCESSADOS', 'L', $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, 'SADOS (SEM DISPONIBI ', 'L', 1, 'C', 1);
        }

        $this->oPdf->cell(($iLargura + 15) * (0.4) + $iLarguraAdicional, 3, '', 'BR', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 7.5) * 0.1 + 7, 3, '(d)', 'BR', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 7.5) * 0.1 + 7, 3, '(e)', 'BR', 0, 'C', 1);
        $this->oPdf->cell(($iLargura - 30) * 0.1 + 7, 3, '(f)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(g)', 'BL', $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, 'LIDADE DE CAIXA) (h)', 'BL', 1, 'C', 1);
        }

        $this->oPdf->setBold(false);
    }

    protected function imprimeColunaValor(\stdClass $oStdLinha)
    {
        $iLargura = $this->oPdf->getAvailWidth();
        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }

        $sBorda = $this->getBorda($oStdLinha->ordem);

        $iColunaNome = $iLargura * 0.8;
        $iColunaValor = $iLargura * 0.2;

        $this->oPdf->cell(
            $iColunaNome,
            4,
            (\relatorioContabil::getIdentacao($oStdLinha->nivel)).$oStdLinha->descricao,
            $sBorda.'R',
            0,
            'L',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iColunaValor,
            4,
            db_formatar($oStdLinha->valor, 'f'),
            $sBorda.'L',
            1,
            'R',
            $this->transparente($oStdLinha->ordem)
        );

        $this->oPdf->setBold(false);
    }

    protected function imprimeCabecalhoColunaValor($tituloColuna = null)
    {
        $legenda = !empty($tituloColuna) ?
                          $tituloColuna : 'RECURSOS RECEBIDOS EM EXERCÍCIOS ANTERIORES E NÃO UTILIZADOS (SUPERÁVIT)';

        //$lBold = $this->oPdf->getBold();
        $this->oPdf->setBold(true);
        $iLargura = $this->oPdf->getAvailWidth();

        $iColunaTitulo = $iLargura * 0.8;
        $iColunaValor = $iLargura * 0.2;

        $this->oPdf->cell($iColunaTitulo, 4, $legenda, 'TB', 0, 'C', 1);
        $this->oPdf->cell($iColunaValor, 4, 'VALOR', 'TBL', 1, 'C', 1);

        $iPosicaoX = $this->oPdf->getX();
        $this->oPdf->setX($iPosicaoX);
        $this->oPdf->setbold(false);
    }

    protected function imprimeLinhaReceitaComposta(\stdClass $oStdLinha, $altura, $x = 10)
    {
        $iLargura = $this->oPdf->getAvailWidth();
        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }

        $sBorda = $this->getBorda($oStdLinha->ordem);

        $y = $this->oPdf->GetY();

        $this->oPdf->MultiCell(
            $iLargura * 0.4,
            $altura,
            (\relatorioContabil::getIdentacao($oStdLinha->nivel)).$oStdLinha->descricao,
            $sBorda.'R',
            'L',
            $this->transparente($oStdLinha->ordem)
        );

        $this->oPdf->SetXY(($iLargura * 0.4) + 10, $y);

        $this->oPdf->cell(
            $iLargura * 0.30,
            8,
            db_formatar($oStdLinha->prev_atual, 'f'),
            $sBorda.'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );

        $this->oPdf->cell(
            $iLargura * 0.30,
            8,
            db_formatar($oStdLinha->rec_atebim, 'f'),
            $sBorda.'L',
            1,
            'R',
            $this->transparente($oStdLinha->ordem)
        );

        $x += $iLargura;
        $this->oPdf->SetXY($x, $y);
        $this->espaco(8);
    }

    protected function imprimeLinhaReceitaSimples(\stdClass $oStdLinha)
    {
        $this->imprimeReceita($oStdLinha);
    }

    /**
     * IMprime valores das despesas.
     */
    protected function imprimeDespesa(\stdClass $oStdLinha)
    {
        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }
        $sBorda = $this->getBorda($oStdLinha->ordem);

        $lUltimoPeriodo = $this->oRelatorio->getPeriodo()->getCodigo() == 11;

        $lQuebra = 1;
        $iLarguraAdicional = 30;
        if ($lUltimoPeriodo) {
            $iLarguraAdicional = 0;
            $lQuebra = 0;
        }

        $iLargura = $this->oPdf->getAvailWidth();

        $nDotacaoAtual = db_formatar($oStdLinha->dot_atual, 'f');
        $nEmpAteBin = db_formatar($oStdLinha->emp_atebim, 'f');
        $nLiqAteBin = db_formatar($oStdLinha->liq_atebim, 'f');
        $nDespPaga = db_formatar($oStdLinha->desppag, 'f');
        $nRpNProcessado = db_formatar($oStdLinha->rp_nproc, 'f');
        if ($lUltimoPeriodo) {
           // $nRpNProcessado = db_formatar(abs($oStdLinha->rp_nprocexant), 'f');
        }

        $lTransparencia = $this->transparente($oStdLinha->ordem);

        $sBordaTop = '';
        $sBordaB = '';
        if ($oStdLinha->ordem == static::TOTAL_DAS_DESPESAS_CUSTEADAS_RECEITAS_ADICIONAIS) {
            $sBordaTop = 'T';
            $sBordaB = 'B';
        }

        if ($oStdLinha->ordem == static::OUTRAS_DESPESAS_DE_CAPITAL) {
            $sBordaB = 'B';
        }

                /*

    [dot_atual] => 21678400
    [emp_atebim] => 3455522.5
    [liq_atebim] => 3455522.5
    [desppag] => 3228943.54
    [rp_nproc] => 0

        */
        $sNomeLinha = (\relatorioContabil::getIdentacao($oStdLinha->nivel)).$oStdLinha->descricao;
        $iTamanhoLinha = strlen($sNomeLinha);

        if ($iTamanhoLinha > 81 && $lUltimoPeriodo) {
            $sNomeLinha = substr($sNomeLinha, 0, 81);
            $sRestanteLinha = substr(
                (\relatorioContabil::getIdentacao($oStdLinha->nivel)).$oStdLinha->descricao,
                81,
                $iTamanhoLinha
            );

            $this->oPdf->cell(
                $iLargura * (0.4) + $iLarguraAdicional,
                4,
                $sNomeLinha,
                "$sBorda R $sBordaTop",
                0,
                'L',
                $this->transparente($oStdLinha->ordem)
            );

            $this->oPdf->cell($iLargura * 0.1 + 7, 4, '', "$sBorda R $sBordaTop", 0, 'R', $lTransparencia);
            $this->oPdf->cell($iLargura * 0.1 + 7, 4, '', "$sBorda R $sBordaTop", 0, 'R', $lTransparencia);
            $this->oPdf->cell($iLargura * 0.1 + 7, 4, '', "$sBorda R $sBordaTop", 0, 'R', $lTransparencia);
            $this->oPdf->cell($iLargura * 0.1 + 7, 4, '', "$sBorda L $sBordaTop", $lQuebra, 'R', $lTransparencia);
            if ($lUltimoPeriodo) {
                $this->oPdf->cell($iLargura * 0.1, 4, '', "$sBorda L $sBordaTop", 1, 'R', $lTransparencia);
            }

            $this->oPdf->cell(
                $iLargura * (0.4) + $iLarguraAdicional,
                4,
                $sRestanteLinha,
                "$sBorda R $sBordaB",
                0,
                'L',
                $this->transparente($oStdLinha->ordem)
            );

            $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nDotacaoAtual, "$sBorda R $sBordaB", 0, 'R', $lTransparencia);
            $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nEmpAteBin, "$sBorda R $sBordaB", 0, 'R', $lTransparencia);
            $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nLiqAteBin, "$sBorda R $sBordaB", 0, 'R', $lTransparencia);
            $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nDespPaga, "$sBorda L $sBordaB", $lQuebra, 'R', $lTransparencia);
            if ($lUltimoPeriodo) {
                $this->oPdf->cell($iLargura * 0.1, 4, $nRpNProcessado, "$sBorda L $sBordaB", 1, 'R', $lTransparencia);
            }
        } else {
            $this->oPdf->cell(
                $iLargura * (0.4) + $iLarguraAdicional,
                4,
                \relatorioContabil::getIdentacao($oStdLinha->nivel).$oStdLinha->descricao,
                "$sBorda R $sBordaB",
                0,
                'L',
                $this->transparente($oStdLinha->ordem)
            );

            $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nDotacaoAtual, "$sBorda R $sBordaB", 0, 'R', $lTransparencia);
            $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nEmpAteBin, "$sBorda R $sBordaB", 0, 'R', $lTransparencia);
            $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nLiqAteBin, "$sBorda R $sBordaB", 0, 'R', $lTransparencia);
            $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nDespPaga, "$sBorda L $sBordaB", $lQuebra, 'R', $lTransparencia);
            if ($lUltimoPeriodo) {
                $this->oPdf->cell($iLargura * 0.1, 4, $nRpNProcessado, "$sBorda L $sBordaB", 1, 'R', $lTransparencia);
            }
        }

        $this->oPdf->setBold(false);
    }

    /**
     * Imprime o cabeçalho da despesa.
     */
    protected function cabecalhoDespesa($legendaQuadro = null, $sTitulo = null, array $aTituloColuna = null)
    {
        $legenda = 'DESPESAS PREVIDENCIÁRIAS - RPPS (FUNDO EM CAPITALIZAÇÃO)';

        if (!empty($legendaQuadro)) {
            $legenda = $legendaQuadro;
        }

        $sTitulo1 = '';
        $sTitulo2 = $legenda;
        $lUltimoPeriodo = $this->oRelatorio->getPeriodo()->getCodigo() == 11;

        $lQuebra = 1;
        $iLarguraAdicional = 30;

        if (count($aTituloColuna) > 0) {
            $sTitulo1 = $aTituloColuna[0];
            $sTitulo2 = $aTituloColuna[1];
        }

        if ($lUltimoPeriodo) {
            $iLarguraAdicional = 0;
            $lQuebra = 0;
        }



        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->setBold(true);

        if ($sTitulo != null) {
            $this->oPdf->setBold(true);
            $this->oPdf->cell($this->oPdf->getAvailWidth() + 2, 4, $sTitulo, 'TB', 1, \PDFDocument::ALIGN_CENTER, 1);
        }

        $this->oPdf->cell($iLargura * (0.4) + $iLarguraAdicional, 3, "$sTitulo1", 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'DOTAÇÃO', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'DESPESAS', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'DESPESAS', 'TR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'DESPESAS', 'TL', $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, 'INCRITAS EM RESTOS', 'TL', 1, 'C', 1);
        }

        $this->oPdf->cell($iLargura * (0.4) + $iLarguraAdicional, 3, $sTitulo2, 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'ATUALIZADA', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'EMPENHADAS', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'LIQUIDADAS', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'PAGAS', 'L', $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, 'A PAGAR ', 'L', 1, 'C', 1);
        }

        $this->oPdf->cell($iLargura * (0.4) + $iLarguraAdicional, 3, '', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(c)', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'Até o Bimestre', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'Até o Bimestre', 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, 'Até o Bimestre', 'L', $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, 'NÃO PROCESSADOS ', 'L', 1, 'C', 1);
        }

        $this->oPdf->cell($iLargura * (0.4) + $iLarguraAdicional, 3, '', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(d)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(e)', 'BR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, '(f)', 'BL', $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, 'No Exercício (g)', 'BL', 1, 'C', 1);
        }

        $this->oPdf->setBold(false);
    }

    protected function imprimeReceita(\stdClass $oStdLinha)
    {
        $iLargura = $this->oPdf->getAvailWidth();
        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }

        $sBorda = $this->getBorda($oStdLinha->ordem);

        $this->oPdf->cell(
            $iLargura * 0.4,
            4,
            (\relatorioContabil::getIdentacao($oStdLinha->nivel)).$oStdLinha->descricao,
            $sBorda.'R',
            0,
            'L',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.30,
            4,
            db_formatar($oStdLinha->prev_atual, 'f'),
            $sBorda.'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.30,
            4,
            db_formatar($oStdLinha->rec_atebim, 'f'),
            $sBorda.'',
            1,
            'R',
            $this->transparente($oStdLinha->ordem)
        );

        $this->oPdf->setBold(false);
    }

    /**
     * @param $sTituloQuadro
     */
    protected function cabecalhoReceita($sTituloQuadro, $tituloColuna = null, $lExibeSubTitulo = true)
    {
        $legenda = !empty($tituloColuna) ? $tituloColuna : 'RECEITA RESULTANTE DE IMPOSTOS';

        $lBold = $this->oPdf->getBold();
        $this->oPdf->setBold(true);
        $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, $sTituloQuadro, 'TB', 1, \PDFDocument::ALIGN_CENTER, 1);

        if ($lExibeSubTitulo) {
            $sSubTitulo = 'FUNDO EM CAPITALIZAÇÃO (PLANO PREVIDENCIÁRIO)';
            $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "$sSubTitulo", 'TB', 1, \PDFDocument::ALIGN_CENTER, 1);
        }

        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->cell($iLargura * 0.4, 3, '', 'T', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, 'PREVISÃO', 'TLR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, 'RECEITAS REALIZADAS', 'TL', 1, 'C', 1);

        $this->oPdf->cell($iLargura * 0.4, 3, $legenda, '', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, 'ATUALIZADA', 'LR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, 'Até o Bimestre', 'L', 1, 'C', 1);

        $this->oPdf->cell($iLargura * 0.4, 3, '', 'B', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, '(a)', 'BLR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, '(b)', 'BL', 1, 'C', 1);

        $iPosicaoX = $this->oPdf->getX();
        $this->oPdf->setX($iPosicaoX);
        $this->oPdf->setbold($lBold);
    }

    protected function getBorda($iOrdem)
    {
        $sBorda = in_array(
            $iOrdem,
            [static::TOTAL_RECEITA_RESULTANTE_DE_IMPOSTOS,
                   static::TOTAL_DESTINADO_FUNDEB_20,
                   static::VALOR_MINIMO_SER_APLICADO_ALEM_VALOR_DESTINADO,
                   static::TOTAL_DOS_RECURSOS_DO_FUNDEB_DISPONIVEIS,
                   static::TOTAL_DAS_DESPESAS_COM_RECURSOS_DO_FUNDEB,
                   static::TOTAL_DAS_DESPESAS_COM_ACOES_TIPICAS_MDE,
                   static::TOTAL_DAS_DESPESAS_PARA_FINS_DE_LIMITE,
                   static::APLICACAO_EM_MDE_SOBRE_A_RECEITA_RESULTANTE_DE_IMPOSTOS,
                   static::TOTAL_DAS_RECEITAS_ADICIONAIS_FINANCIAMENTO_ENSINO,
                 ]
        ) ? 'TB' : '';

        return $sBorda;
    }

    /**
     * Adiciona as legendas do relatório.
     *
     * @throws \BusinessException
     */
    protected function legendas()
    {
        $sNota1 = '1 SE RESULTADO LÍQUIDO DA TRANSFERÊNCIA (7) > 0 = ACRÉSCIMO RESULTANTE DAS TRANSFERÊNCIAS DO ';
        $sNota1 .= 'FUNDEB, SE RESULTADO LÍQUIDO DA TRANSFERÊNCIA (7) < 0 = DECRÉSCIMO RESULTANTE DAS ';
        $sNota1 .= 'TRANSFERÊNCIAS DO FUNDEB';

        $sNota2 = '2 Limites mínimos anuais a serem cumpridos no encerramento do exercício.';

        $sNota3 = '3 Art. 25, § 3º, Lei 14.113/2020: "Até 10% (dez por cento) dos recursos recebidos à conta dos ';
        $sNota3 .= 'Fundos, inclusive relativos à complementação da União, nos termos do § 2º do art. 16 desta Lei, ';
        $sNota3 .= 'poderão ser utilizados no primeiro quadrimestre do exercício imediatamente subsequente, mediante ';
        $sNota3 .= 'abertura de crédito adicional  utilizados no 1º trimestre do exercício imediatamente ';
        $sNota3 .= 'subseqüente, mediante abertura de crédito adicional." ';

        $sNota4 = '4 Os valores referentes à parcela dos Restos a Pagar inscritos sem disponibilidade financeira ';
        $sNota4 .= 'vinculada à educação deverão ser informados somente no RREO do último bimestre do exercício.';

        $sNota5 = '5 Nos cinco primeiros bimestres do exercício o acompanhamento será feito com base na despesa ';
        $sNota5 .= 'liquidada. No último bimestre do exercício, o valor deverá corresponder ao ';
        $sNota5 .= 'total da despesa empenhada.';

        $sNota6 = '6 As linhas representam áreas de atuação e não correspondem exatamente às subfunções da Função ';
        $sNota6 .= 'Educação. As despesas classificadas nas demais subfunções típicas e nas subfunções atípicas ';
        $sNota6 .= 'deverão ser rateadas para essas áreas de atuação.';

        $sNota7 = '7 Valor inscrito em RPNP sem disponibilidade de caixa, que não deve ser considerado na ';
        $sNota7 .= 'apuração dos indicadores e limites';

        $sNota8 = '8 Controle da execução de restos a pagar considerados no cumprimento do limite mínimo dos ';
        $sNota8 .= 'exercícios anteriores.';

        $aNotas = [
            $sNota1,
            $sNota2,
            $sNota3,
            $sNota4,
            $sNota5,
            $sNota6,
            $sNota7,
            $sNota8,
        ];

        $this->listaNotas($aNotas);

        //$this->notas();
        $oRelatorio = new \relatorioContabil(Relatorio::CODIGO_RELATORIO, false);
        $oRelatorio->notaExplicativa(
            $this->oPdf,
            $this->oRelatorio->getPeriodo()->getCodigo(),
            $this->oPdf->getAvailWidth()
        );
    }

    /**
     * @param $iOrdem
     *
     * @return bool
     */
    protected function transparente($iOrdem)
    {
        return in_array(
            $iOrdem,
            [static::TOTAL_DOS_RECURSOS_DO_FUNDEB_DISPONIVEIS,
             static::TOTAL_DAS_DESPESAS_COM_RECURSOS_DO_FUNDEB,
            ]
        );
    }

    /**
     * @param $iAltura
     */
    protected function espaco($iAltura)
    {
        $this->oPdf->ln($iAltura);
    }

    /**
     * @param array $sessao
     *
     * @return \Ecidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoIV | array | null
     */
    public function sessao($sessao = null)
    {
        if (!empty($sessao)) {
            $this->sessao = $sessao;
        }

        return $this;

        return $this->sessao;
    }

    public function setAnexo(\ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2021\AnexoVIII $oRelatorio)
    {
        $this->oRelatorio = $oRelatorio;
    }

    /**
     * Adiciona o cabeçalho do relatório.
     *
     * @throws \ParameterException
     */
    protected function addHeaders()
    {
        $oPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();
        $sMesInicio = mb_strtoupper(\DBDate::getMesExtenso($this->oRelatorio->getDataInicialPeriodo()->getMes()));
        $sMesFim = mb_strtoupper(\DBDate::getMesExtenso($this->oRelatorio->getDataFinal()->getMes()));

        $this->oPdf->addHeaderDescription('');
        $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oPrefeitura));

        $aInstituicoes = explode(',', $this->oRelatorio->getInstituicoes());

        if (count($aInstituicoes) == 1) {
            $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]);

            if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
                $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
            }
        }

        $nomeRelatorio = 'DEMONSTRATIVO DAS RECEITAS E DESPESAS COM MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO - MDE';
        $periodo = $sMesInicio.' A '.$sMesFim.'/'.$this->oRelatorio->getAno().
            ' - BIMESTRE '.$sMesInicio.'-'.$sMesFim;
        $this->oPdf->addHeaderDescription('RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA ');
        $this->oPdf->addHeaderDescription($nomeRelatorio);
        $this->oPdf->addHeaderDescription($periodo);

        $this->oPdf->open();
        $this->oPdf->addPage();
        $this->oPdf->SetFontSize(6);
        $this->oPdf->SetFillColor(235);
    }

    protected function listaNotas(array $aNotas)
    {
        $this->oPdf->SetFontSize(5);
        foreach ($aNotas as $sNota) {
            $this->oPdf->MultiCell(
                $this->oPdf->getAvailWidth(),
                4,
                $sNota,
                '',
                'L',
                0
            );
        }
    }
}

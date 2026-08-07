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

require_once modification('fpdf151/pdf.php');
require_once modification('fpdf151/assinatura.php');
require_once modification('libs/db_sql.php');
require_once modification('libs/db_utils.php');
require_once modification('classes/db_db_config_classe.php');
require_once modification('libs/db_liborcamento.php');
require_once modification('libs/db_libcontabilidade.php');
require_once modification('classes/db_orccenarioeconomicoparam_classe.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('model/cronogramaFinanceiro.model.php');
require_once modification('model/relatorioContabil.model.php');

try {
    $oGet = db_utils::postMemory($_GET);
    $codigoRelatorio = $oGet->iCodRel;
    $recursos = $oGet->slistaRecursos == '' ? null : explode(',', $oGet->slistaRecursos);
    $oRelatorioContabil = new relatorioContabil($codigoRelatorio);
    $clcronogramaFinanceiro = new cronogramaFinanceiro($oGet->iRec);
    $clcronogramaFinanceiro->setInstituicoes(explode('-', $oGet->sListaInstit));
    $receitas = $clcronogramaFinanceiro->getMetasReceita(null, $recursos);
    $iSomaTotalGeral = 0;

    if ($oGet->iPeriodoImpr == 1 && $oGet->iFormaImpr == 1) {
        $descricao = 'Receita';
        $descricao1 = 'Mensal';

        $head2 = 'Metas Mensais de Arrecadação';
        $head3 = 'Art. 13, da Lei Complementar 101/2000';
        $head4 = "Orçamento do exercício de {$clcronogramaFinanceiro->getAno()}";
        $head5 = 'Valores expressos por conta de receita';

        $receitasRelatorio = array();
        $totaisRelatorio = array();

        foreach ($receitas as $receita) {
            if (empty($receita->o70_codigo)) {
                continue;
            }



            $receitaRelatorio = new stdClass();
            $receitaRelatorio->o70_codigo = $receita->o57_fonte;
            $receitaRelatorio->o57_fonte = $receita->o57_fonte;
            $receitaRelatorio->o57_descr = substr(urldecode($receita->o57_descr), 0, 35);
            $receitaRelatorio->aMetas = new stdClass();
            $receitaRelatorio->aMetas->dados = array();

            foreach ($receita->aMetas->dados as $chave => $dado) {
                $dadoRelatorio = new stdClass();
                $dadoRelatorio->valor = $dado->valor;

                if (array_key_exists($chave, $totaisRelatorio)) {
                    $totaisRelatorio[$chave] += $dado->valor;
                } else {
                    $totaisRelatorio[$chave] = $dado->valor;
                }

                $receitaRelatorio->aMetas->getValues = round($receita->o70_valor, 2);
                $receitaRelatorio->aMetas->dados[] = $dado;
            }

            $receitasRelatorio[] = $receitaRelatorio;
        }
    } else {
        if ($oGet->iPeriodoImpr == 1 && $oGet->iFormaImpr == 2) {
            $descricao = 'Recursos';
            $descricao1 = 'Mensal';
            $head2 = 'Metas Mensais de Arrecadação';
            $head3 = 'Art. 13, da Lei Complementar 101/2000';
            $head4 = "Orçamento do exercício de {$clcronogramaFinanceiro->getAno()}";
            $head5 = 'Valores expressos por recurso';

            $receitasRelatorio = array();
            $totaisRelatorio = array();

            for ($jInd = 0; $jInd < 12; $jInd++) {
                $totaisRelatorio[$jInd] = 0;
            }

            foreach ($receitas as $receita) {
                if (empty($receita->o70_codigo)) {
                    continue;
                }
                $recurso = \ECidade\Financeiro\Orcamento\Repository\RecursoRepository::getByCodigo($receita->o70_codigo);

                if (array_key_exists($receita->o70_codigo, $receitasRelatorio)) {
                    $iNumRowsDados = count($receita->aMetas->dados);
                    $receitasRelatorio[$receita->o70_codigo]->aMetas->getValues += round(
                        $receita->o70_valor,
                        2
                    );

                    for ($jInd = 0; $jInd < $iNumRowsDados; $jInd++) {
                        $receitasRelatorio[$receita->o70_codigo]->aMetas->dados[$jInd]->valor += $receita->aMetas->dados[$jInd]->valor;

                        if (!empty($receita->o70_codigo)) {
                            $totaisRelatorio[$jInd] += $receita->aMetas->dados[$jInd]->valor;
                        }
                    }
                } else {
                    $descricao = urldecode($receita->o15_descr) . " - " .
                        $recurso->getComplementoRecursoVinculado()->getDescricao();
                    $receitasRelatorio[$receita->o70_codigo] = new stdClass();
                    $receitasRelatorio[$receita->o70_codigo]->o70_codigo = $receita->o70_codigo;
                    $receitasRelatorio[$receita->o70_codigo]->fonteRecurso = $recurso->getFonteDeRecurso();
                    $receitasRelatorio[$receita->o70_codigo]->o57_fonte = $receita->o57_fonte;
                    $receitasRelatorio[$receita->o70_codigo]->o57_descr = substr(
                        urldecode($descricao), 0, 35
                    );
                    $iNumRowsDados = count($receita->aMetas->dados);

                    for ($jInd = 0; $jInd < $iNumRowsDados; $jInd++) {
                        $receitasRelatorio[$receita->o70_codigo]->aMetas->dados[$jInd]->valor = $receita->aMetas->dados[$jInd]->valor;
                        $receitasRelatorio[$receita->o70_codigo]->aMetas->getValues = round(
                            $receita->o70_valor, 2
                        );

                        if (!empty($receita->o70_codigo)) {
                            $totaisRelatorio[$jInd] += $receitasRelatorio[$receita->o70_codigo]->aMetas->dados[$jInd]->valor;
                        }
                    }
                }
            }
        } else {
            if ($oGet->iPeriodoImpr == 2 && $oGet->iFormaImpr == 1) {
                $descricao = 'Receita';
                $descricao1 = 'Bimestral';

                $head2 = 'Metas Bimestrais de Arrecadação';
                $head3 = 'Art. 13, da Lei Complementar 101/2000';
                $head4 = "Orçamento do exercício de {$clcronogramaFinanceiro->getAno()}";
                $head5 = 'Valores expressos por conta de receita';

                $receitasRelatorio = array();
                $totaisRelatorio = array();

                for ($iInd = 0; $iInd < 6; $iInd++) {
                    $totaisRelatorio[$iInd] = 0;
                }

                $iNumRows = count($receitas);

                for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
                    if (empty($receitas[$iInd]->o70_codigo)) {
                        continue;
                    }

                    $receitasRelatorio[$iInd] = new stdClass();
                    $receitasRelatorio[$iInd]->o70_codigo = $receitas[$iInd]->o57_fonte;
                    $receitasRelatorio[$iInd]->o57_fonte = $receitas[$iInd]->o57_fonte;
                    $receitasRelatorio[$iInd]->o57_descr = substr(urldecode($receitas[$iInd]->o57_descr), 0, 35);
                    $iNumRowsDados = count($receitas[$iInd]->aMetas->dados);
                    $receitasRelatorio[$iInd]->aMetas->getValues = round($receitas[$iInd]->o70_valor, 2);
                    $indice = 0;

                    for ($jInd = 0; $jInd < $iNumRowsDados; $jInd++) {
                        if ($jInd % 2 == 0 || $jInd == 0) {
                            $receitasRelatorio[$iInd]->aMetas->dados[$indice]->valor = $receitas[$iInd]->aMetas->dados[$jInd]->valor +
                                $receitas[$iInd]->aMetas->dados[$jInd + 1]->valor;

                            if (!empty($receitas[$iInd]->o70_codigo)) {
                                $totaisRelatorio[$indice] += $receitasRelatorio[$iInd]->aMetas->dados[$indice]->valor;
                            }

                            $indice++;
                        }
                    }
                }
            } else {
                if ($oGet->iPeriodoImpr == 2 && $oGet->iFormaImpr == 2) {
                    $descricao = 'Recursos';
                    $descricao1 = 'Bimestral';

                    $head2 = 'Metas Bimestrais de Arrecadação';
                    $head3 = 'Art. 13, da Lei Complementar 101/2000';
                    $head4 = "Orçamento do exercício de {$clcronogramaFinanceiro->getAno()}";
                    $head5 = 'Valores expressos por recurso';

                    $receitasRelatorio = array();
                    $totaisRelatorio = array();

                    for ($iInd = 0; $iInd < 6; $iInd++) {
                        $totaisRelatorio[$iInd] = 0;
                    }

                    $iNumRows = count($receitas);

                    for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
                        if (empty($receitas[$iInd]->o70_codigo)) {
                            continue;
                        }
                        $recurso = \ECidade\Financeiro\Orcamento\Repository\RecursoRepository::getByCodigo(
                            $receitas[$iInd]->o70_codigo
                        );

                        if (array_key_exists($receitas[$iInd]->o70_codigo, $receitasRelatorio)) {
                            $iNumRowsDados = count($receitas[$iInd]->aMetas->dados);
                            $indice = 0;

                            for ($jInd = 0; $jInd < ($iNumRowsDados); $jInd++) {
                                if ($jInd % 2 == 0 || $jInd == 0) {
                                    $soma = $receitas[$iInd]->aMetas->dados[$jInd]->valor + $receitas[$iInd]->aMetas->dados[$jInd + 1]->valor;
                                    $receitasRelatorio[$receitas[$iInd]->o70_codigo]->aMetas->dados[$indice]->valor += $soma;
                                    $receitasRelatorio[$receitas[$iInd]->o70_codigo]->aMetas->getValues += round($soma,
                                        2);

                                    if (!empty($receitas[$iInd]->o70_codigo)) {
                                        $totaisRelatorio[$indice] += $soma;
                                    }

                                    $indice++;
                                }
                            }
                        } else {
                            $descricao = urldecode($receitas[$iInd]->o15_descr) . " - " .
                                $recurso->getComplementoRecursoVinculado()->getDescricao();

                            $receitasRelatorio[$receitas[$iInd]->o70_codigo] = new stdClass();
                            $receitasRelatorio[$receitas[$iInd]->o70_codigo]->o70_codigo = $receitas[$iInd]->o70_codigo;
                            $receitasRelatorio[$receitas[$iInd]->o70_codigo]->o57_fonte = $receitas[$iInd]->o57_fonte;
                            $receitasRelatorio[$receitas[$iInd]->o70_codigo]->fonteRecurso = $recurso->getFonteDeRecurso();
                            $receitasRelatorio[$receitas[$iInd]->o70_codigo]->o57_descr = substr(
                                urldecode($descricao), 0, 35
                            );

                            $iNumRowsDados = count($receitas[$iInd]->aMetas->dados);
                            $receitasRelatorio[$receitas[$iInd]->o70_codigo]->aMetas->getValues = 0;
                            $indice = 0;

                            for ($jInd = 0; $jInd < ($iNumRowsDados); $jInd++) {
                                if ($jInd % 2 == 0 || $jInd == 0) {
                                    $soma = $receitas[$iInd]->aMetas->dados[$jInd]->valor + $receitas[$iInd]->aMetas->dados[$jInd + 1]->valor;
                                    $receitasRelatorio[$receitas[$iInd]->o70_codigo]->aMetas->dados[$indice]->valor = $soma;
                                    $receitasRelatorio[$receitas[$iInd]->o70_codigo]->aMetas->getValues += round($soma,
                                        2);

                                    if (!empty($receitas[$iInd]->o70_codigo)) {
                                        $totaisRelatorio[$indice] += $soma;
                                    }

                                    $indice++;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    foreach ($receitasRelatorio as $oReceita) {
        $iSomaTotalGeral += $oReceita->aMetas->getValues;
    }

    $oRelatorio = new stdClass();
    $oRelatorio->linha = array();
    $oRelatorio->linha[0] = criaStdClass($descricao, 86);
    $oRelatorio->linha[1] = criaStdClass($descricao1, 194);

    if ($oGet->iPeriodoImpr == 1) {
        $tamanho = 26;
        $aCabecalho = array();
        $aCabecalho[0] = criaStdClass('Estrutural', 26);
        $aCabecalho[1] = criaStdClass('Descricao', 60);
        $aCabecalho[2] = criaStdClass('Janeiro', $tamanho);
        $aCabecalho[3] = criaStdClass('Fevereiro', $tamanho);
        $aCabecalho[4] = criaStdClass('Março', $tamanho);
        $aCabecalho[5] = criaStdClass('Abril', $tamanho);
        $aCabecalho[6] = criaStdClass('Maio', $tamanho);
        $aCabecalho[7] = criaStdClass('Junho', $tamanho);
        $aCabecalho[8] = criaStdClass('Julho', $tamanho);
        $aCabecalho[9] = criaStdClass('Agosto', $tamanho);
        $aCabecalho[10] = criaStdClass('Setembro', $tamanho);
        $aCabecalho[11] = criaStdClass('Outubro', $tamanho);
        $aCabecalho[12] = criaStdClass('Novembro', $tamanho);
        $aCabecalho[13] = criaStdClass('Dezembro', $tamanho);
        $aCabecalho[14] = criaStdClass('Total', 38);

        $oRelatorio->iPeriocidade = $aCabecalho;

        $oRelatorio->linhaTotal = new stdClass();
        $oRelatorio->linhaTotal->totalDescricao = 'Totalização Geral';
        $oRelatorio->linhaTotal->totalTamanho = $aCabecalho[0]->tamanho + $aCabecalho[1]->tamanho;
    } else {
        if ($oGet->iPeriodoImpr == 2) {
            $tamanho = 27;

            $aCabecalho = array();
            $aCabecalho[0] = criaStdClass('Estrutural', 26);
            $aCabecalho[1] = criaStdClass('Descricao', 60);
            $aCabecalho[2] = criaStdClass('1º Bimestre', $tamanho);
            $aCabecalho[3] = criaStdClass('2º Bimestre', $tamanho);
            $aCabecalho[4] = criaStdClass('3º Bimestre', $tamanho);
            $aCabecalho[5] = criaStdClass('4º Bimestre', $tamanho);
            $aCabecalho[6] = criaStdClass('5º Bimestre', $tamanho);
            $aCabecalho[7] = criaStdClass('6º Bimestre', $tamanho);
            $aCabecalho[8] = criaStdClass('Total', 32);

            $oRelatorio->iPeriocidade = $aCabecalho;
            $oRelatorio->linhaTotal = new stdClass();
            $oRelatorio->linhaTotal->totalDescricao = 'Totalização Geral';
            $oRelatorio->linhaTotal->totalTamanho = $aCabecalho[0]->tamanho + $aCabecalho[1]->tamanho;
        }
    }

    $cldb_config = new cl_db_config();
    $rsConfig = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit')));
    $oConfig = db_utils::fieldsMemory($rsConfig, 0);

    $head1 = "MUNICÍPIO DE " . strtoupper($oConfig->munic);

    $pdf = new PDF('L');
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->SetFillColor(235);
    $pdf->SetAutoPageBreak(false);
    $iAlt = 5;

    if ($oGet->iPeriodoImpr == 1) {
        $background = 1;
        $pdf_cabecalho = true;

        foreach ($receitasRelatorio as $key => $value) {
            if ($receitasRelatorio[$key]->aMetas->getValues == 0) {
                continue;
            }

            if ($pdf->GetY() > $pdf->h - 25 || $pdf_cabecalho == true) {
                $pdf->SetFont('Arial', 'B', 8);
                $pdf_cabecalho = false;
                $pdf->AddPage();
                $pdf->Cell(
                    $oRelatorio->linha[0]->tamanho,
                    $iAlt,
                    $oRelatorio->linha[0]->descricao,
                    'T',
                    0,
                    'C',
                    1
                );
                $pdf->Cell(
                    $oRelatorio->linha[1]->tamanho,
                    $iAlt,
                    $oRelatorio->linha[1]->descricao,
                    'TL',
                    1,
                    'C',
                    1
                );

                $iNumRows = count($oRelatorio->iPeriocidade);

                for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
                    if (($oGet->iPeriodoImpr == 1 && $iInd == 8)) {
                        $pdf->Cell(
                            $oRelatorio->iPeriocidade[14]->tamanho,
                            $iAlt,
                            $oRelatorio->iPeriocidade[14]->descricao,
                            'TL',
                            0,
                            'C',
                            1
                        );

                        $pdf->Ln();
                        $pdf->Cell($oRelatorio->iPeriocidade[0]->tamanho,
                            $iAlt,
                            '',
                            'B',
                            0,
                            'C',
                            1
                        );
                        $pdf->Cell(
                            $oRelatorio->iPeriocidade[1]->tamanho,
                            $iAlt,
                            '',
                            'BL',
                            0,
                            'C',
                            1
                        );

                    }

                    if (($oGet->iPeriodoImpr == 1 && $iInd == 14)) {
                        $pdf->Cell(
                            $oRelatorio->iPeriocidade[$iInd]->tamanho,
                            $iAlt,
                            '',
                            'BL',
                            0,
                            'C',
                            1
                        );
                    } else {
                        $borda = 'LTB';

                        if ($iInd == 0) {
                            $borda = 'TB';
                        }

                        $pdf->Cell(
                            $oRelatorio->iPeriocidade[$iInd]->tamanho,
                            $iAlt,
                            $oRelatorio->iPeriocidade[$iInd]->descricao,
                            $borda,
                            0,
                            'C',
                            1
                        );
                    }
                }

                $pdf->Ln();
            }

            $background = $background == 1 ? 0 : 1;
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell($oRelatorio->iPeriocidade[0]->tamanho, $iAlt, '', 'RT', 0, 'C', $background);
            $pdf->Cell($oRelatorio->iPeriocidade[1]->tamanho, $iAlt, '', 'RLT', 0, 'L', $background);

            $iNumRowsDados = count($receitasRelatorio[$key]->aMetas->dados);

            for ($jInd = 0; $jInd < $iNumRowsDados; $jInd++) {
                if (($oGet->iPeriodoImpr == 1 && $jInd == 6)) {
                    $pdf->Cell($oRelatorio->iPeriocidade[14]->tamanho, $iAlt, '', 'TL', 0, 'C', $background);
                    $pdf->Ln();

                    $codigoImprimir = $receitasRelatorio[$key]->o70_codigo;
                    if ($oGet->iFormaImpr == 2) {
                        $codigoImprimir = $receitasRelatorio[$key]->fonteRecurso;
                    }
                    $pdf->Cell(
                        $oRelatorio->iPeriocidade[0]->tamanho,
                        $iAlt,
                        $codigoImprimir,
                        'BR',
                        0,
                        'C',
                        $background
                    );
                    $pdf->Cell(
                        $oRelatorio->iPeriocidade[1]->tamanho,
                        $iAlt,
                        $receitasRelatorio[$key]->o57_descr,
                        'BLR',
                        0,
                        'L',
                        $background
                    );
                }

                $pdf->Cell(
                    $oRelatorio->iPeriocidade[$jInd + 2]->tamanho,
                    $iAlt,
                    db_formatar($receitasRelatorio[$key]->aMetas->dados[$jInd]->valor, 'f'),
                    'BTL',
                    0,
                    'R',
                    $background
                );
            }

            $pdf->Cell(
                $oRelatorio->iPeriocidade[$jInd + 2]->tamanho,
                $iAlt,
                db_formatar($receitasRelatorio[$key]->aMetas->getValues, 'f'),
                'LB',
                0,
                'R',
                $background
            );
            $pdf->Ln();
        }

        $iNumRows = count($oRelatorio->iPeriocidade);
        $iNumRowsTotais = count($totaisRelatorio);
        $somaTotal = 0;

        for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
            if (($oGet->iPeriodoImpr == 1 && $iInd == 8)) {
                $pdf->Cell($oRelatorio->iPeriocidade[14]->tamanho, $iAlt, '', 'TL', 0, 'C', 1);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Ln();
                $pdf->Cell(
                    $oRelatorio->linhaTotal->totalTamanho,
                    $iAlt,
                    $oRelatorio->linhaTotal->totalDescricao,
                    'RB',
                    0,
                    'R',
                    1
                );
            }
            $pdf->SetFont('Arial', '', 8);

            if ($iInd == 0) {
                $pdf->Cell($oRelatorio->linhaTotal->totalTamanho, $iAlt, '', 'RT', 0, 'R', 1);
            } else {
                if ($iInd > 1 && $iInd < $iNumRowsTotais + 2) {
                    $somaTotal += $totaisRelatorio[$iInd - 2];

                    $pdf->Cell(
                        $oRelatorio->iPeriocidade[$iInd]->tamanho,
                        $iAlt,
                        db_formatar($totaisRelatorio[$iInd - 2], 'f'),
                        'LTB',
                        0,
                        'C',
                        1
                    );
                }
            }
            if ($iInd + 1 == $iNumRows) {
                $pdf->Cell(
                    $oRelatorio->iPeriocidade[$iInd]->tamanho,
                    $iAlt,
                    db_formatar($iSomaTotalGeral, 'f'),
                    'LB',
                    0,
                    'R',
                    1
                );
            }
        }

        $pdf->Ln();
    } else {
        if ($oGet->iPeriodoImpr == 2) {
            $pdf_cabecalho = true;
            $background = 1;

            foreach ($receitasRelatorio as $key => $value) {
                if ($receitasRelatorio[$key]->aMetas->getValues == 0) {
                    continue;
                }

                if ($pdf->GetY() > $pdf->h - 25 || $pdf_cabecalho == true) {
                    $pdf_cabecalho = false;

                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->AddPage();
                    $pdf->Cell(
                        $oRelatorio->linha[0]->tamanho,
                        $iAlt,
                        $oRelatorio->linha[0]->descricao,
                        'TB',
                        0,
                        'C',
                        1
                    );
                    $pdf->Cell(
                        $oRelatorio->linha[1]->tamanho,
                        $iAlt,
                        $oRelatorio->linha[1]->descricao,
                        'TBL',
                        1,
                        'C',
                        1
                    );

                    $iNumRows = count($oRelatorio->iPeriocidade);

                    for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
                        if ($iInd == 0) {
                            $pdf->Cell(
                                $oRelatorio->iPeriocidade[$iInd]->tamanho,
                                $iAlt,
                                $oRelatorio->iPeriocidade[$iInd]->descricao,
                                'TB',
                                0,
                                'C',
                                1
                            );
                        } else {
                            $pdf->Cell(
                                $oRelatorio->iPeriocidade[$iInd]->tamanho,
                                $iAlt,
                                $oRelatorio->iPeriocidade[$iInd]->descricao,
                                'TBL',
                                0,
                                'C',
                                1
                            );
                        }
                    }

                    $pdf->Ln();
                }

                $background = $background == 1 ? 0 : 1;

                $pdf->SetFont('Arial', '', 7);
                $codigoImprimir = $receitasRelatorio[$key]->o70_codigo;
                if ($oGet->iFormaImpr == 2) {
                    $codigoImprimir = $receitasRelatorio[$key]->fonteRecurso;
                }
                $pdf->Cell(
                    $oRelatorio->iPeriocidade[0]->tamanho,
                    $iAlt,
                    $codigoImprimir,
                    'TB',
                    0,
                    'C',
                    $background
                );

                $pdf->Cell(
                    $oRelatorio->iPeriocidade[1]->tamanho,
                    $iAlt,
                    $receitasRelatorio[$key]->o57_descr,
                    'LTB',
                    0,
                    'L',
                    $background
                );

                $iNumRowsDados = count($receitasRelatorio[$key]->aMetas->dados);

                for ($jInd = 0; $jInd < $iNumRowsDados; $jInd++) {
                    $pdf->Cell(
                        $oRelatorio->iPeriocidade[$jInd + 2]->tamanho,
                        $iAlt,
                        db_formatar($receitasRelatorio[$key]->aMetas->dados[$jInd]->valor, 'f'),
                        1,
                        0,
                        'R',
                        $background
                    );
                }

                $pdf->Cell(
                    $oRelatorio->iPeriocidade[$jInd + 2]->tamanho,
                    $iAlt,
                    db_formatar($receitasRelatorio[$key]->aMetas->getValues, 'f'),
                    'LTB',
                    0,
                    'R',
                    $background
                );
                $pdf->Ln();
            }

            $iNumRows = count($oRelatorio->iPeriocidade);
            $iNumRowsTotais = count($totaisRelatorio);
            $somaTotal = 0;

            for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
                if ($iInd == 0) {
                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->Cell(
                        $oRelatorio->linhaTotal->totalTamanho,
                        $iAlt,
                        $oRelatorio->linhaTotal->totalDescricao,
                        'TB',
                        0,
                        'R',
                        1
                    );
                    $pdf->SetFont('Arial', '', 7);
                } else {
                    if ($iInd > 1 && $iInd < $iNumRowsTotais + 2) {
                        if ($receitasRelatorio) {
                            $somaTotal += $totaisRelatorio[$iInd - 2];
                        }

                        $pdf->Cell(
                            $oRelatorio->iPeriocidade[$iInd]->tamanho,
                            $iAlt,
                            db_formatar($totaisRelatorio[$iInd - 2], 'f'),
                            'TBL',
                            0,
                            'R',
                            1
                        );
                    }
                }

                if ($iInd + 1 == $iNumRows) {
                    $pdf->Cell(
                        $oRelatorio->iPeriocidade[$iInd]->tamanho,
                        $iAlt, db_formatar($iSomaTotalGeral, 'f'),
                        'LTB',
                        0,
                        'R',
                        1
                    );
                }
            }

            $pdf->Ln();
        }
    }

    $pdf->Ln();
    $oRelatorioContabil->getNotaExplicativa($pdf, 1);
    $pdf->Output();
} catch (Exception $exception) {
    db_redireciona("db_erros.php?fechar=true&db_erro={$exception->getMessage()}");
}


/**
 * @param string $descricao
 * @param integer $tamanho
 * @return object
 */
function criaStdClass($descricao, $tamanho)
{
    return (object)[
        "descricao" => $descricao,
        "tamanho" => $tamanho,
    ];
}

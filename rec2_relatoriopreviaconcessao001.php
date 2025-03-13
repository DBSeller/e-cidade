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

use ECidade\RecursosHumanos\RH\ConcessaoDireitos\Repository\AssentConceedeConf;
use ECidade\RecursosHumanos\RH\ConcessaoDireitos\Repository\AssentConfig;
use ECidade\RecursosHumanos\RH\ConcessaoDireitos\Repository\ConcessaoAssentRelatorio;
use PhpParser\Node\Expr\Cast\Object_;

require_once(modification("src/RecursosHumanos/RH/ConcessaoDireitos/Repository/ConcessaoAssenRelatorio.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("fpdf151/PDFDocument.php"));

try {
    $object = JSON::create()->parse(str_replace("\\", "", $_POST["object"]));

    if (empty($object->rh500_sequencial) || empty($object->datainicio) || empty($object->datafinal)) {
        throw new BusinessException("Dados não encontrados!");
    }

    $oPdf = new PDFDocument();
    $oPdf->addHeaderDescription('');
    $oPdf->addHeaderDescription('');
    $oPdf->addHeaderDescription('Instituição: ' . db_getsession("DB_instit"));
    if ($object->matricula) {
        $oPdf->addHeaderDescription('Matricula: ' . $object->matricula);
    }
    if ($object->tipo != 'relatorio') {
        $oPdf->addHeaderDescription('Data inicial: ' . $object->datainicio);
        $oPdf->addHeaderDescription('Data final: ' . $object->datafinal);
    }

    $oPdf->Open();
    $oPdf->setBold(true);
    $oPdf->AddPage();
    pdfHeader($oPdf);
    $oPdf->SetFontSize(12);
    if ($object->tipo == 'relatorio') {
        $oPdf->Cell(200, 12, "Relatório concessões de direitos", 0, 1, 'C', 0);
    } else {
        $oPdf->Cell(200, 12, "Relatório prévia", 0, 1, 'C', 0);
    }

    $oPdf->SetFontSize(8);
    $oPdf->setBold(true);
    $oPdf->Cell(17, 4, "Matricula", 'BRLT', 0, 'C', 0);
    $oPdf->Cell(60, 4, "Servidor", 'BRLT', 0, 'C', 0);
    $oPdf->Cell(12, 4, "Ordem", 'BRLT', 0, 'C', 0);
    $oPdf->Cell(20, 4, "Percentual", 'BRLT', 0, 'C', 0);
    $oPdf->Cell(22, 4, "Concessão", 'BRLT', 0, 'C', 0);
    $oPdf->Cell(25, 4, "Aseentamento", 'BRLT', 0, 'C', 0);
    $oPdf->Cell(35, 4, "Portaria", 'BRLT', 1, 'C', 0);
    $oPdf->setBold(false);

    $assentConcedeConf = [];
    $assent = AssentConfig::assentConf($object->rh500_sequencial)[0];
    $assentConcedeConf = AssentConceedeConf::assentConcedeConf($object->rh500_sequencial);
    
    $rh504_seqassentconf = $object->rh500_sequencial;

    $datainicio = explode("/", $object->datainicio);
    $datainicio = $datainicio[2] . '-' . $datainicio[1] . '-' . $datainicio[0];

    $datafinal = explode("/", $object->datafinal);
    $datafinal = $datafinal[2] . '-' . $datafinal[1] . '-' . $datafinal[0];
    $valido = true;

    $assentamentos = array_map(function ($assenta) {
        if ($assenta['rh503_acao'] == 3) {
            return $assenta['rh503_codigo'];
        }
    }, $assentConcedeConf);

    $dados = ConcessaoAssentRelatorio::relatorioPrevia(
        $object->matricula,
        $rh504_seqassentconf,
        $datainicio,
        $datafinal,
        array_filter($assentamentos)
    );

    if (empty($dados)) {
        throw new Exception("Nenhum registro encontrado");
    }

    foreach ($dados as $key => $value) {
        /** verifica se concede ou nao concede */
        $assentam = $assent['rh500_condede'];
        $concede = false;
        $naoconcede = false;
        $data = explode("-", $value['data']);
        $dataportaria = $data[2] . '/' . $data[1] . '/' . $data[0];
        $dataassentamento = $data[2] . '/' . $data[1] . '/' . $data[0];
        $concede = false;
        $naoconcede = false;

        foreach ($assentConcedeConf as $key => $assentconcedeconf) {
            if ($value['ordem'] == 1) {
                $assenta = ConcessaoAssentRelatorio::assentServidor(
                    $assentconcedeconf['rh503_codigo'],
                    $object->rh500_sequencial,
                    $value['matricula'],
                    $value['data'],
                    1
                );
            } else {
                $assenta = ConcessaoAssentRelatorio::assentServidor(
                    $assentconcedeconf['rh503_codigo'],
                    $object->rh500_sequencial,
                    $value['matricula'],
                    $value['data'],
                    2
                );
            }

            $dias = 0;
            foreach ($assenta as $key => $ass) {
                if ($assentconcedeconf['rh503_tipo'] == 1) { // se acumula
                    $dias += $ass['h16_quant']; // soma os dias
                    if ($assentconcedeconf['rh503_acao'] == 1) { // concede
                        if ($assentconcedeconf['rh503_formula'] == '+dias') { // +dias
                            $condicao = $dias . $assentconcedeconf['rh503_condicao'];
                        } else { // + Meses
                            //converter pra meses
                            $condicao = intdiv($dias, 30) . $assentconcedeconf['rh503_condicao'];
                        }
                        eval('if(' . $condicao . '){ $concede = true;}');
                    } elseif ($assentconcedeconf['rh503_acao'] == 2) { // nao concede
                        if ($assentconcedeconf['rh503_formula'] == '+dias') { // +dias
                            $condicao = $dias . $assentconcedeconf['rh503_condicao'];
                        } else { // + Meses
                            //converter pra meses
                            $condicao = (intdiv($dias, 30)) . $assentconcedeconf['rh503_condicao'];
                        }
                        eval('if(' . $condicao . '){ $naoconcede = true;}');
                    }
                } elseif ($assentconcedeconf['rh503_tipo'] == 2) { // se nao acumula
                    $dias = $ass['h16_quant'];
                    if ($assentconcedeconf['rh503_acao'] == 1) { // concede
                        if ($assentconcedeconf['rh503_formula'] == '+dias') { // +dias
                            $condicao = $dias . $assentconcedeconf['rh503_condicao'];
                        } else { // + Meses
                            //converter pra meses
                            $condicao = (intdiv($dias, 30)) . $assentconcedeconf['rh503_condicao'];
                        }
                        eval('if(' . $condicao . '){ $concede = true;}');
                    } elseif ($assentconcedeconf['rh503_acao'] == 2) { // nao concede
                        if ($assentconcedeconf['rh503_formula'] == '+dias') { // +dias
                            $condicao = $dias . $assentconcedeconf['rh503_condicao'];
                        } else { // + Meses
                            //converter pra meses
                            $condicao = intdiv($dias, 30) . $assentconcedeconf['rh503_condicao'];
                        }
                        eval('if(' . $condicao . '){ $naoconcede = true;}');
                    }
                } elseif ($assentconcedeconf['rh503_tipo'] == 3) {
                    if ($assentconcedeconf['rh503_formula'] == '+dias') { // +dias
                        $dias += $ass['h16_quant']; // soma os dias
                        $calculo = ($dias) . $assentconcedeconf['rh503_condicao'];
                        $c = 0;
                        eval('$c = ' . $calculo . ';');
                        $dataassentamento = date('d/m/Y', strtotime('+' . $c . ' days', strtotime($value['data'])));
                    } else { // + Meses
                        $dias += $ass->h16_quant; // soma os dias
                        $calculo = ($dias * 30) . $assentconcedeconf->rh503_condicao;
                        $c = 0;
                        eval('$c = ' . $calculo . ';');
                        $dataassentamento = date('d/m/Y', strtotime('+' . $c . ' days', strtotime($value['data'])));
                    }
                }
            }
        }
        if ($concede == false && $naoconcede == false || $concede == true) {
            $assentam = $assent['rh500_condede'];
        } elseif ($concede == false && $naoconcede == true) {
            $assentam = $assent['rh500_naoconcede'];
        }
        /** se concede ou nao concede faz isso */

        $portariatipo = ConcessaoAssentRelatorio::portariatipo($assentam)[0];

        $admissao = ConcessaoAssentRelatorio::admissao($value['matricula'])[0];

        if (strtotime($admissao['rh01_admiss']) > strtotime($value['data'])) {
            $portariatipo = 'Tempo Averbado';
            $dataassentamento = $dataportaria;
        } else {
            $portariatipo = $portariatipo['h30_amparolegal'];
        }

        $oPdf->Cell(17, 4, $value['matricula'], 'BRLT', 0, 'C', 0);
        $oPdf->Cell(60, 4, mb_strimwidth($value['nome'], 0, 35, "..."), 'BRLT', 0, 'L', 0);
        $oPdf->Cell(12, 4, $value['ordem'], 'BRLT', 0, 'C', 0);
        $oPdf->Cell(20, 4, $value['percentual'] . ' %', 'BRLT', 0, 'C', 0);
        $oPdf->Cell(22, 4, $dataportaria, 'BRLT', 0, 'C', 0);
        $oPdf->Cell(25, 4, $dataassentamento, 'BRLT', 0, 'C', 0);
        $oPdf->Cell(35, 4, $portariatipo, 'BRLT', 1, 'C', 0);
    }

    $oPdf->showPDF();
} catch (Exception $exception) {
    return db_redireciona("db_erros.php?fechar=true&db_erro={$exception->getMessage()}");
}

function pdfHeader($oPdf)
{
    $oInstituicao = new Instituicao(db_getsession("DB_instit"));
    $iColuna = $oPdf->GetLeftMargin();

    $oPdf->setXY($iColuna, 1);
    $oPdf->image("imagens/files/{$oInstituicao->getImagemLogo()}", $iColuna, 3, 20);

    $oPdf->setBold(true);
    $oPdf->setItalic(true);
    $oPdf->setUnderline(false);
    $oPdf->setFontSize(9);

    if (strlen($oInstituicao->getDescricao()) > 42) {
        $oPdf->setFontSize(8);
    }

    $iColunaTexto = $iColuna + 23;
    $oPdf->text($iColunaTexto, 9, $oInstituicao->getDescricao());

    $oPdf->setBold(false);
    $oPdf->setFontSize(8);

    $sComplento = substr(trim($oInstituicao->getComplemento()), 0, 20);

    if (!empty($sComplento)) {
        $sComplento = ", " . substr(trim($oInstituicao->getComplemento()), 0, 20);
    }

    $oPdf->text($iColunaTexto, 14, trim($oInstituicao->getLogradouro()) .
        ", " . trim($oInstituicao->getNumero()) . $sComplento);
    $oPdf->text($iColunaTexto, 18, trim($oInstituicao->getMunicipio()) . " - " . trim($oInstituicao->getUF()));
    $oPdf->text($iColunaTexto, 22, trim($oInstituicao->getTelefone()) .
        "   -    CNPJ : " . db_formatar($oInstituicao->getCNPJ(), "cnpj"));
    $oPdf->text($iColunaTexto, 26, trim($oInstituicao->getEmail()));
    $oPdf->text($iColunaTexto, 30, $oInstituicao->getSite());

    $iColunaFinal = $oPdf->getAvailWidth() + $iColuna;

    $oPdf->setFillColor(0);
    $oPdf->setLeftMargin($iColuna);
    $oPdf->setY(35);
}

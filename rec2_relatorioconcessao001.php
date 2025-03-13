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

use ECidade\RecursosHumanos\RH\ConcessaoDireitos\Repository\AssentConfig;
use ECidade\RecursosHumanos\RH\ConcessaoDireitos\Repository\ConcessaoAssentRelatorio;

require_once(modification("src/RecursosHumanos/RH/ConcessaoDireitos/Repository/ConcessaoAssenRelatorio.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("fpdf151/PDFDocument.php"));

try {
    $object = JSON::create()->parse(str_replace("\\", "", $_POST["object"]));

    if ($object->tipo != 'relatorio') {
        if (empty($object->datainicio) || empty($object->datafinal)) {
            throw new BusinessException("Dados não encontrados!");
        }
    }
    if (empty($object->rh500_sequencial)) {
        throw new BusinessException("Dados não encontrados!");
    }

    $dados = ConcessaoAssentRelatorio::relatorio($object);

    $oPdf = new PDFDocument();
    $oPdf->addHeaderDescription('');
    $oPdf->addHeaderDescription('');
    $oPdf->addHeaderDescription('Instituição: ' . db_getsession("DB_instit"));
    if ($object->matricula) {
        $oPdf->addHeaderDescription('Matricula: ' . $object->matricula);
        if (!AssentConfig::verificarsecao(
            $object->matricula,
            $object->rh500_sequencial
        )) {
            throw new BusinessException("Funcionário sem configuração para esta Concessão");
        }
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

   

    foreach ($dados as $key => $value) {
        $data = explode("-", $value['data']);
        if ($value['dataassenta'] != null) {
            $dataassenta = explode("-", $value['dataassenta']);
            $dataassenta = $dataassenta[2] . '/' . $dataassenta[1] . '/' . $dataassenta[0];
        } else {
            //$dataassenta = $data[2] . '/' . $data[1] . '/' . $data[0];
            $dataassenta = '----------';
        }
       
        $oPdf->Cell(17, 4, $value['matricula'], 'BRLT', 0, 'C', 0);
        $oPdf->Cell(60, 4, mb_strimwidth($value['nome'], 0, 35, "..."), 'BRLT', 0, 'L', 0);
        $oPdf->Cell(12, 4, $value['ordem'], 'BRLT', 0, 'C', 0);
        $oPdf->Cell(20, 4, $value['percentual'] . ' %', 'BRLT', 0, 'C', 0);
        $oPdf->Cell(22, 4, $data[2] . '/' . $data[1] . '/' . $data[0], 'BRLT', 0, 'C', 0);
        $oPdf->Cell(25, 4, $dataassenta, 'BRLT', 0, 'C', 0);
        $oPdf->Cell(35, 4, $value['portaria'], 'BRLT', 1, 'C', 0);
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

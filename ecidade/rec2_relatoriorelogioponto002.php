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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
include_once(modification("libs/db_sessoes.php"));
require_once(modification("fpdf151/pdf.php"));

use ECidade\RecursosHumanos\RH\Efetividade\Repository\Periodo as PeriodoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\EspelhoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\ProcessamentoPontoEletronico;

try {
    $oParametros = \db_utils::postMemory(array_merge($_GET, $_POST));
    $aMatriculas = explode(',', $oParametros->aMatriculas);
    $iCodigoSelecao = !empty($oParametros->iCodigoSelecao) ? $oParametros->iCodigoSelecao : null;

    if (empty($oParametros->sDataInicio)) {
        throw new ParameterException("Informe a data início.");
    }

    if (empty($oParametros->sDataFim)) {
        throw new ParameterException("Informe a data fim.");
    }

    if (empty($aMatriculas)) {
        if (empty($iCodigoSelecao)) {
            throw new ParameterException("Informe uma seleção ou uma ou mais matrículas para emissão do espelho ponto.");
        }
    }

    if (!empty($iCodigoSelecao)) {
        $aMatriculas = array_keys(\ServidorRepository::getServidoresBySelecao(
            DBPessoal::getAnoFolha(),
            DBPessoal::getMesFolha(),
            $iCodigoSelecao
        ));
    }
    if (empty($aMatriculas)) {
        throw new ParameterException("Nenhuma matricula informada.");
    }
    $dataInicial = new \DBDate($oParametros->sDataInicio);
    $dataFinal = new \DBDate($oParametros->sDataFim);
    $where = array("rh229_data between '{$dataInicial->getDate()}' and '{$dataFinal->getDate()}'");

    if (!empty($oParametros->serial)) {
        $where[] = "serial = '{$oParametros->serial}'";
    }
    $where[] = "rh229_matricula in (" . implode(",", $aMatriculas) . ")";

    $sSqlDadosRelatorio = "select distinct rh01_regist as matricula, 
                              z01_nome as nome, 
                              rh229_data as data, 
                              rh229_hora as hora,
                              rh229_serial as serial,
                              rh16_pis as pis
                        from recursoshumanos.pontoeletronicoarquivoimportacaoregistro 
                             inner join rhpessoal on rh229_matricula = rh01_regist 
                             inner join cgm on z01_numcgm = rh01_numcgm 
                             inner join rhpesdoc on rh01_regist = rh16_regist
                       where " . implode(" and ", $where) . " order by rh229_data, rh229_hora, rh229_serial";


    $rsDadosRelatorio = db_query($sSqlDadosRelatorio);
    if (pg_num_rows($rsDadosRelatorio) == 0) {
        throw new BusinessException("Não há servidores para esta selecão.");
    }
    $dadosRelatorio = array();
    $totalLinhas = pg_num_rows($rsDadosRelatorio);
    for ($i = 0; $i < $totalLinhas; $i++) {

        $dados = db_utils::fieldsMemory($rsDadosRelatorio, $i);

        if (!isset($dadosRelatorio[$dados->rh229_matricula])) {

            $dadosServidor = new \stdClass();
            $dadosServidor->nome = $dados->nome;
            $dadosServidor->matricula = $dados->matricula;
            $dadosServidor->pis = $dados->pis;
            $dadosServidor->batidas = array();
            $dadosRelatorio[$dados->matricula] = $dadosServidor;
        }
        $dadosServidor = $dadosRelatorio[$dados->matricula];
        $dadosBatida = new \stdClass();
        $dadosBatida->data = $dados->data;
        $dadosBatida->hora = $dados->hora;
        $dadosBatida->serial = $dados->serial;
        $dadosServidor->batidas[] = $dadosBatida;
    }

    $pdf = new \Pdf();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->SetFillColor(230);
    $pdf->SetAutoPageBreak(false);
    $head2 = "Relógio Ponto";
    $head3 = "Período: {$oParametros->sDataInicio} - {$oParametros->sDataFim}";

    foreach ($dadosRelatorio as $servidor) {

        $pdf->AddPage();
        $pdf->Cell(30, 4, "Matrícula", 1, 0, "C", 1);
        $pdf->Cell(120, 4, "Nome", 1, 0, "C", 1);
        $pdf->Cell(40, 4, "PIS", 1, 1, "C", 1);

        $pdf->Cell(30, 4, $servidor->matricula,1, 0, 'C' );
        $pdf->Cell(120, 4, $servidor->nome, 1, 0, "L");
        $pdf->Cell(40, 4, $servidor->pis, 1, 1, "L");
        $escreverCabecalho = true;
        foreach ($servidor->batidas as $batida) {

            $quebraPagina = $pdf->getY() > $pdf->h - 20;
            if ($quebraPagina|| $escreverCabecalho) {

                if ($quebraPagina) {
                    $pdf->AddPage();
                }
                $pdf->SetX(20);
                $pdf->Cell(30, 4, "Data", 1, 0, 'C', 1);
                $pdf->Cell(20, 4, "Hora", 1, 0, 'C', 1);
                $pdf->Cell(120, 4, "Serial Relógio", 1, 1, 'C', 1);
                $escreverCabecalho = false;

            }
            $pdf->SetX(20);
            $pdf->Cell(30, 4, db_formatar($batida->data, 'd'), 'C', 0);
            $pdf->Cell(20, 4, $batida->hora, 'C', 0);
            $pdf->Cell(120, 4, $batida->serial, 'C', 1);

        }
    }
    $pdf->Output();
} catch (\Exception $e) {

    echo $e->getMessage();
}

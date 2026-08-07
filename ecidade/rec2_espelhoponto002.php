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
use \ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\EspelhoPonto;

$get = db_utils::postMemory($_GET);
$fileName           = "tmp/dados_espelho_ponto_{$get->id}.txt";
$jsonDadosRelatorio = fopen($fileName, 'r');
$dadosRelatorio     = unserialize(fgets($jsonDadosRelatorio));

$lMostraObservacoes = $dadosRelatorio->mostraObservacoes;
$lEmiteTodosAfastamentos = $dadosRelatorio->emiteTodosAfastamentos;
$matriculasComErro       = array();

$head2 = "ESPELHO PONTO";
$head3 = "Período: {$dadosRelatorio->dataInicio} - {$dadosRelatorio->dataFim}";

global $head5, $head6, $head7;

$pdf = new \Pdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetFillColor(230);
$pdf->SetAutoPageBreak(false);

$oLabel = new \stdClass;
$oLabel->data_dia = "Data";
$oLabel->jornada = "Código";
$oLabel->entrada1 = "Entrada 1";
$oLabel->saida1 = "Saída 1";
$oLabel->entrada2 = "Entrada 2";
$oLabel->saida2 = "Saída 2";
$oLabel->entrada3 = "Entrada 3";
$oLabel->saida3 = "Saída 3";
$oLabel->normais = "Trabalho";
$oLabel->faltas = "Faltas";
$oLabel->atraso = "Atrasos";
$oLabel->ext50 = "Ext 50";
$oLabel->ext100 = "Ext 100";
$oLabel->adicional = "Adic Not.";

while (!feof($jsonDadosRelatorio)) {

    $line = fgets($jsonDadosRelatorio);
    $espelhoPontoDados = unserialize($line);

    if(array_key_exists('matriculasComErro', $espelhoPontoDados)) {
        $matriculasComErro = $espelhoPontoDados['matriculasComErro'];
        break;
    }

    if (empty($espelhoPontoDados)) {
        continue;
    }

    if($espelhoPontoDados === false || $line === false) {
        continue;
    }

    $lQuebraPaginaObservacoes = false;
    $iLimiteObservacoesHorarios = 6;
    $iLimiteCarateresObservacoes = 120;
    $dadosServidor = $espelhoPontoDados['dados'];

    $head5 = "Servidor: {$dadosServidor->nome}";
    $head6 = "Matricula: {$dadosServidor->matricula} - Admissão: {$dadosServidor->admissao}";
    $head7 = "PIS/PASEP: {$dadosServidor->pispasep} - Lotação: {$dadosServidor->lotacao}";
    $head8 = "Local de Trabalho: {$dadosServidor->localTrabalho}";

    $pdf->AddPage();
    $pdf->setFontSize(8);

    $sHorasJornada = '';
    $aJornadasServidor = array();
    $iLimiteObservacoesHorarios -= count($aJornadasServidor);
    $contadorHorasJornada = 0;

    foreach ($espelhoPontoDados['aHorasJornada'] as $indice => $jornada){
            $aJornadasServidor[$indice] = $jornada;
        }

    $pdf->Cell(26, 5, 'Horários:', 'TL', 0, "R");
    $lMostrarLegendaAfastamentos = false;
    foreach ($aJornadasServidor as $iCodigo => $jornada) {
        if (!$jornada->diaTrabalhado) {
            continue;
        }

        $sHorasJornada = $iCodigo . ' - ';

        foreach ($jornada->horas as $oHora) {
            $sHorasJornada .= ' ' . $oHora->oHora->format('H:i');
        }

        if ($contadorHorasJornada > 0) {
            $pdf->Cell(26, 5, '', 'TL', 0, "R");
        }

        $pdf->Cell(169, 5, $sHorasJornada, 'RT', 1, "L");

        $contadorHorasJornada++;
    }

    escreverGrade($pdf, $oLabel, true, $lEmiteTodosAfastamentos);

    foreach ($espelhoPontoDados['datas'] as $indDatas => $oData) {

        if ((!!preg_match('/^\d{1,2}\/(\d{1,2})\/\d{1,4}$/', $oData->data, $aMes)) !== true) {
            throw new BusinessException("Não foi possível identificar o mês.");
        }

        $oData->jornada = $oData->oJornada->codigo;
        $oData->entrada1 = $oData->oJornada->tipo_descricao;
        $oData->saida1 = $oData->oJornada->tipo_descricao;
        $oData->entrada2 = $oData->oJornada->tipo_descricao;
        $oData->saida2 = $oData->oJornada->tipo_descricao;
        $oData->entrada3 = $oData->oJornada->tipo_descricao;
        $oData->saida3 = $oData->oJornada->tipo_descricao;

        if (!$oData->oJornada->dsr_folga || $oData->lTemMarcacoes) {
            $oEntrada1 = $oData->aMarcacoes[0]->oEntrada;
            $oSaida1 = $oData->aMarcacoes[0]->oSaida;
            $oEntrada2 = $oData->aMarcacoes[1]->oEntrada;
            $oSaida2 = $oData->aMarcacoes[1]->oSaida;
            $oEntrada3 = $oData->aMarcacoes[2]->oEntrada;
            $oSaida3 = $oData->aMarcacoes[2]->oSaida;

            // só aparece "FERIADO" quando não houver nenhuma marcação.
            $feriado = ($oData->lFeriado && !$oData->lTemMarcacoes && !$oData->lEscalaRevezamento);

            $oData->entrada1 = montarMarcacao($oEntrada1, $lEmiteTodosAfastamentos, $oData->afastamento,
                $lMostrarLegendaAfastamentos, $feriado);

            $oData->saida1 = montarMarcacao($oSaida1, $lEmiteTodosAfastamentos, $oData->afastamento,
                $lMostrarLegendaAfastamentos, $feriado);

            $oData->entrada2 = montarMarcacao($oEntrada2, $lEmiteTodosAfastamentos, $oData->afastamento,
                $lMostrarLegendaAfastamentos, $feriado);

            $oData->saida2 = montarMarcacao($oSaida2, $lEmiteTodosAfastamentos, $oData->afastamento,
                $lMostrarLegendaAfastamentos, $feriado);

            $oData->entrada3 = montarMarcacao($oEntrada3, $lEmiteTodosAfastamentos, $oData->afastamento,
                $lMostrarLegendaAfastamentos, $feriado);

            $oData->saida3 = montarMarcacao($oSaida3, $lEmiteTodosAfastamentos, $oData->afastamento,
                $lMostrarLegendaAfastamentos, $feriado);

            for ($iIndMarcacoes = 0; $iIndMarcacoes < count($oData->aMarcacoes); $iIndMarcacoes++) {
                $oMarcacao = $oData->aMarcacoes[$iIndMarcacoes];

                if ($oMarcacao->oEntrada->manual) {
                    switch ($iIndMarcacoes) {
                        case 0:
                            $oData->entrada1 .= ' ';
                            break;
                        case 1:
                            $oData->entrada2 .= ' ';
                            break;
                        case 2:
                            $oData->entrada3 .= ' ';
                            break;
                    }
                }

                if ($oMarcacao->oSaida->manual) {
                    switch ($iIndMarcacoes) {
                        case 0:
                            $oData->saida1 .= ' ';
                            break;
                        case 1:
                            $oData->saida2 .= ' ';
                            break;
                        case 2:
                            $oData->saida3 .= ' ';
                            break;
                    }
                }
            }
        }

        if ($indDatas > 0) {
                $sTotal = empty($dados['observacoes']) ? 42 : 31;

            if (!($indDatas % $sTotal)) {
                $pdf->AddPage();
            }
        }

        escreverGrade($pdf, $oData, false, $lEmiteTodosAfastamentos);
    }

    $totalHoras = totalizarHorasServidor($espelhoPontoDados);

    $pdf->Cell(117, 5, 'Totais:', 0, 0, "R");
    $pdf->Cell(13, 5, $totalHoras['nTotalHorasNormais'], 0, 0, "C");
    $pdf->Cell(13, 5, $totalHoras['nTotalHorasFaltas'], 0, 0, "C");
    $pdf->Cell(13, 5, $totalHoras['nTotalHorasAtraso'], 0, 0, "C");
    $pdf->Cell(13, 5, $totalHoras['nTotalHorasExt50'], 0, 0, "C");
    $pdf->Cell(13, 5, $totalHoras['nTotalHorasExt100'], 0, 0, "C");
    $pdf->Cell(13, 5, $totalHoras['nTotalHorasAdicional'], 0, 1, "C");

    $pdf->setFontSize(18);
    $pdf->setFontSize(8);

    if ($lMostrarLegendaAfastamentos) {
        $pdf->setFontSize(10);
        $pdf->Cell(3, 5, '+', 0, 0, "C");
        $pdf->setFontSize(8);
        $pdf->Cell(190, 5, 'Existe mais de uma ocorrência de Afastamento/Justificativas.', 0, 1, "L");
    }

    $aObservacoesServidor = array();
    $lMostraObservacoesRescisao = false;
    foreach ($espelhoPontoDados['observacoes'] as $observacao) {

        if (trim($observacao) === '' || trim($observacao) === ":") {
            continue;
        }

        if (in_array($observacao, $aObservacoesServidor)) {
            continue;
        }
        array_push($aObservacoesServidor, $observacao);
    }

    if (!empty($servidor["dados"]->rescisao)) {
        array_unshift($aObservacoesServidor, "Rescindido em {$servidor["dados"]->rescisao->format('d/m/Y')}");
        $lMostraObservacoesRescisao = true;
    }

    if ($lMostraObservacoes || $lMostraObservacoesRescisao) {
        if (count($aObservacoesServidor) > 0) {
            $pdf->Cell(26, 5, 'Justificativas:', 0, 0, "L");
            $pdf->Cell(169, 5, '', 0, 1, "R");
        }

        for ($iObsServidor = 0; $iObsServidor < count($aObservacoesServidor); $iObsServidor++) {
            if (empty($aObservacoesServidor[$iObsServidor])) {
                continue;
            }
            $sObservacao = $aObservacoesServidor[$iObsServidor];

            if ($iLimiteObservacoesHorarios <= 0 || $iObsServidor >= $iLimiteObservacoesHorarios) {
                $lQuebraPaginaObservacoes = true;
                break;
            }

            if (strlen($sObservacao) > $iLimiteCarateresObservacoes) {
                $sObservacao = substr($sObservacao, 0, $iLimiteCarateresObservacoes);
                $sObservacao .= '...';
            }

            $pdf->Cell(195, 5, "  {$sObservacao}", 0, 1, "L");
        }

        $aObservacoesServidor = array_slice($aObservacoesServidor, $iObsServidor);
    }


    escreverAssinaturas($pdf, $dadosServidor, true);

    if ($lMostraObservacoes) {
        if ($lQuebraPaginaObservacoes) {
            $pdf->AddPage();
        }

        if (count($aObservacoesServidor) > 0) {
            $pdf->Cell(26, 5, 'Justificativas:', 0, 0, "L");
            $pdf->Cell(169, 5, '', 0, 1, "R");
        }

        foreach ($aObservacoesServidor as $sObservacao) {
            if (strlen($sObservacao) > $iLimiteCarateresObservacoes) {
                $sObservacao = substr($sObservacao, 0, $iLimiteCarateresObservacoes);
                $sObservacao .= '...';
            }

            $pdf->Cell(195, 5, "  {$sObservacao}", 0, 1, "L");
        }

        if (count($aObservacoesServidor) > 0) {
            escreverAssinaturas($pdf, $dadosServidor);
        }
    }
}

if(!empty($matriculasComErro)) {
    $pdf->AddPage();
    $pdf->Cell(192, 5, 'Erros encontrados durante o processamento do ponto', 'TB', 1, "L", 1);

    foreach ($matriculasComErro as $matriculas) {
        foreach ($matriculas as $indice => $erro) {
            $pdf->MultiCell(192, 4, $erro, 'TB', "L");
        }
    }
}

$pdf->Output();

unlink("tmp/dados_espelho_ponto_{$get->id}.txt");

function escreverGrade(PDF $pdf, $dados, $lHeader = false, $marcacoes=false)
{

    if ($lHeader) {
        $pdf->Bold();
        $pdf->SetFontSize(6.5);
    } else {
        $pdf->SetFontSize(7);
    }

    $colunas = (array)$dados;
    $iMaximoDeLinhas = 5;
    $aColunasNaoContar = array(
        'afastamento',
        'oJornada',
        'data_dia',
        'aMarcacoes',
      'aMarcacoesOriginais',
        'oPeriodoEfetividade',
        'data',
        'possuiEvento',
        'dadosEvento'
    );

    // Valida se vai imprimir todas as marcacoes
    // Caso sim, diminui a altura da celula, para tentar emitir o espelho em apenas 1 pagina
    $alturaBase = 5;
    if ($marcacoes) {
        // Valor baseado em testes
        $alturaBase = 3.3;
    }
    foreach ($colunas as $campo => $coluna) {

        if (in_array($campo, $aColunasNaoContar)) {
            continue;
        }

        $iAlturaLinha = $pdf->NbLines(13, trim($coluna)) * $alturaBase;

            if ($iAlturaLinha > $iMaximoDeLinhas) {
                $iMaximoDeLinhas = $iAlturaLinha;
            }
    }

    $alturaAtual = $pdf->getY();
    if ($alturaAtual+$iMaximoDeLinhas > 250) {
        $pdf->AddPage();
        $alturaAtual = $pdf->getY();
    }

    $pdf->Multicell(26, $alturaBase, $dados->data_dia, 'TLR', "L");
    $pdf->SetXY(36, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->jornada, 'TLR', "C");
    $pdf->SetXY(49, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->entrada1, 'TLR', "C");
    $pdf->SetXY(62, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->saida1, 'TLR', "C");
    $pdf->SetXY(75, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->entrada2, 'TLR', "C");
    $pdf->SetXY(88, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->saida2, 'TLR', "C");
    $pdf->SetXY(101, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->entrada3, 'TLR', "C");
    $pdf->SetXY(114, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->saida3, 'TLR', "C");
    $pdf->SetXY(127, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->normais, 'TLR', "C");
    $pdf->SetXY(140, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->faltas, 'TLR', "C");
    $pdf->SetXY(153, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->atraso, 'TLR', "C");
    $pdf->SetXY(166, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->ext50, 'TLR', "C");
    $pdf->SetXY(179, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->ext100, 'TLR', "C");
    $pdf->SetXY(192, $alturaAtual);
    $pdf->Multicell(13, $alturaBase, $dados->adicional, 'TLR', "C");

    if ($iMaximoDeLinhas > $alturaBase) {
        $pdf->SetY($alturaAtual + $iMaximoDeLinhas);
    }

    /**
     * fechamos as linhas das celulas*
     */
    $posicaoInicioLinha = 10;

    foreach (range(1, 15) as $coluna) {
        $tamanho = 13;
        if ($coluna == 1) {
            $tamanho = 26;
        }
        $pdf->line($posicaoInicioLinha, $alturaAtual, $posicaoInicioLinha, $pdf->GetY());
        $posicaoInicioLinha += $tamanho;
    }

    $pdf->line(10, $pdf->GetY(), 205, $pdf->GetY());

    if ($lHeader) {
        $pdf->EndBold();
        $pdf->SetFontSize(8);
    }
}

function somarHora($horarios)
{

    $nTotalMinutos = 0;
    foreach ($horarios as $horario) {
        if (is_null($horario) || $horario == '' || strpos($horario, ':') == false) {
            continue;
        }

        list($iHora, $iMinute) = explode(':', $horario);
        $nTotalMinutos += $iHora * 60;
        $nTotalMinutos += $iMinute;
    }

    $iHoras = floor($nTotalMinutos / 60);
    $nTotalMinutos -= $iHoras * 60;

    return sprintf('%02d:%02d', $iHoras, $nTotalMinutos);
}

function escreverAssinaturas($pdf, $oDadosServidor, $exibeAssinatura = false)
{
    $imagemAssinatura = file_exists("./imagens/assinatura/assinatura_servidor_{$oDadosServidor->matriculaSupervisor}.jpg") ?
        "./imagens/assinatura/assinatura_servidor_{$oDadosServidor->matriculaSupervisor}.jpg" : "./imagens/assinatura/assinatura_servidor_{$oDadosServidor->matriculaSupervisor}.png";

    if (file_exists($imagemAssinatura) && $exibeAssinatura) {

        $pdf->Image($imagemAssinatura, 140, 215, 65, 30);
        $pdf->ln();
        $pdf->setY(235);
    }

    $pdf->Cell(65, 18, '', 'B', 0, "C");
    $pdf->Cell(65, 18, '', 0, 0, "C");
    $pdf->Cell(65, 18, '', 'B', 1, "C");
    $pdf->Cell(65, 7, $oDadosServidor->nome, 0, 0, "C");
    $pdf->Cell(65, 7, '', 0, 0, "C");
    $pdf->Cell(65, 7, $oDadosServidor->supervisor, 0, 1, "C");

}

/**
 * Monta a string da Marcacao
 * @param $marcacao
 * @param $mostrarAfastamento
 * @return string
 */
function montarMarcacao($marcacao, $mostrarAfastamento, $afastamento, &$mostrarLegenda, $feriado)
{
    if ($feriado) {
        return 'FERIADO';
    }

    $aDados = array();
    $iTotalAfastamento = 0;
    if ($afastamento->isAfastado && $afastamento->abreviacao) {
        $aDados[] = $afastamento->abreviacao;
        $iTotalAfastamento++;
    }
    if (!is_null($marcacao->oJustificativa) && $marcacao->oJustificativa->abreviacao) {
        $aDados[] = $marcacao->oJustificativa->abreviacao;
        $iTotalAfastamento++;
    }

    if(!empty($marcacao->hora)) {
    $aDados[] = $marcacao->hora;
    }

    $string = current($aDados);
    if ($mostrarAfastamento) {
        if(!empty($aDados)) {
        $string = implode("\n", $aDados);
    }
    }

    if (
        !$mostrarAfastamento &&
        (!empty($marcacao->oJustificativa) || !empty($afastamento->isAfastado)) &&
        !empty($marcacao->hora)
    ) {
        $string = $marcacao->hora;
    }

    if ($iTotalAfastamento > 1 && !$mostrarAfastamento) {
        $string .= "+";
        $mostrarLegenda = true;
    }

    return $string === false ? '' : $string;
}

function totalizarHorasServidor($espelhoPonto = null)
{
    $totalHoras = array(
        'nTotalHorasNormais' => '0:00',
        'nTotalHorasFaltas' => '0:00',
        'nTotalHorasAtraso' => '0:00',
        'nTotalHorasExt50' => '0:00',
        'nTotalHorasExt100' => '0:00',
        'nTotalHorasAdicional' => '0:00',
    );
    
    if (empty($espelhoPonto)) {
        return $totalHoras;
    }

    $totalHoras['nTotalHorasNormais']   = EspelhoPonto::somarTotalizador($espelhoPonto['nTotalHorasNormais']);
    $totalHoras['nTotalHorasFaltas']    = EspelhoPonto::somarTotalizador($espelhoPonto['nTotalHorasFaltas']);
    $totalHoras['nTotalHorasAtraso']    = EspelhoPonto::somarTotalizador($espelhoPonto['nTotalHorasAtraso']);
    $totalHoras['nTotalHorasExt50']     = EspelhoPonto::somarTotalizador(array_merge($espelhoPonto['nTotalHorasExt50diurnas'], $espelhoPonto['nTotalHorasExt50noturnas']));
    $totalHoras['nTotalHorasExt100']    = EspelhoPonto::somarTotalizador(array_merge($espelhoPonto['nTotalHorasExt100diurnas'], $espelhoPonto['nTotalHorasExt100noturnas']));
    $totalHoras['nTotalHorasAdicional'] = EspelhoPonto::somarTotalizador($espelhoPonto['nTotalHorasAdicional']);

    return $totalHoras;
}

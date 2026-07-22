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
require_once(modification("libs/db_conect"."a.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("fpdf151/scpdf.php"));

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Repository\DiaTrabalho as DiaTrabalhoRepository;
use ECidade\RecursosHumanos\RH\Efetividade\Repository\Jornada as JornadaRepository;
use ECidade\RecursosHumanos\RH\Efetividade\Repository\Periodo as PeriodoRepository;
use ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo as PeriodoModel;

$parametros = db_utils::postMemory($_GET);

try {
    if (empty($parametros->selecao) && empty($parametros->matriculas) && empty($parametros->localTrabalho)) {
        throw new ParameterException('Seleção, Matrículas ou Local de Trabalho não informado.');
    }

    if (empty($parametros->ano) || empty($parametros->mes)) {
        throw new ParameterException('Competência não informada.');
    }

    $instituicao = InstituicaoRepository::getInstituicaoSessao();

    $dbCompetencia = new DBCompetencia($parametros->ano, $parametros->mes);
    $periodo = PeriodoRepository::getInstanciaPorExercicioCompetencia($parametros->ano, $parametros->mes);

    $titulo = "ABSENTEÍSMO( {$dbCompetencia->getMes()}/{$dbCompetencia->getAno()} )";
    $descricao  = str_repeat(' ' , 15) . "Venho por meio desta, solicitar o comparecimento do(s) funcionário(s) abaixo relacionado(s) na";
    $descricao .= " Divisão de Pessoal";

    if(!empty($parametros->descricaoComplementar)) {
        $descricao .= ": {$parametros->descricaoComplementar}";
    }

    $pdf = new scpdf();
    $pdf->Open();
    $pdf->SetFont('arial', 'b', 9);
    $pdf->AddPage();

    $pdf->Image('imagens/files/' . $instituicao->getImagemLogo(), 96, 8, 20, 20);

    $pdf->SetY(30);
    $pdf->Cell(140, 14, $titulo, 1, 0, 'C');

    $pdf->SetFont('arial', 'b',8);
    $posicaoXCabecalho = 151;

    $pdf->SetX($posicaoXCabecalho);
    $pdf->Cell(52, 4, "Data: " . date('d/m/Y', db_getsession('DB_datausu')), 1, 1, 'L');

    $pdf->SetXY($posicaoXCabecalho, 35);
    $pdf->Cell(52, 4, "Origem: DPES", 1, 1, 'L');

    $pdf->SetXY($posicaoXCabecalho, 40);
    $pdf->Cell(52, 4, "Destino: {$parametros->descricaoLocalTrabalho}", 1, 1, 'L');

    $pdf->Ln(2);
    $pdf->SetFont('arial', '', 8);
    $pdf->Cell(193, 4, "Descrição do Assunto: ", 'TLR', 1, 'L');

    $tamanhoLinhaDescricao = $pdf->NbLines(193, $descricao);
    $pdf->SetFont('arial', 'b', 8);
    $pdf->MultiCell(193, $tamanhoLinhaDescricao % 2 == 1 ? $tamanhoLinhaDescricao * 4 : $tamanhoLinhaDescricao * 2, $descricao, 'BLR', 1, 0, 'L');

    $pdf->Ln();
    $pdf->SetX(10);

    $servidores = buscarServidoresComFalta($parametros, $periodo);

    if(empty($servidores)) {
        throw new BusinessException('Nenhum servidor encontrado com mais de 2 faltas com os filtros selecionados.');
    }

    imprimeServidores($pdf, $servidores);

    $pdf->Output();
} catch (Exception $erro) {
    db_redireciona('db_erros.php?fechar=true&db_erro=' . urlencode($erro->getMessage()));
}

/**
 * @param stdClass $parametros
 * @param PeriodoModel $dbCompetencia
 * @return array
 * @throws BusinessException
 * @throws DBException
 * @throws ParameterException
 */
function buscarServidoresComFalta($parametros, PeriodoModel $periodo) {

    $servidores = array();

    switch ($parametros->filtro) {
        case '1':

            $servidores = \ServidorRepository::getServidoresBySelecao(
              \DBPessoal::getAnoFolha(),
              \DBPessoal::getMesFolha(),
              $parametros->selecao
            );
            break;

        case '2':

            $matriculas = explode(',', $parametros->matriculas);

            foreach ($matriculas as $matricula) {
                $servidores[] = \ServidorRepository::getInstanciaByCodigo($matricula);
            }

            break;

        case '3':

            $servidores = \ServidorRepository::getServidoresByLocalTrabalho(
              \DBPessoal::getAnoFolha(),
              \DBPessoal::getMesFolha(),
              $parametros->localTrabalho
            );
            break;

        default:
            break;
    }

    $servidoresComFaltas = array();
    $datasIntervalo = \DBDate::getDatasNoIntervalo($periodo->getDataInicio(), $periodo->getDataFim());

    foreach ($servidores as $servidor) {
        foreach ($datasIntervalo as $indice => $dataAtual) {
            if($indice == 0) {
                continue;
            }
            $escalaServidorNaData = $servidor->getEscala($dataAtual);

            if ($escalaServidorNaData == null) {
                continue;
            }

            $diaTrabalhoRepository = new DiaTrabalhoRepository();
            $diaTrabalhoRepository->setEscalaServidor($escalaServidorNaData);
            $diaTrabalhoModel = $diaTrabalhoRepository->getApenasHorasCalculadasPorServidorNaData($servidor, $dataAtual);

            if($diaTrabalhoModel->getHorasFalta() == null) {
                continue;
            }

            if($diaTrabalhoModel->getHorasFalta() == '00:00') {
                continue;
            }

            $jornadas = $escalaServidorNaData->getEscalaTrabalho()->getJornadas();
            $ordemJornada = JornadaRepository::getOrdem($diaTrabalhoModel, $escalaServidorNaData);
            $jornadaAtual = $jornadas[$ordemJornada];
            $totalHorasFaltaDia = new DateTime("{$diaTrabalhoModel->getHorasFalta()}");

            if(!isset($servidoresComFaltas[$servidor->getMatricula()])) {
                $dadosServidor = new stdClass();
                $dadosServidor->nome = $servidor->getCgm()->getNome();
                $dadosServidor->faltas = array();

                $servidoresComFaltas[$servidor->getMatricula()] = $dadosServidor;
            }

            if($jornadaAtual->getCargaHoraria()->getTimestamp() == $totalHorasFaltaDia->getTimestamp()) {
                $falta = new stdClass();
                $falta->data = $diaTrabalhoModel->getData()->getDate(DBDate::DATA_PTBR);
                $falta->dia = DBDate::getDiasSemanaAbreviado(date('w', $diaTrabalhoModel->getData()->getTimeStamp()));
                $falta->horas = $diaTrabalhoModel->getHorasFalta();
                $falta->dateTime = new DateTime("{$falta->horas}");

                $servidoresComFaltas[$servidor->getMatricula()]->faltas[] = $falta;
            }
        }
    }

    foreach ($servidoresComFaltas as $matricula => $datasFalta) {
        if(count($datasFalta->faltas) < 2) {
            unset($servidoresComFaltas[$matricula]);
        }
    }

    return $servidoresComFaltas;
}

/**
 * @param scpdf $pdf
 * @param array $servidores
 * @throws Exception
 */
function imprimeServidores(scpdf $pdf, $servidores) {

    $contadorFaltasGeral = new DateTime('00:00');

    foreach ($servidores as $matricula => $servidor) {
        $pdf->SetFont('arial', 'b', 8);

        $contadorFaltasServidor = new DateTime('00:00');
        $linhaServidor = str_repeat(' ', 5) . $matricula . " " . $servidor->nome;
        $pdf->Cell(193, 4, $linhaServidor, 'LTR', 1, 'L');

        $pdf->SetFont('arial', '', 8);

        foreach ($servidor->faltas as $indice => $falta) {
            $data = $falta->data . " " . $falta->dia;
            $pdf->Cell(50, 4, $data, 'L', 0, 'L');
            $pdf->Cell(143, 4, $falta->horas, 'R', 1, 'L');

            $dateInterval = new DateInterval("PT{$falta->dateTime->format('H')}H{$falta->dateTime->format('i')}M");
            $contadorFaltasServidor->add($dateInterval);
            $contadorFaltasGeral->add($dateInterval);
        }

        $totalHoras = calculaDiferenca($contadorFaltasServidor);

        $pdf->SetFont('arial', 'b', 8);
        $pdf->Cell(193, 4, str_repeat(' ', 12) . "Total de Faltas do Servidor: {$totalHoras}", 'T', 1, 'L');

        $pdf->Ln(4);
    }

    $totalHoras = calculaDiferenca($contadorFaltasGeral);

    $pdf->SetFont('arial', 'b', 8);
    $pdf->Cell(193, 4, "Total Geral de Faltas: {$totalHoras}", 1, 1, 'L');
}

/**
 * @param DateTime $horasFaltas
 * @return string
 */
function calculaDiferenca($horasFaltas) {

    $horaCalculoDiferenca = new DateTime('00:00');
    $intervaloDiferencaGeral = $horaCalculoDiferenca->diff($horasFaltas);
    $horas = $intervaloDiferencaGeral->h + ($intervaloDiferencaGeral->d * 24);
    $minutos = str_pad($intervaloDiferencaGeral->i, 2, '0', STR_PAD_LEFT);

    return str_pad("{$horas}:{$minutos}", 7, ' ', STR_PAD_LEFT);
}

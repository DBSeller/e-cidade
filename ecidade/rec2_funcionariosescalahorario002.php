<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_sessoes.php"));
require_once(modification("fpdf151/PDFDocument.php"));

$parametros = db_utils::postMemory($_GET);

try {

    if (empty($parametros->selecao) && empty($parametros->matriculas) && empty($parametros->localTrabalho)) {
        throw new ParameterException('Seleção, Matrículas ou Local de Trabalho não informado.');
    }

    if (empty($parametros->dataInicial) && empty($parametros->dataFinal)) {
        throw new ParameterException('Período de datas não informado.');
    }

    $dadosRelatorio = buscarServidores($parametros);

    if ($parametros->formato == '1') {
        geraCsv($dadosRelatorio);
    }

    $pdf = new PDFDocument();

    montaRelatorio($pdf, $dadosRelatorio, $parametros);

    $pdf->Output();
} catch (Exception $erro) {
    db_redireciona('db_erros.php?fechar=true&db_erro=' . urlencode($erro->getMessage()));
}

/**
 * Gera arquivo csv
 *
 * @param $dadosRelatorios
 */
function geraCsv($dadosRelatorios)
{

    $headers = array(
        'Matricula',
        'Nome',
        'Lotação',
        'Cargo',
        'Escala'
    );

    $arquivo = 'tmp/relatorio_funcionarios_por_escalahoraria.csv';
    $rArquivo = fopen($arquivo, 'w');
    fputs($rArquivo, implode(",", $headers) . "\n");

    foreach ($dadosRelatorios as $dadosRelatorio) {
        foreach ($dadosRelatorio as $dadosJornada) {
            $jornada = "{$dadosJornada->codigoJornada} - {$dadosJornada->descricaoJornada}";
            foreach ($dadosJornada->matriculas as $matricula => $dadosServidor) {
                $linha = array(
                    $matricula,
                    $dadosServidor->nome,
                    $dadosServidor->lotacao->getDescricaoLotacao(),
                    $dadosServidor->cargo->rh37_descr,
                    $jornada
                );

                fputs($rArquivo, implode(",", $linha) . "\n");
            }
        }
    }

    fclose($rArquivo);

    header('Content-Description: File Transfer');
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename=' . basename($arquivo));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($arquivo));
    readfile($arquivo);

    exit;

}


/**
 * @param stdClass $parametros
 * @return array
 * @throws BusinessException
 * @throws DBException
 * @throws ParameterException
 */
function buscarServidores($parametros)
{
    $matriculas = array();
    $dadosRelatorio = array();

    switch ($parametros->filtro) {
        case '1':

            $selecaoSelecionadas = explode(',', $parametros->selecao);

            foreach ($selecaoSelecionadas as $selecao) {
                $servidoresSelecionados = \ServidorRepository::getServidoresBySelecao(
                    \DBPessoal::getAnoFolha(),
                    \DBPessoal::getMesFolha(),
                    $selecao
                );

                foreach ($servidoresSelecionados as $servidorRetornado) {
                    $servidores[] = $servidorRetornado;
                }
            }

            break;

        case '2':

            $matriculasSelecionadas = explode(',', $parametros->matriculas);

            foreach ($matriculasSelecionadas as $matricula) {
                $servidores[] = \ServidorRepository::getInstanciaByCodigo($matricula);
            }

            break;

        case '3':

            $locaisSelecionadas = explode(',', $parametros->localTrabalho);
            foreach ($locaisSelecionadas as $local) {
                $servidoresSelecionados = \ServidorRepository::getServidoresByLocalTrabalho(
                    \DBPessoal::getAnoFolha(),
                    \DBPessoal::getMesFolha(),
                    $local
                );

                foreach ($servidoresSelecionados as $servidorRetornado) {
                    $servidores[] = $servidorRetornado;
                }
            }

            break;

        default:
            break;
    }

    $matriculasFiltro = array();

    foreach ($servidores as $servidor) {
        $dadosServidor = new stdClass();
        $dadosServidor->nome = $servidor->getCgm()->getNome();
        $dadosServidor->lotacao = LotacaoRepository::getInstanceByCodigo($servidor->getCodigoLotacao());
        $dadosServidor->cargo = $servidor->getDadosCargo();
        $dadosServidor->localTrabalho = $servidor->getLocalTrabalhoPrincial();

        $matriculas[$servidor->getMatricula()] = $dadosServidor;
        $matriculasFiltro[] = $servidor->getMatricula();
    }


    $daoEscalaServidor = new cl_escalaservidor();
    $whereEscalaServidor = "     rh192_regist in(" . implode(',', $matriculasFiltro) . ")";
    $whereEscalaServidor .= " AND rh192_instit = " . db_getsession('DB_instit');
    $camposEscalaServidor = "rh190_sequencial, rh190_descricao, rh192_regist, rh192_dataescala";
    $ordernacaoEscalaServidor = "rh192_regist, rh192_dataescala desc";
    $sqlEscalaServidor = $daoEscalaServidor->sql_query(
        null,
        $camposEscalaServidor,
        $ordernacaoEscalaServidor,
        $whereEscalaServidor
    );

    $rsEscalaServidor = db_query($sqlEscalaServidor);

    if (!$rsEscalaServidor) {
        throw new DBException('Erro ao buscar a escala dos servidores.');
    }

    if (pg_num_rows($rsEscalaServidor) == 0) {
        throw new BusinessException('Nenhuma escala encontrada para os servidores selecionados.');
    }

    $totalRetorno = pg_num_rows($rsEscalaServidor);
    $parametros->dataInicial = new DBDate($parametros->dataInicial);
    $parametros->dataFinal = new DBDate($parametros->dataFinal);


    for ($contador = 0; $contador < $totalRetorno; $contador++) {
        $retorno = db_utils::fieldsMemory($rsEscalaServidor, $contador);
        $dataEscala = new DBDate($retorno->rh192_dataescala);

        if (!DBDate::dataEstaNoIntervalo($dataEscala, $parametros->dataInicial, $parametros->dataFinal)) {
            $intervaloData = DBDate::getIntervaloEntreDatas($dataEscala, $parametros->dataInicial);

            if ($intervaloData->invert) {
                continue;
            }
        }

        //@ TODO refazer essa logica
        if ($parametros->quebrarPagina == 1) {

            $codigoAgrupamento = 'g';
            $oLocalTrabalho = $matriculas[$retorno->rh192_regist]->localTrabalho;
            if (!empty($oLocalTrabalho)) {
                $localCodigo = $oLocalTrabalho->getDescricao();
                $localInst = $oLocalTrabalho->getInstituicao()->getSequencial();

                $codigoAgrupamento = $localCodigo . $localInst;
            }

            if (!isset($dadosRelatorio[$codigoAgrupamento][$retorno->rh190_sequencial])) {
                $dadosJornada = new stdClass();
                $dadosJornada->codigoJornada = $retorno->rh190_sequencial;

                $dadosJornada->descricaoJornada = $retorno->rh190_descricao;
                $dadosJornada->matriculas = array();

                $dadosRelatorio[$codigoAgrupamento][$retorno->rh190_sequencial] = $dadosJornada;
            }


            $dadosRelatorio[$codigoAgrupamento][$retorno->rh190_sequencial]->matriculas[$retorno->rh192_regist] = $matriculas[$retorno->rh192_regist];
        } else {

            if (!isset($dadosRelatorio[$retorno->rh190_sequencial])) {
                $dadosJornada = new stdClass();
                $dadosJornada->codigoJornada = $retorno->rh190_sequencial;

                $dadosJornada->descricaoJornada = $retorno->rh190_descricao;
                $dadosJornada->matriculas = array();

                $dadosRelatorio[$retorno->rh190_sequencial] = $dadosJornada;
            }

            $dadosRelatorio[$retorno->rh190_sequencial]->matriculas[$retorno->rh192_regist] = $matriculas[$retorno->rh192_regist];

        }

    }

    //@ TODO refazer essa logica

    if ($parametros->quebrarPagina != 1) {

        return array($dadosRelatorio);
    }

    return $dadosRelatorio;
}

/**
 * @param PDFDocument $pdf
 * @param array $dadosRelatorio
 * @param stdClass $parametros
 * @throws ParameterException
 */
function montaRelatorio(PDFDocument $pdf, $dadosRelatorio, $parametros)
{
    $contadorGeral = 0;


    $pdf->Open();
    $pdf->addHeaderDescription('Funcionários Por Escala de Horário');
    $pdf->addHeaderDescription("Período: {$parametros->dataInicial->getDate(DBDate::DATA_PTBR)} à {$parametros->dataFinal->getDate(DBDate::DATA_PTBR)}");

    foreach (explode(',', $parametros->selecaodesc) as $value) {
        $pdf->addHeaderDescription("Seleção: " .$value);
    }

    $pdf->setFontSize(8);
    $pdf->SetFillColor(235);

        foreach ($dadosRelatorio as $localt => $dados) {
            if ($localt != "0") {
                $pdf->addHeaderDescription('Local: ' . $localt, 'local');
            }

            $pdf->AddPage();

            foreach ($dados as $dadosJornada) {
                $pdf->setBold(true);
                $contadorColaboradores = 0;

                $jornada = "Escala: {$dadosJornada->codigoJornada} - {$dadosJornada->descricaoJornada}";
                $pdf->Cell(192, 4, $jornada, 0, 1, 'L');

                $pdf->Cell(15, 4, "Matrícula", 'TB', 0, 'C', 1);
                $pdf->Cell(85, 4, "Nome", 'TB', 0, 'L', 1);
                $pdf->Cell(40, 4, "Lotação", 'TB', 0, 'C', 1);
                $pdf->Cell(52, 4, "Cargo", 'TB', 1, 'L', 1);

                $pdf->setBold(false);

                foreach ($dadosJornada->matriculas as $matricula => $dadosServidor) {

                    $pdf->Cell(15, 4, $matricula, 0, 0, 'C');
                    $pdf->Cell(85, 4, $dadosServidor->nome, 0, 0, 'L');
                    $pdf->Cell(40, 4, $dadosServidor->lotacao->getDescricaoLotacao(), 0, 0, 'C');
                    $pdf->Cell(52, 4, $dadosServidor->cargo->rh37_descr, 0, 1, 'L');

                    $contadorColaboradores++;
                    $contadorGeral++;

                }

                $pdf->setBold(true);
                $pdf->Cell(192, 4, str_repeat(' ', 10) . "Total de Colaboradores: {$contadorColaboradores}", 'T', 1);
                $pdf->Ln(4);
            }

        }

        $pdf->clearHeaderDescription();

        $pdf->Cell(192, 4, str_repeat(' ', 10) . "Total Geral de Colaboradores: {$contadorGeral}", 'T', 1);

}

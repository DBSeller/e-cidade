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

use ECidade\Educacao\Escola\Service\ConteudoDesenvolvidoService;
use ECidade\Educacao\Secretaria\BNCC\Service\HabilidadeEducacaoInfantilService;
use ECidade\Educacao\Secretaria\BNCC\Service\HabilidadeEnsinoFundamentalService;
use ECidade\Educacao\Secretaria\Services\ParametrosGlobaisService;
use ECidade\Enum\Educacao\Escola\TipoEnsinoEnum;

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$sDataSessao = date('Y/m/d',db_getsession("DB_datausu"));
$configuracao = ParametrosGlobaisService::get();
$oGet = db_utils::postMemory($_GET);

$oPdf = new scpdf('L');
$oEscola = new Escola($oGet->escola);
$oTurma = TurmaRepository::getTurmaByCodigo($oGet->turma);
$oCalendario = new Calendario($oGet->calendario);
$etapa = EtapaRepository::getEtapaByCodigo($oGet->etapa);

$aDisciplinas = array();
$oGet->disciplinas = trim($oGet->disciplinas);
if (!empty($oGet->disciplinas)) {
    $aDisciplinas = explode(",", trim($oGet->disciplinas));
}

$oPeriodoAvaliacao = null;

$sNomeEscola = $oEscola->getNome();
$iCodigoReferencia = $oEscola->getCodigoReferencia();

if ($iCodigoReferencia != null) {
    $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
}
$mostrarHabilidades = false;
if ($oGet->mostrarHabilidades == 'true') {
    $mostrarHabilidades = true;
}

$exibirAnexo = false;
if ($oGet->exibirAnexo == 'true') {
    $exibirAnexo = true;
}
$oDadosCabecalho = new stdClass();
$oDadosCabecalho->sEscola = $sNomeEscola;
$oDadosCabecalho->iAnoExecucao = $oCalendario->getAnoExecucao();
$oDadosCabecalho->sEtapa = $etapa->getNome();
$oDadosCabecalho->sTurma = $oTurma->getDescricao();
$oDadosCabecalho->iTurma = $oTurma->getCodigo();
$oDadosCabecalho->sTurno = $oTurma->getTurno()->getDescricao();
$oDadosCabecalho->sPeriodo = '';
$oDadosCabecalho->iPaginas = $oGet->paginas;
$oDadosCabecalho->dataVigentes = $oGet->emitirVigencia;
$oDadosCabecalho->lDisciplinas = $oGet->mostraTodasDisciplinas;
$oDadosCabecalho->sTitulo = "Registro de Aula";
if ($oGet->preenchimento == "habilidades") {
    $oDadosCabecalho->sTitulo = "Descrição das Habilidades Desenvolvidas";
}

/**
 * Como o código recebido pela tela, no caso do registro de ocorrência era da AvaliacaoPeroidica
 * e não o próprio PeriodoAvaliação, foi necessário fazer esta validação para buscar o valor correto.
 */
if ($oGet->lRegistroOcorrencia == "true") {
    $oDadosCabecalho->sTitulo = "Registro de Ocorrências";
    $oAvaliacaoPeriodica = new AvaliacaoPeriodica($oGet->periodo);
    $oPeriodoAvaliacao = $oAvaliacaoPeriodica->getPeriodoAvaliacao();
} else {
    $oPeriodoAvaliacao = new PeriodoAvaliacao($oGet->periodo);
}

$oPeriodoCalendario = $oCalendario->getPeriodoCalendarioPorPeriodoAvaliacao($oPeriodoAvaliacao);
$oDadosCabecalho->sPeriodo = $oPeriodoAvaliacao->getDescricao();

$oPdf->Open();
$oPdf->SetAutoPageBreak(false);
$oPdf->SetFillColor(230);
/**
 * Percorre as disciplinas selecionadas nos filtros.
 */
$oDadosCabecalho->sNomeProfessor = [];

/**
 * Se for selecnado a opçao de imprimir todas disciplinas, ele pega as informaçoes da primeira disciplina e imprime 1 pagina
 */

if (count($aDisciplinas) > 0 && $oDadosCabecalho->lDisciplinas == 'false') {
    foreach ($aDisciplinas as $iRegencia) {
        $oRegencia = RegenciaRepository::getRegenciaByCodigo($iRegencia);
        $oDadosCabecalho->sNomeDisciplina = $oRegencia->getDisciplina()->getNomeDisciplina();
        $oDadosCabecalho->ensinoInfantil = $oRegencia->getEtapa()->getEnsino()->getTipoEnsino()->isInfantil();
        if (count($oRegencia->getDocentesByDataAtual($sDataSessao)) > 0) {
            foreach ($oRegencia->getDocentesByDataAtual($sDataSessao) as $oDocente) {
                if (!array_key_exists($oDadosCabecalho->sNomeDisciplina, $oDadosCabecalho->sNomeProfessor)) {
                    $oDadosCabecalho->sNomeProfessor[$oDadosCabecalho->sNomeDisciplina] = $oDocente->getNome();
                    continue;
                }

                $oDadosCabecalho->sNomeProfessor[$oDadosCabecalho->sNomeDisciplina] .= ", {$oDocente->getNome()}";
            }
        }

        /**
         * Busca os conteúdos desenvolvidos por disciplina quando for selecionado para ser lançado conforme diário.
         */
        $aConteudoDesenvolvido = array();
        if ($oGet->preenchimento == 'diario') {
            $aConteudoDesenvolvido = buscaConteudoDesenvolvidoDiario($oRegencia, $oPeriodoCalendario, $configuracao);
        }

        switch ($oGet->preenchimento) {
            case "manual":
                imprimeManual($oPdf, $oDadosCabecalho);
                break;
            case "diario":
                imprimeDiario($oPdf, $aConteudoDesenvolvido, $oDadosCabecalho);
                if ($exibirAnexo) {
                    $habilidadesDesenvolvidasPeriodo = buscarDescricaoHabilidades(
                        $oRegencia,
                        $oPeriodoCalendario,
                        $configuracao
                    );
                    imprimeDescritivoHabilidades($oPdf, $habilidadesDesenvolvidasPeriodo, $oDadosCabecalho);
                }
                break;
        }
    }
} else if($oDadosCabecalho->lDisciplinas == 'true') {
        $iRegencia = $aDisciplinas[0];
        $oRegencia = RegenciaRepository::getRegenciaByCodigo($iRegencia);
        $oDadosCabecalho->sNomeDisciplina = $oRegencia->getDisciplina()->getNomeDisciplina();
        $oDadosCabecalho->ensinoInfantil = $oRegencia->getEtapa()->getEnsino()->getTipoEnsino()->isInfantil();
        if (count($oRegencia->getDocentes()) > 0) {
            foreach ($oRegencia->getDocentes() as $oDocente) {
                $oDadosCabecalho->sNomeProfessor[] = $oDocente->getNome();
        }
    }


    imprimeManual($oPdf, $oDadosCabecalho);

} else {
    imprimeManual($oPdf, $oDadosCabecalho);
}

/**
 * Imprime cabecalho do relatório.
 * @param Fpdf $oPdf
 * @param stdClass $oDadosCabecalho dados do cabecalho
 */
function imprimeCabecalho($oPdf, $oDadosCabecalho, $aConteudoDesenvolvido = null)
{
    $oPdf->AddPage();

    $oPdf->SetFont('arial', 'b', 10);

    $oPdf->Cell(290, 4, mb_strtoupper($oDadosCabecalho->sTitulo) . " - {$oDadosCabecalho->sPeriodo}", 0, 1, "C");
    $oPdf->Cell(290, 4, $oDadosCabecalho->sEscola, 0, 1, "C");
    $oPdf->Ln();
    $oPdf->SetFont('arial', 'b', 9);
    $oPdf->Cell(20, 4, "Ano Letivo:", 0, 0, "L");
    $oPdf->Cell(40, 4, $oDadosCabecalho->iAnoExecucao, 0, 0, "L");
    $oPdf->Cell(20, 4, "Etapa:", 0, 0, "L");
    $oPdf->Cell(50, 4, $oDadosCabecalho->sEtapa, 0, 0, "L");
    $oPdf->Cell(20, 4, "Turma:", 0, 0, "L");
    $oPdf->Cell(50, 4, $oDadosCabecalho->sTurma, 0, 0, "L");
    $oPdf->Cell(20, 4, "Turno:", 0, 0, "L");
    $oPdf->Cell(30, 4, $oDadosCabecalho->sTurno, 0, 1, "L");

    if (isset($oDadosCabecalho->sNomeDisciplina)) {
        $label = 'Disciplina';
        $tamanhos = [20, 100, 20, 30];
        $nomeProfessor = "";
        
        if(isset($oDadosCabecalho->sNomeProfessor[$oDadosCabecalho->sNomeDisciplina])){
            $nomeProfessor = $oDadosCabecalho->sNomeProfessor[$oDadosCabecalho->sNomeDisciplina];
        }

        if ($oDadosCabecalho->ensinoInfantil) {
            $label = 'Campos de Experiência';
            $tamanhos = [38, 100, 20, 80, 30];
        }
        $oPdf->Cell($tamanhos[0], 4, "{$label}:", 0, 0, "L");
        if (isset($oDadosCabecalho->lDisciplinas) && $oDadosCabecalho->lDisciplinas == 'true') {
            $oDadosCabecalho->sNomeDisciplina = "TODAS";
        }
        $oPdf->Cell($tamanhos[1], 4, $oDadosCabecalho->sNomeDisciplina, 0, 0, "L");
        $oPdf->Cell($tamanhos[2], 4, "Professor:", 0, 0, "L");

        $oPdf->Cell(0, 4, $nomeProfessor, 0, 0, "L", 0);
    }

    $oPdf->Ln();
    $oPdf->Ln();
}


/**
 * Imprime somente as linhas para lancamento manual.
 * @param Fpdf $oPdf
 * @param stdClass $oDadosCabecalho dados do cabecalho
 * @throws Exception
 */
function imprimeManual($oPdf, $oDadosCabecalho)
{
    global $mostrarHabilidades;
    for ($i = 0; $i < $oDadosCabecalho->iPaginas; $i++) {
        imprimeCabecalho($oPdf, $oDadosCabecalho);

        /**
         * guarda as posicoes iniciais do eixo x e y antes de comecar a imprimir as linhas.
         */
        $iPosicaoYInicial = $oPdf->GetY();
        $iMaximoLinha = 33;

        /**
         * Conforme layout dividimos cada pagina em duas colunas.
         */
        for ($iColuna = 0; $iColuna < 2; $iColuna++) {
            if ($iColuna == 1) {
                $oPdf->SetY($iPosicaoYInicial);
                $oPdf->SetX(149);
            }

            $oPdf->Cell(14, 5, "Data", 1, 0, "C", 1);
            $oPdf->Cell(125, 5, $oDadosCabecalho->sTitulo, 1, 1, "C", 1);

            for ($iLinha = 0; $iLinha < $iMaximoLinha; $iLinha++) {
                if ($iColuna == 1) {
                    $oPdf->SetX(149);
                }

                $oPdf->Cell(14, 5, "", 1, 0);
                $oPdf->Cell(125, 5, "", 1, 1);
            }
        }
    }
}

/**
 * Imprime os conteudos desenvolvidos que foram lancados no diario,
 * se nao houver conteudos lancados imprime linhas em branco.
 * @param Fpdf $oPdf
 * @param array $aConteudoDesenvolvido conteudos desenvolvidos lancado no diario
 * @param stdClass $oDadosCabecalho dados cabecalho
 * @throws Exception
 */
function imprimeDiario($oPdf, $aConteudoDesenvolvido, $oDadosCabecalho)
{
    global $mostrarHabilidades;
    for ($i = 0; $i < $oDadosCabecalho->iPaginas; $i++) {
        imprimeCabecalho($oPdf, $oDadosCabecalho, $aConteudoDesenvolvido);

        /**
         * guarda as posicoes iniciais do eixo x e y antes de comecar a imprimir as linhas.
         */
        $iPosicaoYInicial = $oPdf->GetY();
        $iMaximoLinha = 33;
        $iAlturaLinha = 5;
        $iAlturaQuadroImpressao = 200;

        for ($iColuna = 0; $iColuna < 2; $iColuna++) {
            $lPrimeiraColuna = true;

            $iEixoYFimConteudo = 0;
            for ($iLinha = 0; $iLinha < $iMaximoLinha; $iLinha++) {
                if ($lPrimeiraColuna) {
                    $lPrimeiraColuna = false;
                    imprimeCabecalhoColunas($oPdf, $iColuna, $iPosicaoYInicial, $oDadosCabecalho->sTitulo);
                }
                /**
                 * Fazemos a contagem dos turnos, caso houver mais de um ele habilita a opção para colorir
                 */
                $turnos = [];
                foreach ($aConteudoDesenvolvido as $contagemTurno) {
                    if (empty($contagemTurno->turno)) {
                        continue;
                    }
                    $turnos[$contagemTurno->turno] = $contagemTurno->turno;
                }

                $lColorir = count($turnos) > 1;

                foreach ($aConteudoDesenvolvido as $iIndice => $oConteudo) {

                    if ($iColuna == 1) {
                        $oPdf->SetX(149);
                    }
                    $eixoXInicial = $oPdf->GetX();

                    $conteudo = $oConteudo->conteudo;

                    if ($mostrarHabilidades && !empty($oConteudo->habilidades)) {
                        $conteudo .= "\nHabilidades: {$oConteudo->habilidades}";
                    }

                    $iLinhasUtilizadas = $oPdf->NbLines(125, $conteudo);

                    $iAlturaLinhaUtilizada = ($iLinhasUtilizadas * $iAlturaLinha);
                    $iEixoYFimConteudo = $iAlturaLinhaUtilizada + $oPdf->getY();

                    if ($iColuna == 0 && $iEixoYFimConteudo > $iAlturaQuadroImpressao) {
                        $oPdf->setY($iPosicaoYInicial);
                        $iColuna = 1;
                        $lPrimeiraColuna = true;
                        $iLinha = 0;
                        break;
                    }

                    if ($iColuna == 1 && $iEixoYFimConteudo > $iAlturaQuadroImpressao) {
                        $iColuna = 0;
                        $iLinha = 0;
                        imprimeCabecalho($oPdf, $oDadosCabecalho);
                        imprimeCabecalhoColunas($oPdf, $iColuna, $iPosicaoYInicial, $oDadosCabecalho->sTitulo);
                    }

                    $eixoXFinal = $eixoXInicial + 125;


                    $fillPadrao = 225;
                    $color = 255;
                    if ($oConteudo->turno == "MANHÃ") {
                        $color = 180;
                    } elseif ($oConteudo->turno == "TARDE") {
                        $color = 245;
                    }

                    $oPdf->SetFillColor($color);
                    $oPdf->Cell(14, $iAlturaLinhaUtilizada, $oConteudo->data, 1, 0, "C", $lColorir);
                    $oPdf->MultiCell(125, $iAlturaLinha, $conteudo, 1, "L", $lColorir);
                    $oPdf->SetFillColor($fillPadrao);
                    $oPdf->Line($eixoXInicial, $oPdf->GetY(), $eixoXFinal, $oPdf->GetY());
                    $iLinha += $iLinhasUtilizadas;

                    unset($aConteudoDesenvolvido[$iIndice]);
                }
                if ($iColuna == 1) {
                    $oPdf->SetX(149);
                }
                /**
                 * So ira imprimir linhas em branco se nao houver mais conteudo a ser impresso
                 */
                if (count($aConteudoDesenvolvido) == 0) {
                    $oPdf->Cell(14, 5, "", 1, 0);
                    $oPdf->Cell(125, 5, "", 1, 1);
                }
            }
        }

        if ($oPdf->GetY() < 199) {
            while ($oPdf->GetY() < 199) {
                $oPdf->SetX(149);
                $oPdf->Cell(14, 5, "", 1, 0);
                $oPdf->Cell(125, 5, "", 1, 1);
            }
        }
    }
}

/**
 * Busca conteudo desenvolvido lancado no Diario de Classe para a regencia
 * @param Regencia $oRegencia
 * @param PeriodoCalendario $oPeriodoCalendario
 * @param $configuracao
 * @return array:stdClass
 * @throws ParameterException
 */
function buscaConteudoDesenvolvidoDiario($oRegencia, $oPeriodoCalendario, $configuracao)
{
    $sDataInicial = $oPeriodoCalendario->getDataInicio()->convertTo(DBDate::DATA_EN);
    $sDataFinal = $oPeriodoCalendario->getDataTermino()->convertTo(DBDate::DATA_EN);

    $conteudoDesenvolvidoService = new ConteudoDesenvolvidoService();
    $conteudos = $conteudoDesenvolvidoService->buscarConteudoPeriodo($oRegencia, $sDataInicial, $sDataFinal);
    $conteudoDesenvolvido = array();
    foreach ($conteudos as $conteudo) {
        $habilidades = [];
        foreach ($conteudo->getHabilidades() as $habilidadeDesenvolvida) {
            if ($configuracao->isReferencialCurricularEstadual()) {
                $habilidadesDesenvolvidasReferencial = $habilidadeDesenvolvida->getHabilidadesReferencial();
                foreach ($habilidadesDesenvolvidasReferencial as $habilidadeDesenvolvidaReferencial) {
                    $habilidades[] = $habilidadeDesenvolvidaReferencial->getReferencialCurricular()
                        ->getCodigoReferencial();
                }
            } else {
                $habilidades[] = $habilidadeDesenvolvida->getCodigoHabilidade();
            }
        }

        // Pega o codigo do Turno referente e procura pela descrição do turno //
        $codigoTurno = $conteudo->getTurno();
        if ($codigoTurno != null) {
            $turnoReferente = new cl_turmaturnoreferente();
            $sSqlTurmaTurnoReferente = $turnoReferente->sql_query_file(null, '*', '', "ed336_codigo = $codigoTurno");
            $rsSqlTurmaTurnoReferente = $turnoReferente->sql_record($sSqlTurmaTurnoReferente);
            $iLinhas = pg_num_rows($rsSqlTurmaTurnoReferente);
            for ($i = 0; $i < $iLinhas; $i++) {
                $turmaTurnoReferente = db_utils::fieldsmemory($rsSqlTurmaTurnoReferente, $i);
            }
            $turnoTurma = TurnoRepository::getTurnoByCodigo($turmaTurnoReferente->ed336_turnoreferente);
            $turnoDescricao = $turnoTurma->getDescricao();
        } else {
            $turnoDescricao = '';
        }

        $conteudoDesenvolvido[] = (object)[
            'data' => $conteudo->getData()->format("d/m/Y"),
            'conteudo' => $conteudo->getConteudo(),
            'habilidades' => implode(', ', $habilidades),
            'turno' => $turnoDescricao
        ];
    }

    return $conteudoDesenvolvido;
}

/**
 * Buscar habilidades lançadas no diário
 * @param Regencia $oRegencia
 * @param $oPeriodoCalendario
 * @param $configuracao
 * @return array []
 * @throws Exception
 */
function buscarDescricaoHabilidades($oRegencia, $oPeriodoCalendario, $configuracao)
{
    $sDataInicial = $oPeriodoCalendario->getDataInicio()->convertTo(DBDate::DATA_EN);
    $sDataFinal = $oPeriodoCalendario->getDataTermino()->convertTo(DBDate::DATA_EN);

    $conteudoDesenvolvidoService = new ConteudoDesenvolvidoService();
    $conteudos = $conteudoDesenvolvidoService->buscarConteudoPeriodo($oRegencia, $sDataInicial, $sDataFinal);

    $habilidadesDesenvolvidas = [];
    foreach ($conteudos as $conteudo) {
        foreach ($conteudo->getHabilidades() as $habilidadeDesenvolvida) {
            $data = $habilidadeDesenvolvida->getConteudoDesenvolvido()->getData();
            $anoHabilidade = $data->format('Y');
            $data = $data->format('d/m/Y');

            if ($configuracao->isReferencialCurricularEstadual()) {
                $habilidadesDesenvolvidasReferencial = $habilidadeDesenvolvida->getHabilidadesReferencial();
                foreach ($habilidadesDesenvolvidasReferencial as $habilidadeDesenvolvidaReferencial) {
                    $referencialCurricularEstadual = $habilidadeDesenvolvidaReferencial->getReferencialCurricular();
                    $habilidade = (object)[
                        'codigo' => $referencialCurricularEstadual->getCodigoReferencial(),
                        'descricao' => $referencialCurricularEstadual->getHabilidade()
                    ];

                    if (!array_key_exists($data, $habilidadesDesenvolvidas)) {
                        $habilidadesDesenvolvidas[$data] = [];
                    }
                    array_push($habilidadesDesenvolvidas[$data], $habilidade);
                }
            } else {
                $ensino = $habilidadeDesenvolvida->getDisciplina()->getEnsino()->getTipoEnsino()->getValue();
                if ($ensino === TipoEnsinoEnum::ENSINO_INFANTIL) {
                    $habilidadeEducacaoInfantilService = new HabilidadeEducacaoInfantilService(
                        $configuracao,
                        $anoHabilidade
                    );
                    $habilidadeBncc = $habilidadeEducacaoInfantilService->getHabilidade($habilidadeDesenvolvida);
                } else {
                    $habilidadeEnsinoFundamentalService = new HabilidadeEnsinoFundamentalService(
                        $configuracao,
                        $anoHabilidade
                    );

                    $habilidadeBncc = $habilidadeEnsinoFundamentalService->getHabilidade($habilidadeDesenvolvida);
                }

                $habilidade = (object)[
                    'codigo' => $habilidadeBncc->getCodigo(),
                    'descricao' => $habilidadeBncc->getHabilidade()
                ];

                if (!array_key_exists($data, $habilidadesDesenvolvidas)) {
                    $habilidadesDesenvolvidas[$data] = [];
                }

                array_push($habilidadesDesenvolvidas[$data], $habilidade);
            }
        }
    }

    return $habilidadesDesenvolvidas;
}

function removerCodigo($codigo, $descricao)
{
    $string = "(" . $codigo . ") ";
    $descricao = str_replace($string, '', $descricao);
    return $descricao;
}

/**
 * @param $oPdf
 * @param $habilidadesDesenvolvidasPeriodo
 * @param $oDadosCabecalho
 * @throws Exception
 */
function imprimeDescritivoHabilidades($oPdf, $habilidadesDesenvolvidasPeriodo, $oDadosCabecalho)
{
    if (empty($habilidadesDesenvolvidasPeriodo)) {
        return;
    }

    $cabecalhoHabilidades = (object)[
        "sEscola" => $oDadosCabecalho->sEscola,
        "iAnoExecucao" => $oDadosCabecalho->iAnoExecucao,
        "sEtapa" => $oDadosCabecalho->sEtapa,
        "sTurma" => $oDadosCabecalho->sTurma,
        "sTurno" => $oDadosCabecalho->sTurno,
        "sPeriodo" => $oDadosCabecalho->sPeriodo,
        "iPaginas" => $oDadosCabecalho->iPaginas,
        "sTitulo" => "Descritivo de Habilidades/Objetivos de Aprendizagem",
        "sNomeDisciplina" => $oDadosCabecalho->sNomeDisciplina,
        "sNomeProfessor" => $oDadosCabecalho->sNomeProfessor,
        "ensinoInfantil" => $oDadosCabecalho->ensinoInfantil,
    ];
    imprimeCabecalho($oPdf, $cabecalhoHabilidades);
    imprimeCabecalhoHabilidades($oPdf);

    $maximoLinhas = 30;
    $controleLinhas = 0;

    foreach ($habilidadesDesenvolvidasPeriodo as $data => $habilidades) {
        $linhasUtilizadas = 0;

        foreach ($habilidades as $habilidade) {
            $habilidade->descricao = removerCodigo($habilidade->codigo, $habilidade->descricao);
            $habilidade->linhasUtilizadas = $oPdf->NbLines(238, $habilidade->descricao);
            $linhasUtilizadas += $habilidade->linhasUtilizadas;
        }

        $controleLinhas += $linhasUtilizadas;
        if ($controleLinhas >= $maximoLinhas) {
            $controleLinhas = 0;
            imprimeCabecalho($oPdf, $oDadosCabecalho);
            imprimeCabecalhoHabilidades($oPdf);
        }

        $oPdf->Cell(20, ($linhasUtilizadas * 5), $data, 1, 0, "C", 0);
        $posicaoX = $oPdf->getX();

        foreach ($habilidades as $habilidade) {
            $oPdf->setX($posicaoX);
            $oPdf->Cell(20, ($habilidade->linhasUtilizadas * 5), $habilidade->codigo, 1, 0, "C", 0);
            $oPdf->MultiCell(238, 5, $habilidade->descricao, 1, "L");
        }
    }
}

/**
 * Imprime cabecalho do relatório por colunas.
 * @param Fpdf $oPdf
 * @param int $iColuna
 * @param int $iPosicaoYInicial
 * @param string $sTitulo
 */
function imprimeCabecalhoColunas($oPdf, $iColuna, $iPosicaoYInicial, $sTitulo)
{
    global $mostrarHabilidades;
    if ($iColuna == 1) {
        $oPdf->SetY($iPosicaoYInicial);
        $oPdf->SetX(149);
    }

    $oPdf->SetFont('arial', 'b', 7);
    $oPdf->Cell(14, 5, "Data", 1, 0, "C", 1);
    $oPdf->Cell(125, 5, $sTitulo, 1, 1, "C", 1);
    $oPdf->SetFont('arial', '', 7);
}

/**
 * @param $oPdf
 */
function imprimeCabecalhoHabilidades($oPdf)
{
    $oPdf->SetFont('arial', 'b', 7);
    $oPdf->Cell(20, 5, "Data", 1, 0, "C", 1);
    $oPdf->Cell(20, 5, "Código", 1, 0, "C", 1);
    $oPdf->Cell(238, 5, "Descrição Habilidades / Objetivos de Aprendizagem", 1, 1, "C", 1);
    $oPdf->SetFont('arial', '', 7);
}

$oPdf->Output();

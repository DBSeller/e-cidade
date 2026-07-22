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

use ECidade\Educacao\Escola\Model\DiarioArea;
use ECidade\Educacao\Escola\Model\DiarioAreaAvaliacao;

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/DBDate.php"));

$oDados = db_utils::postMemory($_GET);
$oJson = new services_json();
$aRetornoTurmas = $oJson->decode(str_replace("\\", "", $_GET["aTurmas"]));

/**
 * Objeto com os dados do relatorio
 */
$oDadosRelatorio = new stdClass();

/**
 * Imprimindo relatorio
 */
$oPdf = new scpdf("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetRightMargin(8);
$oPdf->SetFillColor(225);
$oDadosRelatorio->iColunasPorPagina = 22;

/**
 * Limite de aluno por pagina
 */
$oDadosRelatorio->iTotalAlunoPorPagina = 32;

/**
 * Tamanho de cada coluna das disciplinas e das faltas
 */
$oDadosRelatorio->iTamanhoColunasDisciplinas = 7;
$oDadosRelatorio->iTamanhoColunasFaltas = 6;

/**
 * Altura das celulas
 */
$oDadosRelatorio->iAltura = 4;

/**
 * Atribuimos ao objeto Pdf os dados passados por parametro
 */
$oDadosRelatorio->oDados = $oDados;
$aTurmas = explode(",", $oDadosRelatorio->oDados->aTurmas);
$oCalendario = new Calendario($oDadosRelatorio->oDados->iCalendario);

/**
 * Percorremos as turmas/etapas passadas por parametro
 */
foreach ($aRetornoTurmas as $oRetornoTurma) {
    $oTurma = TurmaRepository::getTurmaByCodigo($oRetornoTurma->ed57_i_codigo);
    $oEtapa = EtapaRepository::getEtapaByCodigo($oRetornoTurma->codigo_etapa);
    $oElemento = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);


    /**
     * Atribuimos ao objeto Pdf os dados necessarios para impressao
     */
    $oDadosRelatorio->sTurma = $oTurma->getDescricao();
    $oDadosRelatorio->sTurno = $oTurma->getTurno()->getDescricao();
    $oDadosRelatorio->iAno = $oTurma->getCalendario()->getAnoExecucao();
    $oDadosRelatorio->iDiasLetivos = $oTurma->getCalendario()->getDiasLetivos();
    $oDadosRelatorio->aRegencias = $oTurma->getDisciplinasPorEtapa($oEtapa);
    $oDadosRelatorio->iEnsino = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo();
    $oDadosRelatorio->sDocente = '';
    $oDadosRelatorio->sFormaAvaliacao = '';
    $oDadosRelatorio->ltemObservacaoProgressaoParcial = false;
    $oDadosRelatorio->iTrocaTurma = $oDados->iTrocaTurma;

    $oConselheiro = $oTurma->getProfessorConselheiro();
    if (!empty($oConselheiro)) {
        $oDocente = $oTurma->getProfessorConselheiro();
        if (!is_null($oDocente->getCodigoDocente())) {
            $oDadosRelatorio->sDocente = $oTurma->getProfessorConselheiro()->getNome();
        }
    }

    /**
     * Buscamos os alunos matriculados na turma
     */
    $oDadosRelatorio->aAlunosMatriculados = array();
    $oDadosRelatorio->aAlunosMatriculados = $oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa);
    $oDadosRelatorio->iTotalAlunos = count($oDadosRelatorio->aAlunosMatriculados);
    $oDadosRelatorio->sEtapa = $oEtapa->getNome();


    $oDadosRelatorio->iTotalDisciplinas = 0;


    $areasConhecimentoCabecalho = [];
    foreach ($oDadosRelatorio->aRegencias as $disciplina) {
        if (!array_key_exists($disciplina->getAreaConhecimento()->getCodigo(), $areasConhecimentoCabecalho)) {
            $areasConhecimentoCabecalho[$disciplina->getAreaConhecimento()->getCodigo()] = (object)[
                "codigo" => $disciplina->getAreaConhecimento()->getCodigo(),
                "descricao" => $disciplina->getAreaConhecimento()->getDescricao(),
                "disciplinas" => []
            ];
        }
        $areasConhecimentoCabecalho[$disciplina->getAreaConhecimento()->getCodigo()]->disciplinas[] = $disciplina;
    }
    $oDadosRelatorio->areasPorPagina = [];
    $colunasAdicionadas = 0;
    $iPagina = 1;
    foreach ($areasConhecimentoCabecalho as $areaConhecimento) {
        if (!array_key_exists($iPagina, $oDadosRelatorio->areasPorPagina)) {
            $oDadosRelatorio->areasPorPagina[$iPagina] = [];
        }
        $colunasAdicionadas += (count($areaConhecimento->disciplinas) + 1);
        if ($colunasAdicionadas >= $oDadosRelatorio->iColunasPorPagina) {
            $iPagina++;
            $colunasAdicionadas = 0;
        }
        $oDadosRelatorio->areasPorPagina[$iPagina][] = $areaConhecimento;
    }


    /**
     * Variaveis para controle do array do limite de disciplinas.
     */
    $iPagina = 0;
    $iContadorAux = 0;
    $aDisciplinasPorPagina = array();

    /**
     * Organizamos um array com o limite de disciplinas por pagina, e o total de paginas
     */
    foreach ($oDadosRelatorio->aRegencias as $oRegencia) {
        $aDisciplinasPorPagina[$iPagina][$iContadorAux] = $oRegencia;

        if ($iContadorAux >= $oDadosRelatorio->iColunasPorPagina - 1) {
            $iPagina++;
            $iContadorAux = 0;
        }
        $iContadorAux++;
        $oDadosRelatorio->iTotalDisciplinas++;
    }

    /**
     * Percorremos cada periodo do calendario por turma
     */
    $areaProcedimento = null;
    foreach ($oDadosRelatorio->aAlunosMatriculados as $alunoMatriculado) {
        db_inicio_transacao();
        /** @var DiarioClasse $oDiarioClasse */
        $oDiarioClasse = $alunoMatriculado->getDiarioDeClasse();

        if (!is_null($oDiarioClasse->getAreaProcedimento())) {
            $areaProcedimento = $oDiarioClasse->getAreaProcedimento();
        }
        db_fim_transacao();
    }
    if (is_null($areaProcedimento)) {
        db_redireciona("db_erros.php?fechar=true&db_erro=A turma nao foi avaliada por Area de Conhecimento.");
    }

    foreach ($areaProcedimento->getAvaliacoes() as $avaliacaoArea) {
        $oDadosRelatorio->sPeriodo = $avaliacaoArea->getPeriodoAvaliacao()->getDescricao();
        $oDadosRelatorio->iPeriodoAvaliacao = $avaliacaoArea->getPeriodoAvaliacao()->getCodigo();
        $oDadosRelatorio->iTipoPeriodo = 1;

        /**
         * Iteramos sobre o array das disciplinas, chamando as funcoes para impressao do relatorio
         */

        foreach ($oDadosRelatorio->areasPorPagina as $areasConhecimento) {
            $oPdf->AddPage();
            cabecalhoPadrao($oPdf, $oDadosRelatorio);
            posicionamentoCabecalho($oPdf, $oDadosRelatorio);
            cabecalhoPeriodosAreas($oPdf, $areasConhecimento, $oDadosRelatorio);
            imprimeGradeAproveitamentoAluno($oPdf, $areasConhecimento, $oDadosRelatorio);
        }
    }

    $oPdf->addPage();
    $oDadosRelatorio->iTipoPeriodo = 2;
    $oDadosRelatorio->sPeriodo = $areaProcedimento->getResultado()->getTipoResultado()->getDescricao();
    $oDadosRelatorio->iPeriodoAvaliacao = $areaProcedimento->getResultado()->getTipoResultado()->getCodigo();
    cabecalhoPadrao($oPdf, $oDadosRelatorio);
    posicionamentoCabecalho($oPdf, $oDadosRelatorio);
    cabecalhoPeriodosAreas($oPdf, $areasConhecimento, $oDadosRelatorio);
    imprimeGradeAproveitamentoAluno($oPdf, $areasConhecimento, $oDadosRelatorio);
//    imprimeGradeResultadoArea($oPdf, $areasConhecimento, $oDadosRelatorio);
}

/**
 * Imprimimos a grade de aproveitamento de cada aluno
 */
function imprimeGradeResultadoArea($oPdf, $areasConhecimento, $oDadosRelatorio)
{
    $iLinhasImpressas = 1;
    $oPdf->SetY(54);
    $oPdf->SetFont('arial', '', 7);
    /**
     * Percorremos a matricula de cada aluno, para montar a grade de aproveitamento
     */
    foreach ($oDadosRelatorio->aAlunosMatriculados as $oMatricula) {
        if ($oDadosRelatorio->iTrocaTurma == 1 && $oMatricula->getSituacao() == 'TROCA DE TURMA') {
            continue;
        }

        $oDadosRelatorio->lProgressaoParcial = false;
        $iTemObservacao = 0;

        $oPdf->SetX(10);
        $iContadorDisciplinas = 1;

        if ($iLinhasImpressas > $oDadosRelatorio->iTotalAlunoPorPagina || $oPdf->gety() > $oPdf->h - 8) {
            $oPdf->AddPage();
            cabecalhoPadrao($oPdf, $oDadosRelatorio);
            posicionamentoCabecalho($oPdf, $oDadosRelatorio);
            cabecalhoPeriodosAreas($oPdf, $areasConhecimento, $oDadosRelatorio);

            $oPdf->SetY(54);
            $oPdf->SetX(10);
            $oPdf->SetFont('arial', '', 7);
            $iLinhasImpressas = 1;
        }

        $oPdf->SetFont('arial', '', 7);
        /**
         * Imprimimos o aluno e setamos a posicao do "X"
         */
        $oPdf->Cell(5, $oDadosRelatorio->iAltura, $oMatricula->getNumeroOrdemAluno(), 1, 0, "C");
        $oPdf->Cell(112, $oDadosRelatorio->iAltura, $oMatricula->getAluno()->getNome(), 1, 0, "L");

        $oPdf->SetX($oPdf->GetX());

        /**
         * Verificamos se o aluno nao esta matriculado na turma, apresentando o status na grade
         */
        if ($oMatricula->getSituacao() != 'MATRICULADO') {
            $iTamanhoColuna = $oDadosRelatorio->iColunasPorPagina * $oDadosRelatorio->iTamanhoColunasDisciplinas;
            $oPdf->Cell($iTamanhoColuna, $oDadosRelatorio->iAltura, $oMatricula->getSituacao(), 1, 0, "C");
        } else {

            /**
             * Percorremos cada regencia do aluno para preenchimento das notas
             */
            $colunasAreasImpressas = 0;
            $sAproveitamento = '';
            $diarioAlunoService = $oMatricula->getDiarioDeClasse()->getDiarioAlunoService();
            db_fim_transacao();

            $contadorColunasImpressas = 0;

            foreach ($oDadosRelatorio->areasPorPagina as $areasConhecimento) {
                foreach ($areasConhecimento as $areaConhecimento) {
                    $colunasArea = count($areaConhecimento->disciplinas) + 1;
                    $colunasAreasImpressas += $colunasArea;
                    $diarioAreaAvaliacoes = null;
                    foreach ($diarioAlunoService->getDiarioAluno()->getDiarioAreasConhecimento() as $diarioAreaConhecimento) {
                        if ($diarioAreaConhecimento->getAreaConhecimento()->getCodigo() != $areaConhecimento->codigo) {
                            continue;
                        }
                        $diarioAreaAvaliacoes = $diarioAreaConhecimento->getAvaliacoes();
                        foreach ($diarioAreaAvaliacoes as $diarioAreaAvaliacao) {
                            if ($diarioAreaAvaliacao->getAreaProcedimentoAvaliacao()
                                    ->getPeriodoAvaliacao()->getCodigo() == $oDadosRelatorio->iPeriodoAvaliacao) {
                                $sAproveitamento = $diarioAreaAvaliacao->getNota();
                            }
                            if ($oDadosRelatorio->iTipoPeriodo == 2 && $diarioAreaAvaliacao->getDiarioArea()->getResultado()->getAreaProcedimentoResultado()
                                    ->getTipoResultado()->getCodigo() == $oDadosRelatorio->iPeriodoAvaliacao) {
                                $sAproveitamento = round($diarioAreaAvaliacao->getDiarioArea()->getResultado()->getNota());
                            }
                        }
                        $oPdf->Cell($oDadosRelatorio->iTamanhoColunasDisciplinas, $oDadosRelatorio->iAltura, $sAproveitamento, 1, 0, "C");
                        $contadorColunasImpressas++;
                    }
                    foreach ($areaConhecimento->disciplinas as $oRegencia) {
                        $iNumeroFaltas = '--';
                        $oDiarioAvaliacaoDisciplina = $oMatricula->getDiarioDeClasse()
                            ->getDisciplinasPorRegencia(new Regencia($oRegencia->getCodigo()));
                        foreach ($diarioAreaAvaliacoes as $diarioAreaAvaliacao) {
                            $iNumeroFaltas = $oDiarioAvaliacaoDisciplina->getTotalFaltas();
                        }
                        $oPdf->Cell($oDadosRelatorio->iTamanhoColunasDisciplinas, $oDadosRelatorio->iAltura, "{$iNumeroFaltas}", 1, 0, "C");
                        $contadorColunasImpressas++;
                    }
                }

            }
            $colunasEmBranco = $oDadosRelatorio->iColunasPorPagina - $contadorColunasImpressas;
            imprimeColunasEmBranco($oPdf, $colunasEmBranco, $oDadosRelatorio);
        }
        $iLinhasImpressas++;
        $oPdf->Ln();
    }

    /**
     * Caso exista ao menos 1 aluno que necessite apresentar observacao, chamamos o metodo para impressao
     */
    if ($iTemObservacao > 0) {

        /**
         * Pegamos a posicao final de Y e X ao terminar de imprimir os alunos
         */
        $oDadosRelatorio->iPosicaoX = $oPdf->GetX();
        $oDadosRelatorio->iPosicaoY = $oPdf->GetY();
        mostraObservacoes($oPdf, $oDadosRelatorio);
    }
}

function imprimeGradeAproveitamentoAluno(scpdf $oPdf, $areasConhecimento, $oDadosRelatorio)
{
    $iLinhasImpressas = 1;
    $oPdf->SetY(54);
    $oPdf->SetFont('arial', '', 7);
    /**
     * Percorremos a matricula de cada aluno, para montar a grade de aproveitamento
     */
    foreach ($oDadosRelatorio->aAlunosMatriculados as $oMatricula) {

        if ($oDadosRelatorio->iTrocaTurma == 1 && $oMatricula->getSituacao() == 'TROCA DE TURMA') {
            continue;
        }

        $oDadosRelatorio->lProgressaoParcial = false;
        $iTemObservacao = 0;

        $oPdf->SetX(10);
        $iContadorDisciplinas = 1;

        if ($iLinhasImpressas > $oDadosRelatorio->iTotalAlunoPorPagina || $oPdf->gety() > $oPdf->h - 8) {
            $oPdf->AddPage();
            cabecalhoPadrao($oPdf, $oDadosRelatorio);
            posicionamentoCabecalho($oPdf, $oDadosRelatorio);
            cabecalhoPeriodosAreas($oPdf, $areasConhecimento, $oDadosRelatorio);

            $oPdf->SetY(54);
            $oPdf->SetX(10);
            $oPdf->SetFont('arial', '', 7);
            $iLinhasImpressas = 1;
        }

        $oPdf->SetFont('arial', '', 7);
        /**
         * Imprimimos o aluno e setamos a posicao do "X"
         */
        $oPdf->Cell(5, $oDadosRelatorio->iAltura, $oMatricula->getNumeroOrdemAluno(), 1, 0, "C");
        $oPdf->Cell(112, $oDadosRelatorio->iAltura, $oMatricula->getAluno()->getNome(), 1, 0, "L");

        $oPdf->SetX($oPdf->GetX());

        /**
         * Verificamos se o aluno nao esta matriculado na turma, apresentando o status na grade
         */
        if ($oMatricula->getSituacao() != 'MATRICULADO') {
            $iTamanhoColuna = $oDadosRelatorio->iColunasPorPagina * $oDadosRelatorio->iTamanhoColunasDisciplinas;
            $oPdf->Cell($iTamanhoColuna, $oDadosRelatorio->iAltura, $oMatricula->getSituacao(), 1, 0, "C");
        } else {

            /**
             * Percorremos cada regencia do aluno para preenchimento das notas
             */
            $colunasAreasImpressas = 0;
            $diarioAlunoService = $oMatricula->getDiarioDeClasse()->getDiarioAlunoService();
            db_fim_transacao();

            $contadorColunasImpressas = 0;

            foreach ($oDadosRelatorio->areasPorPagina as $areasConhecimento) {
                foreach ($areasConhecimento as $areaConhecimento) {
                    $colunasArea = count($areaConhecimento->disciplinas) + 1;
                    $colunasAreasImpressas += $colunasArea;
                    $diarioAreaAvaliacoes = null;
                    foreach ($diarioAlunoService->getDiarioAluno()->getDiarioAreasConhecimento() as $diarioAreaConhecimento) {
                        if ($diarioAreaConhecimento->getAreaConhecimento()->getCodigo() != $areaConhecimento->codigo) {
                            continue;
                        }
                        $diarioAreaAvaliacoes = $diarioAreaConhecimento->getAvaliacoes();

                        $sAproveitamento = buscaResultado($oDadosRelatorio, $diarioAreaAvaliacoes);

                        $oPdf->Cell($oDadosRelatorio->iTamanhoColunasDisciplinas, $oDadosRelatorio->iAltura, $sAproveitamento, 1, 0, "C");
                        $contadorColunasImpressas++;
                    }

                    foreach ($areaConhecimento->disciplinas as $oRegencia) {
                        $iNumeroFaltas = '--';
                        $oDiarioAvaliacaoDisciplina = $oMatricula->getDiarioDeClasse()
                            ->getDisciplinasPorRegencia(new Regencia($oRegencia->getCodigo()));

                        foreach ($diarioAreaAvaliacoes as $diarioAreaAvaliacao) {
                            $iNumeroFaltas = $oDiarioAvaliacaoDisciplina->getTotalFaltas();
                        }
                        $oPdf->Cell($oDadosRelatorio->iTamanhoColunasDisciplinas, $oDadosRelatorio->iAltura, "{$iNumeroFaltas}", 1, 0, "C");
                        $contadorColunasImpressas++;
                    }
                }
            }

            $sResultadoFinal = $oMatricula->getDiarioDeClasse()->getResultadoFinal();
            if ($oDadosRelatorio->iTipoPeriodo == 2) {
                $colunasEmBranco = $oDadosRelatorio->iColunasPorPagina - $contadorColunasImpressas - 1;
                imprimeColunasEmBranco($oPdf, $colunasEmBranco, $oDadosRelatorio);
                $oPdf->Cell($oDadosRelatorio->iTamanhoColunasDisciplinas, $oDadosRelatorio->iAltura, $sResultadoFinal, 1, 0, "C");
            } else {
                $colunasEmBranco = $oDadosRelatorio->iColunasPorPagina - $contadorColunasImpressas;
                imprimeColunasEmBranco($oPdf, $colunasEmBranco, $oDadosRelatorio);
            }
        }
        $iLinhasImpressas++;
        $oPdf->Ln();
    }

    /**
     * Caso exista ao menos 1 aluno que necessite apresentar observacao, chamamos o metodo para impressao
     */
    if ($iTemObservacao > 0) {
        /**
         * Pegamos a posicao final de Y e X ao terminar de imprimir os alunos
         */
        $oDadosRelatorio->iPosicaoX = $oPdf->GetX();
        $oDadosRelatorio->iPosicaoY = $oPdf->GetY();
        mostraObservacoes($oPdf, $oDadosRelatorio);
    }

}

function buscaResultado($oDadosRelatorio, $diarioAreaAvaliacoes)
{

    /** @var DiarioAreaAvaliacao $diarioAreaAvaliacao */

    $sAproveitamento = '';
    foreach ($diarioAreaAvaliacoes as $diarioAreaAvaliacao) {

        if ($diarioAreaAvaliacao->getAreaProcedimentoAvaliacao()->getFormaAvaliacao()->getTipo() == 'PARECER') {
            $sAproveitamento = 'PD';
            return $sAproveitamento;
        }

        /*
     * SE O ALUNO FOI AMPARADO
     */
        if ($diarioAreaAvaliacao->isAmparado()) {
            $sAproveitamento = 'AMP';
            return $sAproveitamento;
        }
        /*
         * Se o tipo de periodo for avaliacao, apresenta o resultado.
         */
        if ($oDadosRelatorio->iTipoPeriodo == 1 && $diarioAreaAvaliacao->getAreaProcedimentoAvaliacao()
                ->getPeriodoAvaliacao()->getCodigo() == $oDadosRelatorio->iPeriodoAvaliacao) {
            $sAproveitamento = round($diarioAreaAvaliacao->getNota());
            return $sAproveitamento;
        }

        /*
         * Se o tipo de periodo for resultado, apresenta o resultado.
         */
        if ($oDadosRelatorio->iTipoPeriodo == 2 && $diarioAreaAvaliacao->getDiarioArea()
                ->getResultado()->getAreaProcedimentoResultado()->getTipoResultado()
                ->getCodigo() == $oDadosRelatorio->iPeriodoAvaliacao) {
            $sAproveitamento = round($diarioAreaAvaliacao->getDiarioArea()->getResultado()->getNota());
            return $sAproveitamento;
        }
    }
}

/**
 * Montamos o cabecalho das disciplinas/faltas por periodo
 */
function cabecalhoPeriodosAreas(scpdf $oPdf, $areasConhecimento, $oDadosRelatorio)
{
    $contadorColunasImpressas = 0;
    foreach ($areasConhecimento as $areaConhecimento) {
        $oPdf->VCell($oDadosRelatorio->iTamanhoColunasDisciplinas, 40, $areaConhecimento->descricao, 1, 0, 'C', 1);
        foreach ($areaConhecimento->disciplinas as $regencia) {
            $oPdf->VCell($oDadosRelatorio->iTamanhoColunasDisciplinas, 40, "FALTAS - {$regencia->getDisciplina()->getAbreviatura()}", 1, 0, 'C', 0);
            $contadorColunasImpressas++;
        }
        $contadorColunasImpressas++;
    }

    $colunasEmBranco = $oDadosRelatorio->iColunasPorPagina - $contadorColunasImpressas;

    if ($oDadosRelatorio->iTipoPeriodo == 2 && $oDadosRelatorio->iTotalDisciplinas < $oDadosRelatorio->iColunasPorPagina) {
        imprimeColunasEmBranco($oPdf, $colunasEmBranco - 1, $oDadosRelatorio, true);
        $oPdf->VCell($oDadosRelatorio->iTamanhoColunasDisciplinas, 40, "A./R. RESULTADO", 1, 0, 'C', 0);
    } else {
        imprimeColunasEmBranco($oPdf, $colunasEmBranco, $oDadosRelatorio, true);
    }
}


/**
 * Metodo com as posicoes padroes dos periodos e disciplinas/faltas
 */
function posicionamentoCabecalho(scpdf $oPdf, $oDadosRelatorio)
{

    $oPdf->SetXY(127, 10);

    $oPdf->SetFont('arial', 'bi', 7);
    $oPdf->Cell(154, $oDadosRelatorio->iAltura, $oDadosRelatorio->sPeriodo, 1, 1, 'C', 1);


    $oPdf->SetXY(127, 14);
}

/**
 * Montamos o cabecalho padrao do relatorio
 */
function cabecalhoPadrao(scpdf $oPdf, $oDadosRelatorio)
{

    $oPdf->SetXY(10, 10);

    /**
     * Buscamos o nome do escola
     * Valida se a escola possui Código Referência e o adiciona na frente do nome
     */
    $oEscola = new Escola($oDadosRelatorio->oDados->iEscola);

    $sNomeEscola = $oEscola->getNome();
    $iCodigoReferencia = $oEscola->getCodigoReferencia();

    if ($iCodigoReferencia != null) {
        $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
    }

    $oDadosRelatorio->sEscola = $sNomeEscola;
    $sTituloPadrao = "DADOS DE IDENTIFICAÇÃO - LIVRO NOTAS";

    $oPdf->SetFont('arial', 'bi', 7);
    $oPdf->Cell(117, $oDadosRelatorio->iAltura, $sTituloPadrao, 1, 0, 'C', 1);

    $sDados = "Escola: {$oDadosRelatorio->sEscola}\n";
    $sDados .= "Profº: {$oDadosRelatorio->sDocente}\n";
    $sDados .= "Série: {$oDadosRelatorio->sEtapa}\n";
    $sDados .= "Turma: {$oDadosRelatorio->sTurma}\n";
    $sDados .= "Turno: {$oDadosRelatorio->sTurno}\n";
    $sDados .= "Dias Letivos: {$oDadosRelatorio->iDiasLetivos}               ";
    $sDados .= "Ano: {$oDadosRelatorio->iAno}\n\n\n\n";

    $oPdf->SetXY(10, 14);
    $oPdf->SetFont('arial', '', 7);
    $oPdf->MultiCell(117, 4, $sDados, 0);

    $oPdf->Rect(10, 10, 117, 44);
}

/**
 * Método que imprime as observacoes em casos de aprovado com progressao parcial ou parecer descritivo
 * @param SCPF $oPdf
 * @param object $oDadosRelatorio
 */
function mostraObservacoes(scpdf $oPdf, $oDadosRelatorio)
{

    $sObservacoes = '';

    /**
     * 202 eh o limite maximo permitido na pagina
     */
    $iTamanhoRect = 202 - $oDadosRelatorio->iPosicaoY;

    if ($oDadosRelatorio->ltemObservacaoProgressaoParcial) {
        $sObservacoes .= "OBSERVAÇÕES:\n";
        $sObservacoes .= "* Aprovado com Progressão Parcial\n";
    }

    if ($oDadosRelatorio->sFormaAvaliacao == 'PARECER') {
        $sObservacoes .= "\nLEGENDA:\n";
        $sObservacoes .= "PD - Parecer Descritivo";
    }

    $oPdf->MultiCell(271, 4, $sObservacoes, 0);
    $oPdf->Rect($oDadosRelatorio->iPosicaoX, $oDadosRelatorio->iPosicaoY, 271, $iTamanhoRect);
}

function imprimeColunasEmBranco(scpdf $pdf, $iColunasEmBranco, $oDadosRelatorio, $lCabecalho = false)
{

    if ($iColunasEmBranco < 0) {
        $iColunasEmBranco = 0;
    }
    while ($iColunasEmBranco != 0) {
        if ($lCabecalho) {
            $pdf->VCell($oDadosRelatorio->iTamanhoColunasDisciplinas, 40, '', 1, 0, 'C', 0);
        } else {
            $pdf->Cell($oDadosRelatorio->iTamanhoColunasDisciplinas, $oDadosRelatorio->iAltura, "", 1, 0, "C");
        }
        $iColunasEmBranco--;
    }
}

$oPdf->Output();

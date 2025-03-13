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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$oJson = new services_json();
$oParam = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMessage = '';

define('MSG_EDU4_REGENCIAHORARIORPC', 'educacao.escola.edu4_regenciaHorarioRPC.');

try {
    db_inicio_transacao();

    switch ($oParam->exec) {
        case "removerPeriodo":
            if (empty($oParam->iTurma)) {
                throw new Exception(_M(MSG_EDU4_REGENCIAHORARIORPC . "informe_turma"));
            }

            if (empty($oParam->iEtapa)) {
                throw new Exception(_M(MSG_EDU4_REGENCIAHORARIORPC . "informe_etapa"));
            }

            $oTurma = new Turma($oParam->iTurma);
            $oEtapa = new Etapa($oParam->iEtapa);
            $oGrade = new GradeHorario($oTurma, $oEtapa);
            $horariosRegencia = horariosRegencia($oParam);
            $aPeriodos = $oGrade->getPeriodosAula();
            $oParam->iRecHumano = isset($oParam->iRecHumano) ? $oParam->iRecHumano : 0;
          
        /* Se o recurso humano for 0, é uma disciplina sem professor. Removemos ela direto pelo codigo */
            if ($oParam->iRecHumano == 0) {
                if(isset($oParam->iRegenciaHorario)) {
                    $oDaoRegenciaHorarioDiscSemReg = new cl_regenciahorariodiscsemreg();
                    $oDaoRegenciaHorarioDiscSemReg->excluir($oParam->iRegenciaHorario, null);
                } else {
                    foreach ($aPeriodos as $oPeriodo) {
                        if (intval($oPeriodo->getRegente()) != 0)  {
                            $oPeriodo->remover();
                            atualizarFaltasDiarioDeClasse($oTurma, $oEtapa, $oPeriodo->getRegencia());
                        } else {
                            $oPeriodo->removerDisciplinaSemRegente();
                        }   
                    }
                }
            } else {
               
                foreach ($aPeriodos as $oPeriodo) {
                    if (!empty($horariosRegencia)) {
                        if (in_array($oPeriodo->getCodigo(), $horariosRegencia)) {
                            $oPeriodo->remover();
                            atualizarFaltasDiarioDeClasse($oTurma, $oEtapa, $oPeriodo->getRegencia());
                        }
                    } else {
                        $oPeriodo->remover();
                        atualizarFaltasDiarioDeClasse($oTurma, $oEtapa, $oPeriodo->getRegencia());
                    }
                }
            }
            removerRegenteConcelheiro($oTurma, $oEtapa, $oParam);
            $oRetorno->sMessage = _M(MSG_EDU4_REGENCIAHORARIORPC . "periodo_removido_com_sucesso");
            break;

        case "removerPeriodoAteData":
            if (empty($oParam->iTurma)) {
                throw new Exception(_M(MSG_EDU4_REGENCIAHORARIORPC . "informe_turma"));
            }

            if (empty($oParam->iEtapa)) {
                throw new Exception(_M(MSG_EDU4_REGENCIAHORARIORPC . "informe_etapa"));
            }

            if (empty($oParam->sData)) {
                throw new Exception(_M(MSG_EDU4_REGENCIAHORARIORPC . "informe_data"));
            }


            $oTurma = new Turma($oParam->iTurma);
            $oEtapa = new Etapa($oParam->iEtapa);
            $oGrade = new GradeHorario($oTurma, $oEtapa);

            $horariosRegencia = horariosRegencia($oParam);

            $aPeriodos = $oGrade->getPeriodosAula();
            foreach ($aPeriodos as $oPeriodo) {
                if (!empty($horariosRegencia)) {
                    if (in_array($oPeriodo->getCodigo(), $horariosRegencia)) {
                        $oPeriodo->inativarAte(new DBDate($oParam->sData));
                    }
                } else {
                    $oPeriodo->inativarAte(new DBDate($oParam->sData));
                }
            }

            removerRegenteConcelheiro($oTurma, $oEtapa, $oParam);
            $oRetorno->sMessage = _M(MSG_EDU4_REGENCIAHORARIORPC . "alteracao_salvo_sucesso");
            break;
    }

    db_fim_transacao(false);
} catch (Exception $eErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);


function removerRegenteConcelheiro($oTurma, $oEtapa, $oParam)
{
    $oDao = new cl_regenteconselho();
    $sWhere = "ed235_i_turma = {$oTurma->getCodigo()}";
    $lRemover = true;

    if (!empty($oParam->iRecHumano)) {
        $lRemover = false;
        $oGrade = new GradeHorario($oTurma, $oEtapa);
        $aPeriodos = $oGrade->getPeriodosAula();

        $lTemVinculo = false;

        foreach ($aPeriodos as $oPeriodo) {
            if ($oPeriodo->getRegente() == $oParam->iRecHumano) {
                $lTemVinculo = true;
                break;
            }
        }

        if (!$lTemVinculo) {
            $sWhere .= " and ed235_i_rechumano = {$oParam->iRecHumano} ";
            $lRemover = true;
        }
    }

    if ($lRemover) {
        $oDao->excluir(null, $sWhere);

        if ($oDao->erro_status == 0) {
            throw new Exception(_M(MSG_EDU4_REGENCIAHORARIORPC . "erro_remover_regente_conselheiro"));
        }
    }
}

function atualizarFaltasDiarioDeClasse($oTurma, $oEtapa, $oRegencia)
{
    $aMatriculas = $oTurma->getAlunosMatriculadosNaturmaPorSerie($oEtapa);
    $aPeriodos = $oTurma->getCalendario()->getPeriodos();

    foreach ($aMatriculas as $oMatricula) {
        $oDiarioClasse = $oMatricula->getDiarioDeClasse();
        $oDisciplina = $oDiarioClasse->getDisciplinasPorRegencia($oRegencia);

        foreach ($aPeriodos as $oPeriodoCalendario) {
            $iTotalDeFaltas = $oDisciplina->getTotalDeFaltasPorPeriodoDeAula($oPeriodoCalendario->getPeriodoAvaliacao());

            foreach ($oDisciplina->getAvaliacoes() as $oAvaliacao) {
                if (!$oAvaliacao->getElementoAvaliacao()->isResultado()) {
                    $oPeriodoAvaliacao = $oAvaliacao->getElementoAvaliacao()->getPeriodoAvaliacao();

                    if ($oPeriodoAvaliacao->getCodigo() == $oPeriodoCalendario->getPeriodoAvaliacao()->getCodigo()) {
                        $oAvaliacao->setNumeroFaltas($iTotalDeFaltas);
                    }
                }
            }
        }

        $oDisciplina->salvar();
    }
}


function buscarHorariosRegencia($regencia, $recHumano)
{
    $sql = "
        select ed58_i_codigo from regenciahorario
        where ed58_i_regencia = {$regencia} and ed58_i_rechumano = {$recHumano}
    ";
    $rs = db_query($sql);
    $horariosRegencia = [];
    while ($state = pg_fetch_array($rs)) {
        $horariosRegencia[] = $state['ed58_i_codigo'];
    }
    return $horariosRegencia;
}

function horariosRegencia($parametros)
{

    $horariosRegencia = [];
    if (!empty($parametros->iRegenciaHorario)) {
        $horariosRegencia = [$parametros->iRegenciaHorario];

    }
    if (empty($parametros->iRegenciaHorario) && !empty($parametros->iRegencia)) {
        $horariosRegencia = buscarHorariosRegencia($parametros->iRegencia, $parametros->iRecHumano);
    }
    return $horariosRegencia;
}

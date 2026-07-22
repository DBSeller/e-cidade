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


use ECidade\Educacao\Escola\Registry\AlunoRegistry;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("std/DBDate.php"));

define("URL_MENSAGEM_TURMAAC_RPC", "educacao.escola.edu4_turmaac_RPC.");

$iEscola = db_getsession("DB_coddepto");
$oJson = new Services_JSON();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMensagem = '';

try {

    switch ($oParam->sExecutar) {
        case 'vincularProfissional':
            $oDaoTurmaAc = new cl_turmaac();
            $sSqlAno = $oDaoTurmaAc->sql_query($oParam->iTurmaAc, " ed52_i_ano, ed268_i_escola ");
            $rsAno = $oDaoTurmaAc->sql_record($sSqlAno);
            $iAnoTurma = db_utils::fieldsMemory($rsAno, 0)->ed52_i_ano;
            $iEscolaTurma = db_utils::fieldsMemory($rsAno, 0)->ed268_i_escola;

            $oDaoTurmaHorarioProfissional = new cl_turmaachorarioprofissional();

            db_inicio_transacao();

            foreach ($oParam->aDiaSemana as $iDiaSemana) {

                $oDaoTurmaHorarioProfissional->ed346_sequencial = null;
                $oDaoTurmaHorarioProfissional->ed346_turmaac = $oParam->iTurmaAc;
                $oDaoTurmaHorarioProfissional->ed346_funcaoatividade = $oParam->iFuncaoAtividade;
                $oDaoTurmaHorarioProfissional->ed346_rechumano = $oParam->iRecHumano;
                $oDaoTurmaHorarioProfissional->ed346_diasemana = $iDiaSemana;
                $oDaoTurmaHorarioProfissional->ed346_horainicial = $oParam->sHoraInicial;
                $oDaoTurmaHorarioProfissional->ed346_horafinal = $oParam->sHoraFinal;

                $oDaoTurmaHorarioProfissional->incluir(null);

                if ($oDaoTurmaHorarioProfissional->erro_status == 0) {
                    $oErro = new stdClass();
                    $oErro->ErroSql = str_replace('\\n', "\n", $oDaoTurmaHorarioProfissional->erro_msg);
                    throw new DBException(_M(URL_MENSAGEM_TURMAAC_RPC . "erro_vincular_profissional"));
                }
            }

            db_fim_transacao();
            $oRetorno->sMensagem = urlencode(_M(URL_MENSAGEM_TURMAAC_RPC . "profissional_vinculado"));

            break;

        case 'vincularHorarioSemProfissional':
            $aMapaDias = array(1 => 'DOMINGO', 2 => 'SEGUNDA', 3 => 'TERÇA', 4 => 'QUARTA', 5 => 'QUINTA', 6 => 'SEXTA', 7 => 'SABADO');

            $oDaoTurmaAc = new cl_turmaac();
            $sSqlAno = $oDaoTurmaAc->sql_query($oParam->iTurmaAc, " ed52_i_ano, ed268_i_escola ");
            $rsAno = $oDaoTurmaAc->sql_record($sSqlAno);
            $iAnoTurma = db_utils::fieldsMemory($rsAno, 0)->ed52_i_ano;
            $iEscolaTurma = db_utils::fieldsMemory($rsAno, 0)->ed268_i_escola;

            $aWhere = array();
            $aWhere[] = " ed52_i_ano = {$iAnoTurma} ";
            $aWhere[] = " ed268_i_escola = {$iEscolaTurma} ";
            $aWhere[] = " (('{$oParam->sHoraInicial}'::time, '{$oParam->sHoraFinal}'::time) overlaps (ed176_horainicial::time, ed176_horafinal::time)) ";
            $aWhere[] = " ed176_rechumano is null";

            $oDaoTurmaHorarioProfissionalSemRec = new cl_turmaachorarioprofissionalsemrec();

            $lTemConflito = false;
            $aDiasConflito = array();
            db_inicio_transacao();

            foreach ($oParam->aDiaSemana as $iDiaSemana) {
                $sWhere = implode(" and ", $aWhere);
                $sWhere .= " and ed176_diasemana = {$iDiaSemana} ";
                $sSqlVerificaConflito = $oDaoTurmaHorarioProfissionalSemRec->sql_query(null, "1", null, $sWhere);

                $rsVerificaConflito = $oDaoTurmaHorarioProfissionalSemRec->sql_record($sSqlVerificaConflito);

                if ($oDaoTurmaHorarioProfissionalSemRec->numrows > 0) {
                    $lTemConflito = true;
                    $aDiasConflito[] = $iDiaSemana;
                    continue;
                }

                $oDaoTurmaHorarioProfissionalSemRec->ed176_sequencial = null;
                $oDaoTurmaHorarioProfissionalSemRec->ed176_turmaac = $oParam->iTurmaAc;
                $oDaoTurmaHorarioProfissionalSemRec->ed176_funcaoatividade = $oParam->iFuncaoAtividade;
                $oDaoTurmaHorarioProfissionalSemRec->ed176_rechumano = $oParam->iRecHumano;
                $oDaoTurmaHorarioProfissionalSemRec->ed176_diasemana = $iDiaSemana;
                $oDaoTurmaHorarioProfissionalSemRec->ed176_horainicial = $oParam->sHoraInicial;
                $oDaoTurmaHorarioProfissionalSemRec->ed176_horafinal = $oParam->sHoraFinal;

                $oDaoTurmaHorarioProfissionalSemRec->incluir(null);

                if ($oDaoTurmaHorarioProfissionalSemRec->erro_status == 0) {
                    $oErro = new stdClass();
                    $oErro->ErroSql = str_replace('\\n', "\n", $oDaoTurmaHorarioProfissionalSemRec->erro_msg);
                    throw new DBException(_M(URL_MENSAGEM_TURMAAC_RPC . "erro_vincular_profissional"));
                }
            }

            db_fim_transacao();
            $oRetorno->sMensagem = urlencode(_M(URL_MENSAGEM_TURMAAC_RPC . "profissional_vinculado"));

            if ($lTemConflito) {
                $aDias = array();
                foreach ($aDiasConflito as $iDia) {
                    $aDias[] = $aMapaDias[$iDia];
                }
                $oMsg = new stdClass();
                $oMsg->sDiasConflitados = implode(", ", $aDias);
                $oRetorno->sMensagem = urlencode(_M(URL_MENSAGEM_TURMAAC_RPC . "profissional_vinculado_outras_turmas", $oMsg));
            }
            break;

        case 'getAtividades':
            $oDaoFuncaoAtividade = new cl_funcaoatividade();
            $sWhere = " ed119_sequencial in (1, 3, 4) ";
            $sSqlFuncaoAtividade = $oDaoFuncaoAtividade->sql_query_file(null, "*", "ed119_descricao", $sWhere);
            $rsFuncaoAtividade = $oDaoFuncaoAtividade->sql_record($sSqlFuncaoAtividade);

            $iLinhas = $oDaoFuncaoAtividade->numrows;

            $oRetorno->aFuncoes = array();
            for ($i = 0; $i < $iLinhas; $i++) {
                $oDados = db_utils::fieldsMemory($rsFuncaoAtividade, $i, true);

                /**
                 * Turmas de ATENDIMENTO EDUCACIONAL ESPECIAL (AEE) não podem ter:
                 * a atividade 3 - Profissional/Monitor de atividade complementar
                 */
                if ($oParam->iAtendimento == 5 && $oDados->ed119_sequencial == 3) {
                    continue;
                }
                $oRetorno->aFuncoes[] = $oDados;
            }

            break;
        case 'getProfessoresVinculados':
            $sCampos = " distinct                        ";
            $sCampos .= " ed346_sequencial,               ";
            $sCampos .= " ed20_i_codigo,                  ";
            $sCampos .= " case                            ";
            $sCampos .= "    when ed20_i_tiposervidor = 1 ";
            $sCampos .= "      then trim(cgmrh.z01_nome)  ";
            $sCampos .= "    else trim(cgmcgm.z01_nome)   ";
            $sCampos .= " end as profissional,            ";
            $sCampos .= " ed119_sequencial,               ";
            $sCampos .= " ed119_descricao,                ";
            $sCampos .= " ed32_i_codigo,                  ";
            $sCampos .= " ed32_c_descr,                   ";
            $sCampos .= " ed346_horainicial,              ";
            $sCampos .= " ed346_horafinal                 ";

            $sWhere = " ed346_turmaac = {$oParam->iTurmaAc} ";
            $sWhereUnion = " ed176_turmaac = {$oParam->iTurmaAc} ";
            $oDaoTurmaHorarioProfissional = new cl_turmaachorarioprofissional();

            $sSqlVinculos = $oDaoTurmaHorarioProfissional->sql_query_vinculo_profissional_union_sem_profissional(null, $sCampos, '', $sWhere, $sWhereUnion);
            $rsVinculos = $oDaoTurmaHorarioProfissional->sql_record($sSqlVinculos);
            $iLinhas = $oDaoTurmaHorarioProfissional->numrows;

            $oRetorno->aVinculados = array();

            for ($i = 0; $i < $iLinhas; $i++) {
                $oDados = db_utils::fieldsMemory($rsVinculos, $i);
                $oProfissional = new stdClass();
                $oProfissional->iCodigo = $oDados->ed346_sequencial;
                $oProfissional->iRecHumano = $oDados->ed20_i_codigo;
                $oProfissional->sRecHumano = urlencode($oDados->profissional);
                $oProfissional->iAtividade = $oDados->ed119_sequencial;
                $oProfissional->sAtividade = urlencode($oDados->ed119_descricao);
                $oProfissional->iDia = $oDados->ed32_i_codigo;
                $oProfissional->sDia = urlencode($oDados->ed32_c_descr);
                $oProfissional->sHoraInicial = $oDados->ed346_horainicial;
                $oProfissional->sHoraFinal = $oDados->ed346_horafinal;

                if ($oProfissional->iRecHumano == 0) {
                    $oProfissional->sRecHumano = "SEM PROFISSIONAL/MONITOR VINCULADO";
                    $oProfissional->sAtividade = "Sem Atividade";
                }

                $oRetorno->aVinculados[] = $oProfissional;
            }
            break;
        case 'removerVinculo':
            if ($oParam->iRecHumano == 0) {
                $oDaoTurmaHorarioProfissional = new cl_turmaachorarioprofissionalsemrec();
                $oDaoTurmaHorarioProfissional->ed176_sequencial = $oParam->iVinculo;
            } else {
                $oDaoTurmaHorarioProfissional = new cl_turmaachorarioprofissional();
                $oDaoTurmaHorarioProfissional->ed346_sequencial = $oParam->iVinculo;
            }

            db_inicio_transacao();
            $oDaoTurmaHorarioProfissional->excluir($oParam->iVinculo);

            if ($oDaoTurmaHorarioProfissional->erro_status == 0) {
                $oErro = new stdClass();
                $oErro->ErroSql = str_replace('\\n', "\n", $oDaoTurmaHorarioProfissional->erro_msg);
                throw new DBException(_M(URL_MENSAGEM_TURMAAC_RPC . "erro_desvincular_profissional"));
            }
            db_fim_transacao();
            $oRetorno->sMensagem = urlencode(_M(URL_MENSAGEM_TURMAAC_RPC . "vinculo_removido"));

            break;
        case 'validaTurnoTurma':
            if (empty($oParam->codigo_aluno)) {
                throw new Exception("Informe o código do aluno.");
            }
            if (empty($oParam->codigo_turma)) {
                throw new Exception("Informe o código da turma de Atividade Complementar/AEE.");
            }

            $aluno = new Aluno($oParam->codigo_aluno);
            $daoTurmaAc = new cl_turmaac();
            $sqlTurmaAc = $daoTurmaAc->sql_query_file($oParam->codigo_turma);
            $rsTurmaAc = db_query($sqlTurmaAc);

            if (!$rsTurmaAc) {
                throw new Exception("Erro ao buscar turma de atividade complementar");
            }

            $oTurmaAc = pg_fetch_object($rsTurmaAc);
            $matriculaAtiva = MatriculaRepository::getMatriculaAtivaPorAluno($aluno);
            if (is_null($matriculaAtiva)) {
                $oRetorno->sMensagem = "Permitir vinculo";
                break;
            }

            $daoTurmaEspecialHorarioProfissional = new cl_turmaachorarioprofissional();
            $sqlTurmaEspecialHorario = $daoTurmaEspecialHorarioProfissional->sql_query_file(
                null,
                '*',
                null,
                " ed346_turmaac = {$oTurmaAc->ed268_i_codigo} "
            );
            $rsHorarios = db_query($sqlTurmaEspecialHorario);
            if (!$rsHorarios) {
                throw new Exception("Erro ao buscar horários de Turma Especial.");
            }

            $turma = $matriculaAtiva->getTurma();
            $turno = $turma->getTurno();
            if ($oTurmaAc->ed268_i_tipoatend == 5 && $turma->getTurnoReferente() > 1) {
                $oRetorno->sMensagem = "Permitir vinculo";
                break;
            }

            $periodos = $turma->getEscola()->getPeriodosEscola($turno);
            $horaInicio = array_shift($periodos)->getHoraInicio();
            $timeInicio = strtotime($horaInicio);
            $horaFim = array_pop($periodos)->getHoraFim();
            $timeFim = strtotime($horaFim);
            while ($horarioTurmaEspecial = pg_fetch_object($rsHorarios)) {
                $horaInicioTurmaAc = strtotime($horarioTurmaEspecial->ed346_horainicial);
                $horaFimTurmaAc = strtotime($horarioTurmaEspecial->ed346_horafinal);
                if (($horaInicioTurmaAc <= $timeFim) && ($horaFimTurmaAc >= $timeInicio)) {
                    $oRetorno->status = 3;
                    throw new Exception("Não e possível efetuar matrícula, horário incompatível.");
                }
            }

            $oRetorno->sMensagem = "Permitir vinculo";
            break;
    }
} catch (Exception $oErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);

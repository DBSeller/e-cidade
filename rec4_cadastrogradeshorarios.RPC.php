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

require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_jornada_classe.php"));
require_once(modification("classes/db_jornadahoras_classe.php"));

$oJson = new services_json(0, true);
$oParametros = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status = true;
$oRetorno->erro = false;
$oRetorno->message = '';

try {
    switch ($oParametros->exec) {
        case 'getInformacoesGradeDia':
            $aJornadaHorarios         = array();
            $oDaoGradeHorarioJornadas = new cl_gradeshorariosjornada();

            $sCamposGradeHorarioJornadas  = "  gradeshorariosjornada.rh191_ordemhorario as ordemhorario";
            $sCamposGradeHorarioJornadas .= ", gradeshorariosjornada.rh191_jornada as jornada";
            $sCamposGradeHorarioJornadas .= ", jornada.rh188_descricao as descricao";

            $sSqlGradeHorarioJornadas = "select {$sCamposGradeHorarioJornadas} 
                                     from gradeshorariosjornada 
                                     inner join gradeshorarios on rh191_gradehorarios = rh190_sequencial
                                     inner join jornada on jornada.rh188_sequencial = gradeshorariosjornada.rh191_jornada 
                                    where gradeshorariosjornada.rh191_gradehorarios = {$oParametros->iCodigoGrade}
                                    order by rh191_ordemhorario";

            $rsGradeHorarioJornadas   = db_query($sSqlGradeHorarioJornadas);
            $aGradeHorarioJornadas    = db_utils::getCollectionByRecord($rsGradeHorarioJornadas);

            foreach ($aGradeHorarioJornadas as $oGradeHorarioJornada) {
                $oJornada                 = new stdClass();

                $oJornada->iOrdem         = $oGradeHorarioJornada->ordemhorario;
                $oJornada->iCodigoJornada = $oGradeHorarioJornada->jornada;
                $oJornada->sDescricao     = urlEncode($oGradeHorarioJornada->descricao);

                $aJornadaHorarios[]       = $oJornada;
            }

            $oRetorno->aJornadaHorarios = $aJornadaHorarios;
            break;

        case 'getInformacoesGrade':
            $aJornadaHorarios         = array();
            $oDaoGradeHorarioJornadas = new cl_gradeshorariosjornada();

            $sCamposGradeHorarioJornadas  = "  gradeshorariosjornada.rh191_ordemhorario as ordemhorario";
            $sCamposGradeHorarioJornadas .= ", gradeshorariosjornada.rh191_jornada as jornada";
            $sCamposGradeHorarioJornadas .= ", jornada.rh188_descricao as descricao";

            $sSqlGradeHorarioJornadas = "select {$sCamposGradeHorarioJornadas} 
                                     from gradeshorariosjornada 
                                          inner join jornada on jornada.rh188_sequencial = gradeshorariosjornada.rh191_jornada 
                                    where gradeshorariosjornada.rh191_gradehorarios = {$oParametros->iCodigoGrade}
                                    order by rh191_ordemhorario";

            $rsGradeHorarioJornadas   = db_query($sSqlGradeHorarioJornadas);
            $aGradeHorarioJornadas    = db_utils::getCollectionByRecord($rsGradeHorarioJornadas);

            foreach ($aGradeHorarioJornadas as $oGradeHorarioJornada) {
                $oJornada                 = new stdClass();

                $oJornada->iOrdem         = $oGradeHorarioJornada->ordemhorario;
                $oJornada->iCodigoJornada = $oGradeHorarioJornada->jornada;
                $oJornada->sDescricao     = urlEncode($oGradeHorarioJornada->descricao);

                $aJornadaHorarios[]       = $oJornada;
            }

            $oRetorno->aJornadaHorarios = $aJornadaHorarios;
            break;

        case 'salvar':
            db_inicio_transacao();

            $iCodigo                  = $oParametros->iCodigoGrade;
            $sDescricao               = $oParametros->sDescricaoGrade;
            $dDataBase                = $oParametros->dDataBase;
            $aGradeHorariosJornadas   = $oParametros->aJornadas;
            $fRevezamento             = $oParametros->fRevezamento;
            $fExtraAutorizadaFeriado  = $oParametros->fExtraAutorizadaFeriado;

            $oDaoGradesHorarios                                 = new cl_gradeshorarios;

      // para a Dao básica não apagar os outros dados na alteração
      if (!empty($iCodigo)) {
          $oDaoGradesHorarios = setaDadosJaSalvos($oDaoGradesHorarios, $iCodigo);
      }

            $oDaoGradesHorarios->rh190_sequencial               = $iCodigo;
            $oDaoGradesHorarios->rh190_descricao                = db_stdClass::normalizeStringJsonEscapeString($sDescricao);
            $oDaoGradesHorarios->rh190_database                 = $dDataBase;
            $oDaoGradesHorarios->rh190_revezamento              = $fRevezamento;
            $oDaoGradesHorarios->rh190_extra_autorizada_feriado = $fExtraAutorizadaFeriado;
            $oDaoGradesHorarios->rh190_instit     = db_getsession('DB_instit');

            if (empty($iCodigo)) {
                $oDaoGradesHorarios->incluir(null);
            } else {
                $oDaoGradesHorarios->alterar($iCodigo);
            }

            $iCodigo = $oDaoGradesHorarios->rh190_sequencial;

            if ($oDaoGradesHorarios->erro_status == "0") {
                throw new Exception('Erro ao salvar dados. ERRO: ' . $oDaoGradesHorarios->erro_msg);
            }

            $oDaoGradeHorarioJornadas = new cl_gradeshorariosjornada();
            $oDaoGradeHorarioJornadas->excluir(null, "rh191_gradehorarios = {$iCodigo}");

            if ($oDaoGradeHorarioJornadas->erro_status == '0') {
                throw new Exception('ERRO ao excluir dados de gradeshorariosjornada. ERRO: ' . $oDaoGradeHorarioJornadas->erro_msg);
            }

            foreach ($aGradeHorariosJornadas as $oGradeHorarioJornada) {
                $oDaoGradeHorarioJornadas->rh191_sequencial    = '';
                $oDaoGradeHorarioJornadas->rh191_gradehorarios = $iCodigo;
                $oDaoGradeHorarioJornadas->rh191_ordemhorario  = $oGradeHorarioJornada->iOrdem;
                $oDaoGradeHorarioJornadas->rh191_jornada       = $oGradeHorarioJornada->iCodigoJornada;
                $oDaoGradeHorarioJornadas->incluir(null);

                if ($oDaoGradeHorarioJornadas->erro_status == '0') {
                    throw new Exception('ERRO ao salvar dados de gradeshorariosjornada' . $oGradeHorarioJornada->erro_msg);
                }
            }

            $oRetorno->iCodigoGradeHorarios = $iCodigo;
            $oRetorno->message = utf8_encode("Salvo com sucesso.\n Código: " .  $oRetorno->iCodigoGradeHorarios);

            db_fim_transacao();
            break;

        case 'excluir':
            db_inicio_transacao();
            $sSqlEscalaServidor = "select * from escalaservidor where rh192_gradeshorarios = {$oParametros->iCodigoGrade}";
            $rsEscalaServidor   = db_query($sSqlEscalaServidor);

            if (pg_num_rows($rsEscalaServidor) > 0) {
                throw new Exception('Erro ao excluir grade de horário. Ela está sendo referênciada por uma ou mais escalas.');
            }

            $sSqlGradesHorariosJornada = "delete from gradeshorariosjornada where rh191_gradehorarios = {$oParametros->iCodigoGrade}";
            if (!db_query($sSqlGradesHorariosJornada)) {
                throw new Exception('Erro ao excluir gradeshorariosjornada. ERRO: '. pg_last_error());
            }

            $sSqlGradesHorarios = "delete from gradeshorarios where rh190_sequencial = {$oParametros->iCodigoGrade}";
            if (!db_query($sSqlGradesHorarios)) {
                throw new Exception('Erro ao excluir gradehorario. ERRO: ' . pg_last_error());
            }

            $oRetorno->message = DBString::urlencode_all('Dados excluídos com sucesso.');

            db_fim_transacao();

            break;

    case 'salvarDadosESocial':

        if (empty($oParametros->escala)) {
            throw new Exception("Informe a escala.");
    }

        if (empty($oParametros->dataInicial)) {
            throw new Exception("A data inicial é obridatória.");
        }
        $dao = new cl_gradeshorarios();
        $dao = setaDadosJaSalvos($dao, $oParametros->escala);
        $oParametros->dataInicial = new DBDate($oParametros->dataInicial);
        $dao->rh190_datainicial = $oParametros->dataInicial->getDate();
        $dao->rh190_datafinal = "null";

        if (!empty($oParametros->dataFinal)) {
            $oParametros->dataFinal = new DBDate($oParametros->dataFinal);
            $dao->rh190_datafinal = $oParametros->dataFinal->getDate();
        }

        $dao->alterar($oParametros->escala);
        if ($dao->erro_status == "0") {
            throw new Exception('Erro ao salvar dados. ERRO: ' . $oDaoGradesHorarios->erro_msg);
        }
        $oRetorno->message = urlencode("Dados salvo com sucesso.");
      break;
  }

} catch (Exception $eException) {

    $oRetorno->erro       = true;
    $oRetorno->message    = urlencode($eException->getMessage());
}

echo $oJson->encode($oRetorno);


function setaDadosJaSalvos($dao, $id) {
    $rs = db_query($dao->sql_query_file($id));
    db_utils::makeFromRecord($rs, function ($dado) use ($dao) {
        $dao->rh190_sequencial = $dado->rh190_sequencial;
        $dao->rh190_descricao = $dado->rh190_descricao;
        $dao->rh190_database = empty($dado->rh190_database) ? "null" : $dado->rh190_database;
        $dao->rh190_revezamento = $dado->rh190_revezamento;
        $dao->rh190_extra_autorizada_feriado = $dado->rh190_extra_autorizada_feriado;
        $dao->rh190_datainicial = empty($dado->rh190_datainicial) ? "null" : $dado->rh190_datainicial;
        $dao->rh190_datafinal = empty($dado->rh190_datafinal) ? "null" : $dado->rh190_datafinal;
        $dao->rh190_instit = $dado->rh190_instit;
    }, 0);

    return $dao;
}

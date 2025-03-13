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

$oJson             = new services_json(0,true);
$oParametros       = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = true;
$oRetorno->erro    = false;
$oRetorno->message = '';
$codigoInstituicao = db_getsession("DB_instit");
$matricula         = $oParametros->iMatricula;
$sequencial        = null;

try {

  switch ($oParametros->exec) {

    case 'incluir':

      db_inicio_transacao();

      if (empty($oParametros->iMatricula)) {
        throw new Exception('Matrícula do servidor não informada.');
      }

      $dao = new \cl_rhservidorcontratojornada();
      $where = "rh254_matricula = {$matricula} and rh254_instit = {$codigoInstituicao} ";
      $sql = $dao->sql_query(null, 'rhservidorcontratojornada.rh254_sequencial', null, $where);
      $rs = db_query($sql);
      if ($rs) {
        if (pg_num_rows($rs) > 0) {
            $dado = db_utils::fieldsMemory($rs, 0);
            $sequencial = $dado->rh254_sequencial;
        }
      }

      $dao->rh254_matricula        = $matricula;
      $dao->rh254_instit           = $codigoInstituicao;  
      $dao->rh254_tipojornada      = $oParametros->rh254_tipojornada;
      $dao->rh254_tempoparcial     = $oParametros->rh254_tempoparcial;
      $dao->rh254_horarionoturno   = $oParametros->rh254_horarionoturno;
      $dao->rh254_descricaojornada = $oParametros->rh254_descricaojornada;


      if (empty($sequencial)) {
        $dao->incluir($sequencial);
      } else {
        $dao->alterar($sequencial);
      }

      if ($dao->erro_status == "0") {
        throw new Exception('Erro ao salvar dados. ERRO: ' . $dao->erro_msg);
      }

      $oRetorno->rh254_sequencial = $dao->rh254_sequencial;
      $oRetorno->rh254_instit = $dao->rh254_instit;
      $oRetorno->rh254_tipojornada = $dao->rh254_tipojornada;
      $oRetorno->rh254_tempoparcial = $dao->rh254_tempoparcial;
      $oRetorno->rh254_horarionoturno = $dao->rh254_horarionoturno;
      $oRetorno->rh254_descricaojornada = $dao->rh254_descricaojornada;
      $oRetorno->message = "Salvo com sucesso.";
      db_fim_transacao();

      break;

    case 'carregarContratoJornadaEscala':
    
      $camposContratoJornadaEscala  = [];
      $camposContratoJornadaEscala[]  = "rhservidorcontratojornada.rh254_sequencial";
      $camposContratoJornadaEscala[]  = "rhservidorcontratojornada.rh254_matricula";
      $camposContratoJornadaEscala[]  = "rhservidorcontratojornada.rh254_instit";
      $camposContratoJornadaEscala[]  = "rhservidorcontratojornada.rh254_tipojornada";
      $camposContratoJornadaEscala[]  = "rhservidorcontratojornada.rh254_tempoparcial";
      $camposContratoJornadaEscala[]  = "rhservidorcontratojornada.rh254_horarionoturno";
      $camposContratoJornadaEscala[]  = "rhservidorcontratojornada.rh254_descricaojornada";

      implode(', ', $camposContratoJornadaEscala);
      $dao = new \cl_rhservidorcontratojornada();
      $where = "rh254_matricula = {$matricula} and rh254_instit = {$codigoInstituicao} ";
      $sql = $dao->sql_query(null, implode(', ', $camposContratoJornadaEscala), null, $where);
      $rs = db_query($sql);
      if ($rs) {
        if (pg_num_rows($rs) > 0) {
            $dado = db_utils::fieldsMemory($rs, 0);
            $oRetorno->rh254_sequencial = $dado->rh254_sequencial;
            $oRetorno->rh254_matricula = $dado->rh254_matricula;
            $oRetorno->rh254_instit = $dado->rh254_instit;
            $oRetorno->rh254_tipojornada = $dado->rh254_tipojornada;
            $oRetorno->rh254_tempoparcial = $dado->rh254_tempoparcial;
            $oRetorno->rh254_horarionoturno = $dado->rh254_horarionoturno;
            $oRetorno->rh254_descricaojornada = $dado->rh254_descricaojornada;
        } 
      } else {
        throw new DBException("Erro ao consultar registro.");
      } 

      break;
    case 'excluir':
      db_inicio_transacao();
      if (empty($matricula)) {
        throw new Exception('Matrícula do servidor não informada.');
      }

      $dao = new \cl_rhservidorcontratojornada();
      $where = "rh254_matricula = {$matricula} and rh254_instit = {$codigoInstituicao} ";
      $sql = $dao->sql_query(null, 'rhservidorcontratojornada.rh254_sequencial', null, $where);

      $rs = db_query($sql);
      if ($rs) {
        if (pg_num_rows($rs) > 0) {
            $dado = db_utils::fieldsMemory($rs, 0);
            $sequencial = $dado->rh254_sequencial;
            $dao->excluir(null, "rh254_sequencial = {$sequencial}");
            if ($dao->erro_status == "0") {
              throw new Exception("ERRO ao excluir escala do servidor. ERRO: {$oDaoEscalaServidor->erro_msg}");
            }
            $oRetorno->message  = urlencode('Registro excluído com sucesso.');
        }
      } else {
        throw new DBException("Erro ao consultar registro.");
      }

      db_fim_transacao();

      break;
  }
} catch (Exception $eException) {

  $oRetorno->erro    = true;
  $oRetorno->message = urlencode($eException->getMessage());
}

echo $oJson->encode($oRetorno);

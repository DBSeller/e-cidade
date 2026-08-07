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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification("classes/db_workflow_classe.php");
require_once modification("classes/db_tipoproc_classe.php");
require_once modification("classes/db_tipoprocgrupo_classe.php");
require_once(modification("classes/db_workflowativ_classe.php"));
require_once modification("classes/db_workflowmodulo_classe.php");
require_once modification("classes/db_workflowtipoproc_classe.php");

$clworkflow         = new cl_workflow();
$cltipoproc         = new cl_tipoproc();
$cldb_sysmodulo     = new cl_db_sysmodulo();
$clworkflowativ     = new cl_workflowativ();
$cltipoprocgrupo    = new cl_tipoprocgrupo();
$clworkflowmodulo   = new cl_workflowmodulo();
$clworkflowtipoproc = new cl_workflowtipoproc();

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->status = 1;
$retorno->mensagem = '';

try{

    db_inicio_transacao();

    switch ($parametros->exec){

        case "salvar":

          $cltipoproc->p51_descr         = $parametros->p51_descr;
          $cltipoproc->p51_tipoprocgrupo = $parametros->p51_tipoprocgrupo;
          $clworkflow->db112_descricao   = $parametros->db112_descricao;

          if(empty($parametros->db112_sequencial)){
            $cltipoproc->p51_dtlimite      = '';
            $cltipoproc->p51_identificado  = 'false';
            $cltipoproc->p51_instit        = db_getsession('DB_instit');
            $cltipoproc->incluir(null);

            if ($cltipoproc->erro_status == 0) {
              throw new Exception($cltipoproc->erro_msg);
            }

            $clworkflow->incluir(null);

            if ($clworkflow->erro_status == 0) {
              throw new Exception($clworkflow->erro_msg);
            }

            $clworkflowtipoproc->db116_tipoproc = $cltipoproc->p51_codigo;
            $clworkflowtipoproc->db116_workflow = $clworkflow->db112_sequencial;
            $clworkflowtipoproc->incluir(null);

            if ($clworkflowtipoproc->erro_status == 0) {
              throw new Exception($clworkflowtipoproc->erro_msg);
            }

            $clworkflowmodulo->db173_modulo = $parametros->db173_modulo;
            $clworkflowmodulo->db173_workflow = $clworkflow->db112_sequencial;
            $clworkflowmodulo->incluir();

            if ($clworkflowmodulo->erro_status == 0) {
              throw new Exception($clworkflowmodulo->erro_msg);
            }

          } else {
            $rs = db_query($clworkflow->sql_query_modulo($parametros->db112_sequencial, "db112_sequencial, db112_descricao, p51_tipoprocgrupo, p51_codigo, p51_descr, db173_modulo"));
            $workflow = array();
            $aux = pg_fetch_array($rs); 
                    
            $cltipoproc->p51_codigo = $aux['p51_codigo'];
            $cltipoproc->alterar($cltipoproc->p51_codigo);
            
            if ($cltipoproc->erro_status == 0) { 
              throw new Exception($cltipoproc->erro_msg);
            }

            $clworkflowmodulo->db173_modulo = $aux['db173_modulo'];
            $clworkflowmodulo->db173_workflow = $aux['db112_sequencial'];
            $clworkflowmodulo->excluir($clworkflowmodulo->db173_workflow, $clworkflowmodulo->db173_modulo);
                
            if ($clworkflowmodulo->erro_status == 0) {
              throw new Exception($clworkflowmodulo->erro_msg);
            }

            $clworkflowmodulo->db173_modulo = $parametros->db173_modulo;
            $clworkflowmodulo->db173_workflow = $aux['db112_sequencial'];
            $clworkflowmodulo->incluir();

            if ($clworkflowmodulo->erro_status == 0) {
              throw new Exception($clworkflowmodulo->erro_msg);
            }
          
            $clworkflow->db112_sequencial = $aux['db112_sequencial'];
            $clworkflow->alterar($clworkflow->db112_sequencial);
            
            if ($clworkflow->erro_status == 0) {
              throw new Exception($cltipoproc->erro_msg);
            }
          }

          $retorno->workflow = array();
          $retorno->workflow['db112_sequencial'] = $clworkflow->db112_sequencial;
          $retorno->workflow['db112_descricao'] = $clworkflow->db112_descricao;
          $retorno->workflow['p51_tipoprocgrupo'] = $cltipoproc->p51_tipoprocgrupo;
          $retorno->workflow['p51_descr'] = $cltipoproc->p51_descr;
          $retorno->workflow['db173_modulo'] = $clworkflowmodulo->db173_modulo;

          $retorno->mensagem = "Workflow salvo com sucesso.";
          break;

        case 'excluir':

          if(empty($parametros->db112_sequencial)){
            throw new BusinessException("Campo workflow obrigatório");
          }

          $sWhere            = "workflowativ.db114_workflow = {$parametros->db112_sequencial}"; 
          $sSqlWorkflowAtiv  = $clworkflowativ->sql_query_file(null, "*", null, $sWhere);
          $rsSqlWorkflowAtiv = $clworkflowativ->sql_record($sSqlWorkflowAtiv);

          if ($clworkflowativ->numrows > 0) {
            throw new Exception("Work Flow possui vinculo com atividades!");
          }

          $rs = db_query($clworkflow->sql_query_modulo($parametros->db112_sequencial, "db112_sequencial, p51_tipoprocgrupo, db173_modulo, p51_codigo, db116_sequencial"));
          $workflow = array();
          $aux = pg_fetch_array($rs);
                    
          $clworkflowtipoproc->db116_sequencial = $aux["db116_sequencial"];
          $clworkflowtipoproc->excluir($clworkflowtipoproc->db116_sequencial);
              
          if ($clworkflowtipoproc->erro_status == 0) {
            throw new Exception($clworkflowtipoproc->erro_msg);
          }
          
          $cltipoproc->p51_codigo = $aux['p51_codigo'];
          $cltipoproc->excluir($cltipoproc->p51_codigo);
              
          if ($cltipoproc->erro_status == 0) {
            throw new Exception($cltipoproc->erro_msg);
          }

          $clworkflowmodulo->db173_modulo = $aux['db173_modulo'];
          $clworkflowmodulo->db173_workflow = $aux['db112_sequencial'];
          $clworkflowmodulo->excluir($clworkflowmodulo->db173_workflow, $clworkflowmodulo->db173_modulo);
              
          if ($clworkflowmodulo->erro_status == 0) {
            throw new Exception($clworkflowmodulo->erro_msg);
          }
          
          $clworkflow->db112_sequencial = $aux['db112_sequencial'];
          $clworkflow->excluir($clworkflow->db112_sequencial);
          
          if ($clworkflow->erro_status == 0) {    
            throw new Exception($clworkflow->erro_msg);
          }

          $retorno->mensagem = "Workflow excluido com sucesso.";

          break;

        case 'buscarDadosWorkflow':

          if(empty($parametros->db112_sequencial)){
            throw new BusinessException("Campo workflow obrigatório");
          }

          $rs = db_query($clworkflow->sql_query_modulo($parametros->db112_sequencial, "db112_sequencial, db112_descricao, p51_tipoprocgrupo, p51_descr, db173_modulo"));
          $workflow = array();
          $aux = pg_fetch_array($rs);

          $workflow['db112_sequencial'] = $aux['db112_sequencial'];
          $workflow['db112_descricao'] = $aux['db112_descricao'];
          $workflow['p51_tipoprocgrupo'] = $aux['p51_tipoprocgrupo'];
          $workflow['p51_descr'] = $aux['p51_descr'];
          $workflow['db173_modulo'] = $aux['db173_modulo'];

          $retorno->workflow = $workflow;

          break;

        case 'getModulos':

          $sql = $cldb_sysmodulo->sql_query(null, 'codmod, nomemod');
          $rs = db_query($sql);
          $modulos = array();

          while ($modulo = pg_fetch_array($rs)) {
              $aux = array();
              $aux['codmod'] = $modulo['codmod'];
              $aux['nomemod'] = strtoupper(trim($modulo['nomemod']));
              $modulos[] = $aux;
          }

          $retorno->modulos = $modulos;

          break;

        case 'getTipoProcGrupos':
          $sql = $cltipoprocgrupo->sql_query(null, 'p40_sequencial, p40_descricao');
          $rs = db_query($sql);
          $arrTipoGrupoProc = array();

          while ($tipoGrupoProc = pg_fetch_array($rs)) {
              $aux = array();
              $aux['p40_sequencial'] = $tipoGrupoProc['p40_sequencial'];
              $aux['p40_descricao'] = strtoupper(trim($tipoGrupoProc['p40_descricao']));
              $arrTipoGrupoProc[] = $aux;
          }

          $retorno->arrTipoGrupoProc = $arrTipoGrupoProc;

          break;

        default:
            throw new Exception("Opção inválida!");

    }

    db_fim_transacao(false);

} catch (Exception $erro){

    db_fim_transacao(true);
    $retorno->status = 2;
    $retorno->mensagem = $erro->getMessage();
}

$retorno->erro = $retorno->status == 2;
echo JSON::create()->stringify($retorno);
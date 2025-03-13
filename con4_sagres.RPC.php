<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

 use ECidade\Financeiro\Contabilidade\Sagres\SagresFiscal;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');
require_once(modification("libs/JSON.php"));

$oParam     = JSON::requestParameters();
$oJson      = new services_json();
$oRetorno   = new stdClass();

$oRetorno->erro  = false;
$oRetorno->message = '';

$sCamposRowsOrdenador = "c139_sequencial, c139_cgm, cgm.z01_nome as nome, c139_cgmsubstituto, cgmsubstituto.z01_nome as nomesubstituto,
to_char(c139_datainicio::date, 'DD/MM/YYYY') as datainicio, to_char(c139_datafim::date, 'DD/MM/YYYY') as datafim,
to_char(c139_datainiciosub::date, 'DD/MM/YYYY') as datainiciosub, to_char(c139_datafimsub::date, 'DD/MM/YYYY') as datafimsub, c139_ativo,
c139_principal, c139_substituto, c139_tipoatojuridico, c139_titulo, c139_instit";

$sCamposRowsResponsavel = "c140_sequencial, c140_cgm, cgm.z01_nome as nome, c140_cgmsubstituto, cgmsubstituto.z01_nome as nomesubstituto,
to_char(c140_datainicio::date, 'DD/MM/YYYY') as datainicio, to_char(c140_datafim::date, 'DD/MM/YYYY') as datafim,
to_char(c140_datainiciosub::date, 'DD/MM/YYYY') as datainiciosub, to_char(c140_datafimsub::date, 'DD/MM/YYYY') as datafimsub, c140_ativo,
c140_principal, c140_substituto, c140_tipoatojuridico, c140_orgao, c140_unidade";

switch($oParam->exec) {
    case 'gerarSagres':
        try {
            if (empty($oParam->periodo)) {
                throw new Exception("Selecione o Período.");
            }
    
            $ano = date('Y');
            $instituicoes = InstituicaoRepository::getInstituicoes();
            $instituicao = InstituicaoRepository::getInstituicaoSessao();
    
            if ($instituicao->getTipo() == 2) {
                $codigoInstituicoes = [$instituicao->getCodigo()];
            } else {
                $codigoInstituicoes = array_filter($instituicoes, function (Instituicao $instituicao) {
                    if ($instituicao->getTipo() != 2) {
                        return $instituicao->getCodigo();
                    }
                });
                $codigoInstituicoes = array_keys($codigoInstituicoes);
            }
    
            $departamento = DBDepartamentoRepository::getDBDepartamentoByCodigo(db_getsession('DB_coddepto'));
    
            if (empty($relatorios)) {
                throw new Exception("Nenhum relatório selecionado.");
            }
            
            $oParam->formatos['txt'] = isset($oParam->txt);
            $oParam->formatos['xml'] = isset($oParam->xml);
            $oParam->formatos['csv'] = isset($oParam->csv);

            $oParam->folder = '';
            $oParam->dataSQL = new stdClass;
            switch ($oParam->periodo) {
                case 'diario':
                    $oParam->dataSQL->dia = $oParam->data_dia;
                    $oParam->dataSQL->mes = $oParam->data_mes;
                    $oParam->dataSQL->ano = $oParam->data_ano;
                    $oParam->folder = implode('/', array_reverse(explode('/', $oParam->data)));
                    break;
                case 'mensal':
                    $oParam->dataSQL->mes = $oParam->mes;
                    $oParam->dataSQL->ano = $oParam->ano;
                    $oParam->folder = $oParam->ano.'/'.$oParam->mes;
                    break;
                case 'janeiro':
                    $oParam->dataSQL->ano = $oParam->ano;
                    $oParam->folder = $oParam->ano.'/01';
                    break;
                case 'anual':
                    $oParam->dataSQL->ano = $oParam->ano;
                    $oParam->folder = $oParam->ano;
                    break;
            }
            
            if(empty($oParam->txt) && empty($oParam->xml) && empty($oParam->csv)) {
                throw new Exception("Nenhum formato selecionado.");
            }
            
            $sagresFiscal = new SagresFiscal(
                $oParam,
                $departamento,
                $codigoInstituicoes,
                $ano,
                $oParam->codigoTCE
            );
            $sagresFiscal->processarArquivos($relatorios, $oParam);
            $arquivoZip = $sagresFiscal->comprimir($oParam);
    
            $oRetorno->zip = $arquivoZip;
            $oRetorno->arquivos = $sagresFiscal->getArquivosEmitidos();
            $oRetorno->mensagem = "Arquivo gerado com sucesso!";
        } catch (Exception $e) {
            $oRetorno->erro   = true;
            $oRetorno->message = utf8_encode($e->getMessage());
        }
        
    break;
    
    case 'salvarResponsavel':
        $clResponsavelUnidadeOrcamentaria  = new cl_sagresresponsavelunidadeorcamentaria;
            
        try {
            db_inicio_transacao();
            $aWhereResp[] = "c140_cgm = '$oParam->c140_cgm'";
            $aWhereResp[] = "c140_ativo = 't'";
            $aWhereResp[] = "c140_orgao = $oParam->c140_orgao";
            $aWhereResp[] = "c140_unidade = $oParam->c140_unidade";
            $aWhereResp[] = "c140_instit = " . db_getsession("DB_instit");

            $sSql = $clResponsavelUnidadeOrcamentaria->sql_query_file(null, "count(*) > 0 as existe ", null, implode(" and " , $aWhereResp));
            $rs = $clResponsavelUnidadeOrcamentaria->sql_record($sSql);
            if(db_utils::fieldsMemory($rs, 0)->existe == 't') {
                throw new Exception("Responsável já cadastrado.");
            }
            
            $aWherePrincipal[] = "'t' = '$oParam->c140_principal'";
            $aWherePrincipal[] = "c140_principal = 't'";
            $aWherePrincipal[] = "c140_ativo = 't'";
            $aWherePrincipal[] = "c140_orgao = $oParam->c140_orgao";
            $aWherePrincipal[] = "c140_unidade = $oParam->c140_unidade";
            $aWherePrincipal[] = "c140_instit = ".db_getsession('DB_instit');
            
            $sSql = $clResponsavelUnidadeOrcamentaria->sql_query_file(null, "count(*) > 0 as existe ", null, implode(" and " , $aWherePrincipal));
            $rs = $clResponsavelUnidadeOrcamentaria->sql_record($sSql);
            if(db_utils::fieldsMemory($rs, 0)->existe == 't') {
                throw new Exception("Responsável principal já cadastrado.");
            }
            
            if(db_utils::fieldsMemory($rs, 0)->existe == 't') {
                throw new Exception("Responsável principal já cadastrado.");
            } else {
                $sSql = $clResponsavelUnidadeOrcamentaria->sql_query_file(null, "count(*) > 0 as principal ", null,
                "c140_ativo = 't' and c140_principal = 't' and c140_instit = ".db_getsession('DB_instit'));
                $rs = $clResponsavelUnidadeOrcamentaria->sql_record($sSql);
                if(db_utils::fieldsMemory($rs, 0)->principal == 'f') {
                    $oParam->c140_principal = 't';
                }
            }

            $clResponsavelUnidadeOrcamentaria->c140_sequencial = null;
            $clResponsavelUnidadeOrcamentaria->c140_orgao = $oParam->c140_orgao;
            $clResponsavelUnidadeOrcamentaria->c140_unidade = $oParam->c140_unidade;
            $clResponsavelUnidadeOrcamentaria->c140_cgm = $oParam->c140_cgm;
            $clResponsavelUnidadeOrcamentaria->c140_cgmsubstituto = $oParam->c140_substituto ? $oParam->c140_cgmsubstituto : null;
            $clResponsavelUnidadeOrcamentaria->c140_principal = $oParam->c140_principal;
            $clResponsavelUnidadeOrcamentaria->c140_substituto = $oParam->c140_substituto;
            $clResponsavelUnidadeOrcamentaria->c140_datainicio = implode('-',array_reverse(explode('/',$oParam->c140_datainicio)));
            $clResponsavelUnidadeOrcamentaria->c140_datafim = implode('-',array_reverse(explode('/',$oParam->c140_datafim)));
            $clResponsavelUnidadeOrcamentaria->c140_tipoatojuridico = $oParam->c140_tipoatojuridico;
            $clResponsavelUnidadeOrcamentaria->c140_idusuario = null;
            $clResponsavelUnidadeOrcamentaria->c140_ativo = 't';
            $clResponsavelUnidadeOrcamentaria->c140_anousu = db_getsession("DB_anousu");
            $clResponsavelUnidadeOrcamentaria->c140_instit = db_getsession("DB_instit");
            $clResponsavelUnidadeOrcamentaria->incluir(null);
            
            if ($clResponsavelUnidadeOrcamentaria->erro_status == 0) {
                throw new Exception($clResponsavelUnidadeOrcamentaria->erro_msg);
            }

            $oRetorno->message = utf8_encode("Inclusão bem sucedida!");
            db_fim_transacao(false);

        } catch (Exception $e) {
            $oRetorno->erro   = true;
            $oRetorno->message = utf8_encode($e->getMessage());
        }
            
    break;

    case 'getResponsavel':
        try {
            db_inicio_transacao();
            $clResponsavelUnidadeOrcamentaria  = new cl_sagresresponsavelunidadeorcamentaria;
            $aWhere[] = "1 = 1";
            $aWhere[] = "c140_instit = " . db_getsession("DB_instit");


            if(!empty($oParam->ativo)) {
                $aWhere[] = "c140_ativo = '$oParam->ativo'";
            }

            $sSql = $clResponsavelUnidadeOrcamentaria->sql_query(null, $sCamposRowsResponsavel, "c140_ativo desc", implode(" and " , $aWhere));
            $rsRows = $clResponsavelUnidadeOrcamentaria->sql_record($sSql);
            $oRetorno->rows = array();

            if($rsRows) {   
                $numrows = pg_num_rows($rsRows);
                for($i = 0; $i < $numrows; $i++) {
                    $oDados = db_utils::fieldsMemory($rsRows, $i);

                    $obj = new StdClass;
                    $obj->sequencial = $oDados->c140_sequencial;
                    $obj->cgm = $oDados->c140_cgm;
                    $obj->nome = utf8_encode($oDados->nome);
                    $obj->principal = $oDados->c140_principal;
                    $obj->substituto = $oDados->c140_substituto;
                    $obj->cgmsub = $oDados->c140_cgmsubstituto;
                    $obj->nomesub = utf8_encode($oDados->nomesubstituto);
                    $obj->ativo = $oDados->c140_ativo;
                    $obj->periodo = $oDados->datainicio;
                    if(!empty($oDados->datafim)) {
                        $obj->periodo .= ' - ' .$oDados->datafim; 
                    }
                    $obj->periodosub = $oDados->datainiciosub;
                    if(!empty($oDados->datafimsub)) {
                        $obj->periodosub .= ' - ' .$oDados->datafimsub; 
                    }
                    $obj->tipoato = $oDados->c140_tipoatojuridico;
                    $obj->orgao = $oDados->c140_orgao;
                    $obj->unidade = $oDados->c140_unidade;

                    $oRetorno->rows[] = $obj;
                }      
            }
             
            db_fim_transacao(false);

        } catch (Exception $e) {
            $oRetorno->erro   = true;
            $oRetorno->message = utf8_encode($e->getMessage());
        }
    break;

    case 'inativarResponsavel':
        try {
            db_inicio_transacao();
            $clResponsavelUnidadeOrcamentaria  = new cl_sagresresponsavelunidadeorcamentaria;

            $sWhere = "c140_sequencial = $oParam->id and c140_ativo = 't'";
            $sSql = $clResponsavelUnidadeOrcamentaria->sql_query_file(null, "*", null, $sWhere);
            $rs = db_query($sSql);
            if (!$rs) {
                throw new Exception("Erro ao encontrar responsável.");
            }
            if (pg_num_rows($rs) > 0) {
                $oDados = db_utils::fieldsMemory($rs, 0);
                $clResponsavelUnidadeOrcamentaria->c140_sequencial = $oDados->c140_sequencial;
            
                $_POST["c140_ativo"] = 'f';
            
                $clResponsavelUnidadeOrcamentaria->c140_orgao = $oDados->c140_orgao; 
                $clResponsavelUnidadeOrcamentaria->c140_unidade = $oDados->c140_unidade; 
                $clResponsavelUnidadeOrcamentaria->c140_cgm = $oDados->c140_cgm; 
                $clResponsavelUnidadeOrcamentaria->c140_cgmsubstituto = $oDados->c140_cgmsubstituto; 
                $clResponsavelUnidadeOrcamentaria->c140_principal = $oDados->c140_principal; 
                $clResponsavelUnidadeOrcamentaria->c140_substituto = $oDados->c140_substituto; 
                $clResponsavelUnidadeOrcamentaria->c140_datainicio = $oDados->c140_datainicio; 
                $clResponsavelUnidadeOrcamentaria->c140_datafim = $oDados->c140_datafim; 
                $clResponsavelUnidadeOrcamentaria->c140_tipoatojuridico = $oDados->c140_tipoatojuridico; 
                $clResponsavelUnidadeOrcamentaria->c140_idusuario = $oDados->c140_idusuario; 
                $clResponsavelUnidadeOrcamentaria->c140_anousu = $oDados->c140_anousu; 
                $clResponsavelUnidadeOrcamentaria->c140_ativo = "f";
                $clResponsavelUnidadeOrcamentaria->c140_datainatividade = date("Y-m-d");
                $clResponsavelUnidadeOrcamentaria->alterar($clResponsavelUnidadeOrcamentaria->c140_sequencial);
             
                if ($clResponsavelUnidadeOrcamentaria->erro_status == 0) {
                    throw new DBException("Erro ao inativar responsável.");
                }
            }
            db_fim_transacao(false);
        } catch (Exception $e) {
            $oRetorno->erro   = true;
            $oRetorno->message = utf8_encode($e->getMessage());
        }
    break;

    case 'salvarOrdenador':
        $clOrdenadorDespesa  = new cl_sagresordenadordespesa;
        $aWhereOrdenador[] = "c139_cgm = '$oParam->c139_cgm'";
        $aWhereOrdenador[] = "c139_ativo = 't'";
        $aWhereOrdenador[] = "c139_instit = " . db_getsession("DB_instit");

        try {
            db_inicio_transacao();
            $sSql = $clOrdenadorDespesa->sql_query_file(null, "count(*) > 0 as existe ", null, implode(" and " , $aWhereOrdenador));
            $rs = $clOrdenadorDespesa->sql_record($sSql);
            if(db_utils::fieldsMemory($rs, 0)->existe == 't') {
                throw new Exception("Ordenador já cadastrado.");
            }

            $aWherePrincipal[] = "'t' = '$oParam->c139_principal'";
            $aWherePrincipal[] = "c139_principal = 't'";
            $aWherePrincipal[] = "c139_ativo = 't'";
            $aWherePrincipal[] = "c139_instit = ".db_getsession('DB_instit');

            $sSql = $clOrdenadorDespesa->sql_query_file(null, "count(*) > 0 as existe ", null, implode(" and " , $aWherePrincipal));
            $rs = $clOrdenadorDespesa->sql_record($sSql);
            if(db_utils::fieldsMemory($rs, 0)->existe == 't') {
                throw new Exception("Ordenador principal já cadastrado.");
            } else {
                $sSql = $clOrdenadorDespesa->sql_query_file(null, "count(*) > 0 as principal ", null,
                "c139_ativo = 't' and c139_principal = 't' and c139_instit = ".db_getsession('DB_instit'));
                $rs = $clOrdenadorDespesa->sql_record($sSql);
                if(db_utils::fieldsMemory($rs, 0)->principal == 'f') {
                    $oParam->c139_principal = 't';
                }
            }
            // echo "<pre>"; print_r($oParam); exit;
            $clOrdenadorDespesa->c139_sequencial = null;
            $clOrdenadorDespesa->c139_instit = $oParam->c139_instit;
            $clOrdenadorDespesa->c139_cgm = $oParam->c139_cgm;
            $clOrdenadorDespesa->c139_cgmsubstituto = $oParam->c139_cgmsubstituto;
            $clOrdenadorDespesa->c139_principal = $oParam->c139_principal;
            $clOrdenadorDespesa->c139_substituto = $oParam->c139_substituto;
            $clOrdenadorDespesa->c139_datainicio = implode('-',array_reverse(explode('/',$oParam->c139_datainicio)));
            $clOrdenadorDespesa->c139_datafim = implode('-',array_reverse(explode('/',$oParam->c139_datafim)));
            $clOrdenadorDespesa->c139_tipoatojuridico = $oParam->c139_tipoatojuridico;
            $clOrdenadorDespesa->c139_titulo = empty($oParam->c139_titulo) ? '' : $oParam->c139_titulo;
            $clOrdenadorDespesa->c139_ativo = 't';
            $clOrdenadorDespesa->c139_datainatividade = null;
            $clOrdenadorDespesa->c139_idusuario = null;
            $clOrdenadorDespesa->incluir(null);
            if ($clOrdenadorDespesa->erro_status == 0) {
                throw new Exception($clOrdenadorDespesa->erro_msg);
            }
            
            $oRetorno->message = utf8_encode("Inclusão bem sucedida!");
            db_fim_transacao(false);

        } catch (Exception $e) {
            $oRetorno->erro   = true;
            $oRetorno->message = utf8_encode($e->getMessage());
        }
            
    break;

    case 'getOrdenador':
        try {
            db_inicio_transacao();
            $clOrdenadorDespesa  = new cl_sagresordenadordespesa;
            $aWhere[] = "c139_instit = " . db_getsession("DB_instit");

            if(!empty($oParam->ativo)) {
                $aWhere[] = "c139_ativo = '$oParam->ativo'";
            }

            $sSql = $clOrdenadorDespesa->sql_query(null, $sCamposRowsOrdenador, "c139_ativo desc", implode(" and " , $aWhere));
            $rsRows = $clOrdenadorDespesa->sql_record($sSql);
            $oRetorno->rows = array();

            if($rsRows) {
                $numrows = pg_num_rows($rsRows);
                for($i = 0; $i < $numrows; $i++) {
                    $oDados = db_utils::fieldsMemory($rsRows, $i);

                    $obj = new StdClass;
                    $obj->sequencial = $oDados->c139_sequencial;
                    $obj->instit = $oDados->c139_instit;
                    $obj->cgm = $oDados->c139_cgm;
                    $obj->nome = utf8_encode($oDados->nome);
                    $obj->cgmsub = $oDados->c139_cgmsubstituto;
                    $obj->nomesub = utf8_encode($oDados->nomesubstituto);
                    $obj->principal = $oDados->c139_principal;
                    $obj->substituto = $oDados->c139_substituto;
                    $obj->periodo = $oDados->datainicio;
                    if(!empty($oDados->datafim)) {
                         $obj->periodo .= ' - ' .$oDados->datafim;
                    }
                    $obj->periodosub = $oDados->datainiciosub;
                    if(!empty($oDados->datafimsub)) {
                         $obj->periodosub .= ' - ' .$oDados->datafimsub;
                    }
                    $obj->ativo = $oDados->c139_ativo;
                    $obj->tipoato = $oDados->c139_tipoatojuridico;
                    $obj->titulo = $oDados->c139_titulo;

                    $oRetorno->rows[] = $obj;
                }
            }

            db_fim_transacao(false);

        } catch (Exception $e) {
            $oRetorno->erro   = true;
            $oRetorno->message = utf8_encode($e->getMessage());
        }
    break;

    case 'inativarOrdenador':
        try {
            db_inicio_transacao();
            $clOrdenadorDespesa  = new cl_sagresordenadordespesa;

            $sWhere = "c139_sequencial = $oParam->id and c139_ativo = 't'";
            $sSql = $clOrdenadorDespesa->sql_query_file(null, "*", null, $sWhere);
            $rs = db_query($sSql);
            if (!$rs) {
                throw new Exception("Erro ao encontrar  ordenador.");
            }
            if (pg_num_rows($rs) > 0) {
                $oDados = db_utils::fieldsMemory($rs, 0);
                $clOrdenadorDespesa->c139_sequencial = $oDados->c139_sequencial;
                $clOrdenadorDespesa->c139_instit = $oDados->c139_instit;
                $clOrdenadorDespesa->c139_cgm = $oDados->c139_cgm;
                $clOrdenadorDespesa->c139_cgmsubstituto = $oDados->c139_cgmsubstituto;
                $clOrdenadorDespesa->c139_principal = $oDados->c139_principal;
                $clOrdenadorDespesa->c139_substituto = $oDados->c139_substituto;
                $clOrdenadorDespesa->c139_datainicio = $oDados->c139_datainicio;
                $clOrdenadorDespesa->c139_datafim = $oDados->c139_datafim;
                $clOrdenadorDespesa->c139_tipoatojuridico = $oDados->c139_tipoatojuridico;
                $clOrdenadorDespesa->c139_titulo = $oDados->c139_titulo;
                $_POST["c139_ativo"] = 'f';
                $clOrdenadorDespesa->c139_ativo = "f";
                $clOrdenadorDespesa->c139_datainatividade = date("Y-m-d");
                $clOrdenadorDespesa->c139_idusuario = db_getsession("DB_id_usuario");
                
                $clOrdenadorDespesa->alterar($clOrdenadorDespesa->c139_sequencial);
                if ($clOrdenadorDespesa->erro_status == 0) {
                    throw new DBException("Erro ao inativar ordenador.");
                }
            }
            db_fim_transacao(false);
        } catch (Exception $e) {
            $oRetorno->erro   = true;
            $oRetorno->message = utf8_encode($e->getMessage());
        }
    break;
}

function convertDate($data, $format) {
    switch ($format) {
      case 'd/m/Y':
        $date1 = DateTime::createFromFormat('Y-m-d', $data);
        return $date1->format('d/m/Y');
      break;
      
      case 'Y-m-d':
        $ano= substr($data, 6);
        $mes= substr($data, 3,-5);
        $dia= substr($data, 0,-8);
        return $ano."-".$mes."-".$dia;
      break;
      
      case 'dmY':
        $timestamp = strtotime($data);
        return date("dmY", $timestamp);
      break;

      case 'Y':
        $timestamp = strtotime($data);
        return date("Y", $timestamp);
      break;
    }
  }

echo $oJson->encode($oRetorno);
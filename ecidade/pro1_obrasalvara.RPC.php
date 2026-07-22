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
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$oJson = new services_json();
$oRetorno = new stdClass();
$cl_obrasrenovacaoalvara = new cl_obrasrenovacaoalvara;

$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno->iStatus = 1;
$oRetorno->sMessage = '';

$lErro = false;

switch ($oParam->sExec) {
    case 'getConstrucoes':
        try {
            $oRetorno->aConstrucao = array();

            $oDAOObras = db_utils::getDao('obras');
            $sSqlObras = $oDAOObras->sql_query_obras_construcoes($oParam->iCodigoObra);
            $rsObras = $oDAOObras->sql_record($sSqlObras);

            if ($oDAOObras->numrows == 0) {
                throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nenhuma_construcao_encontrada'));
            }

            $oRetorno->oConstrucao = db_utils::fieldsMemory($rsObras, 0, true, false, true);
        } catch (Exception $oException) {
            $oRetorno->iStatus = 2;
            $oRetorno->sMessage = $oException->getMessage();
        }

        break;

    case 'getObra':
        try {
            $oDAOObrasAlvara = db_utils::getDao('obrasalvara');
            $sSqlObrasAlvara = $oDAOObrasAlvara->sql_query_obrasalvara($oParam->iCodigoObra);
            $rsObrasAlvara = $oDAOObrasAlvara->sql_record($sSqlObrasAlvara);

            if ($oDAOObrasAlvara->numrows == 0) {
                throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nenhum_registro_encontrado'));
            }

            $oRetorno->oObra = db_utils::fieldsMemory($rsObrasAlvara, 0, true, false, true);
        } catch (Exception $oException) {
            $oRetorno->iStatus = 2;
            $oRetorno->sMessage = $oException->getMessage();
        }

        break;

    case 'salvaObraAlvara' :
        try {
            $oDataInicial = new DBDate($oParam->dDtAlvara);
            $oDataFinal = new DBDate($oParam->dDtValidade);

            if (strtotime($oDataInicial->convertTo(DBDate::DATA_EN)) > strtotime($oDataFinal->convertTo(DBDate::DATA_EN))) {
                throw new Exception(_M('tributario.projetos.pro1_obrasalvara.validade_inferior'));
            }

            db_inicio_transacao();

            $oDAOObrasAlvara = db_utils::getDao('obrasalvara');
            $oDAOObrasAlvaraHistorico = db_utils::getDao('obrasalvarahistorico');
            $oDAOObrasAlvaraProtProcesso = db_utils::getDao('obrasalvaraprotprocesso');

            if ($oParam->iCodigoObra != '') {
                $rsObrasAlvara = $oDAOObrasAlvara->sql_record($oDAOObrasAlvara->sql_query_file($oParam->iCodigoObra));
                $oObrasAlvara = db_utils::fieldsMemory($rsObrasAlvara, 0);
            }

            $oDAOObrasAlvara->ob04_codobra = $oParam->iCodigoObra;
            $oDAOObrasAlvara->ob04_alvara = $oParam->iCodigoAlvara;
            $oDAOObrasAlvara->ob04_data = $oParam->dDtAlvara;
            $oDAOObrasAlvara->ob04_processo = db_stdClass::normalizeStringJson($oParam->sProcesso);
            $oDAOObrasAlvara->ob04_titularprocesso = db_stdClass::normalizeStringJson($oParam->sTitularProcesso);
            $oDAOObrasAlvara->ob04_dtprocesso = $oParam->dDtProcesso;
            $oDAOObrasAlvara->ob04_obsprocesso = db_stdClass::normalizeStringJson($oParam->sObservacao);
            $oDAOObrasAlvara->ob04_dtvalidade  = $oParam->dDtValidade;
            $oDAOObrasAlvara->ob04_classe      = $oParam->sClasse;
            $oDAOObrasAlvara->ob04_ativo       = boolval(isset($oObrasAlvara->ob04_ativo)?$oObrasAlvara->ob04_ativo:true);
            $oRetorno->ativo                   = boolval(isset($oObrasAlvara->ob04_ativo)?$oObrasAlvara->ob04_ativo:true);
            $oDAOObrasAlvara->ob04_datacancelamentoreativacao = date('Y-m-d');

            /**
             * Para funcionar o alterar
             */

            $GLOBALS["HTTP_POST_VARS"]["p58_codproc"] = '';
            $GLOBALS["HTTP_POST_VARS"]["ob04_processo"] = '';
            $GLOBALS["HTTP_POST_VARS"]["ob04_titularprocesso"] = '';
            $GLOBALS["HTTP_POST_VARS"]["ob04_dtprocesso_dia"] = '';
            $GLOBALS["HTTP_POST_VARS"]["ob04_obsprocesso"] = '';
            $GLOBALS["HTTP_POST_VARS"]["ob04_dtvalidade_dia"] = '';

            if ($oDAOObrasAlvara->numrows > 0) {
                $sSqlHistorico = $oDAOObrasAlvaraHistorico->sql_query_file(null, "*", " ob35_datafinal desc ",
                  " ob35_codobra = {$oParam->iCodigoObra} ");
                $rsHistorico = $oDAOObrasAlvaraHistorico->sql_record($sSqlHistorico);

                if ($oDAOObrasAlvaraHistorico->numrows > 0) {
                    $oHistorico = db_utils::fieldsMemory($rsHistorico, 0);

                    if (strtotime($oDataInicial->convertTo(DBDate::DATA_EN)) < strtotime($oHistorico->ob35_datainicial)) {
                        throw new Exception(_M('tributario.projetos.pro1_obrasalvara.validacao_data_alvara'));
                    }
                }

                if ($oParam->lProcessoSistema) {
                    $oDAOObrasAlvara->ob04_processo = null;
                    $oDAOObrasAlvara->ob04_titularprocesso = null;
                    $oDAOObrasAlvara->ob04_dtprocesso = null;
                }

                $oDAOObrasAlvara->ob04_codobra = $oParam->iCodigoObra;
                $oDAOObrasAlvara->alterar($oParam->iCodigoObra);

                if ($oDAOObrasAlvara->erro_status == '0') {
                    $lErro = true;
                    $oParms = new stdClass();
                    $oParms->msgErro = $oDAOObrasAlvara->erro_msg;

                    throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_alterar_alvara',
                      $oParms));
                }

                $oDAOObrasAlvaraProtProcesso->excluir(null, "ob26_obrasalvara = {$oParam->iCodigoObra}");

                if ($oDAOObrasAlvaraProtProcesso->erro_status == '0') {
                    $lErro = true;
                    $oParms = new stdClass();
                    $oParms->msgErro = $oDAOObrasAlvaraProtProcesso->erro_msg;

                    throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_excluir_processo',
                      $oParms));
                }

                if ($oParam->lProcessoSistema and !empty($oParam->iCodigoProcesso)) {
                    $oDAOObrasAlvaraProtProcesso->ob26_obrasalvara = $oParam->iCodigoObra;
                    $oDAOObrasAlvaraProtProcesso->ob26_protprocesso = $oParam->iCodigoProcesso;
                    $oDAOObrasAlvaraProtProcesso->incluir(null);

                    if ($oDAOObrasAlvaraProtProcesso->erro_status == '0') {
                        $lErro = true;
                        $oParms = new stdClass();
                        $oParms->msgErro = $oDAOObrasAlvaraProtProcesso->erro_msg;

                        throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_salvar_processo',
                          $oParms));
                    }

                }

                $oRetorno->sMessage = _M('tributario.projetos.pro1_obrasalvara.sucesso_alterar');
            } else {
                $oDAOObrasAlvara->incluirAlvara(null);

                if ($oDAOObrasAlvara->erro_status == '0') {
                    $lErro = true;
                    $oParms = new stdClass();
                    $oParms->msgErro = $oDAOObrasAlvara->erro_msg;

                    throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_salvar_alvara',
                      $oParms));
                }

                if ($oParam->lProcessoSistema and !empty($oParam->iCodigoProcesso)) {
                    $oDAOObrasAlvaraProtProcesso->ob26_obrasalvara = $oParam->iCodigoObra;
                    $oDAOObrasAlvaraProtProcesso->ob26_protprocesso = $oParam->iCodigoProcesso;
                    $oDAOObrasAlvaraProtProcesso->incluir(null);

                    if ($oDAOObrasAlvaraProtProcesso->erro_status == '0') {
                        $lErro = true;
                        $oParms = new stdClass();
                        $oParms->msgErro = $oDAOObrasAlvaraProtProcesso->erro_msg;

                        throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_salvar_processo_2',
                          $oParms));
                    }
                }

                $oRetorno->sMessage = _M('tributario.projetos.pro1_obrasalvara.sucesso_incluir');
            }

            db_fim_transacao($lErro);
        } catch (Exception $oException) {
            $oRetorno->iStatus = 2;
            $oRetorno->sMessage = $oException->getMessage();
        }

        break;

    case 'excluirObraAlvara' :
        try {
            $oDAOObrasAlvara = db_utils::getDao('obrasalvara');
            $oDAOObrasAlvaraProtProcesso = db_utils::getDao('obrasalvaraprotprocesso');
            $oDaoObrasAlvaraHistorico = db_utils::getDao('obrasalvarahistorico');            

            db_inicio_transacao();

            if ($oParam->iCodigoObra == '') {
                throw new Exception(_M('tributario.projetos.pro1_obrasalvara.informe_codigo_obra'));
            }            

            $oDAOObrasAlvaraProtProcesso->excluir(null, "ob26_obrasalvara = {$oParam->iCodigoObra}");

            if ($oDAOObrasAlvaraProtProcesso->erro_status == '0') {
                $oParms = new stdClass();
                $oParms->msgErro = $oDAOObrasAlvaraProtProcesso->erro_msg;
                throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_excluir_processo_alvara',
                  $oParms));
            }

            /**
             * Exclui historico do alvara
             */
            $oDaoObrasAlvaraHistorico->excluir(null, "ob35_codobra = {$oParam->iCodigoObra}");
            
            if ($oDaoObrasAlvaraHistorico->erro_status == "0") {
                throw new DBException(_M("tributario.projetos.pro1_obrasalvara.erro_excluir_historico	"));
            }

            /**
             * Exclui dados obrasrenovacaoalvara
             */
            $cl_obrasrenovacaoalvara->excluir(null, "ob33_codobra = {$oParam->iCodigoObra}");
            
            if ($cl_obrasrenovacaoalvara->erro_status == "0") {
                throw new DBException(_M("tributario.projetos.pro1_obrasalvara.erro_excluir_renovacao_alvara	"));
            }

            $oDAOObrasAlvara->excluir($oParam->iCodigoObra);

            if ($oDAOObrasAlvara->erro_status == '0') {
                $oParms = new stdClass();
                $oParms->msgErro = $oDAOObrasAlvara->erro_msg;
                throw new Exception(_M('tributario.projetos.pro1_obrasalvara.nao_foi_possivel_excluir_processo_alvara',
                  $oParms));
            }

            $oRetorno->sMessage = _M('tributario.projetos.pro1_obrasalvara.sucesso_exclusao');

            db_fim_transacao();
        } catch (Exception $oException) {
            $oRetorno->iStatus = 2;
            $oRetorno->sMessage = $oException->getMessage();
        }

        break;

    case 'cancelaReativa' :
        try {
            $oDAOObrasAlvara = db_utils::getDao('obrasalvara');
            $oDaoObrasHabite = db_utils::getDao('obrashabite');

            db_inicio_transacao();

            if ($oParam->iCodigoObra == '') {
                throw new Exception(_M('tributario.projetos.pro1_obrasalvara.informe_codigo_obra'));
            } else {
                $rsObrasAlvara = $oDAOObrasAlvara->sql_record($oDAOObrasAlvara->sql_query_file($oParam->iCodigoObra));
                $oObrasAlvara = db_utils::fieldsMemory($rsObrasAlvara, 0);
            }

            $rs = db_query($oDaoObrasHabite->sql_query(null, "obrashabite.ob09_codhab", null, "obras.ob01_codobra = {$oParam->iCodigoObra} and obrashabite.ob09_ativo = 't'"));

            if(pg_num_rows($rs) > 0){
                throw new Exception(_M('tributario.projetos.pro1_obrasalvara.alvara_possui_habite'));
            }
            
            $oDAOObrasAlvara->ob04_codobra = $oObrasAlvara->ob04_codobra;
            $oDAOObrasAlvara->ob04_alvara = $oObrasAlvara->ob04_alvara;
            $oDAOObrasAlvara->ob04_data = $oObrasAlvara->ob04_data;
            $oDAOObrasAlvara->ob04_processo = db_stdClass::normalizeStringJson($oObrasAlvara->ob04_processo);
            $oDAOObrasAlvara->ob04_titularprocesso = db_stdClass::normalizeStringJson($oObrasAlvara->ob04_titularprocesso);
            $oDAOObrasAlvara->ob04_dtprocesso = $oObrasAlvara->ob04_dtprocesso;
            $oDAOObrasAlvara->ob04_obsprocesso = db_stdClass::normalizeStringJson($oObrasAlvara->ob04_obsprocesso);
            $oDAOObrasAlvara->ob04_dtvalidade = $oObrasAlvara->ob04_dtvalidade;
            $oDAOObrasAlvara->ob04_classe = $oObrasAlvara->ob04_classe;
            $oDAOObrasAlvara->ob04_ativo = $oParam->bAtivo;
            $oDAOObrasAlvara->ob04_datacancelamentoreativacao = date('Y-m-d');
            $oDAOObrasAlvara->alterar($oParam->iCodigoObra);

            if ($oParam->bAtivo == 'f') {
                $oRetorno->sMessage = _M('tributario.projetos.pro1_obrasalvara.sucesso_reativar');
            } else {
                $oRetorno->sMessage = _M('tributario.projetos.pro1_obrasalvara.sucesso_cancelar');
            }

            $oRetorno->ativo = $oParam->bAtivo;

            db_fim_transacao();
        } catch (Exception $oException) {
            $oRetorno->iStatus = 2;
            $oRetorno->sMessage = $oException->getMessage();
        }

        break;
}

$oRetorno->sMessage = urlencode($oRetorno->sMessage);
echo $oJson->encode($oRetorno);

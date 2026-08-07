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
  require_once(modification("std/db_stdClass.php"));
  require_once(modification("libs/db_app.utils.php"));
  require_once(modification("libs/db_conecta.php"));
  require_once(modification("libs/db_sessoes.php"));
  require_once(modification("libs/db_libcontabilidade.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification("libs/JSON.php"));
  require_once(modification("classes/db_orcparametro_classe.php"));
  require_once(modification("classes/db_concarpeculiar_classe.php"));
  require_once(modification("model/orcamento/CaracteristicaPeculiar.model.php"));    
  
  db_app::import("configuracao.DBEstrutura");
  
  $oJson             = new services_json();
  $oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
  $oRetorno          = new stdClass();
  $oRetorno->status  = 1;
  $oRetorno->message = '';
  
  switch ( $oParam->exec ) {
    
    /**
     * Busca os parâmetros para o ano corrente.
     */
    case "getParametrosPorAno":
      
      $oDaoOrcParametro       = new cl_orcparametro();
      $sSqlOrcParametro       = $oDaoOrcParametro->sql_query(db_getsession("DB_anousu"),"o50_estruturacp");
      $oDadosRetorno          = db_utils::getCollectionByRecord($oDaoOrcParametro->sql_record($sSqlOrcParametro));
      $oRetorno->iEstruturaCP = $oDadosRetorno[0]->o50_estruturacp;
    break;
    
    /**
     * Salva a configuração definida pelo usuário
     */
    case "salvarConfiguracao":

      try {
			
        db_inicio_transacao();
        $oDaoConCarPeculiar = new CaracteristicaPeculiar($oParam->sEstrutural);
			  $oDaoConCarPeculiar->setDescricao(utf8_decode(db_stdClass::db_stripTagsJson($oParam->sDescricao)))
			                     ->setTipoConta($oParam->iTipo)
			                     ->setTipoClassificacao($oParam->iClassificacao)
			                     ->setEstrutura((int)$oParam->iEstruturaCP)
                           ->setEstrutural($oParam->sEstrutural)
			                     ->salvar();
			                     
			  $oRetorno->message = urlencode("Operação efetuada com sucesso!");
			  db_fim_transacao(false);
			  
      } catch (Exception $eErro) {
        
        db_fim_transacao(true);
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());        
      }
		break;
    
		/**
		 * Busca os dados para uma alteração
		 */
    case "getDadosCaracteristica":
      
      $oDaoCaractPeculiar = new cl_concarpeculiar();
      $oDaoCaractPeculiar->c58_sequencial = $oParam->iCodigo;
      
      $sSqlCaracteristica = $oDaoCaractPeculiar->buscaDadosCaracteristica($oDaoCaractPeculiar->c58_sequencial);
      $rsExecutaQuery     = db_query($sSqlCaracteristica);
      if (!$rsExecutaQuery) {
          throw new DBException("Ocorreu um erro ao buscar as informações da característica peculiar.");
      }

      $oDadosCaracteristica = db_utils::fieldsMemory($rsExecutaQuery,0);
      $oRetorno->c58_sequencial  = $oDadosCaracteristica->c58_sequencial;
      $oRetorno->c58_descr       = urlencode($oDadosCaracteristica->c58_descr);
      $oRetorno->c58_tipo        = $oDadosCaracteristica->c58_tipo;
      $oRetorno->db121_tipoconta = $oDadosCaracteristica->db121_tipoconta;
      
    break;
    
    /**
     * Remove uma caracteristica cadastrada pelo código da estrutura
     */
    case "removerCaracteristica":
      
      try {
        
        db_inicio_transacao();
        
        $oRemCaracteristica = new CaracteristicaPeculiar($oParam->sEstrutural);
        $oRemCaracteristica->remover();
        
        db_fim_transacao(false);
        $oRetorno->message = urlencode("Característica excluída com sucesso.");
        
      } catch (Exception $eErro) {
        
        db_fim_transacao(true);
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage()); 
      }
      
    break;

  }
  
  echo $oJson->encode($oRetorno);
  
?>
<?php
/**
 * @fileoverview Controla Ações no cadastro de contrução da obra
 * @version   $Revision: 1.8 $
 * @revision  $Author: dbfabio.esteves $
 */
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));  

require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("classes/db_iptuconstrobrasconstr_classe.php"));
require_once(modification("classes/db_obrasalvara_classe.php"));
require_once(modification("classes/db_obrasconstr_classe.php"));
require_once(modification("classes/db_obrasconstrcaracter_classe.php"));

require_once(modification("model/cadastro/Imovel.model.php"));

define('CONSTRUCAO_MODULO_CADASTRO',1);
define('CONSTRUCAO_MODULO_PROJETOS',2);

if (array_key_exists("json", $_POST)) {
  $oJson = new services_json();
  $oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
} else {
  $oParam = JSON::requestParameters();
}

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();
/**
 * Camada de Tentativas do RPC
 */
try {
  
  switch ($oParam->sExec) {
    
    case "getObrasComAlvara":
    
      $oDaoObrasAlvara           = new cl_obrasalvara();
      
      $sSqlDadosObra             = $oDaoObrasAlvara->sql_query_obrasCadastroImobiliario($oParam->iMatricula);
      $rsObrasAlvara             = db_query($sSqlDadosObra);
      
      if ( !$rsObrasAlvara ) {
        throw new Exception('Erro ao retornar dados da obra');
      }
      
      $oRetorno->aObrasAlvara      = array();
      
      if ( pg_num_rows($rsObrasAlvara) ) {
	      $oRetorno->aObrasAlvara    = db_utils::getCollectionByRecord($rsObrasAlvara, false, false,true );
      }
    break;
    
    case "getConstrucoesMatricula":
      
      $oImovel             = new Imovel($oParam->iMatricula);
      $aDadosConstrucoes   = $oImovel->getConstrucoes(true);

			if ( $oImovel->getDataBaixa() != '' ) {
				throw new Exception("Matricula baixada");
			}

			/**
			 * Verifica se matriculas sao do mesmo lote
			 */	 
      $lMesmoLote       = false;
			$oImovelAlteracao = new Imovel($oParam->iMatriculaParaAlteracao);

			if ( $oImovel->getCodigoLote() == $oImovelAlteracao->getCodigoLote() ) {
				$lMesmoLote = true;
			}

      if ( count($aDadosConstrucoes) == 0) {
        throw new Exception("Nenhuma construção encontrada para a matricula {$oParam->iMatricula}!");
      }
      
      $aRetornoConstrucoes = array();
      
      foreach ($aDadosConstrucoes as $oRegistro) {
        
        $oConstrucao = new stdClass();
        $oConstrucao->iCodigoConstrucao       = $oRegistro->getCodigoConstrucao();
        $oConstrucao->lPrincipal              = $oRegistro->isConstrucaoPrincipal();
        $oConstrucao->iAnoConstrucao          = $oRegistro->getAnoConstrucao();
        $oConstrucao->nAreaConstrucao         = $oRegistro->getArea();
        $oConstrucao->nAreaPrivada            = $oRegistro->getAreaPrivada();
        
        $oConstrucao->lMesmoLote              = $lMesmoLote;
        $oConstrucao->iPavimentos             = $oRegistro->getQuantidadePavimentos();
        $oConstrucao->iCodigoLogradouro       = $oRegistro->getCodigoRua();
        $oConstrucao->iNumeroLogradouro       = $oRegistro->getNumeroEndereco();
        $oConstrucao->sNomeLogradouro         = $oRegistro->getNomeRua();
        $oConstrucao->sComplementoLogradouro  = urlEncode($oRegistro->getComplementoEndereco());
        
        $oConstrucao->iCodigoOrigemConstrucao  = $oRegistro->getCodigoOrigemConstrucao();
        $oConstrucao->sObservacaoConstrucao    = urlEncode($oRegistro->getObservacaoConstrucao());
        
        $aRetornoConstrucoes[] = $oConstrucao;
         
      }
      
      $oRetorno->aConstrucoesMatricula = $aRetornoConstrucoes;
      
    break;  
     
    case "getCaracteristicasSelecao":
      
      $oDaoCarConstr           = db_utils::getDao("carconstr");
      $sSqlCaracteristicas     = $oDaoCarConstr->sql_querySelecaoCaracteristicas($oParam->iMatricula, $oParam->iCodigoConstrucao);      
      $rsCaracteristicas       = db_query($sSqlCaracteristicas);
       
      $oRetorno->aSelecionadas    = array();
      $oRetorno->aCaracteristicas = array();

      if ( !$rsCaracteristicas ) {
        throw new Exception('Erro ao retornar caracteristicas da construcao\n'.pg_last_error());
      }

      foreach ( db_utils::getCollectionByRecord($rsCaracteristicas,false,false,true) as $oDados) {

        $oRetornoGrupo                                  = new stdClass();
        $oRetornoGrupo->iCodigoGrupo                    = $oDados->j32_grupo;
        $oRetornoGrupo->sDescricaoGrupo                 = $oDados->j32_descr;
        $oRetorno->aCaracteristicas[$oDados->j32_grupo] = $oRetornoGrupo;

        $oRetornoSelecao = new stdClass();
        $oRetornoSelecao->iCodigoCaracteristica         = $oDados->j31_codigo;
        $oRetornoSelecao->sDescricaoCaracteristica      = $oDados->j31_descr;
        $oRetornoSelecao->lSelecionada                  = $oDados->selecionada == "t" ? true : false;
        $aCaracteristicas[$oDados->j32_grupo][]         = $oRetornoSelecao;
 
        if ( $oDados->selecionada == "t" ) {
          $oRetorno->aSelecionadas[] = $oDados->j31_codigo;
        }        
      }

      foreach ( $oRetorno->aCaracteristicas as $iGrupo => $oRetornoCaracteristica ) {

        $oCaracter          = $oRetorno->aCaracteristicas[$iGrupo];
        $oCaracter->aOpcoes = $aCaracteristicas[$iGrupo];
      }                             

    break;
    case "getCaracteristicasConstrucao": 
    	
      
      if ($oParam->iTipoConstrucao == CONSTRUCAO_MODULO_CADASTRO) {
        
        $oConstrucao = new Construcao($oParam->iMatricula, $oParam->iCodigoConstrucao);
        $oRetorno->aCaracteristicas = $oConstrucao->getCaracteristicasConstrucao();
        
      } else if ($oParam->iTipoConstrucao == CONSTRUCAO_MODULO_PROJETOS) {
      

        $sSqlCaracteristicas = "

                      select j32_grupo,
                             ob34_caracter
                        from obrasconstrcaracter 
                        inner join caracter on ob34_caracter = j31_codigo
                        inner join cargrup on j31_grupo = j32_grupo
                       where ob34_obrasconstr = {$oParam->iCodigoConstrucao};
        ";

        $rsCaracteristicas       = db_query($sSqlCaracteristicas);
        
    	  
    	  if ( !$rsCaracteristicas ) {
    	  	throw new Exception('Erro ao retornar caracteristicas da construcao\n'.pg_last_error());
    	  }
    	  
    	  $oRetorno->aCaracteristicas  = array();
        $aCaracteristicas = array();
        
    	  if ( pg_numrows($rsCaracteristicas) ) {

           for ( $iCarct = 0; $iCarct <  pg_numrows($rsCaracteristicas); $iCarct++ ) {
            
              $oDadosCaract = db_utils::fieldsMemory($rsCaracteristicas, $iCarct);
              $oDadosRetorno = new stdClass();
              $oDadosRetorno->iGrupo = $oDadosCaract->j32_grupo;
              $oDadosRetorno->iCaracteristica = $oDadosCaract->ob34_caracter;

              $aCaracteristicas[] = $oDadosRetorno;

           }
        }
        
        $oRetorno->aCaracteristicas = $aCaracteristicas;

      }
    	break;

    case 'buscarInscricaoImobiliaria':

      $sql = "select j40_refant from iptuant where j40_matric = {$oParam->matricula}";
      $result = db_query($sql);

      if (!$result) {
        throw new DBException("Erro ao Inscrição Imobiliária.");
      }
      
      if(pg_num_rows($result) > 0) {
        $ors = db_utils::fieldsMemory($result,0);
        $oRetorno->j40_refant = $ors->j40_refant;
      }
      break;
    default:
      throw new Exception("Nenhuma Opção Definida");
    break;
  }

  echo JSON::create()->stringify($oRetorno);
  
} catch (Exception $eErro) {
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}

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

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';
const MENSAGEM          = 'tributario.projetos.pro4_obrasalvara.';
$cl_obrasrenovacaoalvara = new cl_obrasrenovacaoalvara;

try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {

    case 'renovaAlvara':

      if (empty($oParam->iCodigoObra)){
        throw new ParameterException(_M(MENSAGEM . 'codigo_obra_nao_informado'));
      }

      if (empty($oParam->sDataInicial)) {
        throw new ParameterException(_M(MENSAGEM . 'data_inicial_nao_informado'));
      }

      if (empty($oParam->sDataFinal)) {
        throw new ParameterException(_M(MENSAGEM . 'data_final_nao_informado'));
      }

      if($oParam->sDataFinal < $oParam->sDataInicial) {
        throw new BusinessException(_M(MENSAGEM . 'data_final_menor_data_inicial'));
      }

      /**
       * Verifica se a data inicial informada não é menor que a data inical da 
       * ultima renovação
       */
      $oDaoObrasAlvara = db_utils::getDao('obrasalvara');
      $sWhere          = "ob04_codobra = {$oParam->iCodigoObra}";
      $sSqlObrasAlvara = $oDaoObrasAlvara->sql_query_file(null, "*", null, $sWhere);
      $rsObrasAlvara   = pg_query($sSqlObrasAlvara);

      if(pg_num_rows($rsObrasAlvara) == 0){
        throw new BusinessException(_M(MENSAGEM . 'obra_sem_alvara'));
      }

      $oObrasAlvara = db_utils::fieldsMemory($rsObrasAlvara, 0);
      if ($oObrasAlvara->ob04_data >= $oParam->sDataInicial) {
        throw new BusinessException(_M(MENSAGEM . 'data_inicial_menor_ultima_renovacao'));
      } else if($oObrasAlvara->ob04_ativo == 'f'){
        throw new BusinessException(_M(MENSAGEM . 'alvara_cancelado'));
      }
      
      db_inicio_transacao();

      $sWhere          = "ob33_codobra = {$oParam->iCodigoObra}";
      $sSqlObrasRenovacaoAlvara = $cl_obrasrenovacaoalvara->sql_query_file(null, 'ob33_codobra', null, $sWhere);
      $rsObrasRenovacaoAlvara   = pg_query($sSqlObrasRenovacaoAlvara);

      /**
       * Atualiza os dados na tabela obrasrenovacaoalvara.
       */

      if(pg_num_rows($rsObrasRenovacaoAlvara) == 0){
        $cl_obrasrenovacaoalvara->ob33_codobra = $oParam->iCodigoObra;
        $cl_obrasrenovacaoalvara->ob33_dtrenovacao = $oParam->sDataInicial;
        $cl_obrasrenovacaoalvara->ob33_dtvalidade = $oParam->sDataFinal;
        $cl_obrasrenovacaoalvara->incluir($oParam->iCodigoObra);
      } else {
        $cl_obrasrenovacaoalvara->ob33_codobra = $oParam->iCodigoObra;
        $cl_obrasrenovacaoalvara->ob33_dtrenovacao = $oParam->sDataInicial;
        $cl_obrasrenovacaoalvara->ob33_dtvalidade = $oParam->sDataFinal;
        $cl_obrasrenovacaoalvara->alterar($oParam->iCodigoObra);
      }

      /**
       * Atualiza os dados na tabela obrasalvara.
       */

      $oDaoObrasAlvara->ob04_codobra     = $oParam->iCodigoObra;
      $oDaoObrasAlvara->ob04_alvara      = $oParam->iCodigoAlvara;
      $oDaoObrasAlvara->ob04_dtvalidade  = $oParam->sDataFinal;
      $oDaoObrasAlvara->alterarAlvaraRenovacao($oParam->iCodigoObra);

      if ($oDaoObrasAlvara->erro_status == '0') {
        throw new DBException(_M(MENSAGEM . 'erro_obrasalvara'));
      }

      /**
       * Adiciona os dados da tabela obrasalvara na tabela obrasalvarahistorico.
       */
      $oDaoObrasAlvaraHistorico = db_utils::getDao('obrasalvarahistorico');
      $oDaoObrasAlvaraHistorico->ob35_sequencial  = null;
      $oDaoObrasAlvaraHistorico->ob35_codobra     = $oParam->iCodigoObra;
      $oDaoObrasAlvaraHistorico->ob35_datainicial = $oParam->sDataInicial;
      $oDaoObrasAlvaraHistorico->ob35_datafinal   = $oParam->sDataFinal;
      $oDaoObrasAlvaraHistorico->incluir(null);

      if($oDaoObrasAlvaraHistorico->erro_status == '0') {
        throw new DBException(_M(MENSAGEM . 'erro_obrasalvaraistorico'));
      }

      db_fim_transacao(false);
      $oRetorno->sMessage = urlencode(_M(MENSAGEM . 'alvara_renovado'));
    break;
    case 'getAlvara':

      if (empty($oParam->iCodigoObra)){
        throw new ParameterException(_M(MENSAGEM . 'codigo_obra_nao_informado'));
      }

      $sWhere          = "ob33_codobra = {$oParam->iCodigoObra}";
      $sSqlObrasRenovacaoAlvara = $cl_obrasrenovacaoalvara->sql_query(null, '*, ob04_alvara', 'ob33_dtrenovacao desc', $sWhere);
      $rsObrasRenovacaoAlvara   = pg_query($sSqlObrasRenovacaoAlvara);

      if(pg_num_rows($rsObrasRenovacaoAlvara) == 0){

        $oDaoObrasAlvara = db_utils::getDao('obrasalvara');
        $sWhere          = "ob04_codobra = {$oParam->iCodigoObra}";
        $sSqlObrasAlvara = $oDaoObrasAlvara->sql_query_file(null, "*", null, $sWhere);
        $rsObrasAlvara   = pg_query($sSqlObrasAlvara);

        if(pg_num_rows($rsObrasAlvara) == 0){
          throw new BusinessException(_M(MENSAGEM . 'obra_sem_alvara'));
        }

        $oObrasAlvara = db_utils::fieldsMemory($rsObrasAlvara, 0);
      } else {
        $oObrasAlvara = db_utils::fieldsMemory($rsObrasRenovacaoAlvara, 0);
      }

      $oRetorno->oAlvara = $oObrasAlvara;

    break;

    case 'getHistorico':

      if (empty($oParam->iCodigoObra)){
        throw new ParameterException(_M(MENSAGEM . 'codigo_obra_nao_informado'));
      }

      $oDaoObrasAlvaraHistorico = db_utils::getDao("obrasalvarahistorico");

      $sWhere = "ob35_codobra = {$oParam->iCodigoObra}";
      $sSqlHistorico = $oDaoObrasAlvaraHistorico->sql_query(null, "ob35_datainicial, ob35_datafinal", "ob35_datainicial", $sWhere);

      $rsHistorico = db_query($sSqlHistorico);

      if (!$rsHistorico) {
        throw new DBException(_M(MENSAGEM . "erro_busca_historico"));
      }

      $oRetorno->aHistoricos = db_utils::getCollectionByRecord($rsHistorico);
      
    break;
  }
  
    
  
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
?>

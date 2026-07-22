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

require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_stdlibwebseller.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/JSON.php'));
require_once(modification('dbforms/db_funcoes.php'));
require_once(modification("std/db_stdClass.php"));

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->erro     = false;
$oRetorno->sMessage = '';

try {
  switch ($oParam->exec) {
    case 'getRetiradasCgs':
      $oParametroFarmacia                 = db_stdClass::getParametro("far_parametros", array());
      $lImpressaotermica                  = $oParametroFarmacia[0]->fa02_utilizaimpressoratermica;
      $oRetorno->lUtilizaImpressaoTermica = $lImpressaotermica == 't'?true:false;  
      $oDaoFarRetirada                    = new cl_far_retirada();
      $sCampos                            = 'far_retirada.*, descrdepto, fa07_i_matrequi';
      $sWhere                             = 'fa04_i_cgsund = '.$oParam->iCgs;
      $sSql                               = $oDaoFarRetirada->sql_query_retiradas(null, $sCampos, 'fa04_i_codigo desc', $sWhere);
      $rs                                 = $oDaoFarRetirada->sql_record($sSql);
    
      if ($oDaoFarRetirada->numrows == 0) {
        throw new \Exception('Nenhuma retirada encontrada para este CGS.');
      }

      $oRetorno->aRetiradas = db_utils::getCollectionByRecord($rs, false, false, true);

      break;
    case 'getSaldoTotalMedicamento':

      $oDaoFarMaterSaude = new cl_far_matersaude();
      $sSql              = $oDaoFarMaterSaude->sql_query_saldo($oParam->iMedicamento, 'descrdepto', 'm91_codigo');
      $rs                = $oDaoFarMaterSaude->sql_record($sSql);
      if ($oDaoFarMaterSaude->numrows == 0) {
        throw new \Exception('Medicamento informado não encontrado.');
      }
      $estoquesDepartamentos = db_utils::getCollectionByRecord($rs);

      $oRetorno->m70_quant = 0;
      foreach ($estoquesDepartamentos as $estoqueDepartamento) {
        $oRetorno->m70_quant += $estoqueDepartamento->saldo;
      }
      
      if ($oRetorno->m70_quant == 0 || empty($oRetorno->m70_quant)) {
        throw new \Exception('Nenhum registro de estoque encontrado para este Medicamento.');
      }
      $oRetorno->estoques = utf8_encode_all($estoquesDepartamentos);

      break;
    default: 
      break;
  }
} catch (\Exception $e) {
  $oRetorno->iStatus   = 0;
  $oRetorno->erro      = true;
  $oRetorno->sMessage  = $e->getMessage();
}

echo $oJson->encode($oRetorno);
?>
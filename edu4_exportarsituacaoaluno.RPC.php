<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_layouttxt.php");
require_once ("libs/JSON.php");  

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {

    case "exportar":
      
      $oRetorno->sMessage = "Arquivo gerado com sucesso.";
      
      $sArquivoLog = "tmp/exportacao_situacao_aluno_2013.json";
      
      $oLog = new DBLogJSON($sArquivoLog);
      
      $oExportacao = new ExportacaoSituacaoAlunoCenso2013($oLog);
      $oExportacao->setEscola(EscolaRepository::getEscolaByCodigo(db_getsession("DB_coddepto")));
      $oRetorno->sArquivoCenso = urlencode( $oExportacao->getNomeArquivoCenso() );
      
      if (!$oExportacao->gerarArquivo()) {
        
        $oRetorno->iStatus     = 2;
        $oRetorno->sMessage    = urlencode( "Falha ao gerar o arquivo. Verifique as inconsistências." );
        $oRetorno->sArquivoLog = $sArquivoLog;      
      }
        
    break;
  }
  
  db_fim_transacao(false);
    
  
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);
?>
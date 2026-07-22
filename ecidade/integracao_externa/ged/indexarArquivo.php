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


$sURL_eCidade  = "http://localhost/dbportal_prj";

define("BR", "\n -- ");

try {

  $sJson           = $argv[2];
  $sArquivo        = $argv[1];
  $sArquivoDestino = str_replace("documentos", 'arquivos/acervo001', $sArquivo);
  $sCaminhoInterno = str_replace("arquivos/",  '', $sArquivoDestino);


  if ( !file_exists($sArquivo) ) {
    throw new Exception("Arquivo não existe.");
  }

  echo BR . "Arquivo {$sArquivo} existe.";

  $lCopiouArquivo     = copy($sArquivo, $sArquivoDestino);

  if ( !$lCopiouArquivo ) {
    throw new Exception("Não foi possivel mover o arquivo.");
  }
  
  echo BR . "Copiando para {$sArquivoDestino}";
  
  $lDefiniuPermissoes = chmod($sArquivoDestino, 0775);//Deixa com as mesmas permissões do arquivo original

  if ( !$lDefiniuPermissoes ) {
    throw new Exception("Não foi possivel definir permissões para o Arquivo.");
  }
  
  echo BR . "Definindo CHMOD 0775 para o Arquivo: {$sArquivoDestino}";

  /**
   * WebService 
   */
  $oParametrosSoap           = new stdClass();
  $oParametrosSoap->uri      = "http://swp10:8080";
  $oParametrosSoap->location = "http://swp10:8080/wsged/services/GED?wsdl";
  $oSoapClient               = new SoapClient(null, (array)$oParametrosSoap);
  $sNomeMetodo               = "";
  $aParametrosMetodo         = array();
  $oResposta                 = $oSoapClient->indexarArquivo($sCaminhoInterno, $sJson);

  echo BR . "--------------------- INICIO RESPOSTA ---------------------";
  echo BR . print_r( $oResposta, 2 );
  echo BR . "---------------------- FIM RESPOSTA -----------------------";
  echo "\n\n";

} catch ( SoapFault $eErroSoap) {
  print_r( $eErroSoap );
}
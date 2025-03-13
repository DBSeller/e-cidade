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

//
// PROCESSAMENTO DA DEBITOS
//

$sTabelaNumpresTmp = $argv[1]; 
$sTabelaDebitosTmp = $argv[2];

$sData = date("Y-m-d");

$sSqlPrepare ="prepare db_calcula(integer, integer, integer, date, date, integer, varchar) as select fc_calcula($1, $2, $3, $4, $5, $6, $7);";

db_query($pConexao, $sSqlPrepare, $sArquivoLog);


$sSql = "select * from {$sTabelaNumpresTmp}";
$rsNumpres = db_query($pConexao, $sSql, $sArquivoLog);
$iNumrows = db_numrows($rsNumpres, $sArquivoLog);
for($iNumpre=0; $iNumpre < $iNumrows; $iNumpre++) {
  $oNumpre = db_utils::fieldsmemory($rsNumpres, $iNumpre);

  $SqlCalcula = "execute db_calcula({$oNumpre->k00_numpre}, 
                                    {$oNumpre->k00_numpar}, 
                                    {$oNumpre->k00_receit}, 
                                    '{$sData}', 
                                    '{$sData}', 
                                    100000::integer, 
                                    '{$sTabelaDebitosTmp}')";

  db_query($pConexao, $sSqlCalcula, $sArquivoLog);

}

?>
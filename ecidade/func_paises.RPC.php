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

use phpDocumentor\Reflection\Types\Boolean;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oParam   = JSON::requestParameters();
$oRetorno = (object)['erro' => false, 'mensagem' => ''];


switch ($oParam->exec) {
	
	case "buscaPaises":

        $oRetorno->aPaises   = [];  
      try {
        
        $sDadosPaises        = "select db70_sequencial    as codigo,  ";
        $sDadosPaises       .= "       db70_descricao, ";
        $sDadosPaises       .= "       substr(db135_codigo, 2, 3) as codpais";
        $sDadosPaises       .= "  from cadenderpaissistema";
        $sDadosPaises       .= " inner join cadenderpais ";
        $sDadosPaises       .= "    on db70_sequencial = db135_db_cadenderpais ";
        $sDadosPaises       .= "   and db135_db_sistemaexterno = 3";

        if($oParam->retornaBrasil === 'N') {
            $sDadosPaises       .= " where trim(db70_descricao) <> 'BRASIL'";
        }
        
        $rsPaises            = db_query($sDadosPaises);
      
        if($rsPaises == false) {
          
          throw new DBException("Ocorreu erro ao buscar os dados dos municípios.");
        }
        
        $iRowsPaises = pg_num_rows($rsPaises);
        for ($iRow = 0; $iRow < $iRowsPaises; $iRow++) {
      
          $oDadosPais          = db_utils::fieldsMemory($rsPaises,$iRow);
          $oPais               = new stdClass();
          $oPais->codigo       = $oDadosPais->codigo;
          $oPais->codpais      = $oDadosPais->codpais;
          $oPais->descricao    = utf8_encode($oDadosPais->db70_descricao);
          $oRetorno->aPaises[] = $oPais;
        }

	  } catch (Exception $eErro) {

	    $oRetorno->status  = 2;
	    $oRetorno->message = urlencode($eErro->getMessage());
	  }
		break;
}

echo JSON::create()->stringify($oRetorno);
?>
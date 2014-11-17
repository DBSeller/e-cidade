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

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("model/pessoal/ArquivoEconsig.model.php");

$oPost  = db_utils::postMemory($_POST);
$oFiles = db_utils::postMemory($_FILES);

$sPosScripts = '';
$sMensagens = "recursoshumanos.pessoal.pes4_importacaoarquivoeconsig.";

if (isset($oPost->incluir)) {

  if ($oFiles->aArquivoMovimento['error'] != 0) {

    $sPosScripts .= "alert('" . _M("{$sMensagens}falha_importacao") . "');\n";
  } else if (move_uploaded_file($oFiles->aArquivoMovimento['tmp_name'], "tmp/{$oFiles->aArquivoMovimento['name']}")) {

    $oArquivoEconsig = new ArquivoEconsig( db_getsession("DB_instit") );

    db_inicio_transacao();

    try {

      if (!preg_match('/.*\.txt$/i', $oFiles->aArquivoMovimento['name'])) {
        throw new Exception( _M("{$sMensagens}extensao_invalida") );
      }

      if (!preg_match('/econsig_(\d{4})_(\d{2})_(\d{3})\.txt/i', $oFiles->aArquivoMovimento['name'])) {
        throw new Exception( _M("{$sMensagens}nome_invalido") );
      }

      $lFalha = $oArquivoEconsig->importarArquivoMovimento("tmp/{$oFiles->aArquivoMovimento['name']}");
      db_fim_transacao(!$lFalha);

      if ($lFalha) {
        $sPosScripts .= "alert('" . _M("{$sMensagens}arquivo_importado") . "');\n";
      } else {

        $sPosScripts .= "alert('" . _M("{$sMensagens}inconsistencias") . "');\n";
        $sPosScripts .= "js_exibeInconsistencias(['" . $oArquivoEconsig->imprimeRelatorio()  . "']);";
      }

    } catch(Exception $e) {

      $sPosScripts .= "alert('" . $e->getMessage() . "');\n";
      db_fim_transacao(true);
    }

  }
}

$iAnoFolha = DBPessoal::getAnoFolha();
$iMesFolha = DBPessoal::getMesFolha();

include("forms/db_frmimportacaoarquivoeconsig.php");
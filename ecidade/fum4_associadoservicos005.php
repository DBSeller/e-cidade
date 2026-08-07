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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_postmemory($_POST);
db_postmemory($_GET);

if (isset($db_opcao)) {
  if (intval($db_opcao) == 1) {
     $db_opcao = 1;
     $db_botao = true;
  } else if (intval($db_opcao) == 2) {
     $db_opcao = 2;
     $db_botao = true;
  } else if (isset($bt_excluir) || intval($db_opcao) == 3) {
     $db_opcao = 3;
     $db_botao = true;
  }
}

if (!isset($liberaaba)) {
   $liberaaba = '';
}

$classociadovalorservico = new cl_associadovalorservico;
$classociadoservicos = new cl_associadoservicos;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

if (isset($liberaaba) && $liberaaba) {
   $lSqlErro = false;
   if (isset($opcao) && $opcao == 'alterar') {

      $sCampos = "fm13_codigo, fm13_servico, fm13_valor, fm13_vigencia, fm09_valor, fm13_vigencia, ";
      $sCampos .= "case when fm09_copart_percentual is true ";
      $sCampos .= "     then round(((fm13_valor * fm09_valor) / 100), 2) ";
      $sCampos .= "     else fm09_valor ";
      $sCampos .= "end as db_coparticipacao, fm09_copart_percentual, fm09_copart_financeiro ";

      $sql = $classociadovalorservico->sql_query($_POST['fm13_codigo'], $sCampos);
      $result = $classociadovalorservico->sql_record($sql);

      if ($result && pg_numrows($result) > 0) {
          db_fieldsmemory($result, 0);   
          $db_opcao = 2;
          $db_botao = true;
      }
   } else if (isset($opcaoaba) || isset($chavepesquisa)) {

      if (isset($opcaoaba)) {
         $opcaobusca = $opcaoaba;
      }

      if (isset($chavepesquisa)) {
         $opcaobusca = $chavepesquisa;
      }

      $sCampos = "fm12_codigo as fm13_servico, fm09_copart_financeiro, fm09_copart_percentual, fm09_valor, ";
      $sCampos .= "case when fm09_copart_financeiro is true then fm09_valor ";
      $sCampos .= "     else 0 ";
      $sCampos .= "end as db_coparticipacao ";
       
      $sql = $classociadoservicos->sql_query($opcaobusca, $sCampos);
      $result = $classociadoservicos->sql_record($sql);
       
      if ($result && pg_numrows($result) > 0) {
         db_fieldsmemory($result, 0);
      }

      $db_opcao = 1;
      $opcaoaba = '';
   }

   $db_botao = true;    

}

function validaDataVigencia($classociadovalorservico, $iservico, $dVigencia, $iCodigo=0) {

   global $lSqlErro, $sErroMsg;
   $sWhere = "fm13_servico = {$iservico} ";
   $sql = $classociadovalorservico->sql_query_file(null, "fm13_codigo", null, $sWhere);
   $result = $classociadovalorservico->sql_record($sql);
   $sMsgRetorno = pg_last_error();

   if (empty($sMsgRetorno)) {
      if ($result && pg_numrows($result) > 0) {
         $sWhere .= " and (";

         if ($iCodigo > 0) {
            $sWhere .= "(fm13_codigo = {$iCodigo} and fm13_vigencia = '{$dVigencia}') or ";
         }

         $sWhere .= "(select fm13_vigencia from associadovalorservico where fm13_servico = {$iservico} order by 1 desc limit 1)  < '{$dVigencia}') ";
         $sql = $classociadovalorservico->sql_query_file(null, "fm13_codigo", null, $sWhere);
         $result = $classociadovalorservico->sql_record($sql);
         
         if ($result && pg_numrows($result) > 0) {
            return false;
         } else {
            return true;
         }
      } else {
         return false;
      }
   } else {
      $lSqlErro = true;
      $sErroMsg = $sMsgRetorno;
   }
}

$sMsgVigencia = "Atenção, data de vigência deve ser maior que a cadastrada.";

if (isset($incluir)) {
   $lSqlErro = false;

   if (validaDataVigencia($classociadovalorservico, $fm13_servico, $fm13_vigencia)) {
      if (!$lSqlErro) {
         $sErroMsg = $sMsgVigencia;
         $lSqlErro = true;
      }
   } else {
      $fm13_valor = preg_replace("/[^0-9]/", "", $fm13_valor);
      $fm13_valor = ($fm13_valor / 100);

      db_inicio_transacao();
      $classociadovalorservico->fm13_servico = $fm13_servico;
      $classociadovalorservico->fm13_valor = $fm13_valor;
      $classociadovalorservico->fm13_vigencia = $fm13_vigencia;
      $classociadovalorservico->incluir(null);

      if ($classociadovalorservico->erro_status == 0 ) {
         $lSqlErro = true;
      }

      $sErroMsg = $classociadovalorservico->erro_msg;
      db_fim_transacao($lSqlErro);
         
      $db_opcao = 1;
      $db_botao = true;
   }
}

if (isset($alterar)) {
   $lSqlErro = false;

   if (validaDataVigencia($classociadovalorservico, $fm13_servico, $fm13_vigencia, $_POST['fm13_codigo'])) {
      $lSqlErro = true;
      $sErroMsg = $sMsgVigencia;
   } else {
      $fm13_valor = preg_replace("/[^0-9]/", "", $fm13_valor);
      $fm13_valor = ($fm13_valor / 100);

      db_inicio_transacao();
      $classociadovalorservico->fm13_servico = $fm13_servico;
      $classociadovalorservico->fm13_valor = $fm13_valor;
      $classociadovalorservico->fm13_vigencia = $fm13_vigencia;
      $classociadovalorservico->alterar($_POST['fm13_codigo']);

      if ($classociadovalorservico->erro_status == 0){
        $lSqlErro = true;
      }

      $sErroMsg = $classociadovalorservico->erro_msg;
      db_fim_transacao($lSqlErro);

      $db_opcao = 1;
      $db_botao = true;
      $opcao = '';
   }
}

if (isset($opcao) && $opcao == 'excluir') {
   $lSqlErro = false;
   db_inicio_transacao();
   $classociadovalorservico->excluir($fm13_codigo);

   if ($classociadovalorservico->erro_status == 0) {
      $lSqlErro = true;
   }
   $sErroMsg = $classociadovalorservico->erro_msg;
   db_fim_transacao($lSqlErro);

   $sql = $clprestador->sql_query($fm07_prestador);
   $result = $clprestador->sql_record($sql);
   if (pg_numrows($result) > 0) {
      db_fieldsmemory($result, 0);
   }  
   $db_opcao = 1;
   $db_botao = true;
}

?>
<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table align="center" style="padding-top:15px;" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td>
  			<?php
          if ($liberaaba) {
             require_once(modification("forms/db_frmassociadovalorservico.php"));
          }
  			?>
    	</td>
    </tr>
  </table>
  </body>
</html>
<script>

   function cleanCampo(){
      document.getElementById('fm13_codigo').value = '';
      document.getElementById('fm13_servico').value = '';
      document.getElementById('fm13_valor').value = '';
      document.getElementById('fm13_vigencia').value = '';

      var opercentual = document.getElementById('percentual');

      if (opercentual.value) {
         document.getElementById('db_coparticipacao').value = '';
      }
   }
</script>
<?php
  if (isset($chavepesquisa)) {
     echo "<script>parent.document.formaba.valorservico.disabled=false;</script>";
  }

  if (isset($incluir)) {
      if ($lSqlErro == true) {
         db_msgbox($sErroMsg);
         if ($classociadovalorservico->erro_campo != "") {
            echo "<script> document.form1.".$classociadovalorservico->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$classociadovalorservico->erro_campo.".focus();</script>";
         }
      } else {
         db_msgbox($sErroMsg);
      }
      echo "<script> cleanCampo(); </script>";
  }

  if (isset($alterar)) {
      if ($lSqlErro == true) {
         db_msgbox($sErroMsg);
         if ($classociadovalorservico->erro_campo != "") {
            echo "<script> document.form1.".$classociadovalorservico->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$classociadovalorservico->erro_campo.".focus();</script>";
         }
      } else {
         db_msgbox($sErroMsg);
      }  
      echo "<script> cleanCampo(); </script>";
  }

  if (isset($opcao) && $opcao == 'excluir') {
      if ($lSqlErro == true) {
         db_msgbox($sErroMsg);
         if ($classociadovalorservico->erro_campo != "") {
            echo "<script> document.form1.".$classociadovalorservico->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$classociadovalorservico->erro_campo.".focus();</script>";
         }
      }            
      echo "<script> cleanCampo(); </script>";
  }

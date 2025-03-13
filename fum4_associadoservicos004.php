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
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$oDaoAssociadoservicos = new cl_associadoservicos;
$oDaoAssociadoValorServico = new cl_associadovalorservico;

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

if (isset($chavepesquisa) && intval($chavepesquisa) > 0) {

   $sCampos = "fm12_codigo, fm12_tpservico, fm09_descricao, fm12_descricao, fm12_situacao, ";
   $sCampos .= "fm12_odontograma, fm12_idademin, fm12_idademax, fm12_autorizacao, ";
   $sCampos .= "fm12_validadeini, fm12_validadefim, fm12_masculino, fm12_feminino, ";
   $sCampos .= "case when (fm12_idademin % 365) = 0 then (fm12_idademin / 365) ";
   $sCampos .= "     when (fm12_idademin % 30) = 0 then (fm12_idademin / 30) ";
   $sCampos .= "     else fm12_idademin ";
   $sCampos .= "end as idademin, ";
   $sCampos .= "case when (fm12_idademin % 365) = 0 then 3 ";
   $sCampos .= "     when (fm12_idademin % 30) = 0 then 2 ";
   $sCampos .= "     else 1 ";
   $sCampos .= "end as undidadeini, ";
   $sCampos .= "case when (fm12_idademax % 365) = 0 then (fm12_idademax / 365) ";
   $sCampos .= "     when (fm12_idademax % 30) = 0 then (fm12_idademax / 30) ";
   $sCampos .= "     else fm12_idademax ";
   $sCampos .= "end as idademax, ";
   $sCampos .= "case when (fm12_idademax % 365) = 0 then 3 ";
   $sCampos .= "     when (fm12_idademax % 30) = 0 then 2 ";
   $sCampos .= "     else 1 ";
   $sCampos .= "end as undidadefim ";

   $sql = $oDaoAssociadoservicos->sql_query(intval($chavepesquisa), $sCampos);
   $result = $oDaoAssociadoservicos->sql_record($sql);

   if (pg_numrows($result) > 0) {
       db_fieldsmemory($result, 0);
       if (!isset($bt_excluir)) {
          $db_opcao = 2;
          $db_botao = true; 
       } else {
          $db_opcao = 3;
          $db_botao = true;
       }
   }

   if ($db_opcao == 3) {
      echo "<script>
              parent.document.formaba.valorservico.disabled=true;
            </script>";
   } else {
      echo "<script>
              parent.document.formaba.valorservico.disabled=false;
              (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_valorservico.location.href='fum4_associadoservicos005.php?liberaaba=true&opcaoaba=$fm12_codigo&db_opcaoservico=$db_opcao';
            </script>";
   }
}

if (isset($incluir)) {

   $lSqlErro = false;
   db_inicio_transacao();

   if (!isset($GLOBALS["HTTP_POST_VARS"]["fm12_masculino"])) {
      $GLOBALS["HTTP_POST_VARS"]["fm12_masculino"] = "false";
   }
   
   if (!isset($GLOBALS["HTTP_POST_VARS"]["fm12_feminino"])) {
      $GLOBALS["HTTP_POST_VARS"]["fm12_feminino"] = "false";
   }

   $oDaoAssociadoservicos->incluir($fm12_codigo);

   if ($oDaoAssociadoservicos->erro_status == 0) {
      $lSqlErro = true;
   }

   $sErroMsg = $oDaoAssociadoservicos->erro_msg;
   db_fim_transacao($lSqlErro);

   $db_opcao = 1;
   $db_botao = false;
   db_postmemory($_POST);

} else if (isset($alterar)) {


   $lSqlErro = false;
   db_inicio_transacao();

   if (!isset($GLOBALS["HTTP_POST_VARS"]["fm12_masculino"])) {
      $GLOBALS["HTTP_POST_VARS"]["fm12_masculino"] = "false";
   }
   
   if (!isset($GLOBALS["HTTP_POST_VARS"]["fm12_feminino"])) {
      $GLOBALS["HTTP_POST_VARS"]["fm12_feminino"] = "false";
   }

   $oDaoAssociadoservicos->alterar($fm12_codigo);

   if ($oDaoAssociadoservicos->erro_status == 0) {
      $lSqlErro = true;
   }

   $sErroMsg = $oDaoAssociadoservicos->erro_msg;
   db_fim_transacao($lSqlErro);

   $db_opcao = 2;
   $db_botao = true;
   db_postmemory($_POST);

} else if (isset($excluir)) {

   $lSqlErro = false;
   db_inicio_transacao();

   $excluirValorServico = $oDaoAssociadoValorServico->excluir(null, "fm13_servico = {$fm12_codigo}");

   if (!$excluirValorServico) {
      $lSqlErro = true;
      $sErroMsg = $oDaoAssociadoValorServico->erro_msg;
   }

   if (!$lSqlErro) {
      $excluirServicos = $oDaoAssociadoservicos->excluir(null, "fm12_codigo = {$fm12_codigo}");

      if (!$excluirServicos) {
         $lSqlErro = true;
      }
      $sErroMsg = $oDaoAssociadoservicos->erro_msg;
   }

   db_fim_transacao($lSqlErro);

   $db_opcao = 3;
   $db_botao = true; 
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
        db_app::load("scripts.js");
        db_app::load("prototype.js");
    ?>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
  	  <?php
        require_once(modification("forms/db_frmassociadoservicos.php"));
      ?>
    </div>
  </body>
</html>
<script>

   var oGet = js_urlToObject(window.location.search);
   if (oGet.db_opcao != 1 && typeof(oGet.db_opcao) != 'undefined') {
      js_pesquisar();
   }
  
   function js_pesquisar() {
      js_OpenJanelaIframe( '', 
                           'db_iframe_associadoservicos', 
                           'func_associadoservicos.php?funcao_js=parent.js_preenchepesquisa|fm12_codigo',
                           'Pesquisa', true);
   }
  
   function js_preenchepesquisa(sChave) {
      oGet.db_opcao = 1;
      db_iframe_associadoservicos.hide();
      <?php
        if ($db_opcao == 2) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?db_opcao=1&chavepesquisa=' + sChave;";
        } else if ($db_opcao == 3) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?bt_excluir=true&chavepesquisa=' + sChave;";
        }
      ?>
   }
</script>

<?php

   if (isset($incluir)) {
      if ($lSqlErro == true) {
         db_msgbox($sErroMsg);
         if ($oDaoAssociadoservicos->erro_campo != "") {
            echo "<script> document.form1.".$oDaoAssociadoservicos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$oDaoAssociadoservicos->erro_campo.".focus();</script>";
         }
      } else {
         db_msgbox($sErroMsg);

         if ($fm12_masculino == 'true') {
            echo "<script> document.getElementById('fm12_masculino').checked = true; </script>";
         }

         if ($fm12_feminino == 'true') {
            echo "<script> document.getElementById('fm12_feminino').checked = true; </script>";
         }

         echo "<script>
                parent.document.formaba.valorservico.disabled=false;
                document.getElementById('fm12_codigo').value = {$oDaoAssociadoservicos->fm12_codigo};
                (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_valorservico.location.href='fum4_associadoservicos005.php?db_opcao=1&liberaaba=true&chavepesquisa=$oDaoAssociadoservicos->fm12_codigo';
                parent.document.formaba.valorservico.click();
              </script>";
      }
   }

   if (isset($alterar)) {
      if ($lSqlErro == true) {
         db_msgbox($sErroMsg);
         if ($oDaoAssociadoservicos->erro_campo != "") {
            echo "<script> document.form1.".$oDaoAssociadoservicos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$oDaoAssociadoservicos->erro_campo.".focus();</script>";
         }
      } else {
         db_msgbox($sErroMsg);

         if ($fm12_masculino == 'true') {
            echo "<script> document.getElementById('fm12_masculino').checked = true; </script>";
         }

         if ($fm12_feminino == 'true') {
            echo "<script> document.getElementById('fm12_feminino').checked = true; </script>";
         }
         echo "<script>
                 parent.document.formaba.valorservico.disabled=false;
                 (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_valorservico.location.href='fum4_associadoservicos005.php?db_opcao=1&liberaaba=true&chavepesquisa=$oDaoAssociadoservicos->fm12_codigo';
                 parent.document.formaba.valorservico.click();
               </script>";
      }
   }

   if (isset($excluir)) {
      db_msgbox($sErroMsg);
      echo "<script>
              parent.document.formaba.valorservico.disabled=true;
            </script>";
   }
   
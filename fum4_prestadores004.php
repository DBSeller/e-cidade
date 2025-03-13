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

/* Aba Prestador */

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clprestador  = new cl_prestadores;
$clprofissional = new cl_profissionaisprestadores;
$clservicos = new cl_servicosprestadores;

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

if (isset($chavepesquisa)) {

  $sCampos = "fm06_codigo, fm06_numcgm, fm06_depart, descrdepto as depart_descricao, z01_nome, ";
  $sCampos .= "z01_cgccpf, z01_nomefanta, z01_numero, z01_compl, z01_ender, z01_bairro, z01_munic, z01_telcel";

  $sql = $clprestador->sql_query(intval($chavepesquisa), $sCampos);
  $result = $clprestador->sql_record($sql);

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

  echo "<script>
         parent.document.formaba.profissionais.disabled=false;
         parent.document.formaba.servicos.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_profissionais.location.href='fum4_prestadores005.php?liberaaba=true&opcaoaba=$fm06_codigo';
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_servicos.location.href='fum4_prestadores006.php?liberaaba=true&opcaoaba=$fm06_codigo';
        </script>";


}

if (isset($incluir)) {
   $lSqlErro = false;
   db_inicio_transacao();
   $clprestador->fm06_depart = intval($fm06_depart);
   $clprestador->fm06_numcgm = intval($fm06_numcgm);
   $clprestador->incluir(intval($fm06_codigo));

   if ($clprestador->erro_status == 0) {
      $lSqlErro = true;
   }

   $sErroMsg = $clprestador->erro_msg;
   db_fim_transacao($lSqlErro);

   $db_opcao = 1;
   $db_botao = false;
} else if (isset($alterar)) {
   $lSqlErro = false;
   db_inicio_transacao();
   $clprestador->fm06_depart = intval($fm06_depart);
   $clprestador->fm06_numcgm = intval($fm06_numcgm);
   $clprestador->alterar($fm06_codigo);

   if ($clprestador->erro_status == 0) {
      $lSqlErro=true;
   }

   $sErroMsg = $clprestador->erro_msg;
   db_fim_transacao($lSqlErro);

   $db_opcao = 2;
   $db_botao = true;

} else if (isset($excluir)) {
   $lSqlErro = false;
   db_inicio_transacao();
   $servico = $clservicos->sql_query_file('','fm08_codigo', null, 'fm08_prestador = '.$fm06_codigo);
   $resp = db_query($servico);
   if (pg_numrows($resp) > 0) {
      for ($i=0;$i < pg_numrows($resp); $i++) {
          db_fieldsmemory($resp, $i);
          $excluirServico = $clservicos->excluir($fm08_codigo);
          if (!$excluirServico) {
             $lSqlErro = true;
             $sErroMsg = $clservicos->erro_msg;
          }
      }
   }

   $profissional = $clprofissional->sql_query_file('','fm07_codigo', null, 'fm07_prestador = '.$fm06_codigo);
   $resp = db_query($profissional);

   if (pg_numrows($resp) > 0) {
      for ($i=0;$i < pg_numrows($resp); $i++) {
          db_fieldsmemory($resp, $i);
          $excluirProfissional = $clprofissional->excluir($fm07_codigo, 'fm07_codigo = '.$fm07_codigo);
          if (!$excluirProfissional) {
             $lSqlErro = true;
             $sErroMsg = $clprofissional->erro_msg;
          }
      }
   }

   if (!$lSqlErro) {
      $clprestador->excluir($fm06_codigo);
      if ($clprestador->numrows_excluir == 0) {
         $lSqlErro = true;
         $sErroMsg = $clprestador->erro_msg;
      } else {
         $sErroMsg = $clprestador->erro_sql;
      }
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
    db_app::load("strings.js");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
	 <?php
  	 require_once(modification("forms/db_frmprestadorfumam.php"));
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
                           'db_iframe_prestadores', 
                           'func_prestadores.php?funcao_js=parent.js_preenchepesquisa|fm06_codigo', 
                           'Pesquisa', true);
      }

function js_preenchepesquisa(sChave) {

  oGet.db_opcao = 1;
  db_iframe_prestadores.hide();
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
      if ($clprestador->erro_campo != "") {
         echo "<script> document.form1.".$clprestador->erro_campo.".style.backgroundColor='#99A9AE';</script>";
         echo "<script> document.form1.".$clprestador->erro_campo.".focus();</script>";
      };
   } else {
      db_msgbox($sErroMsg);
      echo "<script>
             parent.document.formaba.profissionais.disabled=false;
             parent.document.formaba.servicos.disabled=false;
             document.getElementById('fm06_codigo').value = {$clprestador->fm06_codigo};
             (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_profissionais.location.href='fum4_prestadores005.php?db_opcao=1&liberaaba=true&chavepesquisa=$clprestador->fm06_codigo';
             (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_servicos.location.href='fum4_prestadores006.php?liberaaba=true&chavepesquisa=$clprestador->fm06_codigo';
             parent.document.formaba.profissionais.click();
           </script>";
   }
}

if (isset($alterar)) {
  if ($lSqlErro == true) {
     db_msgbox($sErroMsg);
     if ($clprestador->erro_campo != "") {
        echo "<script> document.form1.".$clprestador->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clprestador->erro_campo.".focus();</script>";
     };
  } else {
     db_msgbox($sErroMsg);
     echo "<script>
            parent.document.formaba.profissionais.disabled=false;
            (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_profissionais.location.href='fum4_prestadores005.php?liberaaba=true&chavepesquisa=$clprestador->fm06_codigo';
            parent.document.formaba.profissionais.click();
           </script>";
  }
}

if (isset($excluir)) {
   db_msgbox($sErroMsg);
   echo "<script>
          parent.document.formaba.profissionais.disabled=false;
          (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_profissionais.location.href='fum4_prestadores004.php?db_opcao=3&chavepesquisa=$clprestador->fm06_codigo';
          document.getElementById('pesquisar').click();
        </script>";
}

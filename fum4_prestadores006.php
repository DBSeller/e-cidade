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

$opcao = '';
db_postmemory($_POST);

$clservicos = new cl_servicosprestadores;
$clprestador  = new cl_prestadores;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

if (isset($liberaaba) && $liberaaba) {
   if ($opcao == 'alterar') {
      $sql = $clservicos->sql_query($_POST['fm08_codigo']);
      $result = $clservicos->sql_record($sql);

      if (pg_numrows($result) > 0) {
         db_fieldsmemory($result, 0);   
         $db_opcao = 2;
         $db_botao = true;                    
      }        
   } else if (isset($opcaoaba)) {
      $sql = $clprestador->sql_query($opcaoaba, 'fm06_codigo as fm08_prestador, fm06_numcgm as fm08_prestador_cgm, z01_nome as nome_prestador');
      $result = $clprestador->sql_record($sql);

      if (pg_numrows($result) > 0) {
          db_fieldsmemory($result, 0);
      }
      $db_opcao = 1; 
  } else {
      $sql = $clprestador->sql_query($chavepesquisa, 'fm06_codigo as fm08_prestador, fm06_numcgm as fm08_prestador_cgm, z01_nome as nome_prestador');
      $result = $clprestador->sql_record($sql);

      if (pg_numrows($result) > 0) {
          db_fieldsmemory($result, 0);
      }
      $db_opcao = 1;
  }
      $db_botao = true;
}

if ($opcao == 'excluir') {
    $lSqlErro = false;
    db_inicio_transacao();
    $clservicos->excluir($fm08_codigo);

    if ($clservicos->erro_status == 0) {
       $lSqlErro = true;
    }

    $sErroMsg = $clservicos->erro_msg;
    db_fim_transacao($lSqlErro);

    $sql = $clprestador->sql_query($fm08_prestador);
    $result = $clprestador->sql_record($sql);

    if (pg_numrows($result) > 0) {
        db_fieldsmemory($result, 0);
    }

    $db_opcao = 1;
    $db_botao = true;
}

if (isset($incluir)) {
   $lSqlErro = false;
   db_inicio_transacao();
   $clservicos->fm08_codigo = intval($fm08_codigo);
   $clservicos->fm08_prestador = intval($fm08_prestador);
   $clservicos->fm08_servico = intval($fm08_servico);
   $clservicos->fm08_situacao = $fm08_situacao;
   $clservicos->fm08_autoriza = $fm08_autoriza;    
   $clservicos->incluir($fm08_codigo);

   if ($clservicos->erro_status == 0) {
      $lSqlErro = true;
   }

   $sErroMsg = $clservicos->erro_msg;
   db_fim_transacao($lSqlErro);
    
   $db_opcao = 1;
   $db_botao = true;
   unset($fm08_codigo);
   unset($fm08_servico);
   unset($fm12_descricao);
   $fm08_situacao = 't';
   $fm08_autoriza = 'f';
}

if (isset($alterar)) {
    $lSqlErro = false;
    db_inicio_transacao();
    $clservicos->fm08_prestador = intval($fm08_prestador);
    $clservicos->fm08_servico = intval($fm08_servico);
    $clservicos->fm08_situacao = $fm08_situacao;
    $clservicos->fm08_autoriza = $fm08_autoriza;    
    $clservicos->alterar($fm08_codigo);

    if ($clservicos->erro_status == 0) {
       $lSqlErro = true;
    }

    $sErroMsg = $clservicos->erro_msg;
    db_fim_transacao($lSqlErro);

    $db_opcao = 1;
    $db_botao = true;
    $opcao = '';
    unset($fm08_codigo);
    unset($fm08_servico);
    unset($fm12_descricao);
    $fm08_situacao = 't';
    $fm08_autoriza = 'f';
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
      <?php require_once(modification("forms/db_frmservicosprestadores.php")); ?>
  	</td>
  </tr>
</table>
</body>
</html>
<script>
    function cleanCampo() {
        document.getElementById('fm08_servico').value = '';
        document.getElementById('servico_nome').value = '';
        document.getElementById('fm08_codigo').value = '';
    }
</script>
<?php
if (isset($chavepesquisa)) {
    echo "<script>parent.document.formaba.profissionais.disabled=false;</script>";
}

if (isset($incluir)) {
    if ($lSqlErro==true) {
       db_msgbox($sErroMsg);
       if($clservicos->erro_campo!=""){
         echo "<script> document.form1.".$clservicos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
         echo "<script> document.form1.".$clservicos->erro_campo.".focus();</script>";
       };
    } else {
       db_msgbox($sErroMsg);
       echo "<script>
              parent.document.formaba.servicos.disabled=false;
              (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_servicos.location.href='fum4_prestadores006.php?liberaaba=true&chavepesquisa=$clservicos->fm08_prestador';
              cleanCampo();
            </script>";
     }
  }

  if (isset($alterar)) {
     if ($lSqlErro==true) {
         db_msgbox($sErroMsg);
         if ($clservicos->erro_campo!="") {
            echo "<script> document.form1.".$clservicos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$clservicos->erro_campo.".focus();</script>";
         };
     } else {
         db_msgbox($sErroMsg);
     }
  }

  if ($opcao == 'excluir') {
    if ($lSqlErro==true) {
        db_msgbox($sErroMsg);
        if ($clservicos->erro_campo!="") {
           echo "<script> document.form1.".$clservicos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
           echo "<script> document.form1.".$clservicos->erro_campo.".focus();</script>";
        };
        echo "cleanCampo();";
    } else {
        db_msgbox($sErroMsg);
    }             
  }  

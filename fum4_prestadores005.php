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

/* Aba Profissionais */

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_postmemory($_POST);
db_postmemory($_GET);

if (!isset($opcao)) {
    $opcao = '';
}

$clprofissionaisprestadores = new cl_profissionaisprestadores;
$clprofissionais = new cl_profissionais;
$clprestador  = new cl_prestadores;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

if (isset($liberaaba) && $liberaaba) {

    if ($opcao == 'alterar') {
        $sql = $clprofissionaisprestadores->sql_query($_POST['fm07_codigo']);
        $result = $clprofissionaisprestadores->sql_record($sql);
        if (pg_numrows($result) > 0) {
            db_fieldsmemory($result, 0);
            $db_opcao = 2;
            $db_botao = true;
        }
    } else if (isset($opcaoaba) && !isset($chavepesquisa)) {
        $sql = $clprestador->sql_query($opcaoaba, 'fm06_codigo as fm07_prestador, fm06_numcgm as fm07_prestador_cgm, z01_nome as nome');
        $result = $clprestador->sql_record($sql);
        if (pg_numrows($result) > 0) {
            db_fieldsmemory($result, 0);
        }
        $db_opcao = 1;
    } else if (isset($chavepesquisa)) {
        $sql = $clprestador->sql_query($chavepesquisa, 'fm06_codigo as fm07_prestador, fm06_numcgm as fm07_prestador_cgm, z01_nome as nome');
        $result = $clprestador->sql_record($sql);

        if (!$result) {
           db_msgbox('Erro ao buscar informações do prestador '.pg_last_error());
        }

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
    $clprofissionaisprestadores->excluir($_POST['fm07_codigo']);

    if ($clprofissionaisprestadores->erro_status==0) {
       $lSqlErro=true;
    }

    $sErroMsg = $clprofissionaisprestadores->erro_msg;
    db_fim_transacao($lSqlErro);

    $sql = $clprestador->sql_query($fm07_prestador);
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
    $clprofissionaisprestadores->fm07_prestador = intval($fm07_prestador);
    $clprofissionaisprestadores->fm07_situacao = $fm07_situacao; 
    $clprofissionaisprestadores->fm07_profissional = intval($fm07_profissional);
    $clprofissionaisprestadores->incluir($fm07_codigo);
  
    if ($clprofissionaisprestadores->erro_status == 0) {
        $lSqlErro = true;
    }

    $sErroMsg = $clprofissionaisprestadores->erro_msg;
    db_fim_transacao($lSqlErro);

    $db_opcao = 1;
    $db_botao = true;
    unset($fm07_codigo);
    unset($fm07_profissional);
    unset($fm15_nome);
    unset($fm15_cpf);
    unset($fm15_cbo);
    unset($rh70_descr);
    unset($fm15_regprof);
    unset($fm15_orgaoemissor);
    unset($sd51_v_descricao);
    $fm07_situacao = 't';
}

if (isset($alterar)) {
    $lSqlErro = false;
    db_inicio_transacao();
    $clprofissionaisprestadores->fm07_profissional = intval($_POST['fm07_profissional']);
    $clprofissionaisprestadores->fm07_situacao = $_POST['fm07_situacao']; 
    $clprofissionaisprestadores->alterar($_POST['fm07_codigo']);

    if ($clprofissionaisprestadores->erro_status == 0) {
       $lSqlErro = true;
    }

    $sErroMsg = $clprofissionaisprestadores->erro_msg;
    db_fim_transacao($lSqlErro);

    $db_opcao = 1;
    $db_botao = true;
    $opcao = '';
    unset($fm07_codigo);
    unset($fm07_profissional);
    unset($fm15_nome);
    unset($fm15_cpf);
    unset($fm15_cbo);
    unset($rh70_descr);
    unset($fm15_regprof);
    unset($fm15_orgaoemissor);
    unset($sd51_v_descricao);
    $fm07_situacao = 't';
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
                    <?php require_once(modification("forms/db_frmprofissionaisprestadores.php")); ?>
              	</td>
            </tr>
        </table>
    </body>
</html>
<script>
    function cleanCampo(){
        document.getElementById('fm07_codigo').value = '';
        document.getElementById('fm07_profissional').value = '';
        document.getElementById('fm15_nome').value = '';
        document.getElementById('fm15_cpf').value = '';
        document.getElementById('fm15_cbo').value = '';
        document.getElementById('rh70_descr').value = '';
        document.getElementById('fm15_regprof').value = '';
        document.getElementById('fm15_orgaoemissor').value = '';
        document.getElementById('sd51_v_descricao').value = '';
    }
</script>
<?php
  if (isset($chavepesquisa)) {
      echo "<script>parent.document.formaba.profissionais.disabled=false;</script>";
  }

  if (isset($incluir)) {
      if ($lSqlErro == true) {
         db_msgbox($sErroMsg);
         if ($clprofissionaisprestadores->erro_campo != "") {
            echo "<script> document.form1.".$clprofissionaisprestadores->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$clprofissionaisprestadores->erro_campo.".focus();</script>";
         }
      } else {
         db_msgbox($sErroMsg);
         echo "<script>
                parent.document.formaba.servicos.disabled=false;
                (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_servicos.location.href='fum4_prestadores006.php?liberaaba=true&chavepesquisa=$clprofissionaisprestadores->fm07_prestador';
                cleanCampo();
              </script>";
      }
  }

  if (isset($alterar)) {
      if ($lSqlErro == true) {
          db_msgbox($sErroMsg);
          if ($clprofissionaisprestadores->erro_campo != "") {
             echo "<script> document.form1.".$clprofissionaisprestadores->erro_campo.".style.backgroundColor='#99A9AE';</script>";
             echo "<script> document.form1.".$clprofissionaisprestadores->erro_campo.".focus();</script>";
          }
      } else {
          db_msgbox($sErroMsg);
      }
  }

  if ($opcao == 'excluir') {
      if ($lSqlErro == true) {
          db_msgbox($sErroMsg);
          if ($clprofissionaisprestadores->erro_campo != "") {
            echo "<script> document.form1.".$clprofissionaisprestadores->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$clprofissionaisprestadores->erro_campo.".focus();</script>";
          }
          echo "cleanCampo();";
      } else {
          db_msgbox($sErroMsg);
      }
  }

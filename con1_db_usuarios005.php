<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_db_usuacgm_classe.php");
require_once("classes/db_db_userinst_classe.php");
require_once("classes/db_db_depusu_classe.php");

$cldb_usuarios = new cl_db_usuarios;
$cldb_usuacgm  = new cl_db_usuacgm;
$cldb_userinst = new cl_db_userinst;
$cldb_depusu   = new cl_db_depusu;

db_postmemory($HTTP_POST_VARS);

$db_opcao = 22;
$db_botao = false;
$sMensagens = "configuracao.configuracao.con1_db_usuarios";

if (isset($alterar)) {

  try {

    $sqlerro = false;
    db_inicio_transacao();

    $oCgmFisico = new CgmFisico($z01_numcgm);

    if (!$oCgmFisico->getCodigo()) {

      $cldb_usuarios->erro_campo = "z01_numcgm";
      throw new Exception(_M("{$sMensagens}.campo_obrigatorio", (Object) array("sCampo" => "CGM")));
    }

    if(count($instit) == 0) {

      throw new Exception(_M("{$sMensagens}.instituicao_nao_selecionada"));
    }

    if ($administrador == 1 && $usuarioativo == 3) {

      $cldb_usuarios->erro_campo = "usuarioativo";
      throw new Exception(_M("{$sMensagens}.situacao_usuario_invalida"));
    }

    if ($usuarioativo != 3) {

      $rsUsuario = $cldb_usuarios->sql_record( $cldb_usuarios->sql_query_file($id_usuario, "usuarioativo") );

      if (!$rsUsuario || !pg_num_rows($rsUsuario)) {
        throw new Exception(_M("{$sMensagens}.usuario_invalido"));
      }

      if (db_utils::fieldsMemory($rsUsuario, 0)->usuarioativo == 3 && empty($senha)) {

        $cldb_usuarios->erro_campo = "senha";
        throw new Exception( _M("{$sMensagens}.campo_obrigatorio", (Object) array("sCampo" => "Senha")) );
      }


    } else {

      /**
       * Validações do CGM caso seja selecionada a situação 3
       */
      if (!$oCgmFisico->isFisico()) {
        throw new Exception(_M("{$sMensagens}.cgm_pessoa_fisica"));
      }

      if (!$oCgmFisico->getCpf()) {
        throw new Exception( _M("{$sMensagens}.campo_cgm_obrigatorio", (Object) array("sCampo" => "CPF")) );
      }

      if (!$oCgmFisico->getEmail()) {
        throw new Exception( _M("{$sMensagens}.campo_cgm_obrigatorio", (Object) array("sCampo" => "E-mail")) );
      }

      if (!$oCgmFisico->getDataNascimento()) {
        throw new Exception( _M("{$sMensagens}.campo_cgm_obrigatorio", (Object) array("sCampo" => "Data de Nascimento")) );
      }

      $senha = "";
    }

    if ((isset($senha) && $senha != "") || $usuarioativo == 3) {
      $cldb_usuarios->senha = Encriptacao::encriptaSenha( $senha );
    } else {
      unset($senha);
      unset($GLOBALS["HTTP_POST_VARS"]["senha"]);
    }

    $cldb_usuarios->nome = $z01_nome;
    $cldb_usuarios->datatoken = date("Y-m-d");
    $cldb_usuarios->alterar($id_usuario);

    if ($cldb_usuarios->erro_status == 0) {
      $senha = '';
      $sqlerro=true;
    }

    $erro_msg = $cldb_usuarios->erro_msg;

    if ($sqlerro == false) {

      $cldb_usuacgm->excluir($id_usuario);
      if ($cldb_usuacgm->erro_status == 0) {
        $sqlerro=true;
        $erro_msg = $cldb_usuacgm->erro_msg;
      }
    }

    if ($sqlerro==false){
      $cldb_userinst->excluir(null, null, "id_usuario=$id_usuario");
      if($cldb_userinst->erro_status==0){
        $sqlerro=true;
        $erro_msg = $cldb_userinst->erro_msg;
      }
    }

    if ($sqlerro==false){
      $cldb_usuacgm->id_usuario=$id_usuario;
      $cldb_usuacgm->cgmlogin=$z01_numcgm;
      $cldb_usuacgm->incluir($id_usuario);
      if($cldb_usuacgm->erro_status==0){
        $sqlerro=true;
        $erro_msg = $cldb_usuacgm->erro_msg;
      }
    }

    if ($sqlerro==false){
      for($i = 0;$i < sizeof($instit);$i++){
        $cldb_userinst->id_usuario=$id_usuario;
        $cldb_userinst->id_instit=$instit[$i];
        $cldb_userinst->incluir();
        if($cldb_userinst->erro_status==0){
          $sqlerro=true;
          $erro_msg = $cldb_userinst->erro_msg;
        }
      }
    }

    db_fim_transacao($sqlerro);

    if ($usuarioativo == 3) {

      $oUsuarioSistema = new UsuarioSistema($id_usuario);

      try {
        $oUsuarioSistema->enviarAtivacaoSenha();
      } catch(Exception $e) { }
    }

  } catch (Exception $e) {

    db_fim_transacao(true);
    $db_opcao = 2;
    $db_botao = true;
    $sqlerro  = true;
    $erro_msg = $e->getMessage();
  }

  /**
   * Limpa o cache do usuário que esta sendo alterado
   */
  DBMenu::limpaCache($id_usuario);

} else if (isset($chavepesquisa)) {

  $db_opcao = 2;
  $db_botao = true;

  $result_cgm = $cldb_usuacgm->sql_record($cldb_usuacgm->sql_query($chavepesquisa));

  if ($cldb_usuacgm->numrows>0){
  	db_fieldsmemory($result_cgm,0);
  }

  $result = $cldb_usuarios->sql_record($cldb_usuarios->sql_query($chavepesquisa));

  if ($cldb_usuarios->numrows>0) {

  	db_fieldsmemory($result,0);
  	$senha = "";
  }
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
  <body class="body-default">
  	<?php
  	  include("forms/db_frmdb_usuarios.php");
  	?>
  </body>
</html>
<?php

if (isset($alterar)) {
  if ($sqlerro == true) {

    db_msgbox($erro_msg);
    if ($cldb_usuarios->erro_campo != "") {
      echo "<script> document.form1.".$cldb_usuarios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_usuarios->erro_campo.".focus();</script>";
    };

  } else {
    db_msgbox($erro_msg);
  }
}

if (isset($chavepesquisa)) {

 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.db_depusu.disabled=false;
		 parent.document.formaba.permemp.disabled=false;
		 parent.document.formaba.permmenu.disabled=false;
         top.corpo.iframe_db_depusu.location.href='con1_db_depusu001.php?id_usuario=".@$id_usuario."&nome=".@addslashes($z01_nome)."';
		 top.corpo.iframe_permemp.location.href='con1_db_permempusu001.php?id_usuario=".@$id_usuario."';
		 top.corpo.iframe_permmenu.location.href='con4_permitensusu.php?usuario=".@$id_usuario."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('db_depusu');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}

if($db_opcao==22||$db_opcao==33){

  echo "<script>document.form1.pesquisar.click();</script>";
}

?>
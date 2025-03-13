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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_conn.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/label/rotulo.php"));
require_once(modification("std/label/RotuloDB.php"));
require_once(modification("std/label/RotuloCampoDB.php"));
require_once(modification("std/label/RotuloBasica.php"));
require_once(modification("std/label/RotuloXML.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("model/configuracao/Encriptacao.model.php"));
require_once(modification("model/configuracao/UsuarioSistema.model.php"));
require_once(modification("model/configuracao/Preferencia.model.php"));
require_once(modification("model/configuracao/PreferenciaCliente.model.php"));

require_once(modification("std/Modification.php"));
Modification::find();

$sStringFunction  = 'require_once("scripts/jquery-2.1.1.min.js");
require_once("scripts/prototype.js");
require_once("ext/javascript/alertify/themes/alertify.core.css");
require_once("ext/javascript/alertify/themes/alertify.default.css");
require_once("ext/javascript/alertify/src/alertify.js");
__alert = alert;
alert = function(sMensagem, tipo) {

  if (!top.alertify) {
     return __alert(sMensagem);
  }
  sMensagem = "<b>" + sMensagem.replace(/\n/g, "<br>") + "</b>";
  var iTimeFade = 300000;
  switch (tipo) {
    case "sucess":

      alertify.success(sMensagem, iTimeFade);
      break;

    case "error":

      alertify.error(sMensagem, iTimeFade);
      break;
    default:

      top.alertify.warning(sMensagem, iTimeFade);
      break;
  }
}';
$stdClass = new db_stdClass();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if (isset($sAuth)) {
  parse_str( base64_decode($sAuth) );
}

if (isset($_GET["logOut"])) {

  $sScriptsConteudo = file_get_contents("scripts/scripts.js");
  $sScriptsConteudo = str_replace("\n\n".$sStringFunction, "", $sScriptsConteudo);
   file_put_contents("scripts/scripts.js", $sScriptsConteudo);
   file_put_contents("/tmp/scripts.js", $sScriptsConteudo."bla");

}
define( 'MENSAGEM', 'configuracao.configuracao.abrir.' );

$DB_SERVIDOR = $_GET["servidor"];
$DB_BASE     = $_GET["base"];
$DB_PORTA    = $_GET["port"];
$DB_USUARIO  = $_GET["user"];
$DB_SENHA    = '';
$_SESSION["TESTING"] = "ON";

if(!($conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {


  echo pg_last_error();
  exit;
}

db_query($conn, "select fc_startsession()");
session_start();

$DB_login = $_GET["db_login"];

/**
 * Habilita acesso apenas para usuarios do e-cidade usuext = 0 negando para:
 * 1 - Usu?rio Externo
 * 2 - Perfil
 */
$sSql  = "select id_usuario,           \n";
$sSql .= "       senha,                \n";
$sSql .= "       administrador,        \n";
$sSql .= "       usuarioativo          \n";
$sSql .= "  from db_usuarios           \n";
$sSql .= " where usuarioativo <> '0'   \n";
$sSql .= "   and usuext not in (1,2)   \n";
$sSql .= "   and login = '{$DB_login}' \n";
$result = db_query( $conn, $sSql );

$sSql    = "select * from db_depusu";
$result1 = db_query( $conn, $sSql ) or die($sSql);
$oUsuario = db_utils::fieldsMemory($result, 0);
  /**
   * Desregistramos a variavel que controla as tentativas de acesso
   */
$_SESSION["DB_acessado"] = '';

db_putsession( "DB_login",         $DB_login);
db_putsession( "DB_id_usuario",    $oUsuario->id_usuario);
db_putsession( "DB_administrador", $oUsuario->administrador);

/**
 * Realiza a busca das preferências do usuário.
 */
$oUsuarioSistema = new UsuarioSistema( $oUsuario->id_usuario);
$sPreferencias   = serialize($oPreferenciaUsuario = $oUsuarioSistema->getPreferenciasUsuario());
db_putsession("DB_preferencias_usuario", base64_encode($sPreferencias));

if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ){
  db_putsession("DB_ip",$_SERVER["HTTP_X_FORWARDED_FOR"]);
}else{
  db_putsession("DB_ip",$HTTP_SERVER_VARS["REMOTE_ADDR"]);
}

db_putsession("DB_base",     $DB_BASE);
db_putsession("DB_NBASE",    $DB_BASE);
db_putsession("DB_servidor", $DB_SERVIDOR);
db_putsession("DB_porta",    $DB_PORTA);
db_putsession("DB_senha",    $DB_SENHA);
db_putsession("DB_user",     $DB_USUARIO);

include(modification("classes/db_db_versao_classe.php"));
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$versao = $_GET["version"];
if (defined("ECIDADE_EXTENSION_VERSION")) {
  $versao =  \ECidade\V3\Extension\Manager::isEnabled('Desktop', $DB_login) ? 3 : 2;
}
$cldb_versao = new cl_db_versao;
$result      = $cldb_versao->sql_record($cldb_versao->sql_query(null,"db30_codversao,db30_codrelease","db30_codver desc limit 1"));

if( $cldb_versao->numrows == 0 ){

  $db30_codversao  = "1";
  $db30_codrelease = "1";
}else{
  db_fieldsmemory($result,0);
}

include(modification('libs/db_acessa.php'));

pg_close($conn);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - Abrindo E-cidade...</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
  wname      = 'wname' + Math.floor(Math.random() * 10000);
  var nav    = navigator.appName;
  var ver    = navigator.appVersion;
  var age    = navigator.userAgent;
  sizeWidth  = screen.availWidth;
  sizeHeight = screen.availHeight;
  var sNomeArquivo = '<?=($versao == 2 ? 'inicio_test.php': 'inicio.php');?>';
  location.href= sNomeArquivo+'?uso=<?=$DB_login?>&janelaWidth='+sizeWidth+'&janelaHeight='+sizeHeight;
</script>
</head>
<body bgcolor="#CCCCCC" onLoad="window.blur()">
</body>
</html>
<script type="text/javascript">
</script>
<?php

/**
 * replace no scripts para o alert
 */

 if ($versao == 2) {

   $sScriptsConteudo = file_get_contents("scripts/scripts.js");
   if (strpos($sScriptsConteudo, 'alert = function') === false) {

     $sScriptsConteudo .= "\n\n{$sStringFunction}";
     file_put_contents("scripts/scripts.js", $sScriptsConteudo);
     file_put_contents("/tmp/scripts.js", $sScriptsConteudo);
   }
 }

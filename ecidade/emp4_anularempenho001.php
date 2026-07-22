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
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("classes/db_orcdotacao_classe.php"));
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/db_empanulado_classe.php"));
require_once(modification("classes/db_empanuladoele_classe.php"));
require_once(modification("classes/db_empelemento_classe.php"));
require_once(modification("classes/db_empautoriza_classe.php"));
require_once(modification("classes/db_pcprocitem_classe.php"));
require_once(modification("classes/db_orcreservaaut_classe.php"));
require_once(modification("classes/db_orcreserva_classe.php"));
require_once(modification("classes/db_orcreservasol_classe.php"));
require_once(modification("classes/db_empparametro_classe.php"));
require_once(modification("classes/db_empanuladotipo_classe.php"));

$clempempenho    = new cl_empempenho;
$clempanulado    = new cl_empanulado;
$clempanuladoele = new cl_empanuladoele;
$clempelemento   = new cl_empelemento;
$clorcdotacao    = new cl_orcdotacao;
$clempautoriza   = new cl_empautoriza;
$clempparametro  = new cl_empparametro;
$db_opcao        = 22;
if(isset($numemp)){

  $db_opcao = 1;
  $db_botao = true;
}

//Checa parametro e mostra alerta de confirmacao de data
$clconparametro  = new cl_conparametro();
$rsconparametro  = $clconparametro->sql_record($clconparametro->sql_query_file(null, "c90_confirmadata"));
$conparametro    = db_utils::fieldsMemory($rsconparametro, 0);
if($conparametro->c90_confirmadata == 't'){
    $data = date('d/m/y', db_getsession('DB_datausu'));
    echo "<db-alertaconfirmadatafinanceiro data=" . $data . "></db-alertaconfirmadatafinanceiro>";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
<script type="text/javascript" src="scripts/components/AlertaConfirmaDataFinanceiro.js"></script>

<link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
      rel="stylesheet"/>
<link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
      rel="stylesheet"/>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>
.saldocentavos {background-color:#d1f07c;}
</style>
</head>
<body style="margin-top: 20px;" >
  <div class="container">
	<?php
  require_once(modification(Modification::getFile("forms/db_frmempanularempenho.php")));
  ?>
  </div>
</body>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
if ($db_opcao == 22) {
	echo "<script>document.form1.pesquisar.click();</script>";
} else {
  echo "<script>js_consultaEmpenho({$numemp});</script>";
}
?>

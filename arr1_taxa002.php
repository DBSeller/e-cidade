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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_taxa_classe.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
db_postmemory($_GET);
$cltaxa   = new cl_taxa;
$db_opcao = 22;
$db_botao = false;
if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $cltaxa->alterar($ar36_sequencial);
  db_fim_transacao();
} else if(isset($chavepesquisa)) {

   $db_opcao = 2;
   $result   = $cltaxa->sql_record($cltaxa->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $db_botao = true;
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript"src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript"src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript"src="scripts/numbers.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class='body-default' >
<div class ='container'>
  <?php
	  include("forms/db_frmtaxa.php");
	?>
</div>
</body>
</html>
<?
if (isset($alterar)) {

  if ($cltaxa->erro_status=="0") {

    $cltaxa->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($cltaxa->erro_campo!="") {

      echo "<script> document.form1.".$cltaxa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltaxa->erro_campo.".focus();</script>";
    }
  }else{
    $cltaxa->erro(true,false);
    db_redireciona("arr1_taxa002.php?liberaaba=true&chavepesquisa=".$cltaxa->ar36_sequencial);
  }
}
if( ($db_opcao == 22) ){

	 //echo $chavepesquisa; die();
  echo "<script>document.form1.pesquisar.click(); </script>";
}

if (isset($chavepesquisa)) {

  if ($ar36_perc > 0) {

    echo "<script>
             js_tipoCobranca(2);
             $('tipo_cobranca').options.length = 0;
             $('tipo_cobranca').options[0]     = new Option('Percentual de Débito', '2');
             $('tipo_cobranca').options[1]     = new Option('Valor Fixado', '1');
          </script>";

  } else {

    echo "<script>
             js_tipoCobranca(1);
             $('tipo_cobranca').options.length = 0;
             $('tipo_cobranca').options[0]     = new Option('Valor Fixado', '1');
             $('tipo_cobranca').options[1]     = new Option('Percentual de Débito', '2');
          </script>";
  }
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.favorecido.disabled = false;
         top.corpo.iframe_favorecido.location.href='arr1_taxaFavorecido001.php?ar36_sequencial=".@$chavepesquisa."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('favorecido');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";

}


?>
<script>
js_tabulacaoforms("form1","ar36_grupotaxa",true,1,"ar36_grupotaxa",true);
</script>
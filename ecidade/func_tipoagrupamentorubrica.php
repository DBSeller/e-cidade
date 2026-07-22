<?php
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("classes/db_tipoagrupamentorubrica_classe.php");

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$oPost = db_utils::postMemory($_POST);

$daoTipoAgrupamento = new cl_tipoagrupamentorubrica;
$daoTipoAgrupamento->rotulo->label("rh238_sequencial");
$daoTipoAgrupamento->rotulo->label("rh238_descricao");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
<form name="form2" method="post" action="" class="container">

  <fieldset>
    <legend>Dados para Pesquisa</legend>
    <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
      <tr>
        <td><label for="chave_rh238_sequencial"><?php echo $Lrh238_sequencial ?></label></td>
        <td>
          <?php db_input("rh238_sequencial", 10, $Irh238_sequencial, true, "text", 4, "", "chave_rh238_sequencial"); ?>
        </td>
      </tr>
      <tr>
        <td><label for="rh238_descricao"><?php echo $Lrh238_descricao ?></label></td>
        <td>
          <?php
          $rh238_descricao = !empty($rh238_descricao) ? htmlentities(stripslashes($rh238_descricao), ENT_QUOTES, 'ISO-8859-1') : '';
          db_input("rh238_descricao", 50, $Irh238_descricao, true, "text", 4);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>

  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
  <input name="limpar" type="reset" id="limpar" value="Limpar" >
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tipoagrupamentorubrica.hide();">
</form>
<?php
if(!isset($pesquisa_chave)) {

  $aCampos = array(
    "rh238_sequencial",
    "rh238_descricao"
  );

  $campos = implode(',', $aCampos);

  if ( !empty($oPost->chave_rh238_sequencial) ) {
    $sSql = $daoTipoAgrupamento->sql_query($oPost->chave_rh238_sequencial, $campos, "rh238_sequencial");
  } else if (!empty($oPost->rh238_descricao)) {
    $sSql = $daoTipoAgrupamento->sql_query(null, $campos, "rh238_sequencial", " rh238_descricao ilike '$oPost->rh238_descricao%' ");
  }else{
    $sSql = $daoTipoAgrupamento->sql_query(null, $campos, "rh238_sequencial");
  }

  $repassa = array();
  if(isset($chave_rh238_sequencial)) {

    $repassa = array(
      "chave_rh238_sequencial" => $chave_rh238_sequencial,
      "rh238_descricao"    => $oPost->rh238_descricao,
    );
  }

  echo '<div class="container">';
  echo '  <fieldset>';
  echo '    <legend>Resultado da Pesquisa</legend>';
  db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
  echo '  </fieldset>';
  echo '</div>';
} else {

  if($pesquisa_chave != null && $pesquisa_chave != ""){

    $sSql        = $daoTipoAgrupamento->sql_query(null, "*", null, "rh238_sequencial = {$pesquisa_chave}");
    $rsResultado = $daoTipoAgrupamento->sql_record($sSql);
    if($daoTipoAgrupamento->numrows != 0){

      db_fieldsmemory($rsResultado, 0);
      echo "<script>" . $funcao_js . "('$rh238_descricao', false);</script>";
    } else {
      echo "<script>" . $funcao_js . "('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
    }
  } else {
    echo "<script>" . $funcao_js . "('',false);</script>";
  }
}
?>
<script>
  js_tabulacaoforms("form2","chave_rh238_sequencial",true,1,"chave_rh238_sequencial",true);
</script>
</body>
</html>

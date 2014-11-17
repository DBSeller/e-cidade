<?php
  require_once("libs/db_stdlib.php");
  require_once("libs/db_utils.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  $clrotulo = new rotulocampo;
  $clrotulo->label("rh01_reajusteparidade");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, estilos.css, geradorrelatorios.js");
    ?>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">

    <form action="" method="post" class="container">
      <fieldset>
        <legend>Relatório por Tipo de Reajuste</legend>

        <table class="form-container">
          <tr>
            <td>
              <label><?=$Lrh01_reajusteparidade?> </label>
            </td>
            <td>
              <select name="tiporeajuste" id="tiporeajuste">
                <option value="f">Real</option>
                <option value="t">Paridade</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      <table class="container">
        <tr>
          <td align="center">
            <input type="button" value="Processar" onclick="js_processar()" />
          </td>
        </tr>
      </table>
    </form>


  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>

  <script>

    function js_processar(){

      var aParametros = new Array(); 
      var sVariavel   = $F('tiporeajuste');
      var sDescricao  = '$sTipoReajuste';                                     
      var objVariavel = new js_criaObjetoVariavel(sDescricao,sVariavel);  
      aParametros.push( objVariavel );

      js_imprimeRelatorio(28, js_downloadArquivo, Object.toJSON(aParametros));

      return false;
    }

  </script>
  </body>
</html>
<?php










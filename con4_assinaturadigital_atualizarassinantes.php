<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/DBDate.php"));

$db_opcao_assinante = 1;
$js_script  = "class='field-size9'";

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php db_app::load("estilos.css, grid.style.css"); ?>
</head>
<body bgcolor="#cccccc" style='margin-top: 30px;'>
  <div class="container">
    <fieldset>
      <legend>Assinante</legend>
      <table class="form-container">
        <tr>
          <td>
            <label>Nome:</label>
          </td>
          <td>
            <?php db_input('atualizar_assinante', 20, '', true, 'text', $db_opcao_assinante, $js_script) ?>
          </td>
        </tr>
        <tr>
          <td>
            <label>CPF/CNPJ:</label>
          </td>
          <td>
            <?php db_input('atualizar_cpf_cnpj', 20, '', true, 'text', $db_opcao_assinante, $js_script); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" id="btnPesquisarUsuarioGridAssinantes" value="Pesquisar Usuario" />
    <input type="button" id="btnAdicionarGridAssinantes" value="Adicionar" />
  </div>
  <div class="container wrapper-documentos-assinar">
    <fieldset>
      <legend>Assinantes</legend>
      <div id="gridAssinantes"></div>
    </fieldset>
  </div>
</body>
</html>
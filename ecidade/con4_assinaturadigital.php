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
$assinantes = [0=>'Selecione'];
$assinado   = [0=>'Não', 1 => 'Sim'];
$js_script  = "";

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php db_app::load("estilos.css, grid.style.css"); ?>
  <style type="text/css">
    div.wrapper-modo-visibilidade {
      position: absolute;
      right: 0;
      margin-right: 14px;
    }
    div.wrapper-modo-visibilidade div#modoVisibilidade{
      margin-top: 5px;
      padding: 10px;
      background-color: white;
      text-align: center;
      text-transform: uppercase;
    }

    .metadata {
      white-space: pre;
    }
  </style>

</head>
<body bgcolor="#cccccc" style='margin-top: 30px;'>
  <div class="wrapper-modo-visibilidade">
    <input type="button" id="btnAlterarModo" value="Alterar Modo" />
    <div id="modoVisibilidade">
      <span>Assinante</span>
    </div>
  </div>
  <div class="container">
    <fieldset>
      <legend>Assinante</legend>
      <table class="form-container">
        <tr>
          <td>
            <label>Nome:</label>
          </td>
          <td>
            <?php db_select('assinante', $assinantes, true, $db_opcao_assinante, $js_script = "class='field-size9'") ?>
          </td>
        </tr>
        <tr>
          <td>
            <label>CPF/CNPJ:</label>
          </td>
          <td>
            <?php db_input('cpf_cnpj', 20, 1, true, 'text', 3, $js_script = "class='field-size9'"); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" id="btnProcurar" value="Buscar Arquivos" />
    <input type="button" id="btnAssinar" value="Assinar" />
    <input type="button" id="btnUpload" value="Novo Arquivo" disabled="disabled" />
  </div>
  <div class="container wrapper-documentos-assinar">
    <fieldset>
      <legend>Arquivos para assinar</legend>
      <table id="filtroAssinado">
        <tr>
          <td style="display: none;">
            <label><strong>Assinados:</strong></label>
          </td>
          <td style="display: none;">
            <?php db_select('assinado', $assinado, true, 1) ?>
          </td>
        </tr>
      </table>
      <div id="gridArquivosAssinar"></div>
    </fieldset>
  </div>
</body>
</html>
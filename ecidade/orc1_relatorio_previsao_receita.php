<?php
require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <title>DBSeller Informática Ltda</title>
    <style>
        .DBJanelaIframe {
            top: 50px;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<div class="container">
    <form id="formulario_unidade" style="margin-top: 5%; padding: 2%;">
        <fieldset style="width: 800px;">
            <legend>Relatório da Previsão de Receita</legend>
            <table style="margin: auto;">
                <tbody>
                <tr>
                    <td>
                        <label for="estrutural">
                            <strong>
                                <a class="DBAncora" onclick="pesquisar()">Unidade Orçamentária</a>
                            </strong>
                        </label>
                    </td>
                    <td>
                        <input name="codigo" id="codigo" class="readonly" disabled style="width: 50px;" title="Código">
                    </td>
                    <td>
                        <input id="descricao" title="Descrição" class="readonly" disabled style="width: 500px;">
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>
        <input type="submit" value="Emitir" id="emitir" style="margin: auto;">
    </form>
</div>
<?php db_menu(); ?>
<script>
  var inputCodigo = document.getElementById('codigo');
  var inputDescricao = document.getElementById('descricao');
  var botaoEmitir = document.getElementById('emitir');

  botaoEmitir.addEventListener('click', emitir);

  function emitir(event) {
    event.preventDefault();

    js_divCarregando('Emitindo Relatório', 'loading_message');

    var formData = new FormData();
    formData.append('acao', 'emitir');
    formData.append('unidade', inputCodigo.value);

    return fetch('orc1_relatorio_previsao_receita.RPC.php', {
      method: 'POST',
      body: formData,
      credentials: 'include',
    }).then(response => response.json()).then(response => {
      if (response.erro) {
        return alert(response.mensagem);
      }

      var download = new DBDownload();
      download.addFile(response.arquivo, response.nomeArquivo);
      download.show();
    }).finally(() => js_removeObj('loading_message'));
  }

  function pesquisar() {
    js_OpenJanelaIframe('', 'db_iframe_orcunidade',
        'func_db_config_orcunidade.php?funcao_js=parent.' + obterDados.name +
        '|o41_orgao|o41_unidade|o40_descr|o41_descr',
        'Pesquisa', true);
  }

  function obterDados(chave1, chave2, chave3, chave4) {
    var orgao = chave1.padStart(2, '0');
    var unidade = chave2.padStart(2, '0');
    var codigoTribunal = orgao + unidade;
    var orgaoUnidade = chave3 + ' / ' + chave4;

    inputCodigo.value = codigoTribunal;
    inputDescricao.value = orgaoUnidade;
    db_iframe_orcunidade.hide();
  }
</script>
</body>
</html>

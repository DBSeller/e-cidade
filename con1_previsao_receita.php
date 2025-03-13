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
    <link rel="stylesheet" type="text/css" href="estilos/avaliacao.css">
    <link rel="stylesheet" type="text/css" href="estilos/awesomplete.css">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputCheckboxRadio.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBRadio.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewResposta.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewPergunta.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewGrupoPerguntas.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewFormulario.classe.js"></script>
    <script src="scripts/awesomplete.js"></script>
    <script src="scripts/classes/avaliacao/DBAutoComplete.js"></script>
    <title>DBSeller Informática Ltda</title>
    <style>
        .DBJanelaIframe {
            top: 60px;
        }
    </style>
</head>
<body>
<div class="container">
    <form id="formulario_conta" style="margin-top: 5%;">
        <fieldset>
            <legend>Previsão de Receita</legend>
            <table>
                <tbody>
                <tr>
                    <td>
                        <label for="estrutural">
                            <strong>
                                <a class="DBAncora" href="#" id="ancora_conta">Natureza da Receita:</a>
                            </strong>
                        </label>
                    </td>
                    <td>
                        <input name="estrutural" id="estrutural" lang="c60_estrut" class="readonly" disabled style="width: 105px;">
                        <input type="hidden" name="codigo_conta" id="codigo_conta" lang="c60_codcon">
                    </td>
                    <td>
                        <input id="descricao_conta" lang="c60_descr" title="Estrutural">
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>
    </form>
    <fieldset style="margin-top: 5%; padding: 2%" id="fieldset_formulario">
        <legend>Formulário de Cadastro da Previsão de Receita</legend>
        <div id="formulario"></div>
    </fieldset>
    <input type="submit" value="Salvar" onclick="salvar()" id="salvar" style="display: none; margin: auto">
</div>
<?php db_menu(); ?>
<script>
  const ano = 2019;
  const ancoraConta = document.getElementById('ancora_conta');
  const inputEstrutural = document.getElementById('estrutural');
  const inputCodigoConta = document.getElementById('codigo_conta');
  const inputDescricaoConta = document.getElementById('descricao_conta');
  const formularioConta = document.getElementById('formulario_conta');
  const formulario = document.getElementById('formulario');
  const fieldsetFormulario = document.getElementById('fieldset_formulario');

  var viewAvaliacao;
  var preenchimento;
  var autoCompleteUnidade;

  fieldsetFormulario.style.display = 'none';

  const lookUp = new DBLookUp(ancoraConta, inputEstrutural, inputDescricaoConta, {
    'sArquivo': 'func_conplanoorcamento.php',
    'sLabel': 'Pesquisar Conta',
    'aParametrosAdicionais': ['previsao=true', 'sSomenteEstrutural=4', 'ano=' + ano],
  });

  lookUp.setCamposAdicionais(['c60_codcon']);
  lookUp.setCallBack('onChange', carregar);
  lookUp.setCallBack('onClick', carregar);

  function carregar(params) {
    inputCodigoConta.value = params[2];

    if (!inputCodigoConta.value) {
      return alert('É necessário informar uma conta.');
    }

    js_divCarregando('Buscando Formulário', 'loading_message');

    autoCompleteUnidade = null;

    const formData = new FormData(formularioConta);
    formData.append('acao', 'buscarAvaliacao');
    formData.append('ano', ano.toString());

    return fetch('con4_previsao_receita.RPC.php', {
      method: 'POST',
      body: formData,
      credentials: 'include',
    }).then(response => response.json()).then(response => {
      if (response.error) {
        return alert(response.mensagem);
      }

      preenchimento = response.preenchimento;
      fieldsetFormulario.style.display = 'block';
      document.getElementById('salvar').style.display = 'block';
      formulario.innerHTML = '';
      viewAvaliacao = DBViewFormulario.makeFromObject(response.formulario).setEvent('changeStep', changeStep).show(formulario);
    }).finally(() => js_removeObj('loading_message'));
  }

  function changeStep() {
    const elemento = document.querySelector('input[identificador=unidadeOrcamentaria]');

    if (elemento && autoCompleteUnidade === null) {
      autoCompleteUnidade = new DBAutoComplete(elemento, 'con4_previsao_receita.RPC.php?acao=buscarUnidadeOrcamentaria');
    }
  }

  function salvar() {
    const grupoAtual = viewAvaliacao.getStatus().grupoAtual;

    if (!grupoAtual.isValido()) {
      return alert('Há informações obrigatórias inconsistentes.\nVerifique.');
    }

    js_divCarregando('Salvando Formulário', 'loading_message');

    const formData = new FormData();
    formData.append('acao', 'salvar');
    formData.append('conta', inputCodigoConta.value);
    formData.append('ano', ano.toString());
    formData.append('codigoGrupoPerguntas', grupoAtual.getCodigo());
    formData.append('perguntasRespostas', JSON.stringify(viewAvaliacao.getDados()));

    if (preenchimento) {
      formData.append('preenchimento', preenchimento);
    }

    return fetch('con4_previsao_receita.RPC.php', {
      method: 'POST',
      body: formData,
      credentials: 'include',
    }).then(response => response.json()).then(response => {
      alert(response.mensagem);

      if (response.error) {
        return;
      }

      if ((viewAvaliacao.comboBox.selectedIndex + 1) < viewAvaliacao.grupos.get().length) {
        viewAvaliacao.avancarGrupo();
      }
    }).finally(() => js_removeObj('loading_message'));
  }
</script>
</body>
</html>

<?php
/**
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="grid.style.css" rel="stylesheet" type="text/css">
  <title>DBSeller Sistemas Integrados</title>
</head>
<body onload="$('nome').focus()">
<div class="container">
  <form>
    <fieldset>
      <legend>Categoria de Tipo de Processo</legend>

      <table class="form-container">
        <tr style="display: none;">
          <td>
            <label for="sequencial">Sequencial:</label>
          </td>
          <td>
            <input id="sequencial" type="text" value=""/>
          </td>
        </tr>

        <tr>
          <td>
            <label for="nome">Nome:</label>
          </td>
          <td>
            <input id="nome" type="text" value="" maxlength="100" class="field-size7"
                   onkeyup="js_ValidaCampos(this, 3, 'Nome', 'f', 't')"/>
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <fieldset class="separator">
              <legend>Descrição da Categoria</legend>
              <textarea id="descricao" maxlength="300"></textarea>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>

    <input id="novaCategoria" type="button" value="Nova"/>
    <input id="pesquisarCategorias" type="button" value="Pesquisar"/>
    <input id="salvarCategoria" type="button" value="Salvar"/>
    <input id="excluirCategoria" type="button" value="Excluir" disabled="disabled"/>
  </form>
</div>

<div class="container">
  <div id="ctnLancador"></div>
</div>

<?php db_menu(); ?>

</body>
</html>

<script type="text/javascript">
  const rpc = 'ouv4_categoriatipoprocesso.RPC.php';

  var lancadorTipoProcesso = new DBLancador('lancadorTipoProcesso');
  lancadorTipoProcesso.setNomeInstancia("lancadorTipoProcesso");
  lancadorTipoProcesso.setLabelAncora("Tipo de Processo: ");
  lancadorTipoProcesso.setTextoFieldset("Tipos de Processo Selecionados");
  lancadorTipoProcesso.setParametrosPesquisa("func_tipoproc_todos.php", ['p51_codigo', 'p51_descr'], '&grupo=2');
  lancadorTipoProcesso.setGridHeight("200px");
  lancadorTipoProcesso.show($("ctnLancador"));

  function preencheCategoria(sequencial, nome, descricao) {

    $('sequencial').value = sequencial;
    $('nome').value = nome;
    $('descricao').value = descricao;

    $('excluirCategoria').removeAttribute('disabled');

    db_iframe_categoriatipoproc.hide();

    buscarTiposProcesso();
  }

  function buscarTiposProcesso()
  {
    new AjaxRequest(rpc, {'executa': 'buscarTiposProcessos', 'sequencial': $F('sequencial')}, function(retorno, erro) {

      if(erro === true) {

        alert(retorno.mensagem);
        return false;
      }

      lancadorTipoProcesso.clearAll();

      retorno.tiposProcesso.each(function(tipoProcesso) {
        lancadorTipoProcesso.adicionarRegistro(tipoProcesso.sequencial, tipoProcesso.descricao);
      });
    }).execute();
  }

  $('novaCategoria').addEventListener('click', () => {
    location.reload();
  });

  $('pesquisarCategorias').addEventListener('click', () => {

    let funcao = 'func_categoriatipoproc.php?funcao_js=parent.preencheCategoria|p104_sequencial|p104_nome|p104_descricao';
    js_OpenJanelaIframe('', 'db_iframe_categoriatipoproc', funcao, 'Pesquisa Categoria', true);
  });

  $('salvarCategoria').addEventListener('click', () => {

    if($F('nome') === '') {

      alert('Nome da categoria não informado.');
      return false;
    }

    let tiposProcesso = [];

    lancadorTipoProcesso.getRegistros().each(function(tipoProcesso) {
      tiposProcesso.push(tipoProcesso.sCodigo);
    });

    let parametros = {
      'executa': 'salvar',
      'sequencial': $F('sequencial'),
      'nome': $F('nome'),
      'descricao': $F('descricao'),
      'tiposProcesso': tiposProcesso
    };

    new AjaxRequest(rpc, parametros, (retorno, erro) => {

      alert(retorno.mensagem);

      if(erro === true) {
        return false;
      }

      location.reload();
    }).setMessage('Aguarde, salvando informações da Categoria...').execute();
  });

  $('excluirCategoria').addEventListener('click', () => {

    if(!confirm("Confirma a exclusão da categoria '" + $F('nome') + "' ?")) {
      return false;
    }

    let parametros = {
      'executa': 'excluir',
      'sequencial': $F('sequencial')
    };

    new AjaxRequest(rpc, parametros, (retorno, erro) => {

      alert(retorno.mensagem);

      if(erro === true) {
        return false;
      }

      location.reload();
    }).setMessage('Aguarde, excluindo a Categoria...').execute();
  });
</script>

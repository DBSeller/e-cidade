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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="iso-8859-1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="estilos.css">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/numbers.js"></script>
  <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
</head>
<body class="body-default">
<div class="container">
  <form>
    <fieldset>
      <legend>Servidor - Outros Vínculos</legend>

      <table class="form-container">
        <tr>
          <td>
            <a id="ancoraMatricula" href="#">
              <label for="codigoMatricula">Matrícula:</label>
            </a>
          </td>
          <td>
            <input id="codigoMatricula" name="codigoMatricula" type="text" data="rh01_regist" class="field-size2"/>
            <input id="nomeServidor" name="nomeServidor" type="text" data="z01_nome" class="field-size7 readonly"
                   disabled="disabled"/>
          </td>
        </tr>

        <tr>
          <td>
            <label for="tipoContribuicao">Indicativo do Tipo de Contribuição:</label>
          </td>
          <td>
            <select id="tipoContribuicao">
              <option value="">Selecione...</option>
              <option value="1">Contribuição descontada pelo primeiro empregador</option>
              <option value="2">Contribuição descontada por outra(s) empresa(s) sobre valor inferior ao limite máximo do salário de contribuição
              </option>
              <option value="3">Contribuição sobre o limite máximo de salário de contribuição já descontada em outra(s) empresa(s)
              </option>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label for="tipoInscricao">Tipo de Inscrição (Empregador):</label>
          </td>
          <td>
            <select id="tipoInscricao">
              <option value="1">CNPJ</option>
              <option value="2">CPF</option>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label for="numeroInscricao">Número da Inscrição (Empregador):</label>
          </td>
          <td class="field-size7">
            <input id="numeroInscricao" name="numeroInscricao" type="text" maxlength="14"/>
          </td>
        </tr>

        <tr>
          <td>
            <label for="categoria">Categoria:</label>
          </td>
          <td>
            <select id="categoria">
              <option value="">Selecione...</option>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label for="valorRemuneracao">Valor da Remuneração:</label>
          </td>
          <td>
            <input id="valorRemuneracao" name="valorRemuneracao" type="text" class="field-size2" placeholder="00.00"/>
          </td>
        </tr>
      </table>
    </fieldset>

    <input id="salvar" name="salvar" type="button" value="Salvar"/>
    <input id="limpar" name="limpar" type="button" value="Limpar"/>
    <input id="sequencial" name="sequencial" value="" type="hidden"/>
  </form>
</div>

<div id="ctnPrincipalGrid" style="width: 45%; padding: 20px 0px 300px 500px;">
  <fieldset>
    <legend>Vínculos</legend>
    <div id="ctnGrid"></div>
  </fieldset>
</div>
</body>
<?php
db_menu();
?>
<script>
  var rpc = 'pes4_rhpessoaloutrosvinculos.RPC.php';

  var collectionVinculos = new Collection();
  collectionVinculos.setId('sequencial');

  var gridVinculos = new DatagridCollection(collectionVinculos, 'gridVinculos');
  gridVinculos.addColumn('sequencial', {'label': 'Sequencial'});
  gridVinculos.addColumn('tipoContribuicao', {'label': 'Tipo de Contribuição'});
  gridVinculos.addColumn('tipoInscricao', {'label': 'Tipo de Inscrição'});
  gridVinculos.addColumn('numeroInscricao', {'label': 'Número da Inscrição', 'width': '30%', 'align' : 'right'});
  gridVinculos.addColumn('codigoCategoria', {'label': 'Código da Categoria', 'width': '30%', 'align' : 'right'});
  gridVinculos.addColumn('valorRemuneracao', {'label': 'Valor Remuneração', 'width': '30%', 'align' : 'right'});
  gridVinculos.addAction('A', 'Alterar', function(event, linha) {
    preencherCampos(linha);
  });
  gridVinculos.addAction('E', 'Excluir', function(event, linha) {
    excluir(linha.sequencial);
  });
  gridVinculos.hideColumns([0, 1, 2]);
  gridVinculos.show($('ctnGrid'));

  new DBInputValor($('valorRemuneracao'));

  var buscarVinculos = function() {

    gridVinculos.clear();
    limparFormulario(false);

    var parametros = {
      'exec': 'buscar',
      'matricula': $F('codigoMatricula')
    };

    new AjaxRequest(rpc, parametros, function(response, error) {

      if(error === true) {

        alert(response.mensagem);
        return false;
      }

      if(response.servidorOutrosVinculos.length === 0) {
        return false;
      }

      response.servidorOutrosVinculos.each(function(vinculo) {

        var valorRemuneracao = new String(vinculo.valorRemuneracao);

        var informacoesVinculo = {
          'sequencial': vinculo.sequencial,
          'tipoContribuicao': vinculo.tipoContribuicao,
          'tipoInscricao': vinculo.tipoInscricao,
          'numeroInscricao': js_formatar(vinculo.numeroInscricao, 'cpfcnpj'),
          'codigoCategoria': vinculo.codigoCategoria,
          'valorRemuneracao': valorRemuneracao.replace('.', ',')
        };

        collectionVinculos.add(informacoesVinculo);
      });

      gridVinculos.reload();
    }).setMessage('Aguarde, buscando os vínculos lançados...').execute();
  };

  var lookupMatricula = new DBLookUp(
    $('ancoraMatricula'),
    $('codigoMatricula'),
    $('nomeServidor'),
    {
      'sArquivo': 'func_rhpessoal.php',
      'sLabel': 'Pesquisar Matrícula'
    }
  );

  lookupMatricula.setParametrosAdicionais(['somenteAtivos=true']);
  lookupMatricula.setCallBack('onChange', buscarVinculos);
  lookupMatricula.setCallBack('onClick', buscarVinculos);

  var salvar = function() {

    if(!validarPreenchimento()) {
      return false;
    }

    var parametros = {
      'exec': 'salvar',
      'sequencial': $F('sequencial'),
      'matricula': $F('codigoMatricula'),
      'tipoContribuicao': $F('tipoContribuicao'),
      'tipoInscricao': $F('tipoInscricao'),
      'numeroInscricao': $F('numeroInscricao').replace(/\D+/g, ''),
      'codigoCategoria': $F('categoria'),
      'valorRemuneracao': $F('valorRemuneracao').replace(',', '.')
    };

    new AjaxRequest(rpc, parametros, function(response, error) {

      alert(response.mensagem);

      if(error === false) {
        limparFormulario(false);
      }

      buscarVinculos();
    }).setMessage('Aguarde, salvando as informações do servidor...').execute();
  };

  var controleTipoInscricao = function() {

    $('numeroInscricao').maxLength = 14;
    $('numeroInscricao').value = '';

    if($F('tipoInscricao') === '2') {
      $('numeroInscricao').maxLength = 11;
    }
  };

  function excluir(id) {

    if(!confirm("Tem certeza que deseja excluir este vínculo?")) {
      return;
    }

    new AjaxRequest(rpc, {'exec': 'excluir', 'sequencial': id}, function(response, error) {

      if(error === true) {

        alert(response.mensagem);
        return false;
      }

      collectionVinculos.remove(id);
      gridVinculos.reload();
      limparFormulario(false);

    }).setMessage('Aguarde, removendo o vínculo...').execute();
  }

  function preencherCampos(linha) {

    $('sequencial').value = linha.sequencial;
    $('tipoContribuicao').value = linha.tipoContribuicao;
    $('tipoInscricao').value = linha.tipoInscricao;
    $('numeroInscricao').value = linha.numeroInscricao;
    $('categoria').value = linha.codigoCategoria;
    $('valorRemuneracao').value = linha.valorRemuneracao;
    
    $('numeroInscricao').maxLength = 14;

    if($F('tipoInscricao') === '2') {
      $('numeroInscricao').maxLength = 11;
    }
  }

  function buscarCategorias() {

    new AjaxRequest(rpc, {'exec': 'buscarCategorias'}, function(response, error) {
      if(error === true) {

        alert(response.mensagem);
        return false;
      }

      response.categorias.each(function(categoria) {
        $('categoria').add(new Option(categoria.descricao, categoria.codigo));
      });
    }).asynchronous(false).setMessage('Aguarde, buscando as categorias...').execute();
  }

  function validarPreenchimento() {

    if(empty($F('codigoMatricula'))) {

      alert('Nenhuma matrícula foi selecionada.');
      return false;
    }

    if(empty($F('tipoContribuicao'))) {

      alert('Indicativo do Tipo de Contribuição não informado.');
      return false;
    }

    if(empty($F('numeroInscricao'))) {

      alert('Número da Inscrição não informado.');
      return false;
    }

    if(empty($F('categoria'))) {

      alert('Nenhuma categoria selecionada.');
      return false;
    }

    if(empty($F('valorRemuneracao'))) {

      alert('Valor da Remuneração não informado.');
      return false;
    }

    return true;
  }

  function limparFormulario(limparMatricula) {

    if(limparMatricula) {
      $('codigoMatricula').value = '';
      $('nomeServidor').value = '';
    }

    $('sequencial').value = '';
    $('tipoContribuicao').value = '';
    $('tipoInscricao').value = '1';
    $('numeroInscricao').value = '';
    $('numeroInscricao').maxLength = 14;
    $('categoria').value = '';
    $('valorRemuneracao').value = '';
  }

  $('tipoInscricao').observe('change', controleTipoInscricao);

  $('codigoMatricula').observe('keyup', function(event) {
    return js_ValidaCampos(this, 1, 'Matrícula', false, false, event);
  });

  $('numeroInscricao').observe('keyup', function(event) {
    return js_ValidaCampos(this, 1, 'Número da Inscrição', false, false, event);
  });

  $('numeroInscricao').observe('change', function() {

    if ($F('tipoInscricao') == 2 && !validaCPF($('numeroInscricao'))) {
      
      alert("Formato de CPF inválido");
      $('numeroInscricao').value = '';
      return false;
    }

    if ($F('tipoInscricao') == 1 && !validaCNPJ($('numeroInscricao'))) {

      $('numeroInscricao').value = '';
      alert("Formato de CNPJ inválido");
      return false;
    }
    this.value = js_formatar(this.value, 'cpfcnpj')
  });

  $('salvar').observe('click', salvar);

  $('limpar').observe('click', function() {
    location.reload();
  });

  (function() {
    buscarCategorias();
  })();

</script>
</html>

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
    <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
</head>
<body class="body-default">
<div class="container">
    <form>
        <fieldset>
            <legend>Servidor - Processos Judiciais da Folha</legend>

            <table class="form-container">
                <tr>
                    <td>
                        <a id="ancoraMatricula" href="#">Matrícula:</a>
                    </td>
                    <td>
                        <input id="codigoMatricula" name="codigoMatricula" type="text" data="rh01_regist" class="field-size2"/>
                        <input id="nomeServidor" name="nomeServidor" type="text" data="z01_nome" class="field-size7 readonly"
                               disabled="disabled"/>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="tipoProcesso">Tipo de Processo Judicial:</label>
                    </td>
                    <td>
                        <select id="tipoProcesso">
                            <option value="">Selecione...</option>
                            <option value="1">IRRF</option>
                            <option value="2">Contribuições Sociais do Trabalhador</option>
                            <option value="3">FGTS</option>
                            <option value="4">Contribuição Sindical</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="numeroProcesso">Número do Processo:</label>
                    </td>
                    <td class="field-size7">
                        <input id="numeroProcesso" name="numeroProcesso" type="text" maxlength="20" />
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="codigoIndicativoSuspensao">Código Indicativo de Suspensão:</label>
                    </td>
                    <td class="field-size7">
                        <input id="codigoIndicativoSuspensao" name="codigoIndicativoSuspensao" type="text" maxlength="14" />
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
        <legend>Processos Lançados</legend>
        <div id="ctnGrid"></div>
    </fieldset>
</div>
</body>
<?php
db_menu();
?>
<script>
  var rpc = 'pes4_rhpessoalprocessosjudiciaisfolha.RPC.php';
  var tiposProcesso = [
    'IRRF',
    'Contribuições Sociais do Trabalhador',
    'FGTS',
    'Contribuição Sindical'
  ];

  var collectionProcessos = new Collection();
  collectionProcessos.setId('sequencial');

  var gridProcessos = new DatagridCollection(collectionProcessos, 'gridProcessos');
  gridProcessos.addColumn('sequencial', {'label': 'Sequencial', 'width': '5%', 'align' : 'right'});
  gridProcessos.addColumn('tipoProcesso', {'label': 'Tipo de Processo', 'width': '40%', 'align' : 'left'});
  gridProcessos.addColumn('numeroProcesso', {'label': 'Número do Processo', 'width': '20%', 'align' : 'right'});
  gridProcessos.addColumn('codigoIndicativoSuspensao', {'label': 'Código Indicativo de Suspensão', 'width': '30%', 'align' : 'right'});
  gridProcessos.addColumn('codigoTipoProcesso', {'label': 'Código Tipo Processo', 'width': '1%', 'align' : 'right'});
  gridProcessos.addAction('A', 'Alterar', function(event, linha) {
    preencherCampos(linha);
  });
  gridProcessos.addAction('E', 'Excluir', function(event, linha) {
    excluir(linha.sequencial);
  });
  gridProcessos.hideColumns([0, 4]);
  gridProcessos.show($('ctnGrid'));

  var buscarProcessos = function() {

    gridProcessos.clear();
    limparFormulario(false);

    var parametros = {
      'executa': 'buscar',
      'matricula': $F('codigoMatricula')
    };

    new AjaxRequest(rpc, parametros, function(response, error) {

      if(error === true) {

        alert(response.mensagem);
        return false;
      }

      if(response.processosJudiciais.length === 0) {
        return false;
      }

      response.processosJudiciais.each(function(processo) {

        var informacoesProcesso = {
          'sequencial': processo.sequencial,
          'tipoProcesso': tiposProcesso[processo.tipoProcesso - 1],
          'numeroProcesso': processo.numeroProcesso,
          'codigoIndicativoSuspensao': processo.codigoIndicativoSuspensao,
          'codigoTipoProcesso': processo.tipoProcesso
        };

        collectionProcessos.add(informacoesProcesso);
      });

      gridProcessos.reload();
    }).setMessage('Aguarde, buscando os processos lançados...').execute();
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
  lookupMatricula.setCallBack('onChange', buscarProcessos);
  lookupMatricula.setCallBack('onClick', buscarProcessos);

  var salvar = function() {

    if(!validarPreenchimento()) {
      return false;
    }

    var parametros = {
      'executa': 'salvar',
      'sequencial': $F('sequencial'),
      'matricula': $F('codigoMatricula'),
      'tipoProcesso': $F('tipoProcesso'),
      'numeroProcesso': $F('numeroProcesso'),
      'codigoIndicativoSuspensao': $F('codigoIndicativoSuspensao')
    };

    new AjaxRequest(rpc, parametros, function(response, error) {

      alert(response.mensagem);

      if(error === false) {
        limparFormulario(false);
      }

      buscarProcessos();
    }).setMessage('Aguarde, salvando as informações do processo...').execute();
  };

  function excluir(id) {

    if(!confirm("Tem certeza que deseja excluir este processo?")) {
      return;
    }

    new AjaxRequest(rpc, {'executa': 'excluir', 'sequencial': id}, function(response, error) {

      alert(response.mensagem);

      if(error === true) {
        return false;
      }

      collectionProcessos.remove(id);
      gridProcessos.reload();
      limparFormulario(false);

    }).setMessage('Aguarde, removendo o processo...').execute();
  }

  function preencherCampos(linha) {

    $('sequencial').value = linha.sequencial;
    $('tipoProcesso').value = linha.codigoTipoProcesso;
    $('numeroProcesso').value = linha.numeroProcesso;
    $('codigoIndicativoSuspensao').value = linha.codigoIndicativoSuspensao;
  }

  function validarPreenchimento() {

    if(empty($F('codigoMatricula'))) {

      alert('Nenhuma matrícula foi selecionada.');
      return false;
    }

    if(empty($F('tipoProcesso'))) {

      alert('Tipo de Processo não informado.');
      return false;
    }

    if(empty($F('numeroProcesso'))) {

      alert('Número do Processo não informado.');
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
    $('tipoProcesso').value = '';
    $('numeroProcesso').value = '';
    $('codigoIndicativoSuspensao').value = '';
  }

  $('codigoMatricula').observe('keyup', function(event) {
    return js_ValidaCampos(this, 1, 'Matrícula', false, false, event);
  });

  $('numeroProcesso').observe('keyup', function(event) {
    return js_ValidaCampos(this, 0, 'Número do Processo', false, 't', event);
  });

  $('codigoIndicativoSuspensao').observe('keyup', function(event) {
    return js_ValidaCampos(this, 1, 'Código Indicativo de Suspensão', false, false, event);
  });

  $('salvar').observe('click', salvar);

  $('limpar').observe('click', function() {
    location.reload();
  });
</script>
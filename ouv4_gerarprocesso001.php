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
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="grid.style.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
  <form>
    <fieldset>
      <legend>Ouvidorias Externas</legend>

      <div id="gridPreProcessos" style="width: 600px;"></div>

    </fieldset>

    <input id="criarProcesso" type="button" value="Criar Processo"/>
  </form>
</div>

<?php db_menu(); ?>

</body>
</html>

<script type="text/javascript">
  const RPC = 'ouv4_gerarprocesso.RPC.php';

  var collection = new Collection();
  collection.setId('sequencial');

  var gridPreProcessos = new DatagridCollection(collection, 'gridPreProcessos');
  gridPreProcessos.configure({'order': false, 'height': '200px'});
  gridPreProcessos.addColumn('sequencial', {'width': '10%', 'label': 'Código', 'align': 'right'});
  gridPreProcessos.addColumn('tipoProcesso', {'width': '75%', 'label': 'Tipo de Processo'});
  gridPreProcessos.addColumn('detalhes', {'width': '15%', 'label': 'Detalhes', 'align': 'center'});
  gridPreProcessos.getGrid().setCheckbox(0);
  gridPreProcessos.show($('gridPreProcessos'));

  function dadosPreProcesso(sequencial) {
    js_OpenJanelaIframe('', 'db_frame_detalhes', 'ouv4_gerarprocessodetalhes001.php?sequencial=' + sequencial, 'Detalhes', true);
  }

  function buscarPreProcessos() {
    gridPreProcessos.clear();

    new AjaxRequest(RPC, {'executa': 'buscarPreProcessos'}, function(retorno, erro) {
      if(erro === true) {

        alert(retorno.mensagem);
        return false;
      }

      retorno.preProcessos.each(function(preProcesso) {

        let button = new Element('input');
        button.setAttribute('id', preProcesso.sequencial);
        button.setAttribute('type', 'button');
        button.setAttribute('value', 'Detalhes');

        let dados = {
          'sequencial': preProcesso.sequencial,
          'tipoProcesso': preProcesso.tipoProcesso,
          'detalhes': button.outerHTML
        };

        collection.add(dados);
      });

      gridPreProcessos.reload();

      let inputs = document.querySelectorAll('input[value=Detalhes]');

      for(let contador = 0; contador < inputs.length; contador++) {

        let element = inputs[contador];

        element.addEventListener('click', function() {
          dadosPreProcesso(this.getAttribute('id'));
        });
      }
    }).execute();
  }

  $('criarProcesso').addEventListener('click', () => {
    let selecionados = gridPreProcessos.getGrid().getSelection('object');

    if(selecionados.length === 0) {

      alert('Selecione ao menos uma ouvidoria.');
      return false;
    }

    let codigosPreProcesso = [];

    selecionados.each(function(linha) {
      codigosPreProcesso.push(linha.itemCollection.sequencial);
    });

    new AjaxRequest(RPC, {
      'executa': 'criarProcesso',
      'codigosPreProcesso': codigosPreProcesso
    }, function(retorno, erro) {

      alert(retorno.mensagem);

      if(erro === false) {
        buscarPreProcessos();
      }
    }).execute();
  });

  (function() {
    buscarPreProcessos();
  })();
</script>

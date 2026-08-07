<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

$clrotulo = new rotulocampo;
$clrotulo->label("rh218_tipoasse");
$clrotulo->label("h12_descr");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js");
  db_app::load("strings.js");
  db_app::load("prototype.js");
  db_app::load("estilos.css");
  db_app::load("widgets/DBLookUp.widget.js");
  db_app::load("AjaxRequest.js");
  db_app::load("datagrid.widget.js");
  db_app::load("widgets/Collection.widget.js");
  db_app::load("widgets/DatagridCollection.widget.js");
  ?>
  <style type="text/css">
    #gridTiposAssentamentos{
      width: 800px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <div class="container">
    <form>
      <fieldset>
        <legend>Tipo de Assentamento não desconta DSR</legend>

        <table class="form-container">
          <tr>
            <td colspan="2" nowrap title="<?=@$Trh218_tipoasse?>">
              <label id="lbl_rh218_tipoasse" for="rh218_tipoasse"><?php echo $Lrh218_tipoasse; ?></label> 
            </td>
            <td>
              <?php db_input('rh218_tipoasse',5,'' ,true,'text', 1); ?>  
              <?php db_input('h12_assent',5,''     ,true,'text', 1); ?>  
            </td>
            <td>
              <?php db_input('h12_descr',    50,'' ,true,'text', 3); ?>  
            </td>
          </tr>
        </table>
      </fieldset>
    </form>
  </div>
  <div class="container" style="margin-top: 0">
    <fieldset>
      <legend>Lista</legend>
      <div id="gridTiposAssentamentos"></div>
    </fieldset>

    <input id="novaConfiguracao"     type="button" value="Novo"    />
    <input id="salvarConfiguracao"    type="button" value="Salvar"  />
  </div>
</body>
<script>
$('rh218_tipoasse').style.display='none';
$('novaConfiguracao').observe('click', novaConfiguracao);
$('salvarConfiguracao').observe('click', salvarConfiguracao);

var collectionTipoasse = Collection.create().setId('sequencial');
var gridTipoasse       = DatagridCollection.create(collectionTipoasse);

montarGrid();
buscarRegistros();

var lookupTipoasse = new DBLookUp(
  $('lbl_rh218_tipoasse'),
  $('h12_assent'),
  $('h12_descr'),
  {
    'sArquivo'               : 'func_tipoasse.php',
    'sLabel'                 : 'Pesquisar Tipo de Assentamento',
    'aParametrosAdicionais'  : ['configuracaoAssentamentoNaoDescontaDSR=true'],
    'sQueryString'           : '|h12_codigo|h12_assent|h12_descr',
    'fCallBack'              : function(h12_assent, h12_descr, h12_codigo) {

      if(arguments.length == 3) { // evt onChange

        h12_descr  = h12_codigo;
        h12_codigo = h12_assent;
      }
      
      $('rh218_tipoasse').value = h12_codigo;
      $('h12_descr').value      = h12_descr;
    },
  }
);

function montarGrid () {

  gridTipoasse.addColumn('sequencial', {'width': '100px', 'label': 'Sequencial', 'align': 'center'});
  gridTipoasse.addColumn('codigo',     {'width': '100px', 'label': 'Código',     'align': 'center'});
  gridTipoasse.addColumn('descricao',  {'width': '500px', 'label': 'Descrição',  'align': 'center'});
  gridTipoasse.addAction('Excluir', 'excluir', function(ev, item) {
      excluirConfiguracao(item);
  });

  gridTipoasse.hideColumns([1]);

  gridTipoasse.configure({'height': '200px', 'order' : false}); 
  gridTipoasse.show($('gridTiposAssentamentos'));
}

function buscarRegistros () {
  AjaxRequest.create(
    'rec4_pontoeletronicoconfiguracoes.RPC.php',
    {
      exec      : 'buscarTiposAssentamentosConfiguradosNaoPerdeDSR',
    },
    function (retorno, erro) {

      if(erro) {
        alert(retorno.mensagem);
      }

      retorno.tiposAssentamentos.each(function (item) {
        collectionTipoasse.add(item);
      });

      gridTipoasse.reload();
    }
  ).setMessage('Buscando registros...').execute();
}

function novaConfiguracao() {

  $('rh218_tipoasse').value  = '';
  $('h12_assent').value      = '';
  $('h12_descr').value       = '';
  
  gridTipoasse.reload();
}

function salvarConfiguracao() {

  if(!validaCampos()) {
    return false;
  }

  AjaxRequest.create(
    'rec4_pontoeletronicoconfiguracoes.RPC.php',
    {
      'exec'       : 'salvarConfiguracaoAssentamentoNaoPerdeDSR',
      'iCodigo'    : $F('rh218_tipoasse'),
    },
    function(oRetorno, lErro) {

      alert(oRetorno.mensagem);

      if(lErro) {
        return false;
      }

      novaConfiguracao();
      buscarRegistros();
    }
  ).setMessage('Aguarde... Salvando a configuração.').execute();
}

function excluirConfiguracao(item) {

  AjaxRequest.create(
    'rec4_pontoeletronicoconfiguracoes.RPC.php',
    {
      'exec'    : 'excluirConfiguracaoAssentamentoNaoPerdeDSR',
      'iCodigo' : item.sequencial,
    },
    function(oRetorno, lErro) {

      alert(oRetorno.mensagem);

      if(lErro) {
        return false;
      }

      collectionTipoasse.remove(item.sequencial);
      gridTipoasse.reload();
    }
  ).setMessage('Aguarde... Excluindo a configuração.').execute();
}

function validaCampos() {

  if(empty($F('rh218_tipoasse'))) {

    alert('Tipo de assentamento não informado.');
    return false;
  }

  return true;
}

</script>
</html>

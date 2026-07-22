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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new rotulocampo();
$oRotulo->label("h12_assent");
$oRotulo->label("h12_descr");
$oRotulo->label("eso10_sequencial");
$oRotulo->label("eso10_descricao");
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
    <form id="frmVinculosAfastamentoEsocial">
        <fieldset>
            <legend>Vínculo de Afastamentos</legend>
            <table cellpadding="0" cellspacing="0" class="form-container">
                <tr>
                    <td>
                        <label for="h12_assent"><a href="" id="lblTipo">Tipo de assentamento: </a></label>
                    </td>
                    <td>
                        <?php
                            db_input('h12_assent', 5, $Ih12_assent, true, 'text', 1);
                            db_input('h12_descr', 40, $Ih12_descr, true, 'text', 3);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="eso10_sequencial"><a href="" id="lblAfastamento">Afastamento: </a></label>
                    </td>
                    <td>
                        <?php
                            db_input('eso10_sequencial', 10, $Ieso10_sequencial, true, 'text', 1);
                            db_input('eso10_descricao', 40, $Ieso10_descricao, true, 'text', 3);
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="button" value="Incluir" id="btnIncluir">
        <fieldset style="width:600px">
            <legend>Afastamentos Vinculados</legend>
            <div id="ctnGridAfastamentos">
            </div>
        </fieldset>
    </form>
</div>
<?php db_menu();?>
</body>
</html>
<script>

    var RPC_PATH = "eso4_vinculoafastamentos.RPC.php";

    new DBLookUp($('lblTipo'), $('h12_assent'), $('h12_descr'), {
      arquivo: 'func_tipoasse.php',
      queryString:'&lConfiguracoesPontoEletronico=true&lConsultaAssentamento=true',
      callBack: callbackTipoAssentamento
    });

    new DBLookUp($('lblAfastamento'), $('eso10_sequencial'), $('eso10_descricao'), {
      arquivo: 'func_grupomotivoafastamentoesocial.php'
    });

    var grid = DatagridCollection.create(new Collection().setId('codigo'));

    grid.configure({'update':false, 'delete':true, 'order':true});

    grid.addColumn('tipoAssentamento')
      .setOption('width', '300px')
      .setOption('align', 'left')
      .setOption('label', 'Tipo Assentamento');

    grid.addColumn('afastamento')
      .setOption('width', '220px')
      .setOption('align', 'left')
      .setOption('label', 'Afastamento');

    grid.show($('ctnGridAfastamentos'));

    consultartDados();

    function callbackTipoAssentamento(){

      var parametros = {
        tipoAssentamento: $F('h12_assent'),
        execucao: "validarTipoAssentamento"
      };

      new AjaxRequest(RPC_PATH, parametros, function (retorno, erro) {

        if (erro || !retorno.afastamento) {
          alert(retorno.mensagem);
        }
      }).execute();
    }

    /**
     * Evento de inclusão do vínculo
     */
    $('btnIncluir').observe('click', function(){

      if (!validarDados()) {
        return false;
      }

      var parametros = {
        tipoAssentamento: $F('h12_assent'),
        afastamento: $F('eso10_sequencial'),
        execucao: "incluir"
      };

      new AjaxRequest(RPC_PATH, parametros, function (retorno, erro) {

        if (erro) {
          alert(retorno.mensagem);
          return false;
        }

        $('frmVinculosAfastamentoEsocial').reset();
        consultartDados();
      }).execute();
    });

    /**
     * Evento do botão "E" da grid que exclui os vínculos do banco e da grid
     */
    grid.setEvent('onclickdelete', function(event, itemCollection){

      var paramentros = {
        execucao: "excluir",
        codigo: itemCollection.codigo
      };

      new AjaxRequest(RPC_PATH, paramentros, function (retorno, erro) {

        alert(retorno.mensagem);
        if (erro) {
          return false;
        }

        grid.collection.remove(itemCollection.codigo);
        grid.reload();
      }).execute();
    });

    /**
     * Função que valida os dados do formulário de acordo com as Regras de Negócio
     * @returns {boolean}
     */
    function validarDados(){

      try{

        if ( empty($F('h12_assent')) ) {
          throw "O campo Tipo de assentamento é de preenchimento obrigatório.";
        }

        if ( empty($F('eso10_sequencial')) ) {
          throw "O campo Afastamento é de preenchimento obrigatório.";
        }

        grid.collection.itens.forEach(function (value) {

          if(value.codigoAssentamento == $F('h12_assent')){
            throw "Tipo de assentamento escolhido já está vinculado a um afastamento.";
          }
        });

      }catch (e) {
        alert(e);
        return false;
      }

      return true;
    }
    
    function validarTipoAssentamento() {
      var paramentros = {
        execucao: "validarTipoAssentamento",
        tipoAssentamento: $F('h12_assent')
      };

      new AjaxRequest(RPC_PATH, paramentros, function (retorno, erro) {

        if (erro) {
          alert(retorno.mensagem);
          return false;
        }

      }).execute();
    }

    /**
     * Função que consulta os dados do banco e preenche a grid
     */
    function consultartDados() {

      var paramentros = { execucao: "consultar" };
      new AjaxRequest(RPC_PATH, paramentros, function (retorno, erro) {

        if (erro) {
          alert(retorno.mensagem);
          return false;
        }

        retorno.dados.forEach(function (registro) {

          grid.collection.add({
            codigo: registro.codigo,
            codigoAssentamento: registro.codigoAssentamento,
            tipoAssentamento: registro.tipoAssentamento,
            afastamento: registro.afastamento
          });
        });

        grid.reload();
      }).execute();
    }

</script>
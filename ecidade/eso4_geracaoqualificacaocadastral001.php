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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
  <form>
    <fieldset>
      <legend>Geração do Arquivo de Qualificação Cadastral</legend>

      <table class="form-container">

        <tr>
          <td>
            <label for="tipoPesquisa">Filtrar Por:</label>
          </td>
          <td colspan="2" class="field-size-max">
            <select id="tipoPesquisa">
              <option value="1">Seleção</option>
              <option value="2">Matrículas</option>
            </select>
          </td>
        </tr>

        <tr id="linhaSelecao">
          <td>
            <label for="r44_selec">
              <a href="#" id="ancoraSelecao">Seleção:</a>
            </label>
          </td>
          <td>
            <input id="r44_selec" type="text" value="" class="field-size2"/>
          </td>
          <td>
            <input id="r44_descr" type="text" value="" class="field-size8 readonly" disabled="disabled"/>
          </td>
        </tr>

        <tr id="linhaMatriculas" style="display: none;">
          <td id="linhaLancadorMatriculas" colspan="3"></td>
        </tr>

      </table>

    </fieldset>

    <input id="gerarArquivo" type="button" value="Gerar"/>
  </form>
</div>
</body>

<?php db_menu(); ?>

<script>
  var linhaSelecao = $('linhaSelecao');
  var linhaMatriculas = $('linhaMatriculas');
  var tipoPesquisa = $('tipoPesquisa');

  new DBLookUp(
    $('ancoraSelecao'),
    $('r44_selec'),
    $('r44_descr'),
    {
      'sArquivo': 'func_selecao.php',
      'sLabel': 'Pesquisar Seleção'
    }
  );

  var lancadorMatriculas = new DBLancador('lancadorMatriculas');
  lancadorMatriculas.setNomeInstancia('lancadorMatriculas');
  lancadorMatriculas.setLabelAncora('Matrícula:');
  lancadorMatriculas.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist', 'z01_nome'], "");
  lancadorMatriculas.show($('linhaLancadorMatriculas'));

  tipoPesquisa.observe('change', function() {

    linhaSelecao.setStyle({'display': ''});
    linhaMatriculas.setStyle({'display': 'none'});

    if(tipoPesquisa.value === '2') {

      linhaSelecao.setStyle({'display': 'none'});
      linhaMatriculas.setStyle({'display': ''});
    }
  });

  function validaCampos() {

    if(tipoPesquisa.value === '1' && empty($F('r44_selec'))) {

      alert('Selecione uma Seleção.');
      return false;
    }

    if(tipoPesquisa.value === '2' && lancadorMatriculas.getRegistros().length === 0) {

      alert('Selecione ao menos uma Matrícula.');
      return false;
    }

    return true;
  }

  function gerarArquivo() {

    if(!validaCampos()) {
      return false;
    }

    var selecao = $F('r44_selec');
    var matriculas = [];

    if(tipoPesquisa.value === '2' && lancadorMatriculas.getRegistros().length > 0) {

      selecao = '';

      lancadorMatriculas.getRegistros().each(function(oRegistros) {
        matriculas.push(oRegistros.sCodigo);
      });
    }

    var parametros = {
      'executa': 'gerarArquivo',
      'selecao': selecao,
      'matriculas': matriculas
    };

    new AjaxRequest('eso4_qualificacaocadastral.RPC.php', parametros, function(retorno, erro) {

      if(erro) {
        alert(retorno.mensagem);
        return false;
      }

      var dbdownload = new DBDownload();
      dbdownload.addGroups('arquivos', 'Arquivo(s) de Qualificação Cadastral');

      retorno.arquivos.each(function(arquivo) {
        dbdownload.addFile(arquivo.caminho, arquivo.nome, 'arquivos');
      });

      dbdownload.show();
    }).setMessage('Aguarde, gerando o arquivo...').execute();
  }

  $('gerarArquivo').observe('click', gerarArquivo);
</script>
</html>

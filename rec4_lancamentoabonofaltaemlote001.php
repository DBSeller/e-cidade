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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

try {
    $tiposAssentamento = TipoAssentamentoRepository::getInstanciasPorNatureza(Assentamento::NATUREZA_ABONO_FALTA);
} catch (Exception $erro) {
    $tiposAssentamento = null;
    db_msgbox($erro->getMessage());
}

$selecaoTiposAssentamento = array();

if($tiposAssentamento !== null) {
    foreach($tiposAssentamento as $sequencial => $tipoAssentamento) {
        $selecaoTiposAssentamento[$sequencial] = $tipoAssentamento->getCodigo() . ' - ' . $tipoAssentamento->getDescricao();
    }
}
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
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <title>DBSeller Sistemas Integrados</title>
</head>
<body class="body-default">
<div class="container">
  <form>
    <fieldset>
      <legend>Lançamento de Assentamento de Abono Falta em Lote</legend>

      <table class="form-container">
        <tr>
          <td>
            <label for="dataInicio">Data Início:</label>
          </td>
          <td>
            <input id="dataInicio"/>
          </td>
        </tr>
        <tr>
          <td>
            <label for="dataFim">Data Final:</label>
          </td>
          <td>
            <input id="dataFim"/>
          </td>
        </tr>

        <tr>
          <td>
            <label for="horaInicial">Hora Inicial:</label>
          </td>
          <td>
            <input id="horaInicial" type="text" value="" />
          </td>
        </tr>

        <tr>
          <td>
            <label for="horaFinal">Hora Final:</label>
          </td>
          <td>
            <input id="horaFinal" type="text" value="" />
          </td>
        </tr>

        <tr>
          <td>
            <label for="tipoAssentamento">Tipo de Assentamento:</label>
          </td>
          <td>
            <select id="tipoAssentamento">
              <option value="">Selecione</option>
                <?php
                if(count($selecaoTiposAssentamento) > 0) {
                    foreach($selecaoTiposAssentamento as $sequencial => $descricao) {

                        ?>
                      <option value="<?=$sequencial;?>"><?=$descricao;?></option>
                        <?php
                    }
                }
                ?>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label for="tipoFiltro">Filtrar por:</label>
          </td>
          <td>
            <select id="tipoFiltro" style="width: 84px;">
              <option value="1" selected>Seleção</option>
              <option value="2">Matrícula</option>
            </select>
          </td>
        </tr>

        <tr id="linhaSelecao">
          <td>
            <label for="r44_selec">
              <a href="#" id="selecao">Seleção:</a>
            </label>
          </td>
          <td>
            <input id="r44_selec" type="text" value=""/>
            <input id="r44_descr" type="text" value=""/>
          </td>
        </tr>

        <tr style="display: none;" id="linhaMatricula">
          <td id="matricula" colspan="2"></td>
        </tr>
      </table>

    </fieldset>

    <input id="lancarAbono" type="button" value="Lançar" />
  </form>

    <?php db_menu(); ?>

  <script>
    var rpc = 'rec4_pontoeletronico.RPC.php';
    var linhaSelecao = $('linhaSelecao');
    var linhaMatricula = $('linhaMatricula');
    var tipoFiltro = $('tipoFiltro');

    new DBInputDate($('dataInicio'));
    new DBInputDate($('dataFim'));

    new DBInputHora($('horaInicial'));
    new DBInputHora($('horaFinal'));

    /**
     * Ancora da seleção
     */
    new DBLookUp(
      $('selecao'),
      $('r44_selec'),
      $('r44_descr'),
      {
        'sArquivo': 'func_selecao.php',
        'sLabel': 'Pesquisa de Seleção'
      }
    );

    var lancadorMatriculas = new DBLancador('lancadorMatriculas');
    lancadorMatriculas.setLabelAncora('Matrícula:');
    lancadorMatriculas.setNomeInstancia('lancadorMatriculas');
    lancadorMatriculas.setTituloJanela('Pesquisa de Matrícula');
    lancadorMatriculas.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist', 'z01_nome']);
    lancadorMatriculas.setTextoFieldset('Matrículas');
    lancadorMatriculas.setGridHeight(150);
    lancadorMatriculas.adicionarItensPrimeiraPosicao(true);

    lancadorMatriculas.setCallbackBotao(function() {
      $('txtCodigolancadorMatriculas').focus();
    });

    lancadorMatriculas.show($('matricula'));

    tipoFiltro.observe('change', function() {

      linhaSelecao.setStyle({'display': 'none'});
      linhaMatricula.setStyle({'display': 'none'});

      /**
       * Filtrar por Seleção
       */
      if(tipoFiltro.value === '1') {

        linhaSelecao.setStyle({'display': ''});
        linhaMatricula.setStyle({'display': 'none'});
        lancadorMatriculas.clearAll();
      }

      /**
       * Filtrar por Matrícula
       */
      if(tipoFiltro.value === '2') {

        linhaSelecao.setStyle({'display': 'none'});
        linhaMatricula.setStyle({'display': ''});
        $('r44_selec').value = '';
        $('r44_descr').value = '';
      }
    });

    function lancarAssentamento() {

      if(!validaPreenchimentoCampos()) {
        return false;
      }

      var matriculas = [];
      var selecao = $F('r44_selec');

      if(tipoFiltro.value === '2') {

        selecao = '';
        lancadorMatriculas.getRegistros().each(function(matricula) {
          matriculas.push(matricula.sCodigo);
        });
      }

      var parametros = {
        'exec'             : 'criarAssentamentosAbonoFaltaLote',
        'selecao'          : selecao,
        'matriculas'       : matriculas,
        'dataInicio'       : $F('dataInicio'),
        'dataFim'          : $F('dataFim'),
        'horaInicio'       : $F('horaInicial'),
        'horaFim'          : $F('horaFinal'),
        'tipoAssentamento': $F('tipoAssentamento'),
        'porLote': true
      };

      new AjaxRequest(rpc, parametros, function(retorno, erro) {

        alert(retorno.mensagem);

        if(erro) {
          return false;
        }

        location.href = 'rec4_lancamentoabonofaltaemlote001.php';
      }).setMessage('Aguarde, lançando o assentamento...').execute();
    }

    function validaPreenchimentoCampos() {

      if($F('dataInicio') === '') {

        alert('Informe a Data de Início.');
        return false;
      }

      if($F('horaInicial') === '') {

        alert('Informe a Hora Inicial.');
        return false;
      }

      if($F('horaFinal') === '') {

        alert('Informe a Hora Final.');
        return false;
      }

      if($F('horaInicial') >= $F('horaFinal')) {

        alert('Hora Final não pode ser menor ou igual a Hora Inicial.');
        return false;
      }

      if($F('tipoAssentamento') === '') {

        alert('Selecione um Tipo de Assentamento.');
        return false;
      }

      if(tipoFiltro.value === '1' && $F('r44_selec') === '') {

        alert('Informe uma Seleção.');
        return false;
      }

      if(tipoFiltro.value === '2' && lancadorMatriculas.getRegistros().length === 0) {

        alert('Informe ao menos uma Matrícula.');
        return false;
      }

      return true;
    }

    $('lancarAbono').observe('click', lancarAssentamento);
    $('horaInicial').addClassName('field-size2');
    $('horaFinal').addClassName('field-size2');
  </script>
</div>
</body>
</html>

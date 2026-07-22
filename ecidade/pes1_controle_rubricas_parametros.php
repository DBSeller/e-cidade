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

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
  </head>
  <body class="body-default">
    <div class="container">
      <form id="frmParametros">
        <input type="hidden" name="sequencial" id="sequencial">
        <fieldset style="margin-bottom: 15px">
          <legend>Seleção 80 horas mensais</legend>
          <table class="form-container">
            <tr>
              <td>
                <label>Competência:</label>
              </td>
              <td id="containerCompetencia"></td>
            </tr>
            <tr>
              <td>
                <a id="ancoraSelecao">Seleção:</a>
              </td>
              <td>
                <input type="text" name="codigoSelecao" id="codigoSelecao" class="field-size2" data="r44_selec">
                <input type="text"
                       name="descricaoSelecao"
                       id="descricaoSelecao"
                       class="field-size9 readonly"
                       data="r44_descr">
              </td>
            </tr>
          </table>
        </fieldset>
        <div id="containerLancadorRubrica"></div>
        <input type="button" value="Salvar" id="btnSalvar">
        <input type="button" value="Excluir" id="btnExcluir" disabled>
      </form>
    </div>
    <?php db_menu();?>
  </body>
</html>
<script>
  const urlRPC = 'pes4_controle_rubricas.RPC.php';
  const formulario = document.querySelector("#frmParametros");
  var lancadorRubrica = new DBLancador("lancadorRubrica");
  var permissoesExclusoesRubricas = [];

  (function(window) {

    const oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);
    oCompetenciaFolha.renderizaFormulario($('containerCompetencia'));
    oCompetenciaFolha.desabilitarFormulario();

    const oLookupServidor = new DBLookUp($("ancoraSelecao"), $("codigoSelecao"), $("descricaoSelecao"), {
      "sArquivo"              : "func_selecao.php",
      "sObjetoLookUp"         : "db_iframe_selecao",
      "aParametrosAdicionais" : ['instit=<?=db_getsession("DB_instit")?>']
    });

    lancadorRubrica.withIcon = true;
    lancadorRubrica.setTextoFieldset('Rubricas controladas');
    lancadorRubrica.setNomeInstancia("lancadorRubrica");
    lancadorRubrica.setLabelAncora("Rubrica: ");
    lancadorRubrica.setParametrosPesquisa("func_rhrubricas.php", ['rh27_rubric', 'rh27_descr']);
    lancadorRubrica.show($("containerLancadorRubrica"));

    function validarDados() {

      if ($F('ano') === '') {
        alert("Ano da competência não informado.");
        return false;
      }

      if ($F('mes') === '') {
        alert("Mês da competência não informado.");
        return false;
      }

      if ($F('codigoSelecao') === '') {
        alert("Selecione uma seleção.");
        return false;
      }

      if (lancadorRubrica.getRegistros().length === 0) {
        alert("Adicione ao menos uma rubrica.");
        return false;
      }

      return true;
    }

    function salvar() {

        if (!validarDados()) {
            return false;
        }

        const registros = lancadorRubrica.getRegistros();
        var rubricas = [];

        for (var i = 0; i < registros.length; i++) {
            const codigoRubrica = registros[i].sCodigo;
            if (permissoesExclusoesRubricas[codigoRubrica] === undefined
                || permissoesExclusoesRubricas[codigoRubrica]) {
                rubricas.push(codigoRubrica);
            }
        }

        const data = new FormData();
        data.append('acao', 'salvarParametros');
        data.append('sequencial', $F('sequencial'));
        data.append('codigoSelecao', $F('codigoSelecao'));
        data.append('rubricas', rubricas);

        HttpClient.post(urlRPC, {body: data}).then(response => {
            alert(response.mensagem);

            if (response.erro) {
                return false;
            }
            buscar();
        });
    }

    function excluir()
    {
        if (!confirm("Deseja excluir o controle de horas extras?")) {
            return false;
        }

        if ($F('sequencial') === '') {
            alert("Não foi encontrado o sequencial do controle de horas extras.");
            return false;
        }

        const data = new FormData();
        data.append('acao', 'excluirParametros');
        data.append('sequencial', $F('sequencial'));

        HttpClient.post(urlRPC, {body: data}).then(response => {
            alert(response.mensagem);

            if (response.erro) {
                return false;
            }
            buscar();
        });
    }

    function buscar()
    {
        const data = new FormData();
        data.append('acao', 'buscarPorInstituicaoECompetencia');

        HttpClient.post(urlRPC, {body: data}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return false;
            }

            limpar();

            if (response.controleHorasExtras) {
                $('sequencial').value = response.controleHorasExtras.sequencial;
                $('codigoSelecao').value = response.controleHorasExtras.codigoSelecao;
                $('descricaoSelecao').value = response.controleHorasExtras.descricaoSelecao;

                var permiteExcluir = true;

                response.controleHorasExtras.controleHorasExtrasRubricas.each((rubrica) => {

                    permissoesExclusoesRubricas[rubrica.codigoRubrica] = rubrica.permiteExclusao;

                    if (!rubrica.permiteExclusao) {
                        permiteExcluir = false;
                    }
                    lancadorRubrica.adicionarRegistro(
                        rubrica.codigoRubrica,
                        rubrica.descricaoRubrica,
                        '',
                        !rubrica.permiteExclusao
                    );
                });

                if (permiteExcluir) {
                    $('btnExcluir').removeAttribute('disabled');
                }
            }
        });
    }
    buscar();

    function limpar()
    {
        $('sequencial').value = '';
        $('codigoSelecao').value = '';
        $('descricaoSelecao').value = '';
        lancadorRubrica.clearAll();
        $('btnExcluir').setAttribute('disabled', '');
    }

    $('btnSalvar').observe("click", function() {
        salvar();
    }) ;

    $('btnExcluir').observe("click", function() {
        excluir();
    }) ;

  })(window);
</script>

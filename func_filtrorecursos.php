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

use ECidade\Financeiro\Orcamento\Recurso\Especificacao;
use ECidade\Financeiro\Orcamento\Recurso\Grupo;
use ECidade\Financeiro\Orcamento\Recurso\IdentificadorUso;
use ECidade\Financeiro\Orcamento\Recurso\TipoDetalhamento;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

require_once (modification("src/Financeiro/Orcamento/Recurso/Especificacao.php"));
require_once (modification("src/Financeiro/Orcamento/Recurso/TipoDetalhamento.php"));
require_once (modification("src/Financeiro/Orcamento/Recurso/IdentificadorUso.php"));
require_once (modification("src/Financeiro/Orcamento/Recurso/Grupo.php"));

$oGet = db_utils::postMemory($_GET);
$display = "";
if (FONTE_RECURSO_2020 === true) {
    $display = "style='display: none'";
}
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js");
    db_app::load("datagrid.widget.js");
    db_app::load("strings.js");
    db_app::load("prototype.js");
    db_app::load("estilos.css");
    db_app::load("AjaxRequest.js");
    db_app::load("arrays.js");
    db_app::load("widgets/DBLancador.widget.js, widgets/DBAncora.widget.js");
    ?>
</head>
<body>

<div id="ctnJornada" class="container">

    <fieldset style="width:550px;">
        <legend>Seleção de recursos</legend>
        <fieldset class="separator">
            <legend class="bold">Fonte de Recurso</legend>
            <table>

                <tr <?php echo $display?>>
                    <td class="bold" nowrap="nowrap"><label for="identificadorUso">Identificador de Uso:</label></td>
                    <td>
                        <?php
                        $identificadorUso = array("" => 'Selecione');
                        foreach (IdentificadorUso::getAll() as $indice => $valor) {
                            $identificadorUso[$indice] = $valor;
                        }
                        db_select("identificadorUso", $identificadorUso, true, 1);
                        ?>
                    </td>
                </tr>
                <tr <?php echo $display?>>
                    <td  nowrap="nowrap">
                        <label for="tipoDetalhamento">
                            <strong>Tipo de Detalhamento:</strong>
                        </label>
                    </td>
                    <td>
                        <?php
                        $tipoDetalhamento = array("" => 'Selecione');
                        foreach (TipoDetalhamento::getAll() as $indice => $valor) {
                            $tipoDetalhamento[$indice] = $valor;
                        }
                        db_select("tipoDetalhamento", $tipoDetalhamento, true, 1);
                        ?>
                    </td>
                </tr>
                <tr <?php echo $display?>>
                    <td>
                        <label for="grupoFonteRecurso">
                            <strong>Grupo:</strong>
                        </label>
                    </td>
                    <td colspan="2">

                        <?php
                        $grupoFonteRecurso = array("" => 'Selecione');
                        foreach (Grupo::getAll() as $indice => $valor) {
                            $grupoFonteRecurso[$indice] = $valor;
                        }
                        db_select("grupoFonteRecurso", $grupoFonteRecurso, true, 1);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td
                        <label for="especificacaoFonte">
                            <strong>Especificação:</strong>
                        </label>
                    </td>
                    <td colspan="2">
                        <?php
                        $especificacaoFonte = array("" => 'Selecione');
                        foreach (Especificacao::getAll() as $indice => $valor) {
                            $especificacaoFonte[$indice] = $valor;
                        }
                        db_select("especificacaoFonte", $especificacaoFonte, true, 1);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="3" class="text-center">
                    <input name='Filtrar' type='button' id='filtrar' value='Filtrar' onclick='js_filtrarDetalhamento();'>
                    <input name='btnLimparRecursos' type='button' id='btnLimparRecursos' value='Limpar Recursos' onclick='limparGridRecursos();'>
                        <?php

                        if (empty($oGet->novaJanela) || $oGet->novaJanela == 'true') {
                            ?>
                            <input type='button' name='fechar' id='fechar' value='Fechar'
                                   onclick='parent.oInstancia.windowSelecaoRecurso.hide();'/>
                            <?php
                        }
                        ?>
                    </td>
                </tr>

            </table>
        </fieldset>
        <div id="divLancadorRecurso"  style="width: 550px;"></div></fieldset>
</div>
</body>

<script>

    js_criarLancadorRecurso();

    var sUrlRPC = 'orc4_manutencaoRecurso.RPC.php';

    $('especificacaoFonte').style.width = "300px";
    $('grupoFonteRecurso').style.width  = "300px";
    $('tipoDetalhamento').style.width   = "300px";
    $('identificadorUso').style.width   = "300px";

    function js_filtrarDetalhamento()
    {

        js_divCarregando('Aguarde buscando recursos...', 'msgBoxPesquisarRecursos');

        var oParam           = new Object();
        oParam.exec          = "getRecursosClassificacao";
        oParam.classificacao = {
            'identificador' :    $F('identificadorUso'),
            'tipoDetalhamento' : $F('tipoDetalhamento'),
            'grupo' :            $F('grupoFonteRecurso'),
            'especificacao' :    $F('especificacaoFonte')
        };

        var oAjax   = new Ajax.Request(
            sUrlRPC,
            {
                method: 'post',
                parameters: 'json='+js_objectToJson(oParam),
                onComplete: js_retornoPesquisar
            }
        );
    }


    function js_retornoPesquisar( resposta )
    {
        js_removeObj("msgBoxPesquisarRecursos");
        var oRetorno = JSON.parse(resposta.responseText);
        // oLancadorRecurso.clearAll();
        if (oRetorno.status == 2) {
            return false;
        } else {

            if (oRetorno.recursos.length === 0) {
                return alert("Não foram encontrados recursos para o filtro selecionado.");
            }
            oRetorno.recursos.each(function (oRecurso, i) {
                oLancadorRecurso.adicionarRegistro(oRecurso.codigo, oRecurso.descricao.urlDecode());
            });
            atualizarRecursos();
            return true;
        }
    }

    function atualizarRecursos ()
    {

        var recursosSelecionados = oLancadorRecurso.getRegistros();
        var aRecursos = [];
        recursosSelecionados.each(function (recurso){
            aRecursos.push(recurso.sCodigo);
        });

        parent.$('recursos').value = aRecursos.implode(",");
    }

    function js_criarLancadorRecurso()
    {

        oLancadorRecurso = new DBLancador("dbLancadorRecurso");
        oLancadorRecurso.setNomeInstancia("oLancadorRecurso");
        oLancadorRecurso.setLabelAncora("Recurso: ");
        oLancadorRecurso.setTextoFieldset("Recursos Selecionados");
        oLancadorRecurso.setParametrosPesquisa("func_orctiporec.php", ['o15_codigo', "o15_loaespecificacao || ' - ' || o15_descr as o15_descr"]);
        oLancadorRecurso.setGridHeight("100px");
        oLancadorRecurso.setCallbackBotao(function(){
            atualizarRecursos();
        });
        oLancadorRecurso.setCallbackRemover(function(){
            atualizarRecursos();
        });
        oLancadorRecurso.show($("divLancadorRecurso"));
        $('divacoes').style.display = 'none';
    }

    function limparGridRecursos () {
        oLancadorRecurso.clearAll();
    }

</script>
</html>

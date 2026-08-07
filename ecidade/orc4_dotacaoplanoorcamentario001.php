<?php
/*
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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>

</head>
<body onload="getPlanos()">
<div class="container">

    <fieldset>
        <legend>Dados do Plano Orçamentário</legend>
        <table class="form-container">
            <tr>
                <td><label for="po_descricao">Título:</label></td>
                <td>
                    <input type="hidden" id="po_codigo" name="po_codigo" class="field-size8"/>
                    <input type="text" id="po_descricao" name="po_descricao" class="field-size8"/>
                </td>
            </tr>
            <tr>
                <td><label for="po_valor">Valor:</label></td>
                <td>
                    <input type="text" id="po_valor" name="po_valor"/>
                </td>
            </tr>
        </table>
    </fieldset>

    <input type="button" value="Salvar" id="btn-po-adicionar" onclick="adicionarPlanoOrcamentario()">
    &nbsp;
    <input type="button" value="Novo" id="btn-po-novo" onclick="limparFormulario()">
    <br/>
    <fieldset style="width: 700px;">
        <legend>Plano Orçamentário</legend>
        <div id="ctnGridPlanoOrcamentario"></div>
    </fieldset>

</div>

<div id="abaLinhasPacto" class="container" >
    <fieldset>
        <legend>Informe os dados da linha pacto</legend>
        <div class="subcontainer">
            <form action="">
                <table class="form-container" style="min-width: 550px">
                    <tr>
                        <td>
                           <label for="linhaplanotitulo">Plano:</label>
                        </td>
                        <td>
                            <input type="hidden" id="linhaplanocodigo" name="linhaplanocodigo"/>
                            <input style="width:400px" id="linhaplanotitulo" name="linhaplanotitulo"
                                   class="readonly"/>
                        </td>

                    </tr>
                    <tr>
                        <td nowrap><label for="iCodigoLinhaPacto" id="ancoraLinhaPacto"></label></td>
                        <td >
                            <input nowrap
                                   type="text"
                                   name="iCodigoLinhaPacto"
                                   id="iCodigoLinhaPacto"
                                   style="width: 90px;"
                                   onblur="js_buscaLinhaPacto();"/>


                            <input
                                    type="text"
                                    name="sDescricaoLinhaPacto"
                                    id="sDescricaoLinhaPacto"
                                    style="width: 302px; background-color: rgb(222, 184, 135);"
                                    readonly/>
                        </td>

                    </tr>

                    <tr>
                        <td><label for="sValorLinhaPacto">Valor:</label></td>
                        <td>
                            <input id="sValorLinhaPacto" onblur="js_ValidaCampos(this, 4, '', 'f', 'f', event)" style="width: 90px;" type="text">
                        </td>
                    </tr>
                </table>

            </form>
    </fieldset>
    <input type="button" value="Salvar" onclick="salvarLinhaPacto()"/>

    <div class="subcontainer">
        <fieldset style="width: 700px;">
            <legend>Adicionar Linhas de Pacto</legend>
            <div id="linhasPacto"></div>
        </fieldset>
    </div>

</div>

</body>
</html>

<script>

    var codigoDotacao = '';
    var input = {
        'po_codigo' : document.querySelector('#po_codigo'),
        'po_descricao' : document.querySelector('#po_descricao'),
        'po_valor' : document.querySelector('#po_valor'),
        'linhaplanotitulo' : document.querySelector('#linhaplanotitulo')
    };

    var inputLinha = {
        'po_codigo' : document.querySelector('#linhaplanocodigo'),
        'codigoLinha' : document.querySelector('#iCodigoLinhaPacto'),
        'descricaoLinha' : document.querySelector('#sDescricaoLinhaPacto'),
        'valor' : document.querySelector('#sValorLinhaPacto')
    };

    /**
     * Salva os dados do plano orçamentário
     */
    function adicionarPlanoOrcamentario() {

        if (input.po_descricao.value.trim() === '') {
            return alert("Título é de preenchimento obrigatório.");
        }

        if (input.po_valor.value.trim() === '') {
            return alert("Valor é de preenchimento obrigatório.");
        }

        if (codigoDotacao === '') {
            codigoDotacao = parent.iframe_orcdotacao.document.form1.o58_coddot.value;
        }

        AjaxRequest.create(
            'orc4_dotacaoplanoorcamentario.RPC.php',
            {
                'exec' : 'salvarPlano',
                'po_codigo': input.po_codigo.value,
                'po_descricao': input.po_descricao.value,
                'po_valor': js_strToFloat(input.po_valor.value),
                'po_dotacao' : codigoDotacao
            },
            function (retorno, erro) {

                alert(retorno.mensagem);
                input.po_codigo.value = '';
                input.po_descricao.value = '';
                input.po_valor.value = '';
                getPlanos();
            }
        ).setMessage('Salvando informações, aguarde...').execute();
    }

    function salvarLinhaPacto() {

        var linhaAdicionada = false;
        var valorTotalLinhas = Number(inputLinha.valor.value);
        gridLinhaPacto.aRows.each(
            function (row) {

                if (Number(row.aCells[4].getValue()) === Number(inputLinha.codigoLinha.value)) {
                    linhaAdicionada = true;
                }
                valorTotalLinhas += js_strToFloat(row.aCells[2].getValue());
            }
        );

        var valorPlano = 0;
        gridPlanoOrcamento.aRows.each(
            function (rowPlano) {
                if (Number(rowPlano.aCells[0].getValue()) === Number(inputLinha.po_codigo.value)) {
                    valorPlano = js_strToFloat(rowPlano.aCells[2].getValue());
                }
            }
        );

        if (valorTotalLinhas > valorPlano) {

            alert('O valor total das linhas ('+js_formatar(valorTotalLinhas, 'f')+') é superior ao valor do plano orçamentário ('+js_formatar(valorPlano, 'f')+').');
            return false;
        }

        if (linhaAdicionada) {
            return alert("A linha "+inputLinha.descricaoLinha.value+" já encontra-se adicionada ao plano orçamentário.");
        }

        AjaxRequest.create(
            'orc4_dotacaoplanoorcamentario.RPC.php',
            {
                'exec' : 'salvarLinha',
                'po_codigo' : inputLinha.po_codigo.value,
                'linha' : inputLinha.codigoLinha.value,
                'valor' : inputLinha.valor.value
            },
            function (retorno, erro) {

                alert(retorno.mensagem);
                if (erro) {
                    return false;
                }
                inputLinha.codigoLinha.value = '';
                inputLinha.descricaoLinha.value = '';
                inputLinha.valor.value = '';
                getLinhas();
            }
        ).execute();
    }

    function getPlanos()
    {

        codigoDotacao = parent.iframe_orcdotacao.document.form1.o58_coddot.value;
        if (codigoDotacao === '') {

            return
        }

        AjaxRequest.create(
            'orc4_dotacaoplanoorcamentario.RPC.php',
            {
                'exec' : 'getPlanos',
                'po_dotacao' : codigoDotacao
            },
            function (retorno, erro) {

                if (erro) {
                    alert(retorno.mensagem);
                }

                gridPlanoOrcamento.clearAll(true);
                retorno.planos.each(
                    function (plano, indice) {

                        gridPlanoOrcamento.addRow([
                            plano.codigo,
                            plano.titulo,
                            js_formatar(plano.valor, 'f'),
                            "<input type='button' value='Linhas de Pacto' onclick='linhasDePacto("+indice+")' />&nbsp;" +
                            "<input type='button' value='E' onclick='excluirPlano("+indice+")' />"
                        ]);
                    }
                );
                gridPlanoOrcamento.renderRows();

            }
        ).setMessage('Aguarde, carregando informações...').execute();
    }

    function getLinhas() {

        AjaxRequest.create(
            'orc4_dotacaoplanoorcamentario.RPC.php',
            {
                'exec' : 'getLinhas',
                'po_codigo' : inputLinha.po_codigo.value
            },
            function (retorno, erro) {

                gridLinhaPacto.clearAll(true);
                if (erro) {
                    alert(retorno.mensagem);
                    return false;
                }

                retorno.linhas.each(
                    function (linha, indice) {

                        gridLinhaPacto.addRow([
                            linha.codigo,
                            linha.descricao,
                            js_formatar(linha.valor, 'f'),
                            "<input type='button' value='Excluir' onclick='excluirLinha("+indice+")' />",
                            linha.codigoLinha
                        ]);
                    }
                );
                gridLinhaPacto.renderRows();
            }
        ).execute();
    }

    function linhasDePacto(indice) {

        var rowSelecionada = gridPlanoOrcamento.aRows[indice];
        var abaLinhasPacto = $("abaLinhasPacto");
        var windowLinhaPacto = new windowAux('windowLinhaPacto', 'Linhas de Pacto', 800, 500);
        windowLinhaPacto.setIndex(1);
        windowLinhaPacto.setContent(abaLinhasPacto);
        abaLinhasPacto.style.display = '';

        var mensagemAjuda = "Informe as informações das linhas de pacto. A soma dos valores das linhas de pacto ";
        mensagemAjuda += "não podem ultrapassar o total do plano orçamentário a qual ela esta vinculada.";

        var oMessageBoard = new DBMessageBoard('msgBoardLinhaPacto', 'Cadastro das Linhas de Pacto', mensagemAjuda, windowLinhaPacto.getContentContainer());
        oMessageBoard.show();
        windowLinhaPacto.show();

        input.linhaplanotitulo.value = rowSelecionada.aCells[1].getValue();
        inputLinha.po_codigo.value = rowSelecionada.aCells[0].getValue();
        getLinhas();
    }


    function excluirPlano(linha) {

        var rowSelecionada = gridPlanoOrcamento.aRows[linha];

        if (!confirm("Confirma a exclusão do plano "+rowSelecionada.aCells[1].getValue()+"?\n\nTodas as linhas de pacto vinculadas serão excluídas.")) {
            return false;
        }

        AjaxRequest.create(
            'orc4_dotacaoplanoorcamentario.RPC.php',
            {
                'exec' : 'excluirPlano',
                'po_codigo' : rowSelecionada.aCells[0].getValue()
            },
            function (retorno, erro) {

                alert(retorno.mensagem);
                if (!erro) {
                    getPlanos();
                }
            }
        ).setMessage('Excluindo informações, aguarde...').execute();
    }

    function excluirLinha(linha)
    {

        var dadosLinha = gridLinhaPacto.aRows[linha];

        if (!confirm("Confirma a exclusão da linha "+dadosLinha.aCells[1].getValue()+"?")) {
            return false;
        }

        AjaxRequest.create(
            'orc4_dotacaoplanoorcamentario.RPC.php',
            {
                'exec' : 'excluirLinha',
                'codigo' : dadosLinha.aCells[0].getValue()
            },
            function (retorno, erro) {

                alert(retorno.mensagem);
                if (!erro) {
                    getLinhas();
                }
            }
        ).setMessage('Excluindo informações, aguarde...').execute();
    }

    var gridPlanoOrcamento = new DBGrid('gridPlanoOrcamento');
    gridPlanoOrcamento.nameInstance = 'gridPlanoOrcamento';
    gridPlanoOrcamento.setHeader(['Código', 'Título', 'Valor', 'Ação']);
    gridPlanoOrcamento.setCellAlign(['left', 'left', 'right', 'center']);
    gridPlanoOrcamento.setCellWidth(['10%', '40%', '20%', '30%']);

    gridPlanoOrcamento.show($('ctnGridPlanoOrcamentario'));


    var gridLinhaPacto = new DBGrid('gridLinhaPacto');
    gridLinhaPacto.nameInstance = 'gridLinhaPacto';
    gridLinhaPacto.setHeader(['Código', 'Título', 'Valor', 'Ação', 'codigoLinha']);
    gridLinhaPacto.setCellAlign(['left', 'left', 'right', 'center', 'left']);
    gridLinhaPacto.setCellWidth(['10%', '40%', '20%', '30%', '0%']);
    gridLinhaPacto.aHeaders[4].lDisplayed = false;
    gridLinhaPacto.show($('linhasPacto'));


    var linhaAncora = new DBAncora("Linhas de Pacto: ");
    linhaAncora.onClick(function () {

        var oParametros = {

            sFontePesquisa: "func_linhaspacto.php",
            aCamposRetorno: ["c07_sequencial", "c07_titulo", "c07_valor"],
            sStringAdicional: ""
        };

        var sQuery = oParametros.sFontePesquisa;
        var sIframe = 'db_iframe_' + oParametros.sFontePesquisa.replace('.php', '').replace('func_', '');

        sQuery += '?funcao_js=parent.js_mostraLinhaPacto|';
        sQuery += oParametros.aCamposRetorno.join("|");
        sQuery += oParametros.sStringAdicional === "" ? "" : '&' + oParametros.sStringAdicional;

        js_OpenJanelaIframe('',
            sIframe,
            sQuery,
            'Pesquisa',
            true);
    });

    linhaAncora.show($('ancoraLinhaPacto'));

    function js_mostraLinhaPacto(iCodigoDepartamento, sDescricaoDepartamento) {

        $("iCodigoLinhaPacto").value = iCodigoDepartamento;
        $("sDescricaoLinhaPacto").value = sDescricaoDepartamento;
        db_iframe_linhaspacto.hide();
    }

    function js_mostraLinhaPacto2(sRetorno, lErro) {
        $('sDescricaoLinhaPacto').value = sRetorno;
    }

    function js_buscaLinhaPacto() {

        if ($('iCodigoLinhaPacto').value != '') {

            js_OpenJanelaIframe('',
                'db_iframe_linhaspacto',
                'func_linhaspacto.php?pesquisa_chave=' + $F('iCodigoLinhaPacto') +
                '&funcao_js=parent.js_mostraLinhaPacto2',
                'Pesquisar',
                false,
                '0');
        } else {
            $('sDescricaoLinhaPacto').value = '';
        }
    }

    function limparFormulario() {

        inputLinha.codigoLinha.value = '';
        inputLinha.descricaoLinha.value = '';
        inputLinha.valor.value = '';
        input.po_codigo.value = '';
        input.po_descricao.value = '';
        input.po_valor.value = '';
    }

    new DBInputValor(input.po_valor);
    new DBInputValor(inputLinha.valor);
    document.querySelector('#abaLinhasPacto').style.display = 'none';

    if ( codigoDotacao !== undefined && codigoDotacao !== '') {
        getPlanos();
    }
</script>


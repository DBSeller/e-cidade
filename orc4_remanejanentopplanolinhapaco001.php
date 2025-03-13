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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/DBFormularios.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <fieldset>
        <legend>Remanejamento de Planos Orçamentários</legend>
        <table>
            <tr>
                <td nowrap>
                    <?php
                    db_ancora("<b>Dotação:</b>", "js_pesquisao80_coddot(true);", 1); ?> </td>
                <td>
                    <?php
                    db_input('codigo_dotacao', 8, 4, true, 'text', 1, "onchange='js_pesquisao80_coddot(false);'");
                    db_input('estrutural_dotacao', 50, 4, true, 'text', 3);
                    ?>

                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <fieldset class="separator">
                        <legend>Origem</legend>
                        <table style="width: 100%" border="0">
                            <tr>
                                <td style="width: 30%" nowrap>
                                    <b>Plano Orçamentário:</b>
                                </td>
                                <td>
                                    <select id="planoorcamentarioorigem" name="planoorcamentarioorigem"
                                            style="width: 100%" onchange="js_pesquisaLinhaPactos(this.value, 1)">
                                        <option value="0">Selecione</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%" nowrap>
                                    <b>Linha de Pacto:</b>
                                </td>
                                <td>
                                    <select id="linhapactoorigem" name="linhapactoorigem" style="width: 100%">
                                    </select>
                                </td>
                            </tr>
                        </table>

                    </fieldset>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <fieldset class="separator">
                        <legend>Destino</legend>
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 30%" nowrap>
                                    <b>Plano Orçamentário:</b>
                                </td>
                                <td>
                                    <select id="planoorcamentariodestino" name="planoorcamentariodestino"
                                            style="width: 100%"
                                            onchange="js_pesquisaLinhaPactos(this.value, 2)">
                                        <option value="0">Selecione</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%" nowrap>
                                    <b>Linha de Pacto:</b>
                                </td>
                                <td>
                                    <select id="linhapactodestino" name="linhapactodestino" style="width: 100%">
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <b>Valor:</b>
                                </td>
                                <td>
                                    <input type="text" id="valor" onblur="js_ValidaCampos(this, 4, '', 'f', 'f', event)">
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
    </fieldset>
    <input id="btnSalvar" type='button' value="Salvar" onclick="salvarRemanejamento()">
</div>
</body>
</html>
<script>

    function js_pesquisao80_coddot(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_orcdotacao', 'func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot|dl_estrutural', 'Pesquisa', true);
        } else {
            if ($('codigo_dotacao').value == '') {
                return;
            }
            js_OpenJanelaIframe('', 'db_iframe_orcdotacao', 'func_orcdotacao.php?pesquisa_chave=' + $('codigo_dotacao').value + '&funcao_js=parent.js_mostraorcdotacao', 'Pesquisa', false);
        }
    }

    function js_mostraorcdotacao(chave, erro) {
        $('estrutural_dotacao').value = chave;
        if (erro == true) {
            $('codigo_dotacao').focus();
            $('codigo_dotacao').value = '';
        } else {
            pesquisarPlanoOrcamentarioDotacao($('codigo_dotacao').value);
        }
    }

    function js_mostraorcdotacao1(chave1, chave2) {
        $('codigo_dotacao').value = chave1;
        $('estrutural_dotacao').value = chave2;
        pesquisarPlanoOrcamentarioDotacao(chave1);
        db_iframe_orcdotacao.hide();
    }

    function pesquisarPlanoOrcamentarioDotacao(dotacao) {

        var parametro = {
            "po_dotacao": dotacao,
            "exec": "getPlanos"
        };

        var planoOrigem = $('planoorcamentarioorigem');
        var planoDestino = $('planoorcamentariodestino');

        planoOrigem.options.length = 1;
        planoDestino.options.length = 1;
        new AjaxRequest('orc4_dotacaoplanoorcamentario.RPC.php', parametro, function (response, erro) {
            for (var plano of response.planos) {

                var optionOrigem = new Option(plano.titulo, plano.codigo);
                var optionDestino = new Option(plano.titulo, plano.codigo);

                planoOrigem.add(optionOrigem);
                planoDestino.add(optionDestino);
            }
        }).setMessage("Aguarde, pesquisando plano de pacto.").execute();

    }

    function js_pesquisaLinhaPactos(planoOrcamentario, tipo) {


        var parametro = {
            "plano": planoOrcamentario,
            "exec": "getLinhasDePactoDoPlano"
        };

        var combo = tipo == 1 ? $('linhapactoorigem') : $('linhapactodestino');
        combo.options.length = 0;
        if (planoOrcamentario == 0) {
            return;
        }
        new AjaxRequest('orc4_dotacao.RPC.php', parametro, function (response, erro) {

            var elementoPlano = combo
            elementoPlano.options.length = 0;
            if (erro) {
                alert(response.mensagem)
            }
            quantidadeDeItens = response.linhas.length;
            for (var linhaPacto of response.linhas) {

                var option = new Option(linhaPacto.descricao + "  - Saldo Atual: R$ " + js_formatar(linhaPacto.saldo_final, 'f'), linhaPacto.codigo);
                option.setAttribute('saldo_final', linhaPacto.saldo_final);
                elementoPlano.add(option);
            }
        }).setMessage("Aguarde, pesquisando linhas de pacto.").execute();

    }

    /**
     *
     */
    function salvarRemanejamento(){

        var linhaPactoOrigem = $('linhapactoorigem');
        var linhaPactoDestino = $('linhapactodestino');
        var valor  = $F('valor');
        var request = {
            exec: 'remanejarValores',
            dotacao : $F('codigo_dotacao'),
            linha_pacto_origem : $F('linhapactoorigem'),
            linha_pacto_destino : $F('linhapactodestino'),
            valor: valor
        }

        if ( $F('linhapactoorigem') == $F('linhapactodestino') ) {
            alert('Não é possível remanejar saldo entre linhas de pacto de origem e destino iguais. Selecione linhas diferentes para continuar.');
            return false;
        }

        if (empty(request.dotacao)) {
            alert('A dotacao deve ser informada.');
            return false;
        }
        if (empty(request.linha_pacto_origem)) {
            alert('A Linha de pacto de origem deve ser informada.');
            return false;
        }
        if (empty(request.linha_pacto_destino)) {
            alert('A Linha de pacto de origem deve ser informada.');
            return false;
        }
        if (empty(request.valor)) {
            alert('O valor deve ser informado.');
            return false;
        }


        if (!confirm('Confirma o remanejamento dos valores entres as Linhas de Pacto?')) {
            return;
        }
        var saldoLinhaPacto = linhaPactoOrigem.options[linhaPactoOrigem.selectedIndex].getAttribute('saldo_final');
        if (new Number(saldoLinhaPacto) < new Number(valor)) {
            alert('O valor informado ('+valor+') é maior que o saldo da Linha de Pacto de origem '+saldoLinhaPacto);
            return false;
        }

        new AjaxRequest('orc4_dotacaoplanoorcamentario.RPC.php', request, function (response, erro) {

            alert(response.mensagem)
            if (erro) {
                return false;
            }
            limparDados();
            pesquisarPlanoOrcamentarioDotacao(request.dotacao);
        }).setMessage("Aguarde, realizando o remanejamento dos valores entre as linhas de pacto.").execute();


    }

    function limparDados()
    {
        $('valor').value = '';
        var planoOrigem = $('planoorcamentarioorigem');
        var planoDestino = $('planoorcamentariodestino');
        planoDestino.options.length = 0;
        planoOrigem.options.length = 0;
        $('linhapactoorigem').options.length = 0;
        $('linhapactodestino').options.length = 0;
        var optionOrigem = new Option("Selecione", 0);
        planoOrigem.add(optionOrigem);

        var optionDestino = new Option("Selecione", 0);
        planoDestino.add(optionDestino);

    }

</script>


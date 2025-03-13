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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>

<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="" quiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/DBFormularios.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputCheckboxRadio.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>

</head>
<body class="body-default">
    <div class="container">
        <form>
            <fieldset style="width: 800px;">
                <legend>Manutenção de Informações Complementares para Lançamentos</legend>

                <table class="form-container">
                    <tr>
                        <td>
                            <label for="codigolancamento">
                                <a class="dbancora" href="#" style="text-decoration: underline;" onclick="pesquisaLancamento();"> Código Lançamento: </a>
                            </label>
                        </td>
                        <td>
                            <input id="codigolancamento"  type="text" size="10"
                                   onblur="js_ValidaMaiusculo(this,'',event);"
                                   onchange="pesquisaLancamento(this.value)"
                                   oninput="js_ValidaCampos(this,0,'','','',event);"
                                   onkeydown="return js_controla_tecla_enter(this,event);"
                                   autocomplete=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="contareduzida">Conta:</label>
                        </td>
                        <td>
                            <select id="contareduzida" class="readonly" disabled="" style="max-width: 100px;" onchange="adicionarInfoComplementaresGrid(this.value)">
                            </select>
                            <input type="text" id="estruturalconta"  size="13" class="readonly" readonly/>
                            <input type="text" id="descricaocontareduzida" size="60" class="readonly" readonly/>
                            <input type="hidden" id="existeConfiguracao"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <br/>
                            <fieldset id="fieldsetInfoComplementar">
                                <legend>Informações Complementares</legend>
                                <div id="gridinfocomplementar"></div>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <input type="button" value="Salvar" id="salvar" name="salvar" />
            <input type="button" value="Excluir" id="excluir" name="excluir" onclick="excluirRegistro();"/>
        </form>
    </div>
    <script type="application/javascript">
        const RPC = 'con4_informacaocomplementarlancamento.RPC.php';
        var contas = new Array();
        var selectContas = document.getElementById('contareduzida');

        $('excluir').disabled = true;

        var collection = new Collection().setId('codigo');
        var gridInfoComplemetares = DatagridCollection.create(collection).configure("order", false);

        gridInfoComplemetares.addColumn("sigla",{label: "Sigla", align: "center", width: "50px"});
        gridInfoComplemetares.addColumn("descricao",{label: "Descrição", align: "left", width: "340px"});
        gridInfoComplemetares.addColumn("valor",{label: "Valor", align: "left", width: "200px"}).transform(function(nValor, oCollection) {
            return "<input type='text' id='info_complementar_"+oCollection.ID+"' maxlength='20' value='"+nValor+"' style='width: inherit'/>";
        });

        gridInfoComplemetares.show($('gridinfocomplementar'));

        limpar();

        function pesquisaLancamento(codigoLancamento) {

            if (codigoLancamento != undefined && codigoLancamento != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_conlancamlan', 'func_conlancamlan.php?codigoTipoDocumento=3000&pesquisa_chave='+codigoLancamento+'&funcao_js=parent.preencheLancamento', 'Pesquisa Lançamentos', false);
            } else {
                js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_conlancamlan', 'func_conlancamlan.php?codigoTipoDocumento=3000&funcao_js=parent.preencheLancamento|c70_codlan&', 'Pesquisa Lançamentos', true);
            }
        }

        function preencheLancamento(c70_codlan, erro){

            if (erro != undefined && erro) {

                alert('Lançamento não encontrado.');
                limpar();
                return;
            }

            $('codigolancamento').value = c70_codlan;
            buscarContasReduzidasPorLancamento(c70_codlan);

            if (typeof db_iframe_conlancamlan !== 'undefined') {
                db_iframe_conlancamlan.hide();
            }
        }


        function buscarContasReduzidasPorLancamento(codLancamento) {
            new AjaxRequest(RPC, {exec: 'buscarContasReduzidasPorLancamento', codigoLancamento: codLancamento}, function (retorno, erro) {

                if (erro) {
                    alert(retorno.mensagem);
                    return false;
                }

                limpar();
                contas = new Array();
                $('contareduzida').disabled = false;
                $('contareduzida').removeClassName('readonly');
                for (var contaReduzida of retorno.contasReduzidas) {
                    contas.push(contaReduzida);
                    adicionarContasOption(contaReduzida);
                }
            }).setMessage("Aguarde, buscando contas...").execute();

        }

        function adicionarContasOption(contaReduzida) {

            var option = document.createElement('option');
            option.value = contaReduzida.numero;
            option.innerHTML = contaReduzida.numero;
            option.id = contaReduzida.numero;

            selectContas.appendChild(option);
            if ($('estruturalconta').value == '' && $('descricaocontareduzida').value == '') {
                $('estruturalconta').value = contaReduzida.estrutural;
                $('descricaocontareduzida').value = contaReduzida.descricao;
                adicionarInfoComplementaresGrid(contaReduzida.numero);
            }
        }

        function  limpar() {
            selectContas.innerHTML = '';
            $('estruturalconta').value = '';
            $('descricaocontareduzida').value = '';
            gridInfoComplemetares.clear();
            $('excluir').disabled = true;
            $('contareduzida').disabled = true;
            $('contareduzida').addClassName('readonly');
        }

        function adicionarInfoComplementaresGrid(contaReduzida) {
            gridInfoComplemetares.clear();

            for (var conta of contas) {
                if (conta.numero == contaReduzida) {
                    $('estruturalconta').value = conta.estrutural;
                    $('descricaocontareduzida').value = conta.descricao.urlDecode();
                    $('existeConfiguracao').value = conta.excluir;
                    $('excluir').disabled = !conta.excluir;

                    if (conta.informacoesComplementares.length == 0) {

                        var mensagem = "Não há informações complemetares configuradas para esta conta. ";
                        mensagem += "Para configurar, acesse:\n Matriz de Saldo Contábeis > Configurações > Informações Complementares"
                        alert(mensagem);
                        return;
                    }

                    for (var infoComplementar of conta.informacoesComplementares) {
                        infoComplementar.descricao = infoComplementar.descricao.urlDecode();
                        collection.add({
                            codigo: infoComplementar.codigo,
                            sigla: infoComplementar.sigla,
                            descricao: infoComplementar.descricao,
                            valor : infoComplementar.valor
                        });

                    }
                }
            }
            gridInfoComplemetares.reload();
        }

        function salvar() {

            var informacoesComplementares = [];

            for (var atributo of gridInfoComplemetares.getCollection().get()) {
                var elemento = "info_complementar_" + atributo.codigo;

                informacoesComplementares.push({
                    codigo: atributo.codigo,
                    valor: $F(elemento),
                    tipoSistema: 1
                });
            }

            var conta = {
                codigoLancamento: $F('codigolancamento'),
                contaReduzida: $F('contareduzida'),
                existeConfiguracao: $F('existeConfiguracao'),
                informacoesComplementares: informacoesComplementares
            };

            new AjaxRequest(RPC, {exec : 'salvar', conta: conta}, function(retorno, erro) {

                alert(retorno.message);
                if (erro) {
                    return;
                }
                atualizarValoresInfoComplementares();

            }).setMessage("Aguarde, salvando configuração...").execute();
        }

        function atualizarValoresInfoComplementares(){
            for (var conta of contas){
                if (conta.numero == $F('contareduzida')) {
                    conta.excluir = true;
                    $('excluir').disabled = false;
                    for (var infoComplementar of conta.informacoesComplementares) {
                        infoComplementar.valor = $F('info_complementar_' + infoComplementar.codigo);
                    }

                }
            }
        }

        function excluirRegistro() {
            var codLancamento = document.getElementById('codigolancamento').value;
            var selectContas  = document.getElementById('contareduzida');
            var conta = selectContas.options[selectContas.selectedIndex].text;

            new AjaxRequest(RPC, {exec: 'excluirInformacoesComplementaresLancamento', codigoLancamento: codLancamento, contaReduzida: conta}, function (retorno, erro) {

                if (erro){
                    alert(retorno.message.urlDecode());
                    return false;
                }

                alert("Informações excluídas com sucesso.");
                $('codigolancamento').value = '';
                limpar();
            }).execute();

        }

        $('salvar').addEventListener('click', function () {
            if (validarFormulario()){
                salvar();
            }
        });

        function validarFormulario() {
            if ($F('codigolancamento') == '') {

                alert("Código do lançamento não informado.");
                return false;
            }


            for (var atributo of gridInfoComplemetares.getCollection().get()) {
                var elemento = "info_complementar_" + atributo.codigo;
                if ($F(elemento) == '') {
                    alert('Valor da informação complementar não informado.');
                    return false;
                }

                var expr = new RegExp("\\W");
                if(expr.test($F(elemento))) {
                    alert("Valor da informação complementar deve conter apenas letras e números.");
                    return false;
                }
            }

            return true;
        }

    </script>
</body>
<?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>

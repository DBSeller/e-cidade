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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="scripts/scripts.js"></script>
    <script src="scripts/prototype.js"></script>
    <script src="scripts/strings.js"></script>
    <script src="scripts/AjaxRequest.js"></script>
    <script src="scripts/widgets/DBLancador.widget.js"></script>
    <script src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script src="scripts/widgets/DBLookUp.widget.js"></script>
    <script src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    <script src="scripts/datagrid.widget.js"></script>
    <script src="scripts/widgets/Collection.widget.js"></script>
    <script src="scripts/widgets/DatagridCollection.widget.js"></script>
</head>
<body class="body-default">
<div class="container">
    <form id="form1" name="form1" method="post" action="">
        <fieldset>
            <legend>Filtro Padrão</legend>
            <div>
                <div id='ctnLancadorAtributos' style="width: 600px;">
                    <div>
                        <fieldset class="separator">
                            <legend>Atributos</legend>
                            <div id="gridAtributos">
                            </div>
                        </fieldset>
                        <table class="form-container">
                            <tr>
                                <td class="field-size1">
                                    <b><label for="exclusao">Exclusão:</label></b>
                                </td>
                                <td>
                                    <input id="exclusao" name="exclusao" type="checkbox">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </fieldset>
        <input type="button" name="btnLancamento" id="btnLancamento" value="Lançar">
    </form>
</div>
<div id="cntLancamentos" class="container" style="width: 75%; display: none;">
    <fieldset>
        <legend>Lançamentos</legend>
        <div id="gridLancamentos"></div>
    </fieldset>
</div>
<script src="scripts/classes/http/http.js"></script>
<script>
    var atributosLancadosCollecition = null;
    var gridAtributosLancados = null;

    const rpc = 'cons2_consultacontacorrente.RPC.php';
    const urlParams = new URLSearchParams(window.location.search);
    const relatorio = urlParams.get('o116_codparamrel');
    const padrao = urlParams.has('padrao');
    const readonly = urlParams.has('readonly');
    const linha = urlParams.get('o116_codseq');
    const headers = ['Codigo', 'Sigla', 'Descrição', 'Valor'];
    const gridContaCorrenteAtributos = new DBGrid('gridAtributos');
    const inputLancamento = document.getElementById('btnLancamento');
    const inputExclusao = document.getElementById('exclusao');

    gridContaCorrenteAtributos.nameInstance = 'gridContaCorrenteAtributos';
    gridContaCorrenteAtributos.setCellWidth(['0%', '10%', '50%', '40%']);
    gridContaCorrenteAtributos.setHeader(headers);
    gridContaCorrenteAtributos.setHeight(250);
    gridContaCorrenteAtributos.aHeaders[0].lDisplayed = false;
    gridContaCorrenteAtributos.show($('gridAtributos'));

    (() => {
        if (readonly) {
            inputLancamento.classList.add('d-none');
        }

        const request = {
            exec: 'getInformacoesComplementares'
        };

        try {
            new AjaxRequest('con4_informacoescomplementares.RPC.php', request, function(response, erro) {
                if (erro) {
                    return;
                }

                criarGridLancamentos(response);

                for (var dados of response.informacoes_complementares) {
                    var linhaInformacaoComplementar = [
                        dados.codigo,
                        dados.sigla,
                        dados.descricao,
                        `<input type="text" style="width: 100%" id="valor${dados.sigla}">`
                    ];
                    gridContaCorrenteAtributos.addRow(linhaInformacaoComplementar);
                }

                gridContaCorrenteAtributos.renderRows();

                if (readonly) {
                    response.informacoes_complementares.each(function(atributo) {
                        var campoAtributo = 'valor' + atributo.sigla;
                        document.getElementById(campoAtributo).disabled = true;
                    });

                    inputExclusao.disabled = true;
                    inputLancamento.classList.add('d-none');
                }

                const formData = new FormData();
                formData.append('acao', 'buscarConfiguracao');
                formData.append('relatorio', relatorio);
                formData.append('linha', linha);

                if (!padrao) {
                    formData.append('customizada', 'true');
                }

                HttpClient.post('con1_relatorio_legal.RPC.php', {body: formData}).
                    then(resposta => {
                        if (resposta.erro) {
                            throw new Error(resposta.mensagem);
                        }

                        atributosLancadosCollecition.clear();
                        atributosLancadosCollecition.add(resposta.lancamentos);
                        gridAtributosLancados.reload();

                        if (readonly) {
                            Array.from(document.querySelectorAll('.collection_button')).forEach(input => {
                                input.disabled = true;
                            });
                        }
                    }).
                    catch(erro => alert(erro.message));
            }).setMessage('Aguarde, pesquisando atributos.').execute();
        } catch (e) {
            return alert(e.message);
        }
    })();

    function filtroAtributo() {
        var retorno = [];
        const linhas = gridContaCorrenteAtributos.aRows;

        if (linhas.length > 0) {
            Object.keys(linhas).forEach(index => {
                const codigo = linhas[index].aCells[0].getValue();
                const sigla = linhas[index].aCells[1].getValue();
                const valor = linhas[index].aCells[3].getValue();

                if (valor !== '') {
                    retorno.push({
                        codigo: codigo,
                        valor: valor,
                        sigla: sigla
                    });
                }
            });
        }

        return retorno;
    }

    inputLancamento.addEventListener('click', () => {
        const atributos = filtroAtributo();

        if (atributos.length === 0) {
            return alert('Ao menos uma informação complementar deve ser preenchida.');
        }

        const formData = new FormData();
        formData.append('acao', 'salvarConfiguracao');
        formData.append('relatorio', relatorio);
        formData.append('linha', linha);
        formData.append('informacoesComplementares', JSON.stringify(atributos));
        formData.append('exclusao', $F('exclusao') != null);

        if (!padrao) {
            formData.append('customizada', 'true');
        }

        HttpClient.post('con1_relatorio_legal.RPC.php', {
            body: formData
        }).then(response => {
            alert(response.mensagem);

            if (!response.erro) {
                Object.keys(response.lancamentos).forEach(codigoLancamento => {
                    atributosLancadosCollecition.add(response.lancamentos[codigoLancamento]);
                });

                gridAtributosLancados.reload();
            }
        }).catch(erro => alert(erro.message));
    });

    function criarGridLancamentos(response) {
        var tamanhoColuna = 80 / response.informacoes_complementares.length;
        atributosLancadosCollecition = new Collection().setId('lancamento');
        gridAtributosLancados = DatagridCollection.create(atributosLancadosCollecition).
            configure({'height': '120px;', 'order': false, 'delete': true});
        gridAtributosLancados.addColumn('lancamento', {label: 'Código', 'width': '7%'}).setOption('align', 'center');
        gridAtributosLancados.addColumn('exclusao', {label: 'Exclusão', 'width': '7%'}).setOption('align', 'center');

        response.informacoes_complementares.forEach(function(atributo) {
            gridAtributosLancados.addColumn(atributo.sigla, {label: atributo.sigla, 'width': `${tamanhoColuna}%`}).
                setOption('align', 'center');
        });

        $('cntLancamentos').style.display = '';
        gridAtributosLancados.show($('gridLancamentos'));

        gridAtributosLancados.addAction('E', null, function(event, element) {
            if (confirm('Deseja excluir este lançamento?')) {
                const formData = new FormData();
                formData.append('acao', 'excluirInformacaoComplementarLancamento');
                formData.append('relatorio', relatorio);
                formData.append('linha', linha);
                formData.append('lancamento', element.lancamento);

                if (!padrao) {
                    formData.append('customizada', 'true');
                }

                HttpClient.post('con1_relatorio_legal.RPC.php', {
                    body: formData
                }).then(response => {
                    alert(response.mensagem);

                    if (response.erro) {
                        return;
                    }

                    atributosLancadosCollecition.remove(element.lancamento);
                    gridAtributosLancados.reload();
                }).catch(erro => alert(erro.message));
            }
        });
    }
</script>
</body>
</html>

<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

//PLUGIN ENVIOESOCIAL - Usuários configurados
?>
<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="iso-8859-1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DBSeller Serviços de Informática Ltda</title>
        <link href="estilos.css" rel="stylesheet" type="text/css">
        <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
        <script src="scripts/scripts.js" rel="script" type="text/javascript"></script>
        <script src="scripts/prototype.js" rel="script" type="text/javascript"></script>
        <script src="scripts/object.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInput.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/DBInputHora.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputCep.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputCNPJ.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputCpf.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputDate.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputInteger.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputTelefone.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputValor.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputCheckboxRadio.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBCheckBox.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBRadio.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Collection.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/DBLancador.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/avaliacao/DBViewFormulario.classe.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/avaliacao/DBViewGrupoPerguntas.classe.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/avaliacao/DBViewPergunta.classe.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/avaliacao/DBViewResposta.classe.js" rel="script" type="text/javascript"></script>
        <script src="scripts/AjaxRequest.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/http/http.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js" rel="script"
            type="text/javascript"></script>
            <script src="scripts/classes/DBViewFormularioFolha/CaixaFolha.js" rel="script"
            type="text/javascript"></script>
        <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
        <style>
            div.esocial-text {
                display: none;
            }
            div.esocial-exibe{
                display: block;
            }

            #competenciaTr input[type="text"] {
                width: inherit;
            }
            #idFiltro{
                width:auto;
            }
        </style>
    </head>
    <body>
        <form name="formExclusaoProcessoJudicial" class="container">
            <div hidden='hidden'  id='idMensagem' class="alert alert-success" role="alert"
                style="text-align:center; width:50vw;">
            </div>
            <input type="hidden" name="lancamentosProcessos" id="idInputProcessos">
            <input type="hidden" name="lancamentosTributos" id="idInputTributos">
            <input type="hidden" name="porNumeroProcesso" id="idPorNumeroProcesso">
            <fieldset>
                <legend>Exclusão de Evento Processual</legend>
                <table>
                    <tr>
                        <td colspan="2">
                            <label for="empregador"><strong>Empregador:</strong></label>
                            <select name="empregador" id="empregador"></select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" ><label for="idFiltro"><strong>Filtro:</strong></label>
                            <select id="idFiltro" onchange = "defineFiltroExclusao()">
                                <option selected value="">Selecione o fitro...</option>
                                <option value="processo">Processo</option>
                                <option value="matricula">Matrícula</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" >
                            <div id='idDivProcesso' style="display: none;">
                                <label for="idProcesso"><strong>Número Processo:</strong></label>
                                <input id="idProcesso" type="text" onchange = "getServodoresProcesso(this.value);buscarProcessos()">
                            </div>
                        </td>
                    </tr>
                    <tr id ="idMatriculaLinha" style="display: none;">
                        <td>
                            <a id="ancoraMatricula" href="#">Matrícula:</a>
                        </td>
                        <td>
                            <input id="idMatricula" name="codigoMatricula" type="text" data="rh01_regist" class="field-size2"/>
                            <input id="idNomeServidor" name="nomeServidor" type="text" data="z01_nome" class="field-size7 readonly" disabled="disabled"/>
                        </td>
                    </tr>
                    <tr id ="idSelectProcessoLinha" style="display: none;">
                        <td colspan="2">
                            <label for="idSelectProcesso"><strong>Número processo:</strong></label>
                            <select name="processo" id="idSelectProcesso" onchange = "defineFiltroLayout()"></select>
                        </td>
                    </tr>
                    <tr id ="idSelectLayoutLinha" style="display: none;">
                        <td colspan="2">
                            <label for="idSelectLayout"><strong>Layout:</strong></label>
                            <select name="layout" id="idSelectLayout" onchange = "defineFiltroRecibo()"></select>
                        </td>
                    </tr>
                    <tr id ="idSelectReciboLinha" style="display: none;">
                        <td colspan="2">
                            <label for="idSelectRecibo"><strong>Recibo:</strong></label>
                            <select name="recibo" id="idSelectRecibo"></select>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <input type="button" id="idExcluirProcesso" value="Excluir processos">
        </form>

    </body>
</html>
<script rel="script" type="text/javascript">
    const divMensagemProcesso = formExclusaoProcessoJudicial.querySelector('#idMensagem');
    var rpcProcesso = 'pes4_processojudicial.RPC.php';
    const lancamentosProcessos = document.getElementById('idInputProcessos');
    const lancamentosTributos = document.getElementById('idInputTributos');

    (() => {

        const selectEmpregador = document.getElementById('empregador');

        idProcesso.style.display = 'none';

        const inicializar = () => {
            const formData = new FormData();
            formData.append('acao', 'inicializar');
            HttpClient.post(rpcProcesso, {
                body: formData
            }).then(response => {
                if (response.erro) {
                    throw response.mensagem;
                }
                //Preenche o empregador
                response.empregadores.map((empregadorOption, chave) => {
                    const selecionado = chave === 0;

                    selectEmpregador.add(
                        new Option(empregadorOption.nome, empregadorOption.cgm),
                        selecionado,
                        selecionado
                    );
                });
            }).catch(mensagem => alert(mensagem));

            idExcluirProcesso.addEventListener('click', excluirProcesso);
        };

        inicializar();

    })();

    const exibeFiltros = (opcao) => {
        //inicializando os grupos de forma forcada
        resetaCampos();
        switch (opcao) {
            case 'matricula':
                break;
            case 'processo':
                break;
        }
    }

    const buscarProcessos = function() {
        let parametros = new FormData(formExclusaoProcessoJudicial);

        let selectProcesso = document.getElementById('idSelectProcesso');
        parametros.append('acao', 'buscarProcessosExclusao');
        parametros.append('json', JSON.stringify(parametros));

        HttpClient.post(rpcProcesso, {body: parametros}).then(response => {
            divMensagemProcesso.removeAttribute("hidden");
            divMensagemProcesso.setAttribute("class", "alert alert-success");
            if (response.erro) {
                divMensagemProcesso.setAttribute("class", "alert alert-danger");
            } else {
                if (response.dados.processos.length == 0) {
                    response.mensagem = "Nenhum processo encontrado.";
                }
                if (response.dados.processos.length > 0) {
                    idSelectProcessoLinha.style.display = 'block';
                    //Limpa os itens
                    let index = selectProcesso.options.length;
                    while (index--) {
                        selectProcesso.remove(index);
                    }
                    //Preenche dados do processo
                    selectProcesso.options[selectProcesso.options.length] = new Option('Selecione o processo', '0');
                    response.dados.processos.map((processoOpcao, chave) => {
                        const selecionado = chave === 0;
                        selectProcesso.add(
                            new Option(processoOpcao.numeroProcesso + ' - ' + processoOpcao.nome, processoOpcao.nrProcTrab),
                            selecionado,
                            selecionado
                        );
                    });
                    lancamentosProcessos.value = JSON.stringify(response.dados.processos);

                    if (response.dados.tributos.length > 0) {
                        lancamentosTributos.value = JSON.stringify(response.dados.tributos);
                    }
                }
            }
            divMensagemProcesso.innerHTML = response.mensagem.trim().replace(/\\n/gi, '\n').replace(/\n/gi, '<br>');
            if (divMensagemProcesso.innerHTML == '') {
                divMensagemProcesso.setAttribute("hidden", "hidden");
            }

        });
    }

    function defineFiltroExclusao() {
        idDivProcesso.style.display = 'none';
        idMatriculaLinha.style.display = 'none';
        idSelectProcessoLinha.style.display = 'none';
        idSelectLayoutLinha.style.display = 'none';
        idSelectReciboLinha.style.display = 'none';
     
        if (idFiltro.value == 'processo') {
            idDivProcesso.style.display = 'block';
            idDivProcesso.style='text-align:left';
            idProcesso.style.display = 'block';
            idProcesso.style='text-align:right';
            idMatricula.value = "";
            idNomeServidor.value = "";

        }
        if (idFiltro.value == 'matricula') {
            idMatriculaLinha.style.display = 'block';
            var lookupMatricula = new DBLookUp(
                $('ancoraMatricula'),
                $('idMatricula'),
                $('idNomeServidor'),
                {
                'sArquivo': 'func_rhpessoal.php',
                'sLabel': 'Pesquisar Matrícula'
                }
            );
            idProcesso.value = "";
            lookupMatricula.setCallBack('onChange', buscarProcessos);
            lookupMatricula.setCallBack('onClick', buscarProcessos);
        } 
    }

    function defineFiltroLayout() {
        idSelectLayoutLinha.style.display = 'none';
        idSelectReciboLinha.style.display = 'none';
        
        if (idSelectProcesso.value != '0') {
            idSelectLayoutLinha.style.display = 'block';
            if (idSelectLayout.value == "") {
                idSelectLayout.options[idSelectLayout.options.length] = new Option('Selecione o layout', '0');
                idSelectLayout.options[idSelectLayout.options.length] = new Option('S-2500', 'S-2500');
                idSelectLayout.options[idSelectLayout.options.length] = new Option('S-2501', 'S-2501');
            }
            idSelectLayout.value = '0';
        }
        
    }

    function defineFiltroRecibo() {
        idSelectReciboLinha.style.display = 'none';

        //Limpa os itens
        let index = idSelectRecibo.options.length;
        while (index--) {
            idSelectRecibo.remove(index);
        }

        if (idSelectLayout.value == "S-2500") {
            idSelectReciboLinha.style.display = 'block';
            idSelectRecibo.options[idSelectRecibo.options.length] = new Option('Selecione o recibo', '0');
            let recibosProcessos = JSON.parse(lancamentosProcessos.value);
            for (let i = 0; i < recibosProcessos.length; i++) {
                idSelectRecibo.options[idSelectRecibo.options.length] = new Option(recibosProcessos[i].nrRecEvt, recibosProcessos[i].nrRecEvt);
            }
        }
        if (idSelectLayout.value == "S-2501") {
            idSelectReciboLinha.style.display = 'block';
            idSelectRecibo.options[idSelectRecibo.options.length] = new Option('Selecione o recibo', '0');
            let recibosTributos = JSON.parse(lancamentosTributos.value);
            for (let i = 0; i < recibosTributos.length; i++) {
                idSelectRecibo.options[idSelectRecibo.options.length] = new Option(recibosTributos[i].nrRecEvt, recibosTributos[i].nrRecEvt);
            }
        }
        
    }


    function getServodoresProcesso(numeroProcesso) {
        let numeroProcessoNaoValido = true;
        switch (numeroProcesso.length)
        {
            case 15:
                numeroProcessoNaoValido = false;
                break;
            case 20:
                numeroProcessoNaoValido = false;
                break;
        }
        if (numeroProcessoNaoValido) {
            alert("O número do processo dever ser 15(quinze) ou 20(vinte) algarismos.");
        }
        idPorNumeroProcesso.value = numeroProcesso;
    }

    function excluirProcesso(numeroProcesso) {
        let parametros = new FormData(formExclusaoProcessoJudicial);

        parametros.append('acao', 'salvarExclusao');

        HttpClient.post(rpcProcesso, {body: parametros}).then(response => {
            divMensagemProcesso.removeAttribute("hidden");
            divMensagemProcesso.setAttribute("class", "alert alert-success");
            if (response.erro) {
                divMensagemProcesso.setAttribute("class", "alert alert-danger");
                alert(response.mensagem.urlDecode().replace(/\\n/g, '\n'));
            }
            divMensagemProcesso.innerHTML = response.mensagem.urlDecode().replace(/\\n/g, '\n');
            if (divMensagemProcesso.innerHTML == '') {
                divMensagemProcesso.setAttribute("hidden", "hidden");
            }

            alert(response.mensagem.urlDecode().replace(/\\n/g, '\n'));
        });

    }

</script>

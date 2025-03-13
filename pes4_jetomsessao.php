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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_stdlibwebseller.php");
require_once modification("dbforms/db_funcoes.php");

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link rel="stylesheet" type="text/css" href="estilos/grid.style.css"/>
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHighlight.plugin.js"></script>
    <style>
        #label-comissao {
            cursor: pointer;
        }

        tr td {
            text-align: left;
        }

        #Jandb_iframe_jetomcomissao {
            z-index: 999 !important;
            height: 100% !important;
            width: 100% !important;
        }

    </style>

</head>
<body>
    <script>
        const competencia = {
            ano : "",
            mes : ""
        }
        const competenciaErro = false;
    </script>
    <?php
    $sCompetencia = "";
    try {
        $competencia = \DBPessoal::getCompetenciaFolha();
        $sCompetencia = $competencia->getMes() . "/" . $competencia->getAno();
        echo "
                    <script>
                        competencia.ano = {$competencia->getAno()};
                        competencia.mes = {$competencia->getMes()};
                    </script>";
    } catch (Exception $e) {
        $sCompetencia = $e->getMessage();
        echo "
                    <script>
                        const competenciaErro = true;
                    </script>";
    }

    ?>
    <div class="container">
        <fieldset>
            <legend>Sessões da Compet&eacute;cia - <?=$sCompetencia;?></legend>
            <div id="container-sessoes" style="width: 700px"></div>
        </fieldset>
        <input type="button" value="Novo" id="button-novo">
    </div>

    <div class="container" id="container-window-sessao">
        <form id="form-sessao">
            <input type="hidden" name="rh247_sequencial" id="input-codigo-sessao">
            <fieldset>
                <legend id="titulo_comissao"></legend>
                <table class="form-container">
                    <tr>
                        <td>
                            <label for="input-comissao" id="label-comissao">Comissão:</label>
                        </td>
                        <td>
                            <input type="text" name="rh247_comissao" id="input-comissao" lang="rh242_sequencial">
                            <input type="text" name="input-comissao-descricao" id="input-comissao-descricao"
                                   lang="rh242_descricao">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="input-comissao" id="label-comissao">Compet&eacute;ncia:</label>
                        </td>
                        <td>
                            <input type="text" name="competencia_exibir" id="input-competencia" lang="competencia"
                                   readonly="" class=" field-size2 readonly" style="text-align: right">
                        </td>
                    </tr>
                </table>
            </fieldset>
            <fieldset>
                <legend>Servidores presentes nas sess&otilde;es</legend>
                <table class="form-container">
                    <tr>
                        <td>
                            <div id="container-servidores" style="width: 700px;"></div>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <input type="button" value="Lançar" id="button-lancar">
        </form>
    </div>
    <style>
    .gridcontainer {
        /*overflow-y: scroll;*/
    }

    .header-container, div.body-container, .footer-container {
        /*overflow-y: visible;*/
    }
    .header-container, div.body-container {
        overflow-y: visible;
    }
    div.body-container {
        /*display:    table-row-group;*/
    }

    div.grid-container {
        overflow: scroll;
        background: #ffffff;
    }
    div.body-container {
        /*overflow-x: hidden;*/
    }
    .grid-resize {
        display: none;
    }
</style>
    <script rel="script" type="text/javascript" src="scripts/session.js"></script>
    <script>
        // ------------- Funções tela de manutenção
        const urlBase = "<?php echo ECIDADE_REQUEST_PATH;?>" + 'v4/api/recursos-humanos/pessoal/jetom';
        const formSessao = document.getElementById('form-sessao');
        const labelComissao = document.getElementById('label-comissao');
        const inputCodigoSessao = document.getElementById('input-codigo-sessao');
        const inputComissao = document.getElementById('input-comissao');
        const inputComissaoDescricao = document.getElementById('input-comissao-descricao');
        const buttonLancar = document.getElementById('button-lancar');
        var containerWindowSessao = document.getElementById('container-window-sessao');
        var windowSessao = new windowAux('wSessao', 'Sessões', 800, 600);

        const inputCompetencia = document.getElementById('input-competencia');
        const codigoUsuario = "<?php echo db_getsession('DB_id_usuario');?>";

        windowSessao.setContent(containerWindowSessao);

        const lookupComissao = new DBLookUp(labelComissao, inputComissao, inputComissaoDescricao, {
            sArquivo: 'func_jetomcomissao.php',
            sObjetoLookUp: 'db_iframe_jetomcomissao',
            sLabel: 'Pesquisar Comissão'
        });

        const buscaComissao = (codigo) => {
            colunas = 0;
            extraordinariaMax = 0;
            extraordinariaAtual = 0;
            normalAtual = 0;
            normalMax = 0;
            urgenteAtual = 0;
            urgenteMax = 0;
            criaGridServidores();

            if (!codigo) {
                return;
            }

            /**
             * Adicionado parametro de funcao = 1
             * esse paramemtro é utilizado para validar se a quantidade da funcao foi ultrapassada
             * dentro das sessoes na competencia atual da folha
             */
            HttpClient.get(`${urlBase}/comissao/getComissao?funcao=1&id=${codigo}`).then(response => {
                if (response.error) {
                    return alert(response.message);
                }
                inicializaGridSessoes();
                inicializaOpcoes(response.data.configuracao);

                Object.keys(response.data.servidores).forEach(chave => {
                    adicionaLinhaServidor(response.data.servidores[chave]);
                });

                Object.keys(response.data.sessao).forEach(chave => {
                    carregarColuna(response.data.sessao[chave]);
                });
                gridServidores.reload();
            });

        };

        function adicionaLinhaServidor(servidor) {
            var dados = {};
            var uso = 0;
            var processado = 0;
            uso = servidor.lancamentos.normal.uso + servidor.lancamentos.extraordinaria.uso + servidor.lancamentos.urgente.uso;
            processado = servidor.lancamentos.normal.processado + servidor.lancamentos.extraordinaria.processado + servidor.lancamentos.urgente.processado;
            dados['id'] = servidor.rh245_matricula;
            dados['matricula'] = servidor.rh245_matricula;
            dados['nome'] = servidor.z01_nome;
            dados['limite'] = servidor.limite;
            dados['uso'] = uso + processado;
            dados['processado'] = processado;
            collectionServidores.add(dados);
        }


        lookupComissao.setCallBack('onChange', () => {
            buscaComissao(inputComissao.value);
        });

        lookupComissao.setCallBack('onClick', () => {
            buscaComissao(inputComissao.value);
        });

        const addItemSessao = (item) => {
            var dataItemSessao = item.rh247_data;
            if (dataItemSessao == null) {
                dataItemSessao = "";
            } else {
                if (item.rh247_data.includes('-')) {
                    dataItemSessao = new Date(`${item.rh247_data} 12:00`).getDateBR()
                }
            }

            collectionSessoes.add({
                sequencial: item.rh247_sequencial,
                comissao: item.comissao.rh242_descricao,
                data: dataItemSessao,
                processada: item.rh247_processada ? 'Sim' : 'Não',
                tipoSessao: item.tipo.rh240_descricao
            });
        };

        const resetForm = () => {
            inputCodigoSessao.value = null;
            collectionServidores.clear();
            gridServidores.reload();
            formSessao.reset();
        };

        buttonLancar.addEventListener('click', () => {
            var dados = formataValoresEnvio();
            if (dados.sessoes.length == 0) {
                alert("Nenhuma sessão selecionada para lançamento ou todas sessões selecionadas já foram processadas. Por favor revise as sessões.")
                return false;
            }
            const data = new FormData(formSessao);
            PHPSession.appendFormData(data);
            data.append("comissao", inputComissao.value);
            data.append("competencia", JSON.stringify(competencia));
            data.append("dados", JSON.stringify(dados));

            HttpClient.post(`${urlBase}/sessao`, {body: data}).then(response => {
                alert(response.message);

                if (response.error) {
                    return;
                }

                resetForm();
                windowSessao.hide();
                buscaSessoes();
                gridSessoes.reload();
            });
        });

        // ------------------- Funções tela principal
        const buttonNovo = document.getElementById('button-novo');
        const containerSessoes = document.getElementById('container-sessoes');
        const collectionSessoes = new Collection().setId('sequencial');
        const gridSessoes = DatagridCollection.create(collectionSessoes).configure('order', false);
        var colunas = 0;

        gridSessoes.addColumn('comissao', {label: 'Comissão', align: 'center', width: '30%'});
        gridSessoes.addColumn('data', {label: 'Data', align: 'center', width: '15%'});
        gridSessoes.addColumn('processada', {label: 'Processada', align: 'center', width: '15%'});
        gridSessoes.addColumn('tipoSessao', {label: 'Tipo de Sessão', align: 'center', width: '20%'});

        const manutencaoSessao = (codigo = null) => {
            criaGridServidores();
            if (codigo !== null) {
                HttpClient.get(`${urlBase}/sessao/${codigo}`).then(response => {
                    if (response.error) {
                        return alert(response.message);
                    }
                    selectTipoSessao.value = response.data.tipo.rh240_sequencial;
                    inputCodigoSessao.value = response.data.rh247_sequencial;
                    inputComissao.value = response.data.comissao.rh242_sequencial;
                    inputComissaoDescricao.value = response.data.comissao.rh242_descricao;

                    response.data.comissao.servidores.map(servidorComissao => {
                        collectionServidores.add({
                            sequencial: servidorComissao.rh245_sequencial,
                            matricula: servidorComissao.rh245_matricula,
                            nome: servidorComissao.z01_nome
                        });
                    });

                    gridServidores.reload();

                    response.data.servidores.map(servidorSelecionado => {
                        const itemServidor = collectionServidores.get(servidorSelecionado.rh248_servidor);
                        itemServidor.datagridRow.select(true)
                    });
                });
            } else {
                resetForm();
            }
            exibeCompetencia();
            windowSessao.show();
        };

        gridSessoes.addAction('Excluir', 'Excluir', (event, linha) => {
            if (confirm('Confirma a exclusão da sessão selecionada?')) {
                HttpClient.delete(`${urlBase}/sessao/${linha.sequencial}`).then(response => {
                    if (response.error) {
                        return alert(response.message);
                    }
                    collectionSessoes.remove(linha.sequencial);
                    gridSessoes.reload();
                });
            }
        }, true, 'fa-trash');

        const buscaSessoes = () => {
            HttpClient.get(`${urlBase}/sessao?usuario=${codigoUsuario}`).then(response => {
                if (response.error) {
                    return alert(response.message);
                }
                response.data.map(sessao => addItemSessao(sessao));
                gridSessoes.reload();
            });
        };
        buscaSessoes();

        buttonNovo.addEventListener('click', () => {
            manutencaoSessao();
        });

        gridSessoes.show(containerSessoes);

        const exibeCompetencia = () => {
            if (competenciaErro == false) {
                inputCompetencia.value = competencia.mes + '/' + competencia.ano;
            }
        };

        /**
         * Funcoes relativas a grid de lancamento das sessoes
         */
        var normalMax = 0;
        var normalAtual = 0;
        var extraordinariaMax = 0;
        var extraordinariaAtual = 0;
        var urgenteMax = 0;
        var urgenteAtual = 0;
        var containerServidores = document.getElementById('container-servidores');
        var collectionServidores;
        var gridServidores;
        const inputColunSize = '130px';
        const constNormal = "normal";
        const constExtraordinaria = "extraordinaria";
        const constUrgente = "urgente";
        const constLinhaSelecao = -1;
        const constLinhaTodos = -2;
        const constLinhaData = -3;

        var optionNormal = document.createElement("option");
        optionNormal.value = constNormal;
        optionNormal.text = "Normal";
        var optionExtraordinaria = document.createElement("option");
        optionExtraordinaria.value = constExtraordinaria;
        optionExtraordinaria.text = "Extraordinaria";
        var optionUrgente = document.createElement("option");
        optionUrgente.value = constUrgente;
        optionUrgente.text = "Urgente";

        function resetaOpcoes() {
            optionNormal.removeAttribute("selected");
            optionExtraordinaria.removeAttribute("selected");
            optionUrgente.removeAttribute("selected");
        }

        // Gera o input com as opcoes disponiveis de tipo de sessoes, respeitando as validacoes
        function geraOpcoes(coluna, sequencial, selecionado) {
            var input;
            input = document.createElement('select');
            var normal = optionNormal;
            var extraordinaria = optionExtraordinaria;
            var urgente = optionUrgente;
            var identificador = "id-input-sessao-tipo-" + coluna;
            if (selecionado == "") {
                selecionado = geraOpcaoDefault();
                if (selecionado == false) {
                    input = document.getElementById("adicionarSessao");
                    input.setAttribute("disabled", true);
                    alert ("O limite de sessões na competência foi alcançado.");

                    // Valor fixo -1
                    var id = collectionServidores.__getIndex(constLinhaSelecao);
                    collectionServidores.itens[id]["nome"] = input.outerHTML;
                    colunas = colunas - 1;
                    return false;
                }
            }

            switch (selecionado) {
                case constNormal :
                    normalAtual += 1;
                    normal.setAttribute("selected", "selected");
                    break;
                case constExtraordinaria :
                    extraordinariaAtual += 1;
                    extraordinaria.setAttribute("selected", "selected");
                    break;
                case constUrgente :
                    urgenteAtual += 1;
                    urgente.setAttribute("selected", "selected");
                    break;
            }
            if (normalAtual > normalMax) {
                optionNormal.setAttribute("disabled", "true");
            }
            if (extraordinariaAtual > extraordinariaMax) {
                optionExtraordinaria.setAttribute("disabled", "true");
            }
            if (urgenteAtual > urgenteMax) {
                optionUrgente.setAttribute("disabled", "true");
            }

            input.appendChild(normal);
            input.appendChild(extraordinaria);
            input.appendChild(urgente);
            input.value = selecionado;
            input.setAttribute("data-value", sequencial);
            input.setAttribute("data-coluna", coluna);
            input.setAttribute("onChange", `alterarOpcao(${coluna}, '${selecionado}')`);

            input.id = identificador;
            input.name = identificador;
            return input;
        }

        // Gera a opcao default do select de tipo de sessoes
        function geraOpcaoDefault() {
            if (normalAtual >= normalMax) {
                optionNormal.setAttribute("disabled", "true");
            } else {
                return constNormal;
            }
            if (extraordinariaAtual >= extraordinariaMax) {
                optionExtraordinaria.setAttribute("disabled", "true");
            } else {
                return constExtraordinaria;
            }
            if (urgenteAtual >= urgenteMax) {
                optionUrgente.setAttribute("disabled", "true");
            } else {
                return constUrgente;
            }
            return false;
        }

        // Adiciona o checkbox que seleciona/deseleciona as matriculas da coluna
        function adicionaTodos() {
            var identificador = `grid-input-sessao-${colunas}-todos`;
            var input = document.createElement('input');

            input.type = "checkbox";
            input.id = identificador;
            input.name = identificador;
            input.setAttribute("onChange", `selecionarTodos(${colunas})`);

            return input;
        }

        // Adiciona input do tipo data
        function adicionaData(coluna, valor) {
            var identificador = `grid-input-sessao-${coluna}-calendario`;
            var input = document.createElement('input');

            if (valor != null) {
                input.setAttribute("value", valor);
            }
            input.setAttribute("onChange", `alterarData(${coluna})`);
            input.style.textAlign = 'center';

            input.type = 'date';
            input.id = identificador;
            input.name = identificador;
            return input;
        }

        // Adiciona checkbox para selecionar a matricula
        function adicionaCheckbox(coluna, matricula) {
            var identificador = `grid-input-sessao-${coluna}-matricula-${matricula}`;
            var input = document.createElement('input');
            input.type = "checkbox";
            input.setAttribute("onChange", `alteraQuantidade(${matricula}, ${coluna})`);
            input.setAttribute("data-matricula", matricula);
            input.id = identificador;
            input.name = identificador;
            return input;
        }

        // Carrega a coluna conforme as informacoes do backend
        function carregarColuna(sessao) {
            colunas += 1;
            nomeColuna = 'sessao-' + colunas;
            gridServidores.addColumn(nomeColuna, {label: 'Sessão - ' + colunas, align: 'center', width: inputColunSize});
            var adicionarHelper = false;
            collectionServidores.itens.map(servidor => {
                var input;
                switch (servidor.id) {
                    case constLinhaSelecao:
                        input = geraOpcoes(colunas, sessao.rh247_sequencial, sessao.tipo);
                        break;
                    case constLinhaTodos:
                        input = adicionaTodos(colunas);
                        break;
                    case constLinhaData:
                        input = adicionaData(colunas, sessao.rh247_data);
                        break;
                    default:
                        input = adicionaCheckbox(colunas, servidor.matricula);
                        sessao.matriculas.map(matricula => {
                            if (matricula == servidor.matricula) {
                                input.setAttribute("checked", "true");
                            }
                        });
                        adicionarHelper = true;
                }

                // bloqueia a coluna em caso de processamento
                if (sessao.rh247_processada == true) {
                    var usado = document.createElement('div');
                    usado.style.background = "#DEB887";

                    input.setAttribute("readOnly", true);
                    input.setAttribute("disabled", true);
                    input.className = "readonly";
                    if (adicionarHelper) {
                        input = adicionaHelper(servidor.nome, input);
                    }
                    usado.appendChild(input);
                    input = usado;
                } else {
                    if (adicionarHelper) {
                        input = adicionaHelper(servidor.nome, input);
                    }
                }
                servidor[nomeColuna] = input.outerHTML;
                resetaOpcoes();
                if (servidor.id > 0) {
                    return false;
                }
            });
            gridServidores.show(containerServidores);
        }

        // adiciona uma nova coluna dinamicamente
        function adicionarColuna() {
            colunas += 1;

            nomeColuna = 'sessao-' + colunas;
            gridServidores.addColumn(nomeColuna, {label: 'Sessão - ' + colunas, align: 'center', width: inputColunSize});

            collectionServidores.itens.map(servidor => {
                var input;
                switch (servidor.id) {
                    case constLinhaSelecao:
                        input = geraOpcoes(colunas, 0, "");
                        if (input == false) {
                            gridServidores.removeColumn(nomeColuna);
                            return false;
                        }
                        break;
                    case constLinhaTodos:
                        input = adicionaTodos(colunas);
                        break;
                    case constLinhaData:
                        input = adicionaData(colunas, null);
                        break;
                    default:
                        input = adicionaCheckbox(colunas, servidor.matricula);
                        input = adicionaHelper(servidor.nome, input);
                }
                servidor[nomeColuna] = input.outerHTML;
            });

            gridServidores.show(containerServidores);
        }

        // Seleciona todas as matriculas de uma coluna
        function selecionarTodos(coluna) {
            var marcado = false;
            var input  = document.getElementById(`grid-input-sessao-${coluna}-todos`);

            if (input.checked == true) {
                marcado = true;
            }
            collectionServidores.itens.map(servidor => {
                switch (servidor.id) {
                    case -1 :
                    case -2:
                    case -3:
                        break;
                    default:
                        var idInput = `grid-input-sessao-${coluna}-matricula-${servidor.matricula}`;
                        var inputMatricula = document.getElementById(idInput);
                        inputMatricula.checked =  marcado;
                        if (marcado == true) {
                            inputMatricula.setAttribute("checked", "true");
                        } else {
                            inputMatricula.removeAttribute("checked");
                        }
                        alteraQuantidade(servidor.matricula, coluna);
                }
            });
        }

        // Funcao responsavel pelo controle de sessoes do servidor dentro da competencia
        function alteraQuantidade(matricula, coluna) {
            var identificador = `grid-input-sessao-${coluna}-matricula-${matricula}`;
            var input = document.getElementById(identificador);
            var servidor = collectionServidores.get(matricula);
            if (input.checked == true) {
                input.setAttribute("checked", "true");
                if ((servidor.uso + 1) > servidor.limite) {
                    input.checked = false;
                    input.removeAttribute("checked");
                    alert(`Limite alcançado de ${servidor.limite} sessões, selecionadas para o servidor ${servidor.nome}.`);
                    return false;
                }
                servidor.uso = servidor.uso + 1;
            } else {
                input.removeAttribute("checked");
                servidor.uso = servidor.uso - 1;
                if (servidor.uso < 0) {
                    servidor.uso = 0;
                }
            }
            var id = collectionServidores.__getIndex(matricula);
            collectionServidores.itens[id].uso = servidor.uso;
            atualizaCollection(matricula, `sessao-${coluna}`, identificador)
        }

        // Funcao responsavel por manter o controle de tipos de sessoes na competencia
        function alterarOpcao(coluna, opcaoAtual) {
            normalAtual = 0;
            extraordinariaAtual = 0;
            urgenteAtual = 0;
            var inputs = document.querySelectorAll('[name^=id-input-sessao-tipo-]');
            var inputColuna = document.getElementById(`id-input-sessao-tipo-${coluna}`);
            var retorna = false;

            inputs.forEach(input => {
                switch (input.value) {
                    case constNormal :
                        normalAtual += 1;
                        if (normalAtual > normalMax) {
                            for(var i = 0; i < inputColuna.length; i++) {
                                var opcao = inputColuna[i];
                                if (opcao.value == inputColuna.value) {
                                    opcao.setAttribute("disabled", "true");
                                    retorna = true;
                                }
                            }
                        }
                        break;
                    case constExtraordinaria :
                        extraordinariaAtual += 1;
                        if (extraordinariaAtual > extraordinariaMax) {
                            for(var i = 0; i < inputColuna.length; i++) {
                                var opcao = inputColuna[i];
                                if (opcao.value == inputColuna.value) {
                                    opcao.setAttribute("disabled", "true");
                                    retorna = true;
                                }
                            }
                        }
                        break;
                    default :
                        urgenteAtual += 1;
                        if (urgenteAtual > urgenteMax) {
                            for(var i = 0; i < inputColuna.length; i++) {
                                var opcao = inputColuna[i];
                                if (opcao.value == inputColuna.value) {
                                    opcao.setAttribute("disabled", "true");
                                    retorna = true;
                                }
                            }
                        }
                        break;
                }
            });

            for(var i = 0; i < inputColuna.length; i++) {
                var opcao = inputColuna[i];
                if (opcao.selected) {
                    opcao.removeAttribute("selected");
                }
                if (opcao.value == inputColuna.value) {
                    opcao.setAttribute("selected", "selected");
                }
            }
            atualizaOpcoes(inputColuna.value);
            atualizaOpcoes(opcaoAtual);
            if (retorna == true) {
                alert("Não foi possivel selecionar o tipo, pois irá ultrapassar o limite para o tipo desejado na competência atual.");
                inputColuna.value = opcaoAtual;
            }
            inputColuna.setAttribute("onChange", `alterarOpcao(${coluna}, '${inputColuna.value}')`);
            atualizaCollection(constLinhaSelecao, `sessao-${coluna}`, `id-input-sessao-tipo-${coluna}`);
        }

        // Funcao responsavel por manter o controle de datas das novas colunas quando adicionamos outra coluna
        function alterarData(coluna) {
            var elemento= document.getElementById(`grid-input-sessao-${coluna}-calendario`);
            if (elemento.value !== '') {
                elemento.setAttribute("value", elemento.value);
            }
            atualizaCollection(constLinhaData, `sessao-${coluna}`, `grid-input-sessao-${coluna}-calendario`);
        }

        /**
         *  Funcao responsavel por atualizar 1 elemento especifico dentro da collection, pois cada vez que
         *  adicionamos uma nova coluna, a grid é renderizada com os valores atuais da collection
         *  sendo que cada vez que atualizamos os valores de dentro da grid, nao é atualizado automaticamente o
         *  valor do elemento da collection.
         *  Parametros
         *      linha, (id da collection)
         *      nomeColuna, (coluna da collection)
         *      identificador. (id do elemento da grid)
         */
        function atualizaCollection(linha, nomeColuna, identificador) {
            var elemento= document.getElementById(identificador);
            var id = collectionServidores.__getIndex(linha);
            collectionServidores.itens[id][nomeColuna] = elemento.outerHTML;
        }

        const inicializaGridSessoes = () => {
            var elemento =  document.createElement('input');
            elemento.type = "button";
            elemento.value = "Adicionar Sessão";
            elemento.id = "adicionarSessao";
            elemento.setAttribute("onclick", "adicionarColuna()");

            collectionServidores.add({
                id: constLinhaSelecao,
                matricula: "",
                nome: elemento.outerHTML,
            });
            collectionServidores.add({
                id: constLinhaTodos,
                matricula: "",
                nome: "<strong>Selecionar/Desmarcar Todos</strong>",
            });
            collectionServidores.add({
                id: constLinhaData,
                matricula: "",
                nome: "<strong>Data</strong>",
            });
            gridServidores.show(containerServidores);
        }

        function criaGridServidores() {
            collectionServidores = new Collection().setId('id');
            collectionServidores.clear();

            gridServidores = DatagridCollection.create(collectionServidores).configure('order', false);

            gridServidores.addColumn('id', {label: 'ID', align: 'center', width: inputColunSize});
            gridServidores.addColumn('limite', {label: 'limite', align: 'center', width: inputColunSize});
            gridServidores.addColumn('usado', {label: 'usado', align: 'center', width: inputColunSize});
            gridServidores.addColumn('processado', {label: 'processado', align: 'center', width: inputColunSize});
            gridServidores.addColumn('matricula', {label: 'Matrícula', align: 'center', width: inputColunSize});
            gridServidores.addColumn('nome', {label: 'Nome', align: 'left', width: '250px'});
            gridServidores.hideColumns([0,1,2,3]);
            gridServidores.show(containerServidores);
        }
        criaGridServidores();

        function inicializaOpcoes(configuracao){
            normalMax = configuracao.normal.maximo;
            extraordinariaMax = configuracao.extraordinaria.maximo;
            urgenteMax = configuracao.urgente.maximo;

            optionNormal.removeAttribute("disabled");
            optionExtraordinaria.removeAttribute("disabled");
            optionUrgente.removeAttribute("disabled");
            resetaOpcoes();
            if (configuracao.normal.uso >= normalMax) {
                optionNormal.setAttribute("disabled", "true");
            }
            if (configuracao.extraordinaria.uso >= extraordinariaMax) {
                optionExtraordinaria.setAttribute("disabled", "true");
            }
            if (configuracao.urgente.uso >= urgenteMax) {
                optionUrgente.setAttribute("disabled", "true");
            }

        }

        function atualizaOpcoes(opcao) {
            var inputs = document.querySelectorAll('[name^=id-input-sessao-tipo-]');
            var bloqueia = false;

            switch (opcao) {
                case constExtraordinaria:
                    if (extraordinariaAtual >= extraordinariaMax) {
                        bloqueia = true;
                    }
                    break;
                case constUrgente:
                    if (urgenteAtual >= urgenteMax) {
                        bloqueia = true;
                    }
                    break;
                case constNormal:
                default:
                    if (normalAtual >= normalMax) {
                        bloqueia = true;
                    }
            }

            inputs.forEach(input => {
                for(var i = 0; i < input.length; i++) {
                    var opcaoInput = input[i];
                    var coluna = input.getAttribute("data-coluna");
                    if (opcaoInput.value != input.value) {
                        if (opcaoInput.value == opcao) {
                            if (bloqueia == true) {
                                opcaoInput.setAttribute("disabled", "true");
                            } else {
                                opcaoInput.removeAttribute("disabled");
                            }
                        }
                    }
                    atualizaCollection(constLinhaSelecao, `sessao-${coluna}`, `id-input-sessao-tipo-${coluna}`);
                }
            });
        }

        // Funcao responsavel por formatar os valores de envio para o lancamento em lote
        function formataValoresEnvio(){
            var data = {};
            data.sessoes = [];

            for (var coluna = 1; coluna <= colunas; coluna++) {
                var dados = {
                    matriculas : [],
                    id : 0,
                    tipo : 0,
                };
                var input = document.getElementById('id-input-sessao-tipo-'+coluna);
                var inputData = document.getElementById(`grid-input-sessao-${coluna}-calendario`);
                dados.id = input.getAttribute('data-value');
                dados.tipo = input.value;
                dados.data = inputData.value
                var inputs = document.querySelectorAll(`input[id^="grid-input-sessao-${coluna}-matricula-"]`);
                var processado = false;
                inputs.forEach(inputCheckbox => {
                    // Pegamos os Marcados na tela
                    if (inputCheckbox.checked == true) {
                        var matricula = inputCheckbox.getAttribute("data-matricula");
                        dados.matriculas.push(matricula);
                    }
                    // Caso o input seja readonly, significa que a sessao ja foi processada
                    var inputProcessado = inputCheckbox.getAttribute("readonly");
                    if (inputProcessado == 'true') {
                        processado = true;
                    }
                });
                // Verifica se a coluna ja foi processada, caso ja tenha sido processada, nao vai para o backend
                // Validamos tbm se existe pelo menos uma matricula selecionada para a sessao
                if (processado == false && (dados.matriculas.length > 0)) {
                    data.sessoes.push(dados);
                }
            }
            return data;
        }

        function exibeServidor(event, nomeServidor, idElemento) {
            var elemento = document.getElementById(idElemento);
            var posicoes = elemento.getBoundingClientRect();
            if ($("divExibeServidor")) {
                $("divExibeServidor").parentNode.removeChild($("divExibeServidor"));
            }
            oDivContainer = document.createElement("DIV");
            oDivInterna = document.createElement("DIV");
            oDivContainer.id = "divExibeServidor";
            oDivContainer.style.position = "absolute";
            oDivContainer.style.border = '1px solid #FFDD00';
            oDivContainer.style.display = 'block';
            oDivContainer.style.backgroundColor = '#FFFFCC';
            oDivContainer.style.zIndex = '9999';

            oDivInterna.id = "divExibeServidorInterna";
            oDivInterna.style.overflowX = "hidden";
            oDivInterna.style.overflowY = "auto";

            oDivInterna.innerHTML = nomeServidor;
            oDivContainer.appendChild(oDivInterna);
            var oDivContainerFinal = oDivContainer;

            document.body.appendChild(oDivContainer);
            var tamanho = oDivContainer.clientWidth;

            $("divExibeServidor").parentNode.removeChild($("divExibeServidor"));
            oDivContainerFinal.style.left = (posicoes.x - tamanho)+'px';
            oDivContainerFinal.style.top = (posicoes.y)+'px';
            document.body.appendChild(oDivContainerFinal);
        }

        function escondeServidor() {
            if ($("divExibeServidor")) {
                $("divExibeServidor").parentNode.removeChild($("divExibeServidor"));
            }
        }

        function adicionaHelper(texto, elemento) {
            var div = document.createElement("div");
            div.setAttribute("onmouseover", `exibeServidor(event, '${texto}', '${elemento.id}')`);
            div.setAttribute("onmouseout", `escondeServidor()`);
            div.style.width = "100%";
            div.style.height = "100%";
            div.appendChild(elemento);
            return div;
        }
    </script>
</body>
</html>

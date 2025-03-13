<div>
    <form id="formularioDados" name="formularioDados">
        <div id="abas"></div>
        <div class="container">
            <div id="abaDadosPrevisao" style="max-width: 40%; margin: auto;">
                <?php require_once(modification('forms/db_frmabadadosprevisaodespesa.php')); ?>
            </div>
            <div id="abaPlanosOrcamentarios" class="container">
                <?php require_once(modification('forms/db_frmabaplanosorcamentarios.php')); ?>
            </div>
            <div id="abaLinhasPacto" class="container" style="display: none">
                <fieldset>
                    <legend>Informe os dados da linha pacto</legend>
                    <div class="subcontainer">
                        <form action="">
                            <table class="form-container" style="min-width: 550px">
                                <tr>
                                    <td>
                                        Plano:
                                    </td>
                                    <td>
                                        <input type="hidden" id="linhaplanocodigo" name="linhaplanocodigo"/>
                                        <input style="width:400px" id="linhaplanotitulo" name="linhaplanotitulo"
                                               class="readonly"/>
                                    </td>

                                </tr>
                                <tr>
                                    <td id="ancoraLinhaPacto" nowrap></td>
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
                                    <td>
                                        Valor:
                                    </td>
                                    <td>
                                        <input id="sValorLinhaPacto" onblur="js_ValidaCampos(this, 4, '', 'f', 'f', event)" style="width: 90px;" type="text">
                                    </td>
                                </tr>
                            </table>

                        </form>
                        <input type="button" value="Adicionar" onclick="adicionaLinhaPacto(this)"/>
                    </fieldset>

                    <div class="subcontainer">
                        <fieldset style="width: 700px;">
                            <legend>Adicionar Linhas de Pacto</legend>
                            <div id="linhasPacto"></div>
                        </fieldset>
                    </div>

            </div>

            <input type="submit" value="Salvar" id="salvar">
            <input type="submit" value="Salvar" id="salvarPlanoOrc" style="display: none">
            <input type="button" id="pesquisar" value="Pesquisar" style="display: none">
            <input type="button" id="limpar" value="Novo">
        </div>
    </form>
</div>

<?php db_menu(); ?>
<script>
    var codigo = null;


    const ano = 2019;
    const collection = new Collection();
    collection.setId('codigo');

    const gridPlanoOrcamentario = DatagridCollection.create(collection).configure({'order': false, 'height': 400});

    const codigoDotacao = document.querySelector('#codigoDotacao');
    const unidadeOrcamentariaAncora = document.querySelector('#unidadeOrcamentariaAncora');
    const unidadeOrcamentaria = document.querySelector('#unidadeOrcamentaria');
    const unidadeOrcamentariaDescricao = document.querySelector('#unidadeOrcamentariaDescricao');
    const funcaoAncora = document.querySelector('#funcaoAncora');
    const funcao = document.querySelector('#funcao');
    const funcaoDescricao = document.querySelector('#funcaoDescricao');
    const subfuncaoAncora = document.querySelector('#subfuncaoAncora');
    const subfuncao = document.querySelector('#subfuncao');
    const subfuncaoDescricao = document.querySelector('#subfuncaoDescricao');
    const programaAncora = document.querySelector('#programaAncora');
    const programa = document.querySelector('#programa');
    const programaDescricao = document.querySelector('#programaDescricao');
    const acaoAncora = document.querySelector('#acaoAncora');
    const acao = document.querySelector('#acao');
    const acaoDescricao = document.querySelector('#acaoDescricao');
    const subtituloAncora = document.querySelector('#subtituloAncora');
    const subtitulo = document.querySelector('#subtitulo');
    const subtituloDescricao = document.querySelector('#subtituloDescricao');
    const naturezaDespesaAncora = document.querySelector('#naturezaDespesaAncora');
    const naturezaDespesa = document.querySelector('#naturezaDespesa');
    const naturezaDespesaDescricao = document.querySelector('#naturezaDespesaDescricao');
    const estrutural = document.querySelector('#estrutural');
    const previsao2019 = new DBInputValor(document.querySelector('#previsao2019'));
    const previsao2019hidden =  (document.querySelector('#previsao2019hidden'));

    const esferaOrcamentaria = document.querySelector('#esferaOrcamentaria');
    const identificadorUso = document.querySelector('#identificadorUso');
    const tipoDetalhamento = document.querySelector('#tipoDetalhamento');
    const grupoFonteRecurso = document.querySelector('#grupoFonteRecurso');
    const especificacaoFonte = document.querySelector('#especificacaoFonte');
    const identificadorResultadoPrimario = document.querySelector('#identificadorResultadoPrimario');
    const abas = new DBAbas(document.querySelector('#abas'));
    const linhaPlano = document.querySelector('#linhaplanocodigo');

    var codigoOrcamentario = 0;

    new DBInputValor($('po_valor'));

    const salvarBotao = document.querySelector('#salvar');
    const salvarOrcLinhaBotao = document.querySelector('#salvarPlanoOrc');

    const limparBotao = document.querySelector('#limpar');
    const pesquisarBotao = document.querySelector('#pesquisar');

    const collectionLinha = new Collection();
    collectionLinha.setId('codigo');


    const gridLinhaPacto = DatagridCollection.create(collectionLinha).configure({
        'order': false,
        'height': 200,
        'width': 300
    });

    gridLinhaPacto.addColumn('codigo', {
        label: 'Código',
        align: 'left',
        width: '100px',
    });

    gridLinhaPacto.addColumn('descricao', {
        label: 'Título',
        align: 'left',
        width: '350px',
    });

    gridLinhaPacto.addColumn('valor', {
        label: 'Valor',
        align: 'right',
        width: '100px',
    }).transform('number');

    gridLinhaPacto.addAction("Excluir", null, function(oEvento, oItem) {

        var planoOrcaSelec = $F("linhaplanocodigo");

        collectionLinha.remove(oItem.codigo);
        collection.get(planoOrcaSelec).linhaPacto = collection.get(planoOrcaSelec).linhaPacto.filter(function (item) {
            return item.codigo != oItem.codigo;
        });

        gridLinhaPacto.reload();

    });

    gridLinhaPacto.show($('linhasPacto'));

    const linhaAncora = new DBAncora("Linhas de Pacto: ");


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
        sQuery += oParametros.sStringAdicional == "" ? "" : '&' + oParametros.sStringAdicional;

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

    function adicionaLinhaPacto(element) {

        var codigoSelecionado = $F("linhaplanocodigo");
        var planoOrca = collection.get(codigoSelecionado);
        var valorLinha = $F('sValorLinhaPacto');
        var somaLinhaPacto = 0;
        var codigoPacto = $F('iCodigoLinhaPacto');

        valorLinha = parseFloat(valorLinha);

        if (!codigoPacto) {
            alert("Código da linha de pacto deve ser preenchido.");
            return false;
        }

        if (!valorLinha) {
            alert("Valor da linha de pacto deve ser preenchido.");
            return false;
        }

        if (valorLinha > planoOrca.valor) {
            alert("Valor da linha de pacto não pode ser maior que o do plano orçamentario.");
            return false;
        }

        collection.get(codigoSelecionado).linhaPacto.each(function (oLinhaInsert) {
            somaLinhaPacto = somaLinhaPacto + parseFloat(oLinhaInsert.valor);
        });


        somaLinhaPacto = valorLinha + somaLinhaPacto;

        if (somaLinhaPacto > planoOrca.valor) {
            alert("Valor total das linhas de pacto não pode ser maior que o do orçamento.");
            return false;
        }

        collectionLinha.add({
            codigo: codigoPacto,
            descricao: $F('sDescricaoLinhaPacto'),
            valor: valorLinha

        });

        collection.get(codigoSelecionado).linhaPacto.push({
            codigo: $F('iCodigoLinhaPacto'),
            valor: $F('sValorLinhaPacto'),
            descricao: $F('sDescricaoLinhaPacto')
        });

        $('iCodigoLinhaPacto').value = "";
        $('sDescricaoLinhaPacto').value = "";
        $('sValorLinhaPacto').value = "";

        gridLinhaPacto.reload();
    }

    criarLookUps();
    criarListeners();

    const abaPrevisao = abas.adicionarAba('Dados da Previsão', document.querySelector('#abaDadosPrevisao'), true);
    const abaPlanos = abas.adicionarAba('Planos Orçamentários', document.querySelector('#abaPlanosOrcamentarios')).bloquear();

    abaPrevisao.setCallback(function () {

        $('pesquisar').show();
        $('salvar').show();
        $('salvarPlanoOrc').hide();
    });

    abaPlanos.setCallback(function () {

        $('pesquisar').hide();
        $('salvar').hide();
        $('salvarPlanoOrc').show();
    });

    function criarListeners() {
        unidadeOrcamentariaAncora.addEventListener('click', buscarUnidadeOrcamentaria);
        salvarBotao.addEventListener('click', salvar);
        limparBotao.addEventListener('click', limpar);
        salvarOrcLinhaBotao.addEventListener('click', salvarPlanoLinhas);
        pesquisarBotao.addEventListener('click', pesquisar);
    }

    function salvarPlanoLinhas(event) {

        event.preventDefault();

        var error = {error: false, mesage: ""};

        var data = [];

        var somaPo = 0;
        collection.itens.each(function (oPlanoLinha) {

            var item = {};

            item.codigo = oPlanoLinha.codigo;
            item.descricao = oPlanoLinha.descricao;
            item.valor = oPlanoLinha.valor;

            if (oPlanoLinha.c55_sequencial) {
                item.c55_sequencial = oPlanoLinha.c55_sequencial;
            }

            item.linhasPacto = oPlanoLinha.linhaPacto;

            var valorPO = parseFloat(oPlanoLinha.valor.replace(".", "").replace(",", "."));
            var valorTotalLinhaPacto = 0;

            if (oPlanoLinha.linhaPacto.length == 0) {
                error.error = true;
                error.mesage = "O Plano Orçamentário " + oPlanoLinha.descricao + " está sem linha de Pacto.";

            }

            oPlanoLinha.linhaPacto.each(function (oLinhaPacto) {
                valorTotalLinhaPacto = valorTotalLinhaPacto + parseFloat(oLinhaPacto.valor);

            });

            if (valorTotalLinhaPacto != valorPO) {
                error.error = true;
                error.mesage = "O valor do plano orçamentário " + oPlanoLinha.descricao + " não bate com os da linha de pacto.";
            }

            somaPo = somaPo + parseFloat(valorPO);

            data.push(item);

        });

        var valorPrevisaototal = previsao2019.getValue();

        if (somaPo < valorPrevisaototal) {
            alert("Planos  orçamentário  não batem com valor da despesa.");
            return false;
        }

        if (error.error) {
            alert(error.mesage);
            return false;
        }

        const parametros = new FormData();
        parametros.append('exec', 'adicionarPlanoLinhaPacto');
        parametros.append('codigoPrevisao', $F('codigoDotacao'));
        parametros.append('itens', JSON.stringify(data));

        js_divCarregando('Adicionado vinculo entre linha de pacto e Plano oramentrio.', 'loading_message');

        return fetch('con1_previsao_despesa.RPC.php', {
            method: 'POST',
            body: parametros,
            credentials: 'include',
        }).then(function (response) {
            if (response.ok) {
                response.json().then(function (value) {
                    alert(value.mensagem);

                });
            }

            js_removeObj('loading_message');
        });

    }

    function salvar(event) {
        event.preventDefault();

        if (!validar()) {
            return false;
        }

        var planoOrcamentario = collection.build();
        planoOrcamentario.forEach(function (dado) {
            dado.valor = dado.valor.toString().replace(',', '.');
            dado.valor = parseFloat(dado.valor).toFixed(2);
        });

        const parametros = new FormData(document.getElementById('formularioDados'));
        parametros.append('exec', 'salvar');
        parametros.append('ano', ano);
        parametros.append('previsao2019', previsao2019.getValue());
        parametros.append('previsao2019alterada', previsao2019hidden.value);

        if (codigo) {
            parametros.append('codigo', codigo);

            var confirmAlteracao = false;
            if (previsao2019.getValue() != parseFloat(previsao2019hidden.value)) {
                confirmAlteracao  = confirm("Valor da previsão alterado, com isso todos planos orçamentarios e linhas de pacto serão apagados.");
                if (!confirmAlteracao) {
                    return false;
                }
            }

            collection.clear();
        }

        js_divCarregando('Salvando Formulário', 'loading_message');

        return fetch('con1_previsao_despesa.RPC.php', {
            method: 'POST',
            body: parametros,
            credentials: 'include',
        }).then(function (response) {
            return response.json()
        }).then(function (response) {
            alert(response.mensagem);

            if (!response.erro) {
                codigoDotacao.value = response.codigo;
                codigo = response.codigo;
                previsao2019hidden.value  = response.previsao2019;
                abaPlanos.desbloquear();
                abas.mostraFilho(abaPlanos);
                $('pesquisar').hide();
                $('salvar').hide();
                $('limpar').hide();
                $('salvarPlanoOrc').show();
                if (response.planoPadrao) {
                    collection.add(response.planoPadrao);
                    gridPlanoOrcamentario.reload();
                }
            }

            js_removeObj('loading_message');

        }).catch(function () {
            alert('Não foi possível salvar o formulário.');
        })
    }

    function validar() {
        if (!esferaOrcamentaria.value) {
            alert('É necessário preencher o campo "Esfera Orçamentária".');
            return false;
        }
        if (!unidadeOrcamentaria.value) {
            alert('É necessário preencher o campo "Unidade Orçamentária".');
            return false;
        }
        if (!funcao.value) {
            alert('É necessário preencher o campo "Função".');
            return false;
        }
        if (!subfuncao.value) {
            alert('É necessário preencher o campo "Subfunção".');
            return false;
        }
        if (!programa.value) {
            alert('É necessário preencher o campo "Programa".');
            return false;
        }
        if (!acao.value) {
            alert('É necessário preencher o campo "Ação".');
            return false;
        }
        if (!subtitulo.value) {
            alert('É necessário preencher o campo "Subtítulo".');
            return false;
        }
        if (!naturezaDespesa.value) {
            alert('É necessário preencher o campo "Natureza de Despesa".');
            return false;
        }
        if (!identificadorUso.value) {
            alert('É necessário preencher o campo "Identificador de Uso".');
            return false;
        }
        if (!tipoDetalhamento.value) {
            alert('É necessário preencher o campo "Tipo de Detalhamento".');
            return false;
        }
        if (!grupoFonteRecurso.value) {
            alert('É necessário preencher o campo "Grupo de Fonte de Recurso".');
            return false;
        }
        if (!especificacaoFonte.value) {
            alert('É necessário preencher o campo "Especificação da Fonte de Recurso".');
            return false;
        }
        if (!identificadorResultadoPrimario.value) {
            alert('É necessário preencher o campo "Identificador de Resultado Primário".');
            return false;
        }
        if (!previsao2019.getValue()) {
            alert('É necessário preencher o campo "Previsão 2019"');
            return false;
        }

        return true;
    }

    function criarLookUps() {
        const lookUpFuncao = new DBLookUp(funcaoAncora, funcao, funcaoDescricao, {
            'sArquivo': 'func_orcfuncao.php',
            'sLabel': 'Pesquisar Função',
        });

        lookUpFuncao.setCallBack('onChange', carregarFuncaoChange);
        lookUpFuncao.setCallBack('onClick', carregarFuncao);

        function carregarFuncao(parametros) {
            funcao.value = parametros[0].padStart(2, '0');
        }

        function carregarFuncaoChange() {
            if (funcao.value) {
                funcao.value = funcao.value.padStart(2, '0');
            }
        }

        const lookUpSubfuncao = new DBLookUp(subfuncaoAncora, subfuncao, subfuncaoDescricao, {
            'sArquivo': 'func_orcsubfuncao.php',
            'sLabel': 'Pesquisar Subfunção',
        });

        lookUpSubfuncao.setCallBack('onChange', carregarSubfuncaoChange);
        lookUpSubfuncao.setCallBack('onClick', carregarSubfuncao);

        function carregarSubfuncao(parametros) {
            subfuncao.value = parametros[0].padStart(3, '0');
        }

        function carregarSubfuncaoChange() {
            if (subfuncao.value) {
                subfuncao.value = subfuncao.value.padStart(3, '0');
            }
        }

        const lookUpPrograma = new DBLookUp(programaAncora, programa, programaDescricao, {
            'sArquivo': 'func_orcprograma.php',
            'sLabel': 'Pesquisar Programa',
            'aParametrosAdicionais': ['previsao=true', 'ano=' + ano],
        });

        lookUpPrograma.setCallBack('onChange', carregarProgramaChange);
        lookUpPrograma.setCallBack('onClick', carregarPrograma);

        function carregarPrograma(parametros) {
            programa.value = parametros[0].padStart(4, '0');
        }

        function carregarProgramaChange() {
            if (programa.value) {
                programa.value = programa.value.padStart(4, '0');
            }
        }

        const lookUpAcao = new DBLookUp(acaoAncora, acao, acaoDescricao, {
            'sArquivo': 'func_orcprojativ.php',
            'sLabel': 'Pesquisar Ação',
            'aParametrosAdicionais': ['previsao=true', 'ano=' + ano],
        });

        lookUpAcao.setCallBack('onChange', carregarAcaoChange);
        lookUpAcao.setCallBack('onClick', carregarAcao);

        function carregarAcao(parametros) {
            acao.value = parametros[0].padStart(4, '0');
        }

        function carregarAcaoChange() {
            if (acao.value) {
                acao.value = acao.value.padStart(4, '0');
            }
        }

        const lookUpSubtitulo = new DBLookUp(subtituloAncora, subtitulo, subtituloDescricao, {
            'sArquivo': 'func_ppasubtitulolocalizadorgasto.php',
            'sLabel': 'Pesquisar Ação',
        });

        lookUpSubtitulo.setCallBack('onChange', carregarSubtituloChange);
        lookUpSubtitulo.setCallBack('onClick', carregarSubtitulo);

        function carregarSubtitulo(parametros) {
            subtitulo.value = parametros[0].padStart(4, '0');
        }

        function carregarSubtituloChange() {
            if (subtitulo.value) {
                subtitulo.value = subtitulo.value.padStart(4, '0');
            }
        }

        var lookupNaturezaDespesa = new DBLookUp(naturezaDespesaAncora, estrutural, naturezaDespesaDescricao, {
            'sArquivo': 'func_conplanoorcamento.php',
            'sLabel': 'Pesquisar Conta',
            'aParametrosAdicionais': ['sSomenteEstrutural=3', 'filtrosEstrutural=true', 'filtrosEstruturalSintetico=true','ano=' + ano],
        });

        lookupNaturezaDespesa.setCamposAdicionais(['c60_codcon']);
        lookupNaturezaDespesa.setCallBack('onClick', carregarNatureza);

        function carregarNatureza(params) {
            naturezaDespesa.value = params[2];
        }
    }

    function buscarUnidadeOrcamentaria() {
        const onde = '';
        const nome = 'db_iframe_unidade_orcamentaria';
        const arquivo = 'func_db_config_orcunidade.php';
        const titulo = 'Pesquisar Unidade Orçamentária';
        const mostra = true;
        const campos = '|o41_orgao|o41_unidade|o40_descr|o41_descr';
        const funcao = '?previsao=true&ano=' + ano + '&funcao_js=parent.' + preencherUnidadeOrcamentaria.name;

        js_OpenJanelaIframe('', nome, arquivo + funcao + campos, titulo, mostra);
    }

    function preencherUnidadeOrcamentaria(chave1, chave2, chave3, chave4) {
        const orgao = chave1.padStart(2, '0');
        const unidade = chave2.padStart(2, '0');
        const codigoTribunal = orgao + unidade;
        const orgaoUnidade = chave3 + ' / ' + chave4;

        unidadeOrcamentaria.value = codigoTribunal;
        unidadeOrcamentariaDescricao.value = orgaoUnidade;
        db_iframe_unidade_orcamentaria.hide();
    }

    gridPlanoOrcamentario.addColumn('codigo', {
        label: 'Código',
        align: 'left',
        width: '100px',
    });

    gridPlanoOrcamentario.addColumn('descricao', {
        label: 'Título',
        align: 'left',
        width: '300px',
    });

    gridPlanoOrcamentario.addColumn('valor', {
        label: 'Valor',
        align: 'right',
        width: '100px',
    }).transform('number');

    gridPlanoOrcamentario.addAction('Linha de Pacto', 'adicionar', function (e, dados) {


        var windowDocumentos = new windowAux('windowDocumentos', 'Linhas de Pacto', 800, 500);

        windowDocumentos.setContent($("abaLinhasPacto"));
        windowDocumentos.setIndex(1);


        $("abaLinhasPacto").show();
        windowDocumentos.show();

        gridLinhaPacto.clear();


        collection.get(dados.codigo).linhaPacto.each(function (value) {
            collectionLinha.add({codigo : value.codigo, valor: value.valor ,descricao: value.descricao});
        })


        gridLinhaPacto.reload();

        $("iCodigoLinhaPacto").value = "";
        $("sDescricaoLinhaPacto").value = "";
        $("sValorLinhaPacto").value = "";

        $("linhaplanocodigo").value = dados.codigo;
        $("linhaplanotitulo").value = dados.descricao;
    });

    gridPlanoOrcamentario.addAction('Excluir', 'Excluir', function (e, dados) {

        var msg = 'Confirma a exclusão do plano orçamentário ' + dados.codigo + ' - ' + dados.descricao + ' no valor ';
        msg += 'de ' + js_formatar(dados.valor, 'f') + '?';
        if (!confirm(msg)) {
            return;
        }

        collection.remove(dados.codigo);

        if (codigoOrcamentario > 0) {
            codigoOrcamentario--;
        }

        gridPlanoOrcamentario.reload();
    });

    // renderiza grid
    gridPlanoOrcamentario.show($('ctnGridPlanoOrcamentario'));

    function permiteInserirPlanoOrcamentario(valor) {
        const planosInclusos = collection.get();
        var valorPlanosInclusos = 0;
        const valorPlanoNovo = parseFloat(valor);
        const valorDespesa = parseFloat(previsao2019.getValue());
        
        planosInclusos.forEach(function (plano) {
            valorPlanosInclusos += parseFloat(plano.valor);
        });

        return (valorPlanosInclusos + valorPlanoNovo) <= valorDespesa;
    }

    $('btn-po-adicionar').addEventListener('click', function () {
        const tituloPlano = $F('po_descricao');
        const valorPlano = $F('po_valor');

        if (tituloPlano === '') {
            alert('Campo Título deve ser informado.');
            return;
        }
        if (valorPlano === '') {
            alert('Campo Valor deve ser informado.');
            return;
        }

        if (!permiteInserirPlanoOrcamentario(valorPlano)) {
            alert("Não foi possível inserir o Plano Orçamentário '" + tituloPlano + "', pois o valor total dos planos inclusos ultrapassam o valor da despesa.");
            return false;
        }

        const parametros = new FormData();
        parametros.append('exec', 'adicionarPlanoOrcamentario');
        parametros.append('codigoPrevisao', $F('codigoDotacao'));
        parametros.append('titulo', tituloPlano);
        parametros.append('valor', valorPlano);

        codigoOrcamentario++;

        collection.add({
            sId: codigoOrcamentario,
            codigo: codigoOrcamentario,
            descricao: tituloPlano,
            valor: valorPlano,
            linhaPacto: []
        });

        gridPlanoOrcamentario.reload();

        $('po_descricao').value = '';
        $('po_valor').value = '';

    });

    function limpar() {
        location.href = 'con1_previsao_despesa.php';
    }

    function pesquisar() {
        const onde = '';
        const nome = 'db_iframe_previsaodespesa';
        const arquivo = 'func_previsaodespesa.php';
        const titulo = 'Pesquisar previsão de despesa';
        const campos = '|c333_sequencial';
        const funcao = '?funcao_js=parent.' + preencherFormulario.name;

        js_OpenJanelaIframe(onde, nome, arquivo + funcao + campos, titulo, true);
    }

    function preencherFormulario(sequencial) {
        db_iframe_previsaodespesa.hide();
        js_divCarregando('Carregando Previsão de Receita', 'loading_message');

        const parametros = new FormData();
        parametros.append('exec', 'pesquisar');
        parametros.append('sequencial', sequencial);

        return fetch('con1_previsao_despesa.RPC.php', {
            method: 'POST',
            body: parametros,
            credentials: 'include',
        }).then(function (response) {
            return response.json()
        }).then(function (response) {
            if (response.erro) {
                return alert(response.mensagem);
            }


            codigoDotacao.value = response.previsao.c333_sequencial;
            codigo = response.previsao.c333_sequencial;
            esferaOrcamentaria.value = response.previsao.c333_esferaorcamentaria.padStart(2, '0');
            unidadeOrcamentaria.value = response.previsao.c333_orcorgao.padStart(2, '0') +
                response.previsao.c333_orcunidade.padStart(2, '0');
            unidadeOrcamentariaDescricao.value = response.previsao.unidade_orcamentaria;
            funcao.value = response.previsao.c333_orcfuncao.padStart(2, '0');
            funcaoDescricao.value = response.previsao.o52_descr;
            subfuncao.value = response.previsao.c333_orcsubfuncao.padStart(3, '0');
            subfuncaoDescricao.value = response.previsao.o53_descr;
            programa.value = response.previsao.c333_orcprograma.padStart(4, '0');
            programaDescricao.value = response.previsao.o54_descr;
            acao.value = response.previsao.c333_orcprojativ.padStart(4, '0');
            acaoDescricao.value = response.previsao.o55_descr;
            subtitulo.value = response.previsao.c333_ppasubtitulolocalizadorgasto.padStart(4, '0');
            subtituloDescricao.value = response.previsao.o11_descricao;
            estrutural.value = response.previsao.estrutural;
            naturezaDespesa.value = response.previsao.c333_conplanoorcamento;
            naturezaDespesaDescricao.value = response.previsao.c60_descr;
            previsao2019.value = response.previsao.c333_previsao;
            previsao2019hidden.value = response.previsao.c333_previsao;

            esferaOrcamentaria.value = response.previsao.c333_esferaorcamentaria;
            identificadorUso.value = response.previsao.c333_identificadoruso;
            tipoDetalhamento.value = response.previsao.c333_tipodetalhamento;
            grupoFonteRecurso.value = response.previsao.c333_grupofonterecursos;
            especificacaoFonte.value = response.previsao.c333_especificacaofonte;
            identificadorResultadoPrimario.value = response.previsao.c333_identificadorresultadoprimario;

            gridPlanoOrcamentario.clear();

            abaPlanos.desbloquear();


            response.previsao.planos.forEach(function (plano) {
                collection.add(plano);
            });

            codigoOrcamentario = collection.count();

            gridPlanoOrcamentario.reload();
            js_removeObj('loading_message');
        }).catch(function () {
            alert('Não foi possível buscar a previsão de despesa.')
        });
    }


</script>

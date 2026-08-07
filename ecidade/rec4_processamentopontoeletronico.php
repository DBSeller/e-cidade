<?php
require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_app.utils.php'));
require_once(modification('dbforms/db_funcoes.php'));
?>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <link href="estilos.css" rel="stylesheet" type="text/css">
        <link href="" rel="stylesheet" type="text/css">
        <?php
        db_app::load(array(
            "strings.js",
            "scripts.js",
            "dates.js",
            "prototype.js",
            "strings.js",
            "AjaxRequest.js",
            "widgets/DBLookUp.widget.js",
            "widgets/DBLancador.widget.js",
            "widgets/Collection.widget.js",
            "widgets/DatagridCollection.widget.js",
            "widgets/datagrid/plugins/DBHint.plugin.js",
            "widgets/Input/DBInput.widget.js",
            "widgets/Input/DBInputDate.widget.js",
            "classes/recursoshumanos/Efetividade/PeriodoEfetividade.js",
            "estilos.css",
            "grid.style.css"
        ));
        ?>
        <style type="text/css">
            #botoesPaginacao {
                margin-top: 8px;
            }

            #labelPaginas {
                margin: 0 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <form>
                <fieldset>
                    <legend>Processamento do Ponto Eletrônico</legend>
                    <table class="form-container">
                        <tr>
                            <td>
                                <label for="periodoInicio">Período:</label>
                            </td>
                            <td id="linhaPeriodo"></td>
                        </tr>
                        <tr>
                            <td>
                                <label for="inicializarMarcacoes">Reinicializar marcações:</label>
                            </td>
                            <td colspan="2" class="field-size-max">
                                <select id="inicializarMarcacoes" style="width: 84px;">
                                    <option value="1" >Sim</option>
                                    <option value="0" selected>Não</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="tipoFiltro">Filtrar por:</label>
                            </td>
                            <td colspan="2" class="field-size-max">
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
                            <td id="matricula" colspan="3"></td>
                        </tr>
                    </table>
                </fieldset>
                <input id="processar" type="button" value="Processar"/>
            </form>
            <fieldset>
                <legend>Servidores</legend>
                <div id="containerGridMatriculas"></div>
                    <div id="botoesPaginacao">
                        <input id="primeira" type="button"  value="Primeira"  disabled />
                        <input id="voltar" type="button"  value="Voltar"    disabled />
                        <span id="labelPaginas">Página <span id="nroPaginaAtual"></span> de <span id="nroTotalPaginas"></span>
                        </span>
                        <input id="avancar"   type="button"  value="Avançar"   disabled />
                        <input id="ultima"    type="button"  value="Última"    disabled />
                    </div>
            </fieldset>
        </div>
    </body>
    <?php db_menu(); ?>
    <script type="text/javascript">
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

        var oPeriodoEfetividade = new PeriodoEfetividade();
        oPeriodoEfetividade.show($('linhaPeriodo'));

        var totalMatriculas  = 0;
        var paginaAtual      = 1;
        var qtdePorPagina    = 14;

        var collectionMatriculas     = Collection.create().setId('matricula');
        var collectionGridMatriculas = Collection.create().setId('matricula');

        var gridMatriculas    = new DatagridCollection(collectionGridMatriculas, 'gridMatriculas').configure({'order' : false});
        gridMatriculas.addColumn('matricula',   {'width': '20%', 'label': 'Matrícula'});
        gridMatriculas.addColumn('nome',        {'width': '60%', 'label': 'Nome'});
        gridMatriculas.addColumn('status',      {'width': '20%', 'label': 'Status'});
        gridMatriculas.configure({'height': '250px'});
        gridMatriculas.show($('containerGridMatriculas'));

        var oLancadorMatricula = new DBLancador('oLancadorMatricula');
        oLancadorMatricula.setLabelAncora('Matrícula:');
        oLancadorMatricula.setNomeInstancia('oLancadorMatricula');
        oLancadorMatricula.setTituloJanela('Pesquisa de Matrícula');
        oLancadorMatricula.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist', 'z01_nome']);
        oLancadorMatricula.setTextoFieldset('Matrículas');
        oLancadorMatricula.setGridHeight(150);
        oLancadorMatricula.adicionarItensPrimeiraPosicao(true);

        oLancadorMatricula.setCallbackBotao(function() {
            $('txtCodigooLancadorMatricula').focus();
        });

        oLancadorMatricula.show($('matricula'));

        $('tipoFiltro').observe('change', function() {
            $('linhaSelecao').setStyle({'display': 'none'});
            $('linhaMatricula').setStyle({'display': 'none'});

            /**
             * Filtrar por Seleção
             */
            if($F('tipoFiltro') == 1) {
                $('linhaSelecao').setStyle({'display': ''});
                $('linhaMatricula').setStyle({'display': 'none'});
                oLancadorMatricula.clearAll();
            }

            /**
             * Filtrar por Matrícula
             */
            if($F('tipoFiltro') == 2) {
                $('linhaSelecao').setStyle({'display': 'none'});
                $('linhaMatricula').setStyle({'display': ''});
                $('r44_selec').value = '';
                $('r44_descr').value = '';
            }
        });

        $('processar').observe('click', function () {
            aMatriculasEnviar = [];
            oLancadorMatricula.getRegistros().each(function(matricula) {
                aMatriculasEnviar.push(matricula.sCodigo);
            });

            var sDataInicio = oPeriodoEfetividade.getDataFormatada(oPeriodoEfetividade.getDataInicio());
            var sDataFim    = oPeriodoEfetividade.getDataFormatada(oPeriodoEfetividade.getDataFim());

            if(sDataFim == '' || sDataFim == null) {
                sDataFim = sDataInicio;
            }

            var oAjaxRequest = new AjaxRequest(
                'rec4_processamentopontoeletronico.RPC.php',
                {
                    'exec'                : 'processarPontoEletronico',
                    'dataInicio'          : sDataInicio,
                    'dataFim'             : sDataFim,
                    'matriculasEnviar'    : aMatriculasEnviar,
                    'selecao'             : $F('r44_selec'),
                    'inicializarMarcacoes': $F('inicializarMarcacoes'),
                },
                function (response, erro) {
                    if(response.mensagem) {
                        alert(response.mensagem);
                    }

                    if(erro) {
                        return;
                    }
                    collectionMatriculas.clear();
                    for(var servidor of response.servidores) {
                        collectionMatriculas.add(servidor);
                    }

                    totalMatriculas = collectionMatriculas.get().length;

                    $('primeira').disabled  = true;
                    $('voltar').disabled    = true;
                    $('avancar').disabled   = true;
                    $('ultima').disabled    = true;

                    if(totalMatriculas > qtdePorPagina) {
                        $('avancar').disabled = false;
                    }

                    paginarGrid();
                }
            ).setMessage("Aguarde... processando dados do ponto.").execute();
        });

        $('primeira').observe('click', function () {
            paginaAtual = 1;
            paginarGrid();
        });

        $('voltar').observe('click', function () {
            paginaAtual--;
            paginarGrid();
        });

        $('avancar').observe('click', function () {
            paginaAtual++;
            paginarGrid();
        });

        $('ultima').observe('click', function () {
            paginaAtual = totalPaginas;
            paginarGrid();
        });

        function atualizarQualUltimaPagina() {
            totalPaginas = parseInt(totalMatriculas / qtdePorPagina);
            if((totalMatriculas % qtdePorPagina) > 0) {
                totalPaginas++;
            }
        }

        function paginarGrid() {

            $('primeira').disabled  = true;
            $('voltar').disabled    = true;
            $('avancar').disabled   = true;
            $('ultima').disabled    = true;

            atualizarQualUltimaPagina();

            $('nroPaginaAtual').innerHTML  = paginaAtual;
            $('nroTotalPaginas').innerHTML = totalPaginas;

            if(totalMatriculas > qtdePorPagina) {

                if(paginaAtual < totalPaginas) {
                    $('avancar').disabled = false;
                    $('ultima').disabled  = false;
                }

                if(paginaAtual > 1) {
                    $('primeira').disabled = false;
                    $('voltar').disabled   = false;
                }
            }

            var itemFinalPagina   = paginaAtual * qtdePorPagina -1;
            var itemInicialPagina = itemFinalPagina - qtdePorPagina +1;

            /**
             * Zerando a collection de matrículas
             */
            collectionGridMatriculas.clear();
            collectionMatriculas.get().forEach(function (item, i) {

                if(i >= itemInicialPagina && i <= itemFinalPagina) {

                    collectionGridMatriculas.add({
                        'matricula'  : item.matricula,
                        'nome'       : item.nome,
                        'status'     : item.status,
                        'erro'       : item.erro,
                    });
                }
            });
            gridMatriculas.reload();

            hintErros();
        }
        
        function hintErros() {
            collectionGridMatriculas.get().forEach(function (item, i) {
                if(item.erro.trim() != '') {
                    gridMatriculas.grid.setHint(i, 2, item.erro);
                }
            });
        }
    </script>
</html>

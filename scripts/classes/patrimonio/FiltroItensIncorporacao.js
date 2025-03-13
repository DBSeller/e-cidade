(function(exports) {
    var FiltroItensIncorporacao = function FiltroItensIncorporacao(callbackRetorno) {

        this.collection = new Collection();
        this.collection.setId("codigo");
        this.gridPendentes = DatagridCollection.create(this.collection).configure({"order": false, "height": '200'});
        this.callbackRetorno = callbackRetorno;

        /**
         * Monta a grid
         * @param gridPendentes
         */
        var montarGrid = function (gridPendentes) {

            gridPendentes.getGrid().setCheckbox(null);
            gridPendentes.addColumn("descricao", {
                label: "Material/Serviço",
                align: "left",
                width: '60%'
            }).transform(function (descricao_bem, itemCollection) {
                return '<label title="' + descricao_bem + '">' + descricao_bem + '</label>';
            });

            gridPendentes.addColumn("quantidade", {
                label: "Quantidade",
                align: "center",
                width: '20%'
            }).transform(function (quantidade, itemCollection) {
                return '<input type="text" id="quantidade_id_' + itemCollection.ID + '" value="' + quantidade + '">';
            });

            gridPendentes.addColumn("valorUnitario", {
                label: "Valor Unitário",
                align: "right",
                width: '20%'
            }).transform('number');

            // adiciona função de validaçãs no input de quantidade da grid
            gridPendentes.setEvent('onafterrenderrows', function (collection) {

                collection.get().forEach(function (itemCollection) {

                    // coloquei essa validação pq o evento onafterrenderrows esta sendo aplicado em todas grids
                    // instanciadas e dando conflito na grid no programa pat4_incorporacaobem001.php
                    if (!$('quantidade_id_' + itemCollection.ID)) {
                        return;
                    }

                    $('quantidade_id_' + itemCollection.ID).addEventListener('change', function () {

                        if (Number(this.value) > itemCollection.quantidade) {
                            this.value = itemCollection.quantidade;
                            alert('Você não pode informar uma quantidade maior que ' + itemCollection.quantidade + '.');

                        }
                    });
                });
            });

            setTimeout(function () {
                gridPendentes.show($('gridItensPendentesIncorporacao'));
            }, 200);
        };


        var implementarAcoesBotoes = function (classe) {

            $('pesquisarItens').addEventListener('click', function () {
                if ($F('empenho') == '') {
                    alert('Informe um empenho.');
                    return;
                }

                if (!$F('e60_numemp')) {
                    return false;
                }

                var paramentros = {exec: 'buscarItensPorEmpenho', 'codigoEmpenho': $F('e60_numemp')};
                new AjaxRequest('pat4_incorporacaobem.RPC.php', paramentros, function (retorno, erro) {

                    if (erro) {
                        alert(retorno.message);
                        return;
                    }

                    classe.gridPendentes.clear();
                    retorno.itens.forEach(function(item){

                        if (item.quantidade != 0) {
                            classe.collection.add(item);
                        }
                    });

                    if (classe.collection.get().size() === 0) {
                        alert("O empenho informado não possui itens que possam ser incorporados a um bem, pois todos existentes já foram previamente incorporados.");
                    }

                    classe.gridPendentes.reload();
                }).setMessage('Buscando os materiais/serviços pendentes de incorporação, aguarde...').execute();
            });

            $('confirmarItens').addEventListener('click', function () {

                var selecionados = [];
                var linhasGrid = classe.gridPendentes.getGrid().aRows;
                for (var linha of linhasGrid) {
                    if (linha.isSelected) {

                        var item = linha.itemCollection.build();
                        item.quantidade = $F('quantidade_id_' + linha.itemCollection.ID);
                        selecionados.push(item);
                    }
                }

                if (selecionados.length == 0) {
                    alert("Você deve selecionar ao menos um item.");
                    return;
                }

                classe.callbackRetorno(selecionados);

                classe.janela.destroy();
            });
        };

        /**
         * cria a instancia da janela
         * @param classe
         */
        var criaJanela = function (classe) {

          classe.janela = new windowAux('wndIncorporar',
              'Pesquisa de itens para incorporar ao bem', document.body.getWidth() - 10, 650);
            classe.janela.setShutDownFunction(function () {
                classe.janela.destroy();
            });

            var sConteudo = '<div class="container">';
            sConteudo += '  <fieldset><legend>Filtro</legend>';
            sConteudo += '    <table>';
            sConteudo += '      <tr>';
            sConteudo += '        <td><a href="#" id="ancoraEmpenho">Empenho:</a></td>';
            sConteudo += '        <td><input type="text" id="empenho" lang="dl_Empenho" ></td>';
            sConteudo += '      </tr>';
            sConteudo += '    </table>';
            sConteudo += '  </fieldset>';
            sConteudo += '  <input type="hidden" id="e60_numemp" disabled />';
            sConteudo += '  <input type="button" value="Pesquisar" id="pesquisarItens" >';
            sConteudo += '  <br>';
            sConteudo += '</div>';
            sConteudo += '<fieldset class="subcontainer" style="width: 70%;"><legend>Itens</legend>';
            sConteudo += '  <div id="gridItensPendentesIncorporacao" style="display: block"></div></div>';
            sConteudo += '</fieldset>';
            sConteudo += '<div class="subcontainer" ><br><input type="button" value="Confirmar" id="confirmarItens"></div>';
            classe.janela.setContent(sConteudo);

            var sMessagemImpressao = 'Filtre por empenho para visualizar os itens que estão em estoque e selecione os que deseja incorporar ao bem informado na tela anterior.';
            new DBMessageBoard('msgBoardAlunos',
                'Escolha os itens para incorporar ao bem',
                sMessagemImpressao,
                classe.janela.getContentContainer()
            );

            classe.janela.show();
        };

        criaJanela(this);

        // Adiciona a funcionalidade da ancora
        new DBLookUp($('ancoraEmpenho'), $('empenho'), $('e60_numemp'), {
            'sArquivo': 'func_empenhoscombensincorporar.php',
            'sLabel': 'Pesquisa de Empenho',
            'aCamposAdicionais': ['e60_numemp', 'dl_Empenho'],
            zIndex: 1000
        }).setCallBack('onChange', function(erro, campos) {
            $('empenho').value = campos[2];
            $('e60_numemp').value = campos[0];

            if (erro) {
                $('empenho').value    = '';
                $('e60_numemp').value = '';
            }
        });

        montarGrid(this.gridPendentes);
        implementarAcoesBotoes(this);
    };

    exports.FiltroItensIncorporacao = FiltroItensIncorporacao;
    return FiltroItensIncorporacao;

})(this);

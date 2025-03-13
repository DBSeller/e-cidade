<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009 DBSeller Servicos de Informatica
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

use ECidade\Configuracao\RelatorioLegal\Enum\OrigemDadosEnum;

$anoSessao = db_getsession('DB_anousu');

?>
<div class="container">
<form>
    <fieldset>
        <legend>Cadastro de Colunas</legend>
        <table class="form-container">
            <tbody>
            <tr>
                <td>
                    <label for="o115_sequencial">
                        <strong>Código:</strong>
                    </label>
                </td>
                <td>
                    <input name="o115_sequencial" id="o115_sequencial" readonly class="form-control field-size2" >
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o115_anousu">
                        <strong>Ano:</strong>
                    </label>
                </td>
                <td>
                    <input class="form-control field-size2" name="o115_anousu" id="o115_anousu" value="<?php echo $anoSessao; ?>"
                           readonly>
                </td>
            </tr>
            <tr>
                <td>
                    <span id="labelRelatorio" style="display: none">Relatório:</span>
                    <a id="ancoraRelatorio" href="#">Relatório:</a>
                </td>
                <td>
                    <input id="codigoRelatorio" name="codigoRelatorio" class="field-size2 form-control"
                           lang="o42_codparrel"/>
                    <input id="descricaoRelatorio" name="descricaoRelatorio" class="field-size7 form-control"
                           lang="o42_descrrel" readonly/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o115_descricao">
                        <strong>Descrição:</strong>
                    </label>
                </td>
                <td>
                    <input name="o115_descricao" id="o115_descricao" class="form-control field-size-max">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o115_nomecoluna">
                        <strong>Nome da Coluna:</strong>
                    </label>
                </td>
                <td>
                    <input name="o115_nomecoluna" id="o115_nomecoluna" class="form-control field-size-max">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o115_tipo">
                        <strong>Tipo:</strong>
                    </label>
                </td>
                <td>
                    <select name="o115_tipo" id="o115_tipo" class="form-control">
                        <option value="1">Valores</option>
                        <option value="2">Alfanumericos</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o115_valoresdefault">
                        <strong>Valor Default:</strong>
                    </label>
                </td>
                <td>
                    <textarea name="o115_valoresdefault" id="o115_valoresdefault" rows="5"
                              class="form-control" style="width: 100%"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="origem">
                        <strong>Origem de Dados:</strong>
                    </label>
                </td>
                <td>
                    <select name="origem" id="origem" class="form-control">
                        <?php

                        foreach (OrigemDadosEnum::todas() as $key => $origem) {
                            ?>
                            <option value="<?php echo $key; ?>"><?php echo $origem; ?></option>
                            <?php
                        }

                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o115_formula">
                        <strong>Fórmula de Cálculo:</strong>
                    </label>
                </td>
                <td>
                    <textarea name="o115_formula" id="o115_formula" rows="2" class="form-control"
                              style="width: 100%"></textarea>
                    <br>
                    <strong>
                        Pressione <kbd>Ctrl</kbd> + <kbd>Espaço</kbd> para listar as variáveis disponíveis.</strong>
                </td>
            </tr>
            </tbody>
        </table>
        <fieldset class="d-none" id="fieldsetContas">
            <legend>Contas</legend>
            <table style="width: 100%" class="text-left">
                <tbody>
                <tr>
                    <td>
                        <label for="estrutural">
                            <strong>Estrutural:</strong>
                        </label>
                    </td>
                    <td>
                        <input id="estrutural" maxlength="15" name="estrutural" class="form-control" style="width: 100%">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="exclusao">
                            <strong>Exclusão:</strong>
                        </label>
                    </td>
                    <td>
                        <input id="exclusao" name="exclusao" type="checkbox">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <input id="lancar" type="button" value="Lançar">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="contasGrid">
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>
    </fieldset>
    <div class="text-center">
        <input type="button" id="botao">
        <input type="reset" value="Novo" class="d-none" id="novo">
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" class="d-none">
    </div>
</form>
</div>
<script src="scripts/widgets/Input/DBInput.widget.js"></script>
<script src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
<script src="scripts/classes/http/http.js"></script>
<script src="scripts/widgets/DBLookUp.widget.js"></script>
<script>
    var MSC = <?php echo OrigemDadosEnum::MSC; ?>;
    var SEM_ORIGEM = <?php echo OrigemDadosEnum::SEM_ORIGEM; ?>;
    var opcao = <?php echo $modo; ?>;
    var incluir = false;
    var alterar = false;
    var excluir = false;
    var colunaNova = true;

    const inputCodigo = document.getElementById('o115_sequencial');
    const inputAno = document.getElementById('o115_anousu');
    const inputDescricao = document.getElementById('o115_descricao');
    const inputEstrutural = document.getElementById('estrutural');
    const inputExclusao = document.getElementById('exclusao');
    const selectTipo = document.getElementById('o115_tipo');
    const textareaValoresDefault = document.getElementById('o115_valoresdefault');
    const inputNomeColuna = document.getElementById('o115_nomecoluna');
    const textareaFormula = document.getElementById('o115_formula');
    const selectOrigem = document.getElementById('origem');
    const estruturaisCollection = new Collection().setId('estrutural');
    const inputBotao = document.getElementById('botao');
    const inputPesquisar = document.getElementById('pesquisar');
    const inputNovo = document.getElementById('novo');
    const inputLancar = document.getElementById('lancar');
    const fieldsetContas = document.getElementById('fieldsetContas');
    const codigoRelatorio = document.getElementById('codigoRelatorio');

    new DBInputInteger(inputEstrutural);

    const lookUpRelatorio = new DBLookUp($('ancoraRelatorio'), $('codigoRelatorio'), $('descricaoRelatorio'), {
        'sArquivo': 'func_orcparamrel.php',
        'sObjetoLookUp': 'db_iframe_orcparamrel',
        'sLabel': 'Pesquisar relatório'
    });

    const limparContas = () => {
        estruturaisCollection.clear();
        inputEstrutural.value = '';
        inputExclusao.checked = false;
        gridEstruturais.reload();
    };

    function carregarCampos() {
        inputCodigo.value = arguments[0];
        inputAno.value = arguments[1];
        inputDescricao.value = arguments[2];
        selectTipo.value = arguments[3];
        textareaValoresDefault.value = arguments[4];
        inputNomeColuna.value = arguments[5];
        textareaFormula.value = arguments[6];
        selectOrigem.value = arguments[7] ? arguments[7] : 0;
        codigoRelatorio.value = arguments[8] ? arguments[8] : null;
        $('descricaoRelatorio').value = arguments[9] ? arguments[9] : null;

        colunaNova = true;
        $('codigoRelatorio').removeAttribute('readonly');
        $('codigoRelatorio').classList.remove('readonly');
        $('labelRelatorio').style.display = 'none';
        $('ancoraRelatorio').style.display = '';

        if (codigoRelatorio.value == '' || excluir) {
            colunaNova = false;
            $('codigoRelatorio').setAttribute('readonly', 'readonly');
            $('codigoRelatorio').addClassName('readonly');
            $('labelRelatorio').style.display = '';
            $('ancoraRelatorio').style.display = 'none';
        }
        limparContas();

        db_iframe_orcparamseqcoluna.hide();

        organizaOrigens();
    }

    const pesquisar = () => {
        const campos = [
            'o115_sequencial',
            'o115_anousu',
            'o115_descricao',
            'o115_tipo',
            'o115_valoresdefault',
            'o115_nomecoluna',
            'o115_formula',
            'o115_origem',
            'o115_relatorio',
            'o42_descrrel'
        ].join('|');

        js_OpenJanelaIframe(
            'CurrentWindow.corpo',
            'db_iframe_orcparamseqcoluna',
            `func_orcparamseqcoluna.php?funcao_js=parent.${carregarCampos.name}|${campos}`,
            'Pesquisa',
            true
        );
    };

    const limpar = () => {
        fieldsetContas.classList.add('d-none');
        limparContas();
    };

    inputNovo.addEventListener('click', limpar);
    inputPesquisar.addEventListener('click', pesquisar);

    switch (opcao) {
        case 1:
            inputNovo.classList.remove('d-none');
            inputBotao.value = 'Incluir';
            incluir = true;
            break;
        case 2:
            inputBotao.value = 'Alterar';
            alterar = true;
            break;
        case 3:
            inputBotao.value = 'Excluir';
            excluir = true;
            break;
    }

    if (alterar || excluir) {
        inputPesquisar.classList.remove('d-none');
        inputPesquisar.dispatchEvent(new Event('click'));
    }

    if (excluir) {
        inputCodigo.readOnly = true;
        inputAno.readOnly = true;
        inputDescricao.readOnly = true;
        selectTipo.disabled = true;
        textareaValoresDefault.readOnly = true;
        inputNomeColuna.readOnly = true;
        textareaFormula.readOnly = true;
        selectOrigem.disabled = true;
        inputEstrutural.readOnly = true;
        inputExclusao.disabled = true;
        inputLancar.disabled = true;
    }

    const gridEstruturais = DatagridCollection.create(estruturaisCollection).configure({
        order: false,
        height: 100
    });
    gridEstruturais.addColumn('estrutural', {
        label: 'Estrutural', width: '70%'
    });
    gridEstruturais.addColumn('exclusao', {
        label: 'Exclusão', width: '20%'
    });
    gridEstruturais.addAction('E', null, (evento, item) => {
        estruturaisCollection.remove(item.estrutural);
        gridEstruturais.reload();
    });
    gridEstruturais.show($('contasGrid'));

    inputLancar.addEventListener('click', () => {
        if (inputEstrutural.value === '') {
            return alert('O campo "Estrutural" é de preenchimento obrigatório.');
        }

        if (estruturaisCollection.__assertId(inputEstrutural.value)) {
            return alert(`Conta com o estrutural ${inputEstrutural.value} já foi lançada.`);
        }

        estruturaisCollection.add({
            estrutural: inputEstrutural.value,
            exclusao: inputExclusao.checked ? 'Sim' : 'Não'
        });

        gridEstruturais.reload();

        inputEstrutural.value = '';
        inputExclusao.checked = false;
    });

    dbContextComplete = new DBContextComplete('DBContextComplete');
    dbContextComplete.setElementForContext(textareaFormula);
    dbContextComplete.setPrependString('#');
    dbContextComplete.init();
    dbContextComplete.addGroup('colunas', '@');

    const organizaOrigens = () => {
        if (selectOrigem.value == MSC) {
            fieldsetContas.classList.remove('d-none');
            gridEstruturais.reload();
            if (inputCodigo.value !== '') {
                carregarContas();
            }
        } else {
            fieldsetContas.classList.add('d-none');
        }

        if (selectOrigem.value != SEM_ORIGEM) {
            const parametros = {
                exec: 'getVariaveis',
                iOrigemDados: selectOrigem.value,
                iCodigoRelatorio: 0,
                iCodigoLinha: 0
            };

            const formData = new FormData();
            formData.append('json', JSON.stringify(parametros));

            HttpClient.post('con4_relatorioslegais.RPC.php', {
                body: formData
            }).then(response => {
                if (dbContextComplete) {
                    dbContextComplete.close();
                }

                dbContextComplete.aListaOpcoes = [];

                response.oListaVariaveis.campos_relatorios.each(sVariavel => {
                    dbContextComplete.addOption(sVariavel, sVariavel);
                });

                response.oListaVariaveis.colunas_linha.each(sVariavel => {
                    dbContextComplete.addOption(sVariavel, sVariavel, 'colunas');
                });
            });
        }
    };

    selectOrigem.addEventListener('change', () => {
        organizaOrigens();
        textareaFormula.value = '';
    });

    organizaOrigens();

    inputBotao.addEventListener('click', e => {
        e.preventDefault();

        var acao = null;
        const contasGrid = gridEstruturais.getCollection().get();
        const formData = new FormData();
        formData.append('sequencial', inputCodigo.value);

        contasGrid.each(conta => {
            formData.append('contas[]', JSON.stringify({
                estrutural: conta.estrutural,
                exclusao: conta.exclusao
            }));
        });

        if (incluir || alterar) {
            acao = 'salvar';

            if ((incluir || (alterar && colunaNova))
                 && codigoRelatorio.value == '') {
                //return alert('Campo "Relatório" é obrigatório.');
            }

            if (inputDescricao.value === '') {
                return alert('Campo "Descrição" é obrigatório.');
            }

            if (inputNomeColuna.value === '') {
                return alert('Campo "Nome da Coluna" é obrigatório.');
            }

            formData.append('ano', inputAno.value);
            formData.append('descricao', inputDescricao.value);
            formData.append('nome', inputNomeColuna.value);
            formData.append('tipo', selectTipo.value);
            formData.append('default', textareaValoresDefault.value);
            formData.append('formula', textareaFormula.value);
            formData.append('origem', selectOrigem.value);
            formData.append('relatorio', codigoRelatorio.value);
        } else {
            acao = 'excluir';
        }

        formData.append('acao', acao);

        HttpClient.post('con1_relatorio_legal_coluna.RPC.php', {
            body: formData
        }).then(response => {
            alert(response.mensagem);

            if (!response.erro) {

                if (incluir) {
                    inputNovo.click();
                    return;
                }

                if (alterar) {
                    inputCodigo.value = response.coluna.sequencial;
                }

                if (excluir) {
                    inputPesquisar.dispatchEvent(new Event('click'));
                    inputNovo.click();
                }
            }
        }).catch(e => alert(e.message));
    });

    function carregarContas() {
        const parametros = {
            exec: 'buscarContasVinculasColuna',
            coluna: inputCodigo.value,
            ano: inputAno.value
        };

        const formData = new FormData();
        formData.append('json', JSON.stringify(parametros));

        HttpClient.post('con4_relatorioslegais.RPC.php', {
            body: formData
        }).then(response => {
            if (response.erro) {
                return alert(response.message);
            }

            response.contas.each(conta => {
                estruturaisCollection.add({
                    estrutural: conta.estrutural,
                    exclusao: conta.exclusao ? 'Sim' : 'Não'
                });
            });
            gridEstruturais.reload();

            if (excluir) {
                document.querySelectorAll('.collection_button').forEach(button => button.disabled = true);
            }
        });
    }
</script>

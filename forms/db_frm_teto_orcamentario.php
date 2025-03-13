<div>
    <form id="formularioDados" name="formularioDados">
        <div class="form-container" style="max-width: 40%; margin: auto">
            <fieldset style="margin-top: 2.5%;">
                <legend>Programação Qualitativa</legend>
                <table>
                    <tbody>
                    <tr>
                        <td>
                            <label for="unidade">
                                <strong>
                                    <a id="unidadeAncora" class="DBAncora" href="javascript:void(0)">Unidade Orçamentária:</a>
                                </strong>
                            </label>
                        </td>
                        <td>
                            <input id="unidade" name="unidade" class="field-size2 readonly"
                                   readonly>
                        </td>
                        <td>
                            <input id="unidadeDescricao" name="unidadeDescricao"
                                   title="Unidade Orçamentária Descrição" class="field-size11 readonly" disabled>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </fieldset>
            <fieldset style="margin-top: 2.5%;">
                <legend>Programação Quantitativa</legend>
                <table>
                    <tbody>
                    <tr>
                        <td>
                            <label for="grupoNaturezaDespesa">
                                <strong>Grupo de Natureza da Despesa:</strong>
                            </label>
                        </td>
                        <td>
                            <input type="text" id="grupoNaturezaDespesa" name="grupoNaturezaDespesa" class="field-size1">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="identificadorUso">
                                <strong>Identificador de Uso:</strong>
                            </label>
                        </td>
                        <td colspan="2">
                            <select name="identificadorUso" id="identificadorUso" class="field-size11">
                                <option selected value="">Selecionar</option>
                                <option value="0">0 - Recursos não destinados à contrapartida ou à identificação de despesas
                                    destinadas ao mínimo da Saúde ou ao mínimo da Educação
                                </option>
                                <option value="1">1 - Contrapartida de empréstimos do BIRD</option>
                                <option value="2">2 - Contrapartida de empréstimos do BID</option>
                                <option value="3">3 - Contrapartida de empréstimos do CAF</option>
                                <option value="4">4 - Contrapartida de outros empréstimos</option>
                                <option value="5">5 - Contrapartida de doações</option>
                                <option value="6">6 - Recursos não destinados à contrapartida, para identificação das
                                    despesas
                                    destinadas ao mínimo da Saúde
                                </option>
                                <option value="7">7 - Recursos de Contrapartida de Convênio</option>
                                <option value="8">8 - Recursos não destinados à contrapartida, para identificação das
                                    despesas
                                    destinadas ao mínimo da Educação
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="tipoDetalhamento">
                                <strong>Tipo de Detalhamento:</strong>
                            </label>
                        </td>
                        <td colspan="2">
                            <select name="tipoDetalhamento" id="tipoDetalhamento" class="field-size11">
                                <option selected value="">Selecionar</option>
                                <option value="0">0 - Sem Detalhamento</option>
                                <option value="1">1 - Cadastro</option>
                                <option value="2">2 - Operação de Crédito</option>
                                <option value="3">3 - Convênio</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="grupoFonteRecursos">
                                <strong>Grupo da Fonte de Recursos:</strong>
                            </label>
                        </td>
                        <td colspan="2">
                            <select name="grupoFonteRecursos" id="grupoFonteRecursos" class="field-size11">
                                <option selected value="">Selecionar</option>
                                <option value="1">1 - Recursos do Tesouro - Exercício Corrente</option>
                                <option value="2">2 - Recursos de Outras Fontes - Exercício Corrente</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="especificacaoFonte">
                                <strong>Especificação da Fonte de Recursos:</strong>
                            </label>
                        </td>
                        <td colspan="2">
                            <select name="especificacaoFonte" id="especificacaoFonte" class="field-size11">
                                <option selected value="">Selecionar</option>
                                <option value="00">00 - Ordinários Não Provenientes de Impostos</option>
                                <option value="01">01 - Operações de Crédito</option>
                                <option value="02">02 - Recursos de Convênios</option>
                                <option value="03">03 - Recursos Próprios Não Financeiros</option>
                                <option value="05">05 - Contribuição do Salário-Educação</option>
                                <option value="06">06 - Recursos Destinados à Alimentação Escolar</option>
                                <option value="07">07 - Recursos do Sistema Único de Saúde</option>
                                <option value="08">08 - Recursos do Fundo Nacional de Assistência Social</option>
                                <option value="10">10 - Recursos Vinculados ao Fundo de Mobilidade</option>
                                <option value="12">12 - Outorga Onerosa do Direito de Construir</option>
                                <option value="13">13 - Ordinários Provenientes de Impostos</option>
                                <option value="14">14 - Transferências Constitucionais Provenientes de Impostos</option>
                                <option value="15">15 - Recursos do Fundeb</option>
                                <option value="17">17 - Outras Transferências da União</option>
                                <option value="18">18 - Recursos Vinculados à Previdência Municipal</option>
                                <option value="36">36 - Recursos de Multas de Trânsito</option>
                                <option value="37">37 - Contribuição sobre a Iluminação Pública</option>
                                <option value="38">38 - Compensação Financeira pela Exploração e Produção de Petróleo
                                </option>
                                <option value="53">53 - Taxas e Multas pelo Exercício do Poder de Polícia</option>
                                <option value="80">80 - Remuneração das Disponibilidades do Tesouro</option>
                                <option value="82">82 - Recursos Próprios Financeiros</option>
                                <option value="83">83 - Recursos de Alienação de Bens e Direitos do Patrimônio Público
                                </option>
                                <option value="90">90 - Recursos do Tesouro - a Definir</option>
                                <option value="99">99 - Recursos Extraorçamentários</option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </fieldset>
            <fieldset style="margin-top: 2.5%;">
                <legend>Valores</legend>
                <table>
                    <tbody>
                    <tr>
                        <td>
                            <label for="valorTeto">
                                <strong>Valor do Teto:</strong>
                            </label>
                        </td>
                        <td colspan="2">
                            <input name="valorTeto" id="valorTeto" class="field-size3">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </fieldset>
            <div style="text-align: center; margin-top: 25px;">
                <input type="submit" value="Salvar" id="salvar">
                <input type="button" id="pesquisar" value="Pesquisar" style="display: none">
                <input type="button" id="limpar" value="Novo">
            </div>
        </div>
    </form>
</div>
<?php db_menu(); ?>
<script>
    const url = 'con4_teto_orcamentario.RPC.php';
    const ano = 2019;
    const unidadeAncora = document.querySelector('#unidadeAncora');
    const unidade = document.querySelector('#unidade');
    const unidadeDescricao = document.querySelector('#unidadeDescricao');
    const grupoNaturezaDespesa = document.querySelector('#grupoNaturezaDespesa');
    const valorTeto = new DBInputValor(document.querySelector('#valorTeto'));
    const identificadorUso = document.querySelector('#identificadorUso');
    const tipoDetalhamento = document.querySelector('#tipoDetalhamento');
    const grupoFonteRecursos = document.querySelector('#grupoFonteRecursos');
    const especificacaoFonte = document.querySelector('#especificacaoFonte');
    const salvarBotao = document.querySelector('#salvar');
    const limparBotao = document.querySelector('#limpar');
    const pesquisarBotao = document.querySelector('#pesquisar');

    var sequencial = null;

    criarListeners();

    function validarGrupoNaturezaDespesa() {
        const value = grupoNaturezaDespesa.value;

        if (isNaN(value)) {
            grupoNaturezaDespesa.value = '';

            return false;
        }

        if (value < 0 || value > 9) {
            grupoNaturezaDespesa.value = value.substr(0, 1);

            return false;
        }

        grupoNaturezaDespesa.value = value.substr(0, 1);

        return true;
    }

    function criarListeners() {
        unidadeAncora.addEventListener('click', buscarUnidadeOrcamentaria);
        salvarBotao.addEventListener('click', salvar);
        limparBotao.addEventListener('click', limpar);
        pesquisarBotao.addEventListener('click', pesquisar);
        grupoNaturezaDespesa.addEventListener('input', validarGrupoNaturezaDespesa);
    }

    function salvar(event) {
        event.preventDefault();

        if (!validar()) {
            return false;
        }

        const parametros = new FormData(document.getElementById('formularioDados'));
        parametros.append('exec', 'salvar');
        parametros.append('ano', ano);
        parametros.append('valorTeto', valorTeto.getValue());

        if (sequencial) {
            parametros.append('codigo', sequencial);
        }

        js_divCarregando('Salvando Teto Orçamentário', 'loading_message');

        return fetch(url, {
            method: 'POST',
            body: parametros,
            credentials: 'include',
        }).
            then(response => response.json()).
            then(response => {
                alert(response.mensagem);

                if (!response.erro) {
                    sequencial = response.sequencial;
                }
            }).
            catch(() => alert('Não foi possível salvar o teto orçamentário.')).
            finally(() => js_removeObj('loading_message'));
    }

    function validar() {
        if (!unidade.value) {
            alert('É necessário preencher o campo "Unidade Orçamentária".');
            return false;
        }
        if (!grupoNaturezaDespesa.value) {
            alert('É necessário preencher o campo "Grupo de Natureza da Despesa".');
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
        if (!grupoFonteRecursos.value) {
            alert('É necessário preencher o campo "Grupo de Fonte de Recurso".');
            return false;
        }
        if (!especificacaoFonte.value) {
            alert('É necessário preencher o campo "Especificação da Fonte de Recurso".');
            return false;
        }
        if (!valorTeto.getValue()) {
            alert('É necessário preencher o campo "Valor do Teto"');
            return false;
        }

        return true;
    }

    function buscarUnidadeOrcamentaria() {
        const onde = '';
        const nome = 'db_iframe_unidade_orcamentaria';
        const arquivo = 'func_db_config_orcunidade.php';
        const titulo = 'Pesquisar Unidade Orçamentária';
        const mostra = true;
        const campos = '|o41_orgao|o41_unidade|o40_descr|o41_descr';
        const funcao = '?previsao=true&ano=' + ano + '&funcao_js=parent.' + preencherUnidadeOrcamentaria.name;

        js_OpenJanelaIframe(onde, nome, arquivo + funcao + campos, titulo, mostra);
    }

    function preencherUnidadeOrcamentaria(chave1, chave2, chave3, chave4) {
        const orgao = chave1.padStart(2, '0');
        const codigoUnidade = chave2.padStart(2, '0');
        const codigoTribunal = orgao + codigoUnidade;
        const orgaoUnidade = chave3 + ' / ' + chave4;

        unidade.value = codigoTribunal;
        unidadeDescricao.value = orgaoUnidade;
        db_iframe_unidade_orcamentaria.hide();
    }

    function limpar() {
        location.href = 'con1_teto_orcamentario_001.php';
    }

    function pesquisar() {
        const onde = '';
        const nome = 'db_iframe_teto_orcamentario';
        const arquivo = 'func_teto_orcamentario.php';
        const titulo = 'Pesquisar Teto Orçamentário';
        const campos = '|c40_sequencial';
        const funcao = '?funcao_js=parent.' + preencherFormulario.name;

        js_OpenJanelaIframe(onde, nome, arquivo + funcao + campos, titulo, true);
    }

    function preencherFormulario(codigo) {
        db_iframe_teto_orcamentario.hide();
        js_divCarregando('Carregando Teto Orçamentário', 'loading_message');

        const parametros = new FormData();
        parametros.append('exec', 'buscar');
        parametros.append('codigo', codigo);

        return fetch(url, {
            method: 'POST',
            body: parametros,
            credentials: 'include',
        }).
            then(response => response.json()).
            then(response => {
                if (response.erro) {
                    return alert(response.mensagem);
                }

                const tetoOrcamentario = response.tetoOrcamentario;

                sequencial = tetoOrcamentario.c40_sequencial;
                unidade.value = tetoOrcamentario.c40_orgao.padStart(2, '0') +
                    tetoOrcamentario.c40_unidade.padStart(2, '0');
                unidadeDescricao.value = tetoOrcamentario.unidade_orcamentaria;
                grupoNaturezaDespesa.value = tetoOrcamentario.c40_grupo_natureza_despesa;
                identificadorUso.value = tetoOrcamentario.c40_identificador_uso;
                tipoDetalhamento.value = tetoOrcamentario.c40_tipo_detalhamento;
                grupoFonteRecursos.value = tetoOrcamentario.c40_grupo_fonte_recursos;
                especificacaoFonte.value = tetoOrcamentario.c40_especificacao_fonte;
                valorTeto.value = tetoOrcamentario.c40_valor_teto;
            }).
            catch(() => alert('Não foi possível buscar o teto orçamentário.')).
            finally(() => js_removeObj('loading_message'));
    }
</script>

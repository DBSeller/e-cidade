<fieldset style="margin-top: 2.5%;">
    <legend>Programação Qualitativa</legend>
    <table>
        <tbody>
        <tr>
            <td>
                <label for="codigoDotacao">
                    <strong>Código da Dotação:</strong>
                </label>
            </td>
            <td colspan="2">
                <input id="codigoDotacao" name="codigoDotacao" class="field-size2 readonly" readonly>
            </td>
        </tr>
        <tr>
            <td>
                <label for="esferaOrcamentaria">
                    <strong>Esfera Orçamentária:</strong>
                </label>
            </td>
            <td colspan="2">
                <select name="esferaOrcamentaria" id="esferaOrcamentaria" class="field-size6">
                    <option selected value="">Selecionar</option>
                    <option value="10">10 - F - Orçamento Fiscal</option>
                    <option value="20">20 - S - Orçamento da Seguridade Social</option>
                    <option value="30">30 - I - Orçamento de Investimento</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="unidadeOrcamentaria">
                    <strong>
                        <a id="unidadeOrcamentariaAncora" class="DBAncora bold" href="javascript:void(0)">Unidade Orçamentária:</a>
                    </strong>
                </label>
            </td>
            <td>
                <input id="unidadeOrcamentaria" name="unidadeOrcamentaria" class="field-size2 readonly" readonly>
            </td>
            <td>
                <input id="unidadeOrcamentariaDescricao" name="unidadeOrcamentariaDescricao"
                       title="Unidade Orçamentária Descrição" class="field-size11 readonly" disabled>
            </td>
        </tr>
        <tr>
            <td>
                <label for="funcao">
                    <strong>
                        <a id="funcaoAncora">Função:</a>
                    </strong>
                </label>
            </td>
            <td>
                <input id="funcao" name="funcao" class="field-size2" lang="o52_funcao">
            </td>
            <td>
                <input id="funcaoDescricao" name="funcaoDescricao" title="Função Descrição" lang="o52_descr"
                       class="field-size11">
            </td>
        </tr>
        <tr>
            <td>
                <label for="subfuncao">
                    <strong>
                        <a id="subfuncaoAncora">Subfunção:</a>
                    </strong>
                </label>
            </td>
            <td>
                <input id="subfuncao" name="subfuncao" class="field-size2" lang="o53_subfuncao">
            </td>
            <td>
                <input id="subfuncaoDescricao" name="subfuncaoDescricao" title="Subfunção Descrição"
                       class="field-size11" lang="o53_descr">
            </td>
        </tr>
        <tr>
            <td>
                <label for="programa">
                    <strong>
                        <a id="programaAncora">Programa:</a>
                    </strong>
                </label>
            </td>
            <td>
                <input id="programa" name="programa" class="field-size2" lang="o54_programa">
            </td>
            <td>
                <input id="programaDescricao" name="programaDescricao" title="Programa Descrição" class="field-size11"
                       lang="o54_descr">
            </td>
        </tr>
        <tr>
            <td>
                <label for="acao">
                    <strong>
                        <a id="acaoAncora">Ação:</a>
                    </strong>
                </label>
            </td>
            <td>
                <input type="text" id="acao" name="acao" class="field-size2" lang="o55_projativ">
            </td>
            <td>
                <input id="acaoDescricao" name="acaoDescricao" title="Ação Descrição" class="field-size11"
                       lang="o55_descr">
            </td>
        </tr>
        <tr>
            <td>
                <label for="subtitulo">
                    <strong>
                        <a id="subtituloAncora">Subtítulo:</a>
                    </strong>
                </label>
            </td>
            <td>
                <input id="subtitulo" name="subtitulo" class="field-size2" lang="o11_sequencial">
            </td>
            <td>
                <input id="subtituloDescricao" name="subtituloDescricao" title="Subtítulo Descrição"
                       class="field-size11" lang="o11_descricao">
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
                <label for="estrutural">
                    <strong>
                        <a id="naturezaDespesaAncora">Natureza da Despesa:</a>
                    </strong>
                </label>
            </td>
            <td>
                <input type="text" id="estrutural" name="estrutural" lang="c60_estrut" class="field-size3 readonly"
                       readonly>
                <input type="hidden" id="naturezaDespesa" name="naturezaDespesa" lang="c60_codcon">
            </td>
            <td>
                <input type="text" id="naturezaDespesaDescricao" name="naturezaDespesaDescricao"
                       title="Natureza de Despesa Descrição" lang="c60_descr" class="field-size8">
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
                    <option value="6">6 - Recursos não destinados à contrapartida, para identificação das despesas
                        destinadas ao mínimo da Saúde
                    </option>
                    <option value="7">7 - Recursos de Contrapartida de Convênio</option>
                    <option value="8">8 - Recursos não destinados à contrapartida, para identificação das despesas
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
                <label for="grupoFonteRecurso">
                    <strong>Grupo da Fonte de Recursos:</strong>
                </label>
            </td>
            <td colspan="2">
                <select name="grupoFonteRecurso" id="grupoFonteRecurso" class="field-size11">
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
                    <option value="38">38 - Compensação Financeira pela Exploração e Produção de Petróleo</option>
                    <option value="53">53 - Taxas e Multas pelo Exercício do Poder de Polícia</option>
                    <option value="80">80 - Remuneração das Disponibilidades do Tesouro</option>
                    <option value="82">82 - Recursos Próprios Financeiros</option>
                    <option value="83">83 - Recursos de Alienação de Bens e Direitos do Patrimônio Público</option>
                    <option value="90">90 - Recursos do Tesouro - a Definir</option>
                    <option value="99">99 - Recursos Extraorçamentários</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="identificadorResultadoPrimario">
                    <strong>Identificador de Resultado Primário:</strong>
                </label>
            </td>
            <td colspan="2">
                <select name="identificadorResultadoPrimario" id="identificadorResultadoPrimario" class="field-size11">
                    <option selected value="">Selecionar</option>
                    <option value="0">0 - Financeira</option>
                    <option value="1">1 - Primária Obrigatória</option>
                    <option value="2">2 - Primária Discricionária</option>
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
                <label for="previsao2019">
                    <strong>Previsão 2019:</strong>
                </label>
            </td>
            <td colspan="2">
                <input name="previsao2019hidden" id="previsao2019hidden" type="hidden" />
                <input name="previsao2019" id="previsao2019" class="field-size3" />
            </td>
        </tr>
        </tbody>
    </table>
</fieldset>

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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
</head>
<body class='body-default'>
<div class='container'>
    <form id="filtrosRelatorio">
        <fieldset>
            <legend>Filtros</legend>
            <fieldset class="separator">
                <legend>Programação Qualitativa</legend>
                <table class="form-container">
                    <tbody>
                    <tr>
                        <td>
                            <label for="codigoDotacao">
                                <a id="codigoDotacaoAncora">Código Dotação:</a>
                            </label>
                        </td>
                        <td>
                            <input id="codigoDotacao" name="codigoDotacao" class="field-size2"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="esferaOrcamentaria">
                                <strong>Esfera Orçamentária:</strong>
                            </label>
                        </td>
                        <td>
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
                                    <a id="unidadeOrcamentariaAncora" class="DBAncora">Unidade Orçamentária:</a>
                                </strong>
                            </label>
                        </td>
                        <td>
                            <input id="unidadeOrcamentaria" name="unidadeOrcamentaria" class="field-size2 readonly"
                                   readonly>
                            <input id="unidadeOrcamentariaDescricao" name="unidadeOrcamentariaDescricao"
                                   title="Unidade Orçamentária Descrição" class="field-size10 readonly" disabled>
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
                            <input id="funcaoDescricao" name="funcaoDescricao" title="Função Descrição" lang="o52_descr"
                                   class="field-size10">
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
                            <input id="subfuncaoDescricao" name="subfuncaoDescricao" title="Subfunção Descrição"
                                   class="field-size10" lang="o53_descr">
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
                            <input id="programaDescricao" name="programaDescricao" title="Programa Descrição"
                                   class="field-size10" lang="o54_descr">
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
                            <input id="acaoDescricao" name="acaoDescricao" title="Ação Descrição" class="field-size10"
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
                            <input id="subtituloDescricao" name="subtituloDescricao" title="Subtítulo Descrição"
                                   class="field-size10" lang="o11_descricao">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </fieldset>
            <fieldset class="separator" style="margin-top: 2.5%;">
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
                            <input type="text" id="estrutural" name="estrutural" lang="c60_estrut"
                                   class="field-size3 readonly"
                                   readonly>
                            <input type="hidden" id="naturezaDespesa" name="naturezaDespesa" lang="c60_codcon">
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
                        <td>
                            <select name="identificadorUso" id="identificadorUso" class="field-size10">
                                <option selected value="">Selecionar</option>
                                <option value="0">0 - Recursos não destinados à contrapartida ou à identificação de
                                    despesas destinadas ao mínimo da Saúde ou ao mínimo da Educação
                                </option>
                                <option value="1">1 - Contrapartida de empréstimos do BIRD</option>
                                <option value="2">2 - Contrapartida de empréstimos do BID</option>
                                <option value="3">3 - Contrapartida de empréstimos do CAF</option>
                                <option value="4">4 - Contrapartida de outros empréstimos</option>
                                <option value="5">5 - Contrapartida de doações</option>
                                <option value="6">6 - Recursos não destinados à contrapartida, para identificação das
                                    despesas destinadas ao mínimo da Saúde
                                </option>
                                <option value="7">7 - Recursos de Contrapartida de Convênio</option>
                                <option value="8">8 - Recursos não destinados à contrapartida, para identificação das
                                    despesas destinadas ao mínimo da Educação
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
                        <td>
                            <select name="tipoDetalhamento" id="tipoDetalhamento" class="field-size10">
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
                        <td>
                            <select name="grupoFonteRecurso" id="grupoFonteRecurso" class="field-size10">
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
                        <td>
                            <select name="especificacaoFonte" id="especificacaoFonte" class="field-size10">
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
                    <tr>
                        <td>
                            <label for="identificadorResultadoPrimario">
                                <strong>Identificador de Resultado Primário:</strong>
                            </label>
                        </td>
                        <td>
                            <select name="identificadorResultadoPrimario" id="identificadorResultadoPrimario"
                                    class="field-size10">
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
        </fieldset>
        <input type="button" value="Emitir Previsão da Despesa" id="emitirPrevisaoDespesa"/>
        <input type="button" value="Emitir Planos Orçamentários" id="emitirPlanoOrcamentario"/>
    </form>
</div>
<?php
db_menu();
?>

<script type="text/javascript">
    const codigoDotacao = document.querySelector('#codigoDotacao');
    const codigoDotacaoAncora = document.querySelector('#codigoDotacaoAncora');
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
    const naturezaDespesaDescricao = document.querySelector('#naturezaDespesaDescricao');
    const ano = 2019;

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
            'aParametrosAdicionais': ['previsao=true', 'sSomenteEstrutural=3'],
        });
        lookupNaturezaDespesa.setCamposAdicionais(['c60_codcon']);
        lookupNaturezaDespesa.setCallBack('onClick', carregarNatureza);

        function carregarNatureza(params) {
            naturezaDespesa.value = params[2];
        }

        const lookUpCodigoDotacao = new DBLookUp(codigoDotacaoAncora, codigoDotacao, document.createElement('input'), {
            'sArquivo': 'func_previsaodespesa.php',
            'sLabel': 'Pesquisar Dotação',
        });

        lookUpCodigoDotacao.setCallBack('onClick', carregarCodigoDotacao);

        function carregarCodigoDotacao(parametros) {
            codigoDotacao.value = parametros[1];
        }
    }

    criarLookUps();

    $('unidadeOrcamentariaAncora').addEventListener('click', function () {

        const nome = 'db_iframe_unidade_orcamentaria';
        const arquivo = 'func_db_config_orcunidade.php';
        const titulo = 'Pesquisar Unidade Orçamentária';
        const mostra = true;
        const campos = '|o41_orgao|o41_unidade|o40_descr|o41_descr';
        const funcao = '?funcao_js=parent.' + preencherUnidadeOrcamentaria.name;

        js_OpenJanelaIframe('', nome, arquivo + funcao + campos, titulo, mostra);
    });

    function preencherUnidadeOrcamentaria(chave1, chave2, chave3, chave4) {
        const orgao = chave1.padStart(2, '0');
        const unidade = chave2.padStart(2, '0');
        const codigoTribunal = orgao + unidade;
        const orgaoUnidade = chave3 + ' / ' + chave4;

        unidadeOrcamentaria.value = codigoTribunal;
        unidadeOrcamentariaDescricao.value = orgaoUnidade;
        db_iframe_unidade_orcamentaria.hide();
    }

    $('emitirPrevisaoDespesa').addEventListener('click', function () {
        var formData = new FormData($('filtrosRelatorio'));
        formData.append('exec', 'emitirPrevisaoDespesa');
        processaEmissao(formData, 'Previsão da Despesa');
    });

    $('emitirPlanoOrcamentario').addEventListener('click', function () {
        var formData = new FormData($('filtrosRelatorio'));
        formData.append('exec', 'emitirPlanoOrcamentario');
        processaEmissao(formData, 'Plano Orçamentario');
    });

    function processaEmissao(formData, titulo) {
        js_divCarregando('Emitindo Relatório', 'loading_message');

        return fetch('con1_previsao_despesa.RPC.php', {
            method: 'POST',
            body: formData,
            credentials: 'include',
        }).then(response => response.json()).then(response => {
            if (response.erro) {
                return alert(response.mensagem);
            }

            var download = new DBDownload();
            download.addFile(response.arquivo, titulo);
            download.show();
        }).finally(() => js_removeObj('loading_message'));
    }
</script>
</body>
</html>

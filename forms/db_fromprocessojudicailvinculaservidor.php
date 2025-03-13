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
?>

<div id="idFuncionarios" style="display:flex;flex-direction:row;flex-wrap:wrap;justify-content:center;align-items:center;">
    <div hidden='hidden'  id='idMensagem' class="alert alert-success" role="alert" style="margin:20px;width:52vw;"></div>
    <div id="idFuncionarios" style="float:right;">
        <fieldset id="idFieldsetFuncionarioVinculados" style="width:52vw;"> 
            <legend> <div id="idExibeNumeroProcesso" style="float:right; background:#fff2cc; border-radius:5px;"> </div></legend> 
                <div id="gridVinculo">
                    <div id="containerVinculo">
                        <table class='form-container' id="dataVinculo-table"></table>
                    </div>
                </div>
        </fieldset>
    </div>
    <form name="formVinculaServidor" class="container" id="idFormVinculaServidor" style="width:50vw;">
        <input type="hidden" name="sequencial" id="sequencial">
        <input type="hidden" name="sequencialProcesso" id="sequencialProcesso">
        <input type="hidden" name="lancamentoPrevidenciario" id="idLancamentoPrevidenciario">
        <input type="hidden" name="lancamentoFGTS" id="idLancamentoFGTS">
        <input type="hidden" name="lancamentoUnicidade" id="idLancamentoUnicidade">
        <input type="hidden" name="lancamentoAnoAbono" id="idLancamentoAnoAbono">
        <input type="hidden" name="lancamentoMudancaCategoria" id="idLancamentoMudancaCategoria">
        <input type="hidden" name="sequencialAnoAbonoExcluir" id="idSequencialAnoAbonoExcluir">
        <input type="hidden" name="sequencialMudancaCategoriaExcluir" id="idSequencialMudancaCategoriaExcluir">
        <input type="hidden" name="sequencialUnicidadeExcluir" id="idSequencialUnicidadeExcluir">
        <input type="hidden" name="sequencialUnicidadeEditar" id="idSequencialUnicidadeEditar">

        <fieldset id="idFieldsetFuncionarioVinculado" style="width:50vw;">
            <legend>Funcionários Vinculados</legend>
            <table class="form-container" style="width:50vw;">
                <tr>
                    <td>
                        <div id="idFiltroMatriculaSelecao">
                            <label for="filtroCombo">Selecione o filtro</label>
                            <select id="filtroCombo" style = "width:10vw">
                                <option value="1">Matrícula</option>
                                <option value="2">Seleção</option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="idFiltroSelecao" style="display:none;">
                            <a id="ancoraSelecao">Seleção:</a>
                            <input type="text" name="codigoSelecao" id="codigoSelecao" class="field-size2" data="r44_selec">
                            <input type="text"
                            name="descricaoSelecao"
                            id="descricaoSelecao"
                            class="field-size9 readonly"
                            data="r44_descr">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="containerLancadorMatricula" style="display:block"></div>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset id="idFieldsetInformacoesAdicionais" style="padding:16px; width:50vw;">
            <legend>Informações Adicionais ref. ao Contrato de trabalho.</legend>
            <table class='form-container' style="width:50vw;">
                <tbody>
                    <tr>
                        <td align = "left"><strong><label for="idTipoContrato">Tipo de contrato a que se refere o processo judicial:</label></strong></td>
                        <td align = "left"> 
                            <select name="tipoContrato" id="idTipoContrato" style="width:10vw;">
                                <option value="">Selecione...</option>
                                <option value="1">1 - Trabalhador com vínculo formalizado, sem alteração nas datas de admissão e de desligamento</option>
                                <option value="2">2 - Trabalhador com vínculo formalizado, com alteração na data de admissão</option>
                                <option value="3">3 - Trabalhador com vínculo formalizado, com inclusão ou alteração de data de desligamento</option>
                                <option value="4">4 - Trabalhador com vínculo formalizado, com alteração nas datas de admissão e de desligamento</option>
                                <option value="5">5 - Empregado com reconhecimento de vínculo</option>
                                <option value="6">6 - Trabalhador sem vínculo de emprego/estatutário (TSVE), sem reconhecimento de vínculo empregatício</option>
                                <option value="7">7 - Trabalhador com vínculo de emprego formalizado em período anterior ao eSocial</option>
                                <option value="8">8 - Responsabilidade indireta</option>
                                <option value="9">9 - Trabalhador cujos contratos foram unificados (unicidade contratual)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align = "left"><strong><label for="idIndicativoContrato">Indicativo se o contrato possui informação no evento S-2190, S-2200 ou S-2300 no declarante.</strong></td>
                        <td align = "left">
                        <select name="indicativoContrato" id="idIndicativoContrato">
                                <option value="">Selecione...</option>
                                <option value="S">Sim</option>
                                <option value="N">Não</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align = "left"><strong><label for="idIndicativoReintegracao">Indicativo de reintegração do empregado.</strong></td>
                        <td align = "left">
                        <select name="indicativoReintegracao" id="idIndicativoReintegracao">
                                <option value="">Selecione...</option>
                                <option value="S">Sim</option>
                                <option value="N">Não</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align = "left"><strong><label for="idIndicativoNovaCategoria">Indicativo se houve reconhecimento de categoria do trabalhador diferente da informada (no eSocial ou na GFIP) pelo declarante..</strong></td>
                        <td align = "left">
                        <select name="indicativoNovaCategoria" id="idIndicativoNovaCategoria">
                                <option value="">Selecione...</option>
                                <option value="S">Sim</option>
                                <option value="N">Não</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align = "left"><strong><label for="idNovaAtividade">Indicativo se houve reconhecimento de natureza da atividade diferente da cadastrada pelo declarante.</strong></td>
                        <td align = "left">
                        <select name="novaAtividade" id="idNovaAtividade">
                                <option value="">Selecione...</option>
                                <option value="S">Sim</option>
                                <option value="N">Não</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align = "left"><strong><label for="idMotivoDesligamento">Indicativo se houve reconhecimento de motivo de desligamento diferente do informado pelo declarante.</strong></td>
                        <td align = "left">
                        <select name="motivoDesligamento" id="idMotivoDesligamento">
                                <option value="">Selecione...</option>
                                <option value="S">Sim</option>
                                <option value="N">Não</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
        <fieldset id="idFieldsetNovoCodigoCategoria" style="padding:16px; width:50vw;">
            <legend>Informações de Novo Código de Categoria Reconhecido Judicialmente.</legend>
            <table class='form-container' style="width:50vw;">
                <tbody>
                    <tr>
                        <td align = "left"><strong>Código Categoria<label for="idCodigoCategoriaMudanca"></strong></td>
                        <td align = "left"><input type="text" id="idCodigoCategoriaMudanca" name="codigoCategoriaMudanca" minlength="3" maxlength="3" size="5"></td>
                    </tr>
                    <tr>
                    <td align = "left"><strong><label for="idNaturezaAtividadeMudanca">Indicativo se houve reconhecimento de unicidade contratual.</strong></td>
                        <td align = "left">
                        <select name="naturezaAtividadeMudanca" id="idNaturezaAtividadeMudanca">
                                <option value="">Selecione...</option>
                                <option value="1">1 - Trabalho urbano</option>
                                <option value="2">2 - Trabalho rural</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align = "left"><label for="idDataReconhecidoMudanca"><strong>Data de Reconhecimento de Nova Categoria:</strong></label></td>
                        <td align = "left"><input type="date" id="idDataReconhecidoMudanca" name="dataReconhecidoMudanca"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;" colspan="2">
                            <a class="btn btn-light" id="idBtnLancarMudanca">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>Lançar Mudança
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td  colspan="2">
                            <div hidden='hidden'  id='idMensagemMudanca' class="alert alert-success" role="alert" style="margin:20px;width:52vw;"></div>
                            <div id="dataMudanca-table" ></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
        <fieldset id="idFieldsetUnicidadeContratual" style="padding:16px; width:50vw;">
            <legend>Informações dos Vínculos/Contratos Incorporados por Reconhecimento de Unicidade Contratual.</legend>
            <table class='form-container' style="width:50vw;">
                <tbody>
                    <tr>
                        <td align = "left"><strong>Matricula Incorporada<label for="idMatriculaUnicidade"></strong></td>
                        <td align = "left"><input type="text" id="idMatriculaUnicidade" name="matriculaUnicidade" size="10"></td>
                    </tr>
                    <tr>
                        <td align = "left"><strong>Código Categoria por Unicidade<label for="idCodigoCategoriaUnicidade"></strong></td>
                        <td align = "left"><input type="text" id="idCodigoCategoriaUnicidade" name="codigoCategoriaUnicidade" minlength="3" maxlength="3" size="5"></td>
                    </tr>
                    <tr>
                        <td align = "left"><label for="idDataReconhecidoUnicidade"><strong>Data Inicio da Unicidade Contratual(TSVE):</strong></label></td>
                        <td align = "left"><input type="date" id="idDataReconhecidoUnicidade" name="dataReconhecidoUnicidade"></td>
                    </tr>
                    <tr>
                    <td style="text-align: center;" colspan="2">
                            <a class="btn btn-light" id="idBtnLancarUnicidade">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>Lançar Unicidade
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td  colspan="2">
                            <div hidden='hidden'  id='idMensagemUnicidade' class="alert alert-success" role="alert" style="margin:20px;width:52vw;"></div>
                            <div id="dataUnicidade-table" ></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
        <fieldset id="idFieldsetPeriodoNaoDeclarado" style="padding:16px; width:50vw;">
            <legend>Informações dos períodos e valores decorrentes de processo trabalhista e ainda não declarados no eSocial.</legend>
            <table class='form-container' style="width:50vw;">
                <tbody>
                    <tr>
                        <td align = "left"><label for="idMesInicialProcesso"><strong>Competência Inicial do Processo/Conciliação:</strong></label></td>
                        <td align = "left"><input type="text" id="idMesInicialProcesso" pattern="[0-9]*" name="mesInicialProcesso" minlength="2" maxlength="2" size="4" onchange="this.value = this.value > 0 ? this.value.padStart(this.maxLength, '0') : '';" placeholder = "Mês">
                        <input type="text" id="idAnoInicialProcesso" pattern="[0-9]*" name="anoInicialProcesso" minlength="4" maxlength="4" size="6" placeholder = "Ano"></td>
                    </tr>
                    <tr>
                        <td align = "left"><label for="idMesFinalProcesso"><strong>Competência Final do Processo/Conciliação:</strong></label></td>
                        <td align = "left"><input type="text" id="idMesFinalProcesso" pattern="[0-9]*" name="mesFinalProcesso" minlength="2" maxlength="2" size="4" onchange="this.value = this.value > 0 ? this.value.padStart(this.maxLength, '0') : '';" placeholder = "Mês">
                        <input type="text" id="idAnoFinalProcesso" pattern="[0-9]*" name="anoFinalProcesso" minlength="4" maxlength="4" size="6" placeholder = "Ano"></td>
                    </tr>
                    <tr>
                        <td align = "left"><label for="idIndicativoRepercussao"><strong>Indicativo de repercussão do processo trabalhista ou de demanda submetida à CCP ou ao NINTER:</strong></label></td>
                        <td align = "left">
                            <select id="idIndicativoRepercussao" name="indicativoRepercussao" style = "width:10vw" >
                                <option value="">Selecione...</option>
                                <option value="1">1 - Decisão COM repercussão tributária e/ou FGTS</option>
                                <option value="2">2 - Decisão SEM repercussão tributária ou FGTS</option>
                                <option value="3">3 - Decisão com repercussão exclusiva para declaração de rendimentos para fins de Imposto de Renda</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align = "left"><label for="idIdenizacaoSD"><strong>Houve decisão para pagamento da indenização substitutiva do seguro-desemprego?:</strong></label></td>
                        <td align = "left">
                            <select id="idIdenizacaoSD" name="idenizacaoSD" >
                                <option value="">Selecione...</option>
                                <option value="S">S - Sim</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align = "left"><label for="idIdenizacaoAbono"><strong>Houve decisão para pagamento da indenização substitutiva de abono salarial?:</strong></label></td>
                        <td align = "left">
                            <select id="idIdenizacaoAbono" name="idenizacaoAbono" >
                                <option value="">Selecione...</option>
                                <option value="S">S - Sim</option>
                            </select>
                        </td>
                    </tr>

                    <!-- <tr>
                        <td align = "left"><label for="idValorRemuneracao"><strong>Valor Total de Verbas Remuneratórias Pagas ao Trabalhador:</strong></label></td>
                        <td align = "left"><input type="text" size="22"   value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.');" id="idValorRemuneracao" name="valorRemuneracao"/></td>
                    </tr>
                    <tr>
                        <td align = "left"><label for="idValorAviso"><strong>Valor do Aviso Prévio Indenizado:</strong></label></td>
                        <td align = "left"><input type="text" size="22"  value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" id="idValorAviso" name="valorAviso"/></td>
                    </tr>
                    <tr>
                        <td align = "left"><label  for="idValorAviso13"><strong>Valor do Aviso Prévio Indenizado Sobre 13º Salário:</strong></label></td>
                        <td align = "left"><input type="text" size="22"  value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" id="idValorAviso13" name="valorAviso13"/></td>
                    </tr>
                    <tr>
                        <td align = "left"><label  for="idValorOutras"><strong>Valor de Outras Verbas Indenizatórias:</strong></label></td>
                        <td align = "left"><input type="text" size="22"  value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" id="idValorOutras" name="valorOutras"/></td>
                    </tr>
                    <tr>
                        <td align = "left"><label for="idPagamentoDiretoRescisao"><strong>A indenização compensatória (multa rescisória) do FGTS transacionada foi paga diretamente ao trabalhador mediante decisão/autorização judicial?</strong></label></td>
                        <td align = "left">
                            <select id="idPagamentoDiretoRescisao" name="pagamentoDiretoRescisao" >
                                <option value="">Selecione...</option>
                                <option value="S">Sim</option>
                                <option value="N">Não</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align = "left"><label  for="idValorBaseFGTS"><strong>Valor da base de cálculo para recolhimento da indenização compensatória (multa rescisória) do FGTS:</strong></label></td>
                        <td align = "left"><input type="text" size="22" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" id="idValorBaseFGTS" name="valorBaseFGTS"/></td>
                    </tr> -->
                </tbody>
            </table>
            <!-- Aqui tabela -->
            <fieldset>
                <legend>Abono</legend>
                <table class='form-container' style="width:50vw;">
                    <tbody>
                        <tr>
                            <td align = "left"><label for="idMesPeriodoApuracao"><strong>Ano-base em que houve indenização substitutiva do abono salarial:</strong></label></td>
                            <td align = "left"><input type="text" id="idAnoAbono" pattern="[0-9]*" name="anoAbono" minlength="4" maxlength="4" size="6" placeholder = "Ano"></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;" colspan="2">
                                <a class="btn btn-light" id="idBtnLancarAnoAbono">
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>Lançar Ano Abono
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td  colspan="2">
                                <div hidden='hidden'  id='idMensagemAbono' class="alert alert-success" role="alert" style="margin:20px;width:52vw;"></div>
                                <div id="dataAbono-table" ></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
            <fieldset>
            <legend>Lançamentos</legend>
                <fieldset>
                    <legend>Para fins previdenciários</legend>
                    <table class='form-container'style="width:50vw;">
                        <tbody>
                            <tr>
                                <td align = "left"><label for="idMesPeriodoApuracao"><strong>Período Apuração:</strong></label></td>
                                <td align = "left"><input type="text" id="idMesPeriodoApuracao" pattern="[0-9]*" name="mesPeriodoApuracao" minlength="2" maxlength="2" size="4" onchange="this.value = this.value > 0 ? this.value.padStart(this.maxLength, '0') : '';" placeholder = "Mês">
                                <input type="text" id="idAnoPeriodoApuracao" pattern="[0-9]*" name="anoPeriodoApuracao" minlength="4" maxlength="4" size="6" placeholder = "Ano"></td>
                            </tr>
                            <tr>
                                <td align = "left"><label for="idMensalContribuicao"><strong>Valor da base de cálculo da contribuição previdenciária sobre a remuneração mensal do trabalhador:</strong></label></td>
                                <td align = "left"><input type="text" size="22" id="idMensalContribuicao" name="mensalContribuicao" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')"/></td>
                            </tr>
                            <tr>
                                <td align = "left"><label for="idContribuicao13"><strong>Valor da base de cálculo da contribuição previdenciária sobre a remuneração do trabalhador referente ao 13º salário:</strong></label></td>
                                <td align = "left"><input type="text" size="22" id="idContribuicao13" name="contribuicao13" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')"/></td>
                            </tr>
                            <tr>
                                <td align = "left"><label for="idIdenizacaoAbono"><strong>Grau de exposição a agentes nocivos:</strong></label></td>
                                <td align = "left">
                                    <select id="idGrauExposicao" name="grauExposicao" style="width:10vw;">
                                        <option value="">Selecione...</option>
                                        <option value="1">1 - Não ensejador de aposentadoria especial</option>
                                        <option value="2">2 - Ensejador de aposentadoria especial - FAE15_12% (15 anos de contribuição e alíquota de 12%)</option>
                                        <option value="3">3 - Ensejador de aposentadoria especial - FAE20_09% (20 anos de contribuição e alíquota de 9%)</option>
                                        <option value="4">4 - Ensejador de aposentadoria especial - FAE25_06% (25 anos de contribuição e alíquota de 6%)</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align = "left"><label for="idCodigoMudancaoCategoria"><strong>Código de Categoria do Trabalhador Declarado no Período de Referência:</strong></label></td>
                                <td align = "left"><input type="text" minlength="3" maxlength="3" size="5" id="idCodigoMudancaoCategoria" name="codigoMudancaoCategoria"></td>
                            </tr>
                            <tr>
                                <td align = "left"><label for="idBaseMudancaoCategoria"><strong>Valor da remuneração do trabalhador a ser considerada para fins previdenciários declarada em GFIP ou em S-1200 de trabalhador sem cadastro no S-2300:</strong></label></td>
                                <td align = "left"><input type="text" size="20" id="idBaseMudancaoCategoria" name="baseMudancaoCategoria" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')"></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;" colspan="2">
                                    <a class="btn btn-light" id="idBtnLancarRegistro">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>Lançar Fins Previdenciário
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <div hidden='hidden'  id='idMensagemBaseCalculo' class="alert alert-success" role="alert" style="margin:20px;width:52vw;"></div>
                                    <div id="dataBaseCalculo-table" ></div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </fieldset>
                <fieldset>
                    <legend>Para fins do FGTS</legend>
                    <table class='form-container'>
                        <tbody>
                            <tr>
                                <td align = "left"><label for="idMesPeriodoApuracaoFGTS"><strong>Período Apuração:</strong></label></td>
                                <td align = "left"><input type="text" id="idMesPeriodoApuracaoFGTS" pattern="[0-9]*" name="mesPeriodoApuracaoFGTS" minlength="2" maxlength="2" size="4" onchange="this.value = this.value > 0 ? this.value.padStart(this.maxLength, '0') : '';" placeholder = "Mês">
                                <input type="text" id="idAnoPeriodoApuracaoFGTS" pattern="[0-9]*" name="anoPeriodoApuracaoFGTS" minlength="4" maxlength="4" size="6" placeholder = "Ano"></td>
                            </tr>
                            <tr>
                                <td align = "left"><label for="idValorFGTSSemSEFIP"><strong>Valor da base de cálculo de FGTS ainda não declarada em SEFIP ou no eSocial, inclusive de verba reconhecida no processo trabalhista:</strong></label></td>
                                <td align = "left"><input type="text" size="22" id="idValorFGTSSemSEFIP" name="valorFGTSSemSEFIP" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')"/></td>
                            </tr>
                            <tr>
                                <td align = "left"><label for="idValorFGTSComSEFIP"><strong>Valor da base de cálculo de FGTS declarada apenas em SEFIP (não informada no eSocial) e ainda não recolhida:</strong></label></td>
                                <td align = "left"><input type="text" size="22" id="idValorFGTSComSEFIP" name="valorFGTSComSEFIP" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')"/></td>
                            </tr>
                            <tr>
                                <td align = "left"><label for="idValorFGTSAnterior"><strong>Valor da base de cálculo de FGTS declarada anteriormente no eSocial e ainda não recolhida:</strong></label></td>
                                <td align = "left"><input type="text" size="22" id="idValorFGTSAnterior" name="valorFGTSAnterior" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')"/></td>
                            </tr>
                            <tr>
                                <td style="text-align: center;" colspan="2">
                                    <a class="btn btn-light" id="idBtnLancarFGTS">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>Lançar Fins FGTS
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div hidden='hidden'  id='idMensagemFGTS' class="alert alert-success" role="alert" style="margin:20px;width:52vw;"></div>
                                    <div id="dataFGTS-table"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </fieldset>
            <!-- Fim tabela -->
        </fieldset>
        <table class='form-container' style="float: center;width:50vw;">
            <tr>
                <td style="text-align: center;">
                    <input type="button" id="idIncluirVinculoServidor" value="Vincular Funcionário">
                </td>
            </tr>
        </table>

    </form>
</div>

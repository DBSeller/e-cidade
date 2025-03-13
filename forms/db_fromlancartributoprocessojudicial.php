<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou123456789
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

 <div class="container" >
 <form name="formTributoProcessoJudicial" class="row g-3 needs-validation" id="idFormTributoProcessoJudicial">
    <div hidden='hidden'  id='idMensagem' class="alert alert-success" role="alert"
        style="text-align:center; width:50vw;">
    </div>
    <input type="hidden" name="sequencial" id="sequencial">
    <input type="hidden" name="lancamentosTributosPagamento" id="idInputTributosPagamento">
    <input type="hidden" name="lancamentosTributosPrevidencial" id="idInputTributosPrevidencial">
    <input type="hidden" name="lancamentosTributosIRRF" id="idInputTributosIRRF">
    <input type="hidden" name="dataSentencaAcordo" id="idDataSentencaAcordo">
    <input type="hidden" name="numeroProcessoDefinido" id="idNumeroProcessoDefinido">
    <input type="hidden" name="sequencialBaseExcluir" id="idSequencialBaseExcluir">
    <input type="hidden" name="sequencialBaseEditar" id="idSequencialBaseEditar">
    <input type="hidden" name="sequencialExcluirIRRF" id="idSequencialExcluirIRRF">
    <input type="hidden" name="sequencialPrevidenciaExcluir" id="idSequencialPrevidenciaExcluir">
    <input type="hidden" name="sequencialPrevidencialEditar" id="idSequencialPrevidencialEditar">

    <input type="hidden" name="lancamentosCodigoIRRF" id="idInputCodigoIRRF">
    <input type="hidden" name="sequencialCodigoIRRFExcluir" id="idSequencialCodigoIRRFExcluir">

    <input type="hidden" name="lancamentosAdvogado" id="idInputAdvogado">
    <input type="hidden" name="sequencialAdvogadoExcluir" id="idSequencialAdvogadoExcluir">

    <fieldset id="idFieldsetPrincipalProcesso">
        <legend>Tributos Decorrentes de Processo Trabalhista</legend>
        <table class="form-container" >
            <tbody>
                <tr>
                    <td colspan="2" align = "left"><label for="idFiltro"><strong>Filtro:</strong></label>
                        <select id="idFiltro" style="width:170px;" onchange = "defineFiltro()">
                            <option selected value="">Selecione o fitro...</option>
                            <!-- <option value="processo">Processo</option> -->
                            <option value="matricula">Matrícula</option>
                        </select>
                    </td>

                </tr>
                <tr id ="idMatriculaLinha" style="display: none;">
                    <td>
                        <a id="ancoraMatricula" href="#">Matrícula:</a>
                    </td>
                    <td>
                        <input id="codigoMatricula" name="codigoMatricula" type="text" data="rh01_regist" class="field-size2"/>
                        <input id="nomeServidor" name="nomeServidor" type="text" data="z01_nome" class="field-size7 readonly" disabled="disabled"/>
                    </td>
                </tr>
                <tr id ="idProcessoLinha" style="display: none;">
                    <td>
                    <label for="idNumeroProcesso"><strong>Número de Processo:</strong></label>
                    </td>
                    <td>
                        <input id="idNumeroProcesso" name="numeroProcesso" type="text" />
                    </td>
                </tr>
                <tr>
                    <td align = "left" colspan="2"><label id="idLabelProcesso" style="display: none;" for="idSelectProcesso"><strong>Lista de Processos:</strong></label>
                        <select onchange = "lancamentoProcessos()" id="idSelectProcesso" name="sequencialNumeroProcesso" style="width:350px;display: none;" >
                            <option selected value="">Selecione...</option>
                        </select>
                    </td>

                </tr>
                <tr style="text-align:center" >
                    <table id="idTabelaPeriodo" class="form-container" style="display: none; margin:auto; padding: 10px; margin: 10px; background: #d3d3d3;" >
                        <caption style="text-align:center" ><strong>Identificação do período e da base de cálculo dos tributos.</strong></caption>
                        <tbody>
                            <tr>
                                <td align = "left"><label for="idPerApura"><strong>Ano e mês de pagamento:</strong></label></td>
                                <td align = "left"><input type="text" size="3" id="idPerApuraMes" minlength="2" maxlength="2" onchange="this.value = this.value > 0 ? this.value.padStart(this.maxLength, '0') : '';" placeholder = "Mês" name="periodoApuracaoMes" />
                                    <input type="text" minlength="4" maxlength="4" size="5" id="idPerApuraAno" name="periodoApuracaoAno" placeholder='Ano' />
                                </td>
                            </tr>
                            <tr>
                                <td align = "left"><label for="idPerApura"><strong>Observacao:</strong></label></td>
                                <td align = "left"><textarea id = "idObservacao" name="observacao"></textarea> 
                                </td>
                            </tr>
                            <tr>
                                <td align = "left"><label id="idLabelPeriodo" style="display: none;" for="idPeriodoReferencia"><strong>Ano e mês contemplado:</strong></label></td>
                                <td align = "left">
                                    <select onchange = "validaPeriodo()" id="idPeriodoReferencia" name="periodoRef" style="display: none; width:170px;" >
                                        <option selected value="">Selecione...</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align = "left"><label for="idMensalContribuicao"><strong>Base de cálculo da contribuição previdenciária mensal:</strong></label></td>
                                <td align = "left"><input type="text" size="22" id="idMensalContribuicao" name="mensalContribuicao" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')"/></td>
                            </tr>
                            <tr>
                                <td align = "left"><label for="idContribuicao13"><strong>Base de cálculo da contribuição previdenciária mensal de 13º salário:</strong></label></td>
                                <td align = "left"><input type="text" size="22" id="idContribuicao13" name="contribuicao13" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')"/></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;"> <input type="button" id="idLancarContribuicaoTributaria" value="Lançar Registro"> </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <div>
                                        <div id="dataBaseCalculo-tabela" ></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </tr>
                <tr style="text-align:center" >
                    <table id="idTabelaContribuicao" align="center" class="form-container" style="display: none; padding: 10px; margin: 10px; background: #d3d3d3;" >
                        <caption style="text-align:center" ><strong>Informações das contribuições sociais devidas à Previdência Social e Outras Entidades e Fundos.</strong></caption>
                        <tbody>
                        <tr>
                            <td align = "left"><label for="idPeriodoPagamentoContemplado"><strong>Períodos de Pagamento/Contemplado:</strong></label></td>
                            <td align = "left">
                                <select onchange = "" id="idPeriodoPagamentoContemplado" name="periodoPagamentoContemplado" style="display: none; width:170px;" >
                                    <option selected value="">Selecione...</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idtpCRLabel"  for="idtpCR"><strong>Código da Receita:</strong></label></td>
                            <td align = "left">
                                <select onchange = "" id="idtpCR" name="tpCR" style="display: none; width:170px;" >
                                    <option selected value="">Selecione...</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align = "left"><label for="idvrCR"><strong>Valor Correspondente.:</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idvrCR" name="vrCR" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;"> <input type="button" id="idLancarTributoPrevidencial" value="Lançar Registro"> </td>
                        </tr>
                        <tr>
                            <td colspan="2" align = "center">
                                <div>
                                    <div id="dataTributoPrevidencial-tabela" ></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </tr>
                <tr style="text-align:center" >
                    <table id="idTabelaImpostoRenda" class="form-container" style="display: none; margin:auto; padding: 10px; margin: 10px; background: #d3d3d3;" >
                        <caption style="text-align:center" ><strong>Informações de Imposto de Renda Retido na Fonte, por Código de Receita.</strong></caption>
                        <tbody>
                        <tr>
                            <td align = "left"><label id="idPerApurPgtoLabel"  for="idPerApurPgto"><strong>Período Pagamento:</strong></label></td>
                            <td align = "left">
                                <select onchange = "" id="idPerApurPgtoImpostoRenda" name="perApurPgtoImpostoRenda" style="display: none; width:170px;" >
                                    <option selected value="">Selecione...</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idtpCRLabel"  for="idtpCRImpostoRenda"><strong>Código da Receita:</strong></label></td>
                            <td align = "left">
                                <select onchange = "" id="idtpCRImpostoRenda" name="tpCRImpostoRenda" style="display: none; width:170px;" >
                                    <option selected value="">Selecione...</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align = "left"><label for="idvrCRImpostoRenda"><strong>Valor Correspondente.:</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idvrCRImpostoRenda" name="vrCRImpostoRenda" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;"> <input type="button" id="idTributoImpostoRenda" value="Lançar Registro"> </td>
                        </tr>
                        <tr>
                            <td colspan="2" align = "center">
                                <div>
                                    <div id="dataImpostoRenda-tabela" ></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </tr>
            </tbody>
        </table>
    </fieldset>
    <div id="idNavegacao" style="display: none;">
         <button type="button" id="idSalvarProcessoJudicial">
            <i class="fas fa-save"></i>
            Salvar
        </button>
        <button type="button" id="idProximaAba">
            Próximo
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

</form>
</div>
<script>

</script>

<!--
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
 -->
<style>
    input:valid{
    background-color: whitesmoke;
    }
</style>
 <div class="container" >
 <form name="formComplementoTributoProcessoJudicial" class="row g-3 needs-validation" id="idFormComplementoTributoProcessoJudicial">
    <div hidden='hidden'  id='idMensagem' class="alert alert-success" role="alert"
        style="text-align:center; width:50vw;">
    </div>

    <input type="hidden" name="lancamentosCodigoIRRF" id="idInputCodigoIRRF">
    <input type="hidden" name="sequencialCodigoIRRFExcluir" id="idSequencialCodigoIRRFExcluir">

    <input type="hidden" name="lancamentosAdvogado" id="idInputAdvogado">
    <input type="hidden" name="sequencialAdvogadoExcluir" id="idSequencialAdvogadoExcluir">

    <input type="hidden" name="lancamentosDependente" id="idInputDependente">
    <input type="hidden" name="sequencialDependenteExcluir" id="idSequencialDependenteExcluir">

    <input type="hidden" name="lancamentosPensao" id="idInputPensao">
    <input type="hidden" name="sequencialPensaoExcluir" id="idSequencialPensaoExcluir">

    <input type="hidden" name="lancamentosRetencao" id="idInputRetencao">
    <input type="hidden" name="sequencialRetencaoExcluir" id="idSequencialRetencaoExcluir">

    <input type="hidden" name="lancamentosValorRetencao" id="idInputValorRetencao">
    <input type="hidden" name="sequencialValorRetencaoExcluir" id="idSequencialValorRetencaoExcluir">

    <input type="hidden" name="lancamentosValorDeducaoSuspensa" id="idInputDeducaoSuspensa">
    <input type="hidden" name="sequencialValorDeducaoSuspensaExcluir" id="idSequencialDeducaoSuspensaExcluir">

    <input type="hidden" name="lancamentosValorSuspensaPensao" id="idInputSuspensaPensao">
    <input type="hidden" name="sequencialValorSuspensaPensaoExcluir" id="idSequencialSuspensaPensaoExcluir">

    <input type="hidden" name="lancamentosIRComplementar" id="idInputIRComplementar">
    <input type="hidden" name="sequencialIRComplementarExcluir" id="idSequencialIRComplementarExcluir">
 
    <fieldset id="idFieldsetComplementoTributoProcesso">
        <legend>Complemento dos Tributos de Processo Trabalhista</legend>
        <table class="form-container" >
            <tbody>
                <tr style="text-align:center" >
                    <table id="idTabelaCodigoIRRF" class="form-container" style="display: none; margin:auto; padding: 10px; margin: 10px; background: #d3d3d3;" >
                        <caption style="text-align:center" ><strong>Informações complementares, vinculadas ao 'Código de Receita' relativo a IRRF, relacionadas a rendimentos tributáveis e a deduções e/ou isenções de acordo com a legislação aplicada ao imposto de renda.</strong></caption>
                        <tbody>
                        <tr>
                            <td align = "left"><label id="idCodigoRelativoIRRFLabel"  for="idCodigoRelativoIRRF"><strong>Código de Receita - CR relativo a Imposto de Renda Retido na Fonte:</strong></label></td>
                            <td align = "left">
                                <select onchange = "" id="idCodigoRelativoIRRF" name="codigoRelativoIRRF" style=" width:170px;" >
                                    <option selected value="">Selecione...</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idValorRendimentoMensalLabel"  for="idValorRendimentoMensal"><strong>Valor do rendimento tributável mensal do Imposto de Renda:</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idValorRendimentoMensal" name="valorRendimentoMensal" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idValorRendimento13MensalLabel"  for="idValorRendimento13Mensal"><strong>Valor do rendimento tributável do Imposto de Renda referente ao 13º salário - Tributação exclusiva:</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idValorRendimento13Mensal" name="valorRendimento13Mensal" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idValorMolestiaGraveLabel"  for="idValorMolestiaGrave"><strong>Valor do rendimento isento por ser portador de moléstia grave atestada por laudo médico:</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idValorMolestiaGrave" name="valorMolestiaGrave" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idValorIsenta65Label"  for="idValorIsenta65"><strong>Valor de parcela isenta de aposentadoria para beneficiário de 65 anos ou mais:</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idValorIsenta65" name="valorIsenta65" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idvalorJuroMoraLabel"  for="idValorJuroMora"><strong>Juros de mora recebidos, devidos pelo atraso no pagamento de remuneração por exercício de emprego, cargo ou função:</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idValorJuroMora" name="valorJuroMora" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idValorNaoTributavelLabel"  for="idValorNaoTributavel"><strong>Valor de outros rendimentos isentos ou não tributáveis:</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idValorNaoTributavel" name="valorNaoTributavel" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idDescricaoNaoTributavelLabel"  for="idDescricaoNaoTributavel"><strong>Descrição do rendimento isento ou não tributável informado em 'Valor de outros rendimentos isentos ou não tributáveis':</strong></label></td>
                            <td align = "left"><textarea id = "idDescricaoNaoTributavel" name="descricaoNaoTributavel"></textarea> 
                        </tr>
                        <tr>
                            <td align = "left"><label id="idValorPrevidenciaOficialLabel"  for="idValorPrevidenciaOficial"><strong>Valor de outros rendimentos isentos ou não tributáveis:</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idValorPrevidenciaOficial" name="valorPrevidenciaOficial" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idDescricaoRRALabel"  for="idDescricaoRRA"><strong>Descrição dos Rendimentos Recebidos Acumuladamente - RRA:</strong></label></td>
                            <td align = "left"><textarea id = "idDescricaoRRA" name="descricaoRRA"></textarea> 
                        </tr>
                        <tr>
                            <td align = "left"><label id="idQuantidadeRRALabel"  for="idQuantidadeRRA"><strong>Número de meses relativo aos Rendimentos Recebidos Acumuladamente - RRA:</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idQuantidadeRRA" name="quantidadeRRA" value = '0' placeholder='0' /></td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idDespCustasLabel"  for="iddespCustas"><strong>Valor das despesas com custas judiciais:</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idDespCustas" name="despCustas" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                        </tr>
                        <tr>
                            <td align = "left"><label id="idDespAdvogadosLabel"  for="idDespAdvogados"><strong>Valor total das despesas com advogado(s):</strong></label></td>
                            <td align = "left"><input type="text" size="22" id="idDespAdvogados" name="despAdvogados" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;"> <button type="button" id="idLancarCodigoIRRF"><i class="fa-solid fa-square-caret-down"></i>Lançar regsitro</button></td>
                        </tr>
                        <tr>
                            <td  colspan="2">
                                <div align-items = 'center'; hidden='hidden'  id='idMensagemCodigoIRRF' class="alert alert-success" role="alert" style="text-align: center;margin:20px;width:52vw;" class="menssagem"></div>
                                <div id="dataCodigoIRRF-tabela" ></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </tr>
                <tr style="text-align:center">
                    <table id="idTabelaAdvogado" class="form-container" style="display: none; margin:auto; padding: 10px; margin: 10px; background: #d3d3d3;" >
                            <caption style="text-align:center" ><strong>Identificação dos advogados.</strong></caption>
                            <tbody>
                                <tr>
                                    <td align = "left"><label id="idTipoInscricaoADVLabel"  for="idTipoInscricaoADV"><strong>Tipo de inscrição:</strong></label></td>
                                    <td align = "left">
                                        <select onchange = "validaTipoIncricao()" id="idTipoInscricaoADV" name="tipoInscricaoADV" style="width:170px;" >
                                            <option selected value="">Selecione...</option>
                                            <option value="1">1 - CNPJ</option>
                                            <option value="2">2 - CPF</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idCNPJADVLabel"  for="idCNPJADV" style="display: none;"><strong>Número CNPJ:</strong></label></td>
                                    <td align = "left"><input onchange = "" type="text" size="22" id="idCNPJADV" name="cnpjADV" value = '' placeholder='' style="display: none;" maxlength="14"/></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idCPFADVLabel"  for="idCPFADV" style="display: none;"><strong>Número CPF:</strong></label></td>
                                    <td align = "left"><input onchange = "" type="text" size="22" id="idCPFADV" name="cpfADV" value = '' placeholder='' style="display: none;" maxlength="11"/></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idValorADVLabel"  for="idValorADV"><strong>Valor da despesa com o advogado, se houver.:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idValorADV" name="valorADV" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;"> <button type="button" id="idLancarAdvogado"><i class="fa-solid fa-share-from-square"></i>Lançar regsitro</button></td>
                                </tr>
                                <tr>
                                    <td  colspan="2">
                                        <div hidden='hidden'  id='idMensagemAdvogado' class="alert alert-success" role="alert" style="text-align: center;margin:20px;width:52vw;"></div>
                                        <div id="dataAdvogado-tabela" ></div>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </tr>
                <tr style="text-align:center">
                    <table id="idTabelaDependente" class="form-container" style="display: none; margin:auto; padding: 10px; margin: 10px; background: #d3d3d3;" >
                            <caption style="text-align:center" ><strong>Dedução do rendimento tributável relativa a dependentes.</strong></caption>
                            <tbody>
                                <tr>
                                    <td align = "left"><label id="idTipoRendimentoDEPLabel"  for="idTipoRendimentoDEP"><strong>Tipo de rendimento:</strong></label></td>
                                    <td align = "left">
                                        <select onchange = "" id="idTipoRendimentoDEP" name="tipoRendimentoDEP" style="width:170px;" >
                                            <option selected value="">Selecione...</option>
                                            <option value="11">11 - Remuneração mensal</option>
                                            <option value="12">13º salário</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idCPFDEPLabel"  for="idCPFDEP" ><strong>Número CPF:</strong></label></td>
                                    <td align = "left"><input onchange = "" type="text" size="22" id="idCPFDEP" name="cpfDEP" value = '' placeholder='' maxlength="11"/></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idValorDEPLabel"  for="idValorDEP"><strong>Valor da dedução da base de cálculo.:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idValorDEP" name="valorDEP" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;"> <button type="button" id="idLancarDependente"><i class="fa-solid fa-share-from-square"></i>Lançar regsitro</button></td>
                                </tr>
                                <tr>
                                    <td  colspan="2">
                                        <div hidden='hidden'  id='idMensagemDependente' class="alert alert-success" role="alert" style="text-align: center;margin:20px;width:52vw;"></div>
                                        <div id="dataDependente-tabela" ></div>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </tr>
                <tr style="text-align:center">
                    <table id="idTabelaPensao" class="form-container" style="display: none; margin:auto; padding: 10px; margin: 10px; background: #d3d3d3;" >
                            <caption style="text-align:center" ><strong>Informação dos beneficiários da pensão alimentícia.</strong></caption>
                            <tbody>
                                <tr>
                                    <td align = "left"><label id="idTipoRendimentoPENLabel"  for="idTipoRendimentoPEN"><strong>Tipo de rendimento:</strong></label></td>
                                    <td align = "left">
                                        <select onchange = "" id="idTipoRendimentoPEN" name="tipoRendimentoPEN" style="width:170px;" >
                                            <option selected value="">Selecione...</option>
                                            <option value="11">11 - Remuneração mensal</option>
                                            <option value="12">13º salário</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idCPFPENLabel"  for="idCPFPEN" ><strong>Número CPF:</strong></label></td>
                                    <td align = "left"><input onchange = "" type="text" size="22" id="idCPFPEN" name="cpfPEN" value = '' placeholder='' maxlength="11"/></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idValorPENLabel"  for="idValorPEN"><strong>Valor relativo à dedução do rendimento tributável correspondente a pagamento de pensão alimentícia:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idValorPEN" name="valorPEN" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;"> <button type="button" id="idLancarPensao"><i class="fa-solid fa-share-from-square"></i>Lançar regsitro</button></td>
                                </tr>
                                <tr>
                                    <td  colspan="2">
                                        <div hidden='hidden'  id='idMensagemPensao' class="alert alert-success" role="alert" style="text-align: center;margin:20px;width:52vw;"></div>
                                        <div id="dataPensao-tabela" ></div>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </tr>
                <tr style="text-align:center">
                    <table id="idTabelaRetencao" class="form-container" style="display: none; margin:auto; padding: 10px; margin: 10px; background: #d3d3d3;" >
                            <caption style="text-align:center" ><strong>Informações de processos relacionados a não retenção de tributos ou a depósitos judiciais.</strong></caption>
                            <tbody>
                                <tr>
                                    <td align = "left"><label id="idTipoRetencaoLabel"  for="idTipoRetencao"><strong>Tipo de processo:</strong></label></td>
                                    <td align = "left">
                                        <select onchange = "" id="idTipoRetencao" name="tipoRetencao" style="width:170px;" >
                                            <option selected value="">Selecione...</option>
                                            <option value="1">1 - Administrativo</option>
                                            <option value="2">2 - Judicial</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idNumeroRetencaoLabel"  for="idNumeroRetencao" ><strong>Número do processo administrativo/judicial:</strong></label></td>
                                    <td align = "left"><input onchange = "" type="text" size="22" id="idNumeroRetencao" name="numeroRetencao" value = '' placeholder='' minlength="17" maxlength="21" required/></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idCodigoSuspensaoLabel"  for="idCodigoSuspensao"><strong>Código do indicativo da suspensão, atribuído pelo empregador em S-1070:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idCodigoSuspensao" name="codigoSuspensao" value = '' /></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;"> <button type="button" id="idLancarRetencao"><i class="fa-solid fa-share-from-square"></i>Lançar regsitro</button></td>
                                </tr>
                                <tr>
                                    <td  colspan="2">
                                        <div hidden='hidden'  id='idMensagemRetencao' class="alert alert-success" role="alert" style="text-align: center;margin:20px;width:52vw;"></div>
                                        <div id="dataRetencao-tabela" ></div>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </tr>
                <tr style="text-align:center">
                    <table id="idTabelaValorRetencao" class="form-container" style="display: none; margin:auto; padding: 10px; margin: 10px; background: #d3d3d3;" >
                            <caption style="text-align:center" ><strong>Informações de valores relacionados a não retenção de tributos ou a depósitos judiciais.</strong></caption>
                            <tbody>
                                 <tr>
                                    <td align = "left"><label id="idProcessoRetencaoLabel"  for="idProcessoRetencao"><strong>Processo relacionado a não retenção de tributos ou a depósitos judiciais:</strong></label></td>
                                    <td align = "left">
                                        <select onchange = "" id="idProcessoRetencao" name="processoRetencao" style="width:170px;" >
                                            <option selected value="">Selecione...</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idPeriodoApuracaoLabel"  for="idPeriodoApuracao"><strong>Indicativo de período de apuração:</strong></label></td>
                                    <td align = "left">
                                        <select onchange = "" id="idPeriodoApuracao" name="periodoApuracao" style="width:170px;" >
                                            <option selected value="">Selecione...</option>
                                            <option value="1">1 - Mensal</option>
                                            <option value="2">2 - Anual (13° salário)</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idValorRetencaoLabel"  for="idValorRetencao"><strong>Valor da retenção que deixou de ser efetuada em função de processo administrativo ou judicial:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idValorRetencao" name="valorRetencao" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idValorDepositoLabel"  for="idValorDeposito"><strong>Valor do depósito judicial em função de processo administrativo ou judicial:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idValorDeposito" name="valorDeposito" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idValorAnoCalendarioLabel"  for="idValorAnoCalendario"><strong>Valor da compensação relativa ao ano calendário em função de processo judicial:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idValorAnoCalendario" name="valorAnoCalendario" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idValorAnoAnteriorLabel"  for="idValorAnoAnterior"><strong>Valor da compensação relativa a anos anteriores em função de processo judicial:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idValorAnoAnterior" name="valorAnoAnterior" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idValorRendimentoSuspensoLabel"  for="idValorRendimentoSuspenso"><strong>Valor do rendimento com exigibilidade suspensa:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idValorRendimentoSuspenso" name="valorRendimentoSuspenso" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;"> <button type="button" id="idLancarValorRetencao"><i class="fa-solid fa-share-from-square"></i>Lançar regsitro</button></td>
                                </tr>
                                <tr>
                                    <td  colspan="2">
                                        <div hidden='hidden'  id='idMensagemValorRetencao' class="alert alert-success" role="alert" style="text-align: center;margin:20px;width:52vw;"></div>
                                        <div id="dataValorRetencao-tabela" ></div>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </tr>
                <tr style="text-align:center">
                    <table id="idTabelaDeducaoSuspensa" class="form-container" style="display: none; margin:auto; padding: 10px; margin: 10px; background: #d3d3d3;" >
                            <caption style="text-align:center" ><strong>Detalhamento das deduções com exigibilidade suspensa</strong></caption>
                            <tbody>
                                 <tr>
                                    <td align = "left"><label id="idProcessoDeducaoSuspensaLabel"  for="idProcessoDeducaoSuspensa"><strong>Processo relacionado ao detalhamento das deduções com exigibilidade suspensa:</strong></label></td>
                                    <td align = "left">
                                        <select onchange = "" id="idProcessoDeducaoSuspensa" name="processoDeducaoSuspensa" style="width:170px;" >
                                            <option selected value="">Selecione...</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idTipoDeducaoLabel"  for="idTipoDeducao"><strong>Indicativo de período de apuração:</strong></label></td>
                                    <td align = "left">
                                        <select onchange = "" id="idTipoDeducao" name="tipoDeducao" style="width:170px;" >
                                            <option selected value="">Selecione...</option>
                                            <option value="1">1 - Previdência oficial</option>
                                            <option value="5">5 - Pensão alimentícia</option>
                                            <option value="7">7 - Dependentes</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idValorDeducaoSuspensaLabel"  for="idValorDeducaoSuspensa"><strong>Valor da dedução da base de cálculo do imposto de renda com exigibilidade suspensa:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idValorDeducaoSuspensa" name="valorDeducaoSuspensa" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;"> <button type="button" id="idLancarDeducaoSuspensa"><i class="fa-solid fa-share-from-square"></i>Lançar regsitro</button></td>
                                </tr>
                                <tr>
                                    <td  colspan="2">
                                        <div hidden='hidden'  id='idMensagemDeducaoSuspensa' class="alert alert-success" role="alert" style="text-align: center;margin:20px;width:52vw;"></div>
                                        <div id="dataDeducaoSuspensa-tabela" ></div>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </tr>
                <tr style="text-align:center">
                    <table id="idTabelaSuspensaPensao" class="form-container" style="display: none; margin:auto; padding: 10px; margin: 10px; background: #d3d3d3;" >
                            <caption style="text-align:center" ><strong>Informação das deduções suspensas por dependentes e beneficiários da pensão alimentícia</strong></caption>
                            <tbody>
                                 <tr>
                                    <td align = "left"><label id="idProcessoSuspensaPensaoLabel"  for="idProcessoSuspensaPensao"><strong>Processo relacionado ao detalhamento das deduções com exigibilidade suspensa:</strong></label></td>
                                    <td align = "left">
                                        <select onchange = "" id="idProcessoSuspensaPensao" name="processoSuspensaPensao" style="width:170px;" >
                                            <option selected value="">Selecione...</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idCPFSuspensaPensaoLabel"  for="idCPFSuspensaPensao"><strong>Número de inscrição no CPF:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idCPFSuspensaPensao" name="CPFSuspensaPensao" value = '' maxlength="11" /></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idValorSuspensaPensaoLabel"  for="idValorSuspensaPensao"><strong>Valor da dedução relativa a dependentes ou a pensão alimentícia com exigibilidade suspensa:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idValorSuspensaPensao" name="valorSuspensaPensao" value = '0.00' placeholder='0.00' pattern="^\d*(\.\d{0,2})?$" onchange="this.value = this.value.replace(/,/g, '.')" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;"> <button type="button" id="idLancarSuspensaPensao"><i class="fa-solid fa-share-from-square"></i>Lançar regsitro</button></td>
                                </tr>
                                <tr>
                                    <td  colspan="2">
                                        <div hidden='hidden'  id='idMensagemSuspensaPensao' class="alert alert-success" role="alert" style="text-align: center;margin:20px;width:52vw;"></div>
                                        <div id="dataSuspensaPensao-tabela" ></div>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </tr>
                <tr style="text-align:center">
                    <table id="idTabelaIRComplementar" class="form-container" style="display: none; margin:auto; padding: 10px; margin: 10px; background: #d3d3d3;" >
                            <caption style="text-align:center" ><strong>Informações relacionadas à retenção na fonte, aos rendimentos tributáveis e não tributáveis, deduções e/ou isenções, etc., de acordo com a legislação aplicada ao imposto de renda</strong></caption>
                            <tbody>
                                <tr>
                                    <td align = "left"><label id="idDataLaudoLabel"  for="idDataLaudo"><strong>Data da moléstia grave atribuída pelo laudo:</strong></label></td>
                                    <td align = "left"><input type="date" size="22" id="idDataLaudo" name="dataLaudo" min="1900-01-01" /></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idCPFIRComplementarLabel"  for="idCPFIRComplementar" ><strong>Número CPF:</strong></label></td>
                                    <td align = "left"><input onchange = "" type="text" size="22" id="idCPFIRComplementar" name="CPFIRComplementar" value = '' maxlength="11"/></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idDataNascimentoLabel"  for="idDataNascimento"><strong>Data de nascimento:</strong></label></td>
                                    <td align = "left"><input type="date" size="22" id="idDataNascimento" name="dataNascimento" min="1890-01-01" /></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idNomeDependenteLabel"  for="idNomeDependente"><strong>Nome do dependente:</strong></label></td>
                                    <td align = "left"><input type="text" size="22" id="idNomeDependente" name="nomeDependente" minlength="2" maxlength="70"/></td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idDepIRRFLabel"  for="idDepIRRF"><strong>Somente informar este campo em caso de dependente do trabalhador para fins de dedução de seu rendimento tributável pelo Imposto de Renda:</strong></label></td>
                                    <td align = "left">
                                        <select onchange = "" id="idDepIRRF" name="depIRRF" style="width:170px;" >
                                            <option selected value="">Selecione...</option>
                                            <option value="S">S - Sim</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idTipoDependenteLabel"  for="idTipoDependente"><strong>Tipo de dependente:</strong></label></td>
                                    <td align = "left">
                                        <select onchange = "" id="idTipoDependente" name="tipoDependente" style="width:170px;" >
                                            <option selected value="">Selecione...</option>
                                            <option value="01">01 - Cônjuge </option>
                                            <option value="02">02 - Companheiro(a) com o(a) qual tenha filho ou viva há mais de 5 (cinco) anos ou possua declaração de união estável </option>
                                            <option value="03">03 - Filho(a) ou enteado(a) </option>
                                            <option value="04">04 - Filho(a) ou enteado(a), universitário(a) ou cursando escola técnica de 2º grau </option>
                                            <option value="06">06 - Irmão(ã), neto(a) ou bisneto(a) sem arrimo dos pais, do(a) qual detenha a guarda judicial </option>
                                            <option value="07">07 - Irmão(ã), neto(a) ou bisneto(a) sem arrimo dos pais, universitário(a) ou cursando escola técnica de 2° grau, do(a) qual detenha a guarda judicial </option>
                                            <option value="09">09 - Pais, avós e bisavós </option>
                                            <option value="10">10 - Menor pobre do qual detenha a guarda judicial </option>
                                            <option value="11">11 - A pessoa absolutamente incapaz, da qual seja tutor ou curador </option>
                                            <option value="12">12 - Ex-cônjuge </option>
                                            <option value="99">99 - Agregado/Outros </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "left"><label id="idDescricaoDependenciaLabel"  for="idDescricaoDependencia"><strong>Informar a descrição da dependência (Informação exclusiva se '99 - Agregado/Outros'):</strong></label></td>
                                    <td align = "left"><textarea id = "idDescricaoDependencia" name="descricaoDependencia" maxlength="100"></textarea> 
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;"> <button type="button" id="idLancarIRComplementar"><i class="fa-solid fa-share-from-square"></i>Lançar regsitro</button></td>
                                </tr>
                                <tr>
                                    <td  colspan="2">
                                        <div hidden='hidden'  id='idMensagemIRComplementar' class="alert alert-success" role="alert" style="text-align: center;margin:20px;width:52vw;"></div>
                                        <div id="dataIRComplementar-tabela" ></div>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </tr>
            </tbody>
        </table>
    </fieldset>
    <button type="button" id="idAnteriorAba">
            <i class="fas fa-chevron-left"></i>
            Anterior
    </button>
    <button type="button" id="idSalvarComplemento">
        <i class="fas fa-save"></i>
        Salvar
    </button>
</form>
</div>
<?php
?>

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
 <form name="formProcessoJudicial" class="row g-3 needs-validation" id="idFormProcessoJudicial">
    <div hidden='hidden'  id='idMensagem' class="alert alert-success" role="alert"
        style="text-align:center; width:50vw;"></div>
    <input type="hidden" name="sequencial" id="sequencial">
    <fieldset id="idFieldsetPrincipalProcesso">
        <legend>Informações Principais do Processo</legend>
        <table class='form-container'>
            <tbody>
                <tr>
                    <td align = "left"><label for="idOrigem"><strong>Origem:</strong></label></td>
                    <td align = "left">
                        <select id="idOrigem" name="origem" onchange="validaOrigemProcesso(this.value)">
                            <option selected value="">Selecione...</option>
                            <option value="1">1 - Processo judicial</option>
                            <option value="2">2 - Demanda submetida à CCP ou ao NINTER</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align = "left"><label for="idNumeroProcesso"><strong>Número do Processo:</strong></label></td>
                    <td align = "left"><input type="text" minlength="15" maxlength="20" size="22" id="idNumeroProcesso" name="numeroProcesso"></td>
                </tr>
                <tr>
                    <td align = "left"><label for="idObservacao"><strong>Observações:</strong></label></td>

                    <td align = "left">
                        <textarea id="idObservacao" name="observacao" rows="4" cols="50" maxlength="999"></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
    <fieldset id="idFieldsetComplemetarProcesso" style="padding:16px; width:50vw;">
        <legend>Informações Complementares do Processo ou da Demanda</legend>
        <table class='form-container' id="idTabelaComplemetarProcesso">
            <tbody>
                <tr>
                    <td align = "left"><label for="idDataSentenca"><strong>Data da Sentença:</strong></label></td>
                    <td align = "left"><input type="date" id="idDataSentenca" name="dataSentenca"></td>
                </tr>
                <tr>
                    <td align = "left"><label for="idUFVara"><strong>Sigla do Estado da Vara:</strong></label></td>
                    <td align = "left">
                        <select id="idUFVara" name="UFVara" >
                            <option selected value="">Selecione...</option>
                            <option value="AC">AC - Acre</option>
                            <option value="AL">AL - Alagoas</option>
                            <option value="AP">AP - Amapá</option>
                            <option value="AM">AM - Amazonas</option>
                            <option value="BA">BA - Bahia</option>
                            <option value="CE">CE - Ceará</option>
                            <option value="DF">DF - Distrito Federal</option>
                            <option value="ES">ES - Espírito Santo</option>
                            <option value="GO">GO - Goías</option>
                            <option value="MA">MA - Maranhão</option>
                            <option value="MT">MT - Mato Grosso</option>
                            <option value="MS">MS - Mato Grosso do Sul</option>
                            <option value="MG">MG - Minas Gerais</option>
                            <option value="PA">PA - Pará</option>
                            <option value="PB">PB - Paraíba</option>
                            <option value="PR">PR - Paraná</option>
                            <option value="PE">PE - Pernambuco</option>
                            <option value="PI">PI - Piauí</option>
                            <option value="RJ">RJ - Rio de Janeiro</option>
                            <option value="RN">RN - Rio Grande do Norte</option>
                            <option value="RS">RS - Rio Grande do Sul</option>
                            <option value="RO">RO - Rondônia</option>
                            <option value="RR">RR - Roraíma</option>
                            <option value="SC">SC - Santa Catarina</option>
                            <option value="SP">SP - São Paulo</option>
                            <option value="SE">SE - Sergipe</option>
                            <option value="TO">TO - Tocantins</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align = "left"><label for="idCodigoMunicipio"><strong>Cód. Município (IBGE):</strong></label></td>
                    <td align = "left"><input type="text"  required minlength="7" maxlength="7" size="9" id="idCodigoMunicipio" name="codigoMunicipio"/></td>
                </tr>
                <tr>
                    <td align = "left"><label for="idCodigoVara"><strong>Código Identificação da Vara</strong></label></td>
                    <td align = "left"><input type="text"  maxlength="4" size="6" id="idCodigoVara" name="codigoVara"/></td>
                </tr>
            </tbody>
        </table>
    </fieldset>
    <fieldset id="idFieldsetcppProcesso" style="padding:16px; width:50vw;">
        <legend>Informações Complementares da Demanda Submetida à CPP ou a NINTER</legend>
        <table class='form-container'>
            <tbody>
                <tr>
                    <td align = "left"><label for="idDataAcordo"><strong>Data da Celebração do Acordo:</strong></label></td>
                    <td align = "left"><input type="date" id="idDataAcordo" name="dataAcordo"></td>
                </tr>
                <tr>
                    <td align = "left"><label for="idTipoAcordo"><strong>Tipo do Âmbito de Celebração de Acordo:</strong></label></td>
                    <td align = "left">
                        <select id="idTipoAcordo" name="tipoAcordo" >
                            <option selected value="">Selecione...</option>
                            <option value="1">1 - CCP no âmbito de empresa</option>
                            <option value="2">2 - CCP no âmbito de sindicato</option>
                            <option value="3">3 - NINTER</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align = "left"><label for="idCnpjCCP"><strong>CNPJ do Sindicato Representativo</strong></label></td>
                    <td align = "left"><input type="text"  onchange="validaCNPJ(this.value);" minlength="14" maxlength="14" size="16" id="idCnpjCCP" name="cnpjCCP"/></td>
                </tr>
            </tbody>
        </table>
    </fieldset>

    <input type="button" id="idSalvarProcessoJudicial" value="Salvar">
    <input type="button" id="idLimparProcessoJudicial" value="Novo">
    <fieldset style="padding:16px; width:50vw;"> 
        <legend>Processos Lançados</legend> 
            <div id="gridProcesso"></div>
                <div id="containerProcesso">
                    <table class='form-container' id="dataProcesso-table">
                    </table>
                </div>
            </div>
    </fieldset>
</form>
</div>

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
<form name="formSindicato" class="container" id="formContribuicao">
    <input type="hidden" name="sequencialContribuicao" id="sequencialContribuicao"/>
    <fieldset>
        <legend>Informação da Contribuição</legend>
        <table class="form-container">
            <tr>
                <td>
                    <label for="periodo_selecionado">Período:</label>
                </td>
                <td>
                    <input type="text" name="periodo_selecionado" id="periodo_selecionado" class="readonly field-size2"
                           disabled/>
                </td>
            </tr>
            <tr>
                <td><label for="codigoSindicato"><a href="#" id="ancoraSindicato">Sindicato:</a></label></td>
                <td>
                    <input type="text" name="codigoSindicato" id="codigoSindicato" lang="rh116_sequencial"
                           class="field-size2"/>
                    <input type="text" name="descricaoSindicato" id="descricaoSindicato" lang="rh116_descricao"
                           class="readonly field-size8" disabled/>
                </td>
            </tr>
            <tr>
                <td><label for="tipoContribuicao">Tipo de Contribuição:</label></td>
                <td>
                    <select id='tipoContribuicao' name="tipoContribuicao">
                        <option value=''>Selecione o tipo de contribuição</option>
                        <option value='1'>Contribuição Sindical Compulsória</option>
                        <option value='2'>Contribuição Associativa</option>
                        <option value='3'>Contribuição Assistencial</option>
                        <option value='4'>Contribuição Confederativa</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="valor">Valor:</label></td>
                <td>
                    <input type="text" name="valor" id="valor">
                </td>
            </tr>
        </table>
    </fieldset>
    <button id="btnSalvarContribuicao" onclick="return false;">
        <label>Salvar</label>
        <i class="fa fa-save" aria-hidden="true"></i>
    </button>
</form>

<fieldset style="width: 800px;" class="container">
    <legend>Contribuições adicionadas no período</legend>
    <div id="gridContribuicao"></div>
</fieldset>

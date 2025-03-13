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
<form name="formPeriodo" class="container" id="formPeriodo">
    <input type="hidden" name="sequencialPeriodo" id="sequencialPeriodo">
    <fieldset>
        <legend>Indicativo de Período</legend>
        <table class="form-container">
            <tr id="tr_empregador" class="d-none">
                <td><label for="empregador">Empregador:</label></td>
                <td><select name="empregador" id="empregador"></select></td>
            </tr>
            <tr>
                <td><label>Indicativo de Período:</label></td>
                <td>
                    <select name="indicativoPeriodo" id="indicativoPeriodo">
                        <option value="1">Mensal (AAAA-MM)</option>
                        <option value="2">Anual (AAAA)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Período:</label></td>
                <td><input type="text" name="periodo" id="periodo" maxlength="7" class="field-size2"></td>
            </tr>
        </table>
    </fieldset>
    <button id="btnSalvarPeriodo" onclick="return false;">
        <label>Salvar</label>
        <i class="fa fa-save" aria-hidden="true"></i>
    </button>
    <button id="btnPesquisarPeriodo" onclick="return false;">
        <label>Pesquisar</label>
        <i class="fa fa-search" aria-hidden="true"></i>
    </button>
</form>

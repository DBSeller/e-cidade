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

//MODULO: Configuracoes
$clperiodo->rotulo->label();
?>
<form name="form1" method="post">
    <fieldset style="width:430px;">
        <legend>Cadastro de Períodos</legend>
        <table>
            <tr>
                <td>
                    <label for="o114_sequencial">
                        <strong>Código:</strong>
                    </label>
                </td>
                <td>
                    <?php
                    db_input('o114_sequencial', 10, $Io114_sequencial, true, 'text', 3, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o114_descricao">
                        <strong>Descrição:</strong>
                    </label>
                </td>
                <td>
                    <?php
                    db_input('o114_descricao', 20, $Io114_descricao, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o114_qdtporano">
                        <strong>Quantidade por Ano:</strong>
                    </label>
                </td>
                <td>
                    <?php
                    db_input('o114_qdtporano', 10, $Io114_qdtporano, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o114_diainicial">
                        <strong>Dia Inicial:</strong>
                    </label>
                </td>
                <td>
                    <?php
                    db_input('o114_diainicial', 10, $Io114_diainicial, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o114_mesinicial">
                        <strong>Mês Inicial:</strong>
                    </label>
                </td>
                <td>
                    <?php
                    db_input('o114_mesinicial', 10, $Io114_mesinicial, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o114_diafinal">
                        <strong>Dia Final:</strong>
                    </label>
                </td>
                <td>
                    <?php
                    db_input('o114_diafinal', 10, $Io114_diafinal, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o114_mesfinal">
                        <strong>Mês Final:</strong>
                    </label>
                </td>
                <td>
                    <?php
                    db_input('o114_mesfinal', 10, $Io114_mesfinal, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o114_sigla">
                        <strong>Sigla:</strong>
                    </label>
                </td>
                <td>
                    <?php
                    db_input('o114_sigla', 10, $Io114_sigla, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="o114_ordem">
                        <strong>Ordem:</strong>
                    </label>
                </td>
                <td>
                    <?php
                    db_input('o114_ordem', 10, $Io114_ordem, true, 'text', $db_opcao, "")
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>"
           type="submit" id="db_opcao"
           value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?> >
    <?php if ($db_opcao !== 1) {
                        ?>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
        <?php
                    } ?>
</form>
<script>
    function js_pesquisa() {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_periodo',
            'func_periodo.php?funcao_js=parent.js_preenchepesquisa|o114_sequencial', 'Pesquisa', true);
    }

    function js_preenchepesquisa(chave) {
        db_iframe_periodo.hide();
        <?php
        if ($db_opcao != 1) {
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
        }
        ?>
    }
</script>
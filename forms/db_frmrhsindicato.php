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

//MODULO: pessoal
$clrhsindicato->rotulo->label();

use ECidade\RecursosHumanos\Pessoal\Model\Sindicato;

$sindicato = isset($sindicato) && $sindicato instanceof Sindicato ? $sindicato : new Sindicato();

?>
<form name="form1" method="post">
    <fieldset style="width:400px;">
        <legend>Sindicato</legend>

        <table class="form-container">
            <tr style="display:none;">
                <td title="<?php echo $Trh116_sequencial; ?>">
                    <?php echo $Lrh116_sequencial; ?>
                </td>
                <td>
                    <?php db_input('rh116_sequencial', 10, $Irh116_sequencial, true, 'text', $db_opcao, "") ?>
                </td>
            </tr>

            <tr>
                <td title="<?php echo $Trh116_codigo; ?>">
                    <?php echo $Lrh116_codigo; ?>
                </td>
                <td>
                    <?php db_input('rh116_codigo', 40, $Irh116_codigo, true, 'text', $db_opcao, "") ?>
                </td>
            </tr>

            <tr>
                <td title="<?php echo $Trh116_cnpj; ?>"><?php echo $Lrh116_cnpj; ?></td>
                <td>
                    <?php db_input('rh116_cnpj', 40, $Irh116_cnpj, true, 'text', $db_opcao, "") ?>
                </td>
            </tr>

            <tr>
                <td title="Informe a razão social do sindicato">Razão Social:</td>
                <td>
                    <?php db_input('rh116_descricao', 40, $Irh116_descricao, true, 'text', $db_opcao, "") ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        <label for="mes_data_base"
                               title="Mês relativo à data base da categoria profissional do trabalhador">
                            Mês da Data Base:
                        </label>
                    </b>
                </td>
                <td>
                    <input type="text" name="mes_data_base" id="mes_data_base"
                           value="<?php echo $sindicato->getMesDataBase() ?: ''; ?>"
                        <?php echo $db_opcao === 3 ? 'disabled' : ''; ?>>
                </td>
            </tr>
        </table>
    </fieldset>

    <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>"
           type="submit" id="db_opcao"
           value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">

</form>
<script src="scripts/widgets/DBInputMes.js"></script>
<script>
    new DBInputMes(document.getElementById('mes_data_base'));
    new DBInputCNPJ($('rh116_cnpj'));

    function js_pesquisa() {
        js_OpenJanelaIframe(
            'CurrentWindow.corpo',
            'db_iframe_rhsindicato',
            'func_rhsindicato.php?funcao_js=parent.js_preenchepesquisa|rh116_sequencial',
            'Pesquisa',
            true
        );
    }

    function js_preenchepesquisa(chave) {
        db_iframe_rhsindicato.hide();
        <?php

        if ($db_opcao != 1) {
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
        }

        ?>
    }
</script>
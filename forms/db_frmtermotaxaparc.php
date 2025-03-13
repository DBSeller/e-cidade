<?php
/**
 * MODULO: arrecadacao
 */
include(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$oDaoTermotaxaparc->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("ar36_descricao");

if ($db_opcao == 1) {
    $sNameBotaoProcessar = "incluir";
} else if ($db_opcao == 2 || $db_opcao == 22) {
    $sNameBotaoProcessar = "alterar";
} else {
    $sNameBotaoProcessar = "excluir";
}
?>

<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
    <form name="form1" method="post"></form>
    <form name="form" method="post" action="">
        <input type="hidden" name="ar29_sequencial" value="<?php echo @$ar29_sequencial ?>">
        <fieldset >
            <legend><?php echo ucfirst($sNameBotaoProcessar); ?> vínculo de parcela com taxas e custas</legend>
            <table >
                <tr>
                    <td nowrap title="<?php echo $Tar29_numpar; ?>" >
                        <label class="bold" for="ar29_numpar" id="lbl_ar29_numpar"><?php echo $Sar29_numpar; ?>:</label>
                    </td>
                    <td>
                        <?php
                        db_input('ar29_numpar', 4, $Iar29_numpar, true, 'text', $db_opcao,"");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?php echo $Tar29_taxa; ?>" >
                        <label class="bold" for="ar29_taxa" id="lbl_ar29_taxa">
                            <?php
                            db_ancora( $Sar29_taxa . ':',
                                "js_pesquisaar29_taxa(true);", $db_opcao);
                            ?>
                        </label>
                    </td>
                    <td>
                        <?php
                        db_input('ar29_taxa', 4, $Iar29_taxa, true, 'text', $db_opcao," onchange='js_pesquisaar29_taxa(false);'");
                        ?>
                        <?php
                        db_input('ar36_descricao', 40, $Iar36_descricao, true, 'text', 3, '');
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> >
        <?php if (in_array($sNameBotaoProcessar, array('alterar', 'excluir'))) { ?>
          <input name="novo" id="cancelar" value="<?=$sNameBotaoProcessar == 'excluir' ? 'Cancelar' : 'Novo' ?>" onclick="js_cancelar();" type="button">
        <?php } ?>
        <table>
            <tr>
                <td valign="top"  align="center">
                    <?php
                    $chavepri = array("ar29_sequencial" => @$ar29_sequencial);
                    $cliframe_alterar_excluir->chavepri = $chavepri;
                    $cliframe_alterar_excluir->sql = $oDaoTermotaxaparc->sql_query(null, "*", "ar29_numpar");
                    $cliframe_alterar_excluir->campos = "ar29_numpar,ar36_descricao";
                    $cliframe_alterar_excluir->legenda = "Custas vinculadas";
                    $cliframe_alterar_excluir->iframe_height = "160";
                    $cliframe_alterar_excluir->iframe_width = "600";
                    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
                    ?>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php db_menu(); ?>
</body>
<script>

    function js_cancelar() {
        location.href = "arr1_termotaxaparc001.php";
    }

    function js_pesquisaar29_taxa(lExibeJanela) {

        if (lExibeJanela) {
            js_OpenJanelaIframe( 'CurrentWindow.corpo',
                'db_iframe_taxa',
                'func_taxa.php?funcao_js=parent.js_mostrataxa1|ar36_sequencial|ar36_descricao',
                'Pesquisa', true);
        } else {
            if (document.form.ar29_taxa.value != '') {
                js_OpenJanelaIframe( 'CurrentWindow.corpo',
                    'db_iframe_taxa',
                    'func_taxa.php?pesquisa_chave=' + document.form.ar29_taxa.value + '&funcao_js=parent.js_mostrataxa',
                    'Pesquisa', false);
            } else {
                document.form.ar36_descricao.value = '';
            }
        }
    }

    function js_mostrataxa(sChave, lErro) {

        document.form.ar36_descricao.value = sChave;
        if (lErro) {

            document.form.ar29_taxa.focus();
            document.form.ar29_taxa.value = '';
        }
    }

    function js_mostrataxa1(sChave, sDescricao) {

        document.form.ar29_taxa.value = sChave;
        document.form.ar36_descricao.value = sDescricao;
        db_iframe_taxa.hide();
    }

    function js_pesquisa() {
        js_OpenJanelaIframe( 'CurrentWindow.corpo',
            'db_iframe_termotaxaparc',
            'func_termotaxaparc.php?funcao_js=parent.js_preenchepesquisa|ar29_sequencial',
            'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

        db_iframe_termotaxaparc.hide();
        <?php
        if ($db_opcao != 1) {
            echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
        ?>
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
</script>
</html>

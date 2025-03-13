<?php
/**
 * MODULO: contabilidade
 */
$oDaoLinhaspacto->rotulo->label();

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

    <style>
        .form1 * {
            max-width: 500px !important;
        }
    </style>
</head>
<body class="body-default">
<div class="container">
    <div style="max-width: 500px">
        <form class="form1" name="form1" method="post" action="">
            <fieldset>
                <legend><?php echo ucfirst($sNameBotaoProcessar); ?> Linhas de pacto</legend>
                <table>
                    <?php if ($db_opcao != 1): ?>
                        <tr>
                            <td nowrap title="<?php echo $Tc07_sequencial; ?>">
                                <label class="bold" for="c07_sequencial"
                                       id="lbl_c07_sequencial"><?php echo $Sc07_sequencial; ?>:</label>
                            </td>
                            <td>
                                <?php
                                db_input('c07_sequencial', 10, $Ic07_sequencial, true, 'text', ($db_opcao == 2 ? 3 : $db_opcao), "");
                                ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td nowrap title="<?php echo $Tc07_titulo; ?>">
                            <label class="bold" for="c07_titulo" id="lbl_c07_titulo"><?php echo $Sc07_titulo; ?>
                                :</label>
                        </td>
                        <td>
                            <?php db_input('c07_titulo', 255, $Ic07_titulo, true, 'text', $db_opcao, ""); ?>
                        </td>
                    </tr>
                    <tr>
                        <td nowrap title="<?php echo $Tc07_valor; ?>">
                            <label class="bold" for="c07_valor" id="lbl_c07_valor"><?php echo $Sc07_valor; ?>:</label>
                        </td>
                        <td>
                            <?php db_input('c07_valor', 15, $Ic07_valor, true, 'text', $db_opcao, ""); ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao"
                   value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo(!$db_botao ? "disabled" : ""); ?> >
            <?php
            if ($db_botao != 1) {
            ?>
            <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
            <?php
            }
            ?>
        </form>
    </div>
</div>
<?php db_menu(db_getsession("DB_id_usuario"),
    db_getsession("DB_modulo"),
    db_getsession("DB_anousu"),
    db_getsession("DB_instit")); ?>
</body>
<script>

    function js_pesquisa() {
        js_OpenJanelaIframe('CurrentWindow.corpo',
            'db_iframe_linhaspacto',
            'func_linhaspacto.php?funcao_js=parent.js_preenchepesquisa|c07_sequencial',
            'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

        db_iframe_linhaspacto.hide();
        <?php
        if ($db_opcao != 1) {
            echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
        ?>
    }

    <?php echo(isset($sPosScripts) ? $sPosScripts : ""); ?>
</script>
</html>

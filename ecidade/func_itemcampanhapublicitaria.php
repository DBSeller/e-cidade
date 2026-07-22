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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_congrupo_classe.php');

db_postmemory($_GET);
db_postmemory($_POST);

$clpcmater    = new cl_pcmater;

$clpcmater->rotulo->label("pc01_codmater");
$clpcmater->rotulo->label("pc01_descrmater");
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="estilos.css">
    <script src="scripts/scripts.js"></script>
</head>
<body>
<form name="form2" method="post" class="container">
    <fieldset>
        <legend>Buscar campanha publicitária: </legend>
        <table class="form-container">
            <tr>
                <td nowrap title="<?=$Tpc01_codmater?>"><?=$Lpc01_codmater?></td>
                <td nowrap><?php  db_input("pc01_codmater", 6, $Ipc01_codmater, true, "text", 4, "", "chave_pc01_codmater"); ?> </td>
            </tr>
            <tr>
                <td nowrap title="<?=$Tpc01_descrmater?>"> <?=$Lpc01_descrmater?></td>
                <td nowrap><?php db_input("pc01_descrmater", 80, $Ipc01_descrmater, true, "text", 4, "", "chave_pc01_descrmater"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_itemcampanhapublicitaria.hide();">
</form>
<div class="container">
    <?php

    $ano = db_getsession("DB_anousu");
    $where_ativo = [
        "pcmater.pc01_codsubgrupo = 10",
        "orcelemento.o56_elemento = '3339039580000'",
        "orcelemento.o56_anousu = {$ano}"
    ];

    if (!isset($pesquisa_chave)) {
        if (empty($campos)) {
            if (file_exists("funcoes/db_func_pcmater.php") == true) {
                include(modification("funcoes/db_func_pcmater.php"));
            } else {
                $campos = "pcmater.*";
            }
        }

        $campos = "pcmater.pc01_codmater, pcmater.pc01_descrmater, pcmater.pc01_servico,
        pcmater.pc01_complmater, pcmater.pc01_codsubgrupo
        ";

        if (isset($chave_pc01_codmater) && (trim($chave_pc01_codmater)!="")) {
            $where_ativo[] = "pc01_codmater=$chave_pc01_codmater";
        } elseif (isset($chave_pc01_descrmater) && (trim($chave_pc01_descrmater)!="")) {
            $where_ativo[] = "pc01_descrmater like '$chave_pc01_descrmater%'";
        }

        $sql = $clpcmater->sql_query_grupo(
            "",
            $campos,
            "pc01_descrmater",
            implode(" and ", $where_ativo)
        );

        if (isset($enviadescr)) {
            $clpcmater->sql_record($sql);
            if ($clpcmater->numrows>0) {
                db_lovrot($sql, 15, "()", "", $funcao_js);
            } else {
                $zero = true;
            }
        } else {
            db_lovrot($sql, 15, "()", "", $funcao_js);
        }
    } else {
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {
            $where_ativo[] = "pc01_codmater=$pesquisa_chave";
            $result = $clpcmater->sql_record($clpcmater->sql_query_grupo(
                null,
                "pc01_descrmater, pc01_servico",
                "",
                implode(" and ", $where_ativo)
            ));
            if ($clpcmater->numrows!=0) {
                db_fieldsmemory($result, 0);
                echo "<script>".$funcao_js."('$pc01_descrmater', false, '$pc01_servico');</script>";
            } else {
                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true, false);</script>";
            }
        } else {
            echo "<script>".$funcao_js."('', false, false);</script>";
        }
    }
    ?>
</div>
</body>
</html>

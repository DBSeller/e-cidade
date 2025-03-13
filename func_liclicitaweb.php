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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_liclicitaweb_classe.php"));
require_once(modification("classes/db_liclicita_classe.php"));
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$clliclicitaweb = new cl_liclicitaweb;
$clliclicita = new cl_liclicita;
$clliclicitaweb->rotulo->label("l29_sequencial");
$clliclicitaweb->rotulo->label("l29_datapublic");
$clliclicita->rotulo->label("l20_edital");
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<div class="container">
    <table>
        <tr>
            <td height="63" align="center" valign="top">
                <table width="35%" border="0" align="center" cellspacing="0">
                    <form name="form2" method="post" action="">
                        <tr>
                            <td width="4%" align="right" nowrap title="<?= $Tl29_sequencial ?>">
                                <?= $Ll29_sequencial ?>
                            </td>
                            <td width="96%" align="left" nowrap>
                                <?php
                                db_input(
                                    "l29_sequencial",
                                    10,
                                    $Il29_sequencial,
                                    true,
                                    "text",
                                    4,
                                    "",
                                    "chave_l29_sequencial"
                                );
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td width="4%" align="right" nowrap title="<?= $Tl20_edital ?>">
                                <?= $Ll20_edital ?>
                            </td>
                            <td width="96%" align="left" nowrap>
                                <?php
                                db_input("l20_edital",
                                    10,
                                    $Il20_edital,
                                    true,
                                    "text",
                                    4,
                                    "",
                                    "chave_l20_edital"
                                );
                                ?>
                            </td>
                        </tr>


                        <tr>
                            <td width="4%" align="right" nowrap title="<?= $Tl29_datapublic ?>">
                                <?= $Ll29_datapublic ?>
                            </td>
                            <td width="96%" align="left" nowrap>
                                <?php
                                db_inputdata(
                                    'chave_l29_datapublic',
                                    @$l29_datapublic_dia,
                                    @$l29_datapublic_mes,
                                    @$l29_datapublic_ano,
                                    true,
                                    'text',
                                    $db_opcao,
                                    ""
                                )
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" align="center">
                                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                                <input name="limpar" type="reset" id="limpar" value="Limpar">
                                <input
                                    name="Fechar"
                                    type="button"
                                    id="fechar"
                                    value="Fechar"
                                    onClick="parent.db_iframe_liclicitaweb.hide();"
                                >
                            </td>
                        </tr>
                    </form>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                if (!isset($pesquisa_chave)) {
                    if (!isset($campos)) {
                        if (file_exists("funcoes/db_func_liclicitaweb.php")) {
                            require_once(modification("funcoes/db_func_liclicitaweb.php"));
                            $liberaEditalComCadastro = "
                                CASE
                                    WHEN liclicitaweb.l29_liberaedital = 1 THEN 'Sem Cadastro'
                                    WHEN liclicitaweb.l29_liberaedital = 2 THEN 'Com Cadastro'
                                END AS l29_liberaedital
                            ";

                            $campos = str_replace(
                                'liclicitaweb.l29_liberaedital',
                                $liberaEditalComCadastro,
                                $campos
                            );
                        } else {
                            $campos = "liclicitaweb.*";
                        }
                    }

                    if (isset($chave_l29_sequencial) && (trim($chave_l29_sequencial) != "")) {
                        $sql = $clliclicitaweb->sql_query($chave_l29_sequencial, $campos, "l29_sequencial");
                    } elseif (isset($chave_l29_datapublic) && (trim($chave_l29_datapublic) != "")) {
                        $sql = $clliclicitaweb->sql_query(
                            "",
                            $campos,
                            "l29_datapublic",
                            " l29_datapublic = '$chave_l29_datapublic' "
                        );
                    } elseif (isset($chave_l20_edital) && (trim($chave_l20_edital) != "")) {
                        $sql = $clliclicitaweb->sql_query(
                            $chave_l29_sequencial,
                            $campos,
                            "l29_sequencial",
                            "l20_edital=$chave_l20_edital"
                        );
                    } else {
                        $sql = $clliclicitaweb->sql_query("", $campos, "l29_sequencial", "");
                    }

                    $repassa = [];
                    if (isset($chave_l29_datapublic)) {
                        $repassa = [
                            "chave_l29_sequencial" => $chave_l29_sequencial,
                            "chave_l29_datapublic" => $chave_l29_datapublic
                        ];
                    }

                    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
                } else {
                    if ($pesquisa_chave != null && $pesquisa_chave != "") {
                        $result = $clliclicitaweb->sql_record($clliclicitaweb->sql_query($pesquisa_chave));
                        if ($clliclicitaweb->numrows != 0) {
                            db_fieldsmemory($result, 0);
                            echo "<script>" . $funcao_js . "('$l29_datapublic',false);</script>";
                        } else {
                            echo "<script>" .
                                $funcao_js .
                                "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
                        }
                    } else {
                        echo "<script>" . $funcao_js . "('',false);</script>";
                    }
                }
                ?>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
<?php
if (!isset($pesquisa_chave)) {
    ?>
    <script>
    </script>
    <?php
}
?>
<script>
    js_tabulacaoforms("form2", "chave_l29_datapublic", true, 1, "chave_l29_datapublic", true);
</script>
<script type="text/javascript">
    (function () {
        var query = frameElement.getAttribute('name').replace('IF', ''),
            input = document.querySelector('input[value="Fechar"]');
        input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
    })();
</script>

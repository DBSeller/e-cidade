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
require_once(modification("classes/db_solicita_classe.php"));

db_postmemory($_POST);
db_postmemory($_GET);
parse_str($_SERVER["QUERY_STRING"]);

$clsolicita = new cl_solicita;
$clsolicita->rotulo->label("pc10_numero");
$clsolicita->rotulo->label("pc10_data");
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
</head>

<body background-color=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <div class="container">
        <table width="35%" align="center" cellspacing="0">
            <form name="form2" method="post" action="">
                <tr>
                    <td width="4%" align="right" nowrap title="<?= $Tpc10_numero ?>">
                        <?= $Lpc10_numero ?>
                    </td>
                    <td width="96%" align="left" nowrap>
                        <?php
                        db_input("pc10_numero", 10, $Ipc10_numero, true, "text", 4, "", "chave_pc10_numero");
                        ?>
                    </td>
                </tr>

                <tr>
                    <td width="4%" align="right" nowrap title="<?= $Tpc10_data ?>">
                        <?= $Lpc10_data ?>
                    </td>
                    <td width="96%" align="left" nowrap>
                        <?php
                        db_inputdata("pc10_data", null, null, null, true, "text", 4, "", "chave_pc10_data");
                        db_input("param", 10, "", false, "hidden", 3);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" align="center">
                        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                        <input name="limpar" type="reset" id="limpar" value="Limpar">
                        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_solicita.hide();">
                    </td>
                </tr>
            </form>
        </table>

        <?php
        $dbInstit = db_getsession("DB_instit");

        $condicoes = [];
        $condicoes[] = "pc10_instit = {$dbInstit}";
        $condicoes[] = 'pc13_codigo IS NOT NULL';

        $sqlValidaDepartamento = "SELECT pc30_validadept FROM pcparam WHERE pc30_instit = {$dbInstit};";
        $resultado = db_query($sqlValidaDepartamento);
        $resultadoCampos = db_utils::fieldsMemory($resultado, 0);

        if ($resultadoCampos->pc30_validadept == 't') {
            $condicoes[] = 'pc10_depto = ' . db_getsession("DB_coddepto");
        }

        if (!empty($departamento)) {
            $condicoes[] = "pc10_depto = {$departamento}";
        }

        if (!empty($gerautori)) {
            $condicoes[] = "pc10_correto = 't'";
        }

        if (!empty($proc) && $proc == true) {
            $condicoes = 'pc81_codproc IS NOT NULL';
        }

        if (isset($nada)) {
            $condicoes = [];
        }

        if (empty($anuladas)) {
            $condicoes[] = "NOT EXISTS(SELECT 1 FROM solicitaanulada WHERE pc67_solicita = pc10_numero)";
        }

        if (empty($pesquisa_chave)) {
            $campos = 'solicita.*';

            if (file_exists("funcoes/db_func_solicita.php")) {
                require_once(modification("funcoes/db_func_solicita.php"));
            }

            $campos = "DISTINCT {$campos}";

            if (!empty($chave_pc10_numero)) {
                $condicoes[] = "pc10_numero = {$chave_pc10_numero}";
            }

            if (!empty($chave_pc10_data)) {
                $dbDate = DBDate::create($chave_pc10_data);
                $condicoes[] = "pc10_data = '{$dbDate->getDate()}'";
            }

            $condicoesSql = implode(' AND ', $condicoes);
            $sql = $clsolicita->sql_query_licitacao_dotacao(
                null,
                $campos,
                "pc10_numero desc ",
                $condicoesSql
            );

            db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", array(), false);
        }

        if (!empty($pesquisa_chave)) {
            $condicoes[] = "pc10_numero = {$pesquisa_chave}";
            $condicoesSql = implode(' AND ', $condicoes);

            $result = $clsolicita->sql_record(
                $clsolicita->sql_query_licitacao_dotacao(
                    null,
                    "distinct *",
                    "",
                    $condicoesSql
                )
            );

            if (empty($clsolicita->numrows)) {
                echo "<script> {$funcao_js} ('Chave({$pesquisa_chave}) não Encontrado',true);</script>";
            }

            if (!empty($clsolicita->numrows)) {
                echo "<script>" . $funcao_js . "('{$pc10_data}',false);</script>";
            }
        } elseif (isset($pesquisa_chave)) {
            echo "<script>" . $funcao_js . "('',false);</script>";
        }
        ?>
    </div>
</body>

</html>

<script type="text/javascript">
    (function() {
        var query = frameElement.getAttribute('name').replace('IF', ''),
            input = document.querySelector('input[value="Fechar"]');
        input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
    })();
</script>
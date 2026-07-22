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

 use stdClass;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($_SERVER["QUERY_STRING"]);

$cl_retencaoreceitas = new cl_retencaoreceitas();
$rotulo = new rotulocampo();
$rotulo->label("z01_numcgm");
$rotulo->label("z01_nome");
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
<div class="container">
  <form name="form2" method="post" action="">
    <fieldset>
      <legend>Filtros</legend>
      <table class="form-container">
        <tr>
            <td title="<?php echo $Tz01_numcgm; ?>">
              <label for="z01_numcgm"> <?= $Lz01_numcgm; ?> </label>
            </td>
            <td>
                <?php
                db_input("z01_numcgm", 10, $Iz01_numcgm, true, "text", 4, "", "chave_z01_numcgm");
                ?>
            </td>
        </tr>
        <tr>
          <td>
            <label for="nome"> <?= $Lz01_nome; ?> </label>
          </td>
          <td>
              <?php
                db_input("z01_nome", 50, $Iz01_nome, true, "text", 4, "", "chave_z01_nome");
                ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cgmesocial.hide();">
  </form>
</div>

<div class="container">
  <table>
    <tr>
      <td align="center" valign="top">
          <?php
            define('APURACAO_TOTAL_RENDIMENTOS', 1);

            $paramsRetencoes = new stdClass();
            $paramsRetencoes->campos = 'DISTINCT cgm.z01_numcgm, cgm.z01_nome';
            $paramsRetencoes->where_retencoes .= "corrente.k12_data between '{$data_inicial}' and '{$data_final}' ";
            $paramsRetencoes->where_retencoes .= " and e23_recolhido is true ";
            $paramsRetencoes->where_retencoes .= " and corrente.k12_estorn is false and e21_retencaotipocalc in (1,2) ";
            $paramsRetencoes->campo_ordernar_retencoes = "cgm.z01_nome";

            $paramsTodosRendimentos = new stdClass();
            $paramsTodosRendimentos->campos = "DISTINCT cgm.z01_numcgm, cgm.z01_nome";
            $paramsTodosRendimentos->where_todos_rendimentos = "coremp.k12_data between '{$data_inicial}' and '{$data_final}' ";
            $paramsTodosRendimentos->campo_ordenar_rendimentos = "cgm.z01_nome ";

            if (!isset($pesquisa_chave)) {
                if (!empty($chave_z01_numcgm)) {
                    if ($tipo_apuracao == APURACAO_TOTAL_RENDIMENTOS) {
                        $paramsTodosRendimentos->where_todos_rendimentos .= "and cgm.z01_numcgm = {$chave_z01_numcgm} ";
                    } else {
                        $paramsRetencoes->where_retencoes .= "and cgm.z01_numcgm = {$chave_z01_numcgm} ";
                    }
                }

                if (!empty($chave_z01_nome)) {
                    if ($tipo_apuracao == APURACAO_TOTAL_RENDIMENTOS) {
                        $paramsTodosRendimentos->where_todos_rendimentos .= "and cgm.z01_nome ilike '{$chave_z01_nome}%' ";
                    } else {
                        $paramsRetencoes->where_retencoes .= "and cgm.z01_nome ilike '{$chave_z01_nome}%' ";
                    }
                }

                $sql = $cl_retencaoreceitas->sql_query_rendimentos_retencoes($paramsRetencoes, $paramsTodosRendimentos, $tipo_apuracao);
              
                $repassa = array();
                if (isset($chave_z01_numcgm) && isset($chave_z01_nome)) {
                    $repassa = array(
                    "chave_z01_numcgm" => $chave_z01_numcgm,
                    "chave_z01_nome" => $chave_z01_nome
                    );
                }
                echo '<div class="container" style="min-width: 750px;">';
                echo '  <fieldset>';
                echo '    <legend>Resultado da Pesquisa</legend>';
                db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
                echo '  </fieldset>';
                echo '</div>';
            } else {
                if ($pesquisa_chave!=null && $pesquisa_chave!="") {
                    if ($tipo_apuracao == APURACAO_TOTAL_RENDIMENTOS) {
                        $paramsTodosRendimentos->where_todos_rendimentos .= "and cgm.z01_numcgm = {$pesquisa_chave} ";
                    } else {
                        $paramsRetencoes->where_retencoes .= "and cgm.z01_numcgm = {$pesquisa_chave} ";
                    }
                    $sql = $cl_retencaoreceitas->sql_query_rendimentos_retencoes($paramsRetencoes, $paramsTodosRendimentos, $tipo_apuracao);
                    $result = $cl_retencaoreceitas->sql_record($sql);
                    if ($cl_retencaoreceitas->numrows!=0) {
                        db_fieldsmemory($result, 0);
                        echo "<script>".$funcao_js."('$z01_nome', false);</script>";
                    } else {
                        echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrado');</script>";
                    }
                } else {
                    echo "<script>".$funcao_js."(false, '');</script>";
                }
            }
            ?>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
<script type="text/javascript">
  (function() {
    var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  })();
</script>

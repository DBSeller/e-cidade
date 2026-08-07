<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$daoNumeroControleInterno = new cl_numerocontroleinternorequisicao();
$daoNumeroControleInterno->rotulo->label();
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body onload="document.getElementById('la65_numero').focus()">
<div class="container">
  <form name="form2" method="post">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table class="form-container">
        <tr>
          <td>
            <label for="la65_numero">Número Controle Interno:</label>
          </td>
          <td>
              <?php
              db_input("la65_numero", 10, $Ila65_numero, true, "text", 4);
              ?>
          </td>
        </tr>

        <tr>
          <td>
            <label for="la65_ano">Ano:</label>
          </td>
          <td>
              <?php
              db_input("la65_ano", 10, $Ila65_ano, true, "text", 4);
              ?>
          </td>
        </tr>

        <tr>
          <td>
            <label for="la65_requisicao">Requisição:</label>
          </td>
          <td>
              <?php
              db_input("la65_requisicao", 10, $Ila65_requisicao, true, "text", 4);
              ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar">
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.frameNumeroControleInterno.hide();">
  </form>
</div>
<?php
$campos = '*';
$query = 'sql_query_file';
$order = 'la65_numero';
$where = array();

if (isset($abreLookup)) {
    if (!empty($la65_numero)) {
        $where[] = "la65_numero = {$la65_numero}";
    }

    if (!empty($la65_ano)) {
        $where[] = "la65_ano = {$la65_ano}";
    }

    if (!empty($la65_requisicao)) {
        $where[] = "la65_requisicao = {$la65_requisicao}";
    }

    $sql = $daoNumeroControleInterno->{$query}(null, $campos, $order, implode(' AND ', $where));
    $repassa = array();

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
    echo '  </fieldset>';
    echo '</div>';
} else {
    if (!empty($numeroControleInterno)) {
        $where[] = "la65_numero = {$numeroControleInterno}";
    }

    if (!empty($ano)) {
        $where[] = "la65_ano = {$ano}";
    }

    $sql = $daoNumeroControleInterno->{$query}(null, $campos, $order, implode(' AND ', $where));
    $rs = db_query($sql);

    if ($rs && pg_num_rows($rs) > 0) {

        db_fieldsmemory($rs, 0);
        echo "<script>" . $funcao_js . "(false, {$la65_numero}, {$la65_ano}, {$la65_requisicao});</script>";
    } else {
        echo "<script>" . $funcao_js . "(true, 'Chave({$la65_numero}/{$la65_ano}) não Encontrada');</script>";
    }
}
?>
</body>
</html>

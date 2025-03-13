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
require_once(modification("classes/db_concarpeculiar_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"], $queryString);

$clconcarpeculiar = new cl_concarpeculiar;
$clconcarpeculiar->rotulo->label("c58_sequencial");
$clconcarpeculiar->rotulo->label("c58_descr");
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <table width="35%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="">
          <tr>
            <td width="4%" align="right" nowrap title="<?= $Tc58_sequencial ?>">
                <?php echo $Lc58_sequencial ?>
            </td>
            <td width="96%" align="left" nowrap>
                <?php
                db_input("c58_sequencial", 10, $Ic58_sequencial, true, "text", 4, "", "chave_c58_sequencial");
                ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?= $Tc58_descr ?>">
                <?php echo $Lc58_descr ?>
            </td>
            <td width="96%" align="left" nowrap>
                <?php
                db_input("c58_descr", 50, $Ic58_descr, true, "text", 4, "", "chave_c58_descr");
                ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="c58_tipo"><b>Classificação:</b></label>
            </td>
            <td>
                <?php
                $listaDeClassificacoes = array(
                  0 => "Todas",
                  1 => "Despesa",
                  2 => "Receita",
                  4 => "Operações de Crédito",
                  5 => "Convênios",
                  3 => "Outros",
                );

                db_select("c58_tipo", $listaDeClassificacoes, true, 1, "style='width:100%'");

                ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar">
              <input name="Fechar" type="button" id="fechar" value="Fechar"
                     onClick="parent.db_iframe_concarpeculiar.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
        <?php
        $dbwhere2 = "";
        $dbwhere = "";

        if (!empty($c58_tipo)) {
            $dbwhere .= " and c58_tipo = {$c58_tipo} ";
            $dbwhere2 .= " c58_tipo = {$c58_tipo} ";

        }

        if (!isset($pesquisa_chave)) {
            if (isset($campos) == false) {
                if (file_exists("funcoes/db_func_concarpeculiar.php") == true) {
                    include(modification("funcoes/db_func_concarpeculiar.php"));
                } else {
                    $campos = "concarpeculiar.*";
                }
            }

            if (isset($chave_c58_sequencial) && (trim($chave_c58_sequencial) != "")) {
                $sql = $clconcarpeculiar->sql_query(null, $campos, "c58_sequencial",
                  "c58_sequencial = '$chave_c58_sequencial' $dbwhere");
            } else {
                if (isset($chave_c58_descr) && (trim($chave_c58_descr) != "")) {
                    $sql = $clconcarpeculiar->sql_query("", $campos, "c58_descr",
                      " c58_descr like '$chave_c58_descr%' $dbwhere");
                } else {
                    $sql = $clconcarpeculiar->sql_query("", $campos, "c58_sequencial", "$dbwhere2");
                }
            }

            $repassa = array();

            if (isset($chave_c58_sequencial)) {
                $repassa = array(
                  "chave_c58_sequencial" => $chave_c58_sequencial,
                  "chave_c58_descr"      => $chave_c58_descr
                );
            }
            db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
        } else {
            if ($pesquisa_chave != null && $pesquisa_chave != "") {
                $result = $clconcarpeculiar->sql_record($clconcarpeculiar->sql_query(null, "*", null,
                  "c58_sequencial ilike '$pesquisa_chave' $dbwhere"));
                if ($clconcarpeculiar->numrows != 0) {
                    db_fieldsmemory($result, 0);
                    echo "<script>" . $funcao_js . "('$c58_descr',false);</script>";
                } else {
                    echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
                }
            } else {
                echo "<script>" . $funcao_js . "('',false);</script>";
            }
        }
        ?>
    </td>
  </tr>
</table>
</body>
</html>
<script>
  js_tabulacaoforms("form2", "chave_c58_sequencial", true, 1, "chave_c58_sequencial", true);
</script>
<script type="text/javascript">
  (function() {
    var query = frameElement.getAttribute('name').replace('IF', ''),
      input = document.querySelector('input[value="Fechar"]');
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  })();
</script>

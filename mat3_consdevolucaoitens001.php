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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_atendrequiitem_classe.php"));
include(modification("classes/db_matestoquedevitem_classe.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clatendrequiitem = new cl_atendrequiitem;
$clmatestoquedevitem = new cl_matestoquedevitem;
$clrotulo = new rotulocampo;
$clrotulo->label("");
?>
<html lang="pt-BR">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        .bordas{
            border: 2px solid #cccccc;
            border-top-color: #999999;
            border-right-color: #999999;
            border-left-color: #999999;
            border-bottom-color: #999999;
            background-color: #999999;
        }
        .bordas_corp{
            border: 1px solid #cccccc;
            border-top-color: #999999;
            border-right-color: #999999;
            border-left-color: #999999;
            border-bottom-color: #999999;
            background-color: #cccccc;
        }
    </style>
</head>
<body class="body-container">
<div class="container">
    <table border="0">
        <?php
        if (isset($codigo) && $codigo != "") {
            $sql = $clmatestoquedevitem->sql_query(null, "*", null, "m46_codmatestoquedev=$codigo");
            $result = $clmatestoquedevitem->sql_record($sql);
            $numrows = $clmatestoquedevitem->numrows;
            if ($numrows > 0) {
                echo "<tr class='bordas'>
	      <td class='bordas' align='center'><b><small>Cod. Material</small></b></td>
	      <td class='bordas' align='center'><b><small>Descrição</small></b></td>
	      <td class='bordas' align='center'><b><small>Quant. Devolvida</small></b></td>
          </tr>";
            } else {
                echo "<b>Nenhum registro encontrado...</b>";
            }

            for ($i = 0; $i < $numrows; $i++) {
                db_fieldsmemory($result, $i);
                echo "
           <tr>
	     <td class='bordas_corp' align='center'><small>$m46_codmatmater </small></td>
	     <td class='bordas_corp' align='center'><small>$m60_descr</small></td>
	     <td class='bordas_corp' align='center'><small>$m46_quantdev</small></td>
	   </tr>
	   ";
            }
        }
        ?>
    </table>
</div>
</body>
</html>

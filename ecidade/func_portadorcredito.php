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

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clabatimento = new cl_abatimento;
$clabatimento->rotulo->label("k125_sequencial");
$clabatimento->rotulo->label("k125_sequencial");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td align="center" valign="top">
      <?php

      $iDBInstit = (integer) db_getsession("DB_instit");

      $sql = "
        select cgm.z01_nome, cgm.z01_numcgm
          from abatimento
            inner join abatimentorecibo   on abatimentorecibo.k127_abatimento   = abatimento.k125_sequencial
            inner join arrenumcgm         on arrenumcgm.k00_numpre              = abatimentorecibo.k127_numprerecibo
            inner join cgm                on cgm.z01_numcgm                     = arrenumcgm.k00_numcgm
        where k125_tipoabatimento = 3
          and k125_instit         = {$iDBInstit}
          and k125_sequencial     = {$abatimento}
      ";

      if (!isset($pesquisa_chave)) {
        db_lovrot($sql, 15, "()", "", $funcao_js);
      } else {
        //Caso não implementado;
        echo "<script>".$funcao_js."('',false);</script>";
      }
      ?>
     </td>
   </tr>
</table>
  <script type="text/javascript">
    (function() {
      var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
      if(!!input){
        input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
      }
    })();
  </script>
</body>
</html>

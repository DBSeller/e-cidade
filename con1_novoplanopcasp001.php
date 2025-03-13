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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
$abas = new cl_criaabas;

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body >
<table>
    <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
            <?php
            $abas->identifica = [
                "conta" => "Conta",
                "reduzido" => "Reduzidos",
                "vinculo"  => "Vínculos",
                "contacorrente" => "Conta Corrente"
            ];
            $abas->title = [
                "conta" => "Conta",
                "reduzido" => "Reduzidos",
                "vinculo"  => "Vínculos",
                "contacorrente" => "Conta Corrente"
            ];
            $abas->src = [
                "conta" => "con1_manutencaopcasp001.php",
                "reduzido" => "",
                "vinculo"  => "",
                "contacorrente" => ""
            ];
            $abas->sizecampo = ["conta" => "23", "reduzido" => "15", "grupos" => "15"];
            $abas->disabled = ["conta" => false, "reduzido" => true, "vinculo" => true, "contacorrente" => true];
            $abas->cria_abas();
            ?>
        </td>
    </tr>
</table>
<?php
db_menu();
?>
</body>
</html>

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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification("model/fornecedor.model.php"));

require_once(modification("classes/db_empautorizaprocesso_classe.php"));

db_postmemory($HTTP_POST_VARS);

$clempautpresta = new cl_empautpresta;
$clempprestatip = new cl_empprestatip;
$clempautoriza = new cl_empautoriza;
$clempauthist = new cl_empauthist;
$clemphist = new cl_emphist;
$clempautitem = new cl_empautitem;
$clempautidot = new cl_empautidot;
$clemptipo = new cl_emptipo;
$clcflicita = new cl_cflicita;
$clpctipocompra = new cl_pctipocompra;
$clempparametro = new cl_empparametro;
$clpcparam = new cl_pcparam;
$clconcarpeculiar = new cl_concarpeculiar;
$oDaoEmpenhoProcessoAdminitrativo = new cl_empautorizaprocesso;


$db_opcao = 1;
$db_botao = true;
$sUrlEmpenho = "emp1_empempenho001.php";
$iAnoUsu = db_getsession("DB_anousu");
$iAnoData = date("Y", db_getsession("DB_datausu"));
$rsEmpParam = $clempparametro->sql_record($clempparametro->sql_query($iAnoUsu));
if ($clempparametro->numrows > 0) {
    db_fieldsmemory($rsEmpParam, 0);
    if ($e30_notaliquidacao != '') {
        $sUrlEmpenho = "emp4_empempenho001.php";
    }
} elseif (isset($pesq_ult) && $pesq_ult == true) {
    $result_ultalt = $clempautoriza->sql_record($clempautoriza->sql_query(null, "e54_numcgm  ,z01_nome  ,e54_login   ,e54_codcom  ,e54_destin  ,e54_valor   ,e54_anousu  ,e54_tipol   ,e54_numerl  ,e54_praent  ,e54_entpar  ,e54_conpag  ,e54_codout  ,e54_contat  ,e54_telef   ,e54_numsol  ,e54_anulad  ,e54_emiss   ,e54_resumo  ,e54_codtipo ,e54_instit  ,e54_depto", "e54_autori desc limit 1", "e54_instit =" . db_getsession("DB_instit") . " and e54_login=" . db_getsession("DB_id_usuario")));
    if ($clempautoriza->numrows > 0) {
        db_fieldsmemory($result_ultalt, 0);
    }
}
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js,
                prototype.js,
                widgets/windowAux.widget.js,
                strings.js,
                widgets/dbtextField.widget.js,
                dbViewNotificaFornecedor.js,
                dbmessageBoard.widget.js,
                dbautocomplete.widget.js,
                dbcomboBox.widget.js,
                datagrid.widget.js,
                widgets/dbtextFieldData.widget.js,
                DBFormCache.js,
                estilos.css,
                grid.style.css
  ");
    ?>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<div style="margin-top: 20px; width: 700px; ">
    <?php
    include(modification("forms/db_frmempautoriza.php"));
    ?>
</div>
</center>

</body>
</html>



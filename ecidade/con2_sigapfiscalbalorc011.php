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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("model/relatorioContabil.model.php"));

$oGet = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$clrotulo->label('o116_periodo');
$oRelatorio = new relatorioContabil($oGet->c83_codrel);

db_postmemory($_POST);

$iAnoUsu = db_getsession("DB_anousu");

$sLabelMsg = "Balanço Orçamentário - Linhas Auxiliares";
$oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
$lPrefeitura = $oInstituicao->prefeitura();

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="post" action="">
    <table align="center" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan=3 class='table_header'>
                <?= $sLabelMsg ?>
            </td>
        </tr>
        <tr>
            <td>
                <fieldset>
                    <legend><b>Filtros</b></legend>
                    <table align="center">
                        <tr style="<?php echo $lPrefeitura ? '' : 'display:none;' ?>">
                            <td align="center" colspan="3" id="ctnInstituicao">
                                <input type="hidden" name="db_selinstit" id="db_selinstit" value="">
                            </td>
                        </tr>
                        <tr>
                            <td align="right" nowrap>
                                <b>Bimestre :</b>
                            </td>
                            <td>
                                <?php
                                    $aPeriodos = $oRelatorio->getPeriodos();
                                    $aListaPeriodos = array();
                                    $aListaPeriodos[0] = "Selecione";
                                    foreach ($aPeriodos as $oPeriodo) {
                                        $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                                    }

                                    db_select("o116_periodo", $aListaPeriodos, true, 1);
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
</form>
</body>
</html>
<script type="text/javascript">
    var oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('ctnInstituicao'));
    oViewInstituicao.show();
</script>

<?php
/**
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
require_once(modification("classes/db_assenta_classe.php"));
require_once(modification("classes/db_tipoasse_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/DBDate.php"));

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoHoraExtraManual;
use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoAbonoFalta;

db_postmemory($_POST);

$classenta  = new cl_assenta;
$cltipoasse = new cl_tipoasse;
$db_opcao   = 1;
$db_botao   = true;

if( !isset($h12_tipo) ) {

    $h12_tipo   = "";
    $h12_tipefe = "";
}

/**
 * Inclui assentamento/afastamento
 */

if( isset($h16_assent) && trim($h16_assent) != "" ) {

    $result_assent = $cltipoasse->sql_record($cltipoasse->sql_query_file($h16_assent, "h12_tipo, h12_tipefe, h12_vinculaperiodoaquisitivo"));

    if($cltipoasse->numrows > 0){
        db_fieldsmemory($result_assent, 0);
    }

    $h80_db_cadattdinamicovalorgrupo = "";
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/dates.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/classes/http/http.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
if ( isset($incluir) && isset($lAssentamentoFuncional) ) { ?>
  <script>
    window.parent.assentamentofuncional.hide();
    window.parent.retornaAssentamentosFuncionais(<?=$oAssentamento->getCodigo()?>);
  </script>
<?php } ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
            <center>
                <?php include(modification("forms/db_frmassenta.php"));?>
            </center>
        </td>
    </tr>
</table>
<?php
if(!isset($lAssentamentoFuncional)) {db_menu();}
?>
</body>
</html>
<script>
    js_tabulacaoforms("form1","h16_regist",true,1,"h16_regist",true);
    js_limpaPeriodoAquisitivo();
</script>
<?php
if(isset($msg)) {
    db_msgbox($msg);
}
?>

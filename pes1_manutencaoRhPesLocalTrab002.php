<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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
require_once(modification("libs/db_conecta_plugin.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("classes/db_rhpeslocaltrab_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

$oGet             = db_utils::postMemory($_GET);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
  db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js, windowAux.widget.js, DBViewManutencaoLocalTrabalho.classe.js');
  db_app::load('dbmessageBoard.widget.js, dbtextField.widget.js, dbtextFieldData.widget.js, estilos.css, grid.style.css');
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="450" align="center" valign="top" bgcolor="#CCCCCC"> 
      <div id="containerLocaisTrabalho"></div>
      <div>
        <input type='button' id='btnSalvar' value='Salvar' onClick='oLocaisTrabalho.salvarRegistros();' />
        <input type="button" value="Cancelar"  onclick="parent.js_fechaJanelaManutencao();">
      </div>    
    </td>
  </tr>
</table>
</body>
</html>
<script>

var oGet            = js_urlToObject();

var oLocaisTrabalho = new DBViewManutencaoLocalTrabalho('oLocaisTrabalho', 'locaisTrabalho');
    
    oLocaisTrabalho.setCodigoServidor( oGet.iMatricula );
    oLocaisTrabalho.show($('containerLocaisTrabalho'));
</script>

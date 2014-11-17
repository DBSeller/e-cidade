<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("libs/db_stdlibwebseller.php");
include("libs/db_jsplibwebseller.php");

include("classes/db_sau_prestadorhorarios_classe.php");
include("classes/db_sau_agendaexames_classe.php");
//include("classes/db_sau_exames_classe.php"

include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clsau_agendaexames      = new cl_sau_agendaexames();
$clsau_prestadorhorarios = new cl_sau_prestadorhorarios();
 
//depois
if( isset( $chave_diasemana ) && $chave_diasemana != "" ){
	$result = $clundmedhorario->sql_record( $clundmedhorario->sql_query_ext("","*","", "sd30_i_codigo = $sd30_i_codigo and sd30_i_diasemana = $chave_diasemana ") );
	if( $clundmedhorario->numrows == 0 ){
		db_msgbox("Profissional não possui agendamento.");
	}else{
		db_fieldsmemory( $result, 0 );
		$agendados = true;
	}
}

$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
    <br><br>
    <center>
        <?
        include("forms/db_frmagendaexames.php");
        ?>
    </center>
    </td>
  </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
  js_tabulacaoforms("form1","s111_i_exame",true,1,"s111_i_exame",true);
</script>
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_saltes_classe.php"));

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$db_opcao = 1;
$db_botao = true;
$clsaltes = new cl_saltes;
$clempagedadosret = new cl_empagedadosret;
$clconcilia = new cl_concilia;


$sWhere = " k68_contabancaria = $conta ";

$sSql = $clconcilia->sql_query(null, " k68_sequencial as concilia,
									   k68_data as data,
									   k68_contabancaria as conta "," k68_data desc limit 1 " , $sWhere);
$result = $clconcilia->sql_record($sSql);



if ($clconcilia->numrows != 0) {

	db_inicio_transacao();
	$sqlerro = false;
	
   db_fieldsmemory($result, 0);
   $clextratolinha         = new cl_extratolinha;
   $clconciliapendextrato  = new cl_conciliapendextrato;
   $clconcilia             = new cl_concilia;

	// busca lancamentos do extratolinha incluido como pendencias futuras
	//
	//


	$sqlPendExtrato  = " select k86_sequencial ";
	$sqlPendExtrato .= "	 from extratolinha ";
	$sqlPendExtrato .= "	 left join conciliapendextrato on k88_extratolinha = k86_sequencial ";
	$sqlPendExtrato .= "	where extract(month from k86_data) = extract(month from( select k68_data "; 
    $sqlPendExtrato .= "                        from concilia "; 
    $sqlPendExtrato .= "                       where k68_data = '".$data."' "; 
    $sqlPendExtrato .= "                         and k68_contabancaria = $conta "; 
    $sqlPendExtrato .= "                       order by k68_data  ";
    $sqlPendExtrato .= "                        desc limit 1 )) ";
	$sqlPendExtrato .= "	  and k86_contabancaria = ".$conta ;
	$sqlPendExtrato .= " and k88_sequencial is null";
	$rsExtrato = $clextratolinha->sql_record($sqlPendExtrato);

	$intNumrowsextrato = $clextratolinha->numrows;
	
	for($i = 0; $i < $intNumrowsextrato; $i++ ){
		
		db_fieldsmemory($rsExtrato,$i);	
		$clconciliapendextrato->k88_extratolinha   = $k86_sequencial;
		$clconciliapendextrato->k88_concilia       = $concilia;
		$clconciliapendextrato->k88_conciliaorigem = 1;
		$clconciliapendextrato->k88_justificativa  = '';
		$clconciliapendextrato->incluir(null);
		if($clconciliapendextrato->erro_status == 0){
			$erromsg = $clconciliapendextrato->erro_msg;
			$sqlerro = true;
			break;
		}
	}

	// mesma coisa que o for a cima porem com as pendencias de extrato 

	/*

	verifiquei que aqui ja vem os que estão vinculados


	$sqlPendExtrato  = " select conciliapendextrato.* ";
	$sqlPendExtrato .= "	 from concilia ";
	$sqlPendExtrato .= "		    inner join conciliapendextrato on k88_concilia = k68_sequencial ";
	$sqlPendExtrato .= "	where k68_data  = ( select k68_data "; 
    $sqlPendExtrato .= "                        from concilia "; 
    $sqlPendExtrato .= "                       where k68_data < '".$data."' "; 
    $sqlPendExtrato .= "                         and k68_contabancaria = $conta "; 
    $sqlPendExtrato .= "                       order by k68_data  ";
    $sqlPendExtrato .= "                        desc limit 1 ) ";
	$sqlPendExtrato .= "	  and k68_contabancaria = ".$conta ;
	
	$rsExtrato = $clextratolinha->sql_record($sqlPendExtrato);
	$intNumrowsextrato = $clextratolinha->numrows;
	for($i = 0; $i < $intNumrowsextrato; $i++ ){
		db_fieldsmemory($rsExtrato,$i);	
		$clconciliapendextrato->k88_extratolinha   = $k88_extratolinha;
		$clconciliapendextrato->k88_concilia       = $clconcilia->k68_sequencial;
		$clconciliapendextrato->k88_conciliaorigem = 1;
		$clconciliapendextrato->k88_justificativa  = $k88_justificativa;
		$clconciliapendextrato->incluir(null);
		if($clconciliapendextrato->erro_status == 0){
			$erromsg = $clconciliapendextrato->erro_msg;
			$sqlerro = true;
			break;
		}
	}
*/






	db_fim_transacao($sqlerro);
}



?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>
	<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
		<table width="790" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
			<tr>
				<td width="360" height="18">&nbsp;</td>
				<td width="263">&nbsp;</td>
				<td width="25">&nbsp;</td>
				<td width="140">&nbsp;</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr> 
				<td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
				<center>
			<?
			include(modification("forms/db_frmconcbanc.php"));
			?>
				</center>
			</td>
			</tr>
		</table>
		<?
		db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?>
	</body>
</html>
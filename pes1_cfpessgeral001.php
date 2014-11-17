<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");

require_once ("classes/db_cfpess_classe.php");
require_once ("classes/db_inssirf_classe.php");

require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clcfpess  = new cl_cfpess;
$clinssirf = new cl_inssirf;
$db_opcao  = 2;
$db_botao  = true;

if (isset($alterar)) {

  include("pes1_cfpess002.php");

}

$sCampos  = "r11_anousu, r11_mesusu   , r11_instit   , r11_ultfec      , r11_arredn, r11_fgts12 , r11_codaec, r11_natest ,r11_modanalitica,                        ";
$sCampos .= "r11_cdfpas, r11_cdactr   , r11_peactr   , r11_pctemp      , r11_pcterc, r11_tbprev , r11_cdcef , r11_cdfgts ,r11_histslip,                            ";
$sCampos .= "r11_confer, r11_ultreg   , r11_concatdv , r11_altfer      , r11_mes13 , r11_sald13 , r11_qtdcal, r11_implan ,                                         ";
$sCampos .= "r11_conver, r11_codestrut, r11_localtrab, r11_databaseatra, r11_infla , r11_baseipe, r11_txadm , r11_mensagempadraotxt,                               ";
$sCampos .= "r11_relatoriocontracheque, r11_relatorioempenhofolha, r11_relatoriocomprovanterendimentos, r11_relatoriotermorescisao,                                ";
$sCampos .= "r11_geraretencaoempenho, r11_datainiciovigenciarpps, r11_sistemacontroleponto, db77_descr,db77_descr as db77_descr1, i01_descr, rh32_descr, c50_descr ";

$sSql     = $clcfpess->sql_query(db_anofolha(),db_mesfolha(),db_getsession("DB_instit"),$sCampos);
$result   = $clcfpess->sql_record($sSql);

if($result != false && $clcfpess->numrows > 0){
  db_fieldsmemory($result,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC style="margin-top:20px;" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmcfpessgeral.php");
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
<?
if(isset($alterar)){
  if($clcfpess->erro_status == "0"){
    $clcfpess->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcfpess->erro_campo != ""){
      echo "<script> document.form1.".$clcfpess->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcfpess->erro_campo.".focus();</script>";
    }
  }else{
    $clcfpess->erro(true,true);
  }
}
if($db_opcao == 22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
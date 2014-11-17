<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory( $_POST );

$clrechumanoativ = new cl_rechumanoativ;
$clrechumano     = new cl_rechumano;

$db_opcao            = 1;
$db_botao            = true;
$iEscola             = db_getsession( "DB_coddepto" );
$sWhereRecHumanoAtiv = "";
$iRecHumanoEscola    = '';
$oGet                = db_utils::postMemory( $_GET );

if( isset( $ed22_i_rechumanoescola ) && !empty( $ed22_i_rechumanoescola ) ) {

  $iRecHumanoEscola    = $ed22_i_rechumanoescola;
  $sWhereRecHumanoAtiv = "ed75_i_codigo = {$ed22_i_rechumanoescola}";
} else if( isset( $oGet->ed22_i_rechumanoescola ) && !empty( $oGet->ed22_i_rechumanoescola ) ) {

  $iRecHumanoEscola    = $oGet->ed22_i_rechumanoescola;
  $sWhereRecHumanoAtiv = "ed75_i_codigo = {$oGet->ed22_i_rechumanoescola}";
} else {
  $sWhereRecHumanoAtiv = "ed75_i_rechumano = {$ed75_i_rechumano} AND ed75_i_escola = {$iEscola}";
}

$sCamposRecHumanoAtiv = "ed22_i_atividade as ativjacad, ed75_i_codigo";
$sSqlRecHumanoAtiv    = $clrechumanoativ->sql_query( "", $sCamposRecHumanoAtiv,"", $sWhereRecHumanoAtiv );
$result1              = $clrechumanoativ->sql_record( $sSqlRecHumanoAtiv );

if( $clrechumanoativ->numrows > 0 ) {

  $sep      = "";
  $ativ_cad = "";

  for( $c = 0; $c < $clrechumanoativ->numrows; $c++ ) {

    db_fieldsmemory( $result1, $c );

    $ativ_cad         .= $sep.$ativjacad;
    $sep               = ",";
    $iRecHumanoEscola  = $ed75_i_codigo;
  }
} else {

  $ativ_cad            = 0;
  $oDaoRecHumanoEscola = new cl_rechumanoescola();
  $sSqlRecHumanoEscola = $oDaoRecHumanoEscola->sql_query_file( null, 'ed75_i_codigo', null, $sWhereRecHumanoAtiv );
  $rsRecHumanoEscola   = db_query( $sSqlRecHumanoEscola );

  if( $rsRecHumanoEscola && pg_num_rows( $rsRecHumanoEscola ) > 0 ) {
    $iRecHumanoEscola  = db_utils::fieldsMemory( $rsRecHumanoEscola, 0 )->ed75_i_codigo;
  }
}

if( isset( $incluir ) ) {

  db_inicio_transacao();

  $clrechumanoativ->ed22_i_rechumanoescola = $iRecHumanoEscola;
  $clrechumanoativ->incluir($ed22_i_codigo);
  db_fim_transacao();
}

if( isset( $alterar ) ) {

  db_inicio_transacao();
  $db_opcao = 2;
  $clrechumanoativ->alterar($ed22_i_codigo);
  db_fim_transacao();
}

if( isset( $excluir ) ) {

  db_inicio_transacao();
  $db_opcao = 3;
  $clrechumanoativ->excluir($ed22_i_codigo);
  db_fim_transacao();
}
$campos = "case
                when ed20_i_tiposervidor = 1
                then ed284_i_rhpessoal
                else ed285_i_cgm
            end as identificacao,
           case
                when ed20_i_tiposervidor = 1
                then cgmrh.z01_nome
                else cgmcgm.z01_nome
            end as z01_nome,
           ed20_i_tiposervidor, ed20_i_codigo, ed22_i_rechumanoescola";

$sSqlRecHumano = $clrechumano->sql_query_escola( "",$campos, "", $sWhereRecHumanoAtiv );
$result11      = $clrechumano->sql_record( $sSqlRecHumano );
db_fieldsmemory( $result11, 0 );
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
        <br>
        <center>
          <fieldset style="width:95%"><legend><b>Atividades</b></legend>
            <?include("forms/db_frmrechumanoativ.php");?>
          </fieldset>
        </center>
      </td>
    </tr>
  </table>
</body>
</html>
<script>
js_tabulacaoforms( "form1", "ed22_i_atividade", true, 1, "ed22_i_atividade", true );
</script>
<?php
if( isset( $incluir ) ) {

  if( $clrechumanoativ->erro_status == "0" ) {

    $clrechumanoativ->erro( true, false );
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $clrechumanoativ->erro_campo != "" ) {

      echo "<script> document.form1.".$clrechumanoativ->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrechumanoativ->erro_campo.".focus();</script>";
    }
  } else {

   ?>
   <script>
     top.corpo.iframe_a5.location.href = 'edu1_relacaotrabalho001.php?ed23_i_rechumanoescola=<?=$iRecHumanoEscola?>';
     top.corpo.iframe_a6.location.href = 'edu1_rechumanohoradisp001.php?ed20_i_codigo=<?=$ed20_i_codigo?>';
     top.corpo.iframe_a7.location.href = 'edu1_rechumanohorario001.php?ed20_i_codigo=<?=$ed20_i_codigo?>';
   </script>
   <?
   db_redireciona("edu1_rechumanoativ001.php?ed22_i_rechumanoescola=$iRecHumanoEscola");
  }
}

if( isset( $alterar ) ) {

  if( $clrechumanoativ->erro_status == "0" ) {

    $clrechumanoativ->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if( $clrechumanoativ->erro_campo != "" ) {

      echo "<script> document.form1.".$clrechumanoativ->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrechumanoativ->erro_campo.".focus();</script>";
    }
  } else {

   ?>
   <script>
     top.corpo.iframe_a5.location.href = 'edu1_relacaotrabalho001.php?ed23_i_rechumanoescola=<?=$ed22_i_rechumanoescola?>';
     top.corpo.iframe_a6.location.href = 'edu1_rechumanohoradisp001.php?ed20_i_codigo=<?=$ed20_i_codigo?>';
     top.corpo.iframe_a7.location.href = 'edu1_rechumanohorario001.php?ed20_i_codigo=<?=$ed20_i_codigo?>';
   </script>
   <?
   db_redireciona("edu1_rechumanoativ001.php?ed22_i_rechumanoescola=$iRecHumanoEscola");
  }
}

if( isset( $excluir ) ) {

  if ($clrechumanoativ->erro_status == "0" ) {
    $clrechumanoativ->erro(true,false);
  } else {

   ?>
   <script>
     top.corpo.iframe_a5.location.href = 'edu1_relacaotrabalho001.php?ed23_i_rechumanoescola=<?=$iRecHumanoEscola?>';
     top.corpo.iframe_a6.location.href = 'edu1_rechumanohoradisp001.php?ed20_i_codigo=<?=$ed20_i_codigo?>';
     top.corpo.iframe_a7.location.href = 'edu1_rechumanohorario001.php?ed20_i_codigo=<?=$ed20_i_codigo?>';
   </script>
   <?
   db_redireciona("edu1_rechumanoativ001.php?ed22_i_rechumanoescola=$iRecHumanoEscola");
  }
}

if( isset( $cancelar ) ) {
  db_redireciona("edu1_rechumanoativ001.php?ed22_i_rechumanoescola=$iRecHumanoEscola");
}
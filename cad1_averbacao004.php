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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_averbacao_classe.php"));
include(modification("classes/db_averbacgm_classe.php"));
include(modification("classes/db_averbaregimovel_classe.php"));
include(modification("classes/db_averbaescritura_classe.php"));
include(modification("classes/db_averbaprocesso_classe.php"));
include(modification("classes/db_averbatipo_classe.php"));
include(modification("classes/db_arrematric_classe.php"));
include(modification("classes/db_iptubase_classe.php"));
include(modification("classes/db_averbaformalpartilha_classe.php"));
include(modification("classes/db_averbaformalpartilhacgm_classe.php"));
include(modification("classes/db_averbadecisaojudicial_classe.php"));
include(modification("classes/db_averbaguia_classe.php"));
include(modification("classes/db_averbaguiaitbi_classe.php"));
include(modification("classes/db_cgm_classe.php"));
include(modification("classes/db_db_config_classe.php"));

$cliptubase                  = new cl_iptubase;
$claverbacao                 = new cl_averbacao;
$claverbacgm                 = new cl_averbacgm;
$claverbaregimovel           = new cl_averbaregimovel;
$claverbaescritura           = new cl_averbaescritura;
$claverbaprocesso            = new cl_averbaprocesso;
$claverbatipo                = new cl_averbatipo;
$clarrematric                = new cl_arrematric;
$clcgm                       = new cl_cgm;
$cldb_config                 = new cl_db_config;
$claverbadecisaojudicial     = new cl_averbadecisaojudicial;
$claverbaformalpartilha      = new cl_averbaformalpartilha;
$claverbaformalpartilhacgm   = new cl_averbaformalpartilhacgm;
$claverbaguia                = new cl_averbaguia;
$claverbaguiaitbi            = new cl_averbaguiaitbi;

$claverbaformalpartilha->rotulo->label();
$claverbaescritura->rotulo->label();
$claverbadecisaojudicial->rotulo->label();
$clcgm->rotulo->label();
$claverbaguia->rotulo->label();

db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
	$sqlerro=false;
	$regra = "";

	$result_regra = $claverbatipo->sql_record($claverbatipo->sql_query_file($j75_tipo,"j93_regra,j93_descr"));
	if ($claverbatipo->numrows>0){
                db_fieldsmemory($result_regra,0);
		$regra = $j93_regra;
		if(($regra== 0) or ($regra == "")){
			$sqlerro=true;
		$erro_msg = "Precisa configurar a Regra de Averbação para o tipo: $j75_tipo - ".@$j93_descr;
		}
	}else{
		$sqlerro=true;
		$erro_msg = "Precisa configurar a Regra de Averbação para o tipo: $j75_tipo - ".@$j93_descr;
	}

  db_inicio_transacao();
  
  if ($sqlerro==false){
		$claverbacao->j75_data=date("Y-m-d",db_getsession("DB_datausu"));
		$claverbacao->j75_situacao = 1;
		$claverbacao->j75_regra = $regra;
		$claverbacao->j75_responsavel=db_getsession('DB_id_usuario');
		$claverbacao->incluir($j75_codigo);
		if($claverbacao->erro_status==0){
			$sqlerro=true;
		}  
		$erro_msg = $claverbacao->erro_msg;
	}
  if ($sqlerro==false){
  	if ($j93_averbagrupo==2){
  		$claverbaregimovel->j78_averbacao=$claverbacao->j75_codigo;
  		$claverbaregimovel->incluir($claverbacao->j75_codigo);
  		if($claverbaregimovel->erro_status==0){
    		$sqlerro=true;
  			$erro_msg = $claverbaregimovel->erro_msg;
  		}
  	}
  } 
  if ($sqlerro==false){
  	if ($j93_averbagrupo==1){
  		$claverbaescritura->j94_averbacao=$claverbacao->j75_codigo;
  		$claverbaescritura->incluir(null);
  		if($claverbaescritura->erro_status==0){
    		$sqlerro=true;
  			$erro_msg = $claverbaescritura->erro_msg;
  		}
  	}
  } 
  if ($sqlerro==false){
  	if ($j77_codproc!=""){
  		$claverbaprocesso->p77_averbacao = $claverbacao->j75_codigo;
  		$claverbaprocesso->incluir($claverbacao->j75_codigo);
  		if ($claverbaprocesso->erro_status==0){
  			$sqlerro=true;
  			$erro_msg = $claverbaprocesso->erro_msg;
  		}
  	}
  }
  if ($sqlerro==false){
  	if ($j93_averbagrupo==4){
  		$claverbaformalpartilha->j100_averbacao=$claverbacao->j75_codigo;
  		$claverbaformalpartilha->incluir(null);
  		if($claverbaformalpartilha->erro_status==0){
    		$sqlerro=true;
  			$erro_msg = $claverbaformalpartilha->erro_msg;
  		}
		if(isset($z01_numcgm1) and $z01_numcgm1!="" ){
		  $claverbaformalpartilhacgm->j102_numcgm = $z01_numcgm1;
		  $claverbaformalpartilhacgm->j102_averbaformalpartilha = $claverbaformalpartilha->j100_sequencial;
  		  $claverbaformalpartilhacgm->incluir(null);
  		  if($claverbaformalpartilhacgm->erro_status==0){
    		$sqlerro=true;
  			$erro_msg = $claverbaformalpartilhacgm->erro_msg;
  	  	  }
	    }
	}
  }
  if ($sqlerro==false){
  	if ($j93_averbagrupo==5){
  		$claverbadecisaojudicial->j101_averbacao = $claverbacao->j75_codigo;
  		$claverbadecisaojudicial->incluir(null);
  		if($claverbadecisaojudicial->erro_status==0){
    		$sqlerro=true;
  			$erro_msg = $claverbadecisaojudicial->erro_msg;
  		}
		
		
	}
	
  }
  
  if ($sqlerro==false){
  	if ($j93_averbagrupo==6){
  	if($guia==2){
  		//não
		$j104_guia = $guianao;
  	}	
	  $claverbaguia->j104_guia      = $j104_guia;
  	$claverbaguia->j104_averbacao = $claverbacao->j75_codigo;
	  $claverbaguia->incluir(null);
  	if($claverbaguia->erro_status==0){
    	$sqlerro=true;
  		$erro_msg = $claverbaguia->erro_msg;
  	}
	  if($guia==1){
  		//sim
		if ($sqlerro==false){
	      if($j103_itbi!=""){
	  	    $claverbaguiaitbi->j103_averbaguia = $claverbaguia->j104_sequencial;
	        $claverbaguiaitbi->incluir(null);
  	        if($claverbaguiaitbi->erro_status==0){
    	      $sqlerro=true;
  	  	      $erro_msg = $claverbaguiaitbi->erro_msg;
  	        }
		  }
	    }
  	  }	// do sim
	  
	}
  }
  db_fim_transacao($sqlerro);
   $j75_codigo= $claverbacao->j75_codigo;
   $db_opcao = 1;
   $db_botao = true;
} else if(isset($j75_matric)) {
  
	if (!isset($debitos)||$debitos!=true) {
	  
	  $sSqlIptuBase = $cliptubase->sql_query(null,"*",null,"j01_matric = $j75_matric and j01_baixa is not null" );
		$result_baixa = $cliptubase->sql_record($sSqlIptuBase);	
		if ($cliptubase->numrows > 0) {
			db_msgbox("Matrícula baixada. Operação cancelada!!");
			echo "<script>   parent.location.href='cad4_averbacao001.php'; </script>";
		}
	
	  $iInstitSessao = db_getsession('DB_instit');
	  $result        = $cldb_config->sql_record($cldb_config->sql_query_file($iInstitSessao, "cgc, db21_codcli"));
	  db_fieldsmemory($result, 0);
	
	  $lBloqueia = 0;
	  if ($db21_codcli == 19985) {
	    $lBloqueia = 1;
	  }
	
	  $sCamposMatricula = "distinct cadtipo.k03_tipo,cadtipo.k03_descr ";
	  $sSqlMatricula    = $clarrematric->sql_query_info(null, null, $sCamposMatricula, null, "arrematric.k00_matric = $j75_matric");
	  $result_deb       = $clarrematric->sql_record($sSqlMatricula);
		$numrows_deb      = $clarrematric->numrows;
		$descr            = '\n';
		$debitos          = true;
	
	  for ($w = 0; $w < $numrows_deb; $w++) {
	    
	    db_fieldsmemory($result_deb,$w);
	    $descr .=  "*".@$k03_descr.'\n';
	
	    if ( $k03_tipo == 13 or $k03_tipo == 18 ) {
	      $lTemInicialAberto = 1;
	    }
	  }
	
		if($numrows_deb > 0 and $lBloqueia == 1) {

		  if ( $lTemInicialAberto == 1 ) {
	     	echo "<script>
	            alert('Existe débito ajuizado em aberto para esta matrícula - procedimento não pode ser executado!');
							parent.location.href='cad4_averbacao001.php';
	            </script>";
	    }
	  } else if ($numrows_deb > 0 ) {
	    
	    echo "<script>
	  
	             if(confirm('Existe débito de: ".$descr."para esta matrícula, deseja continuar?')){
	             }else{
	               parent.location.href='cad4_averbacao001.php';
	             }
	            </script>";
	  }
  
	}
	$DAOregistroImovel = new cl_iptubaseregimovel();
	$sql = $DAOregistroImovel->sql_query_file(null, "j04_matricregimo", null, "j04_matric = {$j75_matric}");
	$rs = db_query($sql);
	if(!$rs) {
		echo "<script>
				alert('Erro ao buscar a Matricula de Registro de Imóveis.');
				parent.location.href='cad4_averbacao001.php';
			  </script>";
	}
	if(pg_num_rows($rs) > 0) {
		$j78_matric = db_utils::fieldsMemory($rs, 0)->j04_matricregimo;
	}

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" onLoad="a=1" >

	<?php
	include(modification("forms/db_frmaverbacao.php"));
	?>

</body>
</html>
<?php
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($claverbacao->erro_campo!=""){
      echo "<script> document.form1.".$claverbacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$claverbacao->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
	 db_redireciona("cad1_averbacao005.php?liberaaba=true&chavepesquisa=$j75_codigo");
	 
  }
}
?>

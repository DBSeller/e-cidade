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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_matrequi_classe.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmatrequi = new cl_matrequi;
$clmatrequi->rotulo->label("m40_codigo");
$clmatrequi->rotulo->label("m40_codigo");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" valign="top">
        <table width="35%" border="0" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" nowrap title="<?=$Tm40_codigo?>" nowrap>
              <?=$Lm40_codigo?>
            </td>
            <td width="96%" nowrap> 
              <?php db_input("m40_codigo",10,$Im40_codigo,true,"text",4,"","chave_m40_codigo"); ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <b>Trazer Requisições de Exercícios Anteriores:</b>
            </td>
            <td>
              <?php db_select("trazoutrozexercicios", array('n'=>'Não','s'=>'Sim'), true,1); ?>
            </td>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="btnLimpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_atendrequi.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td valign="top"> 
      <?php

        $m40_codigo = "";
        $campos = "matrequi.*, db_depart.descrdepto";
		    // filtro para trazer requisição não automáticas
		    $where[] = "m40_auto='f'";
		    if (!isset($trazoutrozexercicios)) {
		      $trazoutrozexercicios = 'n';
		    }
      
        // filtro para não trazer registro de atendimentos com itens devolvidos
        if(isset($naoTrazerDevolucoes)) {
          $where[] = "not exists(
            select * 
            from matestoquedevitem as x
              inner join matestoquedev as a
              on x.m46_codmatestoquedev = a.m45_codigo
              inner join atendrequi as b
              on a.m45_codatendrequi = b.m42_codigo
              inner join matrequi as c
              on c.m40_codigo = a.m45_codmatrequi
            where c.m40_codigo = matrequi.m40_codigo)";
        }
		    // filtro por departamento
        if (isset($sFiltro)) {
          if ($sFiltro == "almox") {
            $where[] = "m91_depto = ".db_getsession("DB_coddepto");
          }
        }
	      if ($trazoutrozexercicios == 'n') {
	        $where[] = "cast(extract(year from m40_data) as integer) = ".db_getsession('DB_anousu');
	      }

        // filtra todas as requisições pelas não atendidas e as parcialmente atendidas 			
	      $groupBy  = "group by 
          matrequi.m40_codigo,      
 	      	matrequi.m40_login,      
	      	matrequi.m40_auto,       
 	       	matrequi.m40_data,       
 	       	matrequi.m40_depto,       
 	      	matrequi.m40_hora,       
 	      	matrequi.m40_obs,        
 	      	matrequiitem.m41_quant,  
 	      	matrequiitem.m41_codigo, 
          db_depart.descrdepto";
         	  
	      $having  = "having                     
	        coalesce(matrequiitem.m41_quant - 
	        ((select coalesce(sum(atendrequiitem.m43_quantatend-coalesce(m46_quantdev,0)),0)
            from atendrequiitem 
              left join matestoquedevitem on atendrequiitem.m43_codigo = m46_codatendrequiitem
            where m43_codmatrequiitem = m41_codigo) + 
            (select coalesce(sum(m103_quantanulada),0) from matanulitem
                left join matanulitemrequi on matanulitemrequi.m102_matanulitem = matanulitem.m103_codigo
              where m102_matrequiitem = m41_codigo))
          ,0) > 0";       
      
        if (!isset($pesquisa_chave)) {
          if (isset($campos) == false) { 
            if(file_exists("funcoes/db_func_matrequi.php") == true) {
              $campos = "matrequi.m40_codigo,matrequi.m40_data,matrequi.m40_depto,matrequi.m40_login,matrequi.m40_hora,matrequi.m40_obs,matrequi.m40_auto,db_depart.descrdepto";
            }
          }
          if (isset($chave_m40_codigo) && (trim($chave_m40_codigo) != "")) {
	          $m40_codigo = $chave_m40_codigo;
		    	}

          $where = implode(' AND ', $where);
          $sql = $clmatrequi->sql_query_atentimentos(
            $m40_codigo,
            $campos,
            "m40_codigo desc",
            "{$where} {$groupBy} {$having}"
          );
		    	
		    	$aRepassa = array();
          db_lovrot($sql, 15, "()", "", $funcao_js, null, 'NoMe', $aRepassa, false);

        } else {
          if ($pesquisa_chave != null && $pesquisa_chave != "") {
            $sSql   = $clmatrequi->sql_query_atentimentos($pesquisa_chave, $campos, "m40_codigo desc", $where);
            $result = $clmatrequi->sql_record($sSql);
            if ($clmatrequi->numrows != 0){
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$m40_codigo',false);</script>";
            } else {
	           echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
            }
          } else {
	         echo "<script>".$funcao_js."('',false);</script>";
          }
        }
      ?>
    </td>
  </tr>
</table>
</body>
</html>
<script type="text/javascript">
document.getElementById('btnLimpar').addEventListener('click', function() {

  document.getElementById('chave_m40_codigo').value = '';
  document.getElementById('chave_m40_codigo').setAttribute('value', '');
});
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>

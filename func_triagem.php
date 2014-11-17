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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");

if( !isset( $data_ini ) ) {

  $data_ini     = date( "d-m-Y", db_getsession("DB_datausu") );
  $data_ini_dia = date( "d",     db_getsession("DB_datausu") );
  $data_ini_mes = date( "m",     db_getsession("DB_datausu") );
  $data_ini_ano = date( "Y",     db_getsession("DB_datausu") );
}

$dHoje = date("Y-m-d",db_getsession("DB_datausu"));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class="body-defaut">
  <div class="container">
    <form name="form2" method="post" action="" class="form-container">
      <fieldset>
        <legend>Filtros</legend>
        <table>
          <tr>
            <td nowrap title="<?=$Tz01_v_nome?>">
              <?=$Lz01_v_nome?>
            </td>
            <td nowrap colspan="4">
              <?php
              db_input( "z01_v_nome", 40, $Iz01_v_nome, true, "text", 4, "", "chave_z01_v_nome" );
              ?>
            </td>
          </tr>
          <tr> 
            <td title="<?=$Tsd24_i_codigo?>">
              <?=$Lsd24_i_codigo?>
            </td>
            <td nowrap>
              <?php
              db_input( "sd24_i_codigo", 11, $Isd24_i_codigo, true, "text", 4, "", "chave_sd24_i_codigo" );
              ?>
            </td>
            <td>
              <label class="bold">Início:</label>
            </td>
            <td>
              <?php
              db_inputdata( 'data_ini', @$data_ini_dia, @$data_ini_mes, @$data_ini_ano, true, 'text', 4, "", 'chave_data_ini' );
              ?>
            </td>
          </tr>
          <tr> 
            <td nowrap title="<?=$Tsd24_i_ano.'|'.$Tsd24_i_mes.'|'.$Tsd24_i_seq?>">
              <?=$Lsd24_i_ano.'|'.$Lsd24_i_mes.'|'.$Lsd24_i_seq?>
            </td>
            <td nowrap>
              <?php
              db_input( "sd24_i_ano", 4, $Isd24_i_ano, true, "text", 4, "", "chave_sd24_i_ano" );
              db_input( "sd24_i_mes", 2, $Isd24_i_mes, true, "text", 4, "", "chave_sd24_i_mes" );
              db_input( "sd24_i_seq", 5, $Isd24_i_seq, true, "text", 4, "", "chave_sd24_i_seq" );
              ?>
            </td>
            <td>
              <label class="bold">Fim:</label>
            </td>
            <td>
              <?php
              db_inputdata( 'data_fim', @$data_fim_dia, @$data_fim_mes, @$data_fim_ano, true, 'text', 4, "", 'chave_data_fim' );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar"    type="button" id="limpar"     value="Limpar"      onClick="js_limpar();">
      <input name="emite"     type="button" id="emite"      value="Emite Lista" onClick="js_emitelista()">
      <input name="Fechar"    type="button" id="fechar"     value="Fechar"      onClick="parent.db_iframe_triagem.hide();">
    </form>
</div>
<div class="container">
  <table>
    <tr>
      <td>
        <?php
        $sSql     = '';
        $lFiltrou = false;
        $sOrder   = 'sd24_i_codigo';
        $sWhere   = '';
        $aWhere   = array();
        $aWhere[] = "sd24_i_unidade = " . DB_getsession( "DB_coddepto" );

        if( !isset( $pesquisa_chave ) ) {

          if( isset( $campos ) == false ) {

            if( file_exists( "funcoes/db_func_triagem.php" ) == true ) {
              require_once("funcoes/db_func_triagem.php");
            } else {
              $campos = "prontuarios.*";
            }
          }

          if( isset( $chave_sd24_i_codigo ) && ( trim( $chave_sd24_i_codigo ) != "" ) ) {

            $lFiltrou = true;
            $aWhere[] = "sd24_i_codigo = {$chave_sd24_i_codigo}";
          }

          if(    isset( $chave_sd24_i_ano ) && ( trim( $chave_sd24_i_ano ) != "" )
                     && isset( $chave_sd24_i_mes ) && ( trim( $chave_sd24_i_mes ) != "" )
                     && isset( $chave_sd24_i_seq ) && ( trim( $chave_sd24_i_seq ) != "" )
                   ) {

            $lFiltrou = true;
            $aWhere[] = "sd24_i_ano = {$chave_sd24_i_ano}";
            $aWhere[] = "sd24_i_mes = {$chave_sd24_i_mes}";
            $aWhere[] = "sd24_i_seq = {$chave_sd24_i_seq}";
          }

          if( isset( $chave_z01_v_nome ) && ( trim( $chave_z01_v_nome ) != "" ) ) {

            $lFiltrou = true;
            $aWhere[] = "cgs_und.z01_v_nome like '{$chave_z01_v_nome}%'";
            $sOrder   = "cgs_und.z01_v_nome, sd24_i_codigo";
          }

          if( isset( $chave_data_ini ) && ( $chave_data_ini != "" ) ) {

            $lFiltrou    = true;
            $oDataInicio = new DBDate( $chave_data_ini );
            $sWhereData  = "sd24_d_cadastro >= '{$oDataInicio->convertTo( DBDate::DATA_EN )}'";

            if( isset( $chave_data_fim ) && $chave_data_fim != "" ) {

              $oDataFim    = new DBDate( $chave_data_fim );
              $sWhereData  = "( sd24_d_cadastro between '{$oDataInicio->convertTo( DBDate::DATA_EN )}'";
              $sWhereData .= "and '{$oDataFim->convertTo( DBDate::DATA_EN )}' )";
             }

            $aWhere[] = $sWhereData;
          }

          if( !$lFiltrou ) {

            $sSql  = "select distinct sd24_i_codigo, sd24_i_ano, sd24_i_mes, z01_v_nome, sd24_i_numcgs ";
            $sSql .= "  from prontuarios ";
            $sSql .= "       inner join cgs_und           on cgs_und.z01_i_cgsund         = prontuarios.sd24_i_numcgs ";
            $sSql .= "       left  join especmedico       on especmedico.sd27_i_codigo    = prontuarios.sd24_i_profissional ";
            $sSql .= "       left  join unidademedicos    on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ";
            $sSql .= "       left  join medicos           on medicos.sd03_i_codigo        = unidademedicos.sd04_i_medico ";
            $sSql .= "       left  join cgm               on cgm.z01_numcgm               = medicos.sd03_i_cgm ";
            $sSql .= "       left  join rhcbo             on rhcbo.rh70_sequencial        =  especmedico.sd27_i_rhcbo ";
            $sSql .= "       left  join unidades          on unidades.sd02_i_codigo       = prontuarios.sd24_i_unidade ";
            $sSql .= "       left  join db_depart         on db_depart.coddepto           = unidades.sd02_i_codigo ";
            $sSql .= "       left  join sau_triagemavulsa on s152_i_cgsund                = cgs_und.z01_i_cgsund ";
            $sSql .= "                                   and s152_d_dataconsulta = '{$dHoje}' ";
            $sSql .= " where ( sd24_v_motivo is null or sd24_v_motivo = '' ) ";
            $sSql .= "   and sd24_c_digitada = 'N' ";
            $sSql .= "   and sd24_i_unidade = " . DB_getsession("DB_coddepto");
            $sSql .= "   and s152_i_codigo is null ";
            $sSql .= "   and sd24_d_cadastro = '{$dHoje}' ";
            $sSql .= " {$sWhere} ";
            $sSql .= " order by sd24_i_codigo";
          }

          $repassa = array();
          if( isset( $chave_sd24_i_codigo ) ) {
            $repassa = array( "chave_sd24_i_codigo" => $chave_sd24_i_codigo );
          }

          $sWhere = implode( " and ", $aWhere );

          $lAutomatico = false;

          if ( $lFiltrou ) {


            $sSql        = $clprontuarios->sql_query( null, $campos, $sOrder, $sWhere );
            $lAutomatico = true;
          }

          db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa, $lAutomatico );
        } else {

          if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

            $result = $clprontuarios->sql_record( $clprontuarios->sql_query( $pesquisa_chave ) );

            if( $clprontuarios->numrows != 0 ) {

              db_fieldsmemory( $result, 0 );
              echo "<script>" . $funcao_js . "('$sd24_i_codigo',false);</script>";
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
</div>
</body>
</html>
<script>
js_tabulacaoforms( "form2", "chave_sd24_i_codigo", true, 1, "chave_sd24_i_codigo", true );

function js_emitelista() {

  jan = window.open(
                     'sau2_triagem001.php?unidade=<?=DB_getsession("DB_coddepto")?>',
                     '',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                   );
  jan.moveTo(0,0);
}
  
function js_limpar(){

  document.form2.chave_sd24_i_codigo.value = "";
  document.form2.chave_sd24_i_ano.value    = "";
  document.form2.chave_sd24_i_mes.value    = "";
  document.form2.chave_sd24_i_seq.value    = "";
  document.form2.chave_z01_v_nome.value    = "";
}

$('chave_z01_v_nome').focus();

$('chave_sd24_i_codigo').className = 'field-size4';
$('chave_data_ini').className      = 'field-size2';
$('chave_sd24_i_ano').className    = 'field-size1';
$('chave_sd24_i_mes').className    = 'field-size1';
$('chave_sd24_i_seq').className    = 'field-size1';
$('chave_data_fim').className      = 'field-size2';
$('chave_z01_v_nome').className    = 'field-size7';
</script>
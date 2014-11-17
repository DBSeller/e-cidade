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

$cltaxa = new cl_taxa;
$cltaxa->rotulo->label("ar36_sequencial");
$cltaxa->rotulo->label("ar36_descricao");
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class="body_default">
  <div class="container">
    <form name="form2" method="post" action="" >
      <fieldset>
        <legend>Filtros</legend>
        <table>
          <tr>
            <td nowrap title="<?=$Tar36_sequencial?>">
              <?=$Lar36_sequencial?>
            </td>
            <td nowrap>
              <?php
              db_input( "ar36_sequencial", 10, $Iar36_sequencial, true, "text", 4, "", "chave_ar36_sequencial" );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tar36_descricao?>">
              <?=$Lar36_descricao?>
            </td>
            <td nowrap>
              <?php
              db_input( "ar36_descricao", 150, $Iar36_descricao, true, "text", 4, "", "chave_ar36_descricao" );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar"    type="button" id="limpar"     value="Limpar" >
      <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_taxa.hide();">
    </form>
  </div>
  <div class="container">
    <table>
      <tr>
        <td>
          <?php
          if( !isset( $pesquisa_chave) ) {

            if( isset( $campos ) == false ) {

              if( file_exists( "funcoes/db_func_taxa.php" ) == true ) {
                include("funcoes/db_func_taxa.php");
              } else {
                $campos = "taxa.*";
              }
            }

            if( isset( $chave_ar36_sequencial ) && ( trim( $chave_ar36_sequencial ) != "" ) ) {
              $sql = $cltaxa->sql_query( $chave_ar36_sequencial, $campos, "ar36_sequencial" );
            } else if( isset( $chave_ar36_descricao ) && ( trim( $chave_ar36_descricao ) != "" ) ) {
              $sql = $cltaxa->sql_query( "", $campos, "ar36_descricao", "ar36_descricao like '{$chave_ar36_descricao}%' " );
            } else {
              $sql = $cltaxa->sql_query("",$campos,"ar36_sequencial","");
            }

            $repassa = array();
            if( isset( $chave_ar36_descricao ) ) {
              $repassa = array( "chave_ar36_sequencial" => $chave_ar36_sequencial, "chave_ar36_descricao" => $chave_ar36_descricao );
            }

            db_lovrot( $sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
          } else {

            if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

              $result = $cltaxa->sql_record( $cltaxa->sql_query( $pesquisa_chave ) );

              if( $cltaxa->numrows != 0 ) {

                db_fieldsmemory( $result, 0 );
                echo "<script>" . $funcao_js . "('{$ar36_descricao}',false);</script>";
              } else {
                echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
              }
            } else {
              echo "<script>" . $funcao_js . "('',false);</script>";
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
$('chave_ar36_sequencial').className = 'field-size2';
$('chave_ar36_descricao').className  = 'field-size7';

$('limpar').onclick = function() {

  $('chave_ar36_sequencial').value = '';
  $('chave_ar36_descricao').value  = '';
}

js_tabulacaoforms( "form2", "chave_ar36_descricao", true, 1, "chave_ar36_descricao", true );
</script>
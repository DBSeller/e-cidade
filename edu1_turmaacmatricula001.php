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

  require("libs/db_stdlibwebseller.php");
  require("libs/db_stdlib.php");
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");
  include("dbforms/db_funcoes.php");
  
  db_postmemory($HTTP_POST_VARS);
  $clturmaacmatricula = new cl_turmaacmatricula;
  $clturmaac          = new cl_turmaac;
  $clmatricula        = new cl_matricula;
  
  $oData = new DBDate(date("d/m/Y"));
  $ed269_d_data_dia = $oData->getDia();
  $ed269_d_data_mes = $oData->getMes();
  $ed269_d_data_ano = $oData->getAno();
  
  $db_opcao = 1;
  $db_botao = true;
  
  if (isset($incluir)) {
    
    db_inicio_transacao();
    $clturmaacmatricula->incluir($ed269_i_codigo);
    db_fim_transacao();
  }
  
  if (isset($alterar)) {
    
    $db_opcao        = 2;
    $oDataConvertida = new DBDate($ed269_d_data);
    
    db_inicio_transacao();
    $clturmaacmatricula->ed269_i_codigo  = $ed269_i_codigo;
    $clturmaacmatricula->ed269_aluno     = $ed269_aluno;
    $clturmaacmatricula->ed269_i_turmaac = $ed269_i_turmaac;
    $clturmaacmatricula->ed269_d_data    = $oDataConvertida->getDate();
    $clturmaacmatricula->alterar($ed269_i_codigo);
    db_fim_transacao();
  }
  
  if (isset($excluir)) {
    
    $db_opcao = 3;
    db_inicio_transacao();
    $clturmaacmatricula->excluir($ed269_i_codigo);
    db_fim_transacao();
  }
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" onLoad="a=1" >
    <?include("forms/db_frmturmaacmatricula.php");?>
  </body>
</html>

<script>
js_tabulacaoforms("form1","ed60_matricula",true,1,"ed60_matricula",true);
</script>

<?
  if (isset($incluir)) {

    if ($clturmaacmatricula->erro_status == "0") {

      $clturmaacmatricula->erro(true, false);
      $db_botao = true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      
      if ($clturmaacmatricula->erro_campo != "") {

        echo "<script> document.form1.".$clturmaacmatricula->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clturmaacmatricula->erro_campo.".focus();</script>";
      }
    } else {

      $clturmaacmatricula->erro(true, false);
      $result_qtd = $clturmaacmatricula->sql_record($clturmaacmatricula->sql_query_turma(""," count(*) as qtdmatricula",""," ed269_i_turmaac = $ed269_i_turmaac"));
      db_fieldsmemory($result_qtd,0);
      $qtdmatricula = $qtdmatricula == "" ? 0 : $qtdmatricula;
      
      $sql1 = "UPDATE turmaac SET
                ed268_i_nummatr = $qtdmatricula
               WHERE ed268_i_codigo = $ed269_i_turmaac
               ";
      $query1 = db_query($sql1);
?>
      <script>
        top.corpo.iframe_a1.location.href='edu1_turmaac002.php?chavepesquisa=<?=$ed269_i_turmaac?>';
      </script>
<?
      db_redireciona("edu1_turmaacmatricula001.php?ed269_i_turmaac=$ed269_i_turmaac&ed268_c_descr=$ed268_c_descr&ed268_i_calendario=$ed268_i_calendario&ed268_i_tipoatend=$ed268_i_tipoatend");
    }
  }
  
  if (isset($excluir)) {

   if ($clturmaacmatricula->erro_status == "0") {
    $clturmaacmatricula->erro(true, false);
   } else {

    $clturmaacmatricula->erro(true, false);
    $result_qtd = $clturmaacmatricula->sql_record($clturmaacmatricula->sql_query_turma(""," count(*) as qtdmatricula",""," ed269_i_turmaac = $ed269_i_turmaac"));
    db_fieldsmemory($result_qtd,0);
    $qtdmatricula = $qtdmatricula == "" ? 0 : $qtdmatricula;
    
    $sql1 = "UPDATE turmaac SET
              ed268_i_nummatr = $qtdmatricula
             WHERE ed268_i_codigo = $ed269_i_turmaac
             ";
    $query1 = db_query($sql1);
?>
    <script>
      top.corpo.iframe_a1.location.href='edu1_turmaac002.php?chavepesquisa=<?=$ed269_i_turmaac?>';
    </script>
<?
      db_redireciona("edu1_turmaacmatricula001.php?ed269_i_turmaac=$ed269_i_turmaac&ed268_c_descr=$ed268_c_descr&ed268_i_calendario=$ed268_i_calendario&ed268_i_tipoatend=$ed268_i_tipoatend");
    }
  }
  
  if (isset($alterar)) {
    
    if ($clturmaacmatricula->erro_status == "0") {
      $clturmaacmatricula->erro(true, false);
    } else {
      db_redireciona("edu1_turmaacmatricula001.php?ed269_i_turmaac=$ed269_i_turmaac&ed268_c_descr=$ed268_c_descr&ed268_i_calendario=$ed268_i_calendario&ed268_i_tipoatend=$ed268_i_tipoatend");
    }  
  }
  
  if (isset($cancelar)) {
    db_redireciona("edu1_turmaacmatricula001.php?ed269_i_turmaac=$ed269_i_turmaac&ed268_c_descr=$ed268_c_descr&ed52_c_descr=$ed52_c_descr&ed268_i_calendario=$ed268_i_calendario&ed268_i_tipoatend=$ed268_i_tipoatend");
  }
?>
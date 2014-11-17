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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once ("fpdf151/pdf.php");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load('scripts.js, prototype.js, strings.js, arrays.js');
  db_app::load('estilos.css');
?>

</head>
<body bgcolor="#cccccc">
  <div class="container">
    <form method="post" action="" enctype="multipart/form-data">
      <fieldset>
       <legend>Importar Situação do Aluno</legend>

         <table class="form-container">

           <tr>
             <td>Ano:</td>
             <td>
               <select name="ano">
                 <?php
                   $iAnoUsu      = db_getsession("DB_anousu");
                   $iAnoAnterior = $iAnoUsu - 1;
                   echo "<option value='{$iAnoUsu}'>{$iAnoUsu}</option>";
                   echo "<option value='{$iAnoAnterior}' selected>{$iAnoAnterior}</option>";
                 ?>
               </select>
             </td>
           </tr>

           <tr>
             <td>Arquivo:</td>
             <td>
               <input type="file" name="arquivo" id="arquivo">
             </td>
           </tr>

         </table>

       </fieldset>
       <input type="submit" id="btnImportarSituacaoAluno" value="Processar">
    </form>
  </div>
</body>
</html>

<?php 
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); 

if (isset($_FILES) && !empty($_FILES["arquivo"]["tmp_name"])) {

  db_inicio_transacao();

  try {

    switch ($_POST["ano"]) {

    	case 2013:

    	  $sCaminhoArquivo                   = "tmp/importacao_situacao_aluno_2013.json";
    	  $oJSON                             = new DBLogJSON( $sCaminhoArquivo );
    	  $oImportacaoSituacaoAlunoCenso2013 = new ImportacaoSituacaoAlunoCenso2013($_FILES["arquivo"]["tmp_name"], $oJSON);
    	  $oImportacaoSituacaoAlunoCenso2013->importarArquivo();

    	  $sMensagem = "Arquivo do censo importado com sucesso.";
    	  if ( $oImportacaoSituacaoAlunoCenso2013->encontrouErro() ) {
          $sMensagem .= " Contudo, foram encontrados erros em determinados dados. Verifique o log.";
        }

    	  db_fim_transacao();
    	  
    	  db_msgbox($sMensagem);
    	  
    	  $iAno       = ImportacaoSituacaoAlunoCenso2013::ANO_ARQUIVO;
    	  $sVariaveis = "iAno={$iAno}&sCaminhoArquivo={$sCaminhoArquivo}";
    	  
    	  $sScript  = "<script>";
    	  $sScript .= "  window.location='edu4_importacaosituacaoaluno002.php?{$sVariaveis}'";
    	  $sScript .= "</script>";
    	  echo $sScript;

    	  break;
    }
  } catch (Exception $oErro) {

    db_msgbox($oErro->getMessage());
    db_fim_transacao(true);
  }
}
?>
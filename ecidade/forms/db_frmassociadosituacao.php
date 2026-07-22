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

/**
 * MODULO: Fumam
 */
$oDaoAssociadosituacao->rotulo->label();

if ($db_opcao == 1) {
  $sNameBotaoProcessar = "incluir";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $sNameBotaoProcessar = "alterar";
} else {
  $sNameBotaoProcessar = "excluir";
}
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend><?php echo ucfirst($sNameBotaoProcessar); ?> Associado Situacao</legend>
          <table>
            <tr>
              <td nowrap title="<?php echo $Tfm02_situacao; ?>" >
                <label class="bold" for="fm02_situacao" id="lbl_fm02_situacao"><?php echo $Sfm02_situacao; ?>:</label>
              </td>
              <td>
                <?php
                  db_input('fm02_situacao', 10, $Ifm02_situacao, true, 'text', 3, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm02_descricao; ?>" >
                <label class="bold" for="fm02_descricao" id="lbl_fm02_descricao"><?php echo $Sfm02_descricao; ?>:</label>
              </td>
              <td>
                <?php db_input('fm02_descricao', 50, $Ifm02_descricao, true, 'text', $db_opcao, ""); ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> >
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
      </form>
    </div>
    <?php db_menu(); ?>
  </body>
  <script>

    function js_pesquisa() {
      js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                           'db_iframe_associadosituacao', 
                           'func_associadosituacao.php?funcao_js=parent.js_preenchepesquisa|fm02_situacao', 
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_associadosituacao.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>

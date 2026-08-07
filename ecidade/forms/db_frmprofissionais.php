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
 * MODULO: fumam
 */

$oDaoProfissionais->rotulo->label();

$oRotulo = new rotulocampo;
$oRotulo->label("rh70_descr");
$oRotulo->label("sd51_v_descricao");

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
          <legend><?php echo ucfirst($sNameBotaoProcessar); ?> Profissionais</legend>
          <table>
            <tr>
              <td nowrap title="<?php echo $Tfm15_codigo; ?>" >
                <label class="bold" for="fm15_codigo" id="lbl_fm15_codigo"><?php echo $Sfm15_codigo; ?>:</label>
              </td>
              <td>
                <?php db_input('fm15_codigo', 10, $Ifm15_codigo, true, 'text', 3, ""); ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm15_nome; ?>" >
                <label class="bold" for="fm15_nome" id="lbl_fm15_nome"><?php echo $Sfm15_nome; ?>:</label>
              </td>
              <td>
                <?php db_input('fm15_nome', 55, $Ifm15_nome, true, 'text', $db_opcao, ""); ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm15_cpf; ?>" >
                <label class="bold" for="fm15_cpf" id="lbl_fm15_cpf"><?php echo $Sfm15_cpf; ?>:</label>
              </td>
              <td>
                <?php
                  if (isset($fm15_cpf) && !empty($fm15_cpf)) {
                     $fm15_cpf = db_formatar($fm15_cpf, 'CPF');
                  }

                  db_input('fm15_cpf', 11, $Ifm15_cpf, true, 'text', $db_opcao, " onblur='js_validacpf();' ");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm15_cbo; ?>" >
                <label class="bold" for="fm15_cbo" id="lbl_fm15_cbo">
                  <?php db_ancora( $Sfm15_cbo . ':', "js_pesquisafm15_cbo(true);", $db_opcao); ?>
                </label>
              </td>
              <td>
                <?php db_input('fm15_cbo', 10, $Ifm15_cbo, true, 'text', $db_opcao, " onchange='js_pesquisafm15_cbo(false);' onblur='js_verificacbo();' "); ?>
                <?php db_input('rh70_descr', 40, $Irh70_descr, true, 'text', 3, ''); ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm15_regprof; ?>" >
                <label class="bold" for="fm15_regprof" id="lbl_fm15_regprof"><?php echo $Sfm15_regprof; ?>:</label>
              </td>
              <td>
                <?php db_input('fm15_regprof', 20, $Ifm15_regprof, true, 'text', $db_opcao, ""); ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm15_orgaoemissor; ?>" >
                <label class="bold" for="fm15_orgaoemissor" id="lbl_fm15_orgaoemissor">
                  <?php db_ancora( $Sfm15_orgaoemissor . ':', "js_pesquisafm15_orgaoemissor(true);", $db_opcao); ?>
                </label>
              </td>
              <td>
                <?php db_input('fm15_orgaoemissor', 10, $Ifm15_orgaoemissor, true, 'text', $db_opcao," onchange='js_pesquisafm15_orgaoemissor(false);'"); ?>
                <?php db_input('sd51_v_descricao', 40, $Isd51_v_descricao, true, 'text', 3, ''); ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> >
        <?php
          if ($db_opcao > 1) {
        ?>
             <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
        <?php
          }
        ?>
      </form>
    </div>
    <?php db_menu(); ?>
  </body>
  <script>

    function js_verificacbo() {
      if (document.form1.fm15_cbo.value.length == 0) {
         document.form1.rh70_descr.value = '';
      }
    }

    function js_validacpf() {
      var oCpf = document.getElementById('fm15_cpf');
      if (oCpf.value.length > 0) {
         var sCpf = oCpf.value;
         oCpf.value = oCpf.value.replace(/[^0-9]/g, '');

         if (!validaCPF(oCpf)) {
            alert("CPF inválido, verifique.");
            return false;
         }
         oCpf.value = mascaraCpf(sCpf);
      }
    }

    function js_pesquisafm15_cbo(lExibeJanela) {

      if (lExibeJanela) {
         js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                              'db_iframe_rhcbo', 
                              'func_rhcbo.php?funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|rh70_descr|rh70_estrutural',
                              'Pesquisa', true);
      } else {
        if (document.form1.fm15_cbo.value != '') {
           js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                                'db_iframe_rhcbo', 
                                'func_rhcbo.php?lCadastroCgm=true&pesquisa_chave=' + document.form1.fm15_cbo.value + '&funcao_js=parent.js_mostrarhcbo', 
                                'Pesquisa', false);
        }
      }
    }

    function js_mostrarhcbo(sChave, sDescricao, sEstrutural, lErro) {

      document.form1.fm15_cbo.value = sChave;
      document.form1.rh70_descr.value = sEstrutural +' - '+ sDescricao;

      if (lErro) {
        document.form1.fm15_cbo.focus();
        document.form1.fm15_cbo.value = '';
      }
    }

    function js_mostrarhcbo1(sChave, sDescricao, sEstrutural) {

      document.form1.fm15_cbo.value = sChave;
      document.form1.rh70_descr.value = sEstrutural +' - '+ sDescricao;
      db_iframe_rhcbo.hide();
    }

    function js_pesquisafm15_orgaoemissor(lExibeJanela) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( '',
                             'db_iframe_sau_orgaoemissor',
                             'func_sau_orgaoemissor.php?funcao_js=parent.js_mostrasau_orgaoemissor1|sd51_i_codigo|sd51_v_descricao',
                             'Pesquisa', true);
      } else {
        if (document.form1.fm15_orgaoemissor.value != '') {
          js_OpenJanelaIframe( '',
                               'db_iframe_sau_orgaoemissor',
                               'func_sau_orgaoemissor.php?pesquisa_chave=' + document.form1.fm15_orgaoemissor.value + '&funcao_js=parent.js_mostrasau_orgaoemissor',
                               'Pesquisa', false);
        } else {
          document.form1.fm15_orgaoemissor.value = ''; 
        }
      }
    }

    function js_mostrasau_orgaoemissor(sChave, lErro) {

      document.form1.sd51_v_descricao.value = sChave;

      if (lErro) {
        document.form1.fm15_orgaoemissor.focus();
        document.form1.fm15_orgaoemissor.value = '';
      }
    }

    function js_mostrasau_orgaoemissor1(sChave, sDescricao) {

      document.form1.fm15_orgaoemissor.value = sChave;
      document.form1.sd51_v_descricao.value = sDescricao;
      db_iframe_sau_orgaoemissor.hide();
    }

    function js_pesquisa() {
      js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                           'db_iframe_profissionais', 
                           'func_profissionais.php?funcao_js=parent.js_preenchepesquisa|fm15_codigo', 
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_profissionais.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>

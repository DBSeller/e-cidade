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

$clprofissionaisprestadores->rotulo->label();
$clprofissionais->rotulo->label();

$clrotulo = new rotulocampo;

if (!isset($fm07_prestador_cgm)) {
   $fm07_prestador_cgm = '';
}

if (!isset($fm07_prestador)) {
   $fm07_prestador = '';
}

if (!isset($nome)) {
   $nome = '';
}

if (!isset($sd51_v_descricao)) {
   $sd51_v_descricao = '';
}

if (!isset($db_botao)) {
   $db_botao = '';
}

if (!isset($fm07_codigo)) {
   $fm07_codigo = '';
}

if (!isset($fm15_nome)) {
   $fm15_nome = '';
}

if (!isset($fm15_cpf)) {
   $fm15_cpf = '';
}

if (!isset($fm15_cbo)) {
  $fm15_cbo = '';
}

if (!isset($rh70_descr)) {
  $rh70_descr = '';
}

if (!isset($fm15_orgaoemissor)) {
  $fm15_orgaoemissor = '';
}

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
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post">
        <fieldset>
          <legend>Profissional</legend>
          <table>
            <tr>
              <td nowrap title="<?php echo $Tfm07_codigo; ?>" >
                <label class="bold" for="fm07_codigo" id="lbl_fm07_codigo"><?php echo $Lfm07_codigo; ?> </label>
              </td>
              <td>
                <?php
                  db_input('fm07_codigo', 15, $Ifm07_codigo, true, 'text', 3,"");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm07_prestador; ?>" >
                <label class="bold" for="fm07_prestador" id="lbl_fm07_prestador">Prestador:</label>
              </td>
              <td>
                <?php
                  db_input('fm07_prestador', 15, $Ifm07_prestador, true, 'hidden', 3,"");
                  db_input('fm07_prestador_cgm', 15, $fm07_prestador_cgm, true, 'text', 3,"");
                  db_input('nome',50,$nome, true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm07_profissional;?>">
                <?php
                  db_ancora($Lfm07_profissional,"js_pesquisa_profissional(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?php
                  db_input('fm07_profissional',15, $Ifm07_profissional,true,'text',$db_opcao," onchange='js_pesquisa_profissional(false);'");
                  db_input('fm15_nome',50,$fm15_nome,true,'text',3,'');
                ?>
              </td>
            </tr>  
            <tr>
              <td nowrap title="<?php echo $Tfm15_cpf;?>" >
                <label class="bold" for="fm15_cpf" id="lbl_fm15_cpf"><?php echo $Lfm15_cpf; ?> </label>
              </td>
              <td>
                <?php
                  if (isset($fm15_cpf) && !empty($fm15_cpf)) {
                     $fm15_cpf = db_formatar($fm15_cpf, 'CPF');
                  }
                  db_input('fm15_cpf', 15, $fm15_cpf, true, 'text', 3,"");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm15_cbo;?>" >
                <label class="bold" for="fm15_cbo" id="lbl_fm15_cbo"><?php echo $Lfm15_cbo; ?> </label>
              </td>
              <td>
                <?php
                db_input("fm15_cbo", 15, $fm15_cbo, true, "text", 3, "");
                db_input("rh70_descr", 50, $rh70_descr,  true, "text", 3, "");
                ?>
              </td>
            </tr>                        
            <tr>
              <td nowrap title="<?php echo $Tfm15_regprof; ?>" >
                <label class="bold" for="fm15_regprof" id="lbl_fm15_regprof"><?php echo $Lfm15_regprof; ?> </label>
              </td>
              <td>
                <?php db_input('fm15_regprof', 15, $Ifm15_regprof, true, 'text', 3, ""); ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm15_orgaoemissor;?>" >
                <label class="bold" for="fm15_orgaoemissor" id="lbl_fm15_orgaoemissor"><?php echo $Lfm15_orgaoemissor; ?> </label>
              </td>
              <td>
                <?php
                  db_input('fm15_orgaoemissor', 15, $fm15_orgaoemissor, true, 'text', 3, "");
                  db_input('sd51_v_descricao', 50, $sd51_v_descricao, true, 'text', 3, '');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm07_situacao; ?>" >
                <label class="bold" for="fm07_situacao" id="lbl_fm07_situacao"><?php echo $Lfm07_situacao; ?> </label>
              </td>
              <td>
                <?php
                  $aSituacao = array("t" => "ATIVO", "f" => "INATIVO");
                  db_select('fm07_situacao', $aSituacao, true, ($db_opcao==3?3:1));
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> >
        <input id="cancelar" name="cancelar" type="button" value="Cancelar" onclick="return js_cancelar();">

      <table>
        <tr>
          <td valign="top"  align="center">  
          <?php
            if (isset($fm07_prestador) && !empty($fm07_prestador)) {
               $sCampos = "fm07_codigo, fm15_nome, ";
               $sCampos .= " case when fm07_situacao is true then 'ATIVO' else 'INATIVO' end as fm07_situacao ";
               $chavepri= array("fm07_codigo"=>@$fm07_codigo);
               $cliframe_alterar_excluir->iframe_nome = "frm_prestadores";
               $cliframe_alterar_excluir->chavepri      = $chavepri;
               $cliframe_alterar_excluir->sql           = $clprofissionaisprestadores->sql_query(null,$sCampos,"fm07_codigo","fm07_prestador = $fm07_prestador");
               $cliframe_alterar_excluir->campos        = "fm07_codigo, fm15_nome, fm07_situacao";
               $cliframe_alterar_excluir->legenda       = "Registros";
               $cliframe_alterar_excluir->tamfontecabec = 11;
               $cliframe_alterar_excluir->tamfontecorpo = 10;
               $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
            }
          ?>
          </td>
        </tr>
      </table>
      </form>
    </div>
    <?php db_menu(); ?>
  </body>
  <script>
    function js_cancelar() {

      document.form1.fm07_codigo.value = '';
      document.form1.fm07_profissional.value = '';
      document.form1.fm15_nome.value = '';
      document.form1.fm15_cpf.value = '';
      document.form1.fm15_orgaoemissor.value = '';
      document.form1.sd51_v_descricao.value = '';
      document.form1.fm15_regprof.value = '';
      document.form1.fm15_cbo.value = '';
      document.form1.rh70_descr.value = '';

      frm_prestadores.location.reload();     
    }

    function js_pesquisa() {
      js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                           'db_iframe_profissionaisprestadores', 
                           'func_profissionaisprestadores.php?funcao_js=parent.js_preenchepesquisa|0', 
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {
      db_iframe_profissionaisprestadores.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    function js_pesquisa_profissional(mostra) {
      if (mostra == true) {

          js_OpenJanelaIframe('',
                              'db_iframe_nome',
                              'func_profissionais.php?funcao_js=parent.js_mostraprofissional1|fm15_codigo|fm15_nome|fm15_cpf|fm15_orgaoemissor|sd51_v_descricao|fm15_regprof|fm15_cbo|rh70_descr',
                              'Pesquisa', true);
      } else {
          if (fm07_profissional.value != '') {
             js_OpenJanelaIframe('',
                                 'db_iframe_nome',
                                 'func_profissionais.php?pesquisa_chave=' + fm07_profissional.value + '&funcao_js=parent.js_mostraprofissional',
                                 'Pesquisa', false);
          } else {
             fm07_profissional.value = '';
          }
      }
    }

    function js_mostraprofissional(chave1, chave2, chave3, chave4, chave5, chave6, chave7, erro) {

        if (erro == true) {
           document.form1.fm07_profissional.focus();
           document.form1.fm07_profissional.value = '';
           return;
        }

        document.form1.fm15_nome.value = chave1;
        document.form1.fm15_cpf.value = js_formatar(chave2, "cpfcnpj");
        document.form1.fm15_cbo.value = chave3;
        document.form1.rh70_descr.value = chave4;
        document.form1.fm15_regprof.value = chave5;
        document.form1.fm15_orgaoemissor.value = chave6;
        document.form1.sd51_v_descricao.value = chave7;
    }

    function js_mostraprofissional1(chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8) {
  
        document.form1.fm07_profissional.value = chave1;
        document.form1.fm15_nome.value = chave2;
        document.form1.fm15_cpf.value = js_formatar(chave3, "cpfcnpj");
        document.form1.fm15_orgaoemissor.value = chave4;
        document.form1.sd51_v_descricao.value = chave5;
        document.form1.fm15_regprof.value = chave6;
        document.form1.fm15_cbo.value = chave7;
        document.form1.rh70_descr.value = chave8;
        db_iframe_nome.hide();
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>

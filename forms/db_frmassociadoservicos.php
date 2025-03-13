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

$oDaoAssociadoservicos->rotulo->label();
$oRotulo = new rotulocampo;

$oRotulo->label("fm02_situacao");
$oRotulo->label("fm09_descricao");

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
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default" onload="js_carregaTela()">
    <div class="container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend><b>Serviços</b></legend>
          <table>
            <tr>
              <td nowrap title="<?php echo $Tfm12_codigo; ?>" >
                <label class="bold" for="fm12_codigo" id="lbl_fm12_codigo"><?php echo $Sfm12_codigo; ?>:</label>
              </td>
              <td>
                <?php
                  db_input('fm12_codigo', $Mfm12_codigo, $Ifm12_codigo, true, 'text', 3, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm12_tpservico; ?>" >
                <label class="bold" for="fm12_tpservico" id="lbl_fm12_tpservico">
                  <?php
                    db_ancora($Sfm12_tpservico . ':', "js_pesquisafm12_tpservico(true);", $db_opcao);
                  ?>
                </label>
              </td>
              <td>
                <?php
                  db_input('fm12_tpservico', $Mfm12_tpservico, $Ifm12_tpservico, true, 'text', $db_opcao," onchange='js_pesquisafm12_tpservico(false);'");
                  db_input('fm09_descricao', 50, $Ifm09_descricao, true, 'text', 3, '');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm12_descricao; ?>" >
                <label class="bold" for="fm12_descricao" id="lbl_fm12_descricao"><?php echo $Sfm12_descricao; ?>:</label>
              </td>
              <td>
                <?php db_input('fm12_descricao', 50, $Ifm12_descricao, true, 'text', $db_opcao, ""); ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm12_situacao; ?>">
                <label class="bold" for="fm12_situacao" id="lbl_fm12_situacao"><?php echo $Sfm12_situacao; ?>:</label>
              </td>
              <td>
                <?php
                  $aSituacao = array("t" => "ATIVO", "f" => "INATIVO");
                  db_select('fm12_situacao', $aSituacao, true, ($db_opcao==3?3:1), "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm12_autorizacao; ?>" >
                <label class="bold" for="fm12_autorizacao" id="lbl_fm12_autorizacao"><?php echo $Sfm12_autorizacao; ?>:</label>
              </td>
              <td>
                <?php
                  $aAutoriza = array("f" => "NAO", "t" => "SIM");
                  db_select('fm12_autorizacao', $aAutoriza, true, $db_opcao, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm12_odontograma; ?>" >
                <label class="bold" for="fm12_odontograma" id="lbl_fm12_odontograma"><?php echo $Sfm12_odontograma; ?>:</label>
              </td>
              <td>
                <?php
                  $aOdontograma = array("f" => "NAO", "t" => "SIM");
                  db_select('fm12_odontograma', $aOdontograma, true, $db_opcao, "");
                ?>
              </td>
            </tr>

            <table>
            <tr>
              <td colspan='2' nowrap>
                <fieldset style='width: 55%; display: inline;'>
                  <legend><b>Idades</b></legend>
                  <table width='100%'>
                    <tr>
                      <td nowrap title="<?php echo $Tfm12_idademin; ?>" >
                        <label class="bold" for="fm12_idademin" id="lbl_fm12_idademin"><?php echo $Sfm12_idademin; ?>:</label>
                      </td>
                      <td>
                        <?php
                          db_input('fm12_idademin', 10, $Ifm12_idademin, true, 'hidden', $db_opcao,"");
                          db_input('idademin', 10, $Ifm12_idademin, true, 'text', $db_opcao,"");
                          $aX = array('3'=>'ANOS', '2'=>'MESES', '1'=>'DIAS');
                          db_select('undidadeini', $aX, true, $db_opcao, '');
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?php echo $Tfm12_idademax; ?>" >
                        <label class="bold" for="fm12_idademax" id="lbl_fm12_idademax"><?php echo $Sfm12_idademax; ?>:</label>
                      </td>
                      <td>
                        <?php
                          db_input('fm12_idademax', 10, $Ifm12_idademax, true, 'hidden', $db_opcao,"");
                          db_input('idademax', 10, $Ifm12_idademax, true, 'text', $db_opcao,"");
                          $aX = array('3'=>'ANOS', '2'=>'MESES', '1'=>'DIAS');
                          db_select('undidadefim', $aX, true, $db_opcao, '');
                        ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>
                <fieldset style='width: 55%; display: inline;'>
                  <legend><b>Validade</b></legend>
                  <table width='100%'>
                    <tr>
                      <td nowrap title="<?php echo $Tfm12_validadeini; ?>" >
                        <label class="bold" for="fm12_validadeini" id="lbl_fm12_validadeini"><?php echo $Sfm12_validadeini; ?>:</label>
                      </td>
                      <td>
                        <?php db_inputdata( 'fm12_validadeini',
                                            @$fm12_validadeini_dia,
                                            @$fm12_validadeini_mes,
                                            @$fm12_validadeini_ano, true, 'text', $db_opcao, ""); ?>
                      </td>
                    </tr>
                    <tr>
                      <td nowrap title="<?php echo $Tfm12_validadefim; ?>" >
                        <label class="bold" for="fm12_validadefim" id="lbl_fm12_validadefim"><?php echo $Sfm12_validadefim; ?>:</label>
                      </td>
                      <td>
                        <?php db_inputdata( 'fm12_validadefim',
                                            @$fm12_validadefim_dia,
                                            @$fm12_validadefim_mes,
                                            @$fm12_validadefim_ano, true, 'text', $db_opcao, "onchange='js_validaData();'"); ?>
                      </td>
                    </tr>
                    </table>
                </fieldset>
              </td>
            </tr>
            </table>

            <tr>
              <td nowrap title="Sexo" >
                <label class="bold" for="sexo" id="lbl_sexo">Sexo:</label>
              </td>
              <td>
                <?php
                  db_input('fm12_masculino', $Mfm12_masculino, $Ifm12_masculino, true, 'checkbox', $db_opcao, "", "fm12_masculino");
                ?>
              </td>
              <td nowrap title="<?php echo $Tfm12_masculino; ?>" >
                <label class="bold" for="fm12_masculino" id="lbl_fm12_masculino"><?php echo $Sfm12_masculino; ?></label>
              </td>
              <td>
                <?php
                  db_input('fm12_feminino', $Mfm12_feminino, $Ifm12_feminino, true, 'checkbox', $db_opcao, "", "fm12_feminino");
                ?>
              </td>
              <td nowrap title="<?php echo $Tfm12_feminino; ?>" >
                <label class="bold" for="fm12_feminino" id="lbl_fm12_feminino"><?php echo $Sfm12_feminino; ?></label>
              </td>
            </tr>

          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>"
                     <?php echo (!$db_botao ? "disabled" : ""); ?> onclick="return js_montastr()" >
        <?php if ($db_opcao != 1) { ?>
          <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
        <?php } else { ?>
          <input name="limpar" type="button" id="limpar" value="Limpar Campos" onclick="js_limpar();" >
        <?php } ?>
      </form>
    </div>
    <?php db_menu(); ?>
  </body>
  <script>

    function js_limpar() {
      document.getElementById('db_opcao').disabled = false;
      parent.document.formaba.valorservico.disabled = true;
      location.href = 'fum4_associadoservicos004.php?db_opcao=1';
    }


    function js_carregaTela() {

      if (document.getElementById('fm12_odontograma').readOnly == true) {
         document.getElementById('fm12_masculino').disabled = true;
         document.getElementById('fm12_feminino').disabled = true;
      }

    }

    function js_validaData() {

      if (document.form1.fm12_validadefim.value != ""  && document.form1.fm12_validadeini.value != "" ) {
         if (document.form1.fm12_validadefim.value < document.form1.fm12_validadeini.value) {
            alert("Data final menor que a data inicial");
            document.form1.fm12_validadefim.value = "";
            document.form1.fm12_validadefim_dia.value = "";
            document.form1.fm12_validadefim_mes.value = "";
            document.form1.fm12_validadefim_ano.value = "";
            return false;
			   }
   		}

      return true;
   	}

    function js_montastr() {

      F = document.form1;
      if (!js_validaIdades()) {
         return false;
      }

      if (!F.fm12_masculino.checked && !F.fm12_feminino.checked) {
         alert('Selecione pelo menos uma op??o de sexo');
         return false;
      }

      if (F.fm12_masculino.checked) {
         F.fm12_masculino.value = true;
      } else {
         F.fm12_masculino.value = false;
      }

      if (F.fm12_feminino.checked) {
         F.fm12_feminino.value = true;
      } else {
        F.fm12_feminino.value = false;
      }

      return true
    }

    function js_validaIdades() {

      oF = document.form1;

      if (oF.idademin.value == ''  || oF.idademax.value == '') {
         alert('Preencha os campos de idade.');
         return false
      }

      if (isNaN(oF.idademin.value) || isNaN(oF.idademax.value) || oF.idademin.value < 0 || oF.idademax.value < 0) {
         alert('Preencha corretamente os campos de idade.');
         return false
      }

      iNdiasmin = parseInt(oF.idademin.value);
      iNdiasmax = parseInt(oF.idademax.value);

      if (parseInt(oF.undidadeini.value) == 2) {
         iNdiasmin *= 30;
      }

      if (parseInt(oF.undidadeini.value) == 3) {
         iNdiasmin *= 365;
      }

      if (parseInt(oF.undidadefim.value) == 2) {
         iNdiasmax *= 30;
      }

      if (parseInt(oF.undidadefim.value) == 3) {
         iNdiasmax *= 365;
      }

      if (iNdiasmax < iNdiasmin) {
         alert('A idade m?xima n?o pode ser menor que a idade m?nima.');
         return false;
      }

      oF.fm12_idademin.value = iNdiasmin;
      oF.fm12_idademax.value = iNdiasmax;

      return true;
    }

    function js_pesquisafm12_tpservico(lExibeJanela) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( '',
                             'db_iframe_associadotiposservicos',
                             'func_associadotiposservicos.php?funcao_js=parent.js_mostraassociadotiposservicos1|fm09_codigo|fm09_descricao',
                             'Pesquisa', true);
      } else {
        if (document.form1.fm12_tpservico.value != '') {
          js_OpenJanelaIframe( '',
                               'db_iframe_associadotiposservicos',
                               'func_associadotiposservicos.php?pesquisa_chave=' + document.form1.fm12_tpservico.value +
                               '&funcao_js=parent.js_mostraassociadotiposservicos',
                               'Pesquisa', false);
        } else {
          document.form1.fm09_descricao.value = '';
        }
      }
    }

    function js_mostraassociadotiposservicos(sChave, lErro) {

      document.form1.fm09_codigo.value = sChave;
      if (lErro) {

        document.form1.fm12_tpservico.focus();
        document.form1.fm12_tpservico.value = '';
      }
    }

    function js_mostraassociadotiposservicos1(sChave, sDescricao) {

      document.form1.fm12_tpservico.value = sChave;
      document.form1.fm09_descricao.value = sDescricao;
      db_iframe_associadotiposservicos.hide();
    }

    function js_pesquisa() {
      js_OpenJanelaIframe( '',
                           'db_iframe_associadoservicos',
                           'func_associadoservicos.php?funcao_js=parent.js_preenchepesquisa|fm12_codigo',
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_associadoservicos.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

  </script>
</html>

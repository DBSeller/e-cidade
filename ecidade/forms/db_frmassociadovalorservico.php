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

$classociadovalorservico->rotulo->label();

$oRotulo = new rotulocampo;
$oRotulo->label("fm09_valor");

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
          <legend>Valor do Serviço</legend>
          <table>
            <tr>
              <td>
              <?php
                  $bPercentual = 'true';
                  if ($fm09_copart_financeiro == 't') {
                     $sSimbolo = 'R$';
                     $bPercentual = 'false';
                  }

                  if ($fm09_copart_percentual == 't') {
                     $sSimbolo = '%';
                  }

                  db_input('fm13_codigo', 10, $Ifm13_codigo, true, 'hidden', 3, '');
                  db_input('fm13_servico', 10, $Ifm13_servico, true, 'hidden', 3, '');
                ?>
                <input name="percentual" type="hidden" id="percentual" value="<?php echo $bPercentual; ?>" >
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm13_valor; ?>" >
                <label class="bold" for="fm13_valor" id="lbl_fm13_valor"><?php echo $Sfm13_valor; ?>:</label>
              </td>
              <td>
                <?php
                  if ( isset($fm13_valor) && $fm13_valor > 0) {
                     $formatter = new NumberFormatter('pt_BR',  NumberFormatter::CURRENCY);
                     $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
                     $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);
                     $formatter->setSymbol(NumberFormatter::CURRENCY_SYMBOL, 'R$');
                     $fm13_valor = $formatter->formatCurrency($fm13_valor, 'BRL');
                     $fm13_valor = utf8_decode($fm13_valor);
                  }

                  db_input('fm13_valor', 15, $Ifm13_valor, true, 'text', $db_opcao, " onkeyup='js_atualizaValor(this)' ");
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="Coparticipação <?php echo $sSimbolo; ?>" >
                <label class="bold" for="" id="">Coparticipação <?php echo $sSimbolo; ?>:</label>
              </td>
              <td>
                <?php
                  db_input('fm09_valor', 15, $Ifm09_valor, true, 'text', 3, "");
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="Valor de Coparticipação" >
                <label class="bold" for="" id="">Valor de Coparticipação:</label>
              </td>
              <td>
                <?php
                  if ( isset($db_coparticipacao) && $db_coparticipacao > 0) {
                     $formatter = new NumberFormatter('pt_BR',  NumberFormatter::CURRENCY);
                     $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
                     $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);
                     $formatter->setSymbol(NumberFormatter::CURRENCY_SYMBOL, 'R$');
                     $db_coparticipacao = $formatter->formatCurrency($db_coparticipacao, 'BRL');
                     $db_coparticipacao = utf8_decode($db_coparticipacao);
                 }

                 db_input('db_coparticipacao', 15, $Ifm13_valor, true, 'text', 3, "");
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Tfm13_vigencia; ?>" >
                <label class="bold" for="fm13_vigencia" id="lbl_fm13_vigencia"><?php echo $Sfm13_vigencia; ?>:</label>
              </td>
              <td>
                <?php db_inputdata( 'fm13_vigencia', 
                                    @$fm13_vigencia_dia,
                                    @$fm13_vigencia_mes,
                                    @$fm13_vigencia_ano, true, 'text', $db_opcao, ""); ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>"
                     <?php echo (!$db_botao ? "disabled" : ""); ?> onclick="return js_valida();">
        <input id="cancelar" name="cancelar" type="button" value="Cancelar" onclick="return js_cancelar();">

        <table>
          <tr>
            <td valign="top"  align="center">
            <?php
              if (isset($fm13_servico) && !empty($fm13_servico)) {
                 $sCampos = "fm13_codigo, fm13_servico, fm13_valor, fm13_vigencia, fm09_valor, ";
                 $sCampos .= "case when fm09_copart_percentual is true ";
                 $sCampos .= "     then round(((fm13_valor * fm09_valor) / 100), 2) ";
                 $sCampos .= "     else fm09_valor ";
                 $sCampos .= "end as db_coparticipacao ";
                 $chavepri= array("fm13_codigo"=>@$fm13_codigo);
                 $cliframe_alterar_excluir->iframe_nome = "frm_valorservico";
                 $cliframe_alterar_excluir->chavepri      = $chavepri;
                 $cliframe_alterar_excluir->sql           = $classociadovalorservico->sql_query(null,$sCampos,"fm13_vigencia desc","fm13_servico = $fm13_servico");
                 $cliframe_alterar_excluir->campos        = "fm13_servico, fm13_codigo, fm13_valor, fm09_valor, db_coparticipacao, fm13_vigencia";
                 $cliframe_alterar_excluir->legenda       = "Registros";
                 $cliframe_alterar_excluir->tamfontecabec = 11;
                 $cliframe_alterar_excluir->tamfontecorpo = 10;
                 $cliframe_alterar_excluir->opcoes        = 2;
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

    function js_valida() {

      if (document.form1.fm13_vigencia.value == "") {
          alert("Data de vigência não pode estar em branco.");
          document.form1.fm13_vigencia.value = "";
          document.form1.fm13_vigencia_dia.value = "";
          document.form1.fm13_vigencia_mes.value = "";
          document.form1.fm13_vigencia_ano.value = "";
          return false;
   		}

      return true;
   	}

    function js_cancelar() {
      document.getElementById('fm13_valor').value = '';
      document.getElementById('fm13_vigencia').value = '';
      document.getElementById('fm13_vigencia_dia').value = '';
      document.getElementById('fm13_vigencia_mes').value = '';
      document.getElementById('fm13_vigencia_ano').value = '';
      frm_valorservico.location.reload();
    }

    function js_atualizaValor(elemento) {
      var coparticip = document.getElementById('db_coparticipacao');
      var percent = document.getElementById('percentual');
      var perccopart = document.getElementById('fm09_valor');
      var valor = elemento.value.replace(/[^0-9]/g, '');
      var options = { style: 'currency',
                      currency: 'BRL',
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2 };

      valor = (valor/100).toFixed(2);
      var valor2 = valor;
      const formatNumber = new Intl.NumberFormat('pt-BR', options);
      valor = formatNumber.format(valor);
      elemento.value = valor;
      
      if (percent.value == 'true') {
             
          const formatNumber = new Intl.NumberFormat('pt-BR', options);
          var valor3 = ((valor2 * perccopart.value) / 100).toFixed(2);
          coparticip.value = formatNumber.format(valor3);
      }
    }

    function js_pesquisafm13_servico(lExibeJanela) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                             'db_iframe_associadosituacao', 
                             'func_associadosituacao.php?funcao_js=parent.js_mostraassociadosituacao1|fm02_situacao|fm02_situacao', 
                             'Pesquisa', true);
      } else {
        if (document.form1.fm13_servico.value != '') {
          js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                               'db_iframe_associadosituacao', 
                               'func_associadosituacao.php?pesquisa_chave=' + document.form1.fm13_servico.value + '&funcao_js=parent.js_mostraassociadosituacao', 
                               'Pesquisa', false);
        } else {
          document.form1.fm02_situacao.value = ''; 
        }
      }
    }

    function js_mostraassociadosituacao(sChave, lErro) {

      document.form1.fm02_situacao.value = sChave;
      if (lErro) {

        document.form1.fm13_servico.focus();
        document.form1.fm13_servico.value = '';
      }
    }

    function js_mostraassociadosituacao1(sChave, sDescricao) {

      document.form1.fm13_servico.value = sChave;
      document.form1.fm02_situacao.value = sDescricao;
      db_iframe_associadosituacao.hide();
    }

    function js_pesquisa() {
      js_OpenJanelaIframe( '', 
                           'db_iframe_associadovalorservico', 
                           'func_associadovalorservico.php?funcao_js=parent.js_preenchepesquisa|0', 
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_associadovalorservico.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>

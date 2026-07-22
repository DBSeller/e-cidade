<?php

/**
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
require_once(modification("classes/db_db_confplan_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$db_opcao = 2;
$db_botao = true;
$q144_ano = db_getsession('DB_anousu');

$cldb_confplan = new cl_db_confplan();
$cldb_confplan->rotulo->label();

$clconfvencissqnvariavel = new cl_confvencissqnvariavel();
$clconfvencissqnvariavel->rotulo->label();

$clrotulo = new rotulocampo();
$clrotulo->label('k02_descr');
$clrotulo->label('k01_descr');
$clrotulo->label('k00_descr');
$clrotulo->label('q92_descr');
$clrotulo->label('j178_receita');
$clrotulo->label('j178_histdebito');
$clrotulo->label('j178_tipodebito');
$clrotulo->label('j178_diavenc');
$clrotulo->label('j178_anousu');
$clrotulo->label('j170_anousu');
$clrotulo->label('j170_receit');
$clrotulo->label('j170_hist');
$clrotulo->label('j170_tipo');
$clrotulo->label('j171_tipoempresa');
//dd($GLOBALS);


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/AjaxRequest.js"></script>
<script type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_carregar();" >
<div class="container">
  <form id="form1" name="form1" method="post" action="">
    <table border="0" align="center" style="text-align: center;">
      <tr>
        <td>
          <fieldset>
            <legend>Configuração da Planilha</legend>
              <fieldset>
                  <legend>ISSQN Nota Avulsa</legend>
                  <table border="0" align="left">
                      <tr id="anoNotaAvulsa">
                          <td nowrap title="<?php echo @$Tj178_anousu?>">
                              <?= @$Lj178_anousu ?>
                          </td>
                          <td>
                              <?php db_input('j178_anousu',6,$Ij178_anousu,true,'text',3); ?>
                          </td>
                      </tr>
                      <tr id="receitaretidoNotaAvulsa">
                          <td nowrap title="<?php echo @$Tj178_receita?>">
                              <?php
                              db_ancora(@$Lj178_receita,"js_pesquisareceitaNotaAvulsa(true);",$db_opcao);
                              ?>
                          </td>
                          <td>
                              <?php
                              db_input('j178_receita',6,$Ij178_receita,true,'text',$db_opcao," onchange='js_pesquisareceitaNotaAvulsa(false);'");
                              db_input('j178_receita_descr',40,null,true,'text',3);
                              ?>
                          </td>
                      </tr>
                      <tr id="historicoretidoNotaAvulsa">
                          <td nowrap title="<?php echo @$Tj178_histdebito?>">
                              <?php
                              db_ancora(@$Lj178_histdebito,"js_pesquisahistoricoNotaAvulsa(true);",$db_opcao);
                              ?>
                          </td>
                          <td>
                              <?php
                              db_input('j178_histdebito',6,$Ij178_histdebito,true,'text',$db_opcao," onchange='js_pesquisahistoricoNotaAvulsa(false);'");
                              db_input('j178_histdebito_descr',40,null,true,'text',3);
                              ?>
                          </td>
                      </tr>
                      <tr id="tipodebitoretidoNotaAvulsa">
                          <td nowrap title="<?php echo @$Tj178_tipodebito?>">
                              <?php
                              db_ancora(@$Lj178_tipodebito,"js_pesquisatipodebitoNotaAvulsa(true);",$db_opcao);
                              ?>
                          </td>
                          <td>
                              <?php
                              db_input('j178_tipodebito',6,$Ij178_tipodebito,true,'text',$db_opcao," onchange='js_pesquisatipodebitoNotaAvulsa(false);'");
                              db_input('j178_tipodebito_descr',40,null,true,'text',3);
                              ?>
                          </td>
                      </tr>
                      <tr>
                          <td>
                              <strong>Vencimento:</strong>
                          </td>
                          <td>
                              <select name="vencimento" id="vencimento" onchange="js_ajustaDataVencimentoNotaAvulsa(this.value)">
                                  <option value="1">Ultimo dia do mês</option>
                                  <option value="2">Dias para vencimento</option>
                              </select>
                          </td>
                      </tr>
                      <tr id="diavencimentoretidoNotaAvulsa" style="display: none">
                          <td nowrap title="<?php echo @$Tj178_diavenc?>">
                              <?php echo @$Lj178_diavenc?>
                          </td>
                          <td>
                              <?php
                              db_input('j178_diavenc',6,$Ij178_diavenc,true,'text',$db_opcao);
                              ?>
                          </td>
                      </tr>
                  </table>
              </fieldset>
            <fieldset>
              <legend>ISSQN Retido</legend>
              <table border="0" align="left">
                <tr id="valorminimoretido">
                  <td nowrap title="<?php echo @$Tw10_valor?>">
                    <input id="w10_oid" name="w10_oid" type="hidden" value="">
                    <?php echo @$Lw10_valor?>
                  </td>
                  <td>
                    <?php
                    db_input('w10_valor',6,$Iw10_valor,true,'text',$db_opcao);
                    ?>
                  </td>
                </tr>
                <tr id="receitaretido">
                  <td nowrap title="<?php echo @$Tw10_receit?>">
                    <?php
                    db_ancora(@$Lw10_receit,"js_pesquisareceita(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('w10_receit',6,$Iw10_receit,true,'text',$db_opcao," onchange='js_pesquisareceita(false);'");
                    db_input('k02_descr',40,$Ik02_descr,true,'text',3,'','k02_descr_retido');
                    ?>
                  </td>
                </tr>
                <tr id="historicoretido">
                  <td nowrap title="<?php echo @$Tw10_hist?>">
                    <?php
                    db_ancora(@$Lw10_hist,"js_pesquisahistorico(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('w10_hist',6,$Iw10_hist,true,'text',$db_opcao," onchange='js_pesquisahistorico(false);'");
                    db_input('k01_descr',40,$Ik01_descr,true,'text',3,'','k01_descr_retido');
                    ?>
                  </td>
                </tr>
                <tr id="tipodebitoretido">
                  <td nowrap title="<?php echo @$Tw10_tipo?>">
                    <?php
                    db_ancora(@$Lw10_tipo,"js_pesquisatipodebito(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('w10_tipo',6,$Iw10_tipo,true,'text',$db_opcao," onchange='js_pesquisatipodebito(false);'");
                    db_input('k00_descr',40,$Ik00_descr,true,'text',3,'','k00_descr_retido');
                    ?>
                  </td>
                </tr>
                <tr id="diavencimentoretido">
                  <td nowrap title="<?php echo @$Tw10_dia?>">
                    <?php echo @$Lw10_dia?>
                  </td>
                  <td>
                    <?php
                    db_input('w10_dia',6,$Iw10_dia,true,'text',$db_opcao);
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend>ISSQN Variável</legend>
              <table border="0" align="left">
                <tr>
                  <td nowrap title="<?php echo @$Tw10_valor?>">
                    <input id="q144_sequencial" name="q144_sequencial" type="hidden" value="">
                    <?php echo @$Lq144_ano?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_ano',6,$Iq144_ano,true,'text',3);
                    ?>
                  </td>
                </tr>
                <tr id="vencimentovariavel">
                  <td nowrap title="<?php echo @$Tq144_codvenc?>">
                    <?php
                    db_ancora(@$Lq144_codvenc,"js_pesquisavencimento(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_codvenc',6,$Iq144_codvenc,true,'text',$db_opcao," onchange='js_pesquisavencimento(false);'");
                    db_input('q92_descr',40,$Iq92_descr,true,'text',3,'','q92_descr_variavel');
                    ?>
                  </td>
                </tr>
                <tr id="receitavariavel">
                  <td nowrap title="<?php echo @$Tq144_receita?>">
                    <?php
                    db_ancora(@$Lq144_receita,"js_pesquisareceitavariavel(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_receita',6,$Iq144_receita,true,'text',$db_opcao," onchange='js_pesquisareceitavariavel(false);'");
                    db_input('k02_descr',40,$Ik02_descr,true,'text',3,'','k02_descr_variavel');
                    ?>
                  </td>
                </tr>
                <tr id="tipodebitovariavel">
                  <td nowrap title="<?php echo @$Tq144_tipo?>">
                    <?php
                    db_ancora($Lq144_tipo,"js_pesquisatipodebitovariavel(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_tipo',6,$Iq144_tipo,true,'text',$db_opcao," onchange='js_pesquisatipodebitovariavel(false);'");
                    db_input('k00_descr',40,$Ik00_descr,true,'text',3,'','k00_descr_variavel');
                    ?>
                  </td>
                </tr>
                <tr id="historicovariavel">
                  <td nowrap title="<?php echo @$Tq144_hist?>">
                    <?php
                    db_ancora(@$Lq144_hist,"js_pesquisahistoricovariavel(true);",$db_opcao);
                    ?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_hist',6,$Iw10_hist,true,'text',$db_opcao," onchange='js_pesquisahistoricovariavel(false);'");
                    db_input('k01_descr',40,$Ik01_descr,true,'text',3,'','k01_descr_variavel');
                    ?>
                  </td>
                </tr>
                <tr id="diavencimentovariavel">
                  <td nowrap title="<?php echo @$Tq144_diavenc?>">
                    <?php echo @$Lq144_diavenc?>
                  </td>
                  <td>
                    <?php
                    db_input('q144_diavenc',6,$Iq144_diavenc,true,'text',$db_opcao);
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
              <fieldset>
                  <legend>ISSQN Retido Empresa Pública</legend>
                  <input id="j170_sequencial" name="j170_sequencial" type="hidden">
                  <table border="0" align="left">
                      <tr>
                          <td nowrap title="<?= @$Tj170_anousu ?>">
                              <?= @$Lj170_anousu ?>
                          </td>
                          <td>
                              <?php
                                  db_input("j170_anousu",6,$Ij170_anousu,true,'text',3);
                              ?>
                          </td>
                      </tr>
                      <tr>
                          <td nowrap title="<?= @$Tj170_receit ?>">
                              <?php
                                  db_ancora(@$Lj170_receit,"js_pesquisaReceitaRetidoEmpresaPublica(true);",$db_opcao);
                              ?>
                          </td>
                          <td>
                              <?php
                                  db_input('j170_receit',6,$Ij170_receit,true,'text',$db_opcao," onchange='js_pesquisaReceitaRetidoEmpresaPublica(false);'");
                                  db_input('k02_descr',40,$Ik02_descr,true,'text',3,'','k02_descr_ret_emp_pub');
                              ?>
                          </td>
                      </tr>
                      <tr id="historicoEmpresaPublica">
                          <td nowrap title="<?php echo @$Tj170_hist?>">
                          <?php
                              db_ancora(@$Lj170_hist,"js_pesquisahistoricoEmpresaPublica(true);",$db_opcao);
                          ?>
                          </td>
                          <td>
                          <?php
                              db_input('j170_hist',6,$Ij170_hist,true,'text',$db_opcao," onchange='js_pesquisahistoricoEmpresaPublica(false);'");
                              db_input('j170_hist_descr',40,$Ij170_hist,true,'text',3,'','j170_hist_descr');
                          ?>
                          </td>
                      </tr>
                      <tr id="tipoDebitoRetidoEmpresaPublica">
                          <td nowrap title="<?php echo @$Tj170_tipo?>">
                          <?php db_ancora($Lj170_tipo,"js_pesquisatipoDebitoRetidoEmpresaPublica(true);",$db_opcao); ?>
                          </td>
                          <td>
                          <?php
                            db_input('j170_tipo',6,$Iq170_tipo,true,'text',$db_opcao," onchange='js_pesquisatipoDebitoRetidoEmpresaPublica(false);'");
                            db_input('j170_tipo_descr',40,$Ik00_descr,true,'text',3,'','j170_tipo_descr');
                          ?>
                          </td>
                      </tr>
                      <tr>
                          <td colspan="2">
                              <fieldset style="margin: 0;">
                                  <table>
                                      <tr>
                                          <td nowrap title="<?= @$Tj171_tipoempresa ?>">
                                              <?php
                                                  db_ancora(@$Lj171_tipoempresa,"js_pesquisaTipoEmpresa(true);",$db_opcao);
                                              ?>
                                          </td>
                                          <td>
                                              <?php
                                                  db_input('j171_tipoempresa',6,$Ij171_tipoempresa,true,'text',$db_opcao," onchange='js_pesquisaTipoEmpresa(false);'");
                                                  db_input('db98_descricao',40,$Ik02_descr,true,'text',3);
                                              ?>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td colspan="2">
                                              <div id="gridTipoEmpresa"></div>
                                          </td>
                                      </tr>
                                  </table>
                              </fieldset>
                          </td>
                      </tr>
                  </table>
              </fieldset>
        </td>
      </tr>
      <tr>
        <td>
          <input type="button" name="db_opcao" id="db_opcao" value="Salvar" <?php echo ($db_botao==false?"disabled":'')?> onclick="js_salvar();">
        </td>
      </tr>
    </table>
  </form>
</div>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  /**
   * Hints do formulario
   */
  var aEventoShow = new Array('onMouseover','onFocus');
  var aEventoHide = new Array('onMouseout' ,'onBlur');

  var oDbHintVencimento = new DBHint('oDbHintVencimento');
  oDbHintVencimento.setText('Nesse campo irá informar o código do vencimento que será vinculado ao valor cálculado de ISSQN.');
  oDbHintVencimento.setShowEvents(aEventoShow);
  oDbHintVencimento.setHideEvents(aEventoHide);
  oDbHintVencimento.make($('vencimentovariavel'));

  var oDbHintReceita = new DBHint('oDbHintReceita');
  oDbHintReceita.setText('Nesse campo irá informar a receita padrão para o cálculo geral de ISSQN.');
  oDbHintReceita.setShowEvents(aEventoShow);
  oDbHintReceita.setHideEvents(aEventoHide);
  oDbHintReceita.make($('receitaretido'));
  oDbHintReceita.make($('receitaretidoNotaAvulsa'));
  oDbHintReceita.make($('receitavariavel'));

  var oDbHintHistoricoCalculo = new DBHint('oDbHintHistoricoCalculo');
  oDbHintHistoricoCalculo.setText('Nesse campo irá informar o histórico de cálculo que será vinculado ao valor cálculado de ISSQN.');
  oDbHintHistoricoCalculo.setShowEvents(aEventoShow);
  oDbHintHistoricoCalculo.setHideEvents(aEventoHide);
  oDbHintHistoricoCalculo.make($('historicoretido'));
  oDbHintHistoricoCalculo.make($('historicoretidoNotaAvulsa'));
  oDbHintHistoricoCalculo.make($('historicovariavel'));
  oDbHintHistoricoCalculo.make($('historicoEmpresaPublica'));

  var oDbHintTipoDebito = new DBHint('oDbHintTipoDebito');
  oDbHintTipoDebito.setText('Nesse campo irá informar o tipo de débito que será vinculado ao valor cálculado de ISSQN.');
  oDbHintTipoDebito.setShowEvents(aEventoShow);
  oDbHintTipoDebito.setHideEvents(aEventoHide);
  oDbHintTipoDebito.make($('tipodebitoretido'));
  oDbHintTipoDebito.make($('tipodebitoretidoNotaAvulsa'));
  oDbHintTipoDebito.make($('tipodebitovariavel'));
  oDbHintTipoDebito.make($('tipoDebitoRetidoEmpresaPublica'));

  var oDbHintDiaVencimento = new DBHint('oDbHintDiaVencimento');
  oDbHintDiaVencimento.setText('Nesse campo irá informar o dia de vencimento para o cálculo geral de ISSQN.');
  oDbHintDiaVencimento.setShowEvents(aEventoShow);
  oDbHintDiaVencimento.setHideEvents(aEventoHide);
  oDbHintDiaVencimento.make($('diavencimentoretido'));
  oDbHintDiaVencimento.make($('diavencimentoretidoNotaAvulsa'));
  oDbHintDiaVencimento.make($('diavencimentovariavel'));

  var oDbHintValorMin = new DBHint('oDbHintValorMin');
  oDbHintValorMin.setText('Nesse campo poderá informar o valor minímo que será lançado para o cálculo geral de ISSQN.');
  oDbHintValorMin.setShowEvents(aEventoShow);
  oDbHintValorMin.setHideEvents(aEventoHide);
  oDbHintValorMin.make($('valorminimoretido'));

  function js_pesquisareceita(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tabrec_retido', 'func_tabrec.php?funcao_js=parent.js_mostrareceita1|k02_codigo|k02_descr', 'Pesquisa', true);
    } else {
      if (document.form1.w10_receit.value != '') {
          js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tabrec_retido', 'func_tabrec.php?pesquisa_chave=' + document.form1.w10_receit.value + '&funcao_js=parent.js_mostrareceita', 'Pesquisa', false);
      } else {
        document.form1.k02_descr_retido.value = '';
      }
    }
  }


  function js_mostrareceita(chave, erro) {
    document.form1.k02_descr_retido.value = chave;
    if (erro == true) {
      document.form1.w10_receit.focus();
      document.form1.w10_receit.value = '';
    }
  }
  function js_mostrareceita1(chave1, chave2) {
    document.form1.w10_receit.value = chave1;
    document.form1.k02_descr_retido.value = chave2;
    db_iframe_tabrec_retido.hide();
  }
  function js_pesquisahistorico(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_histcalc_retido', 'func_histcalc.php?funcao_js=parent.js_mostrahistorico1|k01_codigo|k01_descr', 'Pesquisa', true);
    } else {
      if (document.form1.w10_hist.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_histcalc_retido', 'func_histcalc.php?pesquisa_chave=' + document.form1.w10_hist.value + '&funcao_js=parent.js_mostrahistorico', 'Pesquisa', false);
      } else {
        document.form1.k01_descr_retido.value = '';
      }
    }
  }
  function js_mostrahistorico(chave, erro) {
    document.form1.k01_descr_retido.value = chave;
    if (erro == true) {
      document.form1.w10_hist.focus();
      document.form1.w10_hist.value = '';
    }
  }
  function js_mostrahistorico1(chave1, chave2) {
    document.form1.w10_hist.value = chave1;
    document.form1.k01_descr_retido.value = chave2;
    db_iframe_histcalc_retido.hide();
  }
  function js_pesquisatipodebito(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_arretipo_retido', 'func_arretipo.php?funcao_js=parent.js_mostratipodebito1|k00_tipo|k00_descr', 'Pesquisa', true);
    } else {
      if (document.form1.w10_tipo.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_arretipo_retido', 'func_arretipo.php?pesquisa_chave=' + document.form1.w10_tipo.value + '&funcao_js=parent.js_mostratipodebito', 'Pesquisa', false);
      } else {
        document.form1.k00_descr_retido.value = '';
      }
    }
  }
  function js_mostratipodebito(chave, erro) {
    document.form1.k00_descr_retido.value = chave;
    if (erro == true) {
      document.form1.w10_tipo.focus();
      document.form1.w10_tipo.value = '';
    }
  }
  function js_mostratipodebito1(chave1, chave2) {
    document.form1.w10_tipo.value = chave1;
    document.form1.k00_descr_retido.value = chave2;
    db_iframe_arretipo_retido.hide();
  }
  function js_pesquisavencimento(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cadvencdesc_variavel', 'func_cadvencdesc.php?funcao_js=parent.js_mostravencimento1|q92_codigo|q92_descr', 'Pesquisa', true);
    } else {
      if (document.form1.q144_codvenc.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cadvencdesc_variavel', 'func_cadvencdesc.php?pesquisa_chave=' + document.form1.q144_codvenc.value + '&funcao_js=parent.js_mostravencimento', 'Pesquisa', false);
      } else {
        document.form1.q92_descr_variavel.value = '';
      }
    }
  }
  function js_mostravencimento(chave, erro) {
    document.form1.q92_descr_variavel.value = chave;
    if (erro == true) {
      document.form1.q144_codvenc.focus();
      document.form1.q144_codvenc.value = '';
    }
  }
  function js_mostravencimento1(chave1, chave2) {
    document.form1.q144_codvenc.value = chave1;
    document.form1.q92_descr_variavel.value = chave2;
    db_iframe_cadvencdesc_variavel.hide();
  }
  function js_pesquisareceitavariavel(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tabrec_variavel', 'func_tabrec.php?funcao_js=parent.js_mostrareceitavariavel1|k02_codigo|k02_drecei', 'Pesquisa', true);
    } else {
      if (document.form1.q144_receita.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tabrec_variavel', 'func_tabrec.php?pesquisa_chave=' + document.form1.q144_receita.value + '&funcao_js=parent.js_mostrareceitavariavel', 'Pesquisa', false);
      } else {
        document.form1.k02_descr_variavel.value = '';
      }
    }
  }
  function js_mostrareceitavariavel(chave, erro) {
    document.form1.k02_descr_variavel.value = chave;
    if (erro == true) {
      document.form1.q144_receita.focus();
      document.form1.q144_receita.value = '';
    }
  }
  function js_mostrareceitavariavel1(chave1, chave2) {
    document.form1.q144_receita.value = chave1;
    document.form1.k02_descr_variavel.value = chave2;
    db_iframe_tabrec_variavel.hide();
  }
  function js_pesquisatipodebitovariavel(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_arretipo_variavel', 'func_arretipo.php?funcao_js=parent.js_mostratipodebitovariavel1|k00_tipo|k00_descr', 'Pesquisa', true);
    } else {
      if (document.form1.q144_tipo.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_arretipo_variavel', 'func_arretipo.php?pesquisa_chave=' + document.form1.q144_tipo.value + '&funcao_js=parent.js_mostratipodebitovariavel', 'Pesquisa', false);
      } else {
        document.form1.k00_descr_variavel.value = '';
      }
    }
  }
  function js_mostratipodebitovariavel(chave, erro) {
    document.form1.k00_descr_variavel.value = chave;
    if (erro == true) {
      document.form1.q144_tipo.focus();
      document.form1.q144_tipo.value = '';
    }
  }
  function js_mostratipodebitovariavel1(chave1, chave2) {
    document.form1.q144_tipo.value = chave1;
    document.form1.k00_descr_variavel.value = chave2;
    db_iframe_arretipo_variavel.hide();
  }
  function js_pesquisahistoricovariavel(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_histcalc_variavel', 'func_histcalc.php?funcao_js=parent.js_mostrahistoricovariavel1|k01_codigo|k01_descr', 'Pesquisa', true);
    } else {
      if (document.form1.q144_hist.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_histcalc_variavel', 'func_histcalc.php?pesquisa_chave=' + document.form1.q144_hist.value + '&funcao_js=parent.js_mostrahistoricovariavel', 'Pesquisa', false);
      } else {
        document.form1.k01_descr_variavel.value = '';
      }
    }
  }
  function js_mostrahistoricovariavel(chave, erro) {
    document.form1.k01_descr_variavel.value = chave;
    if (erro == true) {
      document.form1.q144_hist.focus();
      document.form1.q144_hist.value = '';
    }
  }
  function js_mostrahistoricovariavel1(chave1, chave2) {
    document.form1.q144_hist.value = chave1;
    document.form1.k01_descr_variavel.value = chave2;
    db_iframe_histcalc_variavel.hide();
  }
  function js_salvar() {

    var oParametro = {
      'exec'            : 'salvar',
      'w10_oid'         : $('w10_oid').value,
      'w10_valor'       : $('w10_valor').value,
      'w10_receit'      : $('w10_receit').value,
      'w10_hist'        : $('w10_hist').value,
      'w10_tipo'        : $('w10_tipo').value,
      'w10_dia'         : $('w10_dia').value,
      'q144_sequencial' : $('q144_sequencial').value,
      'q144_ano'        : $('q144_ano').value,
      'q144_codvenc'    : $('q144_codvenc').value,
      'q144_receita'    : $('q144_receita').value,
      'q144_tipo'       : $('q144_tipo').value,
      'q144_hist'       : $('q144_hist').value,
      'q144_diavenc'    : $('q144_diavenc').value,
      'j178_receita'    : $('j178_receita').value,
      'j178_histdebito' : $('j178_histdebito').value,
      'j178_tipodebito' : $('j178_tipodebito').value,
      'j178_diavenc'    : $('j178_diavenc').value,
      'j178_anousu'     : $('j178_anousu').value,
      'j170_sequencial' : $('j170_sequencial').value,
      'j170_anousu'     : $('j170_anousu').value,
      'j170_receit'     : $('j170_receit').value,
      'j170_hist'       : $('j170_hist').value,
      'j170_tipo'       : $('j170_tipo').value,
      'aTiposEmpresa'   : JSON.stringify(aTiposEmpresa)
    };

    new AjaxRequest('pre4_db_confplan.RPC.php', oParametro,
      function (oRetorno, lErro) {
        if (lErro) {
          alert(oRetorno.message.urlDecode());
          return false;
        } else {
          alert(oRetorno.message.urlDecode());
          js_carregar();
        }
      }
    ).setMessage('Buscando configurações...').execute();
  }
  function js_carregar() {


    new AjaxRequest('pre4_db_confplan.RPC.php', {'exec' : 'pesquisar'},
      function (oRetorno, lErro) {

          if (lErro) {
          alert(oRetorno.message.urlDecode());
          return false;
        } else {
          oRetorno.aDados.each(
            function (oCampo) {
                // ISSQN Retido
              if (oCampo.oid) {
                $('w10_oid').value    = oCampo.oid;
                $('w10_valor').value  = oCampo.w10_valor;
                $('w10_receit').value = oCampo.w10_receit;
                $('w10_hist').value   = oCampo.w10_hist;
                $('w10_tipo').value   = oCampo.w10_tipo;
                $('w10_dia').value    = oCampo.w10_dia;
              }
              // ISSQN Variável
              if (oCampo.q144_sequencial) {
                $('q144_sequencial').value = oCampo.q144_sequencial;
                $('q144_ano').value        = oCampo.q144_ano;
                $('q144_codvenc').value    = oCampo.q144_codvenc;
                $('q144_receita').value    = oCampo.q144_receita;
                $('q144_tipo').value       = oCampo.q144_tipo;
                $('q144_hist').value       = oCampo.q144_hist;
                $('q144_diavenc').value    = oCampo.q144_diavenc;
              }

              if (oCampo.j178_anousu) {

                  $('j178_receita').value = oCampo.j178_receita;
                  $('j178_histdebito').value = oCampo.j178_histdebito;
                  $('j178_tipodebito').value = oCampo.j178_tipodebito;
                  $('j178_anousu').value = oCampo.j178_anousu;
                  $('j178_diavenc').value = oCampo.j178_diavenc;

                  if (oCampo.j178_diavenc != "") {
                      document.getElementById("vencimento").value = 2;
                      js_ajustaDataVencimentoNotaAvulsa(2);
                  }
              }

              if (oCampo.j170_sequencial) {
                  $('j170_sequencial').value = oCampo.j170_sequencial;
                  $('j170_anousu').value     = oCampo.j170_anousu;
                  $('j170_receit').value     = oCampo.j170_receit;
                  $('j170_hist').value       = oCampo.j170_hist;
                  $('j170_tipo').value       = oCampo.j170_tipo;

                  aTiposEmpresa = oCampo.aTiposEmpresa;

                  js_montaGridTipoEmpresa();
              }
            }
          );

              js_pesquisareceita(false);
              js_pesquisahistorico(false);
              js_pesquisatipodebito(false);
              js_pesquisavencimento(false);
              js_pesquisareceitavariavel(false);
              js_pesquisatipodebitovariavel(false);
              js_pesquisahistoricovariavel(false);
              js_pesquisareceitaNotaAvulsa(false);
              js_pesquisahistoricoNotaAvulsa(false);
              js_pesquisatipodebitoNotaAvulsa(false);
              js_pesquisaReceitaRetidoEmpresaPublica(false);
              js_pesquisahistoricoEmpresaPublica(false);
              js_pesquisatipoDebitoRetidoEmpresaPublica(false);

          }
      }
    ).setMessage('Salvando configurações...').execute();
  }

  function js_ajustaDataVencimentoNotaAvulsa(codigo)
  {
      const diavencimentoretidoNotaAvulsa = document.getElementById("diavencimentoretidoNotaAvulsa");

      if (codigo == 1) {
          diavencimentoretidoNotaAvulsa.hide();
          $('j178_diavenc').value = "";
      } else {
          diavencimentoretidoNotaAvulsa.show();
      }
  }


  function js_pesquisareceitaNotaAvulsa(mostra) {
      if (mostra == true) {
          js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tabrec_nota_avulsa', 'func_tabrec.php?funcao_js=parent.js_mostrareceita1NotaAvulsa|k02_codigo|k02_drecei', 'Pesquisa', true);
      } else {
          if (document.form1.j178_receita.value != '') {
              js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tabrec_nota_avulsa', 'func_tabrec.php?pesquisa_chave=' + document.form1.j178_receita.value + '&funcao_js=parent.js_mostrareceitaNotaAvulsa', 'Pesquisa', false);
          } else {
              document.form1.j178_receita_descr.value = '';
          }
      }
  }
  function js_mostrareceitaNotaAvulsa(chave, erro) {
      document.form1.j178_receita_descr.value = chave;
      if (erro == true) {
          document.form1.j178_receita.focus();
          document.form1.j178_receita.value = '';
      }
  }
  function js_mostrareceita1NotaAvulsa(chave1, chave2) {
      document.form1.j178_receita.value = chave1;
      document.form1.j178_receita_descr.value = chave2;
      db_iframe_tabrec_nota_avulsa.hide();
  }
  function js_pesquisahistoricoNotaAvulsa(mostra) {
      if (mostra == true) {
          js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_histcalc_nota', 'func_histcalc.php?funcao_js=parent.js_mostrahistorico1NotaAvulsa|k01_codigo|k01_descr', 'Pesquisa', true);
      } else {
          if (document.form1.j178_histdebito.value != '') {
              js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_histcalc_nota', 'func_histcalc.php?pesquisa_chave=' + document.form1.j178_histdebito.value + '&funcao_js=parent.js_mostrahistoricoNotaAvulsa', 'Pesquisa', false);
          } else {
              document.form1.j178_histdebito_descr.value = '';
          }
      }
  }
  function js_mostrahistoricoNotaAvulsa(chave, erro) {
      document.form1.j178_histdebito_descr.value = chave;
      if (erro == true) {
          document.form1.j178_histdebito.focus();
          document.form1.j178_histdebito.value = '';
      }
  }
  function js_mostrahistorico1NotaAvulsa(chave1, chave2) {
      document.form1.j178_histdebito.value = chave1;
      document.form1.j178_histdebito_descr.value = chave2;
      db_iframe_histcalc_nota.hide();
  }
  function js_pesquisatipodebitoNotaAvulsa(mostra) {
      if (mostra == true) {
          js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_arretipo_nota_avulsa', 'func_arretipo.php?funcao_js=parent.js_mostratipodebito1NotaAvulsa|k00_tipo|k00_descr', 'Pesquisa', true);
      } else {
          if (document.form1.j178_tipodebito.value != '') {
              js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_arretipo_nota_avulsa', 'func_arretipo.php?pesquisa_chave=' + document.form1.j178_tipodebito.value + '&funcao_js=parent.js_mostratipodebitoNotaAvulsa', 'Pesquisa', false);
          } else {
              document.form1.j178_tipodebito_descr.value = '';
          }
      }
  }
  function js_mostratipodebitoNotaAvulsa(chave, erro) {
      document.form1.j178_tipodebito_descr.value = chave;
      if (erro == true) {
          document.form1.j178_tipodebito.focus();
          document.form1.j178_tipodebito.value = '';
      }
  }
  function js_mostratipodebito1NotaAvulsa(chave1, chave2) {
      document.form1.j178_tipodebito.value = chave1;
      document.form1.j178_tipodebito_descr.value = chave2;
      db_iframe_arretipo_nota_avulsa.hide();
  }
  function js_pesquisahistoricoEmpresaPublica(mostra) {
      if (mostra == true) {
          js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_histcalc_empresa_publica', 'func_histcalc.php?funcao_js=parent.js_mostrahistoricoEmpresaPublica2|k01_codigo|k01_descr', 'Pesquisa', true);
      } else {
          if (document.form1.j170_hist.value != '') {
              js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_histcalc_empresa_publica', 'func_histcalc.php?pesquisa_chave=' + document.form1.j170_hist.value + '&funcao_js=parent.js_mostrahistoricoEmpresaPublica', 'Pesquisa', false);
          } else {
              document.form1.j170_hist_descr.value = '';
          }
      }
  }
  function js_mostrahistoricoEmpresaPublica(chave, erro) {
      document.form1.j170_hist_descr.value = chave;
      if (erro == true) {
          document.form1.j170_hist.focus();
          document.form1.j170_hist.value = '';
      }
  }
  function js_mostrahistoricoEmpresaPublica2(chave1, chave2) {
      document.form1.j170_hist.value = chave1;
      document.form1.j170_hist_descr.value = chave2;
      db_iframe_histcalc_empresa_publica.hide();
  }
  function js_pesquisatipoDebitoRetidoEmpresaPublica(mostra) {
      if (mostra == true) {
          js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_arretipo_empresa_publica', 'func_arretipo.php?funcao_js=parent.js_mostratipoDebitoRetidoEmpresaPublica2|k00_tipo|k00_descr', 'Pesquisa', true);
      } else {
          if (document.form1.j170_tipo.value != '') {
              js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_arretipo_empresa_publica', 'func_arretipo.php?pesquisa_chave=' + document.form1.j170_tipo.value + '&funcao_js=parent.js_mostratipoDebitoRetidoEmpresaPublica', 'Pesquisa', false);
          } else {
              document.form1.j170_tipo_descr.value = '';
          }
      }
  }
  function js_mostratipoDebitoRetidoEmpresaPublica(chave, erro) {
      document.form1.j170_tipo_descr.value = chave;
      if (erro == true) {
          document.form1.j170_tipo.focus();
          document.form1.j170_tipo.value = '';
      }
  }
  function js_mostratipoDebitoRetidoEmpresaPublica2(chave1, chave2) {
      document.form1.j170_tipo.value = chave1;
      document.form1.j170_tipo_descr.value = chave2;
      db_iframe_arretipo_empresa_publica.hide();
  }
  function js_pesquisaReceitaRetidoEmpresaPublica(mostra) {
      if (mostra == true) {
          js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tabrec_retido_empresa_publica', 'func_tabrec.php?funcao_js=parent.js_mostraReceitaRetidoEmpresaPublica1|k02_codigo|k02_drecei', 'Pesquisa', true);
      } else {
          if (document.form1.j170_receit.value != '') {
              js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tabrec_retido_empresa_publica', 'func_tabrec.php?pesquisa_chave=' + document.form1.j170_receit.value + '&funcao_js=parent.js_mostraReceitaRetidoEmpresaPublica', 'Pesquisa', false);
          } else {
              document.form1.k02_descr_ret_emp_pub.value = '';
          }
      }
  }
  function js_mostraReceitaRetidoEmpresaPublica(chave, erro) {
      document.form1.k02_descr_ret_emp_pub.value = chave;
      if (erro == true) {
          document.form1.j170_receit.focus();
          document.form1.j170_receit.value = '';
      }
  }

  function js_mostraReceitaRetidoEmpresaPublica1(chave1, chave2) {
      document.form1.j170_receit.value = chave1;
      document.form1.k02_descr_ret_emp_pub.value = chave2;
      db_iframe_tabrec_retido_empresa_publica.hide();
  }

  function js_pesquisaTipoEmpresa(mostra)
  {
      if (mostra == true) {
          js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tipoempresa', 'func_tipoempresa.php?funcao_js=parent.js_mostraTipoEmpresa1|db98_sequencial|db98_descricao', 'Pesquisa', true);
      } else {
          if (document.form1.j171_tipoempresa.value != '') {
              js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tipoempresa', 'func_tipoempresa.php?pesquisa_chave=' + document.form1.j171_tipoempresa.value + '&funcao_js=parent.js_mostraTipoEmpresa', 'Pesquisa', false);
          } else {
              document.form1.db98_descricao.value = '';
          }
      }
  }

  function js_mostraTipoEmpresa(descricao, erro)
  {
      document.form1.db98_descricao.value = descricao;

      const j171_tipoempresa = document.form1.j171_tipoempresa;

      if (erro == true) {
          j171_tipoempresa.focus();
          j171_tipoempresa.value = '';
      } else {
          verificaTipoEmpresa(j171_tipoempresa.value, descricao);
      }
  }

  function js_mostraTipoEmpresa1(codigo, descricao)
  {
      document.form1.j171_tipoempresa.value = codigo;
      document.form1.db98_descricao.value = descricao;
      db_iframe_tipoempresa.hide();

      verificaTipoEmpresa(codigo, descricao);
  }

  const oGridTaxas = new DBGrid('gridTipoEmpresa');
  const aHeaders   = ["Código", "Descrição", "Ação"];
  const aCellWidth = ["20%", "60%", "20%"];
  const aCellAlign = ["center", "left", "center"];

  oGridTaxas.nameInstance = 'oGridTipoEmpresa';
  oGridTaxas.setCellWidth(aCellWidth);
  oGridTaxas.setCellAlign(aCellAlign);
  oGridTaxas.setHeader(aHeaders);
  oGridTaxas.setHeight(100);
  oGridTaxas.show($('gridTipoEmpresa'));

  let aTiposEmpresa = [];

  function js_montaGridTipoEmpresa()
  {
      oGridTaxas.clearAll(true);

      aTiposEmpresa.forEach((oTipoEmpresa) => {
          const btnRemove = document.createElement("button");
          btnRemove.setAttribute("onclick", `js_removeTipoEmpresa(${oTipoEmpresa.codigo})`);
          btnRemove.setAttribute("title", `Remove o tipo de empresa: ${oTipoEmpresa.descricao}`);
          btnRemove.innerText = "R";

          oGridTaxas.addRow([
              oTipoEmpresa.codigo,
              oTipoEmpresa.descricao,
              btnRemove.outerHTML
          ]);
      });

      oGridTaxas.renderRows();
  }

  function js_limpaTipoEmpresa()
  {
      document.form1.j171_tipoempresa.value = "";
      document.form1.db98_descricao.value = "";
  }

  function renderizaGridTipoEmpresa(codigo, descricao)
  {
      aTiposEmpresa.push({codigo: codigo, descricao: descricao});

      setTimeout(() => {
          js_montaGridTipoEmpresa();
          js_limpaTipoEmpresa();
      }, 1000);
  }

  function verificaTipoEmpresa(codigo, descricao)
  {
      const tipoEmpresaExiste = aTiposEmpresa.find((oTipoEmpresa) => {
          if (oTipoEmpresa.codigo == codigo) {
              alert(`O tipo de empresa ${descricao} já foi adicionado`);
              js_limpaTipoEmpresa();
              return true;
          }
      });

      if (!tipoEmpresaExiste) {
          renderizaGridTipoEmpresa(codigo, descricao);
      }
  }

  function js_removeTipoEmpresa(codigo)
  {
      aTiposEmpresa.find((oTipoEmpresa, key) => {
          if (oTipoEmpresa.codigo == codigo) {
              aTiposEmpresa.splice(key, 1);
              js_montaGridTipoEmpresa();
              return true;
          }
      });
  }
</script>

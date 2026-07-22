<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009 DBSeller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
$clrotulo = new rotulocampo();
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e94_codanu");
$db_opcao = 1;
?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script>
    function js_abre() {
      obj = document.form1;
      query = '';

      if (obj.e94_codanu.value != '') {
        query += "&e94_codanu=" + obj.e94_codanu.value;
      }

      if (obj.e60_numemp.value != '') {
        query += "&e60_numemp=" + obj.e60_numemp.value;
      }

      if (obj.e60_codemp.value != '') {
        query += "&e60_codemp=" + obj.e60_codemp.value;
      }

      let partData = '';
      if (obj.data_inicio.value != '') {
         partData = obj.data_inicio.value.split("/");
        if (partData.length != 3) {
          alert("Data inicial inválida");
          return;
        }
        query += `&data_inicio=${partData[2]}-${partData[1]}-${partData[0]}`;
      }

      if (obj.data_fim.value != '') {
         partData = obj.data_fim.value.split("/");
        if (partData.length != 3) {
          alert("Data final inválida");
          return;
        }
        query += `&data_fim=${partData[2]}-${partData[1]}-${partData[0]}`;
      }

      if (query == '') {
        alert("Selecione um empenho!");
      } else {
        jan = window.open('emp2_anulemp002.php?' + query, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        jan.moveTo(0, 0);
      }
    }
  </script>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <table width="790" height='18' border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>

  <table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
        <form name='form1'>
          <table>
            <tr>
              <td align="right" nowrap title="<?= $Te60_numemp ?>">
                <?php db_ancora(@$Le60_codemp, "js_pesquisae60_codemp(true);", $db_opcao);  ?>
              </td>

              <td nowrap>

                <input name="e60_codemp" title='<?= $Te60_codemp ?>' size="8" type='text' onKeyPress="return js_mascara(event);">
              </td>
            </tr>
            <tr>
              <td nowrap align="right" title="<?= @$Te60_numemp ?>">
                <?php db_ancora(@$Le60_numemp, "js_pesquisae60_numemp(true);", 1); ?>
              </td>
              <td>
                <?php db_input('e60_numemp', 8, $Ie60_numemp, true, 'text', $db_opcao, " onchange='js_pesquisae60_numemp(false);'")  ?>
              </td>
            </tr>
            <tr>
              <td nowrap align="right" title="<?= @$Te94_codanu ?>">
                <?php db_ancora("<b>Código anulação:</b>", "js_pesquisa(true);", 1); ?>
              </td>
              <td>
                <?php db_input('e94_codanu', 8, $Ie94_codanu, true, 'text', $db_opcao, " onchange='js_pesquisa(false);'");  ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="Data Anulação" align="right">
                  <b >Data Anulação:</b>
              </td>
              <td>
                <?php db_inputdata('data_inicio', "", "", "", true, 'text', 4) ?> até
                <?php db_inputdata('data_fim', "", "", "", true, 'text', 4) ?>
              </td>
            </tr>
            <tr>
              <td colspan='2' align='center'>
                <input name='pesquisar' type='button' value='Consultar' onclick='js_abre();'>
              </td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
  </table>
  <?php
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>

</html>
<script>
  function js_pesquisa(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empanulado', 'func_empanulado.php?funcao_js=parent.js_mostra1|e94_codanu', 'Pesquisa', true);
    } else {
      if (document.form1.e94_codanu.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empanulado', 'func_empanulado.php?pesquisa_chave=' + document.form1.e94_codanu.value + '&funcao_js=parent.js_mostra', 'Pesquisa', false);
      } else {
        document.form1.e94_codanu.value = '';
      }
    }
  }

  function js_mostra(chave, erro) {
    if (erro == true) {
      document.form1.e94_codanu.focus();
      document.form1.e94_codanu.value = '';
    }
  }

  function js_mostra1(chave1, x) {
    document.form1.e94_codanu.value = chave1;
    db_iframe_empanulado.hide();
  }

  function js_pesquisae60_numemp(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empempenho', 'func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp', 'Pesquisa', true);
    } else {
      if (document.form1.e60_numemp.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empempenho', 'func_empempenho.php?pesquisa_chave=' + document.form1.e60_numemp.value + '&funcao_js=parent.js_mostraempempenho', 'Pesquisa', false);
      } else {
        document.form1.e60_numemp.value = '';
      }
    }
  }

  function js_mostraempempenho(chave, erro) {
    if (erro == true) {
      document.form1.e60_numemp.focus();
      document.form1.e60_numemp.value = '';
    }
  }

  function js_mostraempempenho1(chave1, x) {
    document.form1.e60_numemp.value = chave1;
    db_iframe_empempenho.hide();
  }

  function js_pesquisae60_codemp(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empempenho02', 'func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp|e60_anousu', 'Pesquisa', true);
    } else {
      // js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
    }
  }

  function js_mostraempempenho2(chave1, chave2) {
    document.form1.e60_codemp.value = chave1 + '/' + chave2;
    db_iframe_empempenho02.hide();
  }


  function js_pesquisae60_numemp(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empempenho', 'func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp', 'Pesquisa', true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empempenho', 'func_empempenho.php?pesquisa_chave=' + document.form1.e60_numemp.value + '&funcao_js=parent.js_mostraempempenho', 'Pesquisa', false);
    }
  }
</script>

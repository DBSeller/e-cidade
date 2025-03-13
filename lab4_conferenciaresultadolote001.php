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

require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/db_usuariosonline.php'));
require_once(modification('dbforms/db_funcoes.php'));
require_once(modification('libs/db_utils.php'));

$oRotulo = new rotulocampo;
$oRotulo->label("la09_i_exame");
$oRotulo->label("la24_i_setor");
$oRotulo->label("la02_i_codigo");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC">
<form name='form1' class="container">
  <fieldset>
    <legend>Conferência de Exames - Lote</legend>
    <table class="form-container">
      <tr>
        <td class="bold">
          Período:
        </td>
        <td>
            <?php
            db_inputdata('dData1', @$iDia1, @$iMes1, @$iAno1, true, 'text', 1, "");
            ?>
            <label>à</label>
            <?php
            db_inputdata('dData2', @$iDia2, @$iMes2, @$iAno2, true, 'text', 1, "");
            ?>
        </td>
      </tr>
      <tr>
        <td class="bold">
            <?php
            db_ancora('<b>Laboratório:</b>', "pesquisaLaboratorio(true);", "");
            ?>
        </td>
        <td>
            <?php
            db_input('la02_i_codigo', 10, $Ila02_i_codigo, true, 'text', "",
              " onchange='pesquisaLaboratorio(false);' "
            );
            db_input('la02_c_descr', 50, @$Ila02_c_descr, true, 'text', 3, '');
            ?>
        </td>
      </tr>
      <tr>
        <td class="bold" nowrap title="<?= @$Tla24_i_setor ?>">
            <?php
            db_ancora(@$Lla24_i_setor, "pesquisaSetor(true);", "");
            ?>
        </td>
        <td>
            <?php
            db_input('la24_i_setor', 10, $Ila24_i_setor, true, 'text', "",
              " onchange='pesquisaSetor(false);'"
            );
            db_input('la24_i_codigo', 10, '', true, 'hidden', '', '');
            db_input('la23_c_descr', 50, @$Ila23_c_descr, true, 'text', 3, '');
            ?>
        </td>
      </tr>
      <tr>
        <td class="bold" nowrap title="<?= @$Tla09_i_exame ?>">
            <?php
            db_ancora(@$Lla09_i_exame, "pesquisaExame(true);", "");
            ?>
        </td>
        <td>
            <?php
            db_input('la09_i_exame', 10, @$Ila09_i_exame, true, 'text', "",
              " onchange='pesquisaExame(false);'"
            );
            db_input('la08_c_descr', 50, @$Ila08_c_descr, true, 'text', 3, '');
            ?>
        </td>
      </tr>
      <tr>
    </table>
  </fieldset>
  <input name='start' type='button' value='Confirmar' onclick="confirma()">
</form>
<?php
db_menu();
?>
</body>
</html>

<script>

  function mostralaboratorio(la02_c_descr, lErro) {
    document.form1.la02_c_descr.value = la02_c_descr;
    if (lErro == true) {
      document.form1.la02_i_codigo.focus();
      document.form1.la02_i_codigo.value = '';
    }
  }

  function mostralaboratorio1(la02_i_codigo, la02_c_descr) {
    document.form1.la02_i_codigo.value = la02_i_codigo;
    document.form1.la02_c_descr.value = la02_c_descr;
    db_iframe_lab_laboratorio.hide();
  }

  function pesquisaLaboratorio(lMostra) {
    if (lMostra == true) {
      js_OpenJanelaIframe('',
        'db_iframe_lab_laboratorio',
        'func_lab_laboratorio.php'
        + '&funcao_js=parent.mostralaboratorio1|la02_i_codigo|la02_c_descr',
        'Pesquisa',
        true
      );
    } else {
      if (document.form1.la02_i_codigo.value != '') {
        js_OpenJanelaIframe('',
          'db_iframe_lab_laboratorio',
          'func_lab_laboratorio.php?pesquisa_chave='
          + document.form1.la02_i_codigo.value + '&funcao_js=parent.mostralaboratorio',
          'Pesquisa',
          false
        );
      } else {
        document.form1.la02_c_descr.value = '';
      }
    }
  }

  function pesquisaSetor(lMostra) {
    if (lMostra == true) {
      js_OpenJanelaIframe('',
        'db_iframe_lab_labsetor',
        'func_lab_labsetor.php?'
        + 'funcao_js=parent.mostralab_labsetor1|la24_i_setor|la23_c_descr|la24_i_codigo',
        'Pesquisa',
        true
      );
    } else {
      if (document.form1.la24_i_setor.value != '') {
        js_OpenJanelaIframe('',
          'db_iframe_lab_labsetor',
          'func_lab_labsetor.php?'
          + 'pesquisa_chave=' + document.form1.la24_i_setor.value
          + '&funcao_js=parent.mostralab_labsetor',
          'Pesquisa',
          false
        );
      } else {

        document.form1.la23_c_descr.value = '';
        document.form1.la24_i_codigo.value = '';
      }
    }
  }

  function mostralab_labsetor(la23_c_descr, lErro, la24_i_codigo) {
    document.form1.la23_c_descr.value = la23_c_descr;
    document.form1.la24_i_codigo.value = la24_i_codigo;
    if (lErro == true) {
      document.form1.la24_i_setor.focus();
      document.form1.la24_i_setor.value = '';
      document.form1.la24_i_codigo.value = '';
    }
  }

  function mostralab_labsetor1(la24_i_setor, la23_c_descr, la24_i_codigo) {
    document.form1.la24_i_setor.value = la24_i_setor;
    document.form1.la24_i_codigo.value = la24_i_codigo;
    document.form1.la23_c_descr.value = la23_c_descr;
    db_iframe_lab_labsetor.hide();
  }

  function pesquisaExame(lMostra) {
    var sUrl = 'func_lab_setorexame.php';
    var sFiltroSetor = '';
    if (document.form1.la24_i_setor.value != '') {
      sFiltroSetor = '&la24_i_codigo=' + document.form1.la24_i_codigo.value + '&';
    }

    if (lMostra == true) {
      sUrl += '?funcao_js=parent.mostralab_exame1|la08_i_codigo|la08_c_descr';
      sUrl += sFiltroSetor;
      js_OpenJanelaIframe('', 'db_iframe_lab_setorexame', sUrl, 'Pesquisa Exames', true);
    } else {
      if (document.form1.la09_i_exame.value != '') {
        sUrl += '?pesquisa_chave=' + $F('la09_i_exame');
        sUrl += '&funcao_js=parent.mostralab_exame';
        sUrl += sFiltroSetor;
        js_OpenJanelaIframe('', 'db_iframe_lab_setorexame', sUrl, 'Pesquisa Exames', false);
      } else {
        document.form1.la08_c_descr.value = '';
      }
    }
  }

  function mostralab_exame(la08_c_descr, lErro) {
    document.form1.la08_c_descr.value = la08_c_descr;
    if (lErro == true) {
      document.form1.la09_i_exame.focus();
      document.form1.la09_i_exame.value = '';
    }
  }

  function mostralab_exame1(la09_i_exame, la08_c_descr) {
    document.form1.la09_i_exame.value = la09_i_exame;
    document.form1.la08_c_descr.value = la08_c_descr;
    db_iframe_lab_setorexame.hide();
  }

  function confirma() {
    var codigoLaboratorio = $F('la02_i_codigo');
    var codigoSetor = $F('la24_i_setor');
    var codigoExame = $F('la09_i_exame');
    var dataInicio = $F('dData1');
    var dataFim = $F('dData2');

    if ($('dData1').value === '' || $('dData2').value === '') {
      alert('Preencha o período.');
      return false;
    }
    
    converteDataInicio = dataInicio.split('/');
    converteDataFim = dataFim.split('/');
    dInicio = new Date(converteDataInicio[2], converteDataInicio[1] - 1, converteDataInicio[0]);
    dFim = new Date(converteDataFim[2], converteDataFim[1] - 1, converteDataFim[0]);

    if (dInicio > dFim) {
      alert('Data inicial é maior que a data final.');
      return false;
    }

    if (!confirm('Deseja confirmar a conferência de resultados?')) {
      return false;
    }

    var oAjaxRequest = new AjaxRequest('lab4_conferencia.RPC.php',
      {
        exec: 'salvarConferenciaLote',
        codigoLaboratorio: codigoLaboratorio,
        codigoSetor: codigoSetor,
        codigoExame: codigoExame,
        dataInicio: dataInicio,
        dataFim: dataFim
      },
      function(oRetorno, lErro) {
        alert(oRetorno.sMensagem.urlDecode());
        return;
      });
    oAjaxRequest.setMessage('Buscando...');
    oAjaxRequest.execute();
  }

</script>

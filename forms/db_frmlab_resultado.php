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

//MODULO: Laboratório
$cllab_resultado->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("la25_i_codigo");
$clrotulo->label("la21_i_codigo");
$clrotulo->label("la21_observacao");
$clrotulo->label("la22_i_codigo");
$clrotulo->label("z01_v_nome");
$clrotulo->label("la24_i_laboratorio");
$clrotulo->label("la09_i_exame");
$clrotulo->label("la21_d_data");
$clrotulo->label("la21_c_hora");
$clrotulo->label("la21_i_requisicao");
$clrotulo->label("la21_d_entrega");
$clrotulo->label("la08_i_codigo");
?>
<fieldset>
  <legend>Digitação de resultados</legend>
  <form name="form1" method="post" action="">
    <?php
    db_input( 'la52_i_codigo', 10, $Ila52_i_codigo, true, 'hidden', $db_opcao );
    ?>
    <table>
      <tr>
        <td>
          <fieldset class='separator'>
            <legend>Exames</legend>
            <table>
              <tr>
                <td title="<?=@$Tla22_i_codigo?>">
                  <?php
                  db_ancora( @$Lla21_i_requisicao, "js_pesquisala22_i_codigo(true);", $db_opcao );
                  ?>
                </td>
                <td>
                  <?php
                  db_input( 'la22_i_codigo', 10, $Ila22_i_codigo, true, 'text', $db_opcao, " onchange='js_pesquisala22_i_codigo(false);'" );
                  db_input( 'z01_v_nome'   , 50, $Iz01_v_nome,    true, 'text',         3, '');
                  ?>
                </td>
              </tr>
              <tr>
                <td title="requiitem">
                  <?php
                  db_ancora ( '<label class="bold">Exame:</label>', "js_pesquisaExame(true);", "" );
                  ?>
                </td>
		            <td>
		              <?php
                  db_input( 'la08_i_codigo',    10, @$Ila08_i_codigo, true, 'text',  "", " onchange='js_pesquisaExame(false);'" );
                  db_input( 'la47_i_requiitem', 10, @$Ila21_i_codigo, true, 'hidden' );
                  db_input( 'la08_c_descr',     50, @$Ila08_c_descr,  true, 'text',  3 );
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
       </tr>
      <tr>
        <td>
          <div id="ctnGridAtributos"></div>
        </td>
      </tr>
    </table>
  </form>
</fieldset>
<script>


F               = document.form1;
var oLancamento = new LancamentoExameLaboratorio('oLancamento');
oLancamento.mostraCampoObservacao(true);
oLancamento.show($('ctnGridAtributos'));

function js_pesquisala22_i_codigo( mostra ) {

	if ( mostra == true ) {
	  js_OpenJanelaIframe(
                         '',
                         'db_iframe_lab_requisicao',
                         'func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>'
                                               +'&funcao_js=parent.js_mostralab_requisicao1|la22_i_codigo|z01_v_nome',
                         'Pesquisa',
                         true
                       );
	} else {

	  if ( document.form1.la22_i_codigo.value != '' ) {
	    js_OpenJanelaIframe(
                           '',
                           'db_iframe_lab_requisicao',
                           'func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>'
                                                 +'&pesquisa_chave='+document.form1.la22_i_codigo.value
                                                 +'&funcao_js=parent.js_mostralab_requisicao',
                           'Pesquisa',
                           false
                         );
	  } else {

	    document.form1.z01_v_nome.value = '';
      js_limpaDadosExame();
	  }
	}
}

function js_mostralab_requisicao( chave, erro ) {

  js_limpaDadosExame();
  document.form1.z01_v_nome.value = chave;

  if ( erro == true ) {

    document.form1.la22_i_codigo.focus();
    document.form1.la22_i_codigo.value = '';
  }
}

function js_mostralab_requisicao1( chave1, chave2 ) {

  js_limpaDadosExame();
  document.form1.la22_i_codigo.value = chave1;
  document.form1.z01_v_nome.value    = chave2;
  db_iframe_lab_requisicao.hide();
}

function js_pesquisaExame( mostra ) {

  if ( document.form1.la22_i_codigo.value == '' ) {

    alert('Escolha uma requisição primeiro.');
	  js_limpaCamposTrocaReq();
	  return false;
  }

  sPesq = 'la21_d_data=0&la21_i_requisicao='+document.form1.la22_i_codigo.value
        +'&iLaboratorioLogado=<?=$iLaboratorioLogado?>&sSituacao=|6 - Coletado|,|2 - Lancado|&';

  if ( mostra == true ) {
	  js_OpenJanelaIframe(
                         '',
                         'db_iframe_lab_requiitem',
                         'func_lab_requiitem.php?'+sPesq
                                                  +'funcao_js=parent.js_mostrarequiitem1|la08_i_codigo|la08_c_descr|la21_i_codigo',
                         'Pesquisa',
                         true
                       );
  } else {

	  if ( document.form1.la08_i_codigo.value != '' ) {
	    js_OpenJanelaIframe(
                           '',
                           'db_iframe_lab_requiitem',
                           'func_lab_requiitem.php?'+sPesq
                                                    +'pesquisa_chave='+document.form1.la08_i_codigo.value
                                                    +'&funcao_js=parent.js_mostrarequiitem',
                           'Pesquisa',
                           false
                         );
	  } else {

	    document.form1.la08_c_descr.value = '';
      js_limpaAtributosExame();
	  }
  }
}

function js_mostrarequiitem( chave, erro, requiitem ) {

  js_limpaAtributosExame();
  document.form1.la08_c_descr.value = chave;

  if ( erro == true) {

	  document.form1.la08_i_codigo.focus();
	  document.form1.la08_i_codigo.value = '';
  } else {

	  document.form1.la47_i_requiitem.value = requiitem
    oLancamento.setRequisicao(requiitem);
  }
}

function js_mostrarequiitem1( chave1, chave2, requiitem ) {

  js_limpaAtributosExame();
  document.form1.la08_i_codigo.value    = chave1;
  document.form1.la47_i_requiitem.value = requiitem;
  document.form1.la08_c_descr.value     = chave2;
  db_iframe_lab_requiitem.hide();
  oLancamento.setRequisicao(requiitem);
}


function js_pesquisa() {
  js_OpenJanelaIframe(
                       'top.corpo',
                       'db_iframe_lab_resultado',
                       'func_lab_resultado.php?funcao_js=parent.js_preenchepesquisa|la39_i_codigo',
                       'Pesquisa',
                       true
                     );
}

function js_preenchepesquisa( chave ) {

  db_iframe_lab_resultado.hide();
  <?php
    if ( $db_opcao != 1 ) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}

function js_limpaDadosExame() {

  $('la08_i_codigo').value    = '';
  $('la47_i_requiitem').value = '';
  $('la08_c_descr').value     = '';
  js_limpaAtributosExame();
}

function js_limpaAtributosExame() {
  oGridAtributosExame.clearAll(true);
}

<?php
  if ( !empty( $_GET["la47_i_requiitem"] ) ) {
    echo "oLancamento.setRequisicao({$_GET["la47_i_requiitem"]});\n";
  }
?>
$('la22_i_codigo').className = 'field-size2';
$('z01_v_nome').setStyle( { 'width': '590px' } );

$('la08_i_codigo').className = 'field-size2';
$('la08_c_descr').setStyle( { 'width': '590px' } );
</script>
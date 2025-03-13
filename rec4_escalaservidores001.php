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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
$clrotulo = new rotulocampo;
$clrotulo->label("rh254_tipojornada");
$clrotulo->label("rh254_tempoparcial");
$clrotulo->label("rh254_horarionoturno");
$clrotulo->label("rh254_descricaojornada");

?>

<html>
<head>
  <?php
  db_app::load('scripts.js, datagrid.widget.js, strings.js, prototype.js, estilos.css, AjaxRequest.js');
  ?>
</head>
<body>
<div id="container" class="container">
  <form name="form1" id="form1">
      <fieldset>
        <legend>Escala de Servidores</legend>

        <table>
          <tr>
            <td>
              <?php
              db_ancora('Servidor:', 'js_pesquisaServidor(true)', 1);
              ?>
            </td>
            <td>
              <?php
              db_input('iMatricula'     , 10, 1, true, 'text', 1, "onchange='js_pesquisaServidor(false)'");
              db_input('sNomeServidor'  , 50, 1, true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?php
              db_ancora('Escala:', 'js_pesquisaEscala(true)', 1);
              ?>
            </td>
            <td>
              <?php
              db_input('iCodigoEscala', 10, 1, true, 'text', 1, "onchange='js_pesquisaEscala(false)'");
              db_input('sDescricao'   , 50, 1, true, 'text', 3);
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <strong>Data Escala:</strong>
            </td>
            <td>
              <?php
              db_inputdata('dDataEscala', null, null, null, true, 'text', 1);
              ?>
            </td>
          </tr>

        </table>

        <input type="button" name="incluir" id="incluir" value="Incluir Escala" onclick="js_incluir()" />

        <fieldset style="margin-top:10px">
          <div id="gridEscalas"></div>
        </fieldset>
      </fieldset>
  </form>
  <div id="esocial" style="display:none">
    <form name="esocialForm" id="esocialForm">
      <fieldset >
        <legend>e-Social</legend>
        <div id="divMensagem"></div>
        <table>
          <tr>
            <td nowrap title="<?=@$Trh254_tipojornada?>">
              <?=@$Lrh254_tipojornada?>
            </td>

            <td>
              <?php
              $tipoJornada = [ '9' => '9 - Demais tipos de jornada',
                              '2' => '2 - Jornada 12 x 36 (12 horas de trabalho seguidas de 36 horas ininterruptas de 
                                descanso)',
                              '3' => '3 - Jornada com horário diário fixo e folga variável',
                              '4' => '4 - Jornada com horário diário fixo e folga fixa (no domingo)',
                              '5' => '5 - Jornada com horário diário fixo e folga fixa (exceto no domingo)',
                              '6' => '6 - Jornada com horário diário fixo e folga fixa (em outro dia da semana), com 
                                folga adicional periódica no domingo',
                              '7' => '7 - Turno ininterrupto de revezamento'
                            ];
              db_select('rh254_tipojornada', $tipoJornada, true, 1, "style='width: 300px;'");
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh254_tempoparcial?>" >
              <?=@$Lrh254_tempoparcial?>
            </td>

            <td>
              <?php
              $tipoContrato = [ '0' => '0 - Não é contrato em tempo parcial',
                              '1' => '1 - Limitado a 25 horas semanais',
                              '2' => '2 - Limitado a 30 horas semanais',
                              '3' => '3 - Limitado a 26 horas semanais'
                            ];
              db_select('rh254_tempoparcial', $tipoContrato, true, 1, "");
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh254_horarionoturno?>">
              <?=@$Lrh254_horarionoturno?>
            </td>

            <td>
              <?php
              $horarioNoturno = [ 'N' => 'Não',
                              'S' => 'Sim'
                            ];
              db_select('rh254_horarionoturno', $horarioNoturno, true, 1, "");
            ?>
            </td>
          </tr>      
          <tr>
            <td colspan="2" nowrap title="<?=@$Trh254_descricaojornada?>">
              <?=@$Lrh254_descricaojornada?>
            </td>
          </tr>
          <tr>                  
            <td colspan="2">
              <?php
              db_textarea('rh254_descricaojornada',
                           3,
                           54,
                           'rh254_descricaojornada',
                           true,
                           'text',
                           1,
                           "style='width: 500px;'")
              ?>
            </td>
          </tr>   
          <tr>
            <td style="text-align: center;">
            <input type="button" name="salvar" id="salvar" value="Salvar" onclick="js_salvar()" />
            </td>
            <td style="text-align: center;">
            <input type="button" name="excluir" id="excluir" value="Excluir" onclick="js_excluir_contrato_jornada()" />
            </td>
          </tr>        
        </table>
      </fieldset>  
    </form>
  </div>
</div>
  <?php
  db_menu();
  ?>

</body>
</html>

<script>

  function js_pesquisaServidor(lMostraLookUp) {

    document.getElementById('esocial').style.display = 'none';
    if (lMostraLookUp) {

      js_OpenJanelaIframe(
        'top.corpo',
        'db_iframe_rhpessoal',
        'func_rhpessoal.php?funcao_js=parent.js_retornoPesquisaServidor|rh01_regist|z01_nome',
        'Consulta Matrícula',
        true
      );
      document.getElementById('esocial').style.display = 'block';
    } else {

      if ( $F('iMatricula') != '') {
        js_OpenJanelaIframe(
          'top.corpo',
          'db_iframe_rhpessoal',
          'func_rhpessoal.php?pesquisa_chave=' + $F('iMatricula') + '&funcao_js=parent.js_retornoPesquisaDigitadaServidor',
          'Consulta Matrícula',
          false
        );
        document.getElementById('esocial').style.display = 'block';
      } else{
        $('sNomeServidor').value = '';
        document.getElementById('esocial').style.display = 'none';
        oGridEscalas.clearAll(true);
      }
    }
  }

  function js_retornoPesquisaDigitadaServidor(sNomeServidor, lErro) {

    $('sNomeServidor').value = sNomeServidor;

    if( lErro == true){

      $('iMatricula').focus();
      $('iMatricula').value = '';
      document.getElementById('esocial').style.display = 'none';
      oGridEscalas.clearAll(true);
    } else {
      js_carregarEscalas();
      js_carregarContratoJornada();
    }
  }

  function js_retornoPesquisaServidor(iMatricula, sNome) {

    $('iMatricula').value    = iMatricula;
    $('sNomeServidor').value = sNome;

    db_iframe_rhpessoal.hide();
    document.getElementById('esocial').style.display = 'block';
    js_carregarEscalas();
    js_carregarContratoJornada();
  }

  function js_pesquisaEscala(lMostraLookUp) {

    if (lMostraLookUp) {
      js_OpenJanelaIframe(
        'top.corpo',
        'db_iframe_gradeshorarios',
        'func_gradeshorarios.php?funcao_js=parent.js_retornoPesquisaEscala|rh190_sequencial|rh190_descricao',
        'Consulta Escala',
        true
      );
    } else {
      js_OpenJanelaIframe(
        'top.corpo',
        'db_iframe_gradeshorarios',
        'func_gradeshorarios.php?pesquisa_chave=' + $F('iCodigoEscala') + '&funcao_js=parent.js_retornoPesquisaDigitadaEscala',
        'Consulta Escala',
        false
      );
    }
  }

  function js_retornoPesquisaEscala(iCodigo, sDescricao) {

    $('iCodigoEscala').value = iCodigo;
    $('sDescricao').value    = sDescricao;

    db_iframe_gradeshorarios.hide();
  }

  function js_retornoPesquisaDigitadaEscala(sDescricao, lErro) {

    $('sDescricao').value = sDescricao;

    if (lErro == true) {

      $('iCodigoEscala').focus();
      $('iCodigoEscala').value = '';
    }
  }

  var sUrl = "rec4_escalaservidores.RPC.php";
  var urlRpc = "rec4_contratojornadaservidores.RPC.php";

  js_gridEscalas();

  function js_gridEscalas() {

    oGridEscalas              = new DBGrid("DataGridEscalas");
    oGridEscalas.sName        = "DataGridEscalas";
    oGridEscalas.nameInstance = "oGridEscalas";

    oGridEscalas.setHeader(["Código","Código", "Escala", "Data", "Excluir"]);
    oGridEscalas.setCellWidth(["", "50px", "200px", "100px", "50px"]);
    oGridEscalas.setCellAlign(["center", "center", "left", "center", "center"]);
    oGridEscalas.setHeight('300');
    oGridEscalas.show( $('gridEscalas') );
    oGridEscalas.showColumn(false, 1);
  }

  function js_carregarEscalas() {

    var aBotoes      = new Array();
    var oParametros  = { 'exec' : 'carregarEscalas', 'iMatricula' : $F('iMatricula') };
    var oAjaxRequest = new AjaxRequest( sUrl, oParametros,

      function (oAjax, lResposta) {

        oGridEscalas.clearAll(true);

        oAjax.aRetornoEscalas.each( function (oEscala, iEscala) {

          oGridEscalas.addRow( [oEscala.iCodigo, oEscala.iCodigoEscala, oEscala.sDescricao.urlDecode(), js_formatar(oEscala.dDataEscala, 'd'), ''] );

          oBotaoExcluir            = document.createElement('input');
          oBotaoExcluir.type       = 'button';
          oBotaoExcluir.value      = 'Excluir';
          oBotaoExcluir.setAttribute('onclick', 'js_excluir(' + oEscala.iCodigo + ')');

          oBotoes                  = new Object();
          oBotoes.oBotaoExcluir    = oBotaoExcluir;
          oBotoes.sIdCelulaExcluir = oGridEscalas.aRows[iEscala].aCells[4].sId;

          aBotoes.push(oBotoes);
        });

        oGridEscalas.renderRows();

        aBotoes.each( function (oBotao, iBotao) {
          document.getElementById(oBotao.sIdCelulaExcluir).appendChild(oBotao.oBotaoExcluir);
        });
      }
    );

    oAjaxRequest.setMessage('Buscando Tipos...');
    oAjaxRequest.execute();
  }

  function js_incluir() {

    oParametros               = new Object();
    oParametros.exec          = 'incluir';
    oParametros.iMatricula    = $F('iMatricula');
    oParametros.iCodigoEscala = $F('iCodigoEscala');
    oParametros.dDataEscala   = $F('dDataEscala');

    var oAjaxRequest = new AjaxRequest(sUrl, oParametros,

      function (oAjax, lErro) {

        alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));

        if ( lErro==false ) {

          $('iCodigoEscala').value = '';
          $('sDescricao').value    = '';
          $('dDataEscala').value   = '';
          js_carregarEscalas();
        }
      }
    );

    oAjaxRequest.setMessage("Salvando");
    oAjaxRequest.execute();
  }

  function js_excluir (iCodigoEscala) {

    oParametros               = new Object();
    oParametros.exec          = 'excluir';
    oParametros.iCodigoEscala = iCodigoEscala;

    if (!confirm('Deseja excluir a escala?')) {
      return false;
    }

    var oAjaxRequest = new AjaxRequest(sUrl, oParametros,

      function (oAjax, lErro) {

        alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));

        if(oAjax.erro == false) {
          js_carregarEscalas();
        }
      }
    );

    oAjaxRequest.setMessage("Excluindo escala selecionada.");
    oAjaxRequest.execute();
  }

function js_carregarContratoJornada() {
let $divMensagem = document.getElementById('divMensagem');
let oParametros  = { 'exec' : 'carregarContratoJornadaEscala', 'iMatricula' : $F('iMatricula') };
let oAjaxRequest = new AjaxRequest( urlRpc, oParametros,

  function (oAjax, lErro) {
    $divMensagem.style.display = 'block';
    document.getElementById('excluir').disabled = false;
    if(oAjax.erro == false) {
      if (typeof oAjax.rh254_sequencial !== 'undefined') {
        $('rh254_tipojornada').value = oAjax.rh254_tipojornada;
        $('rh254_tempoparcial').value = oAjax.rh254_tempoparcial;
        $('rh254_horarionoturno').value = oAjax.rh254_horarionoturno;
        $('rh254_descricaojornada').value = oAjax.rh254_descricaojornada;
        $divMensagem.style.display = 'none';
      } else {
      $('rh254_tipojornada').value = 9;
      $('rh254_tempoparcial').value = 0;
      $('rh254_horarionoturno').value = 'N';
      $('rh254_descricaojornada').value = '';
      $divMensagem.style.color ='red';
      $divMensagem.innerHTML = "<strong>Informações para o eSocial não foram salvas para este sevidor.<strong>";
      document.getElementById('excluir').disabled = true;
    }

    }
  }
);

oAjaxRequest.setMessage('Buscando o registro...');
oAjaxRequest.execute();
}

function js_salvar() {

let oParametros                    = new Object();
oParametros.exec                   = 'incluir';
oParametros.iMatricula             = $F('iMatricula');
oParametros.rh254_matricula        = $F('iMatricula');
oParametros.rh254_tipojornada      = $F('rh254_tipojornada');
oParametros.rh254_tempoparcial     = $F('rh254_tempoparcial');
oParametros.rh254_horarionoturno   = $F('rh254_horarionoturno');
oParametros.rh254_descricaojornada = $F('rh254_descricaojornada');

let oAjaxRequest = new AjaxRequest(urlRpc, oParametros,

  function (oAjax, lErro) {

    alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));

    if ( lErro == false ) {
      $('rh254_tipojornada').value = oAjax.rh254_tipojornada;
      $('rh254_tempoparcial').value = oAjax.rh254_tempoparcial;
      $('rh254_horarionoturno').value = oAjax.rh254_horarionoturno;
      $('rh254_descricaojornada').value = oAjax.rh254_descricaojornada;
      js_carregarContratoJornada()
    }
  }
);

oAjaxRequest.setMessage("Salvando");
oAjaxRequest.execute();
}

function js_excluir_contrato_jornada () {

oParametros               = new Object();
oParametros.exec          = 'excluir';
oParametros.iMatricula    = $F('iMatricula');
oParametros.Menssagem    = $F('iMatricula');

if (!confirm('Deseja excluir a jornada contratual deste servidor para o eSocial?')) {
  return false;
}

var oAjaxRequest = new AjaxRequest(urlRpc, oParametros,

  function (oAjax, lErro) {

    alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));

    if(oAjax.erro == false) {
      js_carregarContratoJornada()
    }
  }
);

oAjaxRequest.setMessage("Excluindo jornada contratual deste servidor para o eSocial.");
oAjaxRequest.execute();
}
</script>

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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/DBDate.php"));

$clrotulo = new rotulocampo();
$clrotulo->label('z01_nome');
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('v70_codforo');

// Verifica se Sistema de Agua esta em Uso
db_sel_instit(null, "db21_usasisagua, db21_regracgmiptu, db21_regracgmiss");

if (isset($db21_usasisagua) && $db21_usasisagua != '') {
    $db21_usasisagua = ($db21_usasisagua == 't');
    if ($db21_usasisagua == true) {
        $j18_nomefunc = "func_aguabase.php";
    } else {
        $j18_nomefunc = "func_iptubase.php";
    }
} else {
    $db21_usasisagua = false;
    $j18_nomefunc = "func_iptubase.php";
}

?>
<html>
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/DBFormularios.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <link href="estilos/desmembramentoInicial.css" rel="stylesheet" type="text/css">
    <script src="scripts/scripts.js" rel="script" type="text/javascript"></script>
    <script src="scripts/strings.js" rel="script" type="text/javascript"></script>
    <script src="scripts/jquery-2.1.1.min.js" rel="script" type="text/javascript"></script>
    <script type="text/javascript" rel="script" type="text/javascript"></script>
</head>
<body class="body-default">
<div class="container">
    <form name="form1" method="post">
        <fieldset>
            <legend>Desmembramento de iniciais</legend>
            <table>
                <tr>
                    <td title="<?php echo $Tz01_nome; ?>">
                        <label for="z01_numcgm"><?php db_ancora($Lz01_nome, 'js_mostranomes(true);', 4); ?></label>
                    </td>
                    <td>
                        <input type="text" name="z01_numcgm" id="z01_numcgm" maxlength="8" size="10" autocomplete="off"
                               onkeyup="js_ValidaCampos(this,1,'Numcgm','t','f',event);"
                               onkeypress="return validarNumeros(event)" onblur="js_ValidaMaiusculo(this,'f',event);"
                               onchange="js_mostranomes(false);"
                               title="Numero de Identificação do Contribuinte ou Empresa no Cadastro geral do Município Campo: z01_numcgm."/>
                    </td>
                    <td>
                        <?php db_input("z01_nome", 35, $Iz01_nome, true, 'text', 5); ?>
                    </td>
                </tr>
                <tr>
                    <td title="<?php echo $Tj01_matric; ?>">
                        <label for="j01_matric"><?php db_ancora($Lj01_matric, "js_mostramatricula(true,'$j18_nomefunc');", 2); ?></label>
                    </td>
                    <td colspan="2">
                        <input type="text" name="j01_matric" id="j01_matric" maxlength="8" size="10" autocomplete="off"
                               onkeyup="js_ValidaCampos(this,1,'Matrícula do Imóvel','t','f',event);"
                               onkeypress="return validarNumeros(event)" onblur="js_ValidaMaiusculo(this,'f',event);"
                               onchange="js_mostramatricula(false,'<?= $j18_nomefunc ?>');"
                               title="Codigo da matrícula do imovel para identificar o proprietário de um determinado lote. Campo: j01_matric."/>
                    </td>
                </tr>

                <?php if ($db21_usasisagua == false) { ?>
                    <tr>
                        <td title="<?php echo $Tq02_inscr; ?>">
                            <label for="q02_inscr"><?php db_ancora($Lq02_inscr, 'js_mostrainscricao(true);', 4); ?></label>
                        </td>
                        <td colspan="2">
                            <input type="text" name="q02_inscr" id="q02_inscr" maxlength="8" size="10" autocomplete="off"
                                   onkeyup="js_ValidaCampos(this,1,'Inscrição Municipal','t','f',event);"
                                   onkeypress="return validarNumeros(event)"
                                   onblur="js_ValidaMaiusculo(this,'f',event);" onchange="js_mostrainscricao(false);"
                                   title="Inscricao Municipal no cadastro de alvará Campo: q02_inscr."/>
                        </td>
                    </tr>
                <?php } else { ?>
                    <input type="hidden" name="q02_inscr" value="">
                <?php } ?>

                <tr>
                    <td title="Processo">
                        <label for="sequencial_processo_foro"><a onclick="buscarProcessoForo(true);" style="text-decoration: underline;"
                                                                 class="dbancora">Processo:</a></label>
                    </td>
                    <td>
                        <input onchange="buscarProcessoForo();" onkeypress="return validarNumeros(event);"
                               title="Sequencial Processo Foro" id="sequencial_processo_foro" name="sequencial_processo_foro" size="10">
                    </td>
                    <td>
                        <input title="Processo" id="codigo_processo_foro" name="codigo_processo_foro" size="35">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="inicial">
                            <strong>Inicial:</strong>
                        </label>
                    </td>
                    <td colspan="2">
                        <input name="inicial" id="inicial" size="10" onkeypress="return validarNumeros(event)">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="exercicio">
                            <strong>Exercício:</strong>
                        </label>
                    </td>
                    <td colspan="2">
                        <input name="exercicio" id="exercicio" size="10" onkeypress="return validarExercicio(event)">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cda">
                            <strong>CDA:</strong>
                        </label>
                    </td>
                    <td colspan="2">
                        <input name="cda" id="cda" size="10" onkeypress="return validarNumeros(event)">
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="button" value="Pesquisar" name="pesquisar">
    </form>
</div>

<div id="container-dividas">
    <table cellspacing=0 cellpadding=0 style="float:left; width: 100%; padding-right:12px;">
        <thead>
        <tr style="background-color: #8a8a8a !important; height: 25px;">
            <th width="3%" colspan="1"></th>
            <th width="12%" colspan="1">Processo</th>
            <th width="5%" colspan="1">Inicial</th>
            <th width="4%" colspan="1">Exercício</th>
            <th width="6%" colspan="2">CDA</th>
            <th width="25%" colspan="3">Procedência</th>
            <th width="43%" colspan="6">Total</th>
            <th width="2%" colspan="1"></th>
        </tr>
        </thead>
    </table>
    <div style="max-height: 375px; overflow-y: scroll; margin: auto; width: 100%">
        <table cellspacing=0 cellpadding=0 width="100%">
            <tbody id="dados"></tbody>
        </table>
    </div>
</div>
<div class="container">
    <input type="button" value="Processar" name="processar" style="margin-top: 10px">
</div>

<?php db_menu(); ?>

<script>
  const RPC = 'jur4_desmembramento_inicial.RPC.php';

  jQuery('input[name="processar"]').click(processar);
  jQuery('input[name="pesquisar"]').click(pesquisar);

  var cgmInput = document.getElementById('z01_numcgm');
  var matriculaInput = document.getElementById('j01_matric');
  var inscricaoInput = document.getElementById('q02_inscr');
  var sequencialProcessoForoInput = document.getElementById('sequencial_processo_foro');
  var inicialInput = document.getElementById('inicial');
  var exercicioInput = document.getElementById('exercicio');
  var cdaInput = document.getElementById('cda');
  var codigoProcessoForoInput = document.getElementById('codigo_processo_foro');
  var nameInput = document.getElementById('z01_nome');

  var quantidadeDividasPorInicial = [];
  var iniciais = [];

  function validarExercicio(evt) {
    return validarNumeros(evt) && validarTamanhoData(evt);
  }

  function validarNumeros(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;

    return charCode <= 57 || charCode === 118;
  }

  function validarTamanhoData(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;

    return !(evt.target.value > 999 && charCode > 46);
  }

  function buscarProcessoForo(abrir = false) {
    var frame = 'iframe_buscar_processo_foro';
    var width = document.body.clientWidth;
    var height = document.body.clientHeight;
    var arquivo = 'func_processoforo.php?objeto=true&funcao_js=parent.' + callbackBuscarProcessoForo.name;

    if (abrir) {
      arquivo += '|v70_sequencial|v70_codforo';
    } else {
      arquivo += '&pesquisa_chave=' + sequencialProcessoForoInput.value;
    }

    js_OpenJanelaIframe('CurrentWindow.corpo', frame, arquivo, 'Buscar Processo do Foro', abrir, 20, 0, width, height);
  }

  function callbackBuscarProcessoForo(objeto, sequencial) {
    disableAdditionalFilters();

    cgmInput.value = '';
    nameInput.value = '';

    if (typeof(objeto) === 'object') {
      if (Object.keys(objeto).length === 0) {
        sequencialProcessoForoInput.value = '';
        codigoProcessoForoInput.value = 'Chave (' + sequencial + ') não encontrada.';
        return false;
      }

      enableAdditionalFilters();

      sequencialProcessoForoInput.value = sequencial;
      codigoProcessoForoInput.value = objeto.v70_codforo;
    } else {
      sequencialProcessoForoInput.value = objeto;
      codigoProcessoForoInput.value = typeof(sequencial) === 'boolean' ? '' : sequencial;
    }

    return iframe_buscar_processo_foro.hide();
  }

  function js_mostranomes(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_nomes', 'func_nome.php?funcao_js=parent.js_preenche|0|1', 'Pesquisa', true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_nomes', 'func_nome.php?pesquisa_chave=' + document.form1.z01_numcgm.value + '&funcao_js=parent.js_preenche1',
          'Pesquisa', false);
    }
  }

  function js_mostramatricula(mostra, nome_func) {
    document.form1.z01_numcgm.value = '';
    document.form1.q02_inscr.value = '';
    if (mostra == true) {
      if (nome_func != 'func_iptubase.php') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_matric', nome_func + '?funcao_js=parent.js_preenchematricula|0|1', 'Pesquisa', true);
      } else {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_matric', nome_func + '?funcao_js=parent.js_preenchematricula3|0|1|2', 'Pesquisa', true);
      }
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_matric', nome_func + '?pesquisa_chave=' + document.form1.j01_matric.value +
          '&funcao_js=parent.js_preenchematricula2', 'Pesquisa', false);
    }
  }

  function js_preenche(cgm, name) {
    enableAdditionalFilters();

    cgmInput.value = cgm;
    nameInput.value = name;

    db_iframe_nomes.hide();
  }

  function js_preenche1(error, name) {
    nameInput.value = name;

    if (error == true) {
      disableAdditionalFilters();

      cgmInput.value = '';
      cgmInput.focus();
    }
  }

  function js_mostramatricula(mostra, nome_func) {
    document.form1.z01_numcgm.value = '';
    document.form1.q02_inscr.value = '';
    if (mostra == true) {
      if (nome_func != 'func_iptubase.php') {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_matric', nome_func + '?funcao_js=parent.js_preenchematricula|0|1', 'Pesquisa', true);
      } else {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_matric', nome_func + '?funcao_js=parent.js_preenchematricula3|0|1|2', 'Pesquisa', true);
      }
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_matric', nome_func + '?pesquisa_chave=' + document.form1.j01_matric.value +
          '&funcao_js=parent.js_preenchematricula2', 'Pesquisa', false);
    }
  }

  function js_mostrainscricao(mostra) {
    document.form1.j01_matric.value = '';
    document.form1.z01_numcgm.value = '';
    if (mostra == true) {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe', 'func_issbase.php?funcao_js=parent.js_mostra|q02_inscr|z01_nome|q02_dtbaix', 'Pesquisa', true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe', 'func_issbase.php?pesquisa_chave=' + document.form1.q02_inscr.value + '&funcao_js=parent.js_mostra',
          'Pesquisa',
          false);
    }
  }

  function js_preenchematricula3(matricula, error, name) {
    enableAdditionalFilters();

    matriculaInput.value = matricula;
    nameInput.value = name;

    db_iframe_matric.hide();
  }

  function js_preenchematricula2(name, error) {
    disableAdditionalFilters();

    nameInput.value = name;

    if (error == false) {
      enableAdditionalFilters();
    } else {
      matriculaInput.value = '';
    }

    db_iframe_matric.hide();
  }

  function js_mostra(chave1, chave2, baixa) {
    disableAdditionalFilters();

    if (chave2 == false) {
      enableAdditionalFilters();

      nameInput.value = chave1;
    } else {
      inscricaoInput.value = chave1;
      nameInput.value = chave2;
    }

    if (inscricaoInput.value == '') {
      nameInput.value = '';
    }

    if (typeof(baixa) == 'undefined' && chave2 == true) {
      nameInput.value = chave1;
      inscricaoInput.value = '';
    }

    db_iframe.hide();
  }

  function number_format(number, decimals, decPoint, thousandsSep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number;
    var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
    var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
    var dec = (typeof decPoint === 'undefined') ? '.' : decPoint;
    var s = '';

    var toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k).toFixed(prec);
    };

    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
    }

    return s.join(dec);
  }

  var countCheckboxPai = 0;
  var countCheckboxDivida = 0;
  var countIcon = 0;

  function getIniciais() {
    js_divCarregando('Buscando Iniciais', 'loading_message');

    var cgm = cgmInput.value;
    var matric = matriculaInput.value;
    var inscr = inscricaoInput.value;
    var processoForo = sequencialProcessoForoInput.value;
    var inicial = inicialInput.value;
    var exercicio = exercicioInput.value;
    var cda = cdaInput.value;
    var parametros = new FormData();

    parametros.append('exec', 'getIniciais');

    if (cgm) {
      parametros.append('cgm', cgm);
    }

    if (matric) {
      parametros.append('matric', matric);
    }

    if (inscr) {
      parametros.append('inscr', inscr);
    }

    if (processoForo) {
      parametros.append('processoForo', processoForo);
    }

    if (inicial) {
      parametros.append('inicial', inicial);
    }

    if (exercicio) {
      parametros.append('exercicio', exercicio);
    }

    if (cda) {
      parametros.append('cda', cda);
    }

    return fetch(RPC, {
      method: 'POST',
      body: parametros,
      credentials: 'include',
    }).then(response => {
      js_removeObj('loading_message');

      return response.json();
    });
  }

  function montarLinha(inicial, tabela) {
    var identifier = buildIdentifier(inicial);

    var checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.className = 'chkmarca';
    countCheckboxPai++;
    checkbox.id = 'check_' + countCheckboxPai;
    checkbox.setAttribute('data-identifier', identifier);
    checkbox.setAttribute('data-processo', !!inicial.processo);
    checkbox.setAttribute('data-inicial', inicial.inicial);

    var icon = document.createElement('span');
    icon.className = 'icon show';
    icon.title = 'Expandir';
    icon.setAttribute('data-identifier', identifier);
    countIcon++;
    icon.id = 'icon_' + countIcon;

    var tr = document.createElement('tr');
    tr.style.height = '2em';
    tr.style.cursor = 'pointer';
    tr.className = 'inicial';
    tr.setAttribute('codigo-processo', inicial.sequencial_processo);
    tr.appendChild(createTd(checkbox, 'table_inicial', null, '3%'));
    tr.appendChild(createTd(inicial.processo, 'table_inicial', null, '12%'));
    tr.appendChild(createTd(inicial.inicial, 'table_inicial', null, '5%'));
    tr.appendChild(createTd(inicial.exercicio, 'table_inicial', null, '4%'));
    tr.appendChild(createTd(inicial.cda, 'table_inicial', 2, '6%'));
    tr.appendChild(createTd(inicial.procedencias.join(', '), 'table_inicial', 3, '25%'));
    tr.appendChild(createTd(number_format(inicial.valor, 2, ',', '.'), 'table_inicial', 6, '43%'));
    tr.appendChild(createTd(icon, 'table_inicial', null, '2%'));
    tr.addEventListener('click', event => {
      if (event.target.tagName === 'TD') {
        icon.dispatchEvent(new Event('click'));
      }
    });

    tabela.appendChild(tr);

    if (inicial.dividas && inicial.dividas.length > 0) {
      createChildrenTable(inicial, tabela);
    }
  }

  function buildIdentifier(data) {
    return data.processo.toString() + data.inicial.toString() + data.exercicio.toString() + data.cda.toString();
  }

  function createChildrenTable(data, table) {
    var identifier = buildIdentifier(data);

    var dividasTr = document.createElement('tr');
    dividasTr.style.display = 'none';
    dividasTr.appendChild(createTh('', 'table_header_children'));
    dividasTr.appendChild(createTh('CDA', 'table_header_children'));
    dividasTr.appendChild(createTh('Divida', 'table_header_children'));
    dividasTr.appendChild(createTh('Numpre', 'table_header_children'));
    dividasTr.appendChild(createTh('P', 'table_header_children'));
    dividasTr.appendChild(createTh('T', 'table_header_children'));
    dividasTr.appendChild(createTh('Dt. oper.', 'table_header_children'));
    dividasTr.appendChild(createTh('Dt Venc.', 'table_header_children'));
    dividasTr.appendChild(createTh('Receita', 'table_header_children th_coluna_receita'));
    dividasTr.appendChild(createTh('Val. Histórico', 'table_header_children th_coluna_valor'));
    dividasTr.appendChild(createTh('Val. Corrigido', 'table_header_children th_coluna_valor'));
    dividasTr.appendChild(createTh('Val. Juros', 'table_header_children th_coluna_valor'));
    dividasTr.appendChild(createTh('Val. Multa', 'table_header_children th_coluna_valor'));
    dividasTr.appendChild(createTh('Val. Desconto', 'table_header_children th_coluna_valor'));
    dividasTr.appendChild(createTh('Val. Total', 'table_header_children th_coluna_valor'));
    dividasTr.appendChild(createTh('', 'table_header_children'));
    dividasTr.setAttribute('data-identifier', identifier);

    table.appendChild(dividasTr);

    var dividas = data.dividas;

    countCheckboxDivida = 0;

    dividas.map(divida => {
      var infos = divida.infos;
      var backgroundColor = dividas.indexOf(divida) % 2 === 0 ? '#fff' : '#eeeff2';
      var rowspan = infos.length;

      var checkbox = document.createElement('input');
      checkbox.type = 'checkbox';
      countCheckboxDivida++;
      checkbox.id = 'check_' + countCheckboxPai.toString() + countCheckboxDivida;
      checkbox.className = 'divida';
      checkbox.value = divida.divida;
      checkbox.setAttribute('data-processo', !!data.processo);
      checkbox.setAttribute('data-inicial', data.inicial);

      var dividaTr = document.createElement('tr');
      dividaTr.setAttribute('codigo-processo', data.sequencial_processo);
      dividaTr.setAttribute('data-identifier', identifier);
      dividaTr.style.backgroundColor = backgroundColor;
      dividaTr.style.display = 'none';

      var checkboxTd = createTd(checkbox);
      checkboxTd.rowSpan = rowspan;

      dividaTr.appendChild(checkboxTd);

      var cdaTd = createTd(data.cda);
      cdaTd.rowSpan = rowspan;

      dividaTr.appendChild(cdaTd);

      var dividaTd = createTd(divida.divida);
      dividaTd.rowSpan = rowspan;

      dividaTr.appendChild(dividaTd);

      infos.map(info => {
        if (infos.indexOf(info) === 0) {
          table.appendChild(createTdInfos(info, dividaTr, backgroundColor));
        } else {
          var infoTr = document.createElement('tr');
          infoTr.setAttribute('data-identifier', identifier);
          infoTr.style.backgroundColor = backgroundColor;
          infoTr.style.display = 'none';

          table.appendChild(createTdInfos(info, infoTr, backgroundColor));
        }
      });
    });
  }

  var mascaraData = valor => {
    var data = new Date(valor);
    data.setDate(data.getDate() + 1);
    return data.toLocaleDateString();
  };

  var mascaraMonetaria = valor => {
    return number_format(valor, 2, ',', '.');
  };

  function createTdInfos(infos, tr, backgroundColor) {
    var style = {
      height: '2em',
      backgroundColor: backgroundColor,
    };

    var numpreTd = createTd(infos.numpre);
    numpreTd.style = style;

    var pTd = createTd(infos.p);
    pTd.style = style;

    var tTd = createTd(infos.t);
    tTd.style = style;

    var dtOperTd = createTd(mascaraData(infos.dt_oper));
    dtOperTd.style = style;

    var valHistoricoTd = createTd(mascaraMonetaria(infos.val_historico));
    valHistoricoTd.style = style;

    var valJurosTd = createTd(mascaraMonetaria(infos.val_juros));
    valJurosTd.style = style;

    var valMultaTd = createTd(mascaraMonetaria(infos.val_multa));
    valMultaTd.style = style;

    var valDescontoTd = createTd(mascaraMonetaria(infos.val_desconto));
    valDescontoTd.style = style;

    var valCorrigidoTd = createTd(mascaraMonetaria(infos.val_corrigido));
    valCorrigidoTd.style = style;

    var receitaTd = createTd(infos.receita);
    receitaTd.style = style;

    var dtVencTd = createTd(mascaraData(infos.dt_venc));
    dtVencTd.style = style;

    var totalTd = createTd(mascaraMonetaria(infos.total));
    totalTd.style = style;

    tr.appendChild(numpreTd);
    tr.appendChild(pTd);
    tr.appendChild(tTd);
    tr.appendChild(dtOperTd);
    tr.appendChild(dtVencTd);
    tr.appendChild(receitaTd);
    tr.appendChild(valHistoricoTd);
    tr.appendChild(valCorrigidoTd);
    tr.appendChild(valJurosTd);
    tr.appendChild(valMultaTd);
    tr.appendChild(valDescontoTd);
    tr.appendChild(totalTd);
    tr.appendChild(createTd(''));
    tr.setAttribute('numpre', infos.numpre);

    return tr;
  }

  function createTd(value, className, colspan, width) {

    if (typeof className === 'undefined') {
      className = 'linhagrid';
    }

    var td = document.createElement('td');
    td.className = className;

    if (typeof value === 'object') {
      td.appendChild(value);
    } else {
      td.innerHTML = value;
    }

    if (colspan) {
      td.colSpan = colspan;
    }

    if (width) {
      td.style.width = width;
    }

    return td;
  }

  function createTh(value, className, colspan) {
    var th = document.createElement('th');
    th.className = className;
    th.innerHTML = value;

    if (typeof colspan !== 'undefined') {
      th.colSpan = colspan;
    }

    return th;
  }

  function showHide() {
    var identifier = jQuery(this).attr('data-identifier'),
        selector = 'tr[data-identifier="' + identifier + '"]';

    if (jQuery(this).hasClass('show')) {
      jQuery(selector).show(200);
      jQuery(this).removeClass('show');
      jQuery(this).addClass('close');
      jQuery(this).attr('title', 'Fechar');
    } else {

      var checked = false;
      jQuery('tr[data-identifier="' + identifier + '"]').each((index, tr) => {
        if (!tr.firstChild.firstChild) {
          return;
        }
        if (tr.firstChild.firstChild.checked) {
          checked = true;
        }
      });

      if (checked) {
        return;
      }

      jQuery(selector).hide(100);
      jQuery(this).removeClass('close');
      jQuery(this).addClass('show');
      jQuery(this).attr('title', 'Expandir');
    }
  }

  function checkAll() {
    var identifier = jQuery(this).attr('data-identifier'),
        value = jQuery(this).is(':checked'),
        selector = 'tr[data-identifier="' + identifier + '"]',
        hasProcess = jQuery(this).attr('data-processo') === 'true',
        initial = jQuery(this).attr('data-inicial');

    var codigoProcesso = jQuery(this).context.parentElement.parentElement.getAttribute('codigo-processo');

    jQuery(selector).each(function() {
      var checkbox = jQuery(this).find('input[type="checkbox"]');

      if (checkbox) {
        checkbox.prop('checked', value);
      }
    });

    disableCheckboxProcess(hasProcess, value, codigoProcesso);
    disableCheckboxInitial(initial, value, hasProcess, codigoProcesso);
  }

  function checkParent() {
    var identifier = jQuery(this).closest('tr').attr('data-identifier'),
        selector = 'tr[data-identifier="' + identifier + '"]',
        hasProcess = jQuery(this).attr('data-processo') === 'true',
        initial = jQuery(this).attr('data-inicial'),
        value = jQuery(this).is(':checked'),
        check = true;

    var checkedNumpre = jQuery(this).context.parentElement.parentElement.getAttribute('numpre');
    var codigoProcesso = jQuery(this).context.parentElement.parentElement.getAttribute('codigo-processo');
    var trs = jQuery('tr[numpre="' + checkedNumpre + '"]');

    trs.map((index, tr) => {
      tr.firstChild.firstChild.checked = value;
    });

    jQuery(selector).each((index, tr) => {
      if (tr.firstChild.firstChild === null) {
        return;
      }

      if (!tr.firstChild.firstChild.checked) {
        check = false;
      }
    });

    jQuery('input[type="checkbox"].chkmarca[data-identifier="' + identifier + '"]').prop('checked', check);

    disableCheckboxProcess(hasProcess, value, codigoProcesso);
    disableCheckboxInitial(initial, value, hasProcess, codigoProcesso);
  }

  // Variável para controle se existe algum checkbox desabilitado.
  var checkboxDisabledProcess = false;

  function disableCheckboxProcess(hasProcess, isChecked, codigoProcesso) {

    var hasChecked = false;

    jQuery('input[type="checkbox"][data-processo="' + hasProcess + '"]').each(function() {
      if (hasChecked) {
        return;
      }

      if (jQuery(this).is(':checked')) {
        hasChecked = true;
      }
    });

    if (checkboxDisabledProcess && hasChecked) {
      return false;
    }

    jQuery('input[type="checkbox"]').each(function() {

      if (hasProcess && jQuery(this).attr('data-processo') === 'true') {
        return true;
      }

      if (!hasProcess && jQuery(this).attr('data-processo') === 'false') {
        return true;
      }

      checkboxDisabledProcess = isChecked;

      var tr = jQuery(this).context.parentElement.parentElement;

      if (codigoProcesso !== tr.getAttribute('codigo-processo')) {
        jQuery(this).prop('disabled', isChecked);
      }
    });
  }

  // Variável para controle se existe algum checkbox desabilitado.
  var checkboxDisabledInitial = false;

  function disableCheckboxInitial(initial, isChecked, hasProcess, codigoProcesso) {
    var hasChecked = false;
    var inputsSelector;

    if (hasProcess) {
      inputsSelector = jQuery('tr[codigo-processo="' + codigoProcesso + '"] > td > input');
    } else {
      inputsSelector = jQuery('input[type="checkbox"][data-inicial="' + initial + '"]');
    }

    inputsSelector.each(function() {
      if (hasChecked) {
        return;
      }

      if (jQuery(this).is(':checked')) {
        hasChecked = true;
      }
    });

    if (checkboxDisabledInitial && hasChecked) {
      return false;
    }

    jQuery('input[type="checkbox"]').each(function() {
      var tr = jQuery(this).context.parentElement.parentElement;

      if (initial !== jQuery(this).attr('data-inicial')) {
        checkboxDisabledInitial = isChecked;
        if (!hasProcess || codigoProcesso !== tr.getAttribute('codigo-processo')) {
          jQuery(this).prop('disabled', isChecked);
        }
      }
    });
  }

  function processar() {
    var dividas = [],
        dividasPorInicial = [];

    jQuery('input[type="checkbox"].divida').each(function() {
      if (jQuery(this).is(':checked')) {
        dividas.push(jQuery(this).val());

        var inicial = jQuery(this).attr('data-inicial');

        if (typeof dividasPorInicial[inicial] === "undefined") {
          dividasPorInicial[inicial] = 1;
        } else {
          dividasPorInicial[inicial]++;
        }
      }
    });

    var iniciaisSelecionadas = iniciais.filter(inicial => {
      var selecionados = inicial.dividas.filter(divida => dividas.indexOf(divida.divida) !== -1).map(divida => divida.divida);

      return selecionados.length > 0;
    }).map(inicial => inicial.inicial);

    for (var inicial of iniciaisSelecionadas) {
      if (dividasPorInicial[inicial] >= quantidadeDividasPorInicial[inicial]) {
        return alert('Não é possível desmembrar uma inicial na sua integralidade. Inicial: ' + inicial);
      }
    }

    if (dividas.length < 1) {
      alert('Nenhuma dívida selecionada para desmembramento.');
      return false;
    }

    var msgConfirm = 'Você tem certeza que deseja efetuar o desmembramento? Esta operação é irreversível.';

    if (!confirm(msgConfirm)) {
      return false;
    }

    js_divCarregando('Processando Iniciais', 'loading_message');

    var parametros = new FormData();
    parametros.append('exec', 'desmembrarInicial');
    parametros.append('dividas', dividas);

    return fetch(RPC, {
      method: 'POST',
      body: parametros,
      credentials: 'include',
    }).then(response => {
      js_removeObj('loading_message');

      return response.json();
    }).then(response => {
      alert(response.message);
      if (!response.erro) {
        pesquisar();
      }
    });
  }

  function pesquisar() {
    getIniciais().then(data => {
      if (data.erro) {
        return alert(data.message);
      }

      quantidadeDividasPorInicial = data.quantidadeDividasPorInicial;

      iniciais = data.iniciais;
      var table = document.getElementById('dados');
      table.innerHTML = '';

      if (!iniciais.length) {
        return alert('Nenhuma inicial encontrada.');
      }

      iniciais.map(inicial => {
        montarLinha(inicial, table);
      });

      jQuery('.icon').click(showHide);
      jQuery('.chkmarca').change(checkAll);
      jQuery('input[type="checkbox"].divida').change(checkParent);
    });
  }

  // Ready

  disableAdditionalFilters();
  disableDescriptionInputs();
  addEventListeners();

  function disableAdditionalFilters() {
    inicialInput.disabled = true;
    exercicioInput.disabled = true;
    cdaInput.disabled = true;
  }

  function enableAdditionalFilters() {
    inicialInput.disabled = false;
    exercicioInput.disabled = false;
    cdaInput.disabled = false;
  }

  function disableDescriptionInputs() {
    codigoProcessoForoInput.disabled = true;
    nameInput.disabled = true;
  }

  function addEventListeners() {
    cgmInput.addEventListener('change', shouldEnableFilters);
    matriculaInput.addEventListener('change', shouldEnableFilters);
    inscricaoInput.addEventListener('change', shouldEnableFilters);
    sequencialProcessoForoInput.addEventListener('change', shouldEnableFilters);
  }

  function shouldEnableFilters() {
    disableAdditionalFilters();

    if (cgmInput.value || matriculaInput.value || inscricaoInput.value || sequencialProcessoForoInput.value) {
      enableAdditionalFilters();
    }
  }
</script>
</body>
</html>

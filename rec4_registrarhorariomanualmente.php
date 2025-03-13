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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js");
    db_app::load("strings.js");
    db_app::load("prototype.js");
    db_app::load("estilos.css");
    db_app::load("AjaxRequest.js");
    db_app::load("widgets/DBLookUp.widget.js");
    db_app::load("datagrid.widget.js");
    db_app::load("widgets/Collection.widget.js");
    db_app::load("widgets/DatagridCollection.widget.js");
    db_app::load("widgets/DBInputHora.widget.js");
    db_app::load("widgets/Input/DBInputDate.widget.js");
    ?>
</head>
<body>
<div class="container">
  <form>
    <fieldset>
      <legend>Registro de Horário Manual</legend>

      <table class="form-container">

        <tr>
          <td>
            <label for="rh01_regist">
              <a href="#" id="ancoraMatricula">Matrícula:</a>
            </label>
          </td>
          <td colspan="3">
            <input id="rh01_regist" type="text" value="" class="field-size2" />
            <input id="z01_nome"    type="text" value="" class="field-size7 readonly" disabled="disabled" />
          </td>
        </tr>

        <tr>
          <td>
            <label for="data">Data:</label>
          </td>
          <td>
            <input id="data" type="text" value="<?=date('d/m/Y')?>" class="field-size2 input-data" />
          </td>
        </tr>

        <tr>
          <td>
            <label for="data">Entrada 1:</label>
          </td>

          <td>
            <input id="entrada1" type="text" value="" class="field-size2 input-hora" />
          </td>

          <td>
            <label for="data">Saída 1:</label>
          </td>

          <td>
            <input id="saida1" type="text" value="" class="field-size2 input-hora" />
          </td>
        </tr>

        <tr>
          <td>
            <label for="data">Entrada 2:</label>
          </td>

          <td>
            <input id="entrada2" type="text" value="" class="field-size2 input-hora" />
          </td>

          <td>
            <label for="data">Saída 2:</label>
          </td>

          <td>
            <input id="saida2" type="text" value="" class="field-size2 input-hora" />
          </td>
        </tr>

        <tr>
          <td>
            <label for="data">Entrada 3:</label>
          </td>

          <td>
            <input id="entrada3" type="text" value="" class="field-size2 input-hora" />
          </td>

          <td>
            <label for="data">Saída 3:</label>
          </td>

          <td>
            <input id="saida3" type="text" value="" class="field-size2 input-hora" />
          </td>
        </tr>

      </table>

    </fieldset>

    <input id="pesquisar" type="button" value="Pesquisar" onclick="buscarRegistros()" />
    <input id="limpar_horarios"   type="button" value="Limpar Horários"   onclick="limparHorarios()" />
    <input id="incluir"   type="button" value="Incluir"   onclick="incluirRegistros()" />
  </form>
  <p>Obs.: Pressione Ctrl + Enter para incluir os horários.</p>
</div>

<div id="registrosPonto" style="width: 800px; margin: 0 auto;">
  <fieldset>
    <legend>Registros</legend>
    <div id="containerGridRegistros"></div>
  </fieldset>
</div>
</body>
<?php db_menu(); ?>
<script>

  var contador = 0;
  var oLookupMatricula = new DBLookUp(
    $('ancoraMatricula'),
    $('rh01_regist'),
    $('z01_nome'),
    {
      'sArquivo'  : 'func_rhpessoal.php',
      'sLabel'    : 'Pesquisar Servidor'
    }
  );

  var oCollectionRegistros = new Collection();
  oCollectionRegistros.setId('sequencial');

  var oGridRegistros    = new DatagridCollection(oCollectionRegistros, 'gridRegistros').configure({'order' : false});
  oGridRegistros.addColumn('servidor',     {'width': '40%', 'label': 'Servidor'}).transform(function (sValue, oItem) {
    return oItem.matricula +' - '+ oItem.nome;
  });
  oGridRegistros.addColumn('data',         {'width': '15%', 'label': 'Data'}).transform(function (sValue, oItem) {
    return criaElemento(sValue, oItem, 2);
  }).configure({'align':'center'});
  oGridRegistros.addColumn('hora',         {'width': '15%', 'label': 'Hora'}).transform(function (sValue, oItem) {
    return criaElemento(sValue, oItem, 3);
  }).configure({'align':'center'});


  oGridRegistros.addAction('Salvar', 'Salvar alteração na marcação', function(event, registro) {

    AjaxRequest.create(
      'rec4_registrarhorariomanualmente.RPC.php',
      {
        'exec'       : 'salvarRegistros',
        'sequencial' : registro.sequencial,
        'data'       : $F('marcacao_'+ registro.sequencial +'_2'),
        'hora'       : $F('marcacao_'+ registro.sequencial +'_3'),
        'pontoeletronicoarquivoimportacao'  : registro.pontoeletronicoarquivoimportacao,
        'matricula'                         : registro.matricula,
        'pis'                               : registro.pis,
      },
      function(oRetorno, lErro) {

        alert(oRetorno.mensagem);
        if(lErro) {
          return false;
        }

        oRetorno.agrupador = registro.agrupador;
        oCollectionRegistros.add(oRetorno.sequencial);
        oCollectionRegistros.sort('desc', ['agrupador', 'sequencial'], funcaoOrdenacaoRegistros);
        oGridRegistros.reload();
      }
    ).asynchronous(false).setMessage('Aguarde, buscando registros...').execute();
  });

  oGridRegistros.addAction('Excluir', 'Excluir marcação do ponto', function(event, registro) {

    AjaxRequest.create(
      'rec4_registrarhorariomanualmente.RPC.php',
      {
        'exec'       : 'excluirRegistros',
        'sequencial' : registro.sequencial
      },
      function(oRetorno, lErro) {

        alert(oRetorno.mensagem);
        if(lErro) {
          return false;
        }

        oCollectionRegistros.remove(registro.sequencial);
        oGridRegistros.reload();
      }
    ).asynchronous(false).setMessage('Aguarde, buscando registros...').execute();
  });

  oGridRegistros.configure({'height': '400px'});
  oGridRegistros.setEvent('onafterrenderrows', function() {

    $$('.input-data-grid').each(function (item) {
      new DBInputDate(item);
    });

    $$('.input-hora-grid').each(function (item) {
      new DBInputHora(item);
    });
  });
  // oGridRegistros.hideColumns([0]);
  oGridRegistros.show($('containerGridRegistros'));

  /**
   * Cria um elemento para cada entrada/saída
   * @param sValue
   * @param oItem
   * @param iTipo
   * @returns {string}
   */
  function criaElemento(sValue, oItem, iTipo) {

    var oDiv           = new Element('div');
    var oElementoDiv   = criaElementoDiv(oItem, iTipo);
    var oElementoInput = criaElementoInput(oItem, iTipo);

    oDiv.appendChild(oElementoDiv);
    oDiv.appendChild(oElementoInput);

    return oDiv.outerHTML;
  }

  /**
   * Cria o elemento input para edição das horas
   */
  function criaElementoInput(oItem, iTipo) {

    var iCodigo       = null;
    var oElemento     = new Element('input');
    var marcacao      = oItem;
    var tipoElemento  = null;

    switch(iTipo) {

      case 2:
        tipoElemento  = 'data';
        break;

      case 3:
        tipoElemento  = 'hora';
        break;
    }

    iCodigo = marcacao.sequencial;

    oElemento.setAttribute('id', 'marcacao_' + iCodigo + '_' + iTipo);

    if(tipoElemento) {
      oElemento.setAttribute('tipo-elemento', tipoElemento);
      oElemento.addClassName('field-size2');
      oElemento.setStyle({'text-align': 'center'});
    }

    if(tipoElemento == 'data') {
      oElemento.setAttribute('value', marcacao.data);
      oElemento.addClassName('input-data');
      oElemento.addClassName('input-data-grid');
    }

    if(tipoElemento == 'hora') {
      oElemento.setAttribute('value', marcacao.hora);
      oElemento.addClassName('input-hora');
      oElemento.addClassName('input-hora-grid');
    }

    return oElemento;
  }

  /**
   * Cria DIV com o texto da folga/DSR
   */
  function criaElementoDiv(oItem, iTipo) {

    var oElemento           = new Element('div');
    oElemento.setStyle({'text-align': 'center', 'display' : 'none'});
    oElemento.setAttribute('data', oItem.data);

    return oElemento;
  }


  /**
   * Verifica se todos os campos foram prrenchidos para pesquisa do ponto
   * @returns {boolean}
   */
  function validaCampos() {

    if(empty($F('rh01_regist'))) {

      alert('Selecione uma Matrícula.');
      return false;
    }

    if(empty($F('data'))) {

      alert('Data não informada.');
      return false;
    }

    if (
      empty($F('entrada1')) &&
      empty($F('entrada2')) &&
      empty($F('entrada3')) &&
      empty($F('saida1')) &&
      empty($F('saida2')) &&
      empty($F('saida3'))
    ) {
      alert('Nenhum horário informado.');
      return false;
    }

    return true;
  }

  /**
   * Busca os registros do ponto do servidor no período selecionado
   * @returns {boolean}
   */
  function buscarRegistros() {

    AjaxRequest.create(
      'rec4_registrarhorariomanualmente.RPC.php',
      {
        'exec'      : 'buscarRegistros',
        'data'      : $F('data'),
        'matricula' : $F('rh01_regist')
      },
      function(oRetorno, lErro) {

        if(lErro) {
          alert(oRetorno.mensagem);
          return false;
        }

        montarGrid(oRetorno);
      }
    ).asynchronous(false).setMessage('Aguarde, buscando registros...').execute();
  }

  function incluirRegistros() {

    if(!validaCampos()) {
      return false;
    }

    var horarios = [
      $F('entrada1'),
      $F('saida1'),
      $F('entrada2'),
      $F('saida2'),
      $F('entrada3'),
      $F('saida3')
    ];

    AjaxRequest.create(
      'rec4_registrarhorariomanualmente.RPC.php',
      {
        'exec'      : 'incluirRegistros',
        'data'      : $F('data'),
        'horarios'  : horarios,
        'matricula' : $F('rh01_regist')
      },
      function(oRetorno, lErro) {

        if(lErro) {
          alert(oRetorno.mensagem);
          return false;
        }

        contador++;

        for (var horario of oRetorno.registro) {
          
          horario.agrupador = contador;
          oCollectionRegistros.add(horario);
        }
        oCollectionRegistros.sort('desc', ['agrupador', 'sequencial'], funcaoOrdenacaoRegistros);

        oGridRegistros.reload();

        var newDate = new Date($F('data').replace( /(\d{2})\/(\d{2})\/(\d{4})/, "$2/$1/$3"));
        newDate.setDate(newDate.getDate() + 1);

        var day = newDate.getDate();
        if (day < 10) {
          day = '0' + day;
        }

        var month = newDate.getMonth() + 1;
        if (month < 10) {
          month = '0' + month;
        }

        $('data').value = day + '/' + (month) + '/' + newDate.getFullYear();
        $('rh01_regist').focus();
      }
    ).asynchronous(false).setMessage('Aguarde, incluindo registros...').execute();
  }

  /**
   * Monta a grid
   */
  function montarGrid(oRetorno, lSalvarAutomatico) {

    oGridRegistros.clear();

    if(oRetorno.aRegistros && oRetorno.aRegistros.length > 0) {

      var aDados = oRetorno.aRegistros;
      var matricula, data;
      contador   = 0;

      aDados.each(function(registro) {

        if(data != registro.data || matricula != registro.matricula) {
          contador++;
        }

        matricula = registro.matricula;
        data      = registro.data;

        registro.agrupador = contador;
        oCollectionRegistros.add(registro);
      });
      oCollectionRegistros.sort('desc', ['agrupador', 'sequencial'], funcaoOrdenacaoRegistros);
    }

    oGridRegistros.reload();
  }

  function limparHorarios()
  {
    $('entrada1').value = '';
    $('entrada2').value = '';
    $('entrada3').value = '';
    $('saida1').value = '';
    $('saida2').value = '';
    $('saida3').value = '';
    $('entrada1').focus();
  }

  /**
   * @param current
   * @param next
   */
  function nextHourField(current, next)
  {
    $(current).observe('keyup', function() {
      if ($(current).value.length >= 5) {

        var error = DBInputHora.validate($(current), function(message) {
          alert(message);
        });

        if (!error) {
          $(current).value = '';
        } else {
          $(next).focus();
        }
      }
    });
  }

  funcaoOrdenacaoRegistros = (item1, item2, campo) => {

      var ordem = 0;

      if (item1[campo] > item2[campo]) {
        ordem = 1;
      }

      if (item1[campo] < item2[campo]) {
        ordem = -1;
      }

      if(ordem != 0) {

        if(campo === 'sequencial') {
          ordem = ordem * (-1);
        }
      }

      return ordem;
  }

  document.observe('keydown', function(event) {
    if (event.ctrlKey && event.keyCode === 13) {
      incluirRegistros();
    }
  });

  $('rh01_regist').observe('keyup', function() {
    if (!$('rh01_regist').value) {
      limparHorarios();
    }
  });

  nextHourField('entrada1', 'saida1');
  nextHourField('saida1', 'entrada2');
  nextHourField('entrada2', 'saida2');
  nextHourField('saida2', 'entrada3');
  nextHourField('entrada3', 'saida3');

  $$('.input-data').each(function (item) {
    new DBInputDate(item);
  });

  $$('.input-hora').each(function (item) {
    new DBInputHora(item);
  });

  document.observe('dom:loaded', function() {
    $('rh01_regist').focus();
  });
</script>
</html>

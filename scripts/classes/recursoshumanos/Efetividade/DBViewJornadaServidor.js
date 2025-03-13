require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/DBLancador.widget.js');
require_once('scripts/widgets/DBLookUp.widget.js');
require_once('scripts/widgets/Input/DBInput.widget.js');
require_once('scripts/widgets/Input/DBInputDate.widget.js');

/**
 * DBView responsável por realizar o lançamento de um assentamento funcional
 * a partir de um assentamento por efetividade.
 */
var DBViewJornadaServidor = function (iCodigoJornadaServidor) {

  this.iCodigoJornadaServidor = iCodigoJornadaServidor;

  this.oWindow = null;

  this.dataInicio                 = null;
  this.dataFim                    = null;
  this.filtro                     = null;
  this.servidor                   = {
    matricula : null,
    nome      : null,
  };
  this.periodo = {
    inicio   : null,
    fim      : null,
  };
  this.lNovaJornada               = true;
  this.lTelaManutencaoIndividual  = false;
  this.oLancadorMatricula         = null;
  this.fCallBack  = {
    close : function() {}
  }
};

DBViewJornadaServidor.prototype.getCodigoJornadaServidor = function () {
  return this.iCodigoJornadaServidor;
};

DBViewJornadaServidor.prototype.setCodigoJornadaServidor = function (iCodigoJornadaServidor) {
  this.iCodigoJornadaServidor = iCodigoJornadaServidor;
  this.setNovaJornada(false);
  return this;
};

DBViewJornadaServidor.prototype.carregarFormulario = function (lTelaManutencaoIndividual) {

  var sTitulo = 'Alterar Jornada do Servidor';

  if(this.lNovaJornada === true) {
    sTitulo = 'Nova Jornada para o Servidor';
  }

  var sFormulario = 'rec4_jornadaservidor.php';

  this.oWindow = new windowAux('AlterarJornadaServidor', sTitulo, 800, 500);
  this.oWindow.zIndex = 2;
  this.oWindow.setContent('');
  this.oWindow.setShutDownFunction(function () {
    this.fechar();
  }.bind(this));
  this.oWindow.show();

  this.oWindow.getContentContainer().load(
    sFormulario,
    function () {

      if(!this.getCodigoJornadaServidor()) {
        
        this.oLancadorMatricula = new DBLancador('oLancadorMatricula');
        this.oLancadorMatricula.setLabelAncora('Matrícula:');
        this.oLancadorMatricula.setNomeInstancia('viewJornadaServidor.oLancadorMatricula');
        this.oLancadorMatricula.setTituloJanela('Pesquisa de Matrícula');
        this.oLancadorMatricula.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist', 'z01_nome']);
        this.oLancadorMatricula.setTextoFieldset('Matrículas');
        this.oLancadorMatricula.setGridHeight(150);
        this.oLancadorMatricula.show($('ctnLancadorMatricula'));
      }

      if(!this.isTelaManutencaoIndividual()) {
        
        this.dataInicio = new DBInputDate($('rh212_data_inicio'));
        this.dataFim    = new DBInputDate($('rh212_data_fim'));
      }

      if(this.getCodigoJornadaServidor()) {
        this.carregarJornada(this.getCodigoJornadaServidor());
      }

      this.setarEventosComportamentosBotoes();

    }.bind(this)
  );
};

DBViewJornadaServidor.prototype.setarEventosComportamentosBotoes = function () {

  $('excluirJornada').observe('click', function () {
    this.excluir(this.getCodigoJornadaServidor())
  }.bind(this));

  $('salvarJornada').observe('click', function () {

    if (!this.validarJornada()) {
      return false;
    }

    this.salvar(true);
  }.bind(this));

  if(this.isNovaJornada()) {

    $('excluirJornada').disabled = true;

  } else {

    $('linhafiltroServidores').style.display = 'none';
    $('linhaSelecao').style.display          = 'none';
    $('ctnLancadorMatricula').style.display  = 'none';
    
    $('linhaMatricula').style.display   = 'table-row';
    $('rh212_matricula').value          = this.servidor.matricula;
    $('z01_nome').readOnly              = true;
    $('z01_nome').value                 = this.servidor.nome;
    $('z01_nome').style.backgroundColor = 'rgb(222, 184, 135)';

    this.dataInicio.setValue(this.periodo.inicio);
    this.dataFim.setValue(this.periodo.fim);
  }

  this.setarAncoras();

  if(this.isTelaManutencaoIndividual()) {
    
    $('linhafiltroServidores').style.display = 'none';
    $('linhaSelecao').style.display          = 'none';
    $('ctnLancadorMatricula').style.display  = 'none';
    
    $('linhaMatricula').style.display = 'table-row';

    $('rh212_matricula').readOnly               = true;
    $('rh212_matricula').value                  = this.servidor.matricula;
    $('rh212_matricula').style.backgroundColor = 'rgb(222, 184, 135)';
    
    $('z01_nome').readOnly               = true;
    $('z01_nome').value                  = this.servidor.nome;
    $('z01_nome').style.backgroundColor = 'rgb(222, 184, 135)';

    $('rh212_data_inicio').readOnly               = true;
    $('rh212_data_inicio').value                  = this.periodo.inicio;
    $('rh212_data_inicio').style.backgroundColor  = 'rgb(222, 184, 135)';

    $('rh212_data_fim').readOnly               = true;
    $('rh212_data_fim').value                  = this.periodo.fim;
    $('rh212_data_fim').style.backgroundColor  = 'rgb(222, 184, 135)';
  }
};

DBViewJornadaServidor.prototype.setarAncoras = function (iCodigoJornadaServidor) {

  $('filtroServidores').observe('change', function() {

    $('linhaSelecao').setStyle({'display': 'none'});
    $('ctnLancadorMatricula').setStyle({'display': 'none'});

    /**
     * Filtrar por Seleção
     */
    if($F('filtroServidores') == 'S') {

      $('linhaSelecao').setStyle({'display': ''});
      $('ctnLancadorMatricula').setStyle({'display': 'none'});
      this.oLancadorMatricula.clearAll();
    }

    /**
     * Filtrar por Matrícula
     */
    if($F('filtroServidores') == 'M') {

      $('linhaSelecao').setStyle({'display': 'none'});
      $('ctnLancadorMatricula').setStyle({'display': 'block'});
      $('r44_selec').value = '';
      $('r44_descr').value = '';
    }
  }.bind(this));

  /**
   * Ancora da seleção
   */
  new DBLookUp(
    $('selecao'),
    $('r44_selec'),
    $('r44_descr'),
    {
      'sArquivo': 'func_selecao.php',
      'sLabel': 'Pesquisa de Seleção'
    }
  );

    /**
   * Ancora da jornada
   */
  new DBLookUp(
    $('jornada'),
    $('rh212_jornada'),
    $('descricao_jornada'),
    {
      'sArquivo'          : 'func_jornada.php',
      'sLabel'            : 'Pesquisa de Jornada',
      'aCamposAdicionais' : ['rh188_tipo'],
      'fCallBack'         : function(codigo, descricao, tipo) {
        
        var descricaoTipo = 'Dia de Trabalho';
        
        switch (tipo) {
                    
          case 'F':
            descricaoTipo = 'Folga';
            break;

          case 'D':
            descricaoTipo = 'DSR';
            break;
          
          default:
            descricaoTipo = 'Dia de Trabalho';
            break;
        }

        $('descricao_jornada').value = descricao;
        $('tipo_jornada').value      = descricaoTipo;
      },
    }
  );


  /**
   * Ancora da matrícula
   */
  if(!this.isTelaManutencaoIndividual()) {
    
    new DBLookUp(
      $('matricula'),
      $('rh212_matricula'),
      $('z01_nome'),
      {
        'sArquivo': 'func_rhpessoal.php',
        'sLabel': 'Pesquisa de Matrícula'
      }
    );
  }
};

DBViewJornadaServidor.prototype.carregarJornada = function (iCodigoJornadaServidor) {

  AjaxRequest.create(
    'rec4_jornadaservidor.RPC.php',
    {
      exec  : 'getJornadaServidor',
      codigo: iCodigoJornadaServidor
    },
    function (response, error) {

      if (response.mensagem) {
        alert(response.mensagem);
      }

      if (error) {
        return false;
      }

      this.periodo  = {
        inicio    : response.jornadas[0].data,
        fim       : response.jornadas[0].data,
      }
      this.servidor = response.jornadas[0].servidor;

      $('rh212_jornada').value       = response.jornadas[0].jornada.codigo;
      $('descricao_jornada').value   = response.jornadas[0].jornada.descricao;
      $('tipo_jornada').value        = response.jornadas[0].jornada.tipo;

    }.bind(this)
  ).setMessage('Buscando jornadas...').asynchronous(false).execute();
};

DBViewJornadaServidor.prototype.isNovaJornada = function () {
  return this.lNovaJornada;
};

DBViewJornadaServidor.prototype.setNovaJornada = function (novaJornada) {
  this.lNovaJornada = novaJornada;
  return this;
};

DBViewJornadaServidor.prototype.isTelaManutencaoIndividual = function () {
  return this.lTelaManutencaoIndividual;
};

DBViewJornadaServidor.prototype.setTelaManutencaoIndividual = function (lTelaManutencaoIndividual) {
  this.lTelaManutencaoIndividual = lTelaManutencaoIndividual;
  return this;
};

DBViewJornadaServidor.prototype.getServidor = function () {
  return this.servidor;
};

DBViewJornadaServidor.prototype.setServidor = function (servidor) {
  this.servidor = {
    matricula : servidor.matricula,
    nome      : servidor.nome,
  };
  return this;
};

DBViewJornadaServidor.prototype.getPeriodo = function (periodo) {
  return this.periodo;
};

DBViewJornadaServidor.prototype.setPeriodo = function (periodo) {
  this.periodo.inicio = (periodo.inicio) ? periodo.inicio : null;
  this.periodo.fim    = (periodo.fim) ? periodo.fim : null;
  return this;
};

DBViewJornadaServidor.prototype.salvar = function () {

  var aMatriculasEnviar = [];

  if(this.oLancadorMatricula !== null) {
    this.oLancadorMatricula.getRegistros().each(function(matricula) {
      aMatriculasEnviar.push(matricula.sCodigo);
    });
  }

  AjaxRequest.create(
    'rec4_jornadaservidor.RPC.php',
    {
      exec       : 'salvarJornadaServidor',
      dataInicio : $F('rh212_data_inicio'),
      dataFim    : $F('rh212_data_fim'),
      jornada    : $F('rh212_jornada'),
      matricula  : $F('rh212_matricula'),
      matriculas : aMatriculasEnviar,
      selecao    : $F('r44_selec'),
      sequencial : this.getCodigoJornadaServidor(),
    },
    function (response, error) {

      if (response.mensagem) {
        alert(response.mensagem);
      }

      if (error) {
        return false;
      }

      this.fechar(true);

    }.bind(this)
  ).setMessage('Salvando jornada...').execute();
};

DBViewJornadaServidor.prototype.excluir = function (iCodigoJornadaServidor) {

  AjaxRequest.create(
    'rec4_jornadaservidor.RPC.php',
    {
      exec                   : 'excluirJornadaServidor',
      iCodigoJornadaServidor : iCodigoJornadaServidor,
    },
    function (response, error) {

      if (response.mensagem) {
        alert(response.mensagem);
      }
      
      if (error) {
        return false;
      }

      this.fechar(true);

    }.bind(this)
  ).setMessage('Excluindo jornada...').execute();
};

DBViewJornadaServidor.prototype.abrir = function () {
  this.carregarFormulario();
};

DBViewJornadaServidor.prototype.fechar = function (lIgnoraCallback) {
  
  if(lIgnoraCallback) {
    console.log('roda')
    this.fCallBack.close();
  }

  this.oWindow.destroy();
};

DBViewJornadaServidor.prototype.validarJornada = function () {

  if($F('rh212_data_inicio') == '' || $F('rh212_data_inicio') == null) {

    alert('Informe a da data de início para alterar a jornada.');
    return false;
  }
  
  if($F('rh212_jornada') == '' || $F('rh212_jornada') == null) {
    
    alert('Informe a jornada que o servidor deve estar.');
    return false;
  }

  var aMatriculasSalvar = [];

  if(this.oLancadorMatricula !== null) {

    this.oLancadorMatricula.getRegistros().each(function(matricula) {
      aMatriculasSalvar.push(matricula.sCodigo);
    });
  }

  if(  ($F('r44_selec') == '' || $F('r44_selec') == null) 
    && ($F('rh212_matricula') == '' || $F('rh212_matricula') == null)
    && (aMatriculasSalvar.length == 0))
  {
    alert('Informe aos menos um servidor.');
    return false;
  }

  return true;
};

DBViewJornadaServidor.prototype.setCallBack = function (action, fn) {
  this.fCallBack[action] = fn;
  return;
};

/**
 * Representa um campo de digitação de data do sistema
 *
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @version  $Revision: 1.22 $
 *
 */
(function(exports, Collection) {

  var getTextoExplicativo = function(texto) {

    var resposta = '<div class="messageboard">';
    resposta    += '  <div>';
    resposta    += texto.replace(/(?:\r\n|\r|\n)/g, '<br />');
    resposta    += '  </div>';
    resposta    += '</div>';
    return resposta;
  };

  var DBViewFormulario = function(elemento) {

    this.elementoPrincipal  = elemento || document.createElement("div");
    this.elementoPrincipal.className = 'main';
    this.container          = document.createElement('div');
    this.container.className= 'container_avaliacao';
    this.label              = document.createElement('label');
    this.spanProgresso      = document.createElement('div');

    this.label.setAttribute('for', 'label_avaliacao_');
    this.label.className    = 'label_avaliacao';
    this.label.innerHTML    = "Grupo de Perguntas: ";
    this.comboBox           = document.createElement('select');
    this.comboBox.id        = 'combo_avaliacao_';
    this.comboBox.className = 'combo_avaliacao';
    this.elementoObjetivas  = 'radio';

    this.sugestao = null;

    this.events = {

      'changeStep' : function(event) {
      }
    };

    this.comboBox.observe('change', function(event) {

      var codigoGrupo = event.target.value;
      this.elementoPrincipal.innerHTML = '';

      this.atualizarProgresso(event.target.selectedIndex);

      if (!codigoGrupo) {
        //this.elementoPrincipal.innerHTML = getTextoExplicativo(this.observacao);
        return;
      }

      this.mostrarGrupo(this.grupos.get(codigoGrupo));

    }.bind(this));

    this.codigo              = null;
    this.tipo                = null;
    this.id                  = null;
    this.ativo               = true;
    this.observacao          = '';
    this.grupos              = Collection.create().setId('codigo');
  };

  DBViewFormulario.makeFromObject = function(object, tipo) {

      var formulario = new DBViewFormulario();
      formulario.set('codigo', object.codigo);
    formulario.set("tipo", object.tipo);
    formulario.set("id", object.id);
    formulario.set("ativo", object.ativo);
    formulario.set("observacao", object.observacao);

      if (tipo != null) {
      formulario.setElementoRespostasObjetivas(tipo);
      }
    formulario.comboBox.id += object.codigo;
    formulario.label.setAttribute('for', 'combo_avaliacao_'+object.codigo);


    for( var grupoJSON of object.grupos) {
      var grupo = DBViewGrupoPerguntas.makeFromObject(grupoJSON, formulario);
      grupo.setFormulario(formulario);
      formulario.addGrupo(grupo);
    }

    return formulario;
  };

  DBViewFormulario.prototype = {

      'setEvent' : function(evento, callback) {
      this.events[evento] = callback;
      return this;
    },

    'addGrupo' : function(grupo) {
      this.grupos.add(grupo);
      return this;
    },

    'set' : function(chave, valor) {
      this[chave] = valor;
      return this;
    },
    'setSugestao' : function (sugestao) {
        this.sugestao = sugestao;
        return this;
    },

    'getDados' : function(codigoGrupo) {

      var aGrupos = [];

      for (var oGrupo of this.grupos.get()) {

        aGrupos.push(oGrupo.getDados());

        if(codigoGrupo == oGrupo.codigo) {
          return {
            codigo  : this.codigo,
            grupos  : oGrupo.getDados()
          };
        }
      }


      return {
        codigo  : this.codigo,
        grupos  : aGrupos
      };
    },

      'show': function(destino, bHideCombo) {
          if (bHideCombo === void 0) { bHideCombo = false; }

          if (!bHideCombo) {
              this.container.appendChild(this.label);
              this.container.appendChild(this.comboBox);
              this.container.appendChild(this.spanProgresso);
          }

          this.container.appendChild(this.elementoPrincipal);
          this.atualizarCombo();
          destino.appendChild(this.container);

          if (!bHideCombo) {
              this.comboBox.observe('change',
                  this.events.changeStep.bind(this));
              this.comboBox.dispatchEvent(
                  new Event('change')
              );
          }

          if (this.sugestao) {
              this.grupos.itens.each(grupo => {
                  grupo.perguntas.itens.each(pergunta => {
                      if (this.sugestao.hasOwnProperty(
                          pergunta.identificador_campo)) {

                          if (this.sugestao[pergunta.identificador_campo] &&
                              this.sugestao[pergunta.identificador_campo].option) {
                              if (this.sugestao[pergunta.identificador_campo] != undefined &&
                                  this.sugestao[pergunta.identificador_campo] != null) {
                                  var identificadorOpcao = this.sugestao[pergunta.identificador_campo].option;

                                  if (identificadorOpcao) {
                                      try {
                                          pergunta.elemento.down('input[identificador_campo="' + identificadorOpcao + '"]').checked = true;
                                      }
                                      catch (err) {

                                      }
                                  }
                              }
                          }
                          else {
                              if (this.sugestao[pergunta.identificador_campo] !== undefined &&
                                  this.sugestao[pergunta.identificador_campo] != null) {
                                  try {
                                      pergunta.elemento.down('input').value = this.sugestao[pergunta.identificador_campo];
                                      pergunta.elemento.down('input').dispatchEvent(new Event('focus'));
                                  }
                                  catch (err) {

                                  }
                              }
                          }
                      }
                  });
              });
          }

          return this;
      },

    'atualizarCombo' : function() {

        this.comboBox.length = 0;

      // this.comboBox.add(
      //   this.criarOpcao('Selecione...', '')
      // );

      for (var grupo of this.grupos.get()) {

        var option = document.createElement;
        this.comboBox.add(
          this.criarOpcao(grupo.getLabel(), grupo.getCodigo())
        );
      }

      this.comboBox.selectedIndex = this.comboBox.selectedIndex;
      this.comboBox.dispatchEvent(new Event('change'));
    },

    'criarOpcao' : function(label, valor) {

      var elemento = document.createElement('option');
      elemento.value = valor;
      elemento.text  = label;
      return elemento;
    },

    'mostrarGrupo' : function(grupo) {

        this.elementoPrincipal.innerHTML = '';
      grupo.show(this.elementoPrincipal);
    },
    'mostrarTodos': function() {

        for (var oGrupo of this.grupos.get()) {
         oGrupo.show(this.elementoPrincipal);
      }
    },

    'avancarGrupo' : function() {
      this.comboBox.selectedIndex = this.comboBox.selectedIndex + 1;
      this.comboBox.dispatchEvent(new Event('change'));
    },

    'recurarGrupo' : function() {
      this.comboBox.selectedIndex = this.comboBox.selectedIndex - 1;
      this.comboBox.dispatchEvent(new Event('change'));
    },

    'atualizarProgresso' : function(posicao) {

      this.spanProgresso.innerHTML = '';

      if (posicao >= 0) {
        this.spanProgresso.innerHTML = posicao + 1 + ' de ' + this.grupos.get().length;
      }
    },

      'setElementoRespostasObjetivas' : function(tipo) {
          this.elementoObjetivas = tipo;
    },
      'getElementoRespostasObjetivas': function() {
      return this.elementoObjetivas;
    },
    'getStatus' : function() {

        var grupos            = this.grupos.get();
      var quantidadeGrupos  = grupos.length;
      var indiceSelecionado = this.comboBox.selectedIndex;
      var status  = {
        grupoPosterior: undefined,
        grupoAtual    : undefined,
        grupoAnterior : undefined
      };

      if ( quantidadeGrupos === 0 ) {
        return status;
      }

      if (quantidadeGrupos === indiceSelecionado && quantidadeGrupos > 0) {

        status.grupoAtual    = grupos[indiceSelecionado];
        status.grupoAnterior = grupos[indiceSelecionado - 1];
        return status;
      }

      status.grupoPosterior= grupos[indiceSelecionado + 1];
      status.grupoAtual    = grupos[indiceSelecionado];
      status.grupoAnterior = grupos[indiceSelecionado - 1];

      return status;
    },
      'isValido': function() {
          var valido = true;

          for (var grupo of this.grupos.get()) {
              if (!grupo.isValido()) {
                  valido = false;
              }
          }

          return valido;
      },
  };

  exports.DBViewFormulario = DBViewFormulario;
  return DBViewFormulario;

})(this, Collection);

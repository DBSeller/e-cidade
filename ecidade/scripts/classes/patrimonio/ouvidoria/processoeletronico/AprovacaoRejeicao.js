require_once('scripts/widgets/windowAux.widget.js');

/**
 * @param acao
 *   1 - Aprovacao
 *   2 - Rejeicao
 * @constructor
 */
AprovacaoRejeicao = function(acao, processo, ano, tipoProcesso, grauRisco) {
  this.windowAprovacaoRejeicao = null;
  this.rpc = 'ouv4_solicitacaoprocessoeletronico.RPC.php';
  this.acao = acao;
  this.executa = acao === 1 ? 'aprovar' : 'rejeitar';
  this.larguraWindow = parent.document.body.getWidth() / 6;
  this.alturaWindow = parent.document.body.clientHeight / (parent.document.body.clientHeight > 600 ? 3.8 : 2.8);
  this.tipoProcesso = tipoProcesso;
  this.processo = processo;
  this.ano = ano;
  this.grauRisco = grauRisco;

  if(acao === 2) {
    this.larguraWindow = parent.document.body.getWidth() / 4;
    this.alturaWindow = parent.document.body.clientHeight / 2.8;
    this.linhasTextArea = this.larguraWindow > 400 ? 10 : 4;
    this.colunasTextArea = this.alturaWindow > 200 ? 58 : 38;
  }
};

AprovacaoRejeicao.prototype.criarFieldset = function() {
  let _this = this;
  let elementoFieldset = new Element('fieldset');
  let elementoLegend = new Element('legend');

  switch(_this.acao) {
    case 1:
      elementoLegend.innerHTML = 'Grau de Risco';
      elementoFieldset.appendChild(elementoLegend);
      elementoFieldset.appendChild(_this.containerInputs());

      break;

    case 2:
      elementoLegend.innerHTML = 'Motivo da Rejeição';
      elementoFieldset.appendChild(elementoLegend);
      elementoFieldset.appendChild(_this.containerText());

      break;

    default:
      throw 'Tipo inexistente.';
  }


  return elementoFieldset;
};

AprovacaoRejeicao.prototype.criarInputRadio = function(valor) {
  let elementDivInput = new Element('div');
  let elementInputRadio = new Element('input');
  let elementLabelInput = new Element('label');
  let id = '';

  switch(valor) {
    case 'A':
      id = 'riscoAlto';
      elementLabelInput.innerHTML = 'Alto';

      break;

    case 'M':
      id = 'riscoMedio';
      elementLabelInput.innerHTML = 'Médio';

      break;

    case 'B':
      id = 'riscoBaixo';
      elementLabelInput.innerHTML = 'Baixo';

      break;
  }

  elementLabelInput.setAttribute('for', id);

  elementInputRadio.setAttribute('type', 'radio');
  elementInputRadio.setAttribute('id', id);
  elementInputRadio.setAttribute('name', 'grauRisco');
  elementInputRadio.setAttribute('value', valor);


  elementDivInput.appendChild(elementInputRadio);
  elementDivInput.appendChild(elementLabelInput);

  return elementDivInput;
};

AprovacaoRejeicao.prototype.containerInputs = function() {
  let _this = this;
  let elementoInputs = new Element('div', {'id': 'ctnInputs'});

  elementoInputs.appendChild(_this.criarInputRadio('A'));
  elementoInputs.appendChild(_this.criarInputRadio('M'));
  elementoInputs.appendChild(_this.criarInputRadio('B'));

  return elementoInputs;
};

AprovacaoRejeicao.prototype.atualizaRadio = function(container) {
  let _this = this;
  inputCheck = container.querySelector(`[value=${_this.grauRisco}]`);

  if(!!inputCheck){
    inputCheck.checked = 'true';
  }
};

AprovacaoRejeicao.prototype.criarBotaoSalvar = function() {
  let elementoDivSalvar = new Element('div');
  let elementoBotaoSalvar = new Element('input');

  elementoBotaoSalvar.setAttribute('id', 'salvarAprovacaoRejeicao');
  elementoBotaoSalvar.setAttribute('type', 'button');
  elementoBotaoSalvar.setAttribute('value', 'Salvar');

  elementoDivSalvar.addClassName('container');
  elementoDivSalvar.appendChild(elementoBotaoSalvar);

  return elementoDivSalvar;
};

AprovacaoRejeicao.prototype.containerText = function() {
  let elementoDivText = new Element('div');
  let elementoText = new Element('textarea');

  elementoText.setAttribute('id', 'motivoRejeicao');
  elementoText.setAttribute('rows', this.linhasTextArea);
  elementoText.setAttribute('cols', this.colunasTextArea);

  elementoDivText.appendChild(elementoText);

  return elementoDivText;
};

AprovacaoRejeicao.prototype.conteudo = function() {
  let _this = this;
  let divPrincipal = new Element('div');

  divPrincipal.setAttribute('id', 'divPrincipal');

  divPrincipal.appendChild(_this.criarFieldset());
  divPrincipal.appendChild(_this.criarBotaoSalvar());

  return divPrincipal.outerHTML;
};

AprovacaoRejeicao.prototype.montaJanela = function() {
  let _this = this;
  let titulo = _this.acao === 1 ? 'Aprovação de Solicitação' : 'Rejeição de Solicitação';
  let conteudo = _this.conteudo();

  _this.windowAprovacaoRejeicao = new windowAux('windowAprovacaoRejeicao', titulo, _this.larguraWindow, _this.alturaWindow);
  _this.windowAprovacaoRejeicao.setContent(conteudo);
  _this.windowAprovacaoRejeicao.show(null, null, true);
  _this.atualizaRadio(_this.windowAprovacaoRejeicao.getContentContainer());
  _this.windowAprovacaoRejeicao.setShutDownFunction(function() {
    _this.windowAprovacaoRejeicao.destroy();
  });
};

AprovacaoRejeicao.prototype.salvarAprovacaoRejeicao = function() {
  let _this = this;
  let formData = new FormData();

  formData.append('ano', _this.ano);
  formData.append('tipoProcesso', _this.tipoProcesso);
  formData.append('processo', _this.processo);

  switch(_this.acao) {
    case 1:
      let grauSelecionado = parent.$$("input[type='radio']:checked")[0];

      if(grauSelecionado === undefined) {

        alert('Selecione o grau de risco.');
        return false;
      }

      formData.append('exec', 'aprovar');
      formData.append('grauRisco', grauSelecionado.value);

      break;

    case 2:
      let motivoRejeicao = parent.$$("textarea")[0];

      formData.append('exec', 'rejeitar');
      formData.append('motivo', motivoRejeicao.value);

      break;
  }


  HttpClient.post(_this.rpc, {body: formData}).then(response => {
    alert(response.mensagem);

    parent.atualizaGrid();
    parent.fechaIframe();

    _this.windowAprovacaoRejeicao.destroy();
  });
};

AprovacaoRejeicao.prototype.show = function() {
  let _this = this;

  _this.montaJanela();

  let elementoSalvar = parent.document.getElementById('salvarAprovacaoRejeicao');
  elementoSalvar.addEventListener('click', _this.salvarAprovacaoRejeicao.bind(_this));
};

require_once('scripts/AjaxRequest.js')
require_once('scripts/prototype.js')

ViewNumeroControleInterno = function(instancia, habilitarAncora) {

  this.instancia = instancia
  this.rpc = 'lab4_numerocontroleinterno.RPC.php'
  this.parametroAtivo = false
  this.regexSomenteNumero = new RegExp(/[^0-9]/, 'g')
  this.requisicaoElementoDestino = null
  this.requisicao = null
  this.ancoraNumeroHabilitada = false

  this.divPrincipal = document.createElement('div')
  this.divPrincipal.setStyle({'display': 'none'})

  this.ancoraNumero = document.createElement('a')
  this.ancoraNumero.setStyle({'pointer-events': 'none'})

  if(habilitarAncora !== undefined && habilitarAncora === true) {

    this.ancoraNumero.setAttribute('href', '#')
    this.ancoraNumero.setStyle({'pointer-events': ''})

    this.ancoraNumeroHabilitada = true
  }

  this.labelNumero = document.createElement('label')
  this.labelNumero.addClassName('bold')
  this.labelNumero.setAttribute('id', 'labelNumero')
  this.labelNumero.setAttribute('for', 'inputNumero')
  this.labelNumero.innerHTML = 'Número Controle Interno: '

  this.inputNumero = document.createElement('input')
  this.inputNumero.addClassName('field-size2')
  this.inputNumero.setAttribute('id', 'inputNumero')
  this.inputNumero.setAttribute('name', 'la65_numero')
  this.inputNumero.setAttribute('type', 'text')
  this.inputNumero.setAttribute('value', '')

  this.spanBarra = document.createElement('span')
  this.spanBarra.addClassName('bold');
  this.spanBarra.innerHTML = ' / '

  this.inputAno = document.createElement('input')
  this.inputAno.addClassName('field-size1')
  this.inputAno.setAttribute('id', 'inputAno')
  this.inputAno.setAttribute('name', 'la65_ano')
  this.inputAno.setAttribute('type', 'text')
  this.inputAno.setAttribute('value', '')
  this.inputAno.setAttribute('maxlength', '4')

  this.inputRequisicao = document.createElement('input')
  this.inputRequisicao.setAttribute('id', 'inputRequisicao')
  this.inputRequisicao.setAttribute('type', 'hidden')
  this.inputRequisicao.setAttribute('value', '')

  this.ancoraNumero.appendChild(this.labelNumero)
  this.divPrincipal.appendChild(this.ancoraNumero)
  this.divPrincipal.appendChild(this.inputNumero)
  this.divPrincipal.appendChild(this.spanBarra)
  this.divPrincipal.appendChild(this.inputAno)
}

ViewNumeroControleInterno.prototype.acoes = function() {

  this.acoesNumero()
  this.acoesAno()
}

ViewNumeroControleInterno.prototype.acoesAno = function() {

  var self = this

  this.inputAno.addEventListener('change', function() {

    if(!self.valorValido(self.inputAno.value)) {

      self.inputAno.value = ''
      return false
    }

    if(!empty(self.inputNumero.value)) {
      self.pesquisaRequisicao(false)
    }
  })

  this.inputAno.addEventListener('keyup', function() {

    if(!self.valorValido(self.inputAno.value)) {
      self.inputAno.value = ''
    }
  })

  return true
}

ViewNumeroControleInterno.prototype.acoesNumero = function() {

  var self = this

  this.inputNumero.addEventListener('change', function() {

    if(!self.valorValido(self.inputNumero.value)) {

      self.inputNumero.value = ''
      return false
    }

    self.pesquisaRequisicao(false)
  })

  this.inputNumero.addEventListener('keyup', function() {

    if(!self.valorValido(self.inputNumero.value)) {
      self.inputNumero.value = ''
    }
  })

  this.ancoraNumero.addEventListener('click', function() {
    self.pesquisaRequisicao(true)
  })

  return true
}

ViewNumeroControleInterno.prototype.getAno = function() {
  return this.inputAno.value
}

ViewNumeroControleInterno.prototype.getNumeroControleInterno = function() {
  return this.inputNumero.value
}

ViewNumeroControleInterno.prototype.getParametroAtivo = function() {
  return this.parametroAtivo
}

ViewNumeroControleInterno.prototype.pesquisaRequisicao = function(mostra) {

  if(this.ancoraNumeroHabilitada === false) {
    return false
  }

  var url = `func_numerocontroleinterno.php?abreLookup=true&funcao_js=parent.${this.instancia}.retornoPesquisaRequisicao`
  url += '|true|la65_numero|la65_ano|la65_requisicao'

  if(mostra === false) {

    if(empty(this.inputNumero.value) || empty(this.inputAno.value)) {
      return false
    }

    url = `func_numerocontroleinterno.php?funcao_js=parent.${this.instancia}.retornoPesquisaRequisicao`
    url += `&numeroControleInterno=${this.inputNumero.value}&ano=${this.inputAno.value}`
  }

  js_OpenJanelaIframe(
    '',
    'frameNumeroControleInterno',
    url,
    'Pesquisa Requisição',
    mostra
  )
}

ViewNumeroControleInterno.prototype.retornoPesquisaRequisicao = function() {

  frameNumeroControleInterno.hide()

  if(typeof arguments[0] === 'boolean' && arguments[0] === true) {

    alert('Número de controle interno não encontrado.')

    $('inputNumero').value = ''
    this.requisicao = null
    $('inputNumero').focus()

    return false
  }

  $('inputNumero').value = arguments[1]
  $('inputAno').value = arguments[2]
  this.requisicao = arguments[3]

  if(this.requisicaoElementoDestino !== null && this.requisicao !== '') {

    this.requisicaoElementoDestino.value = arguments[3]
    this.requisicaoElementoDestino.onchange()
  }
}

ViewNumeroControleInterno.prototype.setAno = function(ano) {

  if(!empty(ano) && !this.valorValido(ano)) {

    this.inputAno.setAttribute('value', '')
    return false
  }

  this.inputAno.setAttribute('value', ano)
}

ViewNumeroControleInterno.prototype.setNumero = function(numero) {

  if(!empty(numero) && !this.valorValido(numero)) {

    this.inputNumero.setAttribute('value', '')
    return false
  }

  this.inputNumero.setAttribute('value', numero)
}

ViewNumeroControleInterno.prototype.setRequisicaoElemento = function(elementoDestino) {
  this.requisicaoElementoDestino = elementoDestino
}

ViewNumeroControleInterno.prototype.show = function(elemento) {

  elemento.appendChild(this.divPrincipal)
  this.verificarParametro();
  
  if(this.parametroAtivo === false) {
    return false
  }

  this.divPrincipal.setStyle({'display': ''})
  this.acoes()
}

ViewNumeroControleInterno.prototype.valorValido = function(valor) {

  if(this.regexSomenteNumero.test(valor) === true) {

    alert('Valor inválido. Somente números são permitidos.')
    return false
  }

  return true
}

ViewNumeroControleInterno.prototype.verificarParametro = function() {

  var self = this

  new AjaxRequest(
    this.rpc,
    {
      'executa': 'verificarParametro'
    },
    function(retorno, erro) {

      if(erro === true) {

        alert(retorno.mensagem)
        return false
      }

      self.parametroAtivo = retorno.parametroAtivo
      self.inputAno.value = retorno.ano
    }
  ).asynchronous(false).execute()
}

ViewNumeroControleInterno.prototype.setApenasLeitura = function(valor) {
  var backgroundColor;
  if (valor) {
    this.inputNumero.setAttribute('readonly', valor)
    this.inputAno.setAttribute('readonly', valor)
    backgroundColor = '#DEB887';
  } else {
    this.inputNumero.removeAttribute('readonly')
    this.inputAno.removeAttribute('readonly')
    backgroundColor = '';
  }

  this.inputNumero.setAttribute('style', `background-color:${backgroundColor}`)
  this.inputAno.setAttribute('style', `background-color:${backgroundColor}`)
}

ViewNumeroControleInterno.prototype.getNumeroControleInternoPorRequisicao = function(requisicao) {
  if (requisicao == null || requisicao == '') {
    return false;
  }

  var self = this

  new AjaxRequest(
    this.rpc,
    {
      'executa': 'buscarInformacoesNumeroControleInterno',
      'requisicao': requisicao
    },
    function(retorno, erro) {

      if(erro === true) {

        alert(retorno.mensagem)
        return false
      }
      
      self.inputAno.value = retorno.ano
      self.inputNumero.value = retorno.numeroControleInterno
      self.requisicao = retorno.requisicao
    }
  ).asynchronous(false).execute()
}

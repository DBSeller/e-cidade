

$.noConflict();
var oAbas = new DBAbas($('abas'));
var oAbaAssentamentos = oAbas.adicionarAba('Assentamentos', $('aba_assentamentos'));
var oAbaIntervalos = oAbas.adicionarAba('Intervalos de valores', $('aba_intervalos'));
var oAbaConfiguracao = oAbas.adicionarAba('Assentamentos envolvidos', $('aba_configuracao'));
var oAbaValidacao = oAbas.adicionarAba('Validação das concessões', $('aba_validacao'));
oAbaIntervalos.bloquear();
oAbaConfiguracao.bloquear();
oAbaValidacao.bloquear();
var rh500_sequencial = '';
var rh501_sequencial;
var rh502_sequencial;
var rh503_sequencial;


const routers = {
    'configuracao': url + '/v4/api/recursos-humanos/rh/concessaodireitos/configuracao',
    'deleteconfiguracao': url + '/v4/api/recursos-humanos/rh/concessaodireitos/deleteconfiguracao',
    'gravarconfiguracao': url + '/v4/api/recursos-humanos/rh/concessaodireitos/gravarconfiguracao',
    'assentperc': url + '/v4/api/recursos-humanos/rh/concessaodireitos/assentperc',
    'gravarassentperc': url + '/v4/api/recursos-humanos/rh/concessaodireitos/gravarassentperc',
    'deleteassentperc': url + '/v4/api/recursos-humanos/rh/concessaodireitos/deleteassentperc',
    'assentform': url + '/v4/api/recursos-humanos/rh/concessaodireitos/assentform',
    'gravarassentform': url + '/v4/api/recursos-humanos/rh/concessaodireitos/gravarassentform',
    'deleteassentform': url + '/v4/api/recursos-humanos/rh/concessaodireitos/deleteassentform',
    'assentconcedeconfig': url + '/v4/api/recursos-humanos/rh/concessaodireitos/assentconcedeconfig',
    'gravarassentconcedeconfig': url + '/v4/api/recursos-humanos/rh/concessaodireitos/gravarassentconcedeconfig',
    'deleteassentconcedeconfig': url + '/v4/api/recursos-humanos/rh/concessaodireitos/deleteassentconcedeconfig'
};
const dadosPesquisa = {
    descricaoassentamento: document.getElementById('descricaoassentamento'),
    assentamento: document.getElementById('assentamento'),
    descricaoassentamentoAba4: document.getElementById('descricaoassentamentoAba4'),
    assentamentoAba4: document.getElementById('assentamentoAba4'),
    descricaoassentamento2: document.getElementById('descricaoassentamento2'),
    assentamento2: document.getElementById('assentamento2'),
    descricaoassentamento3: document.getElementById('descricaoassentamento3'),
    assentamento3: document.getElementById('assentamento3'),
    descricaoconcessao: document.getElementById('descricaoconcessao'),
    concessao: document.getElementById('concessao'),
    descricaonaoconcessao: document.getElementById('descricaonaoconcessao'),
    naoconcessao: document.getElementById('naoconcessao'),
    datalimite: document.getElementById('datalimite'),

    selecao: document.getElementById('selecao'),
    descricaoselecao: document.getElementById('descricaoselecao'),

    allIdAssentamentos: document.getElementById('allIdAssentamentos'),
    allassentamaentos: document.getElementById('allassentamaentos'),
}


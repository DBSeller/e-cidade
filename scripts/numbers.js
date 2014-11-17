/**
 * Funções para formatação e controle de numeros
 * @package numbers.js
 */

/**
 * Função para validar numero inteiro ou float, negativo ou positivo
 * @deprecated
 * @param  iValor valor a ser verificado float ou integer
 * @author Tales Baz
 * @return boolean
 */
function js_validaSomenteNumeros(iValor) {
  return isNumeric(iValor);
}

/**
 * Função para validar numero inteiro ou float, negativo ou positivo
 * @param  iValor valor a ser verificado float ou integer
 * @author Tales Baz
 * @return boolean
 */
function isNumeric( iValor, lApenasPositivos ) {

  lApenasPositivos = lApenasPositivos || false;

  var sRegex  = /^-?(?:\d+|\d*\.\d+)$/;

  if ( lApenasPositivos ) {
    sRegex =/^(?:\d+|\d*\.\d+)$/;
  }
  return sRegex.test(iValor);
}


/**
 * Aplica uma mascara de valor
 * Essa função deve ser utilizada com onKeyPress
 *
 * @param  {event}            e
 * @param  {HTMLInputElement} oObject
 * @return {boolean}
 */
function mascaraValor(e, oObject) {

  if (!js_mask(e, '0-9|.|-')) {
    oObject.value = '';
    return false;
  }

  setTimeout(function() {

    mValor = oObject.value;
    mValor = mValor.replace(/\D/g,"");
    mValor = mValor.replace(/(\d)(\d{2})$/,"$1.$2");
    oObject.value = mValor;
    return true;
  }, 1);


}

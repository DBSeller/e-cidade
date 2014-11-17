var CONTEXT = this;

var DBMask = function(options) {

  this.settings = {
    zIndex: 0,
    miniMask: false,
    context: CONTEXT,
    fade: true
  }

  /**
   * jQuery.extend só que não
   */
  for (var prop in options) {
    if (this.settings.hasOwnProperty(prop)) {
      this.settings[prop] = options[prop]
    }
  }

  this.__init();

}

DBMask.prototype.__init = function() {

  var oMask = this.oMaskElement = this.settings.context.document.createElement('DIV');

  var zIndex = this.settings.zIndex || ( 10000 + (this.settings.context.document.getElementsByClassName('db-mask').length * 2) );

  oMask.setAttribute('class', 'db-mask');
  oMask.style.width           = '100%';
  oMask.style.height          = '100%';
  oMask.style.position        = 'fixed';
  oMask.style.background      = 'url("imagens/ecidade/db-mask-bg.png")';
  oMask.style.left            = '0';
  oMask.style.top             = '0';
  oMask.style.zIndex          = zIndex;
  oMask.style.overflow        = 'auto';

  if (this.settings.fade) {
    oMask.style.opacity       = '0';
    oMask.style.MozTransition = 'opacity 0.25s';
  }

  var topoMask    = oMask.cloneNode(true),
      corpoMask   = oMask,
      bstatusMask = oMask.cloneNode(true);

  this.oMasks = [topoMask, corpoMask, bstatusMask];

  if (this.settings.miniMask) {
    this.settings.context.document.body.appendChild(corpoMask);
    return;
  }

  top.corpo.document.body.appendChild(corpoMask);
  top.topo.document.body.appendChild(topoMask);
  top.bstatus.document.body.appendChild(bstatusMask);

  if (this.settings.fade) {

    setTimeout(function() {
      corpoMask.style.opacity   = 1;
      topoMask.style.opacity    = 1;
      bstatusMask.style.opacity = 1;
    }, 50)
  }
}

/**
 * Método responsável por remover por completo os dados da Dialog.
 */
DBMask.prototype.destroy = function() {

  for (var indexMasks = 0; indexMasks < this.oMasks.length; indexMasks++ ) {
    this.oMasks[indexMasks].outerHTML = '';
    this.oMasks[indexMasks] = null;
  }

  this.oMasks = []
}

DBMask.prototype.getZIndex = function() {
  return this.oMasks[1].style.zIndex
}

DBMask.prototype.getMaskElement = function() {
  return this.oMaskElement;
}
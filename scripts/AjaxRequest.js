/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

/**
 * var oAjaxRequest = new AjaxRequest('con1_departamentos.RPC.php', {exec: 'listarDepartamentos', codusuario : 1}, callBackRetorno);
 * oAjaxRequest.setMessage('Buscando departamentos...');
 * oAjaxRequest.execute();
 *
 * @param {string} sPathFile
 * @param {object} oParameters
 * @param {function} fnCallback
 * @constructor
 */
AjaxRequest = function(sPathFile, oParameters, fnCallback) {

  /**
   * Código da requisição
   * @type {number}
   */
  this.id = 0;

  /**
   * Caminho do arquivo que receberá a requisição
   * @type {string}
   */
  this.sPathFile = sPathFile;

  /**
   * Função de callback que será executada após a conclusão da requisição
   * - Ela será devolvida com dois parâmetros fnCallback(oObject, lErro)
   * @type {Function}
   */
  this.fnCallback = fnCallback;

  /**
   * Parâmetros da requisição
   * @type {object}
   */
  this.oParameters = oParameters;

  /**
   * Asynchronous
   * @type {boolean}
   */
  this.lAsynchronous = false;

  /**
   * Mensagem padrão
   * @type {string}
   */
  this.sMessage = "Aguarde...";

  /**
   * @param {boolean} lAsynchronous
   */
  this.asynchronous = function (lAsynchronous) {
    this.lAsynchronous = lAsynchronous;
    return this;
  };

  /**
   * @param sMessage
   */
  this.setMessage = function(sMessage) {
    this.sMessage = sMessage;
    return this;
  };
};

/**
 * Executa uma requisição ajax
 */
AjaxRequest.prototype.execute = function() {

  js_divCarregando(this.sMessage, 'msgBox' + (++this.id));
  new Ajax.Request(this.sPathFile,
                    {
                      method:'post',
                      async: this.lAsynchronous,
                      parameters:'json='+Object.toJSON(this.oParameters),
                      onComplete: function(oAjax) {

                        js_removeObj('msgBox'+this.id);
                        var oReturn = JSON.parse(oAjax.responseText);
                        if (oReturn.erro == undefined) {

                          oReturn.erro = true;
                          console.log("Variável para controle de erro não localizada. Crie uma variavel no RPC chamada 'erro'.");
                        }
                        this.fnCallback(oReturn, oReturn.erro);
                      }.bind(this)
                    }
                  );
};
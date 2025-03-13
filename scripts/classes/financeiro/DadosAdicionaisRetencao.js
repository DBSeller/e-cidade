/**
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
var DadosAdicionaisRetencao = (function () {

	function DadosAdicionaisRetencao () {
		var _this = this;
		this.rpc = "";
		this.dados = {
			valorNaoRetidoPrincipal: null,
			valorServico15: null,
			valorServico20: null,
			valorServico25: null,
			valorAdicional: null,
			valorNaoRetidoAdicional: null
		};
		this.form = Object;
		this.windowAux = Object;
		this.html =
			'	<form action="" method="post" id="dadosAdicionaisRetencao" enctype="multipart/form-data">\n' +
			'        <fieldset>\n' +
            '           <legend>Dados adicionais sobre o tipo de serviço da NF</legend>\n' +
			'	        <table class="form-container">\n' +
			'	 	        <tr>\n' +
			'	               <td>\n' +
            '                     <label for="">Valor da retenção principal não retido:</label>\n' +
			'                  </td>\n' +
			'	               <td>\n' +
			'					   <input type="text" name="valornaoretidoprincipal" id="valornaoretidoprincipal">' +
			'                  </td>\n' +
            '               </tr>\n' +
            '	 	        <tr>\n' +
            '                  <td colspan="2"> \n' +
            '                      <fieldset style="margin-top: 5px;">\n' +
            '                         <legend>Valor dos serviços prestados em condições especiais</legend>\n' +
            '                         <table class="form-container">\n' +
            '                             <tr>\n' +
			'	               <td>\n' +
            '                                    <label for="">Após 15 anos de contribuição:</label>\n' +
			'                  </td>\n' +
			'	               <td>\n' +
			'					   <input type="text" name="valorservico15" id="valorservico15">' +
			'                  </td>\n' +
            '               </tr>\n' +
            '	 	        <tr>\n' +
			'	               <td>\n' +
            '                                    <label for="">Após 20 anos de contribuição:</label>\n' +
			'                  </td>\n' +
			'	               <td>\n' +
			'					   <input type="text" name="valorservico20" id="valorservico20">' +
			'                  </td>\n' +
            '               </tr>\n' +
            '	 	        <tr>\n' +
			'	               <td>\n' +
            '                                    <label for="">Após 25 anos de contribuição:</label>\n' +
			'                  </td>\n' +
			'	               <td>\n' +
			'					   <input type="text" name="valorservico25" id="valorservico25">' +
			'                  </td>\n' +
            '               </tr>\n' +
            '                             <tr style="display: none;">\n' +
			'	               <td>\n' +
            '                                    <label for="">Adicional apurado de retenção da NF:</label>\n' +
			'                  </td>\n' +
			'	               <td>\n' +
            '                                     <input type="text" name="valoradicional" id="valoradicional" disabled="true">' +
            '                                </td>\n' +
            '                             </tr>\n' +
            '                         </table>\n' +
            '                      </fieldset>\n' +                                               
			'                  </td>\n' +
            '               </tr>\n' +
            '	 	        <tr>\n' +
			'	               <td>\n' +
            '                    <label for="">Valor da retenção adicional não retido:</label>\n' +
			'                  </td>\n' +
			'	               <td>\n' +
			'					   <input type="text" name="valornaoretidoadicional" id="valornaoretidoadicional">' +
			'                  </td>\n' +
            '               </tr>\n' +
            '           </table>\n' +
			'        </fieldset>\n' +                                               
            '        <div style="text-align: center; margin-top: 5px;">\n' +
            '           <input type="button" id="apenasRecebe" value="Apenas Receber" disabled style="display: none;">\n' +
            '           <input type="button" id="salvarDadosAdicionais" value="Salvar">\n' +
            '           <input type="button" id="cancelarDadosAdicionais" value="Cancelar">\n' +
            '        </div>\n' +
            '    </form>';

        this.setWindowAux = function (windowAux) {
            _this.windowAux = windowAux;
			_this.windowAux.setContent(_this.html);
			_this.form = _this.windowAux.divContent.querySelector("#dadosAdicionaisRetencao");
			_this.adicionarAcoes();
        };
		
		this.exibe = function () {
			_this.windowAux.show();
        };

        this.adicionarAcoes = function(){
        	const
        		btnSalvar = document.getElementById('salvarDadosAdicionais'),
        		btnCancelar = document.getElementById('cancelarDadosAdicionais');

        	btnSalvar.addEventListener('click', () => {
        		_this.salvadados();        		
        	});

        	btnCancelar.addEventListener('click', () => {
        		_this.cancelar();
			});
			Object.keys(_this.dados).forEach((dado) => {
				new DBInputValor(_this.form.querySelector(`#${dado.toLowerCase()}`));
			});
        }

		this.salvadados = function () {
			Object.keys(_this.dados).forEach((dado) => {
				_this.dados[dado] = _this.form.querySelector(`#${dado.toLowerCase()}`).value || 0;
			});
			_this.windowAux.hide();
		};
		
		this.cancelar = function () {
			_this.remapear();
			_this.windowAux.hide();
		}

		this.pegaDados = function () {
			return _this.dados;
		}

		this.remapear = function (resetar = false) {
			Object.keys(_this.dados).forEach((dado) => {
				const valorOriginal = _this.dados[dado] || null;
				_this.form.querySelector(`#${dado.toLowerCase()}`).value = resetar ? null : valorOriginal;
			});
		}

        this.preencherDados = function (dados = {}, forcar = false) {
			Object.keys(_this.dados).forEach((dado) => {
                if (dados[dado] || forcar) {
					_this.dados[dado] = dados[dado];
				}
			});
			_this.remapear(false);
		}
	}

	return DadosAdicionaisRetencao;

}());

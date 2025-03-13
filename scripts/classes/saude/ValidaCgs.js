
require('scripts/session.js');
require('scripts/classes/http/http.js');
/**
 * classe para validações do cgs
 * @param {Object} inputCgs objeto com os dois inputs do cgs id e nome;
 */
class ValidaCgs {
	constructor(inputCgs) {
		this.rotas = {
			parametros: 'saude/ambulatorial/parametros',
			cgs: 'saude/ambulatorial/cgs'
		};
		this._inputCgs = {};

		if (inputCgs != undefined) {
			this._inputCgs = {
				id: inputCgs.id.cloneNode(true),
				nome: inputCgs.nome.cloneNode(true)
			};
		}
	}
	
	_buscaCgs = async id => {
		let dados = {};
		await HttpClient.get(`${PHPSession.requestApi}/${this.rotas.cgs}/${id}`).then(response => {
			if (response.error) {
				alert(response.message);
				return false;
			}
			dados = response.data;
		});
		return dados;
	}

	getParametros = async () => {
		if (!PHPSession.requestApi) {
			await PHPSession.loadData();
		}
		let dados = {};
		await HttpClient.get(`${PHPSession.requestApi}/${this.rotas.parametros}`).then(response => {
			if (response.error) {
				alert(response.message);
				return false;
			}
			dados = response.data;
		});
		return dados;
	}

	isCadastradoMicroarea = async id => {
		if (id == '') {
			return true;
		}

		let cgs = await this._buscaCgs(id);
		if (cgs === null) {
			return true;
		}
		
		let idFamiliaMicroarea = cgs.cgs_unidade.z01_i_familiamicroarea;
		if (idFamiliaMicroarea) {
			return true;
		}
		return false;
	}

	isInativo = async id => {
		if (id == '') {
			return false;
		}

		let cgs = await this._buscaCgs(id);
		if (cgs === null) {
			return false;
		}
		
		let cgsExt = cgs.cgs_unidade.cgs_extensao;
		if (cgsExt === null) {
			return false;
		}

		if (cgsExt.z01_b_inativo) {
			return true;
		}
		return false;
	}

	/**
	 * função para validar cadastro cgs na microarea
	 * @param {Object} inputCgs objeto com os dois inputs do cgs id e nome;
	 * @param {Object} divAlert div alert com a mensagem a ser informada;
	 */
	cadastroMicroarea = (inputCgs, divAlert) => {
		const pintaCampoCgs = async () => {
			if(!await this.isCadastradoMicroarea(inputCgs.id.value)) {
				inputCgs.id.style.backgroundColor = '';
				inputCgs.id.classList.remove('readonly');
				inputCgs.id.addClassName('alert-danger');
	
				inputCgs.nome.style.backgroundColor = '';
				inputCgs.nome.classList.remove('readonly');
				inputCgs.nome.addClassName('alert-danger');
				divAlert.hidden = '';;
			} else {
				inputCgs.id.style.backgroundColor = this._inputCgs.id.style.backgroundColor;
				inputCgs.id.classList = this._inputCgs.id.classList;
	
				inputCgs.nome.style.backgroundColor = this._inputCgs.nome.style.backgroundColor;
				inputCgs.nome.classList = this._inputCgs.nome.classList;
	
				divAlert.hidden = 'hidden';
			}
		}

		this.getParametros().then(parametros => {
			if (parametros.s103_validamicroarea) {
				pintaCampoCgs();
				inputCgs.id.addEventListener('change', pintaCampoCgs);
			}
		});
	}
}
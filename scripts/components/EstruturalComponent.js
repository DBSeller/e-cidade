/**
 * Esse componente tem como dependencia a(s) classe(s):
 *  - scripts/classes/financeiro/EstruturalPcasp.js
 *  Antes de requerir o componente, você deve requerir a classe
 */
class EstruturalComponent extends HTMLElement {

    _estruturalPcasp;

    /**
     * mapa dos inputs criados
     * @type {[]}
     * @private
     */
    _mapaInputs = [];

    constructor() {
        super();

        let estrutural = this.hasAttribute('estrutural') ? this.getAttribute('estrutural') : undefined;
        this._estruturalPcasp = new EstruturalPcasp(estrutural);

        const shadow = this.attachShadow({mode: 'open'});

        let configuracao = {};

        if (this.hasAttribute('readOnly')) {
            configuracao.readOnly = true;
        }
        if (this.hasAttribute('readOnlyToLevel')) {
            configuracao.readOnlyToLevel = this.getAttribute('readOnlyToLevel');
        }

        this.appendInputs(shadow, configuracao);
    };


    /**
     * Cria o input individualmente conforme parâmetros de cada nível
     * @param mapa
     * @returns {HTMLInputElement}
     * @private
     */
    _criaInput = (mapa) => {
        let input = document.createElement('input');
        input.setAttribute('maxlength', mapa.maxlength);

        input.dataset.length = mapa.maxlength
        input.dataset.nivel = mapa.nivel;
        input.dataset.nome = mapa.nome;
        input.style.width = mapa.width;
        input.style.fontSize = '9pt';
        input.style.paddingLeft = '1px';
        input.style.textAlign = 'center';
        input.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        input.addEventListener('change', (e) => {
            e.target.value = e.target.value.padStart(input.dataset.length, '0');
        });
        return input;
    }

    /**
     * Cria os inputs e mapeia eles no array _mapaInputs
     * @private
     */
    _criarInputs = () => {
        for (let mapa of this.getEstrutural()._mapa) {
            this._mapaInputs.push(this._criaInput(mapa))
        }
    };

    /**
     * Adiciona readonly no input
     * @param input
     * @private
     */
    _setReadonly = (input) => {
        input.setAttribute('readonly', 'readonly');
        input.style.backgroundColor = 'rgb(222, 184, 135)';
    };

    _removeAllReadonly = () => {
        this._mapaInputs.forEach((input) => {
            input.removeAttribute('readonly');
            input.style.backgroundColor = '#FFF';
        });
    };

    /**
     * Sempre que altera o valor do input, atualiza na classe
     * @param index
     * @param evento
     * @private
     */
    _atualizaEstrututal = (index, evento) => {
        this._estruturalPcasp._estrutural[index] = evento.target.value;
    };
    /**
     * Adiciona os inputs no container informado.
     * @param container
     * @param configuracao
     */
    appendInputs = (container, configuracao) => {
        this._containerPai = container;
        this._containerPai.innerHTML = '';

        this._criarInputs();
        this._mapaInputs.forEach((input, index) => {

            let valor = this._estruturalPcasp._estrutural[index];

            input.value = valor;
            if (configuracao?.readOnly) {
                this._setReadonly(input);
            }

            let nivel = index + 1;
            if (configuracao?.readOnlyToLevel >= nivel) {
                this._setReadonly(input);
            }

            input.addEventListener('change', this._atualizaEstrututal.bind(this, index));

            this._containerPai.append(input)
        })
    }

    bloquearAteNivel = (nivelBloquear) => {
        this._mapaInputs.forEach((input, index) => {
            let nivel = index + 1;
            if (nivelBloquear >= nivel) {
                this._setReadonly(input);
            }
        });
    };

    getEstrutural = () => {
        return this._estruturalPcasp;
    };


    setValue = (estrutural) => {
        this._estruturalPcasp.setEstrutual(estrutural);
        this._mapaInputs.forEach((input, index) => {
            input.value = this._estruturalPcasp._estrutural[index];
        });
    };

    reset = () => {
        this.setValue(this._estruturalPcasp._mascara);
        this._removeAllReadonly();
    };
}

window.customElements.define('db-estrutural', EstruturalComponent);

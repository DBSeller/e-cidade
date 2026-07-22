class Valores {

    _criaContainerInputs = (configuracao) => {
        let container = document.createElement('div');

        if (configuracao?.container?.tableCell) {
            container.style.display = 'table-cell';
        }

        return container;
    };
    _criaLabel = (id, ano, configuracao) => {
        let label = document.createElement('label');
        label.setAttribute('for', id);
        label.innerHTML = `&nbsp${ano}: &nbsp`;
        label.className = 'bold'
        return label;
    };

    _criaInput = (id, ano, configuracao) => {
        let input = document.createElement('input');
        input.setAttribute('maxlength', 15);
        input.dataset.ano = ano;
        input.className = 'field-size3 valores-planejamento';
        input.id = id;

        if (configuracao?.input?.readOnly) {
            input.setAttribute('readonly', 'readonly');
            input.className += ' readonly';
        }

        return input
    };

    criaInputValores = (containerPai, plano, configuracao) => {
        this._containerPai = containerPai;

        this._containerPai.innerHTML = '';
        for (let ano = plano.pl2_ano_inicial; ano <= plano.pl2_ano_final; ano++) {
            let id = `${this._containerPai.id}_valor_${ano}`;
            let container = this._criaContainerInputs(configuracao);

            let label = this._criaLabel(id, ano, configuracao);
            let input = this._criaInput(id, ano, configuracao)

            new DBInputValor(input);
            container.append(label);
            container.append(input);

            this._containerPai.append(container);
        }
    };

    /**
     * Se os inputs são de valores monetarios
     * @returns {[]}
     */
    getValores = () => {
        if (this._containerPai === undefined) {
            return [];
        }
        const inputs = this._containerPai.querySelectorAll('input.valores-planejamento');
        const valores = [];

        for (let input of inputs) {
            let obj = this._getObjectValor(input);
            valores.push(obj);
        }

        return valores;
    }

    _getObjectValor = (input) => {
        return {
            "ano": Number(input.dataset.ano),
            "valor": input.value.getNumber()
        }
    };

    _getInput = (ano) => {
        return this._containerPai.querySelector(`input.valores-planejamento[data-ano="${ano}"]`);
    }

    set = (ano, valor) => {
        const input = this._getInput(ano);
        input.value = valor;
    };

    /**
     * Retorna o valor informado para o informado
     * @param {integer} ano
     */
    getValor = (ano) => {
        const input = this._getInput(ano);
        return Number(input.value);
    };

    /**
     * reseta/limpa os valores dos imputs
     */
    reset = () => {
        this.getValores().map(valor => {
            this.set(valor.ano, '');
        })
    }

    /**
     * Valida se existe valores não informados
     * @returns {boolean}
     */
    existeValoresNaoInformados = () => {
        let valoresNaoInformados = this.getValores().filter(valor => {
            if (valor.valor < 0) {
                return valor;
            }
        });

        return valoresNaoInformados.length > 0;
    };

    /**
     * Valida se os inputs são de valores percentuais, não podendo passar de 100%
     * @returns {boolean}
     */
    validaPercentuais = () => {
        let valores = this.getValores();
        let invalidos = valores.filter(valor => {
            return valor.valor > 100;
        });

        if (invalidos.length > 0) {
            let anos = invalidos.map(valor => {
                return valor.ano;
            })
            let msg = `O valor do ano ${anos.join(', ')} esta inválido.`;
            if (anos.length > 1) {
                msg = `Os valores dos anos ${anos.join(', ')} estão inválidos.`;
            }

            alert(`Você não pode digitar um valor maior que 100%. \n ${msg}`);
            return false;
        }

        return true;
    };
}

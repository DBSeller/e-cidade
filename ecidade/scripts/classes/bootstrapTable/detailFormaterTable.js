/**
 * Linhas formatadas deve ser um array de array, e cada objeto dentro do array é uma coluna que será criada.
 * As colunas devem ser um objeto com a seguinte estrutura: {label: "", valor: ""}
 *
 * @exemple
 * let linhasFormatada = [
 *      [ {label: "", valor: ""}] // para uma linha com 1 coluna
 *      [ {label: "", valor: ""}, {label: "", valor: ""}] // para uma linha com 2 colunas
 * ]
 *
 *
 * @param linhasFormatada
 * @param mensagemFieldSet
 * @returns {*}
 */

class DetailFormaterTable {
    createDetail = (linhasFormatada, mensagemFieldSet) => {
        const container = document.createElement('fieldset');
        container.classList.add('text-left');
        const legenda = document.createElement('legend');
        legenda.innerHTML = mensagemFieldSet
        container.append(legenda);
        for (let colunas of linhasFormatada) {
            container.append(this._criarLinha(colunas));
        }

        return container.outerHTML;
    }
    _createColumnLabel = (dado) => {
        const column = document.createElement('div');
        column.style.display = 'table-cell'

        const label = document.createElement('label');
        label.classList.add('bold');
        label.innerHTML = `&nbsp;${dado.label}&nbsp;`;

        column.append(label)
        return column
    };

    _createColumnValor = (dado) => {
        const column = document.createElement('div');
        column.style.display = 'table-cell';

        const label = document.createElement('label');
        label.innerHTML = `&nbsp;${dado.valor}&nbsp;`;
        column.append(label)
        return column
    };

    _criarLinha = (colunas) => {
        const linha = document.createElement('div');
        linha.style.display = 'table-row';

        for (let coluna of colunas) {
            linha.append(this._createColumnLabel(coluna));
            linha.append(this._createColumnValor(coluna));
        }

        return linha;
    }
}

var detailFormaterTable = new DetailFormaterTable();


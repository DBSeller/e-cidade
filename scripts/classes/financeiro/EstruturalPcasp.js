class EstruturalPcasp {

    _mapa = [
        {nivel: 1, nome: "classe", "maxlength": "1", width: '17px', pInicial: 0, pFinal: 1},
        {nivel: 2, nome: "grupo", "maxlength": "1", width: '17px', pInicial: 1, pFinal: 2},
        {nivel: 3, nome: "subgrupo", "maxlength": "1", width: '17px', pInicial: 2, pFinal: 3},
        {nivel: 4, nome: "titulo", "maxlength": "1", width: '17px', pInicial: 3, pFinal: 4},
        {nivel: 5, nome: "subtitulo", "maxlength": "1", width: '17px', pInicial: 4, pFinal: 5},
        {nivel: 6, nome: "item", "maxlength": "2", width: '23px', pInicial: 5, pFinal: 7},
        {nivel: 7, nome: "subitem", "maxlength": "2", width: '23px', pInicial: 7, pFinal: 9},
        {nivel: 8, nome: "desdobramento1", "maxlength": "2", width: '23px', pInicial: 9, pFinal: 11},
        {nivel: 9, nome: "desdobramento2", "maxlength": "2", width: '23px', pInicial: 11, pFinal: 13},
        {nivel: 10, nome: "desdobramento3", "maxlength": "2", width: '23px', pInicial: 13, pFinal: 15},
    ];

    _mascara = '0.0.0.0.0.00.00.00.00.00';

    /**
     * Estrutural
     * @type {[]}
     * @private
     */
    _estrutural = [];

    constructor(estrutural) {
        this.setEstrutual(estrutural)
    };

    setEstrutual = (estrutural) => {
        this._estrutural = [];
        if (estrutural === undefined) {
            this._estrutural = this._mascara.split('.');
        }

        if (estrutural !== undefined && estrutural.includes('.')) {
            this._estrutural = estrutural.split('.');
        } else if (estrutural !== undefined && !estrutural.includes('.')) {
            for (let mapa of this._mapa) {
                this._estrutural.push(estrutural.substring(mapa.pInicial, mapa.pFinal))
            }
        }
    };

    /**
     * Retorna o estrutural com máscara
     * @returns {string}
     */
    estruturalComMascara = () => {
        return this._estrutural.join('.');
    };

    /**
     * Retorna o estrutural com máscara
     * @returns {string}
     */
    estruturalSemMascara = () => {
        return this._estrutural.join('');
    };

    /**
     * Retorna parte do estrutural até seu nível
     * @returns {*}
     */
    estruturalAteNivel = () => {
        let nivel = this.getNivel();

        let estrutural = '';
        for (let i = 0; i < nivel; i++) {
            estrutural += this._estrutural[i];
        }
        return estrutural;
    };

    /**
     * retorna o nível do estrutural do PCASP
     * @returns {number}
     */
    getNivel = () => {
        let nivel = 10;
        let e = [...this._estrutural]; // cria uma cópia do array
        for (let valor of e.reverse()) {
            if (valor === '0'.repeat(valor.length)) {
                nivel--;
            }
            if (valor !== '0'.repeat(valor.length)) {
                return nivel;
            }
        }
    };
}

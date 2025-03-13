/**
 * Classe para montar o progressbar
 * Fontes exemplo: cad4_emissaogeraliptuarquivo.php
 * @param sDivDestino
 * @param sNomeProgressBar
 * @constructor
 */
var DBProgressBar = function (sDivDestino, sNomeProgressBar) {

    /**
     * Total de Linhas
     * @type integer
     */
    let iTotalLinhas = 0;

    /**
     * Linha Atual
     * @type integer
     */
    let iLinha = 0;

    /**
     * Percentual processado
     * @type integer
     */
    let iPercentual = 0;

    /**
     * Texto da mensagem inicial
     * @type string
     */
    let sTextoAcao = "Aguarde, processando..."

    /**
     * Texta da mensagem final
     * @type string
     */
    let sTextoFinal = "Concluído"

    /**
     * Monta a estrutura inicial do progressbar
     * @param sCorLabel
     * @param sCorProgressBar
     * @param sEspessura
     */
    this.montaProgressBar = (sCorLabel= "white", sCorProgressBar = "blue", sEspessura = "30px") => {
        const oDivDestino = document.getElementById(sDivDestino);

        const oDivTexto = document.createElement("div");
        oDivTexto.setAttribute("id", `divTexto_${sNomeProgressBar}`);
        oDivTexto.setAttribute("style", `width: 100%; text-align: center; line-height: 30px; color: ${sCorLabel};`);
        oDivTexto.innerHTML = sTextoAcao;

        const oSpanLabel = document.createElement("span");
        oSpanLabel.setAttribute("id", `spanPercentual_${sNomeProgressBar}`);
        oSpanLabel.setAttribute("style", `width: 100%; text-align: center; line-height: 30px; color: ${sCorLabel};`);
        oSpanLabel.innerHTML = "0 %";

        const oDivProgressBar = document.createElement("div");
        oDivProgressBar.setAttribute("id", `divProgressBar_${sNomeProgressBar}`);
        oDivProgressBar.setAttribute("style", `width: 0%; height: ${sEspessura}; background-color: ${sCorProgressBar};`);

        oDivDestino.appendChild(oDivTexto);
        oDivDestino.appendChild(oSpanLabel);
        oDivDestino.appendChild(oDivProgressBar);
    };

    /**
     * Seta o total de linhas
     * @param iValor
     * @returns DBProgressBar
     */
    this.setTotal = (iValor) => {
        iTotalLinhas = iValor;

        return this;
    };

    /**
     * Seta a linha atual
     * @param iLinhaAtual
     * @returns DBProgressBar
     */
    this.setLinha = (iLinhaAtual) => {
        iLinha = iLinhaAtual;

        return this;
    };

    /**
     * Seta o texto final
     * @param sTexto
     * @returns DBProgressBar
     */
    this.setTextoFinal = (sTexto) => {
        sTextoFinal = sTexto;

        return this;
    };

    /**
     * Seta o texto final
     * @param sTexto
     * @returns DBProgressBar
     */
    this.setTextoFinal = (sTexto) => {
        sTextoAcao = sTexto;

        return this;
    };

    /**
     * Calcula o percentual e atualiza a label do percentual e o progressbar
     */
    this.calculaPercentual = () => {
        iPercentual = parseInt(((parseInt(iLinha) * 100) / parseInt(iTotalLinhas)));

        if (iPercentual >= 100) {
            const oDivTexto = document.getElementById(`divTexto_${sNomeProgressBar}`);
            oDivTexto.innerHTML = sTextoFinal;
        }

        const oSpanLabel = document.getElementById(`spanPercentual_${sNomeProgressBar}`);
        const oDivProgressBar = document.getElementById(`divProgressBar_${sNomeProgressBar}`);

        oDivProgressBar.style.width = `${iPercentual}%`;
        oSpanLabel.innerHTML = `${iPercentual} %`;
    };
}

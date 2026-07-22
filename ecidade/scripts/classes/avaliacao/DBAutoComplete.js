/**
 * @param elemento
 * @param caminhoArquivoJson
 * @param permiteValorVazio
 * @constructor
 * @tutorial https://leaverou.github.io/awesomplete/
 * @dependence
 * <script type="text/javascript" src="scripts/awesomplete.js"></script>
 * <link href='estilos/awesomplete.css' rel='stylesheet' type='text/css'>
 * @example
 * new DBAutoComplete(document.querySelector("#codTeste"), "arquivos//tabela.json");
 */
const DBAutoComplete = function (elemento, caminhoArquivoJson, permiteValorVazio) {

    this.elemento = elemento;
    this.elementoAuxiliar = elemento.clone();
    this.caminhoArquivoJson = caminhoArquivoJson;
    this.permiteValorVazio = permiteValorVazio || false;

    this.verificaRequisitos = function () {
        if (typeof Awesomplete == "undefined") {
            throw "É necessário importar o componente Awesomplete.";
        }

        return this.elemento.type !== "radio";
    };

    this.prepararElementos= function() {
        if ($(this.elemento.id + '_descricao')) {
            this.elementoAuxiliar = $(this.elemento.id + '_descricao');
            return;
        }
        this.elementoAuxiliar.id = this.elemento.id + '_descricao';
        this.elementoAuxiliar.name = this.elemento.name + '_descricao';
        this.elementoAuxiliar.identificador = this.elemento.identificador + "_descricao";
        this.elementoAuxiliar.setAttribute("identificador", this.elemento.identificador + "_descricao");
        this.elementoAuxiliar.type = "text";
        this.elemento.parentNode.insertBefore(this.elementoAuxiliar, this.elemento.nextSibling);
        this.elemento.setAttribute('type', 'hidden');
        this.iniciarAutoLoad(caminhoArquivoJson);
    };

    this.iniciarAutoLoad = function () {
        var _self = this;

        fetch(_self.caminhoArquivoJson, {credentials: 'include'}).then(response => response.json()).then(dados => {
            if (_self.elemento.value != '') {
                for (var dado of dados) {
                    if (dado.value == _self.elemento.value) {
                        _self.elementoAuxiliar.value = dado.label;
                    }
                }
            }
            new Awesomplete(this.elementoAuxiliar, {
                list : dados,
                autoFirst: true
            });

            this.elementoAuxiliar.addEventListener("awesomplete-selectcomplete", function(selected) {
                _self.elementoAuxiliar.value = selected.text.label;
                _self.elemento.value = selected.text.value;
            });

            if (this.permiteValorVazio) {
                this.elementoAuxiliar.addEventListener("keyup", function(event) {
                    if (event.target.value === "") {
                        _self.elemento.value = "";
                    }
                });
            }
        });
    };

    if(this.verificaRequisitos()) {
        this.prepararElementos();
    }
};

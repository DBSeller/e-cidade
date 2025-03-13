/**
 * DBViewRespostaNula
 * Plugin para o componente DBViewResposta
 * @overview Adiciona uma resposta nula para uma pergunta não obrigatória, tanto em select quando em radio button
 * @author John Lenon R. <john.reis@dbseller.com.br>
 */
(function (exports) {

    const DBViewRespostaNula = function () {

    };

    DBViewRespostaNula.prototype = {
        'adicionaEmSelect': function (divMain, avaliacao) {
            const elementosRespostaObjetivas = divMain.querySelectorAll("select");

            Array.from(elementosRespostaObjetivas).forEach(function (elementoRespostaObjetiva) {

                if (!elementoRespostaObjetiva.querySelector("option[value='null']")) {

                    const perguntasFormularioGrupoAtual = avaliacao.getStatus().grupoAtual.getPerguntas();
                    const idPergunta = elementoRespostaObjetiva.id.replace("select_", "");

                    Array.from(perguntasFormularioGrupoAtual).forEach(function (perguntaFormulario) {
                        if (perguntaFormulario.codigo === idPergunta && !perguntaFormulario.obrigatoria) {

                            const opcaoNula = document.createElement("option");
                            opcaoNula.value = "null";
                            opcaoNula.innerHTML = "<span>Nenhuma das opções</span>";
                            opcaoNula.setAttribute("codigo", "null");

                            elementoRespostaObjetiva.prepend(opcaoNula, null);
                        }
                    });
                }
            });
        },
        'adicionaEmRadioButton': function (divMain, avaliacao) {
            const fieldsetsPerguntas = divMain.querySelectorAll("fieldset[elemento-objetiva=radio]");
            const perguntasFormularioGrupoAtual = avaliacao.getStatus().grupoAtual.getPerguntas();

            Array.from(fieldsetsPerguntas).forEach(function (fieldsetPergunta) {

                if (!fieldsetPergunta.querySelector("input[type=radio][value=null]")) {
                    var existePerguntaRespondida = false;
                    const codigoPergunta = fieldsetPergunta.getAttribute("codigo");
                    var inputName = "";
                    var perguntaObrigatoria = false;

                    const inputsRadio = fieldsetPergunta.querySelectorAll("input[type=radio]");

                    Array.from(inputsRadio).forEach(function (radioButton) {
                        if (radioButton.checked) {
                            existePerguntaRespondida = true;
                        }
                    });

                    Array.from(perguntasFormularioGrupoAtual).forEach(function (perguntaFormulario) {
                        if (perguntaFormulario.codigo === codigoPergunta) {
                            inputName = perguntaFormulario.id;
                            perguntaObrigatoria = perguntaFormulario.obrigatoria;
                        }
                    });

                    if (!perguntaObrigatoria) {
                        const opcaoNula = document.createElement("div");
                        opcaoNula.classList.add("wrapper_resposta");

                        const input = document.createElement("input");
                        input.value = "null";
                        input.type = "radio";
                        input.id = "opcao_nula_" + codigoPergunta;
                        input.name = inputName;

                        const label = document.createElement("label");
                        label.innerHTML = "<span>Nenhuma das opções anteriores</span>";
                        label.setAttribute("for", input.id);

                        opcaoNula.appendChild(input);
                        opcaoNula.appendChild(label);

                        fieldsetPergunta.insertBefore(opcaoNula, null);

                        if (!existePerguntaRespondida) {
                            input.checked = true;
                        }
                    }
                }
            });

        }
    };

    DBViewRespostaNula.adicionaRespostaNula = function (avaliacao) {

        if (avaliacao) {
            const tagRespostasObjetivas = avaliacao.getElementoRespostasObjetivas();
            const viewRespostaNula = new DBViewRespostaNula();
            const divMain = document.querySelector(".main");

            switch (tagRespostasObjetivas) {
                case "select":
                    viewRespostaNula.adicionaEmSelect(divMain, avaliacao);
                    break;
                case "radio":
                    viewRespostaNula.adicionaEmRadioButton(divMain, avaliacao);
                    break;
            }
        }
    };

    exports.DBViewRespostaNula = DBViewRespostaNula;
    return DBViewRespostaNula;

})(this);

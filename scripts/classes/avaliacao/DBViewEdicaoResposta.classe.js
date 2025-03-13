
/**
 * Representa um campo de digitação de uma resposta de uma pergunta de um grupo de perguntas de uma avaliacao
 *
 * @author Renan Silva <renan.silva@dbseller.com.br>
 * @version  $Revision: 1.1 $
 *
 */
(function(exports) {

    var DBViewEdicaoResposta = function(data, colunas, campo, resposta) {

        var labelFormulario = empty(data) ? 'Novo Registro' : 'Edição';
        var sHTML  = "   <form name='mainFormAttributes' id='mainFormAttributes' action=''>";
        sHTML += "      <table width='100%'>                     ";
        sHTML += "        <tr>                                   ";
        sHTML += "          <td >         ";
        sHTML += "            <fieldset>                         ";
        sHTML += "              <legend>                         ";
        sHTML += "                <b>"+labelFormulario+"</b>   ";
        sHTML += "              </legend>                        ";
        sHTML += "              <table id='formAttributes'>";
        for (col of colunas) {
            var valor = '';
            if (!empty(data[col.campo])) {
                valor = data[col.campo];
            }
            sHTML += " <tr>" +
                "<td>" +
                "<label for='"+col.campo+"'><b>"+col.label+":</b></label>"+
                "</td>" +
                "<td>"+
                "<input id='"+col.campo+"' value='"+valor+"'>";
            "</td>"+
            "</tr>";
        }
        sHTML += ""
        sHTML += "              </table>";
        sHTML += "            </fieldset>";
        sHTML += "          </td> ";
        sHTML += "        </tr>";
        sHTML += "      </table>";

        sHTML += "    <table>";
        sHTML += "      <tr>";
        sHTML += "        <td align=center>";
        sHTML += "          <input type=button value='Salvar'   name='salvar'   id='salvarForm'>"
        sHTML += "          <input type=button value='Cancelar' name='cancelar' id='cancelarForm'>"
        sHTML += "        </td> ";
        sHTML += "      </tr>";
        sHTML += "    </table>";

        sHTML += "    </form>";

        windowCampos = new windowAux("windowCampos","Edição de Registros", 550, 350);
        windowCampos.setContent(sHTML);


        windowCampos.show();

        windowCampos.setShutDownFunction(function(){
            windowCampos.destroy();
        });

        $('cancelarForm').onclick = function () {
            windowCampos.destroy()
        };

        var jsonCampo = JSON.parse(campo.value);
        $('salvarForm').onclick = function () {

            var registroAlterado = {};
            for (col of colunas) {
                registroAlterado[col.campo] = $F(col.campo);
            }

            if (empty(data)) {


                jsonCampo.push(registroAlterado);
                var novoValorCampo = JSON.stringify(jsonCampo);
                $(campo.getElement().id).value = novoValorCampo;
                resposta.valor = novoValorCampo;
                resposta.renderTableData();
                windowCampos.destroy();
                return;
            }

            for ( var i = 0; i < jsonCampo.length;i++) {

                if (JSON.stringify(jsonCampo[i]) == JSON.stringify(data)) {


                    jsonCampo[i] = registroAlterado;
                    var novoValorCampo = JSON.stringify(jsonCampo);
                    $(campo.getElement().id).value = novoValorCampo;
                    resposta.valor = novoValorCampo;
                    resposta.renderTableData();
                    windowCampos.destroy();

                    return;
                }
            }


        }.bind(this);
    }
});
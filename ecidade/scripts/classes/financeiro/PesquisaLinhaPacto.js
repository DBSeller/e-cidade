/**
 * @param linha
 * @param acao
 * @param descricaoLinha
 * @constructor
 */
PesquisaLinhaPacto = function (linha, acao, descricaoLinha) {

    this.linha = linha;
    this.acao = acao;
    this.descricaoLinha = descricaoLinha;

    var conteudoJanela = "<div>";

    conteudoJanela    += "<fieldset class='separator'>";
    conteudoJanela    += "  <legend class='bold'>Dados da Ação</legend>";
    conteudoJanela    += "  <table style='width: 100%'  >";
    conteudoJanela    += "    <tr> ";
    conteudoJanela    += "      <th >Ação</th>";
    conteudoJanela    += "      <th >Previsto</th>";
    conteudoJanela    += "      <th >Alterado / Remanejado</th>";
    conteudoJanela    += "      <th >Realizado</th>";
    conteudoJanela    += "      <th >Saldo</th>";
    conteudoJanela    += "    </tr>";
    conteudoJanela    += "    <tr>";
    conteudoJanela    += "      <td style='text-align: left' id='valorAcao'></td>";
    conteudoJanela    += "      <td style='text-align: right' id='valorPrevisto'></td>";
    conteudoJanela    += "      <td style='text-align: right' id='valorAlterado'></td>";
    conteudoJanela    += "      <td style='text-align: right' id='valorRealizado'></td>";
    conteudoJanela    += "      <td style='text-align: right' id='valorSaldo'></td>";
    conteudoJanela    += "    </tr>";
    conteudoJanela    += "  </table>";
    conteudoJanela    += "</fieldset>";
    conteudoJanela    += "<fieldset class='separator'>";
    conteudoJanela    += "  <legend class='bold'>Secretarias</legend>";
    conteudoJanela    += "  <div id='ctnGridSecretarias'></div>";
    conteudoJanela    += "</fieldset>";
    conteudoJanela    += "</div>";


    var gridSecretaria = new DBGrid('gridSecretaria');
    gridSecretaria.nameInstance = 'gridSecretaria';
    gridSecretaria.setHeader(['Secretaria','Previsto', 'Alterado / Remanejado', 'Realizado', 'Saldo']);
    gridSecretaria.setCellAlign(['left', 'right', 'right', 'right', 'right']);
    gridSecretaria.setCellWidth(['40%', '15%', '15%', '15%', '15%']);
    gridSecretaria.setHeight(500);

    var janelaPesquisa = new windowAux('wndDadosLinhaPacto', ' Dados da linha de Pacto', 1024, 768);
    janelaPesquisa.setContent(conteudoJanela);
    janelaPesquisa.setShutDownFunction(
        function () {
            janelaPesquisa.destroy();
        }
    );

    var oMessageBoard = new DBMessageBoard('msgboard',
        'Detalhamento da Linha de Pacto: '+this.descricaoLinha,
        '',
        janelaPesquisa.getContentContainer());

    oMessageBoard.show();
    janelaPesquisa.show();
    gridSecretaria.show($('ctnGridSecretarias'));

    var parametro = {
        exec :'getDetalhamentoLinhaPacto',
        'acao' : acao,
        'linha' : linha
    };
    AjaxRequest.create(
        'orc3_planoorcamentarioRPC.php',
        parametro,
        function (response, erro) {

            $('valorAcao').innerHTML = response.dados_acao.descricao;
            $('valorPrevisto').innerHTML = js_formatar(response.dados_acao.valor_previsto, 'f');
            $('valorAlterado').innerHTML = js_formatar(response.dados_acao.valor_alterado_remanejado, 'f');
            $('valorRealizado').innerHTML = js_formatar(response.dados_acao.valor_realizado, 'f');
            $('valorSaldo').innerHTML = js_formatar(response.dados_acao.saldo_final, 'f');

            gridSecretaria.clearAll(true);

            response.secretarias.each(
                function (secretaria) {

                    gridSecretaria.addRow([
                        secretaria.descricao,
                        js_formatar(secretaria.valor_previsto, 'f'),
                        js_formatar(secretaria.valor_alterado_remanejado, 'f'),
                        js_formatar(secretaria.valor_realizado, 'f'),
                        js_formatar(secretaria.saldo_final, 'f')
                    ]);
                }
            );

            gridSecretaria.renderRows();
        }
    ).execute();

}

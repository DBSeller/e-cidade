require_once('scripts/AjaxRequest.js');
require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');

/**
 * @param nomeInstancia
 * @param codigoMovimento
 * @constructor
 */
DBViewComplementoPagamento = function (nomeInstancia, codigoMovimento) {

    this.nomeInstancia = nomeInstancia;
    this.codigoMovimento = codigoMovimento;
    const RPC = 'cai4_complementoPagamento.RPC.php';
    var self = this;

    this.oWindowAux = null;

    this.abrir = function () {

        var container = "  <fieldset style='width: 95%'>";
        container += "      <legend class='bold'>Alteração de Histórico de Lançamento</legend>";
        container += "      <fieldset class='separator' style='width:98%'>";
        container += "          <legend class='bold'>Histórico de Pagamento "+this.codigoMovimento+"</legend>";
        container += "          <textarea style='width: 98%; height: 30px' id='historicoPagamento" + this.codigoMovimento + "'>";
        container += "          </textarea>";
        container += "      </fieldset>";
        container += "  </fieldset>";
        container += "  <p style='text-align:center;'><input type='button' value='Aplicar' id='btnAplicarHistorico' /></p>";

        this.oWindowAux = new windowAux("window" + this.codigoMovimento, "Histórico de Pagamento", 700, 350);
        this.oWindowAux.setContent(container);

        var sHelpMsgBoard = "Digite abaixo o histórico de pagamento.";
        var oMessageBoard = new DBMessageBoard('msgBoard' + this.codigoMovimento,
            "Defina abaixo o texto que será salvo no histórico de pagamento.",
            sHelpMsgBoard,
            this.oWindowAux.getContentContainer()
        );
        this.oWindowAux.setShutDownFunction(function () {
            self.oWindowAux.destroy();
        });
        this.oWindowAux.show();
        oMessageBoard.show();

        this.pesquisaMovimento();
        $('btnAplicarHistorico').observe('click', function () {

            var parametros = {
                'exec': 'aplicarHistorico',
                'codigo_movimento': self.codigoMovimento,
                'historico': $('historicoPagamento'+self.codigoMovimento).value
            };

            AjaxRequest.create(
                RPC,
                parametros,
                function (response, erro) {
                    alert(response.message);
                    if (!erro) {
                        self.oWindowAux.destroy();
                    }
                }
            ).execute();
        });
    };


    this.pesquisaMovimento = function () {

        var self = this;
        AjaxRequest.create(
            RPC,
            {'exec': 'getDadosMovimento', 'codigo_movimento': this.codigoMovimento},
            function (response, erro) {
                if (erro) {
                    alert(response.message);
                    return false;
                }

                $('historicoPagamento' + self.codigoMovimento).value = response.nota_liquidacao.historico;
            }
        ).execute();
    };



};

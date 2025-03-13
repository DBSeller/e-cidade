const WebsocketTEF = function (sIP = null) {
    if (!sIP) {
        alert("O IP do terminal não foi informado!");
        return;
    }

    let sHost = `wss://${sIP}:4433`;
    let simulaPinpad = false;

    this.dev = () => {
        sHost = `ws://${sIP}:2500`;
    };

    this.envio = (iNumeroTransacao, iOperacao, sValor, iParcelas = null) => {
        return new Promise(resolve => {
            if (simulaPinpad) {
                resolve(simulaPinpad);
                return;
            }

            const oWs = new WebSocket(sHost);

            oWs.onopen = () => {
                const oDados = {
                    numeroTransacao: iNumeroTransacao,
                    operacao: iOperacao,
                    valorTransacao: sValor.toString().replace(/\D+/g, "")
                };

                if (iParcelas) {
                    oDados.numeroParcelas = iParcelas;
                }

                oWs.send(JSON.stringify(oDados));
            }

            oWs.onmessage = (oEvent) => {
                oWs.close();
                const oResponse = JSON.parse(oEvent.data);
                const oRetorno = {};
                oRetorno.response = oResponse;
                oRetorno.erro = false;

                if (oResponse.retorno == 0) {
                    oRetorno.status = "Aprovado";
                    oRetorno.codigoAprovacao = oResponse.codigoAprovacao;
                    oRetorno.nsuCTF = oResponse.nsuCTF;
                    oRetorno.bandeira = oResponse.bandeira;
                    oRetorno.cartao = oResponse.cartao;
                    oRetorno.redeAdquirente = oResponse.redeAdquirente;
                    oRetorno.nsuAutorizadora = oResponse.nsuAutorizadora;
                } else {
                    oRetorno.erro = true;
                    oRetorno.status = "Negado";
                    oRetorno.mensagemErro = this.getError(oResponse);
                }

                resolve(oRetorno);
            }

            oWs.onerror = function (oEvent) {
                oWs.close();
                const oRetorno = {};
                oRetorno.erro = true;
                oRetorno.status = "Negado";
                oRetorno.mensagemErro = "Não foi possível estabelecer uma conexão com o CTFClient";
                resolve(oRetorno);
            }
        });
    };

    this.confirmacao = (iOrdem) => {
        return new Promise(resolve => {
            if (simulaPinpad) {
                resolve(simulaPinpad);
                return;
            }

            const oWs = new WebSocket(sHost);

            oWs.onopen = () => {
                const oDados = {
                    numeroTransacao: iOrdem,
                    operacao: 6
                };

                oWs.send(JSON.stringify(oDados));
            }

            oWs.onmessage = (oEvent) => {
                oWs.close();
                const oResponse = JSON.parse(oEvent.data);
                const oRetorno = {};
                oRetorno.erro = false;

                if (oResponse.retorno == 0 || oResponse.retorno == 13) {
                    oRetorno.mensagem = "";
                } else {
                    oRetorno.erro = true;
                    oRetorno.mensagem = `Não foi possível confirmar a transação ${iOrdem}. Erro: ${this.getError(oResponse)}`;
                }

                resolve(oRetorno);
            }
        });
    };

    this.desfazimento = (iOrdem) => {
        return new Promise(resolve => {
            if (simulaPinpad) {
                resolve(simulaPinpad);
                return;
            }

            const oWs = new WebSocket(sHost);

            oWs.onopen = () => {
                const oDados = {
                    numeroTransacao: iOrdem,
                    operacao: 191
                };

                oWs.send(JSON.stringify(oDados));
            }

            oWs.onmessage = (oEvent) => {
                oWs.close();
                const oResponse = JSON.parse(oEvent.data);
                const oRetorno = {};
                oRetorno.erro = false;

                if (oResponse.retorno == 0 || oResponse.retorno == 13) {
                    oRetorno.mensagem = "";
                } else {
                    oRetorno.erro = true;
                    oRetorno.mensagem = `Não foi possível desfazer a transação ${iOrdem}. Erro: ${this.getError(oResponse)}`;
                }

                resolve(oRetorno);
            }
        });
    };

    this.getError = (oResponse) => {
        switch (oResponse.retorno) {
            case 1:
                return "Tempo de processamento excedido";
            case 13:
                return "Há mais transações para confirmar";
            case 20:
            case 5:
            case 11:
                return this.getSubError(oResponse);
            default:
                return `Retorno: ${oResponse.retorno} / Código de Erro: ${oResponse.codigoErro}. Entre em contato com a Getnet`;
        }
    };

    this.getSubError = (oResponse) => {
        switch (oResponse.codigoErro) {
            case 5300:
                return "Valor não informado";
            case 5301:
                return "Cartão Inválido";
            case 5302:
                return "Cartão Vencido";
            case 5303:
                return "Data de vencimento inválida";
            case 5304:
                return "Código de segurança inválido";
            case 5305:
                return "Taxa de serviço excede limite";
            case 5306:
                return "Operação não permitida";
            case 5307:
                return "Dados inválidos";
            case 5308:
                return "Valor mínimo da parcela inválido";
            case 5309:
                return "Número de parcelas inválido";
            case 5310:
                return "Número de parcelas excede limite";
            case 5311:
                return "Valor da entrada maior ou igual ao valor da transação";
            case 5312:
                return "Valor da parcela inválido";
            case 5313:
                return "Data inválida";
            case 5314:
                return "Prazo excede limite";
            case 5316:
                return "NSU inválido";
            case 5318:
                return "Documento inválido (CPF ou CNPJ)";
            case 5319:
                return "Valor do documento inválido";
            case 5364:
                return "Data de emissão do cartão inválida";
            case 5366:
                return "O tipo de financiamento informado não é coerente com o número de parcelas";
            default:
                if (oResponse.display) {
                    return oResponse.display[0].mensagem;
                } else {
                    return `Erro não encontrado: Retorno: ${oResponse.retorno} / Código de Erro: ${oResponse.codigoErro}. Entre em contato com a Getnet`;
                }
        }
    };

    this.simulaPinpad = () => {
        simulaPinpad = {
            response: {teste: "TESTE"},
            erro: false,
            status: "Aprovado",
            mensagem: "",
            codigoAprovacao: "101010",
            nsuCTF: "101010",
            bandeira: "Master",
            cartao: "**************",
            redeAdquirente: "101010",
            nsuAutorizadora: "101010"
        };
    };
}

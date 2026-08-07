/**
 * Classe para acrescentar campos adicionar á aprovação de alvará
 */
var AlvaraOnline = function () {
    let oSecao;

    this.setSecao = (secao) => {
        oSecao = secao;
        return this;
    };

    /**
     * Cria um campo para selecionar o grau de risco
     */
    this.criaGrauRisco = async () => {
        const atividades = document.getElementById(oSecao.nome);

        const table = document.createElement("table");
        table.setAttribute("id", "table_atividade");
        table.setAttribute("style", "margin-top: 20px;");

        const tr = document.createElement("tr");
        const tdLabel = document.createElement("td");
        tdLabel.innerHTML = "<strong>Grau de Risco:</strong>";
        const tdCampo = document.createElement("td");

        const selectGrauRisco = document.createElement("select");
        selectGrauRisco.setAttribute("id", "grauRisco");
        selectGrauRisco.setAttribute("name", "grauRisco");
        selectGrauRisco.setAttribute("class", "camposAdicionais");
        selectGrauRisco.setAttribute("secao", oSecao.nome);

        const grausRisco = [
            {
                codigo: "A",
                descricao: "ALTO"
            },
            {
                codigo: "M",
                descricao: "MÉDIO"
            },
            {
                codigo: "B",
                descricao: "BAIXO"
            }
        ];

        const { grauRisco } = await this.verificaGrauRisco();

        grausRisco.forEach((oGrauRisco, index) => {
            const option = document.createElement("option");
            option.setAttribute("value", oGrauRisco.codigo);
            option.innerHTML = oGrauRisco.descricao;

            if (grauRisco == oGrauRisco.codigo) {
                option.setAttribute("selected", "selected");
            }

            selectGrauRisco.appendChild(option);
        });

        tdCampo.appendChild(selectGrauRisco);
        tr.appendChild(tdLabel)
        tr.appendChild(tdCampo)
        table.appendChild(tr);

        atividades.insertBefore(table, null);

        return this;
    };

    /**
     * Verifica o grau de risco com base nos atividades selecionadas
     */
    this.verificaGrauRisco = () => {
        const atividades = document.getElementById(oSecao.nome);
        const codigo = atividades.firstChild.id.split("_")[1];

        const tableBody = document.getElementById(`collection_${codigo}body`);
        const tBody = tableBody.firstChild;
        const aTrsBody = [...tBody.children];

        const aAtividades = [];

        aTrsBody.forEach((oTr) => {
            const codigoAtividade = [...oTr.childNodes][0].innerHTML.split("-")[1].trim();
            aAtividades.push(codigoAtividade);
        });

        const data = new FormData();
        data.append('exec', 'verificaGrauRiscoAtividade');
        data.append('aCodigosAtividade', aAtividades);

        return HttpClient.post('iss1_ativid.RPC.php', { body: data }).then(function (response) {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            return response;
        });
    };

    /**
     * Adiciona mais uma coluna na grid para selecionar se a atividade é permanente ou provisória e a data desta
     */
    this.criaCampoAtividadeProvisoria = () => {
        const atividades = document.getElementById(oSecao.nome);
        const codigo = atividades.firstChild.id.split("_")[1];

        const tableHeader = document.getElementById(`tablecollection_${codigo}header`);
        const trHeader = tableHeader.firstChild.firstChild;

        const headerSelect = document.createElement("td");
        headerSelect.setAttribute("class", "table_header cell");
        headerSelect.setAttribute("title", "Permanente");
        headerSelect.setAttribute("gridcolnumber", "3");
        headerSelect.setAttribute("nowrap", "");
        headerSelect.innerHTML = "Permanente"

        trHeader.appendChild(headerSelect);

        const width = 100 / trHeader.cells.length;

        [...trHeader.cells].forEach((oElement) => {
            oElement.setAttribute("style", `width:${width}%`)
        });

        const tableBody = document.getElementById(`collection_${codigo}body`);
        const tBody = tableBody.firstChild;
        const aTrsBody = [...tBody.children];

        aTrsBody.forEach((oElement, indexAtividade) => {
            const td = document.createElement("td");
            td.setAttribute("class", "linhagrid cell");
            td.setAttribute("style", "text-align: center; vertical-align: middle;");
            td.setAttribute("nowrap", "");

            const sNomeCampoData = `data_${codigo}_${indexAtividade}`;

            const selectProvisorio = document.createElement("select");
            selectProvisorio.onchange = (oElement) => {
                if (oElement.target.value == 0) {
                    document.getElementById(sNomeCampoData).value = "";
                    $(sNomeCampoData).hide();
                } else {
                    $(sNomeCampoData).show();
                }
            };

            ["PERMANENTE", "PROVISÓRIO"].forEach((permanente, index) => {
                const option = document.createElement("option");
                option.setAttribute("value", index);
                option.innerHTML = permanente;

                selectProvisorio.appendChild(option);
            });

            const inputDate = document.createElement("input");
            inputDate.setAttribute("type", "date");
            inputDate.setAttribute("class", "camposAdicionaisGrid");
            inputDate.setAttribute("style", "margin-top:5px; display:none");
            inputDate.setAttribute("id", sNomeCampoData);
            inputDate.setAttribute("indice", indexAtividade);
            inputDate.setAttribute("secao", oSecao.nome);
            inputDate.setAttribute("campo", "campoProvisorioPermanente");

            td.appendChild(selectProvisorio);
            td.appendChild(document.createElement("br"));
            td.appendChild(inputDate);

            oElement.appendChild(td)
        });

        aTrsBody.forEach((oElement) => {
            const aTdsBody = [...oElement.children];

            aTdsBody.forEach((oChildren, index) => {
                let sSTyle =  `width:${width}%;`;

                if ((aTdsBody.length - 1) != index) {
                    sSTyle += "text-align: left;"
                }

                oChildren.setAttribute("style", sSTyle);
            });
        });

        return this;
    };

    this.criarCamposInscricoes = (ecidadeInfo, ano, processo) => {
        const data = new FormData();
        data.append('numeroProcesso', processo);
        data.append('anoProcesso', ano);
        data.append('DB_instit', ecidadeInfo.codigoInstituicao);
        data.append('DB_coddepto', ecidadeInfo.codigoDepartamento);
        data.append('DB_id_usuario', ecidadeInfo.codigoUsuario);

        js_divCarregando('Verificando se possui inscrições', 'loading_message_inscricoes');
        HttpClient.post(
            `${ecidadeInfo.apiUrl}patrimonial/ouvidoria/atendimento/atendimento/existeInscricao`,
            {
                body: data,
            }
        ).then(response => {
            if (response.data.success) {
                const containerInscricoes = document.getElementById("containerInscricoes");
                let html = [];
                html.push(`<fieldset style="display:flex"> <legend>Inscrições</legend>`);
                response.data.inscricoes.forEach((value) => {
                    html.push(`<a style="display: flex">
                             <input type="radio" name="inscricao" id="inscricao" value="${value}" style="margin:-2px 3px"/>
                             <b style="color:blue" onclick="abrirInscricao(${value})">${value}</b>
                    </a>`);
                });
                html.push(`</fieldset>`);
                containerInscricoes.innerHTML = html.join("");
            }
        }).finally(()=>{
            js_removeObj('loading_message_inscricoes');
        });

        return;

    };
}

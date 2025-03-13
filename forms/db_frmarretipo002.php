<div id="pix">
    <fieldset class="fieldsetPrincipal">
        <legend>Configuração PIX</legend>
        <table width="100%" style="padding:15px">
            <tr>
                <td>
                    <label for="modsistema" style="width:181px; display: inline-block;">
                        <b>Aceitar modalidade sistema:</b>
                    </label>
                    <input type="checkbox" name="modsistema" id="modsistema">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="moddbpref" style="width:181px; display: inline-block;">
                        <b>Aceitar modalidade DBPref: </b>
                    </label>
                    <input type="checkbox" name="moddbpref" id="moddbpref">
                </td>
            </tr>
        </table>
    </fieldset>

    <fieldset class="fieldsetPrincipal">
        <legend>Cadastro PIX</legend>
        <table width="100%" style="padding:15px">
            <tr>
                <td>
                    <label for="dtini" style="width:80px; display: inline-block;">
                        <b>Data inicial: </b>
                    </label>
                    <?php
                        db_inputdata(
                            'dtini',
                            null,
                            null,
                            null,
                            true,
                            'text',
                            1,
                            ""
                        );
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="dtfim" style="width:80px; display: inline-block;">
                        <b>Data final: </b>
                    </label>
                    <?php
                        db_inputdata(
                            'dtfim',
                            null,
                            null,
                            null,
                            true,
                            'text',
                            1,
                            ""
                        );
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>

    <fieldset class="fieldsetPrincipal">
        <legend>Inclusão de API</legend>
        <table style="padding:15px">
            <tr>
                <td>
                    <label for="modsistema" style=" display: inline-block;">
                        <b>Banco/API:</b>
                        <select
                            id="inputEstado" 
                            class="form-control"
                        >
                        </select>
                        <button 
                            type="button"
                            id="addBtn"
                        >
                            Adicionar
                        </button>
                    </label>
                </td>
            </tr>
        </table>
        <div>
            <div id="tabelaBancos">
                <table 
                    name="cadastrobancos" 
                    class="bootstrap-table" 
                    style="width: 55% !important; margin: 1em auto;"
                >
                    <thead>
                        <tr style="width: 100px;">
                            <th style="text-align:left">Banco</th>
                            <th style="text-align:center;">Ação</th>
                        </tr>
                    </thead>
                    <tbody id="tbody"></tbody>
                </table>
            </div>
            <div id="msg" style="text-align: center; padding: 1em;">
                Nenhuma opção de banco foi encontrada.
            </div>
        </div>
    </fieldset>

    <fieldset class="fieldsetPrincipal">
        <legend>Intervalo randômico</legend>
        <table width="100%" style="padding:15px">
            <tr>
                <td>
                    <label style="width:200px; display: inline-block;">
                        <b>Quantidade de emissão por banco: </b>
                    </label>
                    <input type="text" name="qtdemissao" id="qtdemissao" style="width: 150px;">
                </td>
            </tr>
        </table>
    </fieldset>
</div>

<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script src="scripts/classes/http/http.js"></script>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script>
    const 
        k00_tipo          = <?= $k00_tipo ?>,
        url               = "<?= ECIDADE_REQUEST_PATH ?>",
        listaBancos       = [],
        listaBancosSelect = {},
        codbank           = () => document.querySelectorAll(".codbank"),
        elementos         = {
            btnAba:       document.getElementById("PIX"),
            btnAddBanco:  document.getElementById("addBtn"),
            select:       document.getElementById("inputEstado"),
            tbody:        document.getElementById("tbody"),
            tabela:       document.getElementById("tabelaBancos"),
            msg:          document.getElementById("msg")
        },
        inputs            = {
            modsistema:   document.getElementById("modsistema"),
            moddbpref:    document.getElementById("moddbpref"),
            dtini:        document.getElementById("dtini"),
            dtfim:        document.getElementById("dtfim"),
            qtdemissao:   document.getElementById("qtdemissao")
        };

    function requestTipodeDebito()
    {
        const data   = new FormData();
        const bancos = codbank();

        for (let index in inputs)
        {
            const 
                name  = inputs[index].name,
                value = getValueInput(inputs[index]);

            if (value != "" && value != 0)
            {
                data.append(name, value);  
            }
        }

        for (let index = 0; index < bancos.length; index++)
        {
            data.append("codbank[]", bancos[index].id);
        }

        data.append("k00_tipo", k00_tipo)
        PHPSession.appendFormData(data);

        HttpClient.post(
            url + "v4/api/tributario/arrecadacao/tipo-debito/atualizar/" + k00_tipo,
            {body: data}
        ).then((res) => {
            let message = res.message;

            if (typeof message == 'object') {
                message = JSON.stringify(message);
            }
            
            alert(message);
        });
    }

    function getValueInput(input)
    {
        if (input.type == "checkbox")
        {
            return ((input.checked) ? 1 : 0);
        }
        else if (input.id == "dtini" || input.id == "dtfim")
        {
            let value = input.value;

            if (
                typeof value == "string" &&
                value.length == 10
            )
            {
                value = value.replace(/(\d{2}).?(\d{2}).?(\d{4})/, "$3-$2-$1");
                
                return ((value.length == 10) ? value : "");
            }
        }

        return input.value;
    }

    async function baixarListaBanco()
    {
        while (listaBancos.length)
        {
            listaBancos.pop();
        }

        HttpClient.get(url + "v4/api/configuracao/banco-pix/listar")
        .then((res) => {
            for (let index in res.message)
            {
                if (
                    res.message[index] && 
                    typeof res.message[index] == "object"
                )
                {
                    listaBancos.push(res.message[index]);
                }
            }

            updateListaBanco();
        });
    }

    async function getRequestDados()
    {
        await HttpClient.get(url + "v4/api/tributario/arrecadacao/tipo-debito/" + k00_tipo)
        .then((res) => {
            if (
                res.status == "Success" &&
                typeof res.message == "object"
            )
            {
                for (let index in res.message)
                {   
                    if (inputs.hasOwnProperty(index))
                    {   
                        if (res.message[index] === null)
                        {
                            continue;
                        }

                        if (index == "dtini" || index == "dtfim")
                        {   
                            inputs[index].value = res.message[index].replace(/(\d{4})-?(\d{2})-?(\d{2})/g, "$3/$2/$1");
                            continue;
                        }

                        if (index == 'modsistema' || index == 'moddbpref')
                        {
                            if (res.message[index] == true)
                            {
                                inputs[index].checked = true;
                            }
                            else
                            {
                                inputs[index].checked = false;
                            }
                        }
                        
                        inputs[index].value = res.message[index];
                    }
                }

                if (
                    typeof res.message.bancos == "object" &&
                    res.message.bancos.length > 0
                )
                {
                    for (let index in res.message.bancos)
                    {
                        let banco = res.message.bancos[index];
                        
                        if (typeof banco == "object")
                        {
                            let 
                                value = banco.db90_codban,
                                desc  = banco.dadosBanco.db90_descr;

                            listaBancosSelect[value] = desc;
                        }
                    }
                }
            }
            else 
            {
                alert(res.message);
            }

            atualizarListaBanco();
        });
    }

    function updateListaBanco()
    {
        elementos.select.innerHTML = "";
        if (listaBancos.length > 0)
        {
            for (let index in listaBancos)
            {
                let banco = listaBancos[index];

                if (banco != undefined && banco.hasOwnProperty("dadosBanco"))
                {
                    if (!listaBancosSelect.hasOwnProperty(banco.dadosBanco.db90_codban))
                    {
                        let option = document.createElement("option");

                        option.innerHTML = banco.dadosBanco.db90_descr;
                        option.value     = banco.dadosBanco.db90_codban;

                        elementos.select.append(option);
                    }
                }
            }
        }

        if (elementos.select.innerHTML == "")
        {
            let option = document.createElement("option");

            option.innerHTML               = "Nenhum banco foi encontrado";
            option.disabled                = true;
            option.selected                = true;
            elementos.btnAddBanco.disabled = true;

            elementos.select.append(option);
        }
        else
        {
            elementos.btnAddBanco.disabled = false;
        }

        if (Object.keys(listaBancosSelect).length <= 0)
        {
            elementos.tabela.style.display = "none";
            elementos.msg.style.display    = "block";
        }
        else 
        {
            elementos.tabela.style.display = "block";
            elementos.msg.style.display    = "none";
        }
    }

    function adicionarBanco(bancoName, value)
    {
        let tr       = document.createElement("tr");

        tr.id        = value;
        tr.classList.add("codbank"); 
        tr.innerHTML = `
            <td>${bancoName}</td>
            <td style="text-align:center;">
                <button onclick="removerBanco(this)" type="button">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        elementos.tbody.append(tr);
    }

    function atualizarListaBanco()
    {
        elementos.tbody.innerHTML = "";

        for (let index in listaBancosSelect)
        {   
            adicionarBanco(listaBancosSelect[index], index);
        }   

        updateListaBanco();
    }

    function removerBanco(btn)
    {
        let tr = btn.parentElement.parentElement;

        delete listaBancosSelect[tr.id];

        tr.remove();

        atualizarListaBanco();
    }

    function adicionarBancoClick()
    {
        let 
            value  = elementos.select.value,
            option = elementos.select.querySelector(`option[value="${value}"`);

        if (option && !option.disabled)
        {
            listaBancosSelect[value] = option.innerHTML;

            atualizarListaBanco();
        }
    }
    window.addEventListener('load', async function ()
    {   
        await PHPSession.loadData().then(async () => {
            
            await baixarListaBanco();

            elementos.btnAddBanco.addEventListener(
                "click", 
                adicionarBancoClick
            );

            const abasBtn = oAbas.oContainerSeletores.querySelectorAll(".aba");

            for (let index = 0; index < abasBtn.length; index++)
            {
                abasBtn[index].addEventListener("click", function(){
                    if (this.id == "PIX")
                    {
                        inputAction.style.display = "none";
                        inputActionPIX.style.display = "inline-block";

                        return;
                    }

                    inputAction.style.display = "inline-block";
                    inputActionPIX.style.display = "none";
                });
            }

            await getRequestDados();
        });

    });
</script>

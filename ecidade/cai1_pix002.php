<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009 DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

$k36_modcarnepadrao = null;

if (isset($_GET["k36_modcarnepadrao"]))
{
    $alterar            = true;
    $k36_modcarnepadrao = filter_input(
        INPUT_GET, 
        "k36_modcarnepadrao", 
        FILTER_SANITIZE_NUMBER_INT
    );
}
?>
<?php if (!is_null($k36_modcarnepadrao) && is_numeric($k36_modcarnepadrao)): ?>
<!DOCTYPE html>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <link rel="stylesheet" type="text/css" href="estilos.css" />
        <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css" rel="stylesheet" />
        <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css" rel="stylesheet" />
        <!-- <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"> -->
    </head>
    <body>   
        <div>
            <fieldset style="width: 50%; margin: 0px auto;">
                <legend>Configuração PIX</legend>
                <table style="padding:15px">
                    <tr>
                        <td>
                            <label for="modsistema" style="width:181px; display: inline-block;">
                                <b>Aceitar modalidade modelo:</b>
                            </label>
                            <input type="checkbox" name="k48_ammpix" id="k48_ammpix">
                        </td>
                    </tr>
                </table>
            </fieldset>
            <fieldset style="width: 50%; margin: 10px auto;">
                <legend>Inclusão de API/Emissão geral</legend>
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
            </fieldset>
        </div>
        <?php if (isset($alterar) and $alterar): ?>
            <div style="text-align: center">
                <input 
                    name="Alterar"
                    value="Alterar" 
                    type="submit" 
                    onclick="return requestRegraEmissao();"
                >
            </div>
        <?php endif; ?>
        </form>
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script src="scripts/classes/http/http.js"></script>
        <script rel="script" type="text/javascript" src="scripts/session.js"></script>
        <script>
            const 
                url               = "<?= ECIDADE_REQUEST_PATH ?>",
                k48_sequencial    = <?= (isset($k36_modcarnepadrao) ? $k36_modcarnepadrao : "null") ?>,
                listaBancos       = [],
                listaBancosSelect = {},
                elementos         = {
                    btnAddBanco:  document.getElementById("addBtn"),
                    select: document.getElementById("inputEstado"),
                    tbody:  document.getElementById("tbody"),
                    tabela: document.getElementById("tabelaBancos"),
                    msg: document.getElementById("msg")
                },
                inputs            = {
                    select: document.getElementById("k48_ammpix")
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

            function updateListaBanco()
            {
                elementos.select.innerHTML = "";

                for (let index in listaBancos)
                {
                    let banco = listaBancos[index];

                    if (!listaBancosSelect.hasOwnProperty(banco.dadosBanco.db90_codban))
                    {
                        let option = document.createElement("option");

                        option.innerHTML = banco.dadosBanco.db90_descr;
                        option.value     = banco.dadosBanco.db90_codban;

                        elementos.select.append(option);
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

            function requestRegraEmissao()
            {
                let 
                    data     = new FormData(),
                    codbanks = document.getElementsByClassName("codbank");

                data.append("k48_ammpix", ((inputs.select.checked) ? 1 : 0));
                data.append("k48_sequencial", k48_sequencial);

                for (let index = 0; index < codbanks.length; index++)
                {
                    data.append("codbank[]", codbanks[index].id);
                }

                PHPSession.appendFormData(data);

                HttpClient.post(
                    url + "v4/api/tributario/arrecadacao/regra-emissao/atualizar/" + k48_sequencial, 
                    {
                        body: data
                    }
                )
                .then((res) => {
                    alert(res.message);
                });
            }

            function getDadosRegraEmissao()
            {
                HttpClient.get(
                    url + "v4/api/tributario/arrecadacao/regra-emissao/" + k48_sequencial
                )
                .then((res) => {
                    if (
                        res.status == "Success" &&
                        typeof res.message == "object"
                    )
                    {
                        inputs.select.checked = (res.message.k48_ammpix);

                        for (let index in res.message.bancos)
                        {
                            let 
                                banco = res.message.bancos[index],
                                value = banco.db90_codban,
                                desc  = banco.dadosBanco.db90_descr;

                            listaBancosSelect[value] = desc;
                        }
                    }
                    else 
                    {
                        alert(res.message);
                    }

                    atualizarListaBanco();
                });
            }

            window.onload = async function()
            {
                await PHPSession.loadData().then(async () => {
                    
                    await baixarListaBanco();

                    elementos.btnAddBanco.addEventListener(
                        "click", 
                        adicionarBancoClick
                    );

                    getDadosRegraEmissao();
                });
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
        </script>
    </body>
</html>
<?php endif; ?>

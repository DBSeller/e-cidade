<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification('libs/db_conecta.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<!doctype html>
<<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
</head>
<body class="body-default">
<div>
    <form id="frmCampanha" class="container">
        <fieldset>
            <legend>Campanha Publicitária</legend>
            <table class="form-container">
                <tr>
                    <td><label id="ancoraMaterial" for="material"><a href="#">Nome da campanha:</a></label></td>
                    <td>
                        <input type="text" id="codigoMater" lang="pc01_codmater" class="field-size3 readonly" readonly>
                        <input type="text" id="descricaoMater" lang="pc01_descrmater" class="readonly field-size7 readonly" readonly>
                    </td>
                </tr>
                <tr>
                    <td><label id="ancoraCgm" for="cgm"><a href="#">Agência contratada:</a></label></td>
                    <td>
                        <input type="text" id="numCgm" lang="z01_numcgm" class="field-size3 readonly" readonly>
                        <input type="text" id="nomeCgm" lang="z01_nome" class="readonly field-size7 readonly" readonly>
                    </td>
                </tr>
                <tr>
                    <td><label for="dataInicio">Data de início:</label></td>
                    <td>
                        <input type="date" name="dataInicio" id="dataInicio">
                    </td>
                </tr>
                <tr>
                    <td><label for="dataFim">Data de encerramento:</label></td>
                    <td>
                        <input type="date" name="dataFim" id="dataFim">
                    </td>
                </tr>
                <tr>
                    <td><label for="valorCampanha">Valor total da campanha:</label></td>
                    <td>
                        <input type="number" name="valorCampanha" id="valorCampanha">
                    </td>
                </tr>
                <tr>
                    <td><label for="comissaoProducao">% Comissão da agência sobre serviços de Produção:</label></td>
                    <td>
                        <input type="number" name="comissaoProducao" id="comissaoProducao">
                    </td>
                </tr>
                <tr>
                    <td><label for="comissaoVeiculacao">% Comissão da agência sobre os serviços de Veiculação:</label></td>
                    <td>
                        <input type="number" name="comissaoVeiculacao" id="comissaoVeiculacao">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="tipoCampanha">Tipo da Campanha:</label>
                    </td>
                    <td>
                        <select id="tipoCampanha" name="tipoCampanha">
                            <option value="">Selecione um tipo de campanha</option>
                            <option value="0">0 - Campanhas Institucionais Gerais</option>
                            <option value="1">1 - Festas Municipais</option>
                            <option value="2">2 - Festas Regionais</option>
                            <option value="3">3 - Festas Nacionais</option>
                            <option value="4">4 - Campanhas Educativas da Saúde</option>
                            <option value="5">5 - Campanhas Educativas de Sociais</option>
                            <option value="6">6 - Campanhas Educativas Outras</option>
                            <option value="7">7 - Outras Campanhas</option>

                        </select>
                    </td>
                </tr>

            </table>
        </fieldset>
        <button type="button" id="btnSalvarCampanha">
            Salvar
        </button>

        <button type="button" id="btnLimpar">
            Limpar
        </button>
    </form>
</div>
</body>
</html>
<script type="text/javascript">
    const urlRPC = 'pat1_campanha_publicitaria.RPC.php';

    const formCampanha = {
        frm : document.getElementById('frmCampanha'),
        codigoMater: document.getElementById('codigoMater'),
        descricaoMater: document.getElementById('descricaoMater'),
        numCgm : document.getElementById('numCgm'),
        nomeCgm : document.getElementById('nomeCgm'),
        dataInicio : document.getElementById('dataInicio'),
        dataFim : document.getElementById('dataFim'),
        valorCampanha : document.getElementById('valorCampanha'),
        comissaoProducao : document.getElementById('comissaoProducao'),
        comissaoVeiculacao : document.getElementById('comissaoVeiculacao'),
        tipoCampanha : document.getElementById('tipoCampanha'),
        salvar: document.getElementById('btnSalvarCampanha'),
        limpar: document.getElementById('btnLimpar'),
    };

    function limparCampos() {
        formCampanha.numCgm.value = "";
        formCampanha.nomeCgm.value = "";
        formCampanha.dataInicio.value = "";
        formCampanha.valorCampanha.value = "";
        formCampanha.dataFim.value = "";
        formCampanha.comissaoProducao.value = "";
        formCampanha.comissaoVeiculacao.value = "";
        formCampanha.tipoCampanha.value = "";
    };

    const lookUpMaterial = new DBLookUp(
        document.getElementById('ancoraMaterial'),
        formCampanha.codigoMater,
        formCampanha.descricaoMater,{
            'sArquivo' : 'func_itemcampanhapublicitaria.php',
            'sLabel' : 'Pesquisar itens',
            'sObjetoLookUp': "db_iframe_itemcampanhapublicitaria",
        }
    );

    lookUpMaterial.setCallBack('onClick', (resposta) => {
        let formDataResposta = new FormData();
        formDataResposta.append('codigoMater',resposta[0]);
        formDataResposta.append('acao','buscarCampanhaPublicitaria')
        HttpClient.post(urlRPC, {
            body:formDataResposta
        }).then( (response) => {
            if(response.resultado){
                formCampanha.numCgm.value = response.resultado.cgm;
                formCampanha.nomeCgm.value = response.resultado.nomeCgm;
                formCampanha.valorCampanha.value = response.resultado.valorCampanha;
                formCampanha.dataInicio.value = response.resultado.dataInicio;
                formCampanha.dataFim.value = response.resultado.dataFim;
                formCampanha.comissaoProducao.value = response.resultado.comissaoProducao;
                formCampanha.comissaoVeiculacao.value = response.resultado.comissaoVeiculacao;
                formCampanha.tipoCampanha.value = response.resultado.tipoCampanha;
            } else {
                limparCampos();
            }
        })
    });

    const lookUpCgm = new DBLookUp(
        document.getElementById('ancoraCgm'),
        formCampanha.numCgm,
        formCampanha.nomeCgm, {
            'sArquivo' : 'func_cgm.php',
            'sLavel' : 'Pesquisar agência contratada',
            'sObjetoLookUp' : 'func_nome'
        }
    );


    formCampanha.salvar.addEventListener('click',() => {
        const formData = new FormData(formCampanha.frm)
        formData.append('dataInicio',formCampanha.dataInicio.value);
        formData.append('dataFim',formCampanha.dataFim.value);
        formData.append('comissaoProducao',formCampanha.comissaoProducao.value);
        formData.append('comissaoVeiculacao',formCampanha.comissaoVeiculacao.value);
        formData.append('tipoCampanha',formCampanha.tipoCampanha.value);
        formData.append('codigoMater',formCampanha.codigoMater.value);
        formData.append('valorCampanha',formCampanha.valorCampanha.value);
        formData.append('cgm',formCampanha.numCgm.value);
        formData.append('acao','criarCampanhaPublicitaria');

        if (!checaForm()){
            return;
        }

        HttpClient.post(urlRPC,{
            body:formData,
            reportMessage:"Aguarde..."
        }).then(() => {alert("Dados da campanha salvos com sucesso!")});

    });

    formCampanha.limpar.addEventListener('click', () => {
        formCampanha.codigoMater.value = "";
        formCampanha.descricaoMater.value = "";
        limparCampos()
    });

    function checaForm(){
        if(formCampanha.codigoMater.value.empty()){
            alert("Você deve selecionar uma campanha!");
            return false;
        }
        if(formCampanha.numCgm.value.empty()){
            alert("Você deve selecionar uma agência!");
            return false;
        }
        if (formCampanha.dataInicio.value == ""){
            alert("A data inicial é obrigatória");
            return false;
        }
        if (formCampanha.dataFim.value == ""){
            alert("A data final é obrigatória");
            return false;
        }
        if(formCampanha.dataFim.value < formCampanha.dataInicio.value){
            alert("A data inicial não pode ser maior que a final!");
            return false;
        }
        if(formCampanha.valorCampanha.value < 0 ){
            alert("O valor da campanha não pode ser menor que 0.");
            return false;
        }
        if(formCampanha.valorCampanha.value.empty()){
            alert("O valor da campanha não pode ser vazia.");
            return false;
        }
        if(formCampanha.comissaoVeiculacao.value >= 100){
            alert("A % Comissão da agência sobre os serviços de Veiculação não pode ser maior que 100.");
            return false;
        }
        if(formCampanha.comissaoVeiculacao.value < 0){
            alert("A % Comissão da agência sobre os serviços de Veiculação não pode ser menor que 0.");
            return false;
        }
        if (formCampanha.comissaoVeiculacao.value.empty()){
            alert("A % Comissão da agência sobre os serviços de Veiculação não pode ser vazia.");
            return false;
        }
        if(formCampanha.comissaoProducao.value >= 100){
            alert("A % Comissão da agência sobre os serviços de Produção não pode ser maior que 100.");
            return false;
        }
        if(formCampanha.comissaoProducao.value < 0){
            alert("A % Comissão da agência sobre os serviços de Produção não pode ser menor que 0.");
            return false;
        }
        if (formCampanha.comissaoProducao.value.empty()){
            alert("A % Comissão da agência sobre os serviços de Produção não pode ser vazia.");
            return false;
        }
        if(formCampanha.tipoCampanha.value.empty()){
            alert("Você deve selecionar o tipo da campanha.");
            return false;
        }
        return true;
    };

</script>


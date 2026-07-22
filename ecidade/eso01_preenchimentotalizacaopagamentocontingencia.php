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
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>

<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DBSeller Informática Ltda</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script src="scripts/scripts.js" rel="script" type="text/javascript"></script>
    <script src="scripts/strings.js" rel="script" type="text/javascript"></script>
    <script src="scripts/prototype.js" rel="script" type="text/javascript"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script src="scripts/object.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/DBLookUp.widget.js" rel="script" type="text/javascript"></script>
</head>
<body>
<form action="sped02_preenchimento.php" class="container" id="form1" onsubmit="return false;">
    <input type="hidden" value="2" name="integracao" id="integracao">
    <input type="hidden" value="35" name="formularioTipo" id="formularioTipo" >
    <fieldset>
        <legend>Indicativo de Período</legend>
        <table class="form-container">
            <tr id="tr_empregador" class="d-none">
                <td><label for="empregador">Empregador:</label></td>
                <td><select name="empregador" id="empregador"></select></td>
            </tr>
            <tr>
                <td><label>Indicativo de Período:</label></td>
                <td>
                    <select name="indicativoPeriodo" id="indicativoPeriodo">
                        <option value="1">Mensal (AAAA-MM)</option>
                        <option value="2">Anual (AAAA)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Período:</label></td>
                <td><input type="text" name="periodo" id="periodo" maxlength="7" class="field-size2"></td>
            </tr>
        </table>
    </fieldset>
    <input type="button" value="Salvar" id="proximo" name="proximo" onclick="return validaEnvio()">
</form>
</body>
<?php db_menu(); ?>
<script rel="script" type="text/javascript">
const 
    INTEGRACAO = 2,
    trEmpregador = document.getElementById('tr_empregador'),
    selectEmpregador = document.getElementById('empregador'),
    sUrlRPC = 'eso02_situacaoevento001.RPC.php';

const inicializar = () => {
    const formData = new FormData();
    formData.append('acao', 'inicializar');
    formData.append('integracao', INTEGRACAO);

    HttpClient.post('sped02_preenchimento.RPC.php', {
        body: formData
    }).then(response => {
        if (response.erro) {
            throw response.mensagem;
        }

        response.empregadores.map((empregadorOption, chave) => {
            const selecionado = chave === 0;

            selectEmpregador.add(
                new Option(empregadorOption.nome, empregadorOption.cgm),
                selecionado,
                selecionado
            );
        });
        trEmpregador.classList.remove('d-none');
    }).catch(mensagem => alert(mensagem));
};

const validaEnvio = () => {
    try {
        validaPeriodos();
        validaCriterios().then(() => {
            $('form1').submit();
            return true;
        }).catch((e) => {
            alert(e);
            return false;
        });
    }catch (e) {
        alert(e);
        return false;
    }
};

const validaPeriodos = () => {
    const periodo = $F('periodo');
    if (empty(periodo)) {
        throw 'Informe o período.';
    }
    if ($F('indicativoPeriodo') == 1 && periodo.match(/^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])/) == null)  {
        throw 'Período informado é incompatível com Indicativo de Período selecionado.';
    }

    if ($F('indicativoPeriodo') == 2 && periodo.length > 4) {
        throw 'Período informado é incompatível com Indicativo de Período selecionado.';
    }

    if ($F('indicativoPeriodo') == 2 && periodo.match(/^(19[0-9][0-9]|2[0-9][0-9][0-9])/) == null) {
        throw 'Período informado é incompatível com Indicativo de Período selecionado.';
    }

    if($F('indicativoPeriodo') == 1){
        var 
            mes = periodo.split('-')[1],
            ano = periodo.split('-')[0],
            date = new Date(),
            currentMonth = date.getMonth()+1;
        
        if (!(currentMonth - mes == 1 && date.getDate() <= 20) && !(mes == 12 && mes - currentMonth == 11 && date.getFullYear() - ano == 1)){
            throw ' Este arquivo somente poderá ser enviado entre os dias 01 e 20 do mês subsequente ao de apuração';           
        } 
    } else {
        var             
            date = new Date(),
            currentMonth = date.getMonth()+1;
        
        if (currentMonth != 12){
            throw ' Este arquivo somente poder ser enviado no mês de dezembro do ano atual para apuração anual';
        } else if(periodo != date.getFullYear()){
            throw ' Este arquivo somente poder ser enviado no mês de dezembro do ano atual para apuração anual';
        }
    }
};

const validaCriterios = () => {

    const 
        arrPromises = [],
        date        = new Date(),
        mes         = ((date.getMonth()+1) < 10) ? '0' + (+date.getMonth()+1) : date.getMonth() + 1,
        ano         = date.getFullYear(),
        dia         = date.getDate();

    //verifica se S1299 nao foi enviado com sucesso
    arrPromises.push(consultaDados('1299', 'true', 'false').then(response => {
        if(!!response){            
            if(!response.erro){
                var dados = response.dados.filter(dado => {
                    var perApur = JSON.parse(dado.dados).perApur;
                    return perApur == $F('periodo');
                });
                if(dados.length > 0){
                    throw 'Este arquivo só poderá ser enviado após o envio do S-1299 sem sucesso.';
                }
            }
        }
    }));

    //verifica se houve ocorrencia no envio do 1299
    arrPromises.push(consultaDados('1299', 'false', 'true').then(response => {
        if(!!response){
            if(!response.erro){
                var dados = response.dados.filter(dado => {
                    var perApur = JSON.parse(dado.dados).perApur;
                    return perApur == $F('periodo');
                });
                if(dados.length == 0){
                    throw 'Este arquivo só poderá ser enviado após o envio do S-1299 sem sucesso.';
                }
            } else {
                throw 'Este arquivo só poderá ser enviado após o envio do S-1299 sem sucesso.';
            }
        }
    }));

    //Evento só pode ser enviado 3 vezes até o dia 9
    if(dia <= 9){
        arrPromises.push(consultaDados('1295', 'false', 'false', `01/${mes}/${ano} 00:00`, `09/${mes}/${ano} 23:59`).then(response => {
            if(!!response){
                if(!response.erro){
                    var dados = response.dados.filter(dado => {
                        var perApur = JSON.parse(dado.dados).perApur;
                        return perApur == $F('periodo');
                    });
                    if(dados.length >= 3){
                        throw 'Este arquivo só pode ser enviado 3 vezes entre os dias 01 e 09 de cada mês.';
                    }   
                }
            }
        }));
    //Do dia 10 ao dia 20, pode ser enviado uma vez por dia
    } else {
        arrPromises.push(consultaDados('1295', 'false', 'false', `${dia}/${mes}/${ano} 00:00`, `${dia}/${mes}/${ano} 23:59`).then(response => {
            if(!!response){
                if(!response.erro){
                    var dados = response.dados.filter(dado => {
                        var perApur = JSON.parse(dado.dados).perApur;
                        return perApur == $F('periodo');
                    });
                    if(dados.length >= 1){
                        throw 'Este arquivo só pode ser enviado 1 vez por dia entre os dias 10 e 20 de cada mês.';
                    }   
                }
            }
        }));
    }
    
    return Promise.all(arrPromises);
}

const consultaDados = (strEvento, strStatusRecibo, strStatusOcorrencia, strDataInicial, strDataFinal) => {
    if($F('indicativoPeriodo') == 1){
        const 
            periodo  = $F('periodo'),
            mes      = periodo.split('-')[1],
            ano      = periodo.split('-')[0],
            formData = new FormData(),
            oParam   = {};

        oParam.exec = 'consultaDados';        
        oParam.aFiltros = {};
        oParam.aFiltros.idEvento = strEvento;
        oParam.aFiltros.statusRecibo = strStatusRecibo;
        oParam.aFiltros.statusOcorrencia = strStatusOcorrencia;
        oParam.aFiltros.tipoEvento = "2";
        oParam.aFiltros.statusErro = "false";
        oParam.aFiltros.inscricaoEmpregador = selectEmpregador.value;
        oParam.aFiltros.dataInicio = (!!strDataInicial) ? strDataInicial : `01/${mes}/${ano} 00:00`;
        oParam.aFiltros.dataFinal = (!!strDataFinal) ? strDataFinal : `20/${(mes == 12) ? '1' : (+mes + 1)}/${(mes == 12) ? (+ano + 1) : ano} 23:59`;

        formData.append('json', JSON.stringify(oParam));

        return HttpClient.post(sUrlRPC, {body: formData}).then(response => {
            return response;
        });
        
    } else {
        return new Promise((resolve, reject) => {resolve()});
    }
}


(function () {
    inicializar();
})();
</script>

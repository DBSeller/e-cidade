<?php
/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_arretipo_classe.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libpostgres.php"));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" href="estilos.css" rel="stylesheet">
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body>
</body>
<div id="divAgendamentos" class="container">
    <fieldset>
        <legend>Parêmetro Vencimentos de Parcelamento</legend>
        <table class="form-container" style="width:950px;">
            <tr>
                <td style="text-align: right">
                    <label>Dia do processamento:</label>&ensp;
                </td>
                <td>
                    <div id='diaProc' class="field-size3">
                        <select name='diaProc' class='inputselec' id="sltDiaProc">
                            <option value="">Selecione...</option>
                            <option value="SEGUNDA">Segunda-feira</option>
                            <option value="TERCA">Terça-feira</option>
                            <option value="QUARTA">Quarta-feira</option>
                            <option value="QUINTA">Quinta-feira</option>
                            <option value="SEXTA">Sexta-feira</option>
                            <option value="SABADO">Sábado</option>
                            <option value="DOMINGO">Domingo</option>
                        </select>
                    </div>
                </td>
                <td>
                    <label>Horário de processamento:</label> &nbsp;
                </td>
                <td>
                    <div id='horarioProc' class="field-size2">
                        <select name='horarioProc' class='inputselec' id="sltHorarioProc">
                            <option value="">--:--</option>
                            <option value=00:00:00>00:00</option>
                            <option value=01:00:00>01:00</option>
                            <option value=02:00:00>02:00</option>
                            <option value=03:00:00>03:00</option>
                            <option value=04:00:00>04:00</option>
                            <option value=05:00:00>05:00</option>
                            <option value=06:00:00>06:00</option>
                            <option value=07:00:00>07:00</option>
                            <option value=08:00:00>08:00</option>
                            <option value=09:00:00>09:00</option>
                            <option value=10:00:00>10:00</option>
                            <option value=11:00:00>11:00</option>
                            <option value=12:00:00>12:00</option>
                            <option value=13:00:00>13:00</option>
                            <option value=14:00:00>14:00</option>
                            <option value=15:00:00>15:00</option>
                            <option value=16:00:00>16:00</option>
                            <option value=17:00:00>17:00</option>
                            <option value=18:00:00>18:00</option>
                            <option value=19:00:00>19:00</option>
                            <option value=20:00:00>20:00</option>
                            <option value=21:00:00>21:00</option>
                            <option value=22:00:00>22:00</option>
                            <option value=23:00:00>23:00</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    <label>Quantidade de dias - recibos emitidos:</label> &nbsp;
                </td>
                <td>
                    <input type="text" id="quantDiasRec" class="field-size2">
                </td>
                <td style="text-align: right">
                    <label>Quantidade de dias - margem de tolerância:</label> &nbsp;
                </td>
                <td>
                    <input type="text" id="quantDiasTol" class="field-size2">
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    <label>Quantidade de parcelas vencidas:</label> &nbsp;
                </td>
                <td>
                    <input type="text" id="quantParcVenc" class="field-size2">
                </td>
                <td>
                    <label>Ação:</label>&ensp;
                </td>
                <td>
                    <div id='acao'>
                        <select name='acao' class='inputselec' id="sltAcao">
                            <option value="2">Selecione...</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="text-align: right">
                    <a id="regraParcelamento">Regra de Parcelamento: </a>&ensp;
                </td>
                <td colspan="2">
                    <input type="text" id="k40_codigo" class="field-size2">
                    <input type="text" id="k40_descr" class="field-size8 readonly">
                </td>
            </tr>
            <!-- <tr>
                <td style="text-align: right">
                    <span id="tipoParcelamento">Tipo de Parcelamento: </span>&ensp;
                </td>
                <td colspan="2">
                    <input type="text" id="tiposParcelamento" class="field-size8 readonly" readonly>
                </td>
            </tr> -->
        </table>
    </fieldset>
    <button id='btnSalvar'>
        <i class="fas fa-save"></i>
        Salvar
    </button>
</div>
&nbsp;
<div class="subcontainer" style="width: 1000px;">
    <fieldset>
        <legend>Regras</legend>
        <table id="data-table-regras"
            class="table table-sm">
        </table>
    </fieldset>
</div>
<?php db_menu(); ?>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script>
$.noConflict();
const routes = {
    salvar: 'tributario/arrecadacao/controle-parcelamentos-vencidos/agendamento/salvar',
    desativar: 'tributario/arrecadacao/controle-parcelamentos-vencidos/agendamento/desativar',
    getAgendamentos: 'tributario/arrecadacao/controle-parcelamentos-vencidos/agendamento',
    getAcoes: 'tributario/arrecadacao/controle-parcelamentos-vencidos/acoes',
    /* getByRegra: 'tributario/arrecadacao/controle-parcelamentos-vencidos/tipo-parcelamento' */
};

const sltDiaProcessamento = document.getElementById("sltDiaProc");
const sltHorarioProcessamento = document.getElementById("sltHorarioProc");
const inpQuantDiasRec = document.getElementById("quantDiasRec");
const inpQuantDiasTol = document.getElementById("quantDiasTol");
const inpQuantParcVenc = document.getElementById("quantParcVenc");
const sltAcao = document.getElementById("sltAcao");

const regraParcelamento = {
    ancora: document.getElementById('regraParcelamento'),
    id: document.getElementById('k40_codigo'),
    descricao: document.getElementById('k40_descr'),
    /* inputTiposParcelamento: document.getElementById('tiposParcelamento') */
};
const btnSalvar = document.getElementById('btnSalvar');
const divAgendamentos = document.getElementById('divAgendamentos');
const tabelaRegras = jQuery('#data-table-regras');

/* const callbackRegraParc = () => {
    let id = regraParcelamento.id.value;

    HttpClient.get(`${PHPSession.requestApi}/${routes.getByRegra}/${id}`).then(response => {
        let mensagem = [];
        for (let arretipo of response.data) {
            mensagem.push(arretipo.k00_descr);
        }

        regraParcelamento.inputTiposParcelamento.value = mensagem.join(', ');
    });
}; */

const lookUpRegraParcelamento = new DBLookUp(regraParcelamento.ancora, regraParcelamento.id, regraParcelamento.descricao, {
    'sArquivo': 'func_cadtipoparc.php',
    'sObjetoLookUp': 'db_iframe_cadtipoparc',
    'sLabel': 'Pesquisar Regra de Parcelamento',
    /* 'fCallBack': callbackRegraParc, */
    /* 'aParametrosAdicionais': ['apenasParcelamento'] */
});

btnSalvar.addEventListener('click', () => {
    if(!validaCampos()) {
        return;
    }

    const formData = new FormData();

    formData.append('sltDiaProc', sltDiaProcessamento.value);
    formData.append('sltHorarioProc', sltHorarioProcessamento.value);
    formData.append('quantDiasRec', inpQuantDiasRec.value);
    formData.append('quantDiasTol', inpQuantDiasTol.value);
    formData.append('quantParcVenc', inpQuantParcVenc.value);
    formData.append('sltAcao', sltAcao.value);
    formData.append('regraParcelamento', regraParcelamento.id.value);

    PHPSession.appendFormData(formData);

    HttpClient.post(`${PHPSession.requestApi}/${routes.salvar}`, {body: formData}).then(response => {
        alert(response.message);
        if (response.error) {
            return;
        }

        limpaCampos();
        buscaAgendamentos();
    });
});

jQuery(document).ready(jQuery => {
    const desativar = {
        'click .desativar': (e, d, data) => {
            if (!confirm('Confirma a desativação do agendamento?')) {
                return false;
            }

            const formData = new FormData();

            formData.append('id', data.ar49_id);

            PHPSession.appendFormData(formData);

            HttpClient.post(`${PHPSession.requestApi}/${routes.desativar}`, {body: formData}).then(response => {
                alert(response.message);
                if(response.error) {
                    return;
                }
                buscaAgendamentos();
            })
        }
    };

    tabelaRegras.bootstrapTable({
        height: 300,
        columns:[
            {
                field: 'ar49_id',
                title: 'Nº Proc.',
                align: 'center',
                width: 50,
            },
            {
                field: 'ar49_regra_parcelamento',
                title: 'Regra',
                align: 'center',
                width: 50,
            },
            {
                field: 'ar49_regra_parcelamento',
                title: 'Descrição',
                align: 'center',
                width: 260,
                formatter: (a, data) => {
                    return data.regra_parcelamento.k40_descr;
                }
            },
            {
                field: 'ar49_dia_semana',
                title: 'Dia Proc',
                align: 'center',
                width: 50,
            },
            {
                field: 'ar49_horario',
                title: 'Horario',
                align: 'center',
                width: 50,
            },
            {
                field: 'ar49_prazo_dias',
                title: 'DiasRec',
                align: 'center',
                width: 70,
            },
            {
                field: 'ar49_parcelas_vencidas',
                title: 'Parcelas',
                align: 'center',
                width: 50,
            },
            /* {
                field: 'ar49_tipo_parcelamento',
                title: 'Tipo(s) Débito(s)',
                align: 'center',
                width: 50,
            }, */
            {
                field: 'ar49_acao',
                title: 'Ação',
                align: 'center',
                width: 170,
                formatter: (a, data) => {
                    return data.acao.ar50_descricao;
                }
            },
            {
                field: 'actions',
                title: 'Opções',
                halign: 'center',
                align: 'center',
                width: 50,
                formatter: () => {
                    let btnDesativar = '<a class="desativar"><i class="fas fa-trash-alt"></i></a>';
                    return `${btnDesativar}`
                },
                events: desativar
            }
        ]
    });

    PHPSession.loadData().then(() => {
        // Preenche as ações
        HttpClient.get(`${PHPSession.requestApi}/${routes.getAcoes}`).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }

            sltAcao.options.length = 0;
            sltAcao.add(new Option('Selecione...', ''));
            for (let acao of response.data) {
                sltAcao.add(new Option(acao.ar50_descricao, acao.ar50_id))
            }
        });

        buscaAgendamentos();
    });
});

function validaCampos()
{
    if (sltDiaProcessamento.value == '') {
        alert('Selecione o Dia do processamento');
        return false;
    }
    if (sltHorarioProcessamento.value == '') {
        alert('Insira o Horário do processamento');
        return false;
    }
    if (inpQuantDiasRec.value == '') {
        alert('Insira a Quantidade de dias - recibos emitidos');
        return false;
    }
    if (inpQuantDiasTol.value == '') {
        alert('Insira a Quantidade de dias - margem de tolerância');
        return false;
    }
    if (inpQuantParcVenc.value == '') {
        alert('Insira a Quantidade de parcelas vencidas');
        return false;
    }
    if (sltAcao.value == '') {
        alert('Selecione a Ação');
        return false;
    }
    if (regraParcelamento.id.value == '') {
        alert('Selecione a Regra de parcelamento');
        return false;
    }
    /* if (tipoParcelamento.id.value == '') {
        alert('Selecione o Tipo de parcelamento');
        return false;
    } */
    return true;
}

function buscaAgendamentos()
{
    HttpClient.get(`${PHPSession.requestApi}/${routes.getAgendamentos}`).then(response => {
        if (response.error) {
            alert(response.message);
            return;
        }

        tabelaRegras.bootstrapTable('load', response.data);
    });
}

function limpaCampos()
{
    sltDiaProcessamento.value = '',
    sltHorarioProcessamento.value = '',
    inpQuantDiasRec.value = '',
    inpQuantDiasTol.value = '',
    inpQuantParcVenc.value = '',
    sltAcao.value = '',
    regraParcelamento.id.value = '',
    regraParcelamento.descricao.value = '';
}

</script>
</body>
</html>

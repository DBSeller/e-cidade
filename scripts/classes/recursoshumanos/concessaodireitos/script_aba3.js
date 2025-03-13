var $table1 = jQuery('#table1')
const colum = [{
    title: 'Sequencial',
    align: 'center',
    field: 'rh502_sequencial',
    sortable: true
},
{
    field: 'h12_codigo',
    class: 'hidden'
},
{
    field: 'rh502_codigo',
    title: 'codigo ',
    sortable: true
},
{
    field: 'rh502_condicao',
    title: 'Condição',
    align: 'center',
    sortable: true,
    formatter: Formattercondicao1
},
{
    field: 'rh502_resultado',
    title: 'Ação',
    align: 'center',
    sortable: true,
    formatter: Formatteracao1
},
{
    field: 'rh502_operador',
    title: 'Formula',
    align: 'center',
    sortable: true,
    formatter: Formatteroperadpr1
},
{
    field: 'rh502_multiplicador',
    title: 'multiplicador',
    align: 'center',
    sortable: true
},
{
    title: 'Ação',
    align: 'center',
    clickToSelect: false,
    formatter: FormatterPrincipal1
}
];
$table1.bootstrapTable({
    columns: colum,
    pageSize: 5,
    pagination: true
});

function Formattercondicao1(value, row, index) {
    if (value == 'inicio') {
        value = 'Inicio';
    } else if (value == 'antesdoinicio') {
        value = 'Antes do Inicio';
    } else if (value == 'meio') {
        value = 'Meio';
    } else if (value == 'final') {
        value = 'Final';
    } else if (value == 'interrompe') {
        value = 'Interrompe';
    }
    return [
        '<p>' + value + '</p>'
    ].join('')
}

function Formatteroperadpr1(value, row, index) {
    if (value == '+') {
        value = '+Dias';
    } else if (value == '-') {
        value = '-Dias';
    } else if (value == '*') {
        value = '*Dias';
    } else if (value == 'm+') {
        value = '+Meses';
    } else if (value == 'm-') {
        value = '-Meses';
    } else if (value == 'm*') {
        value = '*Meses';
    }
    return [
        '<p>' + value + '</p>'
    ].join('')
}
function Formatteracao1(value, row, index) {
    if (value == '+dias') {
        value = 'Protela';
    } else if (value == '-dias') {
        value = 'Antecipa';
    }else if (value == 'acao') {
        value = 'Acumula';
    }
    return [
        '<p>' + value + '</p>'
    ].join('')
}

function FormatterPrincipal1(value, row, index) {
    return [
        '<a id="editar" class="editar" onclick="atividadepg3(this);" title="editar">',
        '<i class="far fa-edit mr-5"></i>',
        '</a>  ',
        '<a class="remove" onclick="excluirpg3(this);" title="remove">',
        '<i class="fas fa-trash-alt"></i>',
        '</a>'
    ].join('')
}

function atividadepg3(e) {
    console.log(e.closest("a").closest("td").closest("tr"))
}

function atividadepg3(e) {

    rh502_sequencial = e.closest("a").closest("td").closest("tr").children[0].innerText;
    let assentamento = e.closest("a").closest("td").closest("tr").children[1].innerText;
    jQuery("#allIdAssentamentos").val(assentamento).change()
    jQuery("#allassentamaentos").val(assentamento).change()

    let multiplicacao = e.closest("a").closest("td").closest("tr").children[6].innerText;
    let condicao = e.closest("a").closest("td").closest("tr").children[3].innerText;
    let acao = e.closest("a").closest("td").closest("tr").children[4].innerText;
    let operador = e.closest("a").closest("td").closest("tr").children[5].innerText;

    switch (condicao) {
        case 'Inicio':
            condicao = 'inicio'
            break;
        case 'Antes do Inicio':
            condicao = 'antesdoinicio'
            break;
        case 'Meio':
            condicao = 'meio'
            break;
        case 'Final':
            condicao = 'final'
            break;
        case 'Interrompe':
            condicao = 'interrompe'
            break;
    }
    jQuery("#condicao").val(condicao).change()

    switch (acao) {
        case 'Protela':
            acao = '+dias';
            break;
        case 'Antecipa':
            acao = '-dias';
            break;
        case 'Acumula':
            acao = 'acao';
            break;
    }
    jQuery("#resultado").val(acao).change()
    switch (operador) {
        case '+Dias' || '+dias':
            operador = '+'
            break;
        case '-Dias' || '-dias':
            operador = '-'
            break;
        case '*Dias' || '*dias':
            operador = '*'
            break;
        case '+Meses' || '+meses':
            operador = 'm+'
            break;
        case '-Meses' || '-meses':
            operador = 'm-'
            break;
        case '*Meses' || '*meses':
            operador = 'm*'
            break;
    }
    jQuery("#operador").val(operador).change()
    jQuery("#multiplicacao").val(multiplicacao);
    jQuery(".alterarform").show()
    jQuery(".addform").hide()
}

function cancelarform() {
    jQuery(".alterarform").hide();
    jQuery(".addform").show();
    jQuery("#multiplicacao").val('');
    jQuery("#allIdAssentamentos").val(1).change()
    jQuery("#allassentamaentos").val(1).change()
    jQuery("#condicao").val(1).change()
    jQuery("#resultado").val(1).change()
    jQuery("#operador").val(1).change()
    rh502_sequencial = '';
}

function excluirpg3(e) {
    id = e.closest("a").closest("td").closest("tr").children[0].innerText;
    const data = {
        rh502_seqassentconf: id
    };
    const dado = new FormData;
    for (index in data) {
        dado.append(index, data[index]);
    }
    HttpClient.post(routers.deleteassentform, {
        body: dado
    })
        .then((res) => {
            if (res.hasOwnProperty('data')) {
                buscaconfiguracaoAba3()
                cancelarform()
            } else {
                alert(JSON.stringify(res));
            }
        });
}

function buscaconfiguracaoAba3() {
    const data = {
        rh502_seqassentconf: rh500_sequencial
    };
    const dado = new FormData;
    for (index in data) {
        dado.append(index, data[index]);
    }
    HttpClient.post(routers.assentform, {
        body: dado
    })
        .then((res) => {
            if (res.hasOwnProperty('data')) {
                let tipos = res.data.tipos;
                for (let index = 0; index < tipos.length; index++) {
                    var x = document.createElement("OPTION");
                    x.setAttribute("value", tipos[index].h12_codigo);
                    var t = document.createTextNode(tipos[index].h12_descr);
                    x.appendChild(t);
                    dadosPesquisa.allassentamaentos.appendChild(x);

                    var x = document.createElement("OPTION");
                    x.setAttribute("value", tipos[index].h12_codigo);
                    var t = document.createTextNode(tipos[index].h12_codigo);
                    x.appendChild(t);
                    dadosPesquisa.allIdAssentamentos.appendChild(x);
                }
                $table1.bootstrapTable('load', res.data.parametros)
            } else {
                alert('Erro!');
            }
        });
}
//Unifica Select Assentamentos
jQuery('#allIdAssentamentos').change(function () {
    if (jQuery('#allassentamaentos').children("option:selected").val() != jQuery('#allIdAssentamentos').children("option:selected").val()) {
        let value = jQuery('#allIdAssentamentos').children("option:selected").val();
        jQuery("#allassentamaentos").val(value).change();
    }
});

jQuery('#allassentamaentos').change(function () {
    if (jQuery('#allassentamaentos').children("option:selected").val() != jQuery('#allIdAssentamentos').children("option:selected").val()) {
        let value = jQuery('#allassentamaentos').children("option:selected").val();
        jQuery("#allIdAssentamentos").val(value).change();
    }
});



function addformpg3(acao) {
    if (jQuery('#multiplicacao').val() == '') {
        alert('Insira a Multiplicação');
        return
    }
    const data = {
        acao: acao,
        rh502_sequencial: rh502_sequencial,
        rh502_seqassentconf: rh500_sequencial,
        rh502_codigo: jQuery('#allassentamaentos').children("option:selected").val(),
        rh502_condicao: jQuery('#condicao').children("option:selected").val(),
        rh502_resultado: jQuery('#resultado').children("option:selected").val(),
        rh502_operador: jQuery('#operador ').children("option:selected").val(),
        rh502_multiplicador: jQuery('#multiplicacao').val()
    }
    const dado = new FormData;
    for (index in data) {
        dado.append(index, data[index]);
    }
    HttpClient.post(routers.gravarassentform, {
        body: dado
    })
        .then((res) => {
            if (res.hasOwnProperty('data')) {
                cancelarform();
                buscaconfiguracaoAba3()
            } else {
                alert('Erro ao Atualizar');
            }
        });
}
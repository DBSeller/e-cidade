var $table = jQuery('#table')
const columns = [{
    align: 'center',
    title: 'Sequencial',
    field: 'rh501_sequencial',
    sortable: true
},
{
    field: 'rh501_ordem',
    title: 'Ordem',
    sortable: true,
    align: 'center'
},
{
    field: 'rh501_valor',
    title: 'Periodo',
    sortable: true,
    align: 'center'
},
{
    field: 'rh501_unidade',
    align: 'center',
    title: 'Unidade de tempo',
    sortable: true,
    formatter: FormatterUtempo1
},
{
    field: 'rh501_perc',
    align: 'center',
    title: 'Percentual %',
    sortable: true
},
{
    title: 'Ação',
    align: 'center',
    clickToSelect: false,
    formatter: FormatterPrincipalpg2
}
];

function FormatterUtempo1(value) {
    if (value == 'year') {
        value = 'Ano';
    } else if (value == 'month') {
        value = 'Mês';
    } else if (value == 'day') {
        value = 'Dia';
    }
    return [
        '<p>' + value + '</p>'
    ].join('')
}

function FormatterPrincipalpg2(value, row, index) {
    return [
        '<a id="editar" class="editar" onclick="atividadepg2(this);" title="editar">',
        '<i class="far fa-edit mr-5"></i>',
        '</a>  ',
        '<a class="remove" onclick="excluirpg2(this);" title="remove">',
        '<i class="fas fa-trash-alt"></i>',
        '</a>'
    ].join('')

}

$table.bootstrapTable({
    columns: columns,
    pageSize: 5,
    pagination: true,
});
function atividadepg2(e) {
    let sequencial = e.closest("a").closest("td").closest("tr").children[0].innerText;
    let ordem = e.closest("a").closest("td").closest("tr").children[1].innerText;
    let valor = e.closest("a").closest("td").closest("tr").children[2].innerText;
    let unidade = e.closest("a").closest("td").closest("tr").children[3].innerText;
    let porcentual = e.closest("a").closest("td").closest("tr").children[4].innerText;
    rh501_sequencial = sequencial;
    if (unidade == 'Ano') {
        unidade = 'year'
    } else if (unidade == 'Mês') {
        unidade = 'month'
    } else if (unidade == 'Dia') {
        unidade = 'day'
    }
    jQuery("#ordem").val(ordem);
    jQuery("#unidadetempo").val(unidade).change();
    jQuery("#periodo").val(valor);
    jQuery("#percentual").val(porcentual);
    jQuery(".alterarintervalo").show();
    jQuery(".addintervalo").hide();
}

function cancelarintervalo() {
    jQuery(".alterarintervalo").hide();
    jQuery(".addintervalo").show();
    jQuery("#unidadetempo").val('year').change();
    jQuery("#periodo").val('');
    jQuery("#ordem").val('');
    jQuery("#percentual").val('');
    rh501_sequencial = '';
}

function excluirpg2(e) {
    id = e.closest("a").closest("td").closest("tr").children[0].innerText;
    const data = {
        rh501_sequencial: id
    };
    const dado = new FormData;
    for (index in data) {
        dado.append(index, data[index]);
    }
    HttpClient.post(routers.deleteassentperc, {
        body: dado
    })
        .then((res) => {
            if (res.hasOwnProperty('data')) {
                cancelarintervalo()
                buscaintervalosAba2()
            } else {
                alert(JSON.stringify(res));
            }
        });
}

function buscaintervalosAba2() {
    const data = {
        rh501_seqasentconf: rh500_sequencial
    };
    const dado = new FormData;
    for (index in data) {
        dado.append(index, data[index]);
    }
    HttpClient.post(routers.assentperc, {
        body: dado
    })
        .then((res) => {
            if (res.hasOwnProperty('data')) {
                $table.bootstrapTable('load', res.data)
            } else {
                alert(JSON.stringify(res));
            }
        });
}

function addIntervalopg2(acao) {

    if (jQuery("#periodo").val() == '') {
        alert('Insira o periodo');
        return
    }
    if (jQuery("#percentual").val() == '') {
        alert('Insira o percentual');
        return
    }
    if (jQuery("#ordem").val == '') {
        alert('Insira a Ordem');
        return
    }

    var value = jQuery("#unidadetempo").val()
    const data = {
        acao: acao,
        rh501_sequencial: rh501_sequencial,
        rh501_seqasentconf: rh500_sequencial,
        rh501_ordem: jQuery("#ordem").val(),
        rh501_valor: jQuery("#periodo").val(),
        rh501_perc: jQuery("#percentual").val(),
        rh501_unidade: value
    }
    const dado = new FormData;
    for (index in data) {
        dado.append(index, data[index]);
    }
    HttpClient.post(routers.gravarassentperc, {
        body: dado
    })
        .then((res) => {
            if (res.hasOwnProperty('data')) {
                cancelarintervalo();
                buscaintervalosAba2()
            } else {
                alert('Erro ao Atualizar');
            }
        });
}

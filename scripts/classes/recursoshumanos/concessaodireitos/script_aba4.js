
var $tableAba4 = jQuery('#tableAba4')
const columAba4 = [{
    title: 'Sequencial',
    align: 'center',
    field: 'rh503_sequencial',
    sortable: true
},
{
    field: 'h12_codigo',
    class: 'hidden'
},
{
    field: 'rh503_codigo',
    title: 'Assentamento ',
    sortable: true
},
{
    field: 'rh503_acao',
    title: 'Ação',
    align: 'center',
    sortable: true,
    formatter:FormatterAcaoAba4
},
{
    field: 'rh503_tipo',
    title: 'Tipo',
    align: 'center',
    sortable: true,
    formatter: FormatterTipoAba4
},
{
    field: 'rh503_formula',
    title: 'Formula',
    align: 'center',
    sortable: true,
},
{
    field: 'rh503_condicao',
    title: 'Condicao',
    align: 'center',
    sortable: true
},
{
    title: 'Ações',
    align: 'center',
    formatter: FormatterPrincipalAba4
}
];
$tableAba4.bootstrapTable({
    columns: columAba4,
    pageSize: 5,
    pagination: true
});

function FormatterTipoAba4(value, row, index) {
    if (value == '1') {
        value = 'Acumula';
    } else if (value == '2') {
        value = 'Não Acumula';
    } else if (value == '3') {
        value = 'Protela';
    }
    return [
        '<p>' + value + '</p>'
    ].join('')
}


function FormatterAcaoAba4(value, row, index) {
    if (value == '1') {
        value = 'Concede';
    } else if (value == '2') {
        value = 'Não Concede';
    } else if (value == '3') {
        value = 'Validar';
    }
    return [
        '<p>' + value + '</p>'
    ].join('')
}

function FormatterPrincipalAba4(value, row, index) {
    return [
        '<a id="editar" class="editar" onclick="atividadeAba4(this);" title="editar">',
        '<i class="far fa-edit mr-5"></i>',
        '</a>  ',
        '<a class="remove" onclick="excluirAba4(this);" title="remove">',
        '<i class="fas fa-trash-alt"></i>',
        '</a>'
    ].join('')
}

function atividadeAba4(e) {
    rh503_sequencial = e.closest("a").closest("td").closest("tr").children[0].innerText;
    let assentamento = e.closest("a").closest("td").closest("tr").children[1].innerText;
    let assentament2 = e.closest("a").closest("td").closest("tr").children[2].innerText;
    jQuery("#assentamento2Aba4").val(assentamento);
    jQuery("#descricaoassentamento2Aba4").val(assentament2);
    let acao = e.closest("a").closest("td").closest("tr").children[3].innerText;
    let tipo = e.closest("a").closest("td").closest("tr").children[4].innerText;
    let formula = e.closest("a").closest("td").closest("tr").children[5].innerText;
    let condicao = e.closest("a").closest("td").closest("tr").children[6].innerText;
    switch (acao) {
        case 'Concede':
            acao = 1;
            break;
        case 'Não Concede':
            acao = 2;
            break;
        case 'Validar':
            acao = 3;
            break;
    }
    jQuery("#acaoAba4").val(acao).change()
    switch (tipo) {
        case 'Acumula':
            tipo = 1;
            break;
        case 'Não Acumula':
            tipo = 2;
            break;
        case 'Protela':
            tipo = 3;
            break;
}
    jQuery("#tipoAba4").val(tipo).change()
    jQuery("#formulaAba4").val(formula).change()
    jQuery("#condicaoAba4").val(condicao).change()
    jQuery(".alterarformAba4").show()
    jQuery(".addformAba4").hide()
}

function cancelarformAba4() {
    jQuery("#assentamento2Aba4").val('')
    jQuery("#descricaoassentamento2Aba4").val('')
    jQuery(".alterarformAba4").hide();
    jQuery(".addformAba4").show();
    rh503_sequencial = '';
}

function excluirAba4(e) {
    id = e.closest("a").closest("td").closest("tr").children[0].innerText;
    const data = {
        rh503_sequencial: id
    };
    const dado = new FormData;
    for (index in data) {
        dado.append(index, data[index]);
    }
    HttpClient.post(routers.deleteassentconcedeconfig, {
        body: dado
    })
        .then((res) => {
            if (res.hasOwnProperty('data')) {
                buscavalidacaoAba4()
                cancelarformAba4()
            } else {
                alert(JSON.stringify(res));
            }
        });
}

function buscavalidacaoAba4() {
    const data = {
        rh503_seqassentconf: rh500_sequencial
    };
    const dado = new FormData;
    for (index in data) {
        dado.append(index, data[index]);
    }
    HttpClient.post(routers.assentconcedeconfig, {
        body: dado
    })
        .then((res) => {
            if (res.hasOwnProperty('data')) {
                $tableAba4.bootstrapTable('load', res.data)
            } else {
                alert('Erro!');
            }
        });
}


function addformAba4(acao) {
    if(jQuery("#assentamento2Aba4").val() == ''){
        alert('Insira o Assentamento')
        return
    }
    const data = {
        acao: acao,
        rh503_sequencial: rh503_sequencial,
        rh503_seqassentconf: rh500_sequencial,
        rh503_codigo: jQuery('#assentamento2Aba4').val(),
        rh503_acao: jQuery('#acaoAba4').children("option:selected").val(),
        rh503_tipo: jQuery('#tipoAba4').children("option:selected").val(),
        rh503_formula: jQuery('#formulaAba4 ').children("option:selected").val(),
        rh503_condicao: jQuery('#condicaoAba4').val()
    }
    const dado = new FormData;
    for (index in data) {
        dado.append(index, data[index]);
    }
    HttpClient.post(routers.gravarassentconcedeconfig, {
        body: dado
    })
        .then((res) => {
            if (res.hasOwnProperty('data')) {
                cancelarformAba4();
                buscavalidacaoAba4()
            } else {
                alert('Erro ao Atualizar');
            }
        });
}


function js_pesquisa_assentamentoAba4(mostra) {
    if (mostra == true) {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tipoasseAba4',
            'func_tipoasse.php?funcao_js=parent.js_mostratipoasse1Aba4|h12_codigo|h12_descr&instit='+DB_instit,
            'Pesquisa', true);
    } else {
        if (jQuery('#assentamento2Aba4').val() != '') {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tipoasseAba4', 'func_tipoasse.php?pesquisa_chave=' +
                jQuery('#assentamento2Aba4').val() +
                '&funcao_js=parent.js_mostratipoasseAba4&instit='+DB_instit, 'Pesquisa', false);
        } else {
            jQuery('#assentamento2Aba4').val('');
        }
    }
}

function js_mostratipoasseAba4(chave, erro, chave1) {
    jQuery('#descricaoassentamento2Aba4').val(chave1)
    if (erro == true) {
        jQuery('#assentamento2Aba4').focus();
        jQuery('#descricaoassentamento2Aba4').val('')
    }
}

function js_mostratipoasse1Aba4(chave1, chave2, chave3) {
    jQuery("#assentamento2Aba4").val(chave1);
    jQuery('#descricaoassentamento2Aba4').val(chave2);
    db_iframe_tipoasseAba4.hide();
}
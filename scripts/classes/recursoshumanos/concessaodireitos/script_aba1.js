//Aba 1
var $tableAba1 = jQuery("#tableAba1");

const columAba1 = [
    {
        title: "Sequencial",
        align: "center",
        field: "rh500_sequencial",
        sortable: true,
    },
    {
        title: "Assentamento",
        field: "rh500_assentamento",
        sortable: true,
    },
    {
        field: "rh500_datalimite",
        title: "Data Limite ",
        align: "center",
        sortable: true,
        formatter: FormatterData,
    },
    {
        field: "rh500_condede",
        title: "Concede",
        sortable: true,
    },
    {
        field: "rh500_naoconcede",
        title: "Não Concede",
        sortable: true,
    },
    {
        field: "rh500_selecao",
        title: "Seleção",
        sortable: true,
    },
    {
        title: "Ações",
        align: "center",
        formatter: FormatterPrincipalAba4,
    },
];

function FormatterPrincipalAba4(value, row, index) {
    return [
        '<a onclick="atividadeAba1(this);">',
        '<i class="far fa-edit"></i>',
        "</a>  "
    ].join("");
}

function FormatterData(value) {
    if (value) {
        resultado = value.split("-");
        dataoriginal = resultado[2] + "/" + resultado[1] + "/" + resultado[0];
        return ["<p>" + dataoriginal + "</p>"].join("");
    } else {
        return ["<p> ---- </p>"].join("");
    }
}
$tableAba1.bootstrapTable({
    columns: columAba1,
    pageSize: 5,
    pagination: true
});

function buscarAssentConfg() {
    HttpClient.get(routers.configuracao).then((res) => {
        if (res.hasOwnProperty("data")) {
            $tableAba1.bootstrapTable("load", res.data);
        } else {
            alert("Erro!");
        }
    });
}

buscarAssentConfg();

function js_pesquisa_assentamento(mostra) {
    if (mostra == true) {
        js_OpenJanelaIframe(
            "CurrentWindow.corpo",
            "db_iframe_tipoasse",
            "func_tipoasse.php?funcao_js=parent.js_mostratipoasse1|h12_codigo|h12_descr&instit=" +
                DB_instit,
            "Pesquisa",
            true
        );
    } else {
        if (dadosPesquisa.assentamento.value != "") {
            js_OpenJanelaIframe(
                "CurrentWindow.corpo",
                "db_iframe_tipoasse",
                "func_tipoasse.php?pesquisa_chave=" +
                    dadosPesquisa.assentamento.value +
                    "&funcao_js=parent.js_mostratipoasse&instit=" +
                    DB_instit,
                "Pesquisa",
                false
            );
        } else {
            dadosPesquisa.assentamento.value = "";
        }
    }
}

function js_mostratipoasse(chave, erro, chave1) {
    dadosPesquisa.descricaoassentamento.value = chave1;
    if (erro == true) {
        dadosPesquisa.assentamento.focus();
        dadosPesquisa.descricaoassentamento.value = "";
    } else {
    }
}

function js_mostratipoasse1(chave1, chave2, chave3) {
    dadosPesquisa.assentamento.value = chave1;
    dadosPesquisa.descricaoassentamento.value = chave2;
    db_iframe_tipoasse.hide();
}

function js_pesquisa_concessao(mostra) {
    if (mostra == true) {
        js_OpenJanelaIframe(
            "CurrentWindow.corpo",
            "db_iframe_concessao",
            "func_tipoasse.php?funcao_js=parent.js_mostraconcessao1|h12_codigo|h12_descr&instit=" +
                DB_instit,
            "Pesquisa",
            true
        );
    } else {
        if (dadosPesquisa.concessao.value != "") {
            js_OpenJanelaIframe(
                "CurrentWindow.corpo",
                "db_iframe_tipoasse",
                "func_tipoasse.php?pesquisa_chave=" +
                    dadosPesquisa.concessao.value +
                    "&funcao_js=parent.js_mostraconcessao&instit=" +
                    DB_instit,
                "Pesquisa",
                false
            );
        } else {
            dadosPesquisa.concessao.value = "";
        }
    }
}

function js_mostraconcessao(chave, erro, chave1) {
    dadosPesquisa.descricaoconcessao.value = chave1;
    if (erro == true) {
        dadosPesquisa.concessao.focus();
        dadosPesquisa.descricaoconcessao.value = "";
    }
}

function js_mostraconcessao1(chave1, chave2, chave3) {
    dadosPesquisa.concessao.value = chave1;
    dadosPesquisa.descricaoconcessao.value = chave2;
    db_iframe_concessao.hide();
}

function js_pesquisa_naoconcessao(mostra) {
    if (mostra == true) {
        js_OpenJanelaIframe(
            "CurrentWindow.corpo",
            "db_iframe_naoconcessao",
            'func_tipoasse.php?funcao_js=parent.js_mostranaoconcessao1|h12_codigo|h12_descr&instit=<?= (db_getsession("DB_instit")) ?>',
            "Pesquisa",
            true
        );
    } else {
        if (dadosPesquisa.naoconcessao.value != "") {
            js_OpenJanelaIframe(
                "CurrentWindow.corpo",
                "db_iframe_tipoasse",
                "func_tipoasse.php?pesquisa_chave=" +
                    dadosPesquisa.naoconcessao.value +
                    '&funcao_js=parent.js_mostranaoconcessao&instit=<?= (db_getsession("DB_instit")) ?>',
                "Pesquisa",
                false
            );
        } else {
            dadosPesquisa.naoconcessao.value = "";
        }
    }
}

function js_mostranaoconcessao(chave, erro, chave1) {
    dadosPesquisa.descricaonaoconcessao.value = chave1;
    if (erro == true) {
        dadosPesquisa.naoconcessao.focus();
        dadosPesquisa.descricaonaoconcessao.value = "";
    }
}

function js_mostranaoconcessao1(chave1, chave2, chave3) {
    dadosPesquisa.naoconcessao.value = chave1;
    dadosPesquisa.descricaonaoconcessao.value = chave2;
    db_iframe_naoconcessao.hide();
}

function verificaAssent() {
    if (rh500_sequencial == "") {
        alert("Erro Assentamento nao informado");
        return;
    }

    const data = {
        rh500_sequencial: rh500_sequencial,
    };
    const dado = new FormData();
    for (index in data) {
        dado.append(index, data[index]);
    }
    HttpClient.post(routers.configuracao, {
        body: dado,
    }).then((res) => {
        if (res.data) {
            oAbaIntervalos.desbloquear();
            oAbaConfiguracao.desbloquear();
            oAbaValidacao.desbloquear();
            dadosPesquisa.concessao.value = res.data.rh500_condede;
            dadosPesquisa.descricaoconcessao.value =
                res.data.rh500_condededescr;
            let resultado = res.data.rh500_datalimite.split("-");
            dadosPesquisa.datalimite.value =
                resultado[2] + "/" + resultado[1] + "/" + resultado[0];
            if (res.data.rh500_naoconcede) {
                dadosPesquisa.naoconcessao.value = res.data.rh500_naoconcede;
                dadosPesquisa.descricaonaoconcessao.value =
                    res.data.rh500_noacondededescr;
            }

            rh500_sequencial = res.data.rh500_sequencial;
            dadosPesquisa.assentamento2.value =
                dadosPesquisa.assentamento.value;
            dadosPesquisa.descricaoassentamento2.value =
                dadosPesquisa.descricaoassentamento.value;
            dadosPesquisa.assentamento3.value =
                dadosPesquisa.assentamento.value;
            dadosPesquisa.descricaoassentamento3.value =
                dadosPesquisa.descricaoassentamento.value;
            dadosPesquisa.assentamentoAba4.value =
                dadosPesquisa.assentamento.value;
            dadosPesquisa.descricaoassentamentoAba4.value =
                dadosPesquisa.descricaoassentamento.value;
            buscaintervalosAba2();
            buscaconfiguracaoAba3();
            buscavalidacaoAba4();
        } else {
            rh500_sequencial = "";
            oAbaIntervalos.bloquear();
            oAbaConfiguracao.bloquear();
            oAbaValidacao.bloquear();
            dadosPesquisa.concessao.value = "";
            dadosPesquisa.descricaoconcessao.value = "";
            dadosPesquisa.naoconcessao.value = "";
            dadosPesquisa.descricaonaoconcessao.value = "";
        }
    });
}

function atualizarAssent() {
    let data = {};
    if(dadosPesquisa.assentamento.value == ""){
        alert('Insira um Assentamento');
        return
    }
    if(dadosPesquisa.concessao.value == ""){
        alert('Insira um Assentamento de Concessão');
        return
    }
    if(dadosPesquisa.datalimite.value == ""){
        alert('Insira uma Data');
        return
    }
    data = {
        rh500_sequencial:rh500_sequencial,
        rh500_assentamento: dadosPesquisa.assentamento.value,
        rh500_datalimite: dadosPesquisa.datalimite.value,
        rh500_condede: dadosPesquisa.concessao.value,
        rh500_naoconcede: dadosPesquisa.naoconcessao.value,
        rh500_selecao : dadosPesquisa.selecao.value,
    };
    const dado = new FormData();
    for (index in data) {
        dado.append(index, data[index]);
    }
    HttpClient.post(routers.gravarconfiguracao, {
        body: dado,
    }).then((res) => {
        if (res.hasOwnProperty("data")) {
            rh500_sequencial = res.data.rh500_sequencial;
            alert("Configuração inserida com sucesso");
            buscarAssentConfg();
            verificaAssent();
            jQuery(".altearaba1").show();
            jQuery(".addaba1").hide();
        } else {
            alert("Erro ao Inserir");
        }
    });
}
function atividadeAba1(e) {
    rh500_sequencial = e.closest("a").closest("td").closest("tr")
        .children[0].innerText;
    let assentamento = e.closest("a").closest("td").closest("tr")
        .children[1].innerText;
    let datalimite= e.closest("a").closest("td").closest("tr")
        .children[2].innerText;
    let concede = e.closest("a").closest("td").closest("tr")
    .children[3].innerText;
    let naoconcede = e.closest("a").closest("td").closest("tr")
    .children[4].innerText;

    let selecao = e.closest("a").closest("td").closest("tr")
    .children[5].innerText;

    assentamento = assentamento.split("-");
    jQuery("#assentamento").val(assentamento[0]);
    jQuery("#descricaoassentamento").val(assentamento[1]);

    concede = concede.split("-");
    jQuery("#concessao").val(concede[0]);
    jQuery("#descricaoconcessao").val(concede[1]);

    naoconcede = naoconcede.split("-");
    jQuery("#naoconcessao").val(naoconcede[0]);
    jQuery("#descricaonaoconcessao").val(naoconcede[1]);

    selecao = selecao.split("-");
    jQuery("#selecao").val(selecao[0]);
    jQuery("#descricaoselecao").val(selecao[1]);


    jQuery("#datalimite").val(datalimite);

    jQuery(".altearaba1").show();
    jQuery(".addaba1").hide();
    verificaAssent()
}

function cancelarformAba1() {
    jQuery("#assentamento").val("");
    jQuery("#descricaoassentamento").val('');
    jQuery("#concessao").val("");
    jQuery("#descricaoconcessao").val('');
    jQuery("#naoconcessao").val("");
    jQuery("#descricaonaoconcessao").val('');
    jQuery(".altearaba1").hide();
    jQuery(".addaba1").show();
    rh500_sequencial = "";
    oAbaIntervalos.bloquear();
    oAbaConfiguracao.bloquear();
    oAbaValidacao.bloquear();
    dadosPesquisa.concessao.value = "";
    dadosPesquisa.descricaoconcessao.value = "";
    dadosPesquisa.naoconcessao.value = "";
    dadosPesquisa.descricaonaoconcessao.value = "";
    dadosPesquisa.selecao.value = "";
    dadosPesquisa.descricaoselecao.value = "";
}


function js_geraform_pesquisaselecao(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_mostrasel1|r44_selec|r44_descr','Pesquisa',true);
    }else{
       if(dadosPesquisa.selecao.value != ''){ 
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave='+dadosPesquisa.selecao.value+'&funcao_js=parent.js_mostrasel','Pesquisa',false);
       }else{
        dadosPesquisa.descricaoselecao.value = '';
       }
    }
  }
  function js_mostrasel(chave,erro){
    dadosPesquisa.descricaoselecao.value = chave; 
    if(erro==true){ 
      ddadosPesquisa.selecao.focus(); 
      dadosPesquisa.selecao.value = ''; 
    }
  }
  function js_mostrasel1(chave1,chave2){
    dadosPesquisa.selecao.value = chave1;
    dadosPesquisa.descricaoselecao.value   = chave2;
    db_iframe_selecao.hide();
  }

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
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
</head>
<body>
<div id="ctnAbas"></div>
<div>
    <div id="abaPeriodo">
        <?php require_once modification('forms/db_fromperiodosindicato.php'); ?>
    </div>
    <div id="abaSindicato">
        <?php require_once modification('forms/db_fromsindicatocontribuicao.php'); ?>
    </div>
</div>
<?php db_menu(); ?>
<script rel="script" type="text/javascript">
    const dbAbas = new DBAbas(document.querySelector('#ctnAbas'));
    const abaPeriodo = dbAbas.adicionarAba('Período', document.querySelector('#abaPeriodo'));
    const abaSindicato = dbAbas.adicionarAba('Sindicatos', document.querySelector('#abaSindicato'));

    const INTEGRACAO = 2;

    // campos aba período
    const formPeriodos = document.getElementById('formPeriodo');
    const trEmpregador = document.getElementById('tr_empregador');
    const selectEmpregador = document.getElementById('empregador');

    // campos aba sindicatos
    const formContribuicao = document.getElementById('formContribuicao');
    const inputValor = new DBInputValor(document.getElementById('valor'));

    const collection = new Collection().setId('sequencial');
    const grid = DatagridCollection.create(collection).configure({'order': false, 'height': '200px'});
    grid.addColumn('sindicato', {
        label: 'Sindicato',
        align: 'left',
        width: '350px'
    });

    grid.addColumn('tipo_contribuicao', {
        label: 'Tipo de Contribuição',
        align: 'left',
        width: '250px'
    });

    grid.addColumn('valor', {
        label: 'Valor',
        align: 'left',
        width: '100px'
    }).transform('dinheiro');

    grid.addAction('E', 'Excluir', (event, linha) => {
        excluirContribuicao(linha);
    });
    grid.show($('gridContribuicao'));

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
        }).then(adicionarListenersAbaPeriodo)
            .then(adicionarListenersAbaSindicatos)
            .catch(mensagem => alert(mensagem));
    };

    const adicionarListenersAbaPeriodo = () => {
        $('btnSalvarPeriodo').addEventListener('click', salvarPeriodo);
        $('btnPesquisarPeriodo').addEventListener('click', function () {
            js_OpenJanelaIframe(
                '',
                'db_iframe_contribuicaosindicalperiodo',
                'func_pesquisa_contribuicao_sindical.php?funcao_js=parent.retornoLoockupPesquisaPeriodo|db_id',
                'Pesquisa Período de Contribuição Sindical Patronal',
                true
            );
        });
    };

    const adicionarListenersAbaSindicatos = () => {
        new DBLookUp($('ancoraSindicato'), $('codigoSindicato'), $('descricaoSindicato'), {
            'sArquivo': 'func_rhsindicato.php',
            'sObjetoLookUp': 'db_iframe_operadorasaude',
            'sLabel': 'Pesquisar Operadoras de Plano de Saúde'
        });

        $('btnSalvarContribuicao').addEventListener('click', salvarContribuicao);
    };

    function retornoLoockupPesquisaPeriodo(id) {

        db_iframe_contribuicaosindicalperiodo.hide();
        buscarDadosPeriodo(id);
    }

    const buscarDadosPeriodo = id => {
        const formData = new FormData();
        formData.append('acao', 'buscarPeriodoPorId');
        formData.append('codigo', id);
        HttpClient.post('eso4_contribuicaosindicalpatronal.RPC.php', {
            body: formData
        }).then(response => {
            if (response.erro) {
                throw response.mensagem;
            }

            atualizarDadosPeriodo(response.periodo);
        }).then(controlarAba(false)).catch(mensagem => alert(mensagem));
    };

    const atualizarDadosPeriodo = periodo => {

        $('sequencialPeriodo').value = periodo.sequencial;
        selectEmpregador.value = periodo.empregador.codigo;
        $('indicativoPeriodo').value = periodo.indicativoPeriodo;
        $('periodo').value = periodo.periodo;

        $('periodo_selecionado').value = periodo.periodo;

        grid.clear();
        if (periodo.contribuicoes) {
            periodo.contribuicoes.map((contribuicao) => {
                populaCollection(contribuicao)
            });
            atualizaGrid();
        }
    };

    const validaPeriodos = () => {
        try {
            const periodo = $F('periodo');
            if (empty(periodo)) {
                throw 'Informe o período.';

            }
            if ($F('indicativoPeriodo') == 1 && periodo.match(/^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])/) == null) {
                throw 'Período informado é incompatível com Indicativo de Período selecionado.';
            }

            if ($F('indicativoPeriodo') == 2 && periodo.length > 4) {
                throw 'Período informado é incompatível com Indicativo de Período selecionado.';
            }

            if ($F('indicativoPeriodo') == 2 && periodo.match(/^(19[0-9][0-9]|2[0-9][0-9][0-9])/) == null) {
                throw 'Período informado é incompatível com Indicativo de Período selecionado.';
            }
        } catch (e) {
            alert(e);
            return false;
        }

        return true;
    };

    const salvarPeriodo = () => {

        if (!validaPeriodos()) {
            return;
        }

        const formData = new FormData(formPeriodos);
        formData.append('acao', 'salvarPeriodo');
        HttpClient.post('eso4_contribuicaosindicalpatronal.RPC.php', {
            body: formData
        }).then(response => {
            if (response.erro) {
                throw response.mensagem;
            }
            alert(response.mensagem);
            atualizarDadosPeriodo(response.periodo);
        }).then(controlarAba(false)).catch(mensagem => alert(mensagem));
    };

    const validaDadosContribuicao = () => {
        try {
            if (empty($F('codigoSindicato'))) {
                throw "Informe o sindicato.";
            }

            if (empty($F('tipoContribuicao'))) {
                throw "Informe o tipo de contribuição.";
            }

            if (inputValor.getValue().valueOf() === 0) {
                throw "Informe o valor.";
            }
        } catch (e) {
            alert(e);
            return false;
        }

        return true;
    };

    const salvarContribuicao = () => {

        if (!validaDadosContribuicao()) {
            return;
        }
        const formData = new FormData(formContribuicao);
        formData.append('acao', 'salvarContribuicao');
        formData.append('sequencialPeriodo', $F('sequencialPeriodo'));
        formData.append('valor', inputValor.getValue());
        HttpClient.post('eso4_contribuicaosindicalpatronal.RPC.php', {
            body: formData
        }).then(response => {
            if (response.erro) {
                throw response.mensagem;
            }
            alert(response.mensagem);
            populaCollection(response.contribuicao);
            atualizaGrid();
            $('codigoSindicato').value = '';
            $('descricaoSindicato').value = '';
            $('tipoContribuicao').value = '';
            inputValor.setValue(0);
        }).catch(mensagem => alert(mensagem));
    };

    const populaCollection = contribuicao => {

        const dado = {
            sequencial: contribuicao.sequencial,
            sindicato: contribuicao.sindicato.razaoSocial,
            tipo_contribuicao: contribuicao.descricaoTipoContribuicao,
            valor: contribuicao.valor,
            contribuicao: contribuicao
        };

        collection.add(dado);
    };

    const atualizaGrid = () => {
        grid.reload();
    };

    const excluirContribuicao = dados => {

        var msg = `Você deseja realmente excluir a contribuição para:
    Sindicato: ${dados.sindicato}
    Tipo de contribuição: ${dados.tipo_contribuicao}
    Valor: ${dados.valor}`;

        if (!confirm(msg)) {
            return
        }

        const formData = new FormData();
        formData.append('acao', 'excluirContribuicao');
        formData.append('sequencialContribuicao', dados.sequencial);
        HttpClient.post('eso4_contribuicaosindicalpatronal.RPC.php', {
            body: formData
        }).then(response => {
            if (response.erro) {
                throw response.mensagem;
            }

            collection.remove(dados.sequencial);
            atualizaGrid();
            $('codigoSindicato').value = '';
            $('descricaoSindicato').value = '';
            $('tipoContribuicao').value = '';
            inputValor.setValue(0);

        }).catch(mensagem => alert(mensagem));
    };

    const controlarAba = bloqueia => {
        abaSindicato.lBloqueada = bloqueia;
    };

    document.addEventListener("DOMContentLoaded", () => {
        controlarAba(true);
        inicializar();
    });

</script>

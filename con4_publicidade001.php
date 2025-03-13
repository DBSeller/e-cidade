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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
include_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <style>
        .elipse {
            overflow: hidden;
            width: 100%;
        }
    </style>
</head>
<body class="body-default">
<div class="container" style="width: 500px;">
    <form id="frmPublicidade" method="post" action="">
        <fieldset>
            <legend>Configuração do arquivo de Publicidade do SIGAP Fiscal</legend>
            <input type="hidden" id="codigo" name="codigo">
            <input type="hidden" id="ano" name="ano">
            <table class="form-container">
                <tr>
                    <td><label for="tipoRelatorio">Tipo Relatório:</label></td>
                    <td>
                        <select id="tipoRelatorio" name="tipoRelatorio">
                            <option value="1">Relatório Resumido de Execução Orçamentária</option>
                            <option value="2">Relatório de Gestão Fiscal</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="periodo">Período:</label></td>
                    <td>
                        <select id="periodo" name="periodo">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>
                <tr title="Informar o nome do Jornal/Número do Diário">
                    <td><label for="descricao">Descrição:</label></td>
                    <td><input type="text" name="descricao" id="descricao" class="field-size-max"/></td>
                </tr>
                <tr>
                    <td><label for="dataPublicacao">Data da Publicação:</label></td>
                    <td>
                        <input type="text" id="dataPublicacao" name="dataPublicacao"/>
                    </td>
                </tr>
                <tr>
                    <td><label for="meioComunicacao">Meio de Comunicação:</label></td>
                    <td>
                        <select id="meioComunicacao" name="meioComunicacao">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>
                <tr title="Informação complementar ao campo Meio de Comunicação - Internet">
                    <td><label for="link">Link da Transparência:</label></td>
                    <td><input type="text" id="link" name="link" class="field-size-max"></td>
                </tr>
                <tr>
                    <td><label for="localPublicacao">Local de Publicação:</label></td>
                    <td><input type="text" id="localPublicacao" name="localPublicacao" class="field-size-max"></td>
                </tr>
            </table>
        </fieldset>
        <button type="button" id="btnSalvar">
            <i class="far fa-save"></i>
            Salvar
        </button>
    </form>
</div>
<div class="subcontainer" style="width: 1200px;">
    <fieldset>
        <legend>Publicações informadas</legend>
        <div id="ctnGrid"></div>
    </fieldset>
</div>


<script type="text/javascript">
    const formulario = document.getElementById('frmPublicidade');
    const btnSalvar = document.getElementById('btnSalvar');

    const cboTipoRelatorio = document.getElementById('tipoRelatorio'),
     cboPeriodo = document.getElementById('periodo'),
     inputDescricao = document.getElementById('descricao'),
     inputDataPublicacao = document.getElementById('dataPublicacao'),
     inputMeioComunicacao = document.getElementById('meioComunicacao'),
     inputLink = document.getElementById('link'),
     inputLocalPublicacao = document.getElementById('localPublicacao'),
     inputCodigo = document.getElementById('codigo'),
     inputAno = document.getElementById('ano');

    const inputData = new DBInputDate(inputDataPublicacao);

    const tipoRelatorioBimestral = 1;
    const tipoRelatorioQuadrimestral = 2;

    const meiosComunicacao = [];

    const periodosBimestrais = [
        {"codigo": 6, "descricao": "1º BIMESTRE"},
        {"codigo": 7, "descricao": "2º BIMESTRE"},
        {"codigo": 8, "descricao": "3º BIMESTRE"},
        {"codigo": 9, "descricao": "4º BIMESTRE"},
        {"codigo": 10, "descricao": "5º BIMESTRE"},
        {"codigo": 11, "descricao": "6º BIMESTRE"}
    ]

    const periodosQuadrimestral = [
        {"codigo": 14, "descricao": "1º QUADRIMESTRE"},
        {"codigo": 15, "descricao": "2º QUADRIMESTRE"},
        {"codigo": 16, "descricao": "3º QUADRIMESTRE"},
    ];

    cboTipoRelatorio.addEventListener('change', (e) => {
        cboPeriodo.options.length = 0;
        cboPeriodo.add(new Option('Selecione', ''));

        if (e.target.value == tipoRelatorioBimestral) {
            periodosBimestrais.forEach((periodo) => {
                cboPeriodo.add(new Option(periodo.descricao, periodo.codigo));
            });
        }
        if (e.target.value == tipoRelatorioQuadrimestral) {
            periodosQuadrimestral.forEach((periodo) => {
                cboPeriodo.add(new Option(periodo.descricao, periodo.codigo));
            });
        }
    });

    const collection = new Collection().setId('codigo');
    const gridPublicacoes = new DatagridCollection(collection).configure({
        order: false,
        height: 200
    });

    gridPublicacoes.addColumn('descricaoTipoRelatorio', {label: "Tipo", 'width': '8%'});
    gridPublicacoes.addColumn('periodo', {label: "Período", 'width': '10%'})
        .transformCallback = (valor, itemCollection) => {

        return itemCollection.periodo.descricao;
    };
    gridPublicacoes.addColumn('dataPublicacao', {label: "Data", 'width': '10%', 'align': 'center'}).transform('date');
    gridPublicacoes.addColumn('descricao', {label: "Descrição", 'width': '20%'})
        .transformCallback = (valor, itemCollection) => {
        return `<p class="elipse" title="${itemCollection.descricao}">${itemCollection.descricao}</p>`
    };
    gridPublicacoes.addColumn('meio', {label: "Meio de Comunicação", 'width': '20%'})
        .transformCallback = (valor, itemCollection) => {
        const label = itemCollection.meioComunicacao.descricao
        return `<p class="elipse " title="${label}">${label}</p>`
    }
    gridPublicacoes.addColumn('linl', {label: "Link", 'width': '10%'})
        .transformCallback = (valor, itemCollection) => {
        return `<p class="elipse" title="${itemCollection.link}">${itemCollection.link}</p>`
    };

    gridPublicacoes.addColumn('localPublicacao', {label: "Local de Publicação", 'width': '12%'})
        .transformCallback = (label, itemCollection) => {
        return `<p class="elipse" title="${label}">${label}</p>`
    };

    gridPublicacoes.addAction('A', 'Alterar', (event, linha) => {
        preencheFormParaAlteracao(linha);
    }, true, 'fa-edit');

    gridPublicacoes.addAction('E', 'Excluir', (event, linha) => {

        if (!confirm(`Deseja realmente excluir a publicação ${linha.descricao}?`)) {
            return false;
        }
        const formData = new FormData(formulario);
        formData.append('acao', 'exclusaoPublicacao');
        formData.append('codigo', linha.codigo);

        HttpClient.post('con4_gerarsigap_fiscal.RPC.php', {body: formData}).then(response => {
            alert(response.mensagem);
            if (response.erro) {
                return true;
            }

            collection.remove(linha.codigo);
            gridPublicacoes.reload();
        });
    }, true, 'fa-trash');

    gridPublicacoes.show($('ctnGrid'));

    const buscarMeiosComunicacao = () => {
        const formData = new FormData(formulario);
        formData.append('acao', 'buscarMeiosComunicacao');

        HttpClient.post('con4_gerarsigap_fiscal.RPC.php', {body: formData}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }
            meioComunicacao.options.length = 0;
            meioComunicacao.add(new Option("Selecione", ''));
            response.meios.forEach((meio) => {
                meiosComunicacao.push(meio);
                meioComunicacao.add(new Option(meio.descricao, meio.codigo));
            });
        });
    };

    const buscarPublicidadesPublicadas = () => {
        const formData = new FormData(formulario);
        formData.append('acao', 'buscarPublicidades');

        HttpClient.post('con4_gerarsigap_fiscal.RPC.php', {body: formData}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            atualizaGrid(response.publicidades);
        });
    };

    (function () {
        buscarMeiosComunicacao();
        cboTipoRelatorio.dispatchEvent(new Event('change'));
        buscarPublicidadesPublicadas();
    })();

    const validar = () => {
        try {
            if (empty(cboTipoRelatorio.value)) {
                throw 'O "Tipo do Relatório" deve ser selecionado.';
            }
            if (empty(cboPeriodo.value)) {
                throw 'O "Período" deve ser selecionado.';
            }
            if (empty(inputDescricao.value)) {
                throw 'Informar no campo "Descrição" o nome do Jornal/Número do Diário.';
            }
            if (inputData.value == null) {
                throw 'Informe a data de publicação.';
            }
            if (empty(inputMeioComunicacao.value)) {
                throw 'O "Meio de Comunicação" deve ser selecionado.';
            }
        } catch (e) {
            alert(e);
            return false;
        }

        return true;
    };

    btnSalvar.addEventListener('click', () => {

        if (!validar()) {
            return;
        }
        const formData = new FormData(formulario);
        formData.append('acao', 'salvarPublicidade');

        HttpClient.post('con4_gerarsigap_fiscal.RPC.php', {body: formData}).then(response => {
            alert(response.mensagem);
            if (response.erro) {
                return;
            }

            atualizaGrid(response.publicidades);
            limparForm();
        });
    });

    const preencheFormParaAlteracao = (linha) => {
        cboTipoRelatorio.value = linha.codigoTipoRelatorio;
        cboTipoRelatorio.dispatchEvent(new Event('change'));
        cboPeriodo.value = linha.periodo.codigo;
        inputDescricao.value = linha.descricao;
        inputData.setValue(linha.dataPublicacao);
        inputMeioComunicacao.value = linha.meioComunicacao.codigo;
        inputLink.value = linha.link;
        inputLocalPublicacao.value = linha.localPublicacao;
        inputCodigo.value = linha.codigo;
        inputAno.value = linha.ano;
    };

    const limparForm = () => {
        formulario.reset();
        inputCodigo.value = '';
        inputAno.value = '';
    };

    const atualizaGrid = (publicidades) => {
        collection.add(publicidades);
        gridPublicacoes.reload();
    };
</script>
</body>

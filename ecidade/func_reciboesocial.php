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
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputCpf.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>

</head>
<body class="body-default">
<div class="container">
    <form id="filtros">
        <fieldset>
            <legend>Eventos eSocial</legend>
            <table class="form-container">
                <tr>
                    <td><label for="cbxEmpregador">Empregador:</label></td>
                    <td>
                        <select id="cbxEmpregador">
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="cbxEvento">Evento:</label></td>
                    <td>
                        <select id="cbxEvento">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="cpf">CPF:</label></td>
                    <td>
                        <input class="field-size3" type="text" name="cpf" id="cpf">
                    </td>
                </tr>
                <tr>
                    <td><label for="dataInicio">Data do envio:</label></td>
                    <td>
                        <input id="dataInicio"/>
                        <label for="dataFinal">até</label>
                        <input id="dataFinal"/>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar">
        <input name="limpar" type="reset" id="limpar" value="Limpar">
        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_reciboesocial.hide();">
    </form>
</div>
<div class="container">
    <fieldset>
        <legend>Resultados</legend>
        <div id="grid"></div>
    </fieldset>
</div>

</body>
</html>

<script type="text/javascript">
    var urlRPC = 'eso01_preenchimentoexclusaoeventos.RPC.php';
    var recibosCollection = new Collection().setId('numero');
    var grid = DatagridCollection.create(recibosCollection).configure('order', false);
    var inputCPF = new DBInputCpf($('cpf'));
    var inputDataInicio = new DBInputDate($('dataInicio'));
    var inputDataFinal = new DBInputDate($('dataFinal'));

    grid.addColumn('evento', {label: 'Evento', 'width': '70px', 'align' : 'center'});
    grid.addColumn('data', {label: 'Data do Recibo', 'width': '130px', 'align' : 'center'});
    grid.addColumn('numero', {label: 'Número', 'width': '170px', 'align' : 'center'});
    grid.addColumn('cpf', {label: 'CPF', 'width': '100px', 'align' : 'center'});
    grid.addColumn('descricao', {label: 'Descrição', 'width': '350px'});
    grid.show($('grid'));

    document.querySelector('#limpar').addEventListener('click', () => {
        document.querySelector('#filtros').reset();
        recibosCollection.clear();
        grid.reload();
    });

    function buscarEventos() {
        fetch('arquivos/esocial/tabelas/tabela_tipo_evento_exclusao.json', {
            method: 'GET',
            credentials: 'include',
        }).then(response => response.json()).then(response => {
            response.map(function(evento) {
                $('cbxEvento').add(new Option(evento.label, evento.id));
            });
        });
    }

    buscarEventos();
    buscarEmpregador();

    function buscarEmpregador() {
        js_divCarregando('Buscando Empregadores', 'loading_message');

        var formData = new FormData();
        formData.append('acao', 'buscarEmpregador');
        return fetch(urlRPC, {
            method: 'POST',
            body: formData,
            credentials: 'include',
        }).then(response => {
            js_removeObj('loading_message');
            return response;
        }).then(response => response.json()).then(response => {
            if (response.erro) {
                return alert(response.mensagem);
            }
            response.empregadores.forEach(function(empregador) {
                $('cbxEmpregador').add(new Option(empregador.empregador, empregador.cgm));
            });
        });
    }

    $('pesquisar').onclick = function() {
        if ($F('cbxEmpregador') == '') {
            alert('Selecione um empregador.');
            return false;
        }

        if ($F('cbxEvento') == '') {
            alert('Selecione um evento.');
            return false;
        }

        js_divCarregando('Buscando Eventos', 'loading_message');

        var aFiltros = {};
        aFiltros.inscricaoEmpregador = $F('cbxEmpregador');
        aFiltros.idEvento = $F('cbxEvento');
        aFiltros.cpf = inputCPF.getValue();


        if (inputDataInicio.getValue()) {
            var dataInicio = inputDataInicio.__toLocaleDateString();
            console.log('datainicio', dataInicio);
            if (dataInicio != null) {
                aFiltros.dataInicio = dataInicio + ' 00:00:00';
            }
        }

        if (inputDataFinal.getValue()) {
            var dataFinal = inputDataFinal.__toLocaleDateString()
            console.log('dataFim', dataFinal);
            if (dataFinal != null) {
                aFiltros.dataFinal = dataFinal + ' 23:59:59';
            }
        }

        var formData = new FormData();
        formData.append('acao', 'consultarRecibos');
        formData.append('aFiltros', JSON.stringify(aFiltros));

        return fetch(urlRPC, {
            method: 'POST',
            body: formData,
            credentials: 'include',
        }).then(response => {
            js_removeObj('loading_message');
            return response;
        }).then(response => response.json()).then(response => {
            recibosCollection.clear();

            if (response.erro) {
                grid.reload();
                return alert(response.mensagem);
            }

            response.recibos.map(function(recibo) {
                recibosCollection.add({
                    evento: recibo.tipo_evento,
                    data: recibo.data,
                    numero: recibo.numero,
                    cpf: recibo.cpftrab,
                    descricao: recibo.descricao,
                });
            });

            grid.reload();
        });
    };

    grid.setEvent('onclickrow', function(recibo) {
        parent.preencher(recibo, $F('cbxEmpregador'));
        $('fechar').click();
    });
</script>

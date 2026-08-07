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
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputCpf.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
    <style>
        input:disabled:not([type=button]), select:disabled {
            background-color: #DEB887 !important;
        }

        #nome {
            text-transform: uppercase;
        }
    </style>
</head>
<body class="body-default">
<div class="container">
    <form id="filtros">
        <fieldset>
            <legend>Eventos eSocial</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <label for="buscarPor">Buscar por:</label>
                    </td>
                    <td>
                        <select name="buscarPor" id="buscarPor">
                            <option value="">Selecione...</option>
                            <option value="servidor">Cadastro do Servidor</option>
                            <option value="preenchimento">Preenchimentos Anteriores</option>
                        </select>
                    </td>
                </tr>
                <tr class="ambos">
                    <td>
                        <label for="cbxEmpregador">Empregador:</label>
                    </td>
                    <td>
                        <select id="cbxEmpregador" name="empregador"></select>
                    </td>
                </tr>
                <tr class="ambos">
                    <td>
                        <label for="cpf">CPF:</label>
                    </td>
                    <td>
                        <input class="field-size3" type="text" name="cpf" id="cpf">
                    </td>
                </tr>
                <tr class="ambos">
                    <td>
                        <label for="matricula">Matrícula:</label>
                    </td>
                    <td>
                        <input type="text" name="matricula" id="matricula" maxlength="9">
                    </td>
                </tr>
                <tr class="ambos">
                    <td>
                        <label for="cgm">CGM:</label>
                    </td>
                    <td>
                        <input type="text" name="cgm" id="cgm" maxlength="9">
                    </td>
                </tr>
                <tr class="ambos">
                    <td>
                        <label for="nome">Nome:</label>
                    </td>
                    <td>
                        <input type="text" name="nome" id="nome">
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar">
        <input name="limpar" type="reset" id="limpar" value="Limpar">
        <input name="Fechar" type="button" id="fechar" value="Fechar"
               onClick="parent.db_iframe_reintegracao.hide();">
    </form>
</div>
<div class="container">
    <fieldset id="resultados">
        <legend>Resultados da Pesquisa</legend>
        <div id="gridServidor"></div>
    </fieldset>
</div>
</body>
</html>
<script type="text/javascript">
    var urlRPC = 'eso01_preenchimentoreintegracao.RPC.php';
    var servidorCollection = null;
    var gridServidor = null;
    var inputCPF = new DBInputCpf($('cpf'));

    new DBInputInteger(document.getElementById('matricula'));
    new DBInputInteger(document.getElementById('cgm'));

    const buscarPor = document.getElementById('buscarPor');
    const limparInput = document.querySelector('#limpar');
    const filtros = document.querySelector('#filtros');

    buscarEmpregador();
    criarGridServidor();
    desabilitarCampos();

    buscarPor.addEventListener('change', desabilitarCampos);
    limparInput.addEventListener('click', limpar);

    function limpar() {
        filtros.reset();
        servidorCollection.clear();
        gridServidor.reload();
        desabilitarAmbos();
    }

    function criarGridServidor() {
        servidorCollection = new Collection().setId('matricula');

        gridServidor = DatagridCollection.create(servidorCollection).configure('order', false);
        gridServidor.addColumn('matricula', {label: 'Matrícula', 'width': '75px'});
        gridServidor.addColumn('cgm', {label: 'CGM', 'width': '75px'});
        gridServidor.addColumn('nome', {label: 'Nome', 'width': '300px'});
        gridServidor.addColumn('cpf', {label: 'CPF', 'width': '125px'});
        gridServidor.addColumn('nis', {label: 'NIS', 'width': '125px'});
        gridServidor.addColumn('preenchimento', {label: 'Preenchimento', 'width': '100px'});
        gridServidor.hideColumns([5]);
        gridServidor.show($('gridServidor'));
        gridServidor.setEvent('onclickrow', function(servidor) {
            parent.preencherSugestoes(servidor);
            $('fechar').click();
        });
        gridServidor.setEvent('onafterrenderrows', () => {
            js_removeObj('loading_grid');
        });
    }

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

    function preencherGridServidor(servidores) {
        servidores.map(servidor => {
            servidorCollection.add({
                matricula: servidor.matricula,
                cgm: servidor.cgm,
                nome: servidor.nome,
                cpf: servidor.cpf,
                nis: servidor.nis,
                preenchimento: servidor.preenchimento
            });
        });

        gridServidor.reload();
    }

    function validarFiltros() {
        if (buscarPor.value === '') {
            alert('Selecione o que deseja buscar.');
            return false;
        }

        const body = new FormData($('filtros'));
        const filtrosComunsInvalidos = !body.get('cpf') && !body.get('matricula') && !body.get('cgm') &&
            !body.get('nome');

        if (filtrosComunsInvalidos) {
            alert('É necessário informar ao menos um filtro.');
            return false;
        }

        return true;
    }

    $('pesquisar').onclick = function() {
        if (!validarFiltros()) {
            return;
        }

        js_divCarregando('Buscando resultados...', 'loading_message');

        const body = new FormData($('filtros'));
        const acao = buscarPor.value === 'servidor' ? 'consultarServidores' : 'consultarPreenchimentos';

        body.append('acao', acao);
        body.append('cpf', inputCPF.getValue());

        const init = {
            method: 'POST',
            body: body,
            credentials: 'include',
        };

        const input = new Request(urlRPC, init);

        return fetch(input).then(response => {
            js_removeObj('loading_message');
            js_divCarregando('Carregando Servidores', 'loading_grid');
            return response;
        }).then(response => response.json()).then(response => {
            if (response.erro) {
                limpar();
                return alert(response.mensagem);
            }

            servidorCollection.clear();
            preencherGridServidor(response.resultados);
        });
    };

    function habilitarAmbos() {
        $('resultados').style.display = '';
        document.querySelectorAll('.ambos select, .ambos input').forEach(input => {
            input.disabled = false;
        });
    }

    function habilitarServidor() {
        $('gridServidor').style.display = '';
        document.querySelectorAll('.servidor input').forEach(input => {
            input.disabled = false;
        });
    }

    function desabilitarCampos() {
        desabilitarAmbos();

        if (buscarPor.value) {
            habilitarAmbos();

            if (buscarPor.value === 'servidor') {
                criarGridServidor();
                habilitarServidor();
            }

        } else {
            limpar();
        }
    }

    function desabilitarAmbos() {
        document.querySelectorAll('.ambos select, .ambos input, .servidor input').
            forEach(input => {
                input.disabled = true;
            });
    }
</script>

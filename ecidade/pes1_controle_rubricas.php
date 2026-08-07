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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script type="text/javascript" src="ext/javascript/prototype.maskedinput.js"></script>
    <script type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>

    <style>
        thead {
            text-align: left;
        }

        #label-matricula {
            cursor: pointer;
        }

        input {
            text-align: center;
        }

        #input-matricula {
            text-align: left;
        }
    </style>

</head>
<body class="body-default">
<div class="container">
    <form id="form-matriculas">
        <input type="text" name="input-sequencial" id="input-sequencial" style="display: none">

        <fieldset>
            <legend>Adicionar controle de horas para o servidor</legend>
            <table class="form-container" cellpadding="3">
                <thead>
                <tr>
                    <th>
                        <label id="label-matricula">Matrícula:</label>
                    </th>
                    <th>
                        Ano/ Mês limite
                    </th>
                    <th>
                        Horas Liberadas
                    </th>
                </tr>
                </thead>
                <tr>
                    <td>
                        <input type="text" id="input-matricula" lang="rh01_regist">
                        <input type="text" id="input-nome" lang="z01_nome">
                    </td>
                    <td>
                        <div id="container-competencia"></div>
                    </td>
                    <td>
                        <input type="text" name="horas-liberadas" id="horas-liberadas" placeholder="00:00">
                    </td>
                </tr>
            </table>
        </fieldset>
        <div id="containerLancadorRubrica"></div>
        <input type="button" value="Salvar" id="button-salvar">
        <input type="button" value="Limpar" id="button-limpar">
    </form>
    <form>
        <fieldset>
            <legend>Manutenção de Controle de Horas</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <div id="container-matriculas"></div>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<?php db_menu(); ?>
</body>
</html>
<script>
    const rpc = 'pes1_controle_rubricas_matriculas.RPC.php';
    const formMatriculas = document.getElementById('form-matriculas');

    const buttonSalvar = document.getElementById('button-salvar');
    const buttonLimpar = document.getElementById('button-limpar');
    const labelMatricula = document.getElementById('label-matricula');

    const inputSequencial = document.getElementById('input-sequencial');
    const inputMatricula = document.getElementById('input-matricula');
    const inputNome = document.getElementById('input-nome');
    const inputCompetenciaLimite = document.getElementById('competenciaLimite');
    const inputHorasLiberadas = document.getElementById('horas-liberadas');

    const containerCompetencia = document.getElementById('container-competencia');
    const containerMatriculas = document.getElementById('container-matriculas');
    const collectionMatriculas = new Collection().setId('sequencial');

    const competenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);
    competenciaFolha.renderizaFormulario(containerCompetencia);

    const horas = new MaskedInput(inputHorasLiberadas, "00:00", {placeholder: "0"});

    const inputAno = document.getElementById('ano');
    const inputMes = document.getElementById('mes');

    const gridMatriculas = DatagridCollection.create(collectionMatriculas).configure({'order': false, height: 150});
    gridMatriculas.addColumn('matricula', {label: 'Matrícula', align: 'center', width: '8%'});
    gridMatriculas.addColumn('nome', {label: 'Nome', align: 'center', width: '32%'});
    gridMatriculas.addColumn('competenciaLimite', {label: 'Ano/ Mês limite', align: 'center', width: '20%'});
    gridMatriculas.addColumn('horas', {label: 'Horas liberadas', align: 'center', width: '20%'});

    gridMatriculas.addAction('Alterar', 'Alterar', (event, linha) => {
        inputSequencial.value = linha.sequencial;
        inputMatricula.value = linha.matricula;
        inputNome.value = linha.nome;
        inputAno.value = linha.ano;
        inputMes.value = linha.mes;
        inputHorasLiberadas.value = linha.horas;
        inputHorasLiberadas.focus();
    }, true, 'fa-edit');

    gridMatriculas.addAction('Excluir', 'Excluir', (event, linha) => {
        if (confirm(`Deseja excluir as configurações de horas liberadas\npara a matrícula ${linha.matricula} - ${linha.nome}?`)) {
            const data = new FormData();
            data.append('acao', 'removerControleHorasExtrasMatricula');
            data.append('matricula', linha.matricula);

            HttpClient.post(rpc, {body: data}).then(response => {
                if (response.erro) {
                    return alert(response.mensagem);
                }

                if (response.itensExcluidos === 0) {
                    return alert('Ocorreu um erro ao tentar excluir as configurações.');
                }

                collectionMatriculas.remove(linha.sequencial);
                gridMatriculas.reload();
            });
        }
    }, true, 'fa-trash');

    const lookupMatricula = new DBLookUp(labelMatricula, inputMatricula, inputNome, {
        'sArquivo': 'func_rhpessoal.php',
        'sObjetoLookUp': 'db_iframe_rhpessoal',
        'sLabel': 'Pesquisar Matrícula',
        'aParametrosAdicionais': ['somenteAtivos=true']
    });

    const buscaDadosMatricula = (matricula) => {
        const data = new FormData();
        data.append('acao', 'buscaDadosMatricula');
        data.append('matricula', matricula);

        HttpClient.post(rpc, {body: data}).then(response => {
            if (response.erro) {
                return alert(response.erro);
            }
            inputHorasLiberadas.value = response.matricula.horasLiberadas;
            inputAno.value = response.matricula.ano;
            inputMes.value = response.matricula.mes;
        })
    };

    inputMatricula.addEventListener('change', () => {
        if (inputMatricula.value === '') {
            formReset();
        }
    });

    lookupMatricula.setCallBack('onChange', function (erro, value) {
        if (!erro) {
            buscaDadosMatricula(inputMatricula.value);
        }
    });

    lookupMatricula.setCallBack('onClick', function (params) {
        buscaDadosMatricula(inputMatricula.value)
    });

    const adicionaMatriculasCollection = (matricula) => {
        collectionMatriculas.add({
            sequencial: matricula.servidor.matricula,
            matricula: matricula.servidor.matricula,
            nome: matricula.servidor.cgm.nome,
            competenciaLimite: `${matricula.ano}/${matricula.mes}`,
            ano: matricula.ano,
            mes: matricula.mes,
            horas: matricula.horasLiberadas
        })
    };

    const formReset = () => {
        formMatriculas.reset()
    };

    const buscaMatriculasConfiguradas = () => {
        const data = new FormData();
        data.append('acao', 'buscaMatriculasConfiguradas');

        HttpClient.post(rpc, {body: data}).then(response => {
            if (response.erro) {
                return alert(response.mensagem);
            }

            response.matriculas.map(matricula => adicionaMatriculasCollection(matricula));
            gridMatriculas.reload();
        })
    };

    buttonLimpar.addEventListener('click', () => formReset());

    buttonSalvar.addEventListener('click', () => {
        if (inputAno.value !== competenciaFolha.iAno || inputMes.value !== competenciaFolha.iMes) {
            return alert ('O Ano/Mês limite deve ser maior ou igual ao da competência atual.');
        }
        if (inputMatricula.value === '') {
            return alert('É necessário informar a matricula.');
        }
        if (inputAno.value === '') {
            return alert('É necessário informar o ano.');
        }
        if (inputMes.value === '') {
            return alert('É necessário informar o mes.');
        }
        if (inputHorasLiberadas.value === '') {
            return alert('É necessário informar a quantidade de horas liberadas.');
        }

        const data = new FormData();
        data.append('acao', 'salvarMatriculas');
        data.append('sequencial', inputSequencial.value);
        data.append('matricula', inputMatricula.value);
        data.append('ano', inputAno.value);
        data.append('mes', inputMes.value);
        data.append('horasLiberadas', inputHorasLiberadas.value);

        HttpClient.post(rpc, {body: data}).then(response => {
            if (response.erro) {
                return alert(response.mensagem);
            }

            adicionaMatriculasCollection(response.matricula);
            formReset();
            gridMatriculas.reload();
        });
    });

    gridMatriculas.show(containerMatriculas);

    buscaMatriculasConfiguradas();
</script>

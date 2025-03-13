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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link rel="stylesheet" type="text/css" href="estilos/grid.style.css"/>
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <title>Comissões > Processamento</title>
</head>
<body>
<div class="container" id="container-window-sessao">
    <form id="form-sessao">
        <input type="hidden" name="rh247_sequencial" id="input-codigo-sessao">
        <fieldset>
            <legend>Processamento de Sessões do Jetom</legend>
            <div id="container-sessoes" style="width: 700px"></div>
        </fieldset>
    </form>
    <input type="button" value="Processar Sessões" id="button-processar-sessoes">
</div>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script>
    const urlBase = "<?php echo ECIDADE_REQUEST_PATH;?>" + 'v4/api/recursos-humanos/pessoal/jetom';
    const buttonProcessar = document.getElementById('button-processar-sessoes');
    const containerSessoes = document.getElementById('container-sessoes');
    const collectionSessoes = new Collection().setId('sequencial');
    const gridSessoes = DatagridCollection.create(collectionSessoes).configure('order', false);
    const codigoInstituicao = "<?php echo db_getsession('DB_instit');?>";
    const codigoUsuario = "<?php echo db_getsession('DB_id_usuario');?>";

    gridSessoes.getGrid().setCheckbox(0);
    gridSessoes.addColumn('comissao', {label: 'Comissão', align: 'center', width: '45%'});
    gridSessoes.addColumn('data', {label: 'Data', align: 'center', width: '15%'});
    gridSessoes.addColumn('tipoSessao', {label: 'Tipo de Sessão', align: 'center', width: '20%'});

    const addItemSessao = (item) => {
        var dataItemSessao = item.rh247_data;
        if (dataItemSessao === null) {
            dataItemSessao = "";
        } else {
            if (item.rh247_data.includes('-')) {
                dataItemSessao = new Date(`${item.rh247_data} 12:00`).getDateBR()
            }
        }

        collectionSessoes.add({
            sequencial: item.rh247_sequencial,
            comissao: item.comissao.rh242_descricao,
            data: dataItemSessao,
            processada: item.rh247_processada ? 'Sim' : 'Não',
            tipoSessao: item.tipo.rh240_descricao
        });
    };

    const processarSessoes = () => {
        const ids = [];
        gridSessoes.getGrid().getSelection('object').map(sessao => {
            ids.push(sessao.itemCollection.sequencial);
        });

        if (ids.size() === 0) {
            return alert('É necessário selecionar ao menos uma Sessão para processar.');
        }

        var descricao = 'da Sessão selecionada?';
        if (ids.size() > 1) {
            descricao = 'das Sessões selecionadas?';
        }

        if (confirm(`Confirma o processamento ${descricao}`)) {
            const data = new FormData();
            PHPSession.appendFormData(data);
            data.append('ids', JSON.stringify(ids));

            HttpClient.post(`${urlBase}/sessao/processar`, {body: data}).then(response => {
                alert(response.message);
                if (response.error) {
                    return;
                }

                response.data.map(sessao => {
                    collectionSessoes.remove(sessao.rh247_sequencial);
                });
                gridSessoes.reload();
            });
        }
    };

    gridSessoes.show(containerSessoes);

    HttpClient.get(`${urlBase}/sessao?rh247_processada=false&instituicao=${codigoInstituicao}&usuario=${codigoUsuario}`, ).then(response => {
        if (response.error) {
            return alert(response.message);
        }

        response.data.map(sessao => addItemSessao(sessao));
        gridSessoes.reload();
        gridSessoes.getGrid().checkAllRows();
    });

    buttonProcessar.addEventListener('click', () => processarSessoes());
</script>
</body>
</html>

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

use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Repository\Justificativa as JustificativaRepository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$tipoAssentamentos = TipoAssentamentoRepository::getInstanciasPorNaturezaComJustificativaConfigurada();
$justificativaRepository = new JustificativaRepository();
$codigoDepartamento = db_getsession('DB_coddepto');

foreach ($tipoAssentamentos as $tipoAssentamento) {
    $justificativa = $justificativaRepository->getJustificativaPorTipoAssentamento($tipoAssentamento->getSequencial(), $codigoDepartamento);
    if (!empty($justificativa)) {
        $tipos[$tipoAssentamento->getSequencial()] = $justificativa->getAbreviacao() . ' - ' . $tipoAssentamento->getDescricao();
    }
}

$tiposAssentamento = array_merge(
    TipoAssentamentoRepository::getInstanciasPorNatureza(\Assentamento::NATUREZA_AUTORIZA_HORA_EXTRA),
    TipoAssentamentoRepository::getInstanciasPorNatureza(Assentamento::NATUREZA_ABONO_FALTA)
);

foreach ($tiposAssentamento as $tipoAssentamento) {
    if (empty($tipos[$tipoAssentamento->getSequencial()])) {
        $tipos[$tipoAssentamento->getSequencial()] = "{$tipoAssentamento->getCodigo()} - {$tipoAssentamento->getDescricao()}";
    }
}
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link rel="stylesheet" href="estilos.css"/>
    <link rel="stylesheet" href="estilos/grid.style.css"/>
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script language="JavaScript" type="text/javascript" rel="script" type="text/javascript"
            src="scripts/classes/http/http.js"></script>

    <style>
    </style>
</head>
<body bgcolor="#cccccc" style='margin-top: 30px;'>
<div class="container" style="min-width: 600px">
    <form class="form-container" id="formFiltros">
        <fieldset>
            <legend>Filtros</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <label for="dataInicial">Data inicial (Lote):</label>
                    </td>
                    <td>
                        <input type="text" name="dataInicial" class="filtro" id="dataInicial" value="">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="dataFinal">Data final (Lote):</label>
                    </td>
                    <td>
                        <input type="text" name="dataFinal" class="filtro" id="dataFinal" value="">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="tipoAssentamento">Tipo Assentamento:</label>
                    </td>
                    <td>
                        <select name="tipoAssentamento" id="tipoAssentamento">
                            <option value="">SELECIONE</option>
                            <?php foreach ($tipos as $value => $descricao): ?>
                                <option value="<?php echo $value ?>"><?php echo $descricao ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <button class="button button--sm button--light" type="button" id="buscar">Buscar</button>
    </form>
    <div id="containerLotes"></div>
    <div id="containerAssentamentos" style="display: none;"></div>
</div>

<?php db_menu(); ?>
</body>
<script>
    const rpc = 'rec4_pontoeletronico.RPC.php';
    const formFiltros = document.getElementById('formFiltros');
    const dataInicial = new DBInputDate(document.getElementById('dataInicial'));
    const dataFinal = new DBInputDate(document.getElementById('dataFinal'));
    const tipoAssentamento = document.getElementById('tipoAssentamento');
    const buscar = document.getElementById('buscar');
    const containerLotes = document.getElementById('containerLotes');
    const containerAssentamentos = document.getElementById('containerAssentamentos');
    const collectionLotes = new Collection().setId('codigo');
    const collectionAssentamentos = new Collection().setId('sequencial');
    var windowAuxiliar = new windowAux('windowAuxiliar', 'Assentamentos', 750, 360);

    const gridAssentamentos = DatagridCollection.create(collectionAssentamentos).configure({
        'order': false,
        'height': 250
    });
    gridAssentamentos.addColumn('sequencial', {
        'label': 'Sequencial',
        'align': 'center',
        'width': '100px'
    });
    gridAssentamentos.addColumn('matricula', {
        'label': 'Matrícula',
        'align': 'center',
        'width': '100px'
    });
    gridAssentamentos.addColumn('dataConcessao', {
        'label': 'Data inicial',
        'align': 'center',
        'width': '100px'
    });
    gridAssentamentos.addColumn('dataTermino', {
        'label': 'Data final',
        'align': 'center',
        'width': '100px'
    });
    gridAssentamentos.addColumn('servidor', {
        'label': 'Servidor',
        'align': 'left',
        'width': '300px'
    });

    const gridLotes = DatagridCollection.create(collectionLotes).configure({
        'order': false,
        'height': 250
    });
    gridLotes.addColumn('lote', {
        'label': 'Lote',
        'align': 'center',
        'width': '100px'
    });
    gridLotes.addColumn('tipo', {
        'label': 'Tipo',
        'align': 'center',
        'width': '200px'
    });
    gridLotes.addColumn('data', {
        'label': 'Data do Lote',
        'align': 'center',
        'width': '100px'
    }).transform((value) => {
        return new Date(value + ' 12:00:00').getDateBR();
    });

    gridLotes.addAction('Visualizar', 'Visualizar', function (event, item) {
        mostraWindowAux(item.assentamentos);
    }, true, 'fa-search');

    gridLotes.addAction('Excluir', 'Excluir', function (event, item) {
        if (confirm(`Realmente deseja excluir o lote ${item.codigo} - (${item.tipo})?\nTodos os assentamentos dentro desse lote serão excluídos.`)) {
            const data = new FormData();
            data.append('json', JSON.stringify({
                'exec': 'excluiLoteLancamento',
                'codigo': item.codigo,
                'dataInicial': dataInicial.value,
                'dataFinal': dataFinal.value,
                'tipoAssentamento': tipoAssentamento.value
            }));

            HttpClient.post(rpc, {'body': data}).then(response => {
                if (response.erro) {
                    alert(response.mensagem);
                    return;
                }

                if (!response.excluiuTodosAssentamentos) {
                    if (confirm(response.mensagem)) {
                        const download = new DBDownload();
                        download.fDownload(response.arquivo);
                        return;
                    }
                }
                gridLotes.collection.remove(item.codigo);
                gridLotes.reload();
            });
        }
    }, true, 'fa-trash');

    const mostraWindowAux = (data) => {
        gridAssentamentos.collection.clear();
        gridAssentamentos.clear();

        data.map((assentamento) => {
            gridAssentamentos.collection.add({
                'sequencial': assentamento.codigo,
                'matricula': assentamento.matricula,
                'dataConcessao': assentamento.dataConcessao,
                'dataTermino': assentamento.dataTermino,
                'servidor': assentamento.nome_servidor
            });
        });
        gridAssentamentos.reload();
        windowAuxiliar.setContent(containerAssentamentos);
        containerAssentamentos.style.display = 'block';
        windowAuxiliar.show();
    };

    buscar.addEventListener('click', () => {
            const data = new FormData();
            data.append('json', JSON.stringify({
                'exec': 'buscaLoteLancamento',
                'dataInicial': dataInicial.value,
                'dataFinal': dataFinal.value,
                'tipoAssentamento': tipoAssentamento.value
            }));
            HttpClient.post(rpc, {'body': data}).then(response => {
                if (response.erro) {
                    alert(response.mensagem);
                    return false;
                }

                gridLotes.collection.clear();
                gridLotes.clear();

                if (response.lotes.length === 0) {
                    alert('Nenhum lote de assentamentos foi encontrado com os parâmetros informados.');
                    return;
                }

                response.lotes.map((lote) => {
                    collectionLotes.add({
                        'codigo': lote.codigo,
                        'lote': lote.codigo,
                        'tipo': lote.tipo.descricao,
                        'data': lote.data,
                        'assentamentos': lote.assentamentos
                    });
                });

                gridLotes.reload();
            });
    });

    gridAssentamentos.show(containerAssentamentos);
    gridLotes.show(containerLotes);
</script>











































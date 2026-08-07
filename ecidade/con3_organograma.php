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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/db_app.utils.php');
require_once modification('dbforms/db_funcoes.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
   <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
   <meta charset="iso-8859-1">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link type="text/css" href="assets/jquery-treegrid/css/jquery.treegrid.css" rel="stylesheet">
   <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
   <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
   <link type="text/css" href="estilos.css" rel="stylesheet">
   <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
   <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body>
    <div class="alert alert-info" role="alert">
        <ul>
            <li>
                Selecione um Departamento e Clique em <kbd><i class="fas fa-filter"></i>Filtrar</kbd> 
                para ver seus departamentos vinculados!
            </li>
        </ul>
    </div>
    <div class="container">
        <div class="subcontainer">
            <fieldset>
                <legend>Filtrar Departamento</legend>
                <table>
                    <tr>
                        <td>
                            <a id="departamento" class="bold">Departamento:</a>
                            <input id="coddepto" size="10">
                            <input id="descrdepto" size="50" class="readonly" readonly>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <button id="btnFiltrar">
                <i class="fas fa-filter"></i>
                Filtrar
            </button>
        </div>
        <div class="subcontainer" style="width: 900px;">
            <fieldset>
                <legend>Organograma</legend>
                <table
                    id="data-table-organograma"
                    class="table table-sm"
                    style="width: 99%;">
                </table>
            </fieldset>
        </div>
    </div>
<?php
   db_menu();
?>
</body>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/jquery-treegrid/js/jquery.treegrid.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/extensions/treegrid/bootstrap-table-treegrid.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script> 
<script>
const rota = 'configuracao/organograma';

const aDepartamento = document.getElementById('departamento');
const btnFiltrar = document.getElementById('btnFiltrar');
const inputId = document.getElementById('coddepto');
const inputDescricao = document.getElementById('descrdepto');
const table = jQuery('#data-table-organograma');

const lookUp = new DBLookUp(aDepartamento, inputId, inputDescricao, {
    'sArquivo': 'func_db_departalt.php',
    'sObjetoLookUp': 'db_iframe_db_depart',
    'sLabel': 'Pesquisar Departamento'
});


jQuery(document).ready(jQuery => {
    table.bootstrapTable({
        height: 400,
        idField: 'departamento',
        showColum: true,
        columns: [
            {
                field: 'descricao',
                title: 'Departamento',
                align: 'left',
                width: 800,
                formatter: (a, data) => {
                    return `${data.departamento} - ${data.descricao}`;
                }
            },
            {
                field: 'associado',
                title: 'Associado',
                halign: 'center',
                align: 'left',
                formatter: value => {
                    if (value == true) {
                        return 'SIM';
                    }
                    return 'NÃO';
                }
            }

        ],
        treeShowField: 'descricao',
        parentIdField: 'departamentopai',
        onPostBody: () => {
            let columns = table.bootstrapTable('getOptions').columns

            if (columns && columns[0][1].visible) {
                table.treegrid({
                    treeColumn: 0,
                    onChange: () => {
                        table.bootstrapTable('resetView')
                    }
                });
            }
        }
    });


    PHPSession.loadData().then(() => {
        let instit = PHPSession.data.find(val => val.name == 'DB_instit');
        HttpClient.get(`${PHPSession.requestApi}/${rota}/${instit.value}`).then(response => {
            if (response.error) {
                return alert(response.message);
            }
            let organograma = montaOrganograma(response.data);
            table.bootstrapTable('load', organograma);
        });
    });
    
    const montaOrganograma = departamento => {
        let organograma = [
            {
                'departamento': departamento.departamento,
                'descricao': departamento.descricao,
                'departamentopai': departamento.departamentopai,
                'associado': departamento.associado
            }
        ]
        departamento.filhos.forEach(filho => {
            organograma = organograma.concat(montaOrganograma(filho));
        });
        return organograma;
    }

    btnFiltrar.addEventListener('click', () => {
        const formData = new FormData();
        
        if (!inputId.value) {
            return alert('Necessário informar um departamento para filtrar');
        }

        formData.append('id', inputId.value);
        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${rota}/filtrar`, {body: formData}).then(response => {
            if (response.error) {
                return alert(response.message);
            }
            table.bootstrapTable('removeAll');
            let organograma = montaOrganograma(response.data);
            table.bootstrapTable('load', organograma);
        });
    });
});
</script>
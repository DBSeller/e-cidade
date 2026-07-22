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
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_selecao_classe.php"));
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

            #input-matricula {
                text-align: left;
            }
        </style>

    </head>
    <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
        <div class="container">
            <form name="form1" method="post" action="">
                <input type="text" name="input-sequencial" id="input-sequencial" style="display: none">
                <fieldset class="container" style="width:400px;">
                    <legend>Funções</legend>
                    <table class="form-container">
                        <tr>
                            <td nowrap title="Descrição">
                                Descrição:
                            </td>
                            <td>
                                <?php db_input('descricao', 50, '', true, 'text', 1); ?>
                            </td>
                        </tr>
                        <tr>
                            <td nowrap title="Descrição">
                                Tipo:
                            </td>
                            <td>
                                <select name="Tipo" id="incluir-alterar">
                                    <option value="incluir">Inclusão</option>
                                    <option value="alterar">Alteração</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <br/>
                <input name="incluir" type="button" id="incluir" value="Incluir">
            </form>
            <form>
                <fieldset>
                    <legend>Manutenção de Funções</legend>
                    <table class="form-container">
                        <tr>
                            <td>
                                <div id="container-funcoes"></div>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </div>
        <?php
        db_menu(
            db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
        );
        ?>
    </body>
    <script>
        const inputDescricao = document.getElementById("descricao");
        const inputSequencial = document.getElementById('input-sequencial');
        const inputOpcao = document.getElementById('incluir-alterar');


        const btnIncluir = document.getElementById('incluir');
        const url =  "<?php echo ECIDADE_REQUEST_PATH;?>";
        const codigoInstituicao = "<?php echo db_getsession('DB_instit');?>";
        const containerFuncoes = document.getElementById('container-funcoes');
        const collectionFuncoes = new Collection().setId('sequencial');
        const gridFuncoes = DatagridCollection.create(collectionFuncoes).configure({'order': false, height: 150});
        gridFuncoes.addColumn('sequencial', {label: 'Código', align: 'center', width: '15%'});
        gridFuncoes.addColumn('descricao', {label: 'Descrição', align: 'center', width: '55%'});

        gridFuncoes.addAction('Alterar', 'Alterar', (event, linha) => {
            inputSequencial.value = linha.sequencial;
            inputDescricao.value = linha.descricao;
            inputOpcao.value = 'alterar';
            btnIncluir.value = 'Alterar';
            inputDescricao.focus();
        }, true, 'fa-edit');

        inputOpcao.addEventListener('change', () => {
            if (inputOpcao.value == 'alterar') {
                btnIncluir.value = "Alterar";
            } else {
                btnIncluir.value = "Incluir";
            }
        });

        btnIncluir.addEventListener('click', () => {

            if (!validate()) {
                js_removeObj("msgBox");
                return false;
            }

            const data = new FormData();
            data.append('instituicao', codigoInstituicao);
            data.append('descricao', descricao.value);

            if (inputOpcao.value == 'incluir') {
                HttpClient.post(url + 'v4/api/recursos-humanos/pessoal/jetom/funcao', {body: data}).then(response => {
                    js_removeObj("msgBox");
                    alert(response.message);
                    if (!response.error) {
                        buscaFuncoes();
                    } else {
                        return false;
                    }
                });
            } else {
                data.append('id', inputSequencial.value);
                HttpClient.post(
                    url + 'v4/api/recursos-humanos/pessoal/jetom/funcao/alterar',
                    {
                        body: data
                    }
                ).then(response => {
                    js_removeObj("msgBox");
                    alert(response.message);
                    if (!response.error) {
                        buscaFuncoes();
                    }
                });
            }
        });

        function validate()
        {
            if (descricao.value === '') {
                alert("Descrição inválida.");
                return false;
            }

            if (inputOpcao.value == 'alterar') {
                if (inputSequencial.value == '') {
                    alert("Código inválido.");
                    return false;
                }
            }
            return true;
        }

        const adicionaFuncaoCollection = (funcao) => {
            collectionFuncoes.add({
                sequencial: funcao.codigo,
                descricao: funcao.descricao
            })
        };

        const buscaFuncoes = () => {
            gridFuncoes.clear();
            HttpClient.get(url + 'v4/api/recursos-humanos/pessoal/jetom/funcao/?instituicao='+ codigoInstituicao)
                .then(response => {
                    response.data.map(funcao => adicionaFuncaoCollection(funcao));
                    gridFuncoes.reload();
                });
        };

        gridFuncoes.addAction('Excluir', 'Excluir', (event, linha) => {
            if (confirm(`Deseja excluir a função: ${linha.descricao}?`)) {
                const data = new FormData();
                data.append('id', linha.sequencial);

                HttpClient.post(
                    url + 'v4/api/recursos-humanos/pessoal/jetom/funcao/deletar',
                    {
                        body: data
                    }
                ).then(response => {
                    alert(response.message);
                    if (response.error) {
                        return false;
                    } else {
                        collectionFuncoes.remove(linha.sequencial);
                    }

                    gridFuncoes.reload();
                });
            }
        }, true, 'fa-trash');
        gridFuncoes.show(containerFuncoes);
        buscaFuncoes();
    </script>
</html>

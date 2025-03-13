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
    <link type="text/css" href="estilos.css" rel="stylesheet">
    <link type="text/css" href="grid.style.css" rel="styleshet">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
</head>
<body>
    <div class="container">
        <form id="form" method="post" action="">
            <fieldset>
                <legend>Configurar Departamento Principal da Instituição</legend>
                <table style="text-align: left;">
                    <tr>
                        <td>
                            <label class="bold">Descrição Abreviada:</label> &nbsp;
                        </td>
                        <td>
                            <input id="descricaoabrev" type="text">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="#" id="ancoraDepartamento" class="bold">Departamento:</a> &nbsp;
                        </td>
                        <td>
                            <input id="coddepto" type="text" style="width: 20%;">
                            <input id="descrdepto" class="readonly" type="text" style="width: 75%;" readonly>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <button id="btnSalvar">
                Salvar
            </button>
        </form>
    </div>
<?php
    db_menu();
?>
</body>
</html>
<script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script>
    const rota = {
        get: 'configuracao/instituicao/',
        post: 'configuracao/instituicao/departamento-principal'
    };
    const form = document.getElementById('form');
    const ancora = {
        departamento: document.getElementById('ancoraDepartamento')
    };
    const input = {
        descricaoAbreviada: document.getElementById('descricaoabrev'),
        idDepartamento: document.getElementById('coddepto'),
        descricaoDepartamento: document.getElementById('descrdepto')
    };
    const btnSalvar = document.getElementById('btnSalvar');

    new DBLookUp(ancora.departamento, input.idDepartamento, input.descricaoDepartamento, {
        "sArquivo": "func_db_depart.php",
        "sObjetoLookUp": "db_iframe_Departamentos",
        "sLabel": "Pesquisa Departamentos",
    });

    btnSalvar.addEventListener('click', (e) => {
        e.preventDefault();
        const formData = new FormData();
        if (input.descricaoAbreviada == '') {
            return alert('O campo Descrição Abreviada não pode estar vazio.');
        }
        if (input.idDepartamento.value == '') {
            return alert('O campo Departamento não pode estar vazio.');
        }
        formData.append('descricaoAbreviada', input.descricaoAbreviada.value);
        formData.append('departamento', input.idDepartamento.value);

        PHPSession.appendFormData(formData);
        HttpClient.post(`${PHPSession.requestApi}/${rota.post}`, {body: formData}).then(response => {
            alert(response.message);
            if (response.error) {
                return;
            }
            form.reset();
        });
    });

    window.addEventListener('load', () => {
        PHPSession.loadData().then(() => {
            let instituicao = PHPSession.data.find(val => val.name == 'DB_instit');
            HttpClient.get(`${PHPSession.requestApi}/${rota.get}${instituicao.value}`).then(response => {
                input.descricaoAbreviada.value = response.data.db21_descr_depart_abrev;
                input.idDepartamento.value = response.data.db21_departamento;
                input.idDepartamento.dispatchEvent(new Event('change'));
            });
        });
    });
</script>

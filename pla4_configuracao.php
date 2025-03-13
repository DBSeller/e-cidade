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
require_once modification('libs/db_conecta.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body>
<div class="container">
    <fieldset>
        <legend>Configurações e parametrização do módulo</legend>
        <div style="display: flex; align-items: center; ">
            <input type="checkbox" id="apenasValorAnalitico" name="apenas_valor_analitico">
            <label class="bold" for="apenasValorAnalitico">Informar valores apenas de forma analítica</label>
        </div>
    </fieldset>
    <button id="salvar" type="button">
        <i class="fas fa-save"></i>
        Salvar
    </button>
    </fieldset>
</div>
</body>
<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>
<script>
    const btnSalvar = document.getElementById('salvar');
    const inputApenasValorAnalitico = document.getElementById('apenasValorAnalitico');

    const routs = {
        index: 'financeiro/planejamento/configuracao',
        salvar: 'financeiro/planejamento/configuracao/salvar'
    };

    PHPSession.loadData().then(() => {
        HttpClient.get(`${PHPSession.requestApi}/${routs.index}`).then(response => {
            inputApenasValorAnalitico.checked = response.data.apenas_valor_analitico;
        });
    });
    btnSalvar.addEventListener('click', function () {

        const formData = new FormData();
        formData.append('apenas_valor_analitico', inputApenasValorAnalitico.checked);

        HttpClient.post(`${PHPSession.requestApi}/${routs.salvar}`, {body: formData}).then(response => {
            alert(response.message);
            if (response.error) {
                return;
            }

            inputApenasValorAnalitico.checked = response.data.apenas_valor_analitico;
        });
    });
</script>

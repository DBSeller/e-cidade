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
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
</head>
<body>
<div class="alert alert-primary text-left" role="alert">
    Calcula os valores dos programas e iniciativas conforme o detalhamento da despesa.
</div>
<div class="container">
    <form id="frmDetalhamento" class="container">
        <fieldset>
            <legend>Calcula os valores sintéticos</legend>
            <table class="form-container">
                <tr class="text-left">
                    <td><label class="bold" for="planejamento">Planejamento:</label></td>
                    <td colspan="3">
                        <select id="planejamento" class="field-size8">
                            <option value="">Selecione um plano</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label class="bold" for="ano_inicial">Ano inicial:</label></td>
                    <td>
                        <input type="text" name="ano_inicial" id="ano_inicial" class="field-size2 readonly" readonly>
                    </td>
                    <td><label class="bold" for="ano_final">Ano final:</label></td>
                    <td>
                        <input type="text" name="ano_final" id="ano_final" class="field-size2 readonly" readonly>
                    </td>
                </tr>
            </table>
        </fieldset>
        <button id="processar" type="button" disabled>
            <i class="fas fa-cog"></i>
            Processar
        </button>
    </form>
</div>

<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>
<script rel="script" type="text/javascript" src="scripts/classes/planejamento/planejamento.js"></script>
<script rel="script" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
<script type="text/javascript">

    const rota = 'financeiro/planejamento/recalcular-valores-sinteticos';

    const planejamento = new Planejamento(document.getElementById('planejamento'));
    const inputAnoInicial = document.getElementById('ano_inicial');
    const inputAnoFinal = document.getElementById('ano_final');
    const btnProcesar = document.getElementById('processar');

    planejamento.load();
    planejamento.getElement().addEventListener('change', () => {
        if (planejamento.getValue() == '') {
            inputAnoInicial.value = '';
            inputAnoFinal.value = '';
            btnProcesar.setAttribute('disabled', 'disabled');
            return
        }
        inputAnoInicial.value = planejamento.getPlano().pl2_ano_inicial;
        inputAnoFinal.value = planejamento.getPlano().pl2_ano_final;
        btnProcesar.removeAttribute('disabled');
    });

    btnProcesar.addEventListener('click', () => {
        const formData = new FormData();
        formData.append('planejamento_id', planejamento.getValue());

        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${rota}`, {body: formData}).then(response => {
            alert(response.message);
            if (response.error) {
                return;
            }

        });
    });
</script>

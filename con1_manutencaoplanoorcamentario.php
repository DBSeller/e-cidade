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
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <title>DBSeller Informática Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <style>
        #fade {
            position: absolute;
            top: 0;
            display: none;
            height: 100vh;
            align-items: center;
            justify-content: center;
            width: 100%;
            z-index: 1;
            background: rgba(0, 0, 0, 0.7);
        }

        #ctnModal {
            width: 90%;
            top: 0;
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 2px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }

        #close {
            position: absolute;
            float: right;
            right: 5px;
            cursor: pointer;
        }

        .field-size3 {
            width: 130px;
        }
    </style>
</head>
<body class="body-default">

<div id='ctnAbas'></div>

<div id="abaConta">

    <div class="alert alert-primary text-left" role="alert">
        bla bla bla...<br>
    </div>

    <div class="container">

        <fieldset>
            <legend>Atualização do Plano Orçamentário</legend>
            <table class="form-container">

                <tr>
                    <td><label id="ancoraPlanoUniao"><a href="#">Plano da União: &nbsp;</a></label></td>
                    <td>
                        <input type="text" disabled id="planoUniao" name="planoUniao" class="field-size3 readonly">
                        <input type="text" disabled id="nomePlanoUniao" name="nomePlanoUniao"
                               class="field-size8 readonly">
                    </td>
                </tr>
                <tr>
                    <td><label id="ancoraPlanoEstadual"><a href="#">Plano Estadual: &nbsp;</a></label></td>
                    <td>
                        <input type="text" disabled id="planoEstadual" name="planoEstadual"
                               class="field-size3 readonly">
                        <input type="text" disabled id="nomePlanoEstadual" name="nomePlanoEstadual"
                               class="field-size8 readonly">
                    </td>
                </tr>

                <tr>
                    <td><label for="estruturalEcidade">Estrutural:</label></td>
                    <td>
                        <input type="text" id="planoEstadual" name="planoEstadual" class="field-size3" maxlength="15">
                    </td>
                </tr>

                <tr>
                    <td><label for="nomeConta">Título:</label></td>
                    <td>
                        <input type="text" id="nomeConta" name="nomeConta" class="field-size-max">
                    </td>
                </tr>


                <tr>
                    <td><label for="naturezaSaldo">Natureza de Saldo:</label></td>
                    <td>
                        <select id="naturezaSaldo" name="naturezaSaldo" class="field-size-max">
                            <option value="1">Saldo Devedor</option>
                            <option value="2">Saldo Credor</option>
                            <option value="3">Ambos</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><label for="identificadorResultadoPrimario">Identificador Resultado Primário: &nbsp;</label>
                    </td>
                    <td>
                        <select name="identificadorResultadoPrimario" id="identificadorResultadoPrimario">
                            <option value="1">Financeiro</option>
                            <option value="2">Primário</option>
                            <option value="3">Primária Obrigatória</option>
                            <option value="4">Primária Discricionária</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <fieldset class="separator">
                            <legend><label for="finalidade">Finalidade</label></legend>
                            <textarea id="finalidade" name="finalidade" rows="3" cols="65"></textarea>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
<div id="abaReduzidos">
</div>

<div id="abaGrupos">
</div>


<div id="fade">
    <div id="ctnModal">
        <div class="alert text-left" role="alert">
            <i id="close" class="fas fa-window-close"></i>
            Selecione a conta...
        </div>
        <fieldset id="ctnPesquisaPcasp">
            <legend>Plano de Contas - <span id="labelTipoPlano"></span></legend>
            <div>
                <table id="tablePlanoConta"
                       class="table table-sm"
                       data-height="400"
                       data-virtual-scroll="true"

                       style="width: 100%;">
                </table>
            </div>
        </fieldset>
    </div>
</div>
</div>

<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript">

    $.noConflict();

    let labelTipoPlano = document.getElementById('labelTipoPlano');
    const inputPlanoUniao = document.getElementById('planoUniao');
    const inputNomePlanoUniao = document.getElementById('nomePlanoUniao');
    const inputPlanoEstadual = document.getElementById('planoEstadual');
    const inputNomePlanoEstadual = document.getElementById('nomePlanoEstadual');

    const fade = document.getElementById('fade');
    const close = document.getElementById('close');
    close.onclick = () => {
        tablePlano.bootstrapTable('load', [])
        fade.style.display = "none"
    }

    const routs = {
        planoUniao: 'financeiro/contabilidade/plano-contas/consulta/orcamentario/receita/padrao'
    };

    let planoUniao, planoEstado;

    console.log('5')


    const setDadosPlanoUniao = (dados) => {
        inputPlanoUniao.value = dados.mascara;
        inputNomePlanoUniao.value = dados.nome;
        planoUniao = dados;
    };

    const setDadosPlanoEstado = (dados) => {
        inputPlanoEstadual.value = dados.mascara;
        inputNomePlanoEstadual.value = dados.nome;
        planoEstado = dados;
    };

    const tablePlano = jQuery('#tablePlanoConta');
    tablePlano.bootstrapTable({
        locale: 'pt-BR',
        uniqueId: "conta",
        cache: false,
        height: 500,
        search: true,
        class: "table table-sm",
        onClickRow: (row, $element, field) => {
            if (row.uniao) {
                setDadosPlanoUniao(row);
            } else {
                setDadosPlanoEstado(row);
            }
            close.dispatchEvent(new Event('click'))
        },
        columns: [
            {
                "title": "Conta",
                "field": 'conta',
                "align": 'center',
                "valign": 'middle',
                "width": "150"
            },
            {
                "title": "Nome",
                "field": 'nome',
                "align": 'left',
                "valign": 'middle'
            }
        ]
    });

    document.getElementById('ancoraPlanoUniao').addEventListener('click', () => {
        labelTipoPlano.innerHTML = 'Plano da união';
        buscarPlanoGoverno('uniao');
    });

    document.getElementById('ancoraPlanoEstadual').addEventListener('click', () => {
        labelTipoPlano.innerHTML = 'Plano Estadual';
        buscarPlanoGoverno('UF', planoUniao.conta);
    });


    const buscarPlanoGoverno = (tipoPlano, conta) => {

        fade.style.display = "flex";

        const formData = new FormData();
        formData.append('tipoPlano', tipoPlano);
        formData.append('exercicio', PHPSession.getValueSession('DB_anousu'));
        formData.append('apenasAnaliticas', 1);
        if (conta !== undefined) {
            formData.append('conta', conta);
        }

        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${routs.planoUniao}`, {body: formData}).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }
            tablePlano.bootstrapTable('load', response.data);
        });
    };

    console.log('10')
</script>
<?php db_menu(); ?>
</body>
</html>

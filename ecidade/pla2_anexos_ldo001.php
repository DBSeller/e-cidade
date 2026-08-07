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

$relatorio = new relatorioContabil($_GET['codigo']);
$descricao = $relatorio->getDescricao();
$periodos = $relatorio->getPeriodos();

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
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
</head>
<body>

<div class="container">
    <form id="frmDetalhamento" class="container">
        <fieldset>
            <legend><?=$descricao?></legend>
            <table class="form-container">
                <tr class="text-left">
                    <td class="field-size3"><label class="bold" for="planejamento">Planejamento:</label></td>
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
                <tr >
                    <td id="ctnInstituicao" colspan="4" style="font-weight: normal" >
                        <input type="hidden" name="db_selinstit" id="db_selinstit" value="">
                    </td>
                </tr>
                <tr>
                    <td><label class="bold" for="o116_periodo">Período:</label></td>
                    <td colspan="3">
                        <select id="o116_periodo" name="o116_periodo">
                            <option selected value="">Selecione</option>
                            <?php foreach ($periodos as $periodo): ?>
                                <option value="<?=$periodo->o114_sequencial?>"><?=$periodo->o114_descricao?></option>
                            <?php endforeach;?>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <button id="emitir" type="button">
            <i class="fas fa-print"></i>
            Emitir
        </button>
    </form>
</div>

<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>
<script rel="script" type="text/javascript" src="scripts/classes/planejamento/planejamento.js"></script>
<script rel="script" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
<script type="text/javascript">

    const get = js_urlToObject();

    const rota = get.rota;
    const planejamento = new Planejamento(document.getElementById('planejamento'));
    const inputAnoInicial = document.getElementById('ano_inicial');
    const inputAnoFinal = document.getElementById('ano_final');
    const periodo = document.getElementById('o116_periodo');
    const btnEmitir = document.getElementById('emitir');

    var viewInstituicao = new DBViewInstituicao('viewInstituicao', $('ctnInstituicao'));
    viewInstituicao.show();

    PHPSession.loadData().then(() => {
        planejamento.load();
    });

    planejamento.getElement().addEventListener('change', () => {
        if (planejamento.getValue() == '') {
            inputAnoInicial.value = '';
            inputAnoFinal.value = '';
            return
        }

        inputAnoInicial.value = planejamento.getPlano().pl2_ano_inicial;
        inputAnoFinal.value = planejamento.getPlano().pl2_ano_final;
    });

    const valida = () => {
        try {
            if (planejamento.getValue() === '') {
                throw 'Selecione o planejamento';
            }
            if (viewInstituicao.getInstituicoesSelecionadas(true).length === 0) {
                throw 'Selecione ao menos uma instituição';
            }

            if (periodo.value === '') {
                throw 'Selecione o período';
            }
        } catch (e) {
            alerta(e);
            return false;
        }

        return  true;
    };

    btnEmitir.addEventListener('click', () => {
        if (!valida()) {
            return
        }

        const formData = new FormData();
        formData.append('planejamento_id', planejamento.getValue());
        formData.append('codigo_relatorio', get.codigo);
        formData.append('periodo', periodo.value);

        for (let codigo of viewInstituicao.getInstituicoesSelecionadas(true)) {
            formData.append('instituicoes[]', codigo);
        }

        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${rota}`, {body: formData}).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }

            const download = new DBDownload();
            download.addFile(response.data.xls, response.message);
            download.show();
        });
    });
</script>

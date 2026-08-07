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
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body>
<div id='ctnAbas'></div>

<div id="abaPrincipal" class="container">
    <fieldset>
        <legend>Emissão das Projeções da Despesa por Elemento</legend>
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
            <tr class="text-left">
                <td><label class="bold" for="apresentarRecurso">Apresentar Recurso:</label></td>
                <td colspan="3">
                    <select id="apresentarRecurso" name="apresentarRecurso" class="field-size8">
                        <option value="f" selected>Não</option>
                        <option value="t">Sim</option>
                    </select>
                </td>
            </tr>
            <tr style="display: none" id="linhaRecursoOriginal">
                <td>Apresentar Recurso<br>Anterior</td>
                <td colspan="3">
                    <select id="apresentarRecursoOriginal" name="apresentarRecursoOriginal" class="field-size8">
                        <option value="f" selected>Não</option>
                        <option value="t">Sim</option>
                    </select>
                </td>
            </tr>
        </table>
    </fieldset>
    <button id="emitir" type="button">
        <i class="fas fa-print"></i>
        Emitir
    </button>

</div>
<div id="abaFiltros" style="display: none">
    <?php
    $_GET['iCodigoRelatorio'] = 250;
    require_once 'con2_filtrosrelatorios.php';
    ?>
</div>

</body>
<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>
<script rel="script" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
<script rel="script" type="text/javascript" src="scripts/classes/planejamento/planejamento.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script>
    const rota = 'financeiro/planejamento/relatorios/por-elemento';

    const planejamento = new Planejamento(document.getElementById('planejamento'));
    const inputAnoInicial = document.getElementById('ano_inicial');
    const inputAnoFinal = document.getElementById('ano_final');
    const apresentarRecurso = document.getElementById('apresentarRecurso');
    const btnEmitir = document.getElementById('emitir');

    const ctnAbaFiltros = document.getElementById('abaFiltros')
    const ctnAbas = new DBAbas(document.getElementById('ctnAbas'));
    const abaDetalhamento = ctnAbas.adicionarAba("Relatório", document.getElementById('abaPrincipal'));
    const abaCronograma = ctnAbas.adicionarAba("Filtros", ctnAbaFiltros);

    PHPSession.loadData().then(() => {
        planejamento.load();
        ctnAbaFiltros.style.display = '';
    });

    apresentarRecurso.addEventListener('change', function (){
        linhaRecursoOriginal.style.display = 'none';
        if (apresentarRecurso.value === 't') {
            linhaRecursoOriginal.style.display = 'table-row';
        }
    })

    planejamento.getElement().addEventListener('change', () => {

        if (planejamento.getValue() == '') {
            inputAnoInicial.value = '';
            inputAnoFinal.value = '';
            return
        }

        inputAnoInicial.value = planejamento.getPlano().pl2_ano_inicial;
        inputAnoFinal.value = planejamento.getPlano().pl2_ano_final;
    });

    btnEmitir.addEventListener('click', () => {

        let plano = planejamento.getValue();
        if (empty(plano)) {
            alert("selecione um plano.");
            return
        }

        const formData = new FormData();
        formData.append('planejamento_id', plano);
        formData.append('filtros', JSON.stringify(getFiltros()));
        formData.append('apresentarRecurso', apresentarRecurso.value);
        formData.append('apresentarRecursoOriginal', apresentarRecursoOriginal.value);
        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${rota}`, {body: formData}).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }

            const download = new DBDownload();
            download.addFile(response.data.pdf, "Projeções da despesa por Elemento - PDF");
            download.show();
        });
    });
</script>

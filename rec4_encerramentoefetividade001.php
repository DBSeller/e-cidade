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

use ECidade\RecursosHumanos\RH\Efetividade\Model\ConfiguracaoDataEfetividade;
use ECidade\RecursosHumanos\RH\Efetividade\Repository\ConfiguracaoDataEfetividadeRepository;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('libs/db_app.utils.php');
require_once modification('classes/db_tipoasse_classe.php');

$instituicao = InstituicaoRepository::getInstituicaoSessao();
$configuracaoDataEfetividadeRepository = new ConfiguracaoDataEfetividadeRepository();
$configuracoesDataEfetividade = $configuracaoDataEfetividadeRepository
    ->scopeInstituicao($instituicao)
    ->get(array('rh186_exercicio'), array('rh186_exercicio DESC'));

$exercicios = array();

foreach ($configuracoesDataEfetividade as $configuracaoDataEfetividade) {
    $exercicios[$configuracaoDataEfetividade->getAno()] = $configuracaoDataEfetividade;
}

$anoSessao = db_getsession('DB_anousu');

if (!array_key_exists($anoSessao, $exercicios)) {
    $configuracaoDataEfetividade = new ConfiguracaoDataEfetividade();
    $configuracaoDataEfetividade->setAno($anoSessao);
    $exercicios[$anoSessao] = $configuracaoDataEfetividade;
}

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DBSeller Informática Ltda</title>
    <?php
    db_app::load('scripts.js, strings.js, prototype.js, estilos.css, datagrid.widget.js, AjaxRequest.js, ProgressBar.widget.js, DBLancador.widget.js');
    ?>
    <style>
        .ctn {
            width: 100%;
            margin-right: auto;
            margin-left: auto;
            padding: 5% 15px;
        }

        @media (min-width: 576px) {
            .ctn {
                max-width: 540px;
            }
        }

        @media (min-width: 768px) {
            .ctn {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            .ctn {
                max-width: 960px;
            }
        }

        @media (min-width: 1200px) {
            .ctn {
                max-width: 1140px;
            }
        }

        .form-group {
            padding-top: .25rem;
            padding-bottom: .25rem;
        }

        .w-100 {
            width: 100% !important;
        }
    </style>
</head>
<body class="ctn">
<form name="form1" id="form1">
    <fieldset>
        <legend>Encerramento do Período de Efetividade</legend>
        <table class="w-100">
            <tr>
                <td class="form-group" style="width: 15%">
                    <strong>
                        <label for="iExercicio">Exercício:</label>
                    </strong>
                </td>
                <td class="form-group">
                    <select name="iExercicio" id="iExercicio" onchange="js_carregarRegistros()">
                        <?php foreach ($exercicios as $configuracaoDataEfetividade): ?>
                            <option value="<?php echo $configuracaoDataEfetividade->getAno() ?>">
                                <?php echo $configuracaoDataEfetividade->getAno() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="form-group">
                    <strong>
                        <label for="iGerarAssentamentos">Gerar Assentamentos:</label>
                    </strong>
                </td>
                <td class="form-group">
                    <select name="iGerarAssentamentos" id="iGerarAssentamentos">
                        <option value="0">Não</option>
                        <option value="1" selected>Sim - Todas matrículas</option>
                        <option value="2">Sim - Por matrícula</option>
                    </select>
                </td>
            </tr>
            <tr id="filtroMatriculas" class="d-none">
                <td colspan="2" class="form-group">
                    <div id="ctnLancador"></div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="form-group">
                    <div id="grid_registros" class="form-group"></div>
                </td>
            </tr>
        </table>
    </fieldset>
    <div class="text-center form-group">
        <input type="button" name="salvar" id="salvar" value="Encerrar" onclick="js_salvar()"/>
    </div>
</form>
<?php db_menu(); ?>
<script>
    const url = "rec4_encerramentoefetividade.RPC.php";
    const selectGerarAssentamentos = document.getElementById('iGerarAssentamentos');
    const selectExercicio = document.getElementById('iExercicio');
    const trFiltroMatriculas = document.getElementById('filtroMatriculas');

    selectGerarAssentamentos.addEventListener('change', () => {
        if (parseInt(selectGerarAssentamentos.value) === 2) {
            trFiltroMatriculas.classList.remove('d-none');
        } else {
            trFiltroMatriculas.classList.add('d-none');
        }
    });

    js_montaGrid();

    var oLancador = new DBLancador('Lancador');
    oLancador.setNomeInstancia('oLancador');
    oLancador.setLabelAncora('Matrícula: ');
    oLancador.setTextoFieldset('Matrículas');
    oLancador.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist', 'z01_nome']);
    oLancador.show($('ctnLancador'));

    function js_montaGrid() {
        oGridRegistros = new DBGrid("dataGridRegistros");
        oGridRegistros.sName = "dataGridRegistros";
        oGridRegistros.nameInstance = "oGridRegistros";
        oGridRegistros.setSelectAll(false);
        oGridRegistros.setHeight(236);
        oGridRegistros.setCheckbox(0);
        oGridRegistros.setHeader(["Competência", "Data Início", "Data Fechamento", "Data Entrega"]);
        oGridRegistros.setCellWidth(["100px", "150px", "150px", "150px"]);
        oGridRegistros.setCellAlign(["center", "center", "center", "center"]);
        oGridRegistros.show($('grid_registros'));
        oGridRegistros.showColumn(false, 4);

        js_carregarRegistros();
    }

    function js_carregarRegistros() {
        const oParametros = {
            exec: 'carregarConfiguracoes',
            iExercicio: selectExercicio.value
        };

        const oAjaxRequest = new AjaxRequest(url, oParametros, function (oAjax) {
                oGridRegistros.clearAll(true);

                for (var iCompetencia = 1; iCompetencia <= 12; iCompetencia++) {
                    oDatasCompetencia = oAjax.aConfiguracoes[iCompetencia - 1];

                    lProcessado = oDatasCompetencia.lProcessado;

                    oGridRegistros.addRow([
                        iCompetencia,
                        oDatasCompetencia.dDataInicioEfetividade,
                        oDatasCompetencia.dDataFechamentoEfetividade,
                        oDatasCompetencia.dDataEntregaEfetividade
                    ], false, lProcessado, lProcessado);
                }

                oGridRegistros.renderRows();
            }
        );

        oAjaxRequest.setMessage('Carregando configurações...');
        oAjaxRequest.execute();
    }

    function js_salvar() {
        const matriculas = [];
        const aSelecionados = [];

        $$('input:checkbox:checked:enabled').forEach(function (element) {
            aSelecionados.push(element.value);
        });

        if (aSelecionados.length === 0) {
            alert('Selecione ao menos uma Competência.');
            return;
        }

        if (!!parseInt(selectGerarAssentamentos.value)) {
            if (!confirm("Assentamentos de Efetividade e Eventos financeiros serão lançados, deseja continuar?")) {
                return false;
            }

            if (parseInt(selectGerarAssentamentos.value) === 2) {
                if (oLancador.getRegistros().length === 0) {
                    alert('Informe ao menos uma matrícula.');
                    return false;
                }

                oLancador.getRegistros().forEach(item => matriculas.push(parseInt(item.sCodigo)));
            }
        }

        var queryString = 'rec4_encerramentoefetividade002.php?';
        queryString += 'iExercicio=' + selectExercicio.value;
        queryString += '&aSelecionados=' + aSelecionados.join('-');
        queryString += '&gerarAssentamentos=' + parseInt(selectGerarAssentamentos.value);

        if (matriculas.length > 0) {
            queryString += '&matriculas=' + matriculas.join('-');
        }

        js_OpenJanelaIframe('', 'db_encerramento_efetividade', queryString, 'Encerrando Efetividade', true);
    }

    function js_mostrarMensagem(erro) {
        db_encerramento_efetividade.hide();

        $$('input:checkbox:checked').forEach(function (element) {
            element.disabled = true;
        });

        alert(erro.urlDecode());
    }
</script>
</body>
</html>

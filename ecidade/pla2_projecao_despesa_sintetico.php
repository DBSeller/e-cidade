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
<div class="alert alert-primary text-left" role="alert">
Este relatório tem o nível de agrupamento por <b>Programa</b> ou <b>Iniciativa</b>, ou seja, o valor impresso sempre
será o total do Programa/Iniciativa, mesmo que o Órgão seja filtrado. O filtro Órgão é para visualizar quais programas
constam dentro deste Órgão.
</div>
<div class="container">
    <fieldset>
        <legend>Demonstrativo das Projeções da Despesa</legend>
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
        <fieldset>
            <legend>Filtros para impressão</legend>

            <table class="form-container">

                <tr>
                    <td><label for="agrupar">Agrupar Por:</label></td>
                    <td colspan="3">
                        <select id="agrupar">
                            <option value="programa">Programa</option>
                            <option value="iniciativa">Iniciativa (Projeto/Atividade)</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label id="ancoraOrgao" for="orgao"><a href="#">Órgão:</a></label></td>
                    <td>
                        <input type="text" id="orgao" lang="o40_orgao" class="field-size2">
                        <input type="text" id="descricaoOrgao" lang="o40_descr" class="readonly field-size8 readonly"
                               readonly>
                    </td>
                </tr>
                <tr>
                    <td><label id="ancoraPrograma" for="programa"><a href="#">Programa:</a></label></td>
                    <td>
                        <input type="text" id="programa" lang="o54_programa" class="field-size2">
                        <input type="text" id="descricao" lang="o54_descr" class="readonly field-size8 readonly"
                               readonly>
                    </td>
                </tr>
            </table>
        </fieldset>
    </fieldset>
    <button id="emitir" type="button">
        <i class="fas fa-print"></i>
        Emitir
    </button>
</body>
<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>
<script rel="script" type="text/javascript" src="scripts/classes/planejamento/planejamento.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
<script>
    const rota = 'financeiro/planejamento/relatorios/projecao-despesa-agrupado-sintetico';

    const planejamento = new Planejamento(document.getElementById('planejamento'));
    const inputAnoInicial = document.getElementById('ano_inicial');
    const inputAnoFinal = document.getElementById('ano_final');
    const cboAgrupar = document.getElementById('agrupar');
    const btnEmitir = document.getElementById('emitir');

    const ancoraOrgao = document.getElementById('ancoraOrgao');
    const orgao = document.getElementById('orgao');
    const descricaoOrgao = document.getElementById('descricaoOrgao');

    const ancoraPrograma = document.getElementById('ancoraPrograma');
    const programa = document.getElementById('programa');
    const descricao = document.getElementById('descricao');


    const lookUpPrograma = new DBLookUp(ancoraPrograma, programa, descricao, {
        'sArquivo': 'func_orcprograma.php',
        'sLabel': 'Pesquisar Programa',
        'sObjetoLookUp': "db_iframe_orcprograma"
    });

    const lookUpOrgao = new DBLookUp(ancoraOrgao, orgao, descricaoOrgao, {
        'sArquivo': 'func_orcorgao.php',
        'sLabel': 'Pesquisar Programa',
        'sObjetoLookUp': "db_iframe_orcprograma"
    });

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
        } catch (e) {
            alert(e);
            return false;
        }

        return true;
    };

    const programaSelecionado = () => {
        if (!empty(programa.value)) {
            return Number(programa.value)
        }
        return '';
    };

    const orgaoSelecionado = () => {
        if (!empty(orgao.value)) {
            return Number(orgao.value)
        }
        return '';
    };

    btnEmitir.addEventListener('click', () => {
        if (!valida()) {
            return;
        }

        let plano = planejamento.getValue();
        const formData = new FormData();
        formData.append('planejamento_id', plano);
        formData.append('agrupar', cboAgrupar.value);
        formData.append('orcprograma_id', programaSelecionado());
        formData.append('orcorgao_id', orgaoSelecionado());
        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${rota}`, {body: formData}).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }

            const download = new DBDownload();
            download.addFile(response.data.pdf, "Projeção da Despesa - PDF");
            download.show();
        });
    });

</script>

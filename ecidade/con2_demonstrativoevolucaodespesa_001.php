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
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
</head>
<body>
<div id='ctnAbas'></div>    
<div id='ctnAbaEmissao' class='subcontainer'>
    <form name="form1" id="form1">
        <fieldset>
            <legend>Demonstrativo da Evolução da Despesa</legend>
            <table class="form-container">
                <tr>
                    <td id="ctnInstituicao" colspan="4" style="font-weight: normal"></td>
                </tr>
            </table>
            <fieldset class="separator">
                <table class="form-container">
                    <tr>
                        <td style="width: 115px">Valores Exibidos:</td>
                        <td>
                            <select id="tipo_pagamento" name="tipo_pagamento" style="width: 100%;">
                                <option value="pago">PAGO NO MÊS</option>
                                <option value="liquidado">LIQUIDADO NO MÊS</option>
                                <option value="empenhado">EMPENHADO NO MÊS</option>
                            </select>
                        </td>
                        
                    </tr>
                </table>
            </fieldset>
            <fieldset class="separator">
                <table class="form-container">
                    <tr>
                        <td style="width: 115px">Mês:</td>
                        <td>
                            <select id="mes" name="mes" style="width: 100%;">
                                <option selected value="">Selecione</option>
                                <option value="01">JANEIRO</option>
                                <option value="02">FEVEREIRO</option>
                                <option value="03">MARÇO</option>
                                <option value="04">ABRIL</option>
                                <option value="05">MAIO</option>
                                <option value="06">JUNHO</option>
                                <option value="07">JULHO</option>
                                <option value="08">AGOSTO</option>
                                <option value="09">SETEMBRO</option>
                                <option value="10">OUTUBRO</option>
                                <option value="11">NOVEMBRO</option>
                                <option value="12">DEZEMBRO</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </fieldset>
    </form>
    <button id="emitir" type="button">
        <i class="fas fa-print"></i>
        Emitir
    </button>
</div>
<div id='ctnAbaFiltros' style="display: none">
    <?php
    $_GET['iCodigoRelatorio'] = 250;
    require_once 'con2_filtrosrelatorios.php';
    ?>
</div>

</body>
<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
<script>

    const ctnAbaFiltros = document.getElementById('ctnAbaFiltros');
    const ctnAbas = new DBAbas(document.getElementById('ctnAbas'));
    const abaRelatorio = ctnAbas.adicionarAba("Relatório", document.getElementById('ctnAbaEmissao'));
    const abaFiltros = ctnAbas.adicionarAba("Filtros", ctnAbaFiltros);

    var viewInstituicao = new DBViewInstituicao('viewInstituicao', document.getElementById('ctnInstituicao'));
    viewInstituicao.iHeight = 150;
    viewInstituicao.show();

    filtro.showSaveButton(false);

    PHPSession.loadData().then(() => {
        ctnAbaFiltros.style.display = '';
        desmarcarFiltros();
        esconderBotoes();
    });

    const desmarcarFiltros = () => {
        
        // Órgaos
        var checksOrgaos = document.querySelectorAll('.checkboxorgao'); 
        var qtdChecksOrgaos = checksOrgaos.length;
        for (var i=0; i < qtdChecksOrgaos; i++) {
            checksOrgaos[i].checked = false;
        }

        // Unidades
        var checksUnidades = document.querySelectorAll('.checkboxunidade'); 
        var qtdChecksUnidades = checksUnidades.length;
        for (var i=0; i < qtdChecksUnidades; i++) {
            checksUnidades[i].checked = false;
        }
    }

    const esconderBotoes = () => {
        document.querySelector('#btnFuncao').style.visibility = 'hidden';
        document.querySelector('#btnSubfuncao').style.visibility = 'hidden';
        document.querySelector('#btnPrograma').style.visibility = 'hidden';
        document.querySelector('#btnProjativ').style.visibility = 'hidden';
        document.querySelector('#btnElemento').style.visibility = 'hidden';
        document.querySelector('#btnRecurso').style.visibility = 'hidden';
    }

    const selectMes = document.getElementById('mes');

    const routs = {
        relatorio: 'financeiro/contabilidade/relatorio/demonstrativo-evolucao-despesa',
    };

    const validarInputs = () => {
        try {
            
            let instituicoesSelecionadas = viewInstituicao.getInstituicoesSelecionadas(true);
            if (instituicoesSelecionadas.length == 0) {
                throw 'Pelo menos uma Instituição deve ser selecionada.';
            }

            if (selectMes.value == '') {
                throw 'O Mês deve ser selecionado.';
            }

        } catch (e) {
            alert(e);
            return false;
        }

        return true;
    }

    document.getElementById('emitir').addEventListener('click', () => {

        if (!validarInputs()) {
            return
        }

        const formData = new FormData(document.getElementById('form1'));
        formData.append('instituicoes', JSON.stringify(viewInstituicao.getInstituicoesSelecionadas()));
        formData.append('filtros', JSON.stringify(getFiltros()));
        formData.append('exercicio', <?= db_getsession('DB_anousu')?>);
        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${routs.relatorio}`, {body: formData}).then((response) => {
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

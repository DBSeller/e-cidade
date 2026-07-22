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

    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
</head>
<body class="body-default">
<div class="container">
    <form>
        <fieldset>
            <legend></legend>
            <table class="form-container">
                <tr>
                    <td>Relatórios:</td>
                    <td>
                        <select id="cboRelatorios" name="relatorio">
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cboSelecionarPeriodos">Selecionar Períodos:</label>
                    </td>
                    <td>
                        <select id="cboSelecionarPeriodos">
                            <option value="f">Não</option>
                            <option value="t">Sim</option>
                        </select>
                    </td>
                </tr>
                <tr id="linhaPeriodos" style="display: none">
                    <td>
                        <label for="cboPeriodos">
                            <strong>Períodos:</strong>
                        </label>
                    </td>
                    <td>
                        <select name="o116_periodo[]" id="cboPeriodos" multiple></select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cboModelo">
                            <strong>Modelo:</strong>
                        </label>
                    </td>
                    <td>
                        <select name="modelo" id="cboModelo" >
                            <option value="0">Não se aplica</option>
                            <option value="1">Modelo In13</option>
                            <option value="2">Modelo Porto Velho</option>
                            <option value="3">Modelo MDF</option>
                        </select>
                    </td>
                </tr>
            </table>
            <fieldset class="separator">
                <legend>Clique no botão "Arquivo" e selecione o arquivo</legend>
                <div id="ctnImportacao" class="field-size-max"></div>
            </fieldset>
        </fieldset>
        <button type="button" name="upload" id="btnUpload">
            <i class="fas fa-file-upload"></i>
            Upload
        </button>
    </form>

</div>
<script language="JavaScript" type="text/javascript" src="scripts/session.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
<script type="text/javascript">

    const routs = {
        relatorios: 'configuracao/relatorios-legais/lrf',
        periodos: 'configuracao/relatorios-legais/periodos',
        template: 'configuracao/relatorios-legais/upload/template',
    }

    const cboRelatorios = document.getElementById('cboRelatorios');
    const cboSelecionarPeriodos = document.getElementById('cboSelecionarPeriodos');
    const linhaPeriodos = document.getElementById('linhaPeriodos');
    const cboPeriodos = document.getElementById('cboPeriodos');
    const cboModelo = document.getElementById('cboModelo');
    const ctnImportacao = document.getElementById('ctnImportacao');
    const btnUpload = document.getElementById('btnUpload');

    PHPSession.loadData().then(() => {
        HttpClient.get(`${PHPSession.requestApi}/${routs.relatorios}`).then(response => {

            cboRelatorios.options.length = 0;
            cboRelatorios.add(new Option('Selecione um relatório', ''));
            response.data.map((relatorio) => {
                cboRelatorios.add(new Option(`${relatorio.codigo} - ${relatorio.descricao}`, relatorio.codigo));
            });
        });
    });

    cboSelecionarPeriodos.addEventListener('change', () => {
        linhaPeriodos.style.display = cboSelecionarPeriodos.value == 't' ? 'table-row' : 'none';

        // marca todos períodos como selecionados
        if (cboSelecionarPeriodos.value == 'f') {
            [...cboPeriodos.options].map((x) => x.selected = true);
        }
    });

    cboRelatorios.addEventListener('change', () => {
        cboPeriodos.options.length = 0;
        console.log(cboPeriodos.options);
        if (cboRelatorios.value === '') {
            return;
        }
        let codigo = cboRelatorios.value;
        HttpClient.get(`${PHPSession.requestApi}/${routs.periodos}/${codigo}`).then(response => {
            response.data.map((periodo) => {
                cboPeriodos.add(new Option(periodo.periodo.o114_descricao, periodo.codigo));
            });

            // seleciona por default todos periodos
            cboSelecionarPeriodos.dispatchEvent(new Event('change'))
        });
    });


    function retornoEnvioArquivo(retorno) {
        if (retorno.error) {
            alert(retorno.error);
            btnUpload.disabled = true;
            return false;
        }

        if (retorno.extension.toLowerCase() != 'xlsx') {
            alert('Arquivo inválido, extensão do arquivo não é "xlsx".');
            btnUpload.disabled = true;
            return false;
        }

        btnUpload.disabled = false;
    }

    const fileUpload = new DBFileUpload({callBack: retornoEnvioArquivo, labelButton: 'Arquivo'});
    fileUpload.show(ctnImportacao);

    const validaFormulario = () => {

        try {
            if (cboRelatorios.value == '') {
                throw 'Selecione um relatório';
            }

            if (cboSelecionarPeriodos.value === 't' && cboPeriodos.value == '') {
                throw 'Selecione ao menos um período.'
            }
        } catch (e) {
            alert(e);
            return false;
        }
        return true;
    }

    const limparForm = () => {
        cboRelatorios.value = '';
        cboRelatorios.dispatchEvent(new Event('change'));
        fileUpload.clear();
    }

    document.getElementById('btnUpload').addEventListener('click', function () {
        if (!validaFormulario()) {
            return false;
        }

        const formData = new FormData();
        formData.append('relatorio', cboRelatorios.value);
        formData.append('modelo', cboModelo.value);
        [...cboPeriodos.options]
            .filter((x) => x.selected)
            .map((x)=> formData.append('periodo[]', x.value));

        formData.append('file', JSON.stringify({
            "extension": fileUpload.extension,
            "name": fileUpload.file,
            "path": fileUpload.filePath
        }));

        HttpClient.post(`${PHPSession.requestApi}/${routs.template}`, {body: formData}).then(response => {
            alert(response.message);
            if (response.erro) {
                return;
            }

            limparForm()
        });
    });
</script>
</body>
</html>

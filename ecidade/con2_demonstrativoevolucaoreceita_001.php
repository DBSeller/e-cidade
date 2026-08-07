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

$meses = [
    '01' => 'JANEIRO','02' => 'FEVEREIRO',
    '03' => 'MARÇO','04' => 'ABRIL',
    '05' => 'MAIO','06' => 'JUNHO',
    '07' => 'JULHO','08' => 'AGOSTO',
    '09' => 'SETEMBRO','10' => 'OUTUBRO',
    '11' => 'NOVEMBRO','12' => 'DEZEMBRO'
];
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
<div id='ctnAbaEmissao' class='subcontainer'>
    <br />
    <fieldset>
        <legend>Demonstrativo da Evolução da Receita</legend>
        <form name="formulario" id="formulario">
            <table class="form-container">
                <tr>
                    <td id="ctnInstituicao" colspan="4" style="font-weight: normal">
                        <input type="hidden" name="db_selinstit" id="db_selinstit" value="">
                    </td>
                </tr>
            </table>
            <fieldset class="separator">
                <legend>&nbsp;</legend>
                <table class="form-container">
                    <tr>
                        <td>Mês:</td>
                        <td>
                        <select id="mes" name="mes" style="width: 100%;">
                            <option selected value="">Selecione</option>
                            <?php foreach ($meses as $key => $mes) : ?>
                                <option
                                    value="<?= $key ?>"><?= $mes ?></option>
                            <?php endforeach; ?>
                        </select>
                        </td>

                    </tr>
                </table>
            </fieldset>
        </form>
    </fieldset>
    <button id="emitir" type="button">
        <i class="fas fa-print"></i>
        Emitir
    </button>
</div>

</body>
<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
<script language="javascript" type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>
<script language="javascript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
<script>

    const inputNatureza = document.getElementById('natureza');
    const selectMes = document.getElementById('mes');

    const routs = {
        relatorio: 'financeiro/contabilidade/relatorio/demonstrativo-evolucao-receita',
    };

    var viewInstituicao = new DBViewInstituicao('viewInstituicao', document.getElementById('ctnInstituicao'));
    viewInstituicao.iHeight = 150;
    viewInstituicao.show();

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

        const formData = new FormData(document.getElementById('formulario'));
        formData.append('instituicoes', JSON.stringify(viewInstituicao.getInstituicoesSelecionadas()));
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

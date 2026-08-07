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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("fpdf151/pdf.php"));

$modulo = (int) db_getsession("DB_modulo");
$moduloEscola = 1100747;
$display = $moduloEscola === $modulo ? 'display: none' : 'display:';
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">

</head>
<body bgcolor="#cccccc">
<div class="container">
    <form id="form-upload" method="post" action="" enctype="multipart/form-data">
        <fieldset>
            <legend>Importar Situação do Aluno</legend>
            <fieldset class="separator">
                <legend><label for='ano'>Selecione o ano do arquivo</label></legend>
                <select id='ano' name="ano" class="field-size-max"> </select>
            </fieldset>

            <fieldset class="separator">
                <legend>Clique no botão "Arquivo" e selecione o arquivo</legend>
                <div id="ctnImportacao"></div>
            </fieldset>
        </fieldset>

        <fieldset style="<?=$display?>">
            <legend>Filtro</legend>
            <div class="alert alert-primary text-left" role="alert">
                Por padrão, todas as escolas presentes no arquivo serão importadas.<br>
                Você pode optar por importar apenas algumas escolas selecionando:<br>
                <strong>Filtrar Escola: Sim</strong>.<br>
            </div>
            <div>
                <table class="form-container">
                    <tr>
                        <td class="field-size2"><label for="filtrarEscola">Filtrar Escola:</label></td>
                        <td>
                            <select id="filtrarEscola" name="filtrarEscola">
                                <option selected value="0">Não</option>
                                <option value="1">Sim</option>
                            </select>
                        </td>
                    </tr>
                </table>

                <div style="display: none" id="lancadorEscolas"></div>
            </div>
        </fieldset>
        <input type="button" id="btnProcessar" value="Processar">
    </form>
</div>
<?php db_menu(); ?>
</body>
<script type="text/javascript">
    const dataAtual = new Date();
    const formulario = $('form-upload');
    const cboAno = $('ano');
    const cboFiltrarEscola = $('filtrarEscola');
    const cntLancadorEscolas = $('lancadorEscolas');
    const btnProcessar = $('btnProcessar');
    cboAno.add(new Option(dataAtual.getFullYear(), dataAtual.getFullYear()));
    cboAno.add(new Option(dataAtual.getFullYear() - 1, dataAtual.getFullYear() - 1, true));

    var lancadorEscolas = new DBLancador("lancadorEscolas");
    lancadorEscolas.iGridHeight    = 100;
    lancadorEscolas.sTextoFieldset = 'Escola(s)';
    lancadorEscolas.setLabelAncora("Escola:");
    lancadorEscolas.setNomeInstancia("lancadorEscolas");
    lancadorEscolas.setHabilitado(true);
    lancadorEscolas.setParametrosPesquisa("func_escola.php", ["ed18_i_codigo", "ed18_c_nome"]);
    lancadorEscolas.show($("lancadorEscolas"));

    btnProcessar.disabled = true;

    function retornoEnvioArquivo(retorno) {

        if (retorno.error) {

            alert(retorno.error);
            btnProcessar.disabled = true;
            return false;
        }

        if (retorno.extension.toLowerCase() != 'txt') {
            alert('Arquivo inválido, extensão do arquivo não é "txt".');
            btnProcessar.disabled = true;
            return false;
        }

        btnProcessar.disabled = false;
    }

    const fileUpload = new DBFileUpload({callBack: retornoEnvioArquivo, labelButton: 'Arquivo'});
    fileUpload.show($('ctnImportacao'));

    document.querySelector(".inputUploadFile").addClassName('field-size8');

    btnProcessar.addEventListener('click', function () {

        const formData = new FormData(formulario);
        formData.append('acao', 'importarInep');
        formData.append('file', JSON.stringify({
            "extension": fileUpload.extension,
            "name": fileUpload.file,
            "path": fileUpload.filePath
        }));

        if (Number(cboFiltrarEscola.value) === 1) {
            const escolas = lancadorEscolas.getRegistros();
            escolas.map((escola) => {
                formData.append('escolas[]', escola.sCodigo);
            })
        }

        HttpClient.post('edu4_novoCenso.RPC.php', {body: formData}).then(response => {
            alert(response.mensagem);
            if (response.erro) {
                return;
            }

            const download = new DBDownload();
            download.addFile(response.arquivo_log, "Log da importação");
            download.show();
        });
    });

    cboFiltrarEscola.addEventListener('change', (event) => {
        lancadorEscolas.clearAll();
        cntLancadorEscolas.style.display = 'none';
        if (event.target.value == 1) {
            cntLancadorEscolas.style.display = '';
        }
    });
</script>

</html>

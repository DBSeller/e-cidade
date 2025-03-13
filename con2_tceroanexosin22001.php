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

use ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22\Base as RelatoriosIn22;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$get = db_utils::postMemory($_GET);

$dePara = RelatoriosIn22::getRegrasEmissaoRelatorio($get->anexo);
$relatorioContabil = new relatorioContabil($dePara["codigo"]);
$periodos = $relatorioContabil->getPeriodos();
$periodosComboBox = array();
$periodosComboBox[0] = "Selecione";

foreach ($periodos as $oPeriodo) {
    $periodosComboBox[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
}
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
    <fieldset style="width: 500px">
        <legend class="bold">TCE / RO - Anexo <?php echo $get->anexo;?></legend>
        <div id="ctnGridInstituicao"></div>
        <table style="width: 100%">
            <tr>
                <td style="width: 20%" class="bold">Período:</td>
                <td style="width: 80%">
                    <?php db_select("o116_periodo", $periodosComboBox, true, 1); ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <p>
        <input type="button" value="Emitir" onclick="emitir();" />
    </p>
</div>
</body>
</html>
<script type="text/javascript">

    let periodo = document.querySelector('#o116_periodo');
    periodo.style.width = '100%';
    var oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('ctnGridInstituicao'));
    oViewInstituicao.show();

    function emitir() {

        if (oViewInstituicao.getInstituicoesSelecionadas().length === 0) {
            alert('Selecione uma instituição.');
            return false;
        }

        if (periodo.value === '0') {
            alert('Período é de preenchimento obrigatório.');
            return false;
        }

        AjaxRequest.create(
            'con2_tceroanexoin22.RPC.php',
            {
                'exec' : 'processar',
                'instituicoes' : oViewInstituicao.getInstituicoesSelecionadas(true),
                'periodo' : periodo.value,
                'codigo_relatorio' : <?php echo $dePara["codigo"];?>,
            },
            function (retorno, erro) {

                if (erro) {
                    alert(retorno.mensagem);
                    return false;
                }

                var download = new DBDownload();
                download.addFile(retorno.caminho_arquivo, retorno.nome_arquivo);
                download.show();

            }
        ).execute();

    }

</script>

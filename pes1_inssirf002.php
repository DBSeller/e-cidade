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
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <link rel="stylesheet" href="estilos/grid.style.css"/>
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <script type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToggleList.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBPesquisa.plugin.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" id="body-previdencia" data-codigo-tabela="<?php echo !empty($codtab) ? $codtab : '' ?>">

<div id="container-abas">
    <div id='aba-previdencias'>
        <?php include('pes1_inssirf002_previdencia.php'); ?>
    </div>
    <div id='aba-vinculos'>
        <?php if ($codtab >= 3) include('pes1_inssirf002_afastamento.php'); ?>
    </div>
</div>

<?php
db_menu();
?>
</body>
</html>

<script type="application/javascript">

    const body = document.getElementById('body-previdencia');
    const codigoTabelaPrevidencia = body.getAttribute('data-codigo-tabela');

    if (codigoTabelaPrevidencia >= 3) {
        const containerAbas = new DBAbas(document.getElementById('container-abas'));
        const abaPrevidencias = containerAbas.adicionarAba('Previdência e IRRF', document.getElementById('aba-previdencias'), true);
        const abaVinculos = containerAbas.adicionarAba('Rubricas para afastamento', document.getElementById('aba-vinculos'), false);
    }
</script>

<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009 DBSeller Servicos de Informatica
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

use ECidade\Configuracao\RelatorioLegal\Registry\PeriodoRegistry;
use ECidade\Configuracao\RelatorioLegal\Repositorio\RelatorioPeriodoRepositorio;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('classes/db_periodo_classe.php');
require_once modification('dbforms/db_funcoes.php');

parse_str($HTTP_SERVER_VARS["QUERY_STRING"], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

db_postmemory($HTTP_POST_VARS);
db_inicio_transacao();

$clperiodo = new cl_periodo();
$db_botao = false;
$db_opcao = 33;
$erro = false;
$mensagem = '';
$excluiu = false;

try {
    if (isset($excluir)) {
        if (empty($o114_sequencial)) {
            throw new Exception('É necessário informar o código do período.');
        }

        $relatorioPeriodoRepositorio = new RelatorioPeriodoRepositorio();
        $relatorioPeriodos = $relatorioPeriodoRepositorio->scopePeriodo(PeriodoRegistry::get($o114_sequencial))->get();

        if (count($relatorioPeriodos) > 0) {
            throw new Exception('Não é possível excluir o período, pois o mesmo está vinculado a um ou mais relatórios.');
        }

        $db_opcao = 3;
        $clperiodo->excluir($o114_sequencial);
        $excluiu = true;
        $mensagem = 'Período excluído com sucesso!';
    } elseif (isset($chavepesquisa)) {
        $db_opcao = 3;
        $result = $clperiodo->sql_record($clperiodo->sql_query($chavepesquisa));
        db_fieldsmemory($result, 0);
        $db_botao = true;
    }
} catch (Exception $exception) {
    $mensagem = $exception->getMessage();
    $erro = true;
}

db_fim_transacao($erro);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DBSeller Informática Ltda</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
    </tr>
</table>

<br/><br/>
<center>
    <?php require_once modification('forms/db_frmperiodo.php'); ?>
</center>

<?php

db_menu();

if ($mensagem) {
    db_msgbox($mensagem);
}

if ($excluiu) {
    ?>
    <script>location.href = 'Con1_periodo003.php';</script>
    <?php
}

if ($db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
    js_tabulacaoforms('form1', 'excluir', true, 1, 'excluir', true);
</script>
</body>
</html>

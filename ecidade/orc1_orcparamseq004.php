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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_orcparamseq_classe.php');
require_once modification('classes/db_orcparamseqorcparamseqcoluna_classe.php');

db_inicio_transacao();

$erro = false;
$mensagem = '';

try {
    $parametros = JSON::requestParameters();

    db_postmemory($_POST);

    $clorcparamseq = new cl_orcparamseq();
    $db_opcao = 1;
    $db_botao = true;

    if (isset($incluir)) {
        $clorcparamseq->incluir($parametros->o69_codparamrel, $parametros->o69_codseq);

        if ($clorcparamseq->erro_status === '0') {
            throw new Exception('Não foi possível salvar a linha. Contate o suporte.');
        }

        $mensagem = 'Linha salva com sucesso!';
        $o69_codparamrel = $clorcparamseq->o69_codparamrel;
        $o69_codseq = $clorcparamseq->o69_codseq;
        $db_opcao = 1;
        $db_botao = true;
    }
} catch (Exception $exception) {
    $erro = true;
    $mensagem = $exception->getMessage();
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
    <script src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
    <?php

    require_once modification('forms/db_frmorcparamseq.php');

    if (isset($incluir)) {
        db_msgbox($mensagem);

        if (!$erro) {
            db_redireciona("orc1_orcparamseq005.php?liberaaba=true&chavepesquisa={$o69_codparamrel}&chavepesquisa1={$o69_codseq}");
        }
    }

    ?>
</center>
</body>
</html>

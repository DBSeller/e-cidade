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

use ECidade\Configuracao\RelatorioLegal\Repositorio\RelatorioRepositorio;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_orcparamrel_classe.php');
require_once modification('classes/db_orcparamrelperiodos_classe.php');

db_postmemory($HTTP_POST_VARS);

$clorcparamrel = new cl_orcparamrel();
$db_opcao = 1;
$db_botao = true;
$erro = false;
$mensagem = '';
$codigo = null;

if (isset($incluir)) {
    db_inicio_transacao();

    try {

        $clorcparamrel->incluir(RelatorioRepositorio::nextval());

        if ($clorcparamrel->erro_status === '0') {
            throw new Exception('Não foi possível salvar o relatório. Contate o suporte.');
        }

        if ( !empty($templatePath) ) {

            $oDaoOrcparamreltemplate = new cl_orcparamreltemplate();

            /**
             * Geramos um Blob vazio e gravamos o arquivo no banco
             */
            $iOid          = DBLargeObject::criaOID( true );
            $lSalvaArquivo = DBLargeObject::escrita( $templatePath, $iOid );

            $oDaoOrcparamreltemplate->o163_template = $iOid;
            $oDaoOrcparamreltemplate->o163_orcparamrel = $clorcparamrel->o42_codparrel;
            $oDaoOrcparamreltemplate->incluir();

            if ($oDaoOrcparamreltemplate->erro_status == "0") {
                $sMsg  = "Não foi possível salvar template do relatório. Tente novamente mais tarde, ";
                $sMsg .= "se o problema persisrir, contate o suporte.";
                throw new Exception($sMsg);
            }
        }

        // incluir relatorio template

        $mensagem = 'Relatório salvo com sucesso!';
        $codigo = $clorcparamrel->o42_codparrel;
    } catch (Exception $exception) {
        $erro = true;
        $mensagem = $exception->getMessage();
    }

    db_fim_transacao($erro);
    $db_opcao = 1;
    $db_botao = true;
}
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
<body class="container">
<?php

require_once modification('forms/db_frmorcparamrel.php');

if (isset($incluir)) {
    db_msgbox($mensagem);

    if (!$erro) {
        db_redireciona("orc1_orcparamrel005.php?liberaaba=true&chavepesquisa={$codigo}");
    }
}
?>
</body>
</html>

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

use App\Domain\Patrimonial\Protocolo\Repository\DocumentosAndamentoRepository;

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_app.utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);
$documentosAndamentoRepository = new DocumentosAndamentoRepository();
try {
    $documentoAndamento = $documentosAndamentoRepository->scopeCodigoOrigem($oGet->e60_numemp)->first();
    $codigoEstorage = $documentoAndamento->processoDocumento->p01_documento;
    if (empty($codigoEstorage)) {
        throw new Exception("Não foi encontrado o Documento deste Empenho.");
    }
} catch (Exception $e) {
    die($e->getMessage());
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<div class="container">
    <fieldset>
        <legend>Reemitir nota de empenho</legend>
        <button class="btn btn-default" id="btnImprimir">Imprimir PDF</button>
    </fieldset>
</div>

<script type="text/javascript">
    const btnImprimir = document.getElementById('btnImprimir');
    btnImprimir.addEventListener('click', () => {
        window.open(`db_visualizar_estorage.php?id=<?=$codigoEstorage?>`);
    })
</script>
</body>
</html>

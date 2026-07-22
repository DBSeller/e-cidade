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

use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Service\FluxoCaixaService;

require_once modification("fpdf151/assinatura.php");
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_sql.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_libtxt.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_libpostgres.php");
require_once modification("libs/db_libcontabilidade.php");
require_once modification("libs/db_liborcamento.php");
require_once modification("fpdf151/PDFDocument.php");

try {
    if (empty($_GET['periodo'])) {
        throw new Exception('Período não informado.');
    }

    if (empty($_GET['db_selinstit'])) {
        throw new Exception('Instituição não informada.');
    }

    $instituicoes = implode(',', explode('-', $_GET['db_selinstit']));
    if (!empty($_GET['lQuadroPrincipal']) && $_GET['lQuadroPrincipal'] === 'true') {
        $aQuadros[] = FluxoCaixaService::QUADRO_PRINCIPAL;
    }

    if (!empty($_GET['lQuadroReceitas']) && $_GET['lQuadroReceitas'] === 'true') {
        $aQuadros[] = FluxoCaixaService::QUADRO_RECEITAS;
    }

    if (!empty($_GET['lQuadroTransferencias']) && $_GET['lQuadroTransferencias'] === 'true') {
        $aQuadros[] = FluxoCaixaService::QUADRO_TRANSFERENCIAS;
    }

    if (!empty($_GET['lQuadroDesembolsos']) && $_GET['lQuadroDesembolsos'] === 'true') {
        $aQuadros[] = FluxoCaixaService::QUADRO_DESEMBOLSOS;
    }

    if (!empty($_GET['lQuadroDesembolsos']) && $_GET['lQuadroDesembolsos'] === 'true') {
        $aQuadros[] = FluxoCaixaService::QUADRO_DIVIDA;
    }

    $exibirExercicioAnterior = false;
    if (!empty($_GET['imprimirValorExercicioAnterior']) && $_GET['imprimirValorExercicioAnterior'] === 'true') {
        $exibirExercicioAnterior = true;
    }
    $service = new FluxoCaixaService(
        $_GET['modelo'],
        db_getsession('DB_anousu'),
        $_GET['periodo'],
        $instituicoes,
        $exibirExercicioAnterior,
        $aQuadros
    );

    $service->imprimir();
} catch (Exception $e) {
    db_redireciona('db_erros.php?db_erro=' . urlencode($e->getMessage()));
}

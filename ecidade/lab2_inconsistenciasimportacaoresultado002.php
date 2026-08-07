<?php
/**
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("fpdf151/PDFDocument.php"));

use ECidade\Saude\Laboratorio\Exame\Relatorio\ImportacaoInconsistencia;
use ECidade\Saude\Laboratorio\Service\ImportacaoRequisicaoInconsistenciaService;
use ECidade\Saude\Laboratorio\Repository\ImportacaoRequisicaoInconsistenciaRepository;

global $head1;

/**
 * Parâmetros:
 * - buscarRegistros
 * - arquivo
 */
$get = db_utils::postMemory($_GET);
$pdf = new PDFDocument();

$service = new ImportacaoRequisicaoInconsistenciaService(
    new ImportacaoRequisicaoInconsistenciaRepository(new \cl_lab_importacaorequisicaoinconsistencia())
);

try {
    $dados = null;

    /**
     * Se flag for igual a 'menuRelatorio' o PDF está sendo gerado
     * ao importar atributos para o exame.
     */
    if (isset($_GET['flag']) && $_GET['flag'] === 'menuRelatorio') {
        $dadosJson = $service->getInconsistenciasJsonByLaboratorioSetor($laboratorio, $setor);
    } else {
        $dados = $service->getInconsistenciasByRequisicao($requisicao);
        $jsonObject = JSON::create();
        $dadosJson = array();

        foreach ($dados as $dado) {
            $dadosJson[] = $jsonObject->parse(
                $dado['la64_inconsistencias'],
                JSON::UTF8_DECODE,
                true,
                true
            );
        }
    }

    $importacaoInconsistencia = new ImportacaoInconsistencia($pdf);
    $importacaoInconsistencia->setDadosRelatorio($dadosJson);
    $importacaoInconsistencia->imprimir();
} catch (Exception $erro) {
    db_redireciona('db_erro?erro=' . $erro->getMessage());
}

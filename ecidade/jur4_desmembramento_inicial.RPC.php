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

use ECidade\Tributario\Juridico\Inicial\Repository\HistoricoDesmembramentoRepository;
use ECidade\Tributario\Juridico\Inicial\Repository\InicialNumpreRepository;
use ECidade\Tributario\Juridico\Repository\Desmembramento as DesmembramentoRepository;
use ECidade\Tributario\Juridico\Service\Desmembramento;

$message = '';
$error = false;
$response = new stdClass();
$params = JSON::requestParameters();

try {
    db_inicio_transacao();

    $desmembramentoRepository = new DesmembramentoRepository(
        db_getsession('DB_instit'),
        db_getsession('DB_datausu'),
        db_getsession('DB_anousu')
    );

    $desmembramentoService = new Desmembramento(
        $desmembramentoRepository,
        new HistoricoDesmembramentoRepository(),
        new InicialNumpreRepository()
    );

    switch ($params->exec) {
        case 'getIniciais':
            $filter = new stdClass();
            isset($params->cgm) ? $filter->cgm = $params->cgm : null;
            isset($params->matric) ? $filter->matric = $params->matric : null;
            isset($params->inscr) ? $filter->inscr = $params->inscr : null;
            isset($params->processoForo) ? $filter->processoForo = $params->processoForo : null;
            isset($params->inicial) ? $filter->inicial = $params->inicial : null;
            isset($params->exercicio) ? $filter->exercicio = $params->exercicio : null;
            isset($params->cda) ? $filter->cda = $params->cda : null;

            $response->iniciais = $desmembramentoService->getDados($filter);
            $response->quantidadeDividasPorInicial = $desmembramentoService->getQuantidadeDeDividasPorInicial($response->iniciais);
            break;
        case 'desmembrarInicial':
            if (empty($params->dividas)) {
                throw new Exception('Nenhuma dívida informada para desmembramento.');
            }

            $desmembramentoService->desmembrarInicial(explode(',', $params->dividas));

            $message = 'Desmembramento de iniciais efetuado com sucesso. Iniciais criadas: '
                . implode(', ', $desmembramentoService->getIniciaisCriadas());

            break;
    }
} catch (Exception $exception) {
    $error = true;
    $message = $exception->getMessage();
}

db_fim_transacao($error);

$response->erro = $error;
$response->message = $message;

echo JSON::create()->stringify($response);

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

namespace App\Domain\Financeiro\Planejamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
//phpcs:disable
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\RemoverIndicadorProgramaEstrategicoRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\SalvarIndicadorProgramaEstrategicoRequest;
//phpcs:enable
use App\Domain\Financeiro\Planejamento\Services\IndicadorProgramaEstrategicoService;
use App\Http\Controllers\Controller;

/**
 * Class IndicadorProgramaEstrategicoController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class IndicadorProgramaEstrategicoController extends Controller
{
    /**
     * @var IndicadorProgramaEstrategicoService
     */
    private $service;

    public function __construct(IndicadorProgramaEstrategicoService $service)
    {
        $this->service = $service;
    }

    /**
     * @param SalvarIndicadorProgramaEstrategicoRequest $request
     * @return DBJsonResponse
     */
    public function salvar(SalvarIndicadorProgramaEstrategicoRequest $request)
    {
        return new DBJsonResponse($this->service->salvarFromRequest($request), 'Indicador salvo com sucesso.');
    }

    public function delete(RemoverIndicadorProgramaEstrategicoRequest $request)
    {
        $this->service->remover($request->get('pl22_codigo'));
        return new DBJsonResponse([], 'Indicador removido com sucesso.');
    }
}

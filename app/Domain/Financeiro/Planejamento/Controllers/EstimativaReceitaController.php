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
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Receita\ExcluirEstimativaReceitaRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Receita\SalvarEstimativaReceitaRequest;
use App\Domain\Financeiro\Planejamento\Services\EstimativaReceitaService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

/**
 * Class EstimativaReceitaController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class EstimativaReceitaController extends Controller
{
    /**
     * @var EstimativaReceitaService
     */
    private $service;

    public function __construct(EstimativaReceitaService $service)
    {
        $this->service = $service;
    }

    /**
     * @param $id
     * @return DBJsonResponse
     */
    public function show($id)
    {
        return new DBJsonResponse($this->service->find($id), 'Estimativas encontrada.');
    }

    /**
     * @param Request $request
     * @return DBJsonResponse
     */
    public function buscar(Request $request)
    {
        $estimativas = $this->service->filtrar($request->all());
        return new DBJsonResponse(
            $estimativas,
            'Estimativas encontradas.'
        );
    }

    /**
     * @param SalvarEstimativaReceitaRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function salvar(SalvarEstimativaReceitaRequest $request)
    {
        return new DBJsonResponse(
            $this->service->salvarToObject((object)$request->all()),
            'Estimativa salva com sucesso.'
        );
    }

    public function remover(ExcluirEstimativaReceitaRequest $request)
    {
        $this->service->remover($request->get('id'));
        return new DBJsonResponse([], 'Estimativa excluída com sucesso.');
    }

    public function removerNaturezas(Request $request)
    {
        $this->service->removerNaturezas($request->get('planejamento_id'), $request->get('orcfontes'));
        return new DBJsonResponse([], 'Estimativas excluídas com sucesso.');
    }
}

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
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\ExcluirDetalhamentoDespesaRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\SalvarDetalhamentoDespesaRequest;
use App\Domain\Financeiro\Planejamento\Services\DetalhamentoDespesaService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

/**
 * Class DetalhamentoDespesaController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class DetalhamentoDespesaController extends Controller
{
    /**
     * @var DetalhamentoDespesaService
     */
    private $service;

    public function __construct(DetalhamentoDespesaService $service)
    {
        $this->service = $service;
    }

    /**
     * @param $id
     * @return DBJsonResponse
     */
    public function show($id)
    {
        return new DBJsonResponse($this->service->find($id), 'Detalhamento encontrado.');
    }

    /**
     * @param Request $request
     * @return DBJsonResponse
     */
    public function buscar(Request $request)
    {
        return new DBJsonResponse($this->service->buscar($request->all()), 'Detalhamentos encontrados.');
    }

    /**
     * retorna uma coleção de detalhamento da despesa de acordo com os filtros informados
     * @param SalvarDetalhamentoDespesaRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function salvar(SalvarDetalhamentoDespesaRequest $request)
    {
        $detalhamento = $this->service->salvarFromObject((object) $request->all());
        return new DBJsonResponse($detalhamento, 'Detalhamento salvo com sucesso.');
    }

    /**
     * @param ExcluirDetalhamentoDespesaRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function delete(ExcluirDetalhamentoDespesaRequest $request)
    {
        $this->service->remover($request->get('pl20_codigo'));
        return new DBJsonResponse([], 'Detalhamento removido com sucesso.');
    }
}

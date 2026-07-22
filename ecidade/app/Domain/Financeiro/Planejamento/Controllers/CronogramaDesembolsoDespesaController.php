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
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Cronograma\CronogramaRequest;
use App\Domain\Financeiro\Planejamento\Services\CronogramaDesembolsoDespesaService;
use App\Http\Controllers\Controller;
use Exception;

// phpcs:disable
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\RecalcularGeralCronogramaDesembolsoDespesaRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\RecalcularCronogramaDesembolsoDespesaRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa\SalvarCronogramaDesembolsoDespesaRequest;
// phpcs:enable

/**
 * Class CronogramaDesembolsoDespesaController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class CronogramaDesembolsoDespesaController extends Controller
{

    /**
     * @var CronogramaDesembolsoDespesaService
     */
    private $service;

    /**
     * CronogramaDesembolsoDespesaController constructor.
     * @param CronogramaDesembolsoDespesaService $service
     */
    public function __construct(CronogramaDesembolsoDespesaService $service)
    {
        $this->service = $service;
    }

    /**
     * @param SalvarCronogramaDesembolsoDespesaRequest $request
     * @return DBJsonResponse
     */
    public function salvar(SalvarCronogramaDesembolsoDespesaRequest $request)
    {
        $cronograma = $this->service->salvarFromObject((object) $request->all());
        return new DBJsonResponse($cronograma, 'Cronograma de desembolso salvo com sucesso.');
    }

    /**
     * Recalcula o cronograma da despesa
     * @param RecalcularCronogramaDesembolsoDespesaRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function recalcular(RecalcularCronogramaDesembolsoDespesaRequest $request)
    {
        $cronogramas = $this->service->recalcular($request->all());

        $cronogramas = array_values($cronogramas->toArray());
        return new DBJsonResponse($cronogramas, 'Cronograma de desembolso recalculado com sucesso.');
    }

    /**
     * @param RecalcularGeralCronogramaDesembolsoDespesaRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function recalcularGeral(RecalcularGeralCronogramaDesembolsoDespesaRequest $request)
    {
        $this->service->recalcularGeral($request->all());
        return new DBJsonResponse([], 'Cronogramas de desembolsos recalculados com sucesso.');
    }

    /**
     * @param CronogramaRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function buscar(CronogramaRequest $request)
    {
        $estimativas = array_values($this->service->buscarPorRequest($request));
        return new DBJsonResponse($estimativas, 'Estimativas do cronograma de desembolso.');
    }
}

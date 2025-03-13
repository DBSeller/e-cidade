<?php

namespace App\Domain\Financeiro\Orcamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Orcamento\Requests\Relatorios\AcompanhamentoDespesaRequest;
use App\Domain\Financeiro\Orcamento\Requests\Relatorios\AcompanhamentoMetasVersusCotasRequest;
use App\Domain\Financeiro\Orcamento\Requests\Relatorios\AcompanhamentoReceita;
use App\Domain\Financeiro\Orcamento\Services\Relatorios\CotasDespesaService;
use App\Domain\Financeiro\Orcamento\Services\Relatorios\MetasArrecadacaoService;
use App\Domain\Financeiro\Orcamento\Services\Relatorios\MetasVersusCotasService;
use App\Http\Controllers\Controller;

class RelatoriosCronogramaController extends Controller
{
    public function metaArrecadacao(AcompanhamentoReceita $request)
    {
        $service = new MetasArrecadacaoService($request->all());
        return new DBJsonResponse($service->emitir(), 'Metas de Arrecadação');
    }

    public function cotaDespesa(AcompanhamentoDespesaRequest $request)
    {
        $service = new CotasDespesaService($request->all());
        return new DBJsonResponse($service->emitir(), 'Cotas da Despesa');
    }

    public function metaVersusCota(AcompanhamentoMetasVersusCotasRequest $request)
    {
        $service = new MetasVersusCotasService($request->all());
        return new DBJsonResponse($service->emitir(), 'Metas de Arrecadação x Cotas da Despesa');
    }
}

<?php

namespace App\Domain\Saude\ESF\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\ESF\Requests\RelatorioControleVacinasRequest;
use App\Domain\Saude\ESF\Services\ControleVacinasService;
use App\Domain\Saude\ESF\Services\FichaVacinacaoService;
use Illuminate\Routing\Controller;

/**
 * [Description VacinasController]
 */
class VacinasController extends Controller
{
    /**
     * @param RelatorioControleVacinasRequests $request
     *
     * @return DBJsonResponse
     */
    public function relatorioControleVacinas(RelatorioControleVacinasRequest $request)
    {
        $service = new ControleVacinasService();
        $relatorio = $service->gerarRelatorio((object)$request->all());

        return new DBJsonResponse($relatorio->emitir(), 'Emitindo PDF');
    }

    /**
     * Retorna as fichas de vacinação do paciente
     * @param integer $cgs
     * @throws \Exception
     * @return DBJsonResponse
     */
    public function getByPaciente($cgs)
    {
        validaRequest(['cgs' => $cgs], ['cgs' => 'required|integer']);

        return new DBJsonResponse(FichaVacinacaoService::getVacinasPorPaciente($cgs));
    }
}

<?php

namespace App\Domain\Tributario\Arrecadacao\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\Arrecadacao\Models\RegistroOriginalControleParcelamento;
use App\Domain\Tributario\Arrecadacao\Services\ReversaoParcelamentoVencidoService;

class ReversaoControleParcelamentoController extends Controller
{
    /**
     * Retorna os dados do parcelamento
     * @param integer $numParcelamento
     * @return DBJsonResponse
     */
    public function buscar($numParcelamento)
    {
        $rules = [
            'numParcelamento' => 'required|integer'
        ];
        validaRequest(['numParcelamento' => $numParcelamento], $rules);

        $parcelamentoOrginal = RegistroOriginalControleParcelamento::select(
            ['controleparc_registrosorig.*', 'v07_parcel']
        )
        ->join('termo', 'v07_numpre', 'ar51_numpre')
        ->join('arrecad', function ($join) {
            $join->on('k00_numpre', 'ar51_numpre')
                 ->on('k00_numpar', 'ar51_numpar')
                 ->on('k00_receit', 'ar51_receit');
        })
        ->where('v07_parcel', $numParcelamento)
        ->orderBy('ar51_numpar', 'asc')
        ->get();

        if ($parcelamentoOrginal->isEmpty()) {
            throw new Exception('Parcelamento não encontrado.');
        }

        return new DBJsonResponse($parcelamentoOrginal);
    }

    public function processar($numParcelamento)
    {
        $rules = [
            'numParcelamento' => 'required|integer'
        ];
        validaRequest(['numParcelamento' => $numParcelamento], $rules);

        $service = new ReversaoParcelamentoVencidoService;
        $service->processar($numParcelamento);

        return new DBJsonResponse([], 'Reversão concluída com sucesso!');
    }
}

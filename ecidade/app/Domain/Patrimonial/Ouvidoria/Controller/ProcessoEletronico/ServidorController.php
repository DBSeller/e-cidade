<?php


namespace App\Domain\Patrimonial\Ouvidoria\Controller\ProcessoEletronico;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Ouvidoria\Services\ServidorService;
use App\Http\Controllers\Controller;

class ServidorController extends Controller
{

    public function getMatriculas($cpf, ServidorService $servidorService)
    {
        return new DBJsonResponse($servidorService->getMatriculas($cpf));
    }

    public function getAssentamentos($matricula, ServidorService $servidorService)
    {
        return new DBJsonResponse($servidorService->getAssentamentos($matricula));
    }

    public function getAverbacoes($matricula, ServidorService $servidorService)
    {
        return new DBJsonResponse($servidorService->getAverbacoes($matricula));
    }

    public function getFerias($matricula, ServidorService $servidorService)
    {
        $data["ferias"] = $servidorService->getFerias($matricula);
        $data["proxima_periodo"] = $servidorService->getProximoPeriodoAquisito($matricula);
        return new DBJsonResponse($data);
    }

    public function getAnosTrabalhados($matricula, ServidorService $servidorService)
    {
        return new DBJsonResponse($servidorService->getAnosTrabalhados($matricula));
    }

    /**
     * @throws \Exception
     */
    public function getComprovanteIRRF($matricula, $ano, ServidorService $servidorService)
    {
        return new DBJsonResponse($servidorService->getComprovanteIRRF($matricula, $ano));
    }
}

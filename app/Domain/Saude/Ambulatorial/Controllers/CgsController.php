<?php

namespace App\Domain\Saude\Ambulatorial\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\Ambulatorial\Requests\FindOrCreateCgsRequest;
use App\Domain\Saude\Ambulatorial\Services\FindOrCreateCgsService;
use App\Http\Controllers\Controller;
use App\Domain\Saude\Ambulatorial\Models\Cgs;
use App\Domain\Saude\Ambulatorial\Models\FamiliaMicroarea;
use Exception;

class CgsController extends Controller
{
    /**
     * Retorna os dados completos do cgs
     * @param integer $id
     * @return DBJsonResponse
     */
    public function get($id)
    {
        $rules = [
            'id' => 'required|integer'
        ];
        validaRequest(['id' => $id], $rules);

        $cgs = Cgs::find($id);
        if ($cgs) {
            $cgs->cgsUnidade->cgsExtensao;
        }

        return new DBJsonResponse($cgs);
    }

    /**
     * retorna a familia e microarea do cgs
     * @param integer $id
     * @return DBJsonResponse
     */
    public function getFamiliaMicroarea($id)
    {
        $rules = [
            'id' => 'required|integer'
        ];
        validaRequest(['id' => $id], $rules);

        $cgs = Cgs::find($id);
        if (!$cgs) {
            throw new Exception('CGS não encontrado.');
        }

        $idFamiliaMicroarea = $cgs->cgsUnidade->z01_i_familiamicroarea;

        $familiaMicroarea = FamiliaMicroarea::find($idFamiliaMicroarea);
        if ($familiaMicroarea) {
            $familiaMicroarea->Familia;
            $familiaMicroarea->Microarea;
        }

        return new DBJsonResponse($familiaMicroarea);
    }

    public function findOrCreate(FindOrCreateCgsRequest $request, FindOrCreateCgsService $service)
    {
        $cgs = $service->execute($request);

        return new DBJsonResponse((object)['codigo' => $cgs]);
    }
}

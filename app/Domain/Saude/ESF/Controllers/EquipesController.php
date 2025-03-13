<?php

namespace App\Domain\Saude\ESF\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\ESF\Services\EquipesService;
use Illuminate\Http\Request;

class EquipesController extends Controller
{
    public function getUnidadesComEquipe(Request $request)
    {
        return new DBJsonResponse(EquipesService::buscarUnidadesComEquipe($request->DB_instit));
    }

    public function getEquipesUnidade($idUnidade)
    {
        return new DBJsonResponse(EquipesService::getEquipesUnidade($idUnidade));
    }
}

<?php

namespace App\Domain\Educacao\Censo\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Educacao\Escola\Models\CensoAreasPos;
use App\Http\Controllers\Controller;
use ECidade\Enum\Educacao\Censo\AreasPosGraduacaoEnum;
use ECidade\Enum\Educacao\Censo\TiposPosGraduacaoEnum;

/**
 * Class CalendarioController
 * @package App\Domain\Educacao\Escola\Controllers
 */
class TabelasCensoController extends Controller
{
    public function getTiposPosGraduacao()
    {
        $tipos = TiposPosGraduacaoEnum::getAll();
        return new DBJsonResponse($tipos);
    }

    public function getAreasPosGraduacao()
    {
        $areas = [];
        $areasPos = CensoAreasPos::all()->toArray();
        foreach ($areasPos as $area) {
            $areas[$area['ed184_id']] = $area['ed184_descricao'];
        }
        return new DBJsonResponse($areas);
    }
}

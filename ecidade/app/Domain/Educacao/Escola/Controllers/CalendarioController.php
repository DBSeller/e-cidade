<?php

namespace App\Domain\Educacao\Escola\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Educacao\Escola\Models\Calendario;
use App\Domain\Educacao\Escola\Resources\CalendarioResource;
use App\Http\Controllers\Controller;

/**
 * Class CalendarioController
 * @package App\Domain\Educacao\Escola\Controllers
 */
class CalendarioController extends Controller
{
    public function buscarCalendariosAtivosEscola($escola)
    {
        $calendarios = Calendario::join('duracaocal', 'duracaocal.ed55_i_codigo', '=', 'calendario.ed52_i_duracaocal')
                ->join('calendarioescola', 'calendarioescola.ed38_i_calendario', '=', 'calendario.ed52_i_codigo')
                ->where('ed38_i_escola', $escola)
                ->apenasAtivos()
                ->orderBy('ed52_i_ano', 'desc')->get()->all();
        return new DBJsonResponse(CalendarioResource::toArray($calendarios), '');
    }
}

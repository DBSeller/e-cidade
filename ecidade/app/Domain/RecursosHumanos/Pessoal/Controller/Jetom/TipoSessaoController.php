<?php

namespace App\Domain\RecursosHumanos\Pessoal\Controller\Jetom;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\TipoSessao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoSessaoController extends Controller
{
    /**
     * @param Request $request
     * @return DBJsonResponse
     */
    public function index(Request $request)
    {
        return new DBJsonResponse(TipoSessao::getTipoSessao($request->descricao));
    }

    /**
     * @return DBJsonResponse
     */
    public function all()
    {
        try {
            return new DBJsonResponse(TipoSessao::all());
        } catch (\Exception $exception) {
            return new DBJsonResponse([], 'Não foi possível buscar os tipo de sessão.', 400);
        }
    }
}

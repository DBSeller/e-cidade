<?php

namespace App\Domain\Educacao\Escola\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Educacao\Escola\Models\Vacina;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VacinasController extends Controller
{
    public function index()
    {
        return new DBJsonResponse(Vacina::query()->orderBy('ed178_descricao')->get());
    }

    public function show(Vacina $vacina)
    {
        $vacina->doses;
        return new DBJsonResponse($vacina);
    }
}

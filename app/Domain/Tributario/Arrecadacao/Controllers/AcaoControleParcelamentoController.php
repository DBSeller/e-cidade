<?php

namespace App\Domain\Tributario\Arrecadacao\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\Arrecadacao\Models\AcaoControleParcelamento;

class AcaoControleParcelamentoController extends Controller
{
    public function getAll()
    {
        $acoes = AcaoControleParcelamento::all();
            
        return new DBJsonResponse($acoes);
    }
}

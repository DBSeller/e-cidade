<?php

namespace App\Domain\Saude\ESF\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Saude\ESF\Models\Imunobiologico;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;

class ImunobiologicoController extends Controller
{
    public function getAll()
    {
        return new DBJsonResponse(Imunobiologico::where('psf22_ativo', true)->get());
    }
}

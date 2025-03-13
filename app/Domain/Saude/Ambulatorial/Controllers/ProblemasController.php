<?php

namespace App\Domain\Saude\Ambulatorial\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\Ambulatorial\Models\Problema;
use App\Http\Controllers\Controller;

class ProblemasController extends Controller
{
    public function getAll()
    {
        return new DBJsonResponse(
            Problema::orderBy('s169_descricao')->get(['s169_id as id', 's169_descricao as descricao'])
        );
    }
}

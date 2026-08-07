<?php

namespace App\Domain\Patrimonial\Patrimonio\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Patrimonio\Models\Bem;
use App\Domain\Patrimonial\Patrimonio\Repositories\BensRepository;
use App\Domain\Patrimonial\Patrimonio\Resources\BensResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BensController extends Controller
{
    public function buscar(Request $request, BensRepository $repository)
    {
        $bens = BensResource::toArray($repository->findByRequest($request));

        return new DBJsonResponse($bens);
    }
}

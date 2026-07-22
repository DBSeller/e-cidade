<?php

namespace App\Domain\Configuracao\Banco\Controller;

use App\Domain\Configuracao\Banco\Models\DBBancos;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Controllers\Controller;

class BancoController extends Controller
{
    public function index()
    {
        return new DBJsonResponse(DBBancos::orderBy('db90_codban')->all(), 'Bancos cadastrados');
    }
}

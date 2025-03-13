<?php


namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Support\Facades\DB;

class EncerramentoPeriodoContabilController
{
    public function ultimaData($instituicao)
    {
        $encerramento = DB::table('condataconf')
            ->where('c99_instit', '=', $instituicao)
            ->orderBy('c99_anousu', 'desc')
            ->first();

        return new DBJsonResponse($encerramento->c99_data, "Data Encerramento.");
    }
}

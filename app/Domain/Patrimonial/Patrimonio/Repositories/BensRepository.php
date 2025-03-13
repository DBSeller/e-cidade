<?php

namespace App\Domain\Patrimonial\Patrimonio\Repositories;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Patrimonio\Models\Bem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BensRepository extends BaseRepository
{
    protected $modelClass = Bem::class;

    public function findByRequest(Request $request)
    {
        $query = $this->newQuery()->select('bens.*');
        $query->leftJoin('bensbaix', 't55_codbem', 't52_bem');
        $query->where('t55_codbem', null);

        if (!empty($request->departamento)) {
            $query->where('t52_depart', $request->departamento);
        }
        if (!empty($request->divisao)) {
            $query->join('bensdiv', 't33_bem', 't52_bem');
            $query->where('t33_divisao', $request->divisao);
        }
        if (!empty($request->bemInicial) && !empty($request->bemFinal)) {
            $query->whereBetween('t52_bem', [$request->bemInicial, $request->bemFinal]);
        }
        if (!empty($request->placaInicial) && !empty($request->placaFinal)) {
            $query->whereBetween(DB::raw('cast(t52_ident as int)'), [$request->placaInicial, $request->placaFinal]);
        }
        if ($request->has('codigos')) {
            $query->whereIn('t52_bem', $request->codigos);
        }
        if ($request->has('DB_instit')) {
            $query->where('t52_instit', $request->DB_instit);
        }

        return $query->orderBy('t52_bem', 'asc')->get();
    }
}

<?php

namespace App\Domain\Tributario\Arrecadacao\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Tesouraria\Models\TipoParcelamento;

class TipoParcelamentoControleParcelamentoController extends Controller
{
    /**
     * Retorna os acompanhamentos do paciente
     * @param integer $id
     * @return DBJsonResponse
     */
    public function getByRegra($id)
    {
        $rule = [
            'id' => ['required', 'integer']
        ];
        validaRequest(['id' => $id], $rule);

        $daoCadtipoparc = new \cl_cadtipoparc;
        $where = "cadtipo.k03_tipo in (6,13,16,17) and k40_codigo = {$id}";
        $sql = $daoCadtipoparc->sql_query_parcelamento('', 'k41_arretipo', 'k41_arretipo', $where);
        $rs = $daoCadtipoparc->sql_record($sql);

        $arrayTipo = [];

        while ($row = pg_fetch_assoc($rs)) {
            $arrayTipo[] = $row['k41_arretipo'];
        }

        $tiposParcelamento = TipoParcelamento::select('k00_descr')
                    ->whereIn('k00_tipo', $arrayTipo)
                    ->orderBy('k00_tipo', 'asc')
                    ->get();

        return new DBJsonResponse($tiposParcelamento);
    }
}

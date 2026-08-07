<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentConfig;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentForm;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentPerc;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\TipoAsse;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Providers\ConcessaoConfig;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Requests\ConcessaoRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LDAP\Result;

class ConcessaoCalculoLogController extends Controller
{
    public function show(Request $request)
    {
        $data = $request->all();
        if ($data['acao'] == 'novadata') {
            $result = DB::table('concessaocalculonovadatalog')
                ->select(
                    'h12_descr',
                    'h16_dtconc',
                    'h16_dtterm',
                    DB::raw("(CASE 
                    WHEN rh502_condicao = 'inicio' THEN 'Inicio'
                    WHEN rh502_condicao = 'interrompe' THEN 'Interrompe'
                    WHEN rh502_condicao = 'final' THEN 'Final'
                    WHEN rh502_resultado = '-dias' THEN 'Antecipa'
                    WHEN rh502_operador = '-' THEN 'Antecipa'
                    ELSE 'Protela'
                    END) AS rh502_condicao")
                )
                ->join('assenta', 'h16_codigo', '=', 'rh508_codigo')
                ->join('tipoasse', 'h12_codigo', '=', 'h16_assent')
                ->join('concessaocalculo', 'rh504_sequencial', '=', 'rh508_concessaocalculo')
                ->join('assentform', function ($join) {
                    $join->on('rh504_seqassentconf', '=', 'rh502_seqassentconf')
                        ->on('h16_assent', '=', 'rh502_codigo');
                })
                ->where('rh508_concessaocalculo', $data['rh507_concessaocalculo'])
                ->get();
        } else {
            $result = DB::table('concessaocalculolog')
                ->select(
                    'h12_descr',
                    'h16_dtconc',
                    'h16_dtterm',
                    DB::raw("(CASE 
                    WHEN rh502_condicao = 'inicio' THEN 'Inicio'
                    WHEN rh502_condicao = 'interrompe' THEN 'Interrompe'
                    WHEN rh502_condicao = 'final' THEN 'Final'
                    WHEN rh502_resultado = '-dias' THEN 'Antecipa'
                    WHEN rh502_operador = '-' THEN 'Antecipa'
                    ELSE 'Protela'
                    END) AS rh502_condicao")
                )
                ->join('assenta', 'h16_codigo', '=', 'rh507_assent')
                ->join('tipoasse', 'h12_codigo', '=', 'h16_assent')
                ->join('concessaocalculo', 'rh504_sequencial', '=', 'rh507_concessaocalculo')
                ->join('assentform', function ($join) {
                    $join->on('rh504_seqassentconf', '=', 'rh502_seqassentconf')
                        ->on('h16_assent', '=', 'rh502_codigo');
                })
                ->where('rh507_concessaocalculo', $data['rh507_concessaocalculo'])
                ->get();
        }

        return new DBJsonResponse($result, 'Configuração Atualizada com Sucesso!', 200);
    }
}

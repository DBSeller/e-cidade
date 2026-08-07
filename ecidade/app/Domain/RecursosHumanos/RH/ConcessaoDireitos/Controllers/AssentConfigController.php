<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentConfig;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Providers\ConcessaoConfig;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Requests\ConcessaoRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssentConfigController extends Controller
{
    public function __construct()
    {
        $this->concessaoRequest = new ConcessaoRequest;
    }

    public function allAssentConfig(Request $request)
    {
        $dados = DB::table('recursoshumanos.assentconf')
            ->join('tipoasse as a', 'rh500_assentamento', '=', 'a.h12_codigo')
            ->join('tipoasse as b', 'rh500_condede', '=', 'b.h12_codigo')
            ->leftJoin('selecao as s', 'rh500_selecao', '=', 's.r44_selec')
            ->leftJoin('tipoasse as c', 'rh500_naoconcede', '=', 'c.h12_codigo')
            ->select(
                'assentconf.rh500_sequencial',
                'rh500_datalimite',
                DB::Raw("CONCAT(a.h12_codigo, ' - ', a.h12_descr) AS rh500_assentamento"),
                DB::Raw("CONCAT(b.h12_codigo, ' - ', b.h12_descr) AS rh500_condede"),
                DB::Raw("CONCAT(c.h12_codigo, ' - ', c.h12_descr) AS rh500_naoconcede"),
                DB::Raw("CONCAT(s.r44_selec, ' - ', s.r44_descr) AS rh500_selecao")
            )
            ->orderBy('rh500_sequencial')
            ->get();
        return new DBJsonResponse($dados);
    }

    public function gravarAssentConfig(Request $request)
    {
        $data = $request->all();
        $data['rh500_datalimite'] = substr($data['rh500_datalimite'], 6, 4) .
            '-' . substr($data['rh500_datalimite'], 3, 2) .
            "-" . substr($data['rh500_datalimite'], 0, 2);
        $rules  = $this->concessaoRequest->rulesAssentConfig();
        $errors = $this->validationRule(
            $data,
            $rules
        );
        if ($errors !== true) {
            return new DBJsonResponse([], $errors, 422);
        }

        $dados = ConcessaoConfig::gravarAssentConfig($data);

        if ($dados) {
                return new DBJsonResponse($dados);
        } else {
            return new DBJsonResponse([], $dados, 400);
        }
    }

    public function showAssentConfig(Request $request)
    {

        $data = $request->all();
        if ($data['rh500_sequencial'] != '') {
            $dados = ConcessaoConfig::show($data['rh500_sequencial']);
            return new DBJsonResponse($dados);
        } else {
            return new DBJsonResponse(null, 'Insira um Assentamento!', 400);
        }
    }

    public function deleteAssentConfig($rh500_sequencial)
    {
        return AssentConfig::where('rh500_sequencial', $rh500_sequencial)->delete();
    }
    
    private function validationRule(&$requestData, &$rule)
    {
        $validator = Validator::make($requestData, $rule);

        if ($validator->fails()) {
            return $validator->messages();
        }

        return true;
    }
}

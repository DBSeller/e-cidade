<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentConcedeConf;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentPerc;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Requests\ConcessaoRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AssentConcedeConfigController extends Controller
{
    public function __construct()
    {
        $this->concessaoRequest = new ConcessaoRequest;
    }

    public function gravarAssentConcedeConfig(Request $request)
    {
        $data = $request->all();
        $rules  = $this->concessaoRequest->AssentConcedeConf();
        $errors = $this->validationRule(
            $data,
            $rules
        );
        if ($errors !== true) {
            return new DBJsonResponse([], $errors, 422);
        }

        if ($data['acao'] == 'create') {
            try {
                $data = AssentConcedeConf::create([
                    'rh503_seqassentconf' =>  $data['rh503_seqassentconf'],
                    'rh503_acao' => $data['rh503_acao'],
                    'rh503_tipo' => $data['rh503_tipo'],
                    'rh503_condicao' => $data['rh503_condicao'],
                    'rh503_formula' => $data['rh503_formula'],
                    'rh503_codigo' => $data['rh503_codigo']
                ]);
                return new DBJsonResponse($data);
            } catch (\Throwable $th) {
                return new DBJsonResponse([], $th, 422);
            }
        } else {
            $data = AssentConcedeConf::where('rh503_sequencial', $data['rh503_sequencial'])
                ->update([
                    'rh503_acao' => $data['rh503_acao'],
                    'rh503_tipo' => $data['rh503_tipo'],
                    'rh503_condicao' => $data['rh503_condicao'],
                    'rh503_formula' => $data['rh503_formula'],
                    'rh503_codigo' => $data['rh503_codigo']
                ]);
            return new DBJsonResponse($data);
        }
    }


    public function showAssentAssentConcedeConfig(Request $request)
    {
        $data = $request->all();
        if ($data['rh503_seqassentconf'] != '') {
            $dados = DB::table('recursoshumanos.assentconcedeconf')
                ->join('tipoasse', 'rh503_codigo', '=', 'h12_codigo')
                ->select(
                    DB::Raw("CONCAT(h12_assent, ' - ', h12_descr) AS rh503_codigo"),
                    'h12_codigo',
                    'rh503_sequencial',
                    'rh503_acao',
                    'rh503_tipo',
                    'rh503_formula',
                    'rh503_condicao'
                )
                ->where('rh503_seqassentconf', $data['rh503_seqassentconf'])
                ->get();
            return new DBJsonResponse($dados);
        } else {
            return new DBJsonResponse(null, '', 400);
        }
    }

    public function deleteAssentAssentConcedeConfig(Request $request)
    {
        $data = $request->all();
        if ($data['rh503_sequencial'] != '') {
            $dados = AssentConcedeConf::where('rh503_sequencial', $data['rh503_sequencial'])
                ->delete();
            return new DBJsonResponse($dados);
        } else {
            return new DBJsonResponse(null, '', 400);
        }
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

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

class AssentFormController extends Controller
{
    public function __construct()
    {
        $this->concessaoRequest = new ConcessaoRequest;
    }

    public function gravarAssentForm(Request $request)
    {

        $data = $request->all();
        $rules  = $this->concessaoRequest->AssentForm();
        $errors = $this->validationRule(
            $data,
            $rules
        );
        if ($errors !== true) {
            return new DBJsonResponse([], $errors, 422);
        }
        if ($data['acao'] == 'alterar') {
            $result = AssentForm::where('rh502_sequencial', $data['rh502_sequencial'])
                ->update([
                    'rh502_codigo' =>  $data['rh502_codigo'],
                    'rh502_condicao' => $data['rh502_condicao'],
                    'rh502_resultado' =>  $data['rh502_resultado'],
                    'rh502_operador' =>  $data['rh502_operador'],
                    'rh502_multiplicador' => $data['rh502_multiplicador']
                ]);
        } elseif ($data['acao'] == 'create') {
            $result = AssentForm::create([
                'rh502_codigo' =>  $data['rh502_codigo'],
                'rh502_condicao' => $data['rh502_condicao'],
                'rh502_resultado' =>  $data['rh502_resultado'],
                'rh502_seqassentconf' =>  $data['rh502_seqassentconf'],
                'rh502_operador' =>  $data['rh502_operador'],
                'rh502_multiplicador' => $data['rh502_multiplicador']
            ]);
        }
        if ($result) {
            return new DBJsonResponse(null, 'Configuração Atualizada com Sucesso!', 200);
        } else {
            return new DBJsonResponse(null, 'Insira um Assentamento!', 400);
        }
    }


    public function mostrarAssentForm(Request $request)
    {
        $data = $request->all();
        if ($data['rh502_seqassentconf'] != '') {
            $dados = DB::table('assentform')
                ->join('tipoasse', 'rh502_codigo', '=', 'h12_codigo')
                ->select(
                    DB::Raw("CONCAT(h12_assent, ' - ', h12_descr) AS rh502_codigo"),
                    'h12_codigo',
                    'rh502_seqassentconf',
                    'rh502_condicao',
                    'rh502_resultado',
                    'rh502_operador',
                    'rh502_multiplicador',
                    'rh502_sequencial'
                )
                ->where('rh502_seqassentconf', $data['rh502_seqassentconf'])
                ->get();

            $s = AssentConfig::select('rh500_assentamento')
            ->where('rh500_sequencial', $data['rh502_seqassentconf'])->first();
            $assenta = TipoAsse::select('h12_codigo', DB::Raw("CONCAT(h12_assent, ' - ', h12_descr) AS h12_descr"))
                ->where('h12_codigo', '!=', $s->rh500_assentamento)
                ->orderBy('h12_codigo')->get();

            $data = [
                'tipos' => $assenta,
                'parametros' => $dados
            ];
            return new DBJsonResponse($data);
        } else {
            return new DBJsonResponse(null, 'Insira um Assentamento!', 400);
        }
    }

    public function deleteAssentForm(Request $request)
    {
        $data = $request->all();
        if ($data['rh502_seqassentconf'] != '') {
            $dados = AssentForm::where('rh502_sequencial', $data['rh502_seqassentconf'])->delete();
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

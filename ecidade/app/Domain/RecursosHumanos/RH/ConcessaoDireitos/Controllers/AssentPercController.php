<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models\AssentPerc;
use App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Requests\ConcessaoRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AssentPercController extends Controller
{
    public function __construct()
    {
        $this->concessaoRequest = new ConcessaoRequest;
    }

    public function gravarAssentPerc(Request $request)
    {
        $data = $request->all();
        $rules  = $this->concessaoRequest->rulesAssentPerc();
        $errors = $this->validationRule(
            $data,
            $rules
        );
        if ($errors !== true) {
            return new DBJsonResponse([], $errors, 422);
        }
        if ($data['acao'] == 'create') {
            try {
                $data = AssentPerc::create([
                    'rh501_seqasentconf' =>  $data['rh501_seqasentconf'],
                    'rh501_ordem' => $data['rh501_ordem'],
                    'rh501_perc' => $data['rh501_perc'],
                    'rh501_unidade' => $data['rh501_unidade'],
                    'rh501_valor' => $data['rh501_valor']
                ]);
                return new DBJsonResponse($data);
            } catch (\Throwable $th) {
                return new DBJsonResponse([], $th, 422);
            }
        } else {
            $data = AssentPerc::where('rh501_sequencial', $data['rh501_sequencial'])
                ->update([
                    'rh501_ordem' => $data['rh501_ordem'],
                    'rh501_perc' => $data['rh501_perc'],
                    'rh501_unidade' => $data['rh501_unidade'],
                    'rh501_valor' => $data['rh501_valor']
                ]);
            return new DBJsonResponse($data);
        }
    }


    public function showAssentPerc(Request $request)
    {
        $data = $request->all();
        if ($data['rh501_seqasentconf'] != '') {
            $dados = AssentPerc::where('rh501_seqasentconf', $data['rh501_seqasentconf'])
                ->orderBy('rh501_perc')->get();
            return new DBJsonResponse($dados);
        } else {
            return new DBJsonResponse(null, '', 400);
        }
    }

    public function deleteAssentPerc(Request $request)
    {
        $data = $request->all();
        if ($data['rh501_sequencial'] != '') {
            $dados = AssentPerc::where('rh501_sequencial', $data['rh501_sequencial'])
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

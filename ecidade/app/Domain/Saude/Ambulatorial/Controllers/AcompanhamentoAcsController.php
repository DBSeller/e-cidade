<?php

namespace App\Domain\Saude\Ambulatorial\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\Ambulatorial\Models\AcompanhamentoAcs;
use App\Domain\Saude\Ambulatorial\Services\AcompanhamentoAcsService;
use App\Domain\Saude\Ambulatorial\Requests\SaveAcompanhamentoAcsRequest;

class AcompanhamentoAcsController extends Controller
{
    /**
     * Salva um acompanhamento
     * @param Request $request
     * @return DBJsonResponse
     */
    public function save(SaveAcompanhamentoAcsRequest $request)
    {
        $acompanhamentoAcs = new AcompanhamentoAcs();
        if ($request->get('id')) {
            $acompanhamentoAcs = AcompanhamentoAcs::find($request->get('id'));
        }

        $acompanhamentoAcs->s168_unidade = $request->get('unidade');
        $acompanhamentoAcs->s168_profissional = $request->get('profissional');
        $acompanhamentoAcs->s168_paciente = $request->get('paciente');
        $acompanhamentoAcs->s168_data_hora = $request->get('data_hora');
        $acompanhamentoAcs->s168_evolucao = $request->get('evolucao');

        $acompanhamentoAcs->save();

        return new DBJsonResponse([], 'Acompanhamento salvo com sucesso!');
    }

    /**
     * Deleta o acompanhamento passado por request
     * @param Request $request
     * @return DBJsonResponse
     */
    public function delete(Request $request)
    {
        $rule = [
            'id' => ['required', 'integer']
        ];
        validaRequest($request->all(), $rule);

        $acompanhamentoAcs = AcompanhamentoAcs::find($request->get('id'));
        $acompanhamentoAcs->delete();

        return new DBJsonResponse([], 'Acompanhamento apagado com sucesso!');
    }

    /**
     * Retorna o acompanhamento
     * @param integer $id
     * @return DBJsonResponse
     */
    public function get($id)
    {
        $rule = [
            'id' => ['required', 'integer']
        ];
        validaRequest(['id' => $id], $rule);
        
        $acompanhamentoAcs = AcompanhamentoAcs::find($id);
 
        return new DBJsonResponse($acompanhamentoAcs);
    }

    /**
     * Retorna os acompanhamentos do paciente
     * @param integer $id
     * @return DBJsonResponse
     */
    public function getByPaciente($id)
    {
        $rule = [
            'id' => ['required', 'integer']
        ];
        validaRequest(['id' => $id], $rule);

        $acompanhamentos = AcompanhamentoAcs::where('s168_paciente', '=', $id)
            ->orderByRaw('s168_data_hora desc')
            ->get();

        foreach ($acompanhamentos as $acompanhamentoAcs) {
            $acompanhamentoAcs->profissional->cgm;
        }

        return new DBJsonResponse($acompanhamentos);
    }

    /**
     * Gera um relatorio com os ids passados pela request
     * @param Request $request
     * @return DBJsonResponse
     */
    public function relatorio(Request $request)
    {
        $rule = [
            'ids' => ['required', 'array'],
            'ids.*' => 'integer'
        ];
        $mensagem = [
            'ids.*.integer' => 'Os valores do array devem ser do tipo inteiro.'
        ];
        validaRequest($request->all(), $rule, $mensagem);

        $acompanhamentos = AcompanhamentoAcs::whereIn('s168_id', $request->get('ids'))
            ->orderByRaw('s168_data_hora desc')
            ->get();

        $service = new AcompanhamentoAcsService();
        $pdf = $service->gerarRelatorioPorPaciente($acompanhamentos);

        return new DBJsonResponse($pdf->emitir(), 'Emitindo PDF');
    }
}

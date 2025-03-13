<?php

namespace App\Domain\Tributario\Arrecadacao\Controllers;

use Illuminate\Http\Request;
use App\Domain\Tributario\Arrecadacao\Requests\TEF\SalvarAgendamentoControleParcelamentoRequest;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\Arrecadacao\Models\AgendamentoControleParcelamento;

class AgendamentoControleParcelamentoController extends Controller
{
    public function salvar(SalvarAgendamentoControleParcelamentoRequest $request)
    {
        $parcelamentoVencido = new AgendamentoControleParcelamento();
        if ($request->get('id')) {
            $parcelamentoVencido = AgendamentoControleParcelamento::find($request->get('id'));
        }

        $parcelamentoVencido->ar49_dia_semana = $request->get('sltDiaProc');
        $parcelamentoVencido->ar49_horario = $request->get('sltHorarioProc');
        $parcelamentoVencido->ar49_prazo_dias = $request->get('quantDiasRec');
        $parcelamentoVencido->ar49_margem_dias = $request->get('quantDiasTol');
        $parcelamentoVencido->ar49_parcelas_vencidas = $request->get('quantParcVenc');
        $parcelamentoVencido->ar49_acao = $request->get('sltAcao');
        $parcelamentoVencido->ar49_regra_parcelamento = $request->get('regraParcelamento');

        /* $daoCadtipoparc = new \cl_cadtipoparc;
        $where = "cadtipo.k03_tipo in (6,13,16,17) and k40_codigo = {$parcelamentoVencido->ar49_regra_parcelamento}";
        $sql = $daoCadtipoparc->sql_query_parcelamento('', 'k41_arretipo', 'k41_arretipo', $where);
        $rs = $daoCadtipoparc->sql_record($sql);

        $arrayTipo = [];

        while ($row = pg_fetch_assoc($rs)) {
            $arrayTipo[] = $row['k41_arretipo'];
        }

        $tipos = implode(', ', $arrayTipo);
        $parcelamentoVencido->ar49_tipo_parcelamento = $tipos; */

        $parcelamentoVencido->ar49_agendamento_ativo = true;

        $parcelamentoVencido->save();

        return new DBJsonResponse([], 'Agendamento salvo com sucesso!');
    }

    public function desativar(Request $request)
    {
        $rule = [
            'id' => ['required', 'integer']
        ];
        validaRequest($request->all(), $rule);

        $parcelamentoVencido = AgendamentoControleParcelamento::find($request->get('id'));
        
        $parcelamentoVencido->ar49_agendamento_ativo = false;

        $parcelamentoVencido->save();

        return new DBJsonResponse([], 'Agendamento desativado com sucesso!');
    }

    public function getAll()
    {
        $agendamentos = AgendamentoControleParcelamento::where('ar49_agendamento_ativo', true)
        ->with(['regraParcelamento', 'acao'])
        ->get();
        
        return new DBJsonResponse($agendamentos);
    }
}

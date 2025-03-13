<?php

namespace App\Domain\Patrimonial\Protocolo\Controller;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Protocolo\Model\AtividadeExecucao;
use App\Domain\Patrimonial\Protocolo\Model\TipoProcesso;
use App\Domain\Patrimonial\Protocolo\Requests\AtividadeExecucaoRequest;
use App\Domain\Patrimonial\Protocolo\Requests\AtividadesReordenarRequest;
use App\Http\Controllers\Controller;
use JSON;

class AtividadesExecucaoController extends Controller
{
    public function index()
    {
        return new DBJsonResponse(AtividadeExecucao::all());
    }

    public function tipoProcesso(TipoProcesso $tipoProcesso)
    {
        return new DBJsonResponse($tipoProcesso->atividades->toArray());
    }

    public function excluirVinculo(AtividadeExecucaoRequest $request)
    {
        $tipoProcesso = TipoProcesso::findOrFail($request->get('codigoTipoProcesso'));
        $atividadeExcluir = $request->get('codigoAtividade'); // 2
        $ordemExcluir = $request->get('ordem'); // 3

        $novosVinculos = $tipoProcesso->atividades->filter(
            function ($atividadeVinculada) use ($atividadeExcluir, $ordemExcluir) {
                return !($atividadeVinculada->p114_codigo == $atividadeExcluir &&
                    $atividadeVinculada->pivot->p115_ordem == $ordemExcluir);
            }
        );

        $ordem = 1;
        $tipoProcesso->atividades()->sync([]);
        foreach ($novosVinculos as $atividadeVincular) {
            $tipoProcesso->atividades()->attach($atividadeVincular->p114_codigo, ['p115_ordem' => $ordem]);
            $ordem++;
        }

        $tipoProcesso->refresh();
        return new DBJsonResponse($tipoProcesso->atividades->toArray(), 'Atividade desvinculada do tipo de processo.');
    }

    public function vincularAtividade(AtividadeExecucaoRequest $request)
    {
        $tipoProcesso = TipoProcesso::findOrFail($request->get('codigoTipoProcesso'));
        $ordemNova = 1;
        $ultimaAtividade = $tipoProcesso->atividades->last();
        if (!is_null($ultimaAtividade)) {
            $ordemNova = $ultimaAtividade->pivot->p115_ordem + 1;
        }
        $tipoProcesso->atividades()->attach($request->get('codigoAtividade'), ['p115_ordem' => $ordemNova]);
        $tipoProcesso->refresh();
        return new DBJsonResponse($tipoProcesso->atividades->toArray());
    }

    public function reordenarVinculos(AtividadesReordenarRequest $request)
    {
        $tipoProcesso = TipoProcesso::findOrFail($request->get('codigoTipoProcesso'));

        $atividadesOrdenar = $request->get('atividadesOrdenar');
        $atividades = JSON::create()->parse(str_replace("\\", "", $atividadesOrdenar));

        $ordem = 1;
        $tipoProcesso->atividades()->sync([]);
        foreach ($atividades as $atividadeVincular) {
            $tipoProcesso->atividades()->attach($atividadeVincular->p114_codigo, ['p115_ordem' => $ordem]);
            $ordem++;
        }
        $tipoProcesso->refresh();
        return new DBJsonResponse($tipoProcesso->atividades->toArray());
    }
}

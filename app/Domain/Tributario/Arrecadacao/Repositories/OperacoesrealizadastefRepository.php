<?php

namespace App\Domain\Tributario\Arrecadacao\Repositories;

use App\Domain\Tributario\Arrecadacao\Models\Operacoesrealizadastef;

class OperacoesrealizadastefRepository
{
    /**
     * @param $sequencial
     * @return Operacoesrealizadastef|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     */
    public function getBySequencial($sequencial)
    {
        return Operacoesrealizadastef::find($sequencial);
    }

    /**
     * @param $numnov
     * @param string[] $campos
     * @return Operacoesrealizadastef[]|
     * \Illuminate\Database\Eloquent\Builder[]|
     * \Illuminate\Database\Eloquent\Collection|
     * \Illuminate\Support\Collection
     */
    public function getAllConfirmadasAutorizadoraByNumnov($numnov, $campos = ["*"])
    {
        return Operacoesrealizadastef::numnov($numnov)->confirmadoAutorizadora()->get($campos);
    }

    /**
     * @param $numnov
     * @param $grupo
     * @param string[] $campos
     * @return Operacoesrealizadastef[]|
     * \Illuminate\Database\Eloquent\Builder[]|
     * \Illuminate\Database\Eloquent\Collection|
     * \Illuminate\Support\Collection
     */
    public function getAllDesfeitasByNumnovGrupo($numnov, $grupo, $campos = ["*"])
    {
        return Operacoesrealizadastef::numnov($numnov)->desfeito()->grupo($grupo)->orderBy("k198_nsu")->get($campos);
    }

    public function getAllPendentes($dataInicio, $dataFim, $terminal)
    {
        $query = Operacoesrealizadastef::confirmadoAutorizadora()->desfeito("f")->confirmadoAuttar("f");

        if (!empty($dataInicio) && !empty($dataFim)) {
            $query->beetwenDataOperacao($dataInicio, $dataFim);
        }

        if (!empty($terminal)) {
            $query->terminal($terminal);
        }

        return $query->get();
    }
}

<?php

namespace App\Domain\Saude\Farmacia\Resources;

use Illuminate\Database\Eloquent\Collection;
use App\Domain\Saude\Farmacia\Models\DemandaReprimida;

class DemandaReprimidaResource
{
    /**
     * @param DemandaReprimida $model
     *
     * @return \stdClass
     */
    public static function toObject(DemandaReprimida $model)
    {
        return (object)[
            'id' => $model->fa67_id,
            'dataHora' => $model->fa67_data_hora->format('d/m/Y h:i'),
            'idPaciente' => $model->fa67_paciente,
            'nomePaciente' => $model->paciente->z01_v_nome,
            'idMedicamento' => $model->fa67_medicamento,
            'descricaoMedicamento' => $model->medicamento->material->m60_descr,
            'quantidade' => $model->fa67_quantidade,
            'idUsuario' => $model->fa67_usuario,
            'loginUsuario' => $model->usuario->login,
            'nomeUsuario' => $model->usuario->nome,
            'idUnidade' => $model->fa67_unidade_saude,
            'descricaoUnidade' => $model->unidade->departamento->descrdepto,
            'observacoes' => $model->fa67_observacoes,
            'unidadeMedida' => $model->medicamento->material->unidade->m61_descr
        ];
    }

    /**
     * @param Collection $demandas
     *
     * @return array
     */
    public static function toArray(Collection $demandas)
    {
        $dados = [];

        foreach ($demandas as $demanda) {
            $dados[] = static::toObject($demanda);
        }

        return $dados;
    }
}

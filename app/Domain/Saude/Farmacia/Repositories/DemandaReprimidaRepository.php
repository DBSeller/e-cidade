<?php

namespace App\Domain\Saude\Farmacia\Repositories;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Saude\Farmacia\Models\DemandaReprimida;
use App\Domain\Saude\Farmacia\Requests\SalvarDemandaReprimidaRequest;

class DemandaReprimidaRepository extends BaseRepository
{
    protected $modelClass = DemandaReprimida::class;

    /**
     * Inseri um novo registro na tabela
     * @param SalvarDemandaReprimidaRequest $request
     *
     * @return DemandaReprimida
     */
    public function createFromRequest(SalvarDemandaReprimidaRequest $request)
    {
        $model = new DemandaReprimida;
        
        $model->fa67_data_hora = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $model->fa67_paciente = $request->paciente;
        $model->fa67_medicamento = $request->medicamento;
        $model->fa67_unidade_saude = $request->DB_coddepto;
        $model->fa67_usuario = $request->DB_id_usuario;
        $model->fa67_quantidade = $request->quantidade;
        $model->fa67_observacoes = $request->observacoes;
        
        $model->save();

        return $model;
    }

    /**
     * Atualiza um registro existente, conforme o id passado na request
     * @param SalvarDemandaReprimidaRequest $request
     *
     * @return DemandaReprimida
     */
    public function updateFromRequest(SalvarDemandaReprimidaRequest $request)
    {
        $model = DemandaReprimida::find($request->id);

        $model->fa67_quantidade = $request->quantidade;
        $model->fa67_observacoes = $request->observacoes;
        
        $model->save();

        return $model;
    }

    /**
     * @param integer $id
     *
     * @return boolean
     */
    public function delete($id)
    {
        $model = DemandaReprimida::find($id);

        return $model->delete();
    }

    /**
     * @param integer $idPaciente
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByPaciente($idPaciente)
    {
        return $this->newQuery()->where('fa67_paciente', $idPaciente)->get();
    }

    /**
     * @param string|array|\Closure $where
     * @param integer $ordem
     * @return \Illuminate\Database\Eloquent\Collection|\App\Domain\Saude\Farmacia\Models\DemandaReprimida
     */
    public function get($where = null, $ordem = 0)
    {
        $query = $this->newQuery()
            ->select('demanda_reprimida.*')
            ->join('farmacia.far_matersaude', 'fa01_i_codigo', 'fa67_medicamento')
            ->join('material.matmater', 'm60_codmater', 'fa01_i_codmater')
            ->join('ambulatorial.cgs_und', 'z01_i_cgsund', 'fa67_paciente');
        
        if ($where != null) {
            $query->where($where);
        }

        switch ($ordem) {
            case 1:
                $query->orderBy('m60_descr')->orderBy('fa67_data_hora')->orderBy('z01_v_nome');
                break;
            case 2:
                $query->orderBy('z01_v_nome')->orderBy('fa67_data_hora')->orderBy('m60_descr');
                break;
            default:
                $query->orderBy('fa67_data_hora')->orderBy('m60_descr')->orderBy('z01_v_nome');
        }

        return $query->get();
    }
}

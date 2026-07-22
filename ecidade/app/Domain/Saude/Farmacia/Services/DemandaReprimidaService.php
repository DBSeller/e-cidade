<?php

namespace App\Domain\Saude\Farmacia\Services;

use App\Domain\Saude\Farmacia\Resources\DemandaReprimidaResource;
use App\Domain\Saude\Farmacia\Relatorios\ControleDemandaReprimidaPdf;
use App\Domain\Saude\Farmacia\Requests\SalvarDemandaReprimidaRequest;
use App\Domain\Saude\Farmacia\Repositories\DemandaReprimidaRepository;
use App\Domain\Saude\Farmacia\Requests\RelatorioDemandaReprimidaRequest;
use Illuminate\Database\Eloquent\Collection;

class DemandaReprimidaService
{
    /**
     * @var DemandaReprimidaRepository
     */
    private $repository;

    public function __construct(DemandaReprimidaRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param SalvarDemandaReprimidaRequest $request
     *
     * @return \App\Domain\Saude\Farmacia\Models\DemandaReprimida
     */
    public function salvar(SalvarDemandaReprimidaRequest $request)
    {
        if ($request->has('id')) {
            return $this->repository->updateFromRequest($request);
        }

        return $this->repository->createFromRequest($request);
    }

    /**
     * @param integer $idPaciente
     *
     * @return array
     */
    public function getByPaciente($idPaciente)
    {
        $demandas = $this->repository->getByPaciente($idPaciente);

        return DemandaReprimidaResource::toArray($demandas);
    }

    /**
     * @return DemandaReprimidaRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Retorna uma collection com as demandas encontradas
     * @param \stdClass $filtros
     * @return Collection
     */
    public function buscar(\stdClass $filtros)
    {
        return $this->repository->get(function ($query) use ($filtros) {
            if (property_exists($filtros, 'pacientes')) {
                $query->whereIn('fa67_paciente', $filtros->pacientes);
            }
            if (property_exists($filtros, 'medicamentos')) {
                $query->whereIn('fa67_medicamento', $filtros->medicamentos);
            }
            if (property_exists($filtros, 'departamentos')) {
                $query->whereIn('fa67_unidade_saude', $filtros->departamentos);
            }

            $query->whereDate('fa67_data_hora', ">=", $filtros->periodoInicial)
                  ->whereDate('fa67_data_hora', "<=", $filtros->periodoFinal);
        }, $filtros->ordem);
    }

    /**
     * @return ControleDemandaReprimidaPdf
     */
    public function gerarRelatorio(Collection $demandas)
    {
        $dados = DemandaReprimidaResource::toArray($demandas);

        return new ControleDemandaReprimidaPdf($dados);
    }
}

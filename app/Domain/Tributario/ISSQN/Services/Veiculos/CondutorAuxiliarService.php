<?php
namespace App\Domain\Tributario\ISSQN\Services\Veiculos;

use App\Domain\Tributario\ISSQN\Repository\Veiculos\CondutorAuxiliarRepository;
use App\Domain\Tributario\ISSQN\Model\Veiculos\CondutorAuxiliar;

/**
* Classe CondutorAuxiliarsService
* Faz os tramites referentes a ordem de serviço
*/
class CondutorAuxiliarService
{
    /**
     * Construtor da classe
     *
     * @param CondutorAuxiliarRepository $condutorAuxiliarRepository
     */
    public function __construct(
        CondutorAuxiliarRepository $condutorAuxiliarRepository
    ) {
        $this->condutorAuxiliarRepository = $condutorAuxiliarRepository;
    }

    /**
     * Função que salva um alvara de evento
     *
     * @param stdClass $condutorAuxiliar
     */
    public function salvarCondutorAuxiliar(\stdClass $condutorAuxiliar)
    {
        $model = new CondutorAuxiliar();

        if (!empty($condutorAuxiliar->q173_sequencial)) {
            $model = $this->condutorAuxiliarRepository->find($condutorAuxiliar->q173_sequencial);
        }

        empty($condutorAuxiliar->q173_issveiculo) || $model->setVeiculo($condutorAuxiliar->q173_issveiculo);
        empty($condutorAuxiliar->q173_cgm)        || $model->setCgm($condutorAuxiliar->q173_cgm);
        empty($condutorAuxiliar->q173_datainicio) || $model->setDataInicio($condutorAuxiliar->q173_datainicio);
        empty($condutorAuxiliar->q173_datafim)    || $model->setDataFim($condutorAuxiliar->q173_datafim);

        $this->condutorAuxiliarRepository->persist($model);

        return $model;
    }
}

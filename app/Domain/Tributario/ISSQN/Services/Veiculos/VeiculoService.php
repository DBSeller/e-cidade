<?php
namespace App\Domain\Tributario\ISSQN\Services\Veiculos;

use App\Domain\Tributario\ISSQN\Repository\Veiculos\VeiculoRepository;
use App\Domain\Tributario\ISSQN\Repository\Veiculos\CondutorAuxiliarRepository;
use App\Domain\Tributario\ISSQN\Model\Veiculos\Veiculo;

/**
* Classe VeiculosService
* Faz os tramites referentes a ordem de serviço
*/
class VeiculoService
{
    /**
     * Construtor da classe
     *
     * @param VeiculoRepository $veiculoRepository
     * @param CondutorAuxiliarRepository $condutorAuxiliarRepository
     */
    public function __construct(
        VeiculoRepository $veiculoRepository,
        CondutorAuxiliarRepository $condutorAuxiliarRepository
    ) {
        $this->veiculoRepository = $veiculoRepository;
        $this->condutorAuxiliarRepository = $condutorAuxiliarRepository;
    }

    /**
     * Função que salva um alvara de evento
     *
     * @param stdClass $veiculo
     */
    public function salvarVeiculo(\stdClass $veiculo)
    {
        $model = new Veiculo();

        if (!empty($veiculo->q172_sequencial)) {
            $model = $this->veiculoRepository->find($veiculo->q172_sequencial);
        }

        empty($veiculo->q172_datacadastro)  || $model->setDataCadastro($veiculo->q172_datacadastro);
        empty($veiculo->q172_issbase)       || $model->setInscricao($veiculo->q172_issbase);
        empty($veiculo->q172_tipo)          || $model->setTipo($veiculo->q172_tipo);
        empty($veiculo->q172_marca)         || $model->setMarca($veiculo->q172_marca);
        empty($veiculo->q172_modelo)        || $model->setModelo($veiculo->q172_modelo);
        empty($veiculo->q172_cor)           || $model->setCor($veiculo->q172_cor);
        empty($veiculo->q172_procedencia)   || $model->setProcedencia($veiculo->q172_procedencia);
        empty($veiculo->q172_categoria)     || $model->setCategoria($veiculo->q172_categoria);
        empty($veiculo->q172_chassi)        || $model->setChassi($veiculo->q172_chassi);
        empty($veiculo->q172_renavam)       || $model->setRenavan($veiculo->q172_renavam);
        empty($veiculo->q172_placa)         || $model->setPlaca($veiculo->q172_placa);
        empty($veiculo->q172_potencia)      || $model->setPotencia($veiculo->q172_potencia);
        empty($veiculo->q172_capacidade)    || $model->setCapacidade($veiculo->q172_capacidade);
        empty($veiculo->q172_anofabricacao) || $model->setAnoFabricacao($veiculo->q172_anofabricacao);
        empty($veiculo->q172_anomodelo)     || $model->setAnoModelo($veiculo->q172_anomodelo);
        empty($veiculo->q172_aam)           || $model->setAam($veiculo->q172_aam);

        $this->veiculoRepository->persist($model);

        return $model;
    }

    /**
     * Função que busca um veículo
     *
     * @param integer $id
     */
    public function getVeiculo($id)
    {
        $veiculo = $this->veiculoRepository->getVeiculo($id);
        empty($veiculo) || $veiculo->setCondutores(
            $this->condutorAuxiliarRepository->findByVeiculo($id)
        );

        return $veiculo;
    }

    /**
     * Função que remove uma inscrição de veículo e seus condutores auxiliares
     *
     * @param integer $id
     */
    public function desprocessarInscricaoVeiculo($id)
    {
        $this->condutorAuxiliarRepository->deleteByInscricaoVeiculo($id);
        $this->veiculoRepository->delete($id);
    }
}

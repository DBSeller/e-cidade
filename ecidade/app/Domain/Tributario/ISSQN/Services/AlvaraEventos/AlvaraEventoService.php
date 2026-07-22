<?php
namespace App\Domain\Tributario\ISSQN\Services\AlvaraEventos;

use App\Domain\Tributario\ISSQN\Repository\AlvaraEventos\AlvaraEventoRepository;
use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\AlvaraEvento;

/**
* Classe AlvaraEventosService
* Faz os tramites referentes a ordem de serviço
*/
class AlvaraEventoService
{
    private $ordemServico;

    /**
     * Construtor da classe
     *
     * @param AlvaraEventoRepository $alvaraEventoRepository
     */
    public function __construct(
        AlvaraEventoRepository $alvaraEventoRepository
    ) {
        $this->alvaraEventoRepository = $alvaraEventoRepository;
    }

    /**
     * Função que salva um alvara de evento
     *
     * @param stdClass $alvaraEvento
     */
    public function salvarAlvaraEvento(\stdClass $alvaraEvento)
    {
        $model = new AlvaraEvento();

        if (!empty($alvaraEvento->q170_codigo)) {
            $model = $this->alvaraEventoRepository->find($alvaraEvento->q170_codigo);
        }

        empty($alvaraEvento->q170_ordemservico)    ||$model->setOrdemServico($alvaraEvento->q170_ordemservico);
        empty($alvaraEvento->q170_tipoalvara)      ||$model->setTipoAlvara($alvaraEvento->q170_tipoalvara);
        empty($alvaraEvento->q170_certidaobombeiro)||$model->setCertidaoBombeiro($alvaraEvento->q170_certidaobombeiro);
        empty($alvaraEvento->q170_dataemissao)     ||$model->setDataEmissao($alvaraEvento->q170_dataemissao);
        empty($alvaraEvento->q170_observacao)      ||$model->setObservacao($alvaraEvento->q170_observacao);

        if (!empty($alvaraEvento->q170_estimativapublico)) {
            $model->setEstimativaPublico($alvaraEvento->q170_estimativapublico);
        }

        $this->alvaraEventoRepository->persist($model);

        return $model;
    }

    /**
     * Função que busca um alvará de evento
     *
     * @param integer $id
     */
    public function getAlvaraEvento($id)
    {
        return $this->alvaraEventoRepository->getAlvaraEvento($id);
    }
}

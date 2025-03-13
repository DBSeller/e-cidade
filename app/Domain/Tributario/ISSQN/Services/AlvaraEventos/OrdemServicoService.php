<?php
namespace App\Domain\Tributario\ISSQN\Services\AlvaraEventos;

use App\Domain\Tributario\ISSQN\Repository\AlvaraEventos\OrdemServicoFiscalRepository;
use App\Domain\Tributario\ISSQN\Repository\AlvaraEventos\OrdemServicoRepository;
use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\OrdemServicoFiscal;
use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\OrdemServico;

/**
* Classe OrdemServicoService
* Faz os tramites referentes a ordem de serviço
*/
class OrdemServicoService
{
    private $ordemServico;

    /**
     * Construtor da classe
     *
     * @param ProcessoDocumentoRepository $processoDocumentoRepository
     */
    public function __construct(
        OrdemServicoRepository $ordemServicoRepository,
        OrdemServicoFiscalRepository $ordemServicoFiscalRepository
    ) {
        $this->ordemServicoRepository = $ordemServicoRepository;
        $this->ordemServicoFiscalRepository = $ordemServicoFiscalRepository;
    }

    /**
     * Função que realiza o processamento de uma ordem de servico
     *
     * @param stdClass $dadosOrdemServico
     */
    public function processarOrdemServico(\stdClass $dadosOrdemServico)
    {
        $this->salvarOrdemServico($dadosOrdemServico);
        $this->processaFiscais($dadosOrdemServico);
        return $this->ordemServico;
    }

    /**
     * Função que busca uma ordem de servico
     *
     * @param integer $id
     */
    public function getOrdemServico($id)
    {
        $ordemServico = $this->ordemServicoRepository->getOrdemServico($id);
        empty($ordemServico) || $ordemServico->setFiscais(
            $this->ordemServicoFiscalRepository->findByOrdemServico($id)
        );

        return $ordemServico;
    }

    /**
     * Função que salva uma ordem de serviço
     *
     * @param stdClass $ordemServico
     */
    public function salvarOrdemServico(\stdClass $ordemServico)
    {
        $model = new OrdemServico();

        if (!empty($ordemServico->q168_codigo)) {
            $model = $this->ordemServicoRepository->find($ordemServico->q168_codigo);
        }

        if (empty($ordemServico->q168_processo)) {
            $ordemServico->q168_processo = null;
        }

        if (empty($ordemServico->q168_dataprocessoexterno)) {
            $ordemServico->q168_dataprocessoexterno = null;
        }

        empty($ordemServico->q168_dataemissao) || $model->setDataEmissao($ordemServico->q168_dataemissao);
        empty($ordemServico->q168_cgm)         || $model->setCgm($ordemServico->q168_cgm);
        empty($ordemServico->q168_inscricao)   || $model->setInscricao($ordemServico->q168_inscricao);
        empty($ordemServico->q168_descricao)   || $model->setDescricao($ordemServico->q168_descricao);
        empty($ordemServico->q168_localizacao) || $model->setLocalizacao($ordemServico->q168_localizacao);
        empty($ordemServico->q168_datainicio)  || $model->setDataInicio($ordemServico->q168_datainicio);
        empty($ordemServico->q168_datafim)     || $model->setDataFim($ordemServico->q168_datafim);
        empty($ordemServico->q168_horainicio)  || $model->setHoraInicio($ordemServico->q168_horainicio);
        empty($ordemServico->q168_horafim)     || $model->setHoraFim($ordemServico->q168_horafim);

        $model->setProcesso($ordemServico->q168_processo);
        $model->setProcessoExterno($ordemServico->q168_processoexterno);
        $model->setTitularprocessoExterno($ordemServico->q168_titularprocessoexterno);
        $model->setDataProcessoExterno($ordemServico->q168_dataprocessoexterno);

        $this->ordemServicoRepository->persist($model);

        $this->ordemServico = $model;

        return $model;
    }

    /**
     * Função que processa os fiscais de uma ordem de servico
     *
     * @param stdClass $ordemServico
     * @param array $ordemServico->fiscais[]
     */
    public function processaFiscais($ordemServico)
    {
        //Delete todos os registros para aquela ordem fiscal
        $this->ordemServicoFiscalRepository->deleteByOrdemServico($this->ordemServico->getCodigo());

        //Percorre o array de fiscais inserindo no banco
        if (!empty($ordemServico->fiscal)) {
            foreach ($ordemServico->fiscal as $fiscal) {
                $ordemServicoFiscal = new \stdClass;
                $ordemServicoFiscal->q169_ordemservico = $this->ordemServico->getCodigo();
                $ordemServicoFiscal->q169_fiscal = $fiscal;
                $this->salvarOrdemServicoFiscal($ordemServicoFiscal);
            }
        }
    }

    /**
     * Função que realiza o processamento de uma ordem de servico
     *
     * @param stdClass $ordemServicoFiscal
     */
    public function salvarOrdemServicoFiscal($ordemServicoFiscal)
    {
        $model = new OrdemServicoFiscal();

        if (!empty($ordemServicoFiscal->q169_codigo)) {
            $model = $this->ordemServicoFiscalRepository->find($ordemServicoFiscal->q169_codigo);
        }

        empty($ordemServicoFiscal->q169_ordemservico)||$model->setOrdemServico($ordemServicoFiscal->q169_ordemservico);
        empty($ordemServicoFiscal->q169_fiscal)      ||$model->setFiscal($ordemServicoFiscal->q169_fiscal);

        $this->ordemServicoFiscalRepository->persist($model);
    }

    /**
     * Função que remove uma ordem de serviço e seus fiscais
     *
     * @param integer $id
     */
    public function desprocessarOrdemServico($id)
    {
        $this->ordemServicoFiscalRepository->deleteByOrdemServico($id);
        $this->ordemServicoRepository->delete($id);
    }
}

<?php

namespace App\Domain\Saude\ESF\Services;

use App\Domain\Saude\Ambulatorial\Repositories\ProntuarioRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * @package App\Domain\Saude\ESF\Services
 */
abstract class IndicadorDesempenhoService
{
    /**
     * Proporção de gestantes com pelo menos 6 consultas pré-natal (PN) realizadas,
     * sendo a primeira até a 16ª semana de gestação.
     */
    const UM = 1;

    /**
     * @var \DateTime
     */
    private $periodoInicio;

    /**
     * @var \DateTime
     */
    private $periodoFim;

    /**
     * @var array
     */
    private $unidades;

    /**
     * @param array $dados
     * @return \App\Domain\Saude\ESF\Contracts\IndicadorDesempenhoPdf
     */
    abstract protected function getRelatorio(array $dados);

    /**
     * @param \DateTime $periodoInicio
     * @return IndicadorDesempenhoService
     */
    final public function setPeriodoInicio(\DateTime $periodoInicio)
    {
        $this->periodoInicio = $periodoInicio;
        return $this;
    }

    /**
     * @return \DateTime
     */
    final public function getPeriodoInicio()
    {
        return $this->periodoInicio;
    }

    /**
     * @param \DateTime $periodoFim
     * @return IndicadorDesempenhoService
     */
    final public function setPeriodoFim(\DateTime $periodoFim)
    {
        $this->periodoFim = $periodoFim;
        return $this;
    }

    /**
     * @return \DateTime
     */
    final public function getPeriodoFim()
    {
        return $this->periodoFim;
    }

    /**
     * @param array $unidades
     * @return IndicadorDesempenhoService
     */
    final public function setUnidades(array $unidades)
    {
        $this->unidades = $unidades;
        return $this;
    }

    /**
     * @return array
     */
    final public function getUnidades()
    {
        return $this->unidade;
    }

    /**
     * @throws \Exception
     * @return \App\Domain\Saude\ESF\Contracts\IndicadorDesempenhoPdf
     */
    final public function gerarRelatorio()
    {
        $repository = new ProntuarioRepository;
        $dados = $repository->getAtendimentos(
            $this->periodoInicio,
            $this->periodoFim,
            $this->unidades,
            $this->getFiltros()
        );

        if ($dados->isEmpty()) {
            throw new \Exception('Não foram encontrados registros para os filtros informados.', 200);
        }

        $dados = $this->processar($dados);

        return $this->getRelatorio($dados);
    }

    /**
     * @param Collection $atendimentos
     * @return array
     */
    protected function processar(Collection $atendimentos)
    {
        $dados = [];
        foreach ($atendimentos as $atendimento) {
            $dados[] = $atendimento;
        }
        
        return $dados;
    }

    /**
     * @return null|array|string|\Closure
     */
    protected function getFiltros()
    {
        return null;
    }
}

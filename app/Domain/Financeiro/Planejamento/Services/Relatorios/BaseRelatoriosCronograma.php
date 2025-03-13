<?php

namespace App\Domain\Financeiro\Planejamento\Services\Relatorios;

use App\Domain\Financeiro\Orcamento\Services\Relatorios\BaseCronograma;
use App\Domain\Financeiro\Planejamento\Models\Comissao;
use App\Domain\Financeiro\Planejamento\Models\Planejamento;

abstract class BaseRelatoriosCronograma extends BaseCronograma
{
    /**
     * @var Planejamento
     */
    protected $planejamento;

    /**
     * @param array $filtros
     */
    public function __construct(array $filtros)
    {
        $this->processaFiltros($filtros);
    }

    protected function processaFiltros(array $filtros)
    {
        $this->getPlanejamento($filtros['planejamento_id']);
        $this->exercicio = (int)$filtros['exercicio'];
        $this->agruparPor = $filtros['agruparPor'];
        $this->periodicidade = $filtros['periodicidade'];
        $this->instituicoes = $filtros['instituicoes'];

        $this->organizaFiltrosEmissao();
        $this->inicializaTotalizadores();
    }

    /**
     * Organiza os filtros de emissão
     */
    protected function organizaFiltrosEmissao()
    {
        $this->organizaFiltrosComissao();
        $this->organizaFiltrosPlanejamento();

        $this->dados['filtros']['exercicio'] = $this->exercicio;
        $this->dados['filtros']['agruparPor'] = $this->agruparPor;
        $this->dados['filtros']['periodicidade'] = $this->periodicidade;
    }

    protected function getPlanejamento($planejamento_id)
    {
        $this->planejamento = Planejamento::find($planejamento_id);
    }

    /**
     * Organiza os filtros do planejamento
     */
    protected function organizaFiltrosPlanejamento()
    {
        $this->dados['planejamento'] = $this->planejamento->toArray();
        $this->dados['planejamento']['exercicios'] = $this->planejamento->execiciosPlanejamento();
        $this->dados['planejamento']['missao'] = $this->planejamento->pl2_missao;
        $this->dados['planejamento']['visao'] = $this->planejamento->pl2_visao;
        $this->dados['planejamento']['valores'] = $this->planejamento->pl2_valores;
    }

    /**
     * Organiza os filtros da comissão do planejamento
     */
    protected function organizaFiltrosComissao()
    {
        $cgms = $this->planejamento->comissoes->map(function (Comissao $comissao) {
            return $comissao->cgm->z01_nome;
        })->toArray();
        $this->dados['planejamento']['comissao'] = $cgms;
    }
}

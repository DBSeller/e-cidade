<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\PlanoContas;
use App\Domain\Financeiro\Contabilidade\Models\PlanoDespesa;
use App\Domain\Financeiro\Contabilidade\Models\PlanoReceita;
use App\Domain\Financeiro\Contabilidade\Relatorios\PlanoContasOrcamentarioPcaspCsv;
use Exception;

class EmissaoPadraoOrcamentarioService
{
    /**
     * @var bool
     */
    private $uniao;

    /**
     * Receita ou Despesa
     * @var string
     */
    private $origem;
    /**
     * @var integer
     */
    private $exercicio;

    /**
     * @var array
     */
    protected $dados = [];

    /**
     * @param string $tipoPlano
     * @param string $origem
     * @param integer $exercicio
     */
    public function __construct($tipoPlano, $origem, $exercicio)
    {
        $this->uniao = $tipoPlano === PlanoContas::PLANO_UNIAO;
        $this->origem = $origem;
        $this->exercicio = $exercicio;
    }


    public function emitir()
    {
        $this->filtros();
        $this->buscarDados();

        $csv = new PlanoContasOrcamentarioPcaspCsv();
        $csv->setDados($this->dados);
        return $csv->emitir();
    }

    private function buscarDados()
    {
        if ($this->origem === PlanoContas::ORIGEM_RECEITA) {
            $contas = $this->getPlanoReceita();
        } else {
            $contas = $this->getPlanoDespesa();
        }

        if ($contas->isEmpty()) {
            throw new Exception(sprintf(
                'Não foi encontrado o plano de contas Orçamentário %s para o exercício %s.',
                $this->uniao ? 'da união' : 'regional',
                $this->exercicio
            ));
        }

        $this->dados['dados'] = $contas;
    }

    private function filtros()
    {
        $this->dados['filtros'][] = sprintf('Plano de Contas Orçamentário %s', $this->uniao ? 'da união' : 'regional');
        $this->dados['filtros'][] = sprintf('Exercício %s', $this->exercicio);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function getPlanoReceita()
    {
        return PlanoReceita::query()
            ->where('uniao', $this->uniao)
            ->where('exercicio', $this->exercicio)
            ->orderBy('conta')
            ->get();
    }

    private function getPlanoDespesa()
    {
        return PlanoDespesa::query()
            ->where('uniao', $this->uniao)
            ->where('exercicio', $this->exercicio)
            ->orderBy('conta')
            ->get();
    }
}

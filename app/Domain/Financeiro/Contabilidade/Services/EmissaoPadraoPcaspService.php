<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Models\Pcasp;
use App\Domain\Financeiro\Contabilidade\Relatorios\PlanoContasPadraoPcaspCsv;
use Exception;

class EmissaoPadraoPcaspService
{

    /**
     * @var bool
     */
    private $uniao;
    /**
     * @var integer
     */
    private $exercicio;

    /**
     * @var array
     */
    protected $dados = [];

    /**
     * @param string $tipo
     * @param integer $exercicio
     */
    public function __construct($tipo, $exercicio)
    {
        $this->uniao = $tipo === 'uniao';
        $this->exercicio = $exercicio;
    }


    public function emitir()
    {
        $this->filtros();
        $this->buscarDados();

        $csv = new PlanoContasPadraoPcaspCsv();
        $csv->setDados($this->dados);
        return $csv->emitir();
    }

    private function buscarDados()
    {
        $contas = Pcasp::query()
            ->where('uniao', $this->uniao)
            ->where('exercicio', $this->exercicio)
            ->orderBy('conta')
            ->get();

        if ($contas->isEmpty()) {
            throw new Exception(sprintf(
                'Não foi encontrado o plano de contas PCASP %s para o exercício %s.',
                $this->uniao ? 'da união' : 'regional',
                $this->exercicio
            ));
        }

        $this->dados['dados'] = $contas;
    }

    private function filtros()
    {
        $this->dados['filtros'][] = sprintf('Plano de Contas PCASP %s', $this->uniao ? 'da união' : 'regional');
        $this->dados['filtros'][] = sprintf('Exercício %s', $this->exercicio);
    }
}

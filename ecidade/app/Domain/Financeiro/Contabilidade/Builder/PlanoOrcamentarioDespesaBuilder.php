<?php

namespace App\Domain\Financeiro\Contabilidade\Builder;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\PlanoContas;
use Carbon\Carbon;

class PlanoOrcamentarioDespesaBuilder extends PlanoOrcamentarioBuilder
{
    protected $deParaSintetica = [
        'S' => true,
        'A' => false,
    ];

    public function build()
    {
        $dado = [];
        $colunasMapeadas = $this->layout->colunasMapper();
        foreach ($this->layout->colunasImportar() as $indexColuna) {
            $dado[$colunasMapeadas[$indexColuna]] = $this->linha[$indexColuna];
        }

        $dado['exercicio'] = $this->exercicio;
        $dado['uniao'] = $this->plano === PlanoContas::PLANO_UNIAO;
        $dado['conta'] = str_replace('.', '', $dado['conta']);
        $dado['nome'] = str_replace("\n", '', $dado['nome']);
        if (isset($dado['funcao'])) {
            $dado['funcao'] = str_replace("\n", '', $dado['funcao']);
        }

        if (empty($dado['classe'])) {
            $dado['classe'] = substr($dado['conta'], 0, 1);
        }

        if (isset($dado['sintetica'])) {
            $dado['sintetica'] = $this->sintetica($dado['sintetica']);
        }

        $dado['desdobramento1'] = empty($dado['desdobramento1']) ? '00' : $dado['desdobramento1'];
        $dado['desdobramento2'] = empty($dado['desdobramento2']) ? '00' : $dado['desdobramento2'];
        $dado['desdobramento3'] = empty($dado['desdobramento3']) ? '00' : $dado['desdobramento3'];

        $dado['created_at'] = Carbon::now();
        $dado['updated_at'] = Carbon::now();

        return $dado;
    }
}

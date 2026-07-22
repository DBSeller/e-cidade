<?php

namespace App\Domain\Financeiro\Contabilidade\Builder;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\PlanoContas;
use Carbon\Carbon;

/**
 * Como no RS a forma de indentidicar as contas sintéticas muda, foi extendido a classe e sobrescrito o array de depara
 *
 * @class PlanoOrcamentarioDespesaRsBuilder
 */
class PlanoOrcamentarioDespesaRsBuilder extends PlanoOrcamentarioBuilder
{
    /**
     * Mapa dos possíveis valores para definir se a conta é sintética
     * - Informar como consta na planilha, mas sempre em CAPSLOCK
     * @var array
     */
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

        if (isset($dado['sintetica'])) {
            $dado['sintetica'] = $this->sintetica($dado['sintetica']);
        }
        $dado['created_at'] = Carbon::now();
        $dado['updated_at'] = Carbon::now();

        return $dado;
    }
}

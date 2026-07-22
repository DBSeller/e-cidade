<?php

namespace App\Domain\Saude\ESF\Services;

use App\Domain\Saude\Ambulatorial\Models\Unidade;
use App\Domain\Saude\ESF\Formatters\ControleVacinasFormatter;
use Illuminate\Support\Facades\DB;
use ECidade\Enum\Saude\ESF\SituacaoPacienteVacinacaoEnum;
use App\Domain\Saude\ESF\Models\FichaVacinacao;
use App\Domain\Saude\ESF\Models\Imunobiologico;
use App\Domain\Saude\ESF\Relatorios\ControleVacinasPDF;
use ECidade\Enum\Common\FaixaEtariaEnum;
use ECidade\Enum\Saude\ESF\DoseEnum;
use ECidade\Enum\Saude\ESF\EstrategiaVacinacaoEnum;

class ControleVacinasService
{
    /**
     * Prepara os dados e retorna a Classe do PDF
     * @param \stdClass $request
     *
     * @return ControleVacinasPDF
     */
    public function gerarRelatorio(\stdClass $request)
    {
        $dados = [];

        $vacinasAplicadas = $this->buscarVacinasAplicadas($request);

        if ($vacinasAplicadas->isEmpty()) {
            throw new \Exception('Nenhum registro encontrado!');
        }

        foreach ($vacinasAplicadas as $vacina) {
            $imunobiologico = Imunobiologico::where('psf22_id_esus', $vacina->psf21_imunobiologico)->first();

            if (!array_key_exists($vacina->psf20a_profissional_unidade, $dados)) {
                $unidade = Unidade::where('sd02_i_codigo', $vacina->psf20a_profissional_unidade)->first();

                $dados[$vacina->psf20a_profissional_unidade] = (object)[
                    'descricao' => "{$vacina->psf20a_profissional_unidade} - {$unidade->departamento->descrdepto}",
                    'vacinas' => []
                ];
            }

            $unidade = $dados[$vacina->psf20a_profissional_unidade];
            if (!array_key_exists($imunobiologico->psf22_id, $unidade->vacinas)) {
                $unidade->vacinas[$imunobiologico->psf22_id] = (object)[
                    'descricao' => $imunobiologico->psf22_descricao,
                    'estrategias' => []
                ];
            }

            $imuno = $unidade->vacinas[$imunobiologico->psf22_id];

            if (!array_key_exists($vacina->psf21_estrategia, $imuno->estrategias)) {
                $imuno->estrategias[$vacina->psf21_estrategia] = (object)[
                    'descricao' => (new EstrategiaVacinacaoEnum($vacina->psf21_estrategia))->name(),
                    'total' => 0,
                    'doses' => []
                ];
            }

            $imuno->estrategias[$vacina->psf21_estrategia]->doses[] = (object)[
                'descricao' => (new DoseEnum($vacina->psf21_dose))->name(),
                'quantidade' => $vacina->quantidade
            ];

            $imuno->estrategias[$vacina->psf21_estrategia]->total += $vacina->quantidade;

            $unidade->vacinas[$imunobiologico->psf22_id] = $imuno;
            $dados[$vacina->psf20a_profissional_unidade] = $unidade;
        }

        $this->ordenarEstrategia($dados);
        
        return new ControleVacinasPDF($dados, $request);
    }

    /**
     * @param \stdClass $request
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function buscarVacinasAplicadas(\stdClass $request)
    {
        $query = FichaVacinacao::query()
            ->select([
                'psf20a_profissional_unidade',
                'psf21_imunobiologico',
                'psf21_estrategia',
                'psf21_dose',
                DB::raw('COUNT(*) as quantidade')
            ])->join('plugins.psf_ficha_vacinacao_imunobiologico', 'psf21_id_ficha', '=', 'psf20_id')
            ->join('plugins.psf_ficha_vacinacao_profissional', 'psf20a_id', '=', 'psf20_id_profissional')
            ->whereBetween('psf20a_profissional_data', [$request->periodoInicial, $request->periodoFinal])
            ->groupBy(['psf20a_profissional_unidade', 'psf21_imunobiologico', 'psf21_estrategia', 'psf21_dose']);
        
        if (!empty($request->unidade)) {
            $query->where('psf20a_profissional_unidade', $request->unidade);
        }
        if (!empty($request->imunobiologico)) {
            $query->where('psf21_imunobiologico', $request->imunobiologico);
        }
        if (!empty($request->estrategia)) {
            $query->where('psf21_estrategia', $request->estrategia);
        }
        if (!empty($request->dose)) {
            $query->where('psf21_dose', $request->dose);
        }
        if (!empty($request->situacao)) {
            $situacao = new SituacaoPacienteVacinacaoEnum((int)$request->situacao);
            $query->where($situacao->column(), true);
        }
        if (!empty($request->faixaEtaria)) {
            $faixaEtariaEnum = new FaixaEtariaEnum((int)$request->faixaEtaria);
            $query->whereBetween(
                DB::raw('EXTRACT(YEAR FROM age(psf20a_profissional_data, psf20_nascimento))'),
                $faixaEtariaEnum->getFaixaEtaria()
            );
        }

        return $query->orderBy('psf21_imunobiologico')->get();
    }

    /**
     * Reordena o array, colocando a estrategia em ordem alfabética
     * @param array $dados
     *
     * @return array $dados
     */
    private function ordenarEstrategia(&$dados)
    {
        array_map(function ($unidade) {
            return array_map(function ($vacina) {
                return usort($vacina->estrategias, function ($a, $b) {
                    return strcmp($a->descricao, $b->descricao);
                });
            }, $unidade->vacinas);
        }, $dados);
    }
}

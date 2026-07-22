<?php

namespace App\Domain\Financeiro\Orcamento\Services;

use App\Domain\Financeiro\Orcamento\Models\FonteRecurso;
use App\Domain\Financeiro\Orcamento\Models\FontesSiconfi;
use App\Domain\Financeiro\Orcamento\Models\Recurso;
use ECidade\File\Csv\Dumper\Dumper;

class DeParaRecursosSiconfiService
{
    private $columnIdRecurso = 0;
    private $columnSiconf = 6;
    private $columnGestao = 7;
    private $columnTipoDetalhamento = 8;

    public function exportar($exercicio)
    {
        $listaRecursos = [
            [
                'Id Recurso',
                'Descrição',
                "FR $exercicio",
                'Complemento',
                'Exercício',
                'Classificacao',
                'Siconfi',
                'Gestão',
                'Tipo Detalhamento'
            ]
        ];

        $exercicio += 1;
        $listaRecursos = $this->buscarRecursosDePara($listaRecursos, $exercicio);

        $file = 'tmp/de-para-recursos.csv';
        $dumper = new Dumper();
        $dumper->dumpToFile($listaRecursos, $file);
        return [
            'csv' => $file,
            'csvLinkExterno' => ECIDADE_REQUEST_PATH . $file
        ];
    }

    public function importar($dados)
    {
        $file = \JSON::create()->parse(str_replace('\"', '"', $dados['file']));
        if ($file->extension !== 'csv') {
            $str = 'Deve ser enviado o mesmo arquivo expotado, atualizando apenas as informações a partir da coluna ';
            $str .= 'Siconfi.';
            throw new \Exception($str);
        }
        $dumper = new Dumper();
        $linhas = $dumper->ler($file->path);
        unset($linhas[0]); // remove o cabeçalho

        foreach ($linhas as $linha) {
            $siconfi = $linha[$this->columnSiconf];
            $codigoSiconfi = substr($siconfi, 1);
            $siconf = FontesSiconfi::find($codigoSiconfi);
            if (empty($siconf)) {
                continue;
            }

            $fonte = FonteRecurso::query()
                ->where('exercicio', '=', $dados['exercicioAtualizar'])
                ->where('orctiporec_id', '=', $linha[$this->columnIdRecurso])
                ->first();

            $fonte->classificacao()->associate($siconf->classificacao);
            $fonte->codigo_siconfi = $siconfi;
            $fonte->gestao = str_pad($linha[$this->columnGestao], 4, 0, STR_PAD_LEFT);
            $fonte->tipo_detalhamento = $linha[$this->columnTipoDetalhamento];

            if ($dados['atualizaNome'] == 1) {
                $fonte->descricao = $siconf->descricao;
            }
            $fonte->save();
        }
        return true;
    }

    /**
     * @param array $listaRecursos
     * @param integer $exercicio
     * @return array
     */
    private function buscarRecursosDePara(array $listaRecursos, $exercicio)
    {
        Recurso::query()
            ->orderBy('o15_recurso')
            ->get()
            ->each(function (Recurso $recurso) use (&$listaRecursos, $exercicio) {
                $fonteRecurso = $recurso->fonteRecurso($exercicio);
                if (is_null($fonteRecurso)) {
                    return;
                }

                $listaRecursos[] = [
                    'codigo' => $fonteRecurso->orctiporec_id,
                    'descricao' => $fonteRecurso->descricao,
                    'fonte_2021' => $recurso->o15_recurso,
                    'complemento' => $recurso->complemento->o200_sequencial,
                    'exercicio' => $fonteRecurso->exercicio,
                    'classificacao' => $fonteRecurso->classificacao->descricao,
                    'codigo_siconf' => $fonteRecurso->codigo_siconfi,
                    'codigo_gestao' => $fonteRecurso->gestao,
                    'tipo_detalhamento' => $fonteRecurso->tipo_detalhamento,
                ];
            });
        return $listaRecursos;
    }
}

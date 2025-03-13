<?php

namespace App\Domain\Financeiro\Planejamento\Relatorios;

use ECidade\File\Csv\Dumper\Dumper;

class DemonstrativoProjecaoReceitaCsv extends Dumper
{
    /**
     * @var array
     */
    private $dados = [];

    private $porRecurso = false;
    /**
     * @var array
     */
    private $exerciciosAnteriores = [];
    /**
     * @var array
     */
    private $exerciciosProjetados = [];
    /**
     * @var int
     */
    private $exercicioProjecao;

    private $titulo = 'Demonstrativo das Projeções da Receita';


    public function setDados(array $dados)
    {
        $this->dados = $dados;
        $this->exerciciosAnteriores = $this->dados['exerciciosAnteriores'];
        $this->exercicioProjecao = $this->dados['planejamento']['pl2_ano_inicial'] - 1;
        $this->exerciciosProjetados = $this->dados['planejamento']['exercicios'];
    }

    public function emitir()
    {
        $filename = sprintf('tmp/projecao-receita-%s.csv', time());
        $this->dumpToFile($this->organizaDados(), $filename);
        return [
            'csv' => $filename,
            'csvLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    public function porRecurso($porRecurso = false)
    {
        $this->porRecurso = $porRecurso;
        if ($this->porRecurso) {
            $this->titulo .= ' - por Recurso';
        }
    }

    private function organizaDados()
    {
        if ($this->porRecurso) {
            return $this->emissaoPorRecurso();
        }

        return $this->emissaoProjecaoReceita();
    }

    private function emissaoProjecaoReceita()
    {
        $dadosImprimir = [$this->titulo];
        $dadosImprimir[] = $this->cabecalho();

        foreach ($this->dados['dados'] as $receita) {
            $linha = [
                $receita->fonte,
                $receita->descricao,
                $receita->recurso,
                $receita->complemento
            ];


            foreach ($this->exerciciosAnteriores as $exercicio) {
                $linha[] = formataValorMonetario($receita->{"arrecadado_{$exercicio}"});
            }
            $linha[] = formataValorMonetario($receita->valor_base);
            foreach ($this->exerciciosProjetados as $exercicio) {
                $linha[] = formataValorMonetario($receita->{"valor_{$exercicio}"});
            }
            $dadosImprimir[] = $linha;
        }
        return $dadosImprimir;
    }

    private function emissaoPorRecurso()
    {
        $dadosImprimir = [$this->titulo];
        $dadosImprimir[] = $this->cabecalho();

        foreach ($this->dados['dados'] as $recurso) {
            $linha = [
                $recurso->recurso,
                $recurso->complemento,
                $recurso->descricao
            ];

            foreach ($this->exerciciosAnteriores as $exercicio) {
                $linha[] = formataValorMonetario($recurso->{"arrecadado_{$exercicio}"});
            }
            $linha[] = formataValorMonetario($recurso->valor_base);
            foreach ($this->exerciciosProjetados as $exercicio) {
                $linha[] = formataValorMonetario($recurso->{"valor_{$exercicio}"});
            }
            $dadosImprimir[] = $linha;
        }
        return $dadosImprimir;
    }

    public function cabecalho()
    {
        $cabecalho = [];

        if ($this->porRecurso) {
            $cabecalho[] = 'Recurso';
            $cabecalho[] = 'Complemento';
            $cabecalho[] = 'Descrição';
        } else {
            $cabecalho[] = 'Estrutural';
            $cabecalho[] = 'Descrição';
            $cabecalho[] = 'Recurso';
            $cabecalho[] = 'Complemento';
        }

        foreach ($this->exerciciosAnteriores as $exercicio) {
            $cabecalho[] = "Vlr. Arrec. $exercicio";
        }

        $cabecalho[] = "Previsão Atualizada $this->exercicioProjecao";

        foreach ($this->exerciciosProjetados as $exercicio) {
            $cabecalho[] = "Vlr. Proj. $exercicio";
        }

        return $cabecalho;
    }
}

<?php

namespace App\Domain\Financeiro\Planejamento\Relatorios;

use ECidade\File\Csv\Dumper\Dumper;

class MetasArrecadacaoCSV extends Dumper
{
    /**
     * @var array
     */
    private $dados;
    /**
     * @var bool
     */
    private $porBimestre;
    /**
     * @var bool
     */
    private $porReceita;
    /**
     * @var mixed
     */
    private $tipoAgrupador;

    public function setDados(array $dados)
    {
        $this->dados = $dados;
        $this->porBimestre = $this->dados['filtros']['periodicidade'] === 'bimestral';
        $this->porReceita = $this->dados['filtros']['agruparPor'] === 'receita';
        $this->tipoAgrupador = $this->dados['filtros']['agruparPor'];
    }

    public function emitir()
    {
        $filename = sprintf('tmp/meta-arrecadacao-%s.csv', time());
        $this->dumpToFile($this->organizarDados(), $filename);
        return [
            'csv' => $filename,
            'csvLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    private function organizarDados()
    {
        $dadosImprimir = ['METAS DE ARRECADAÇÃO DA RECEITA'];
        $dadosImprimir[] = $this->cabecalho();
        foreach ($this->dados['dados'] as $dado) {
            if ($this->porReceita) {
                $linha = [$dado->estrutural, $dado->natureza];
            } else {
                $descricao = sprintf('%s - %s', $dado->fonte_recurso, $dado->descricao_recurso);
                if ($this->tipoAgrupador === 'recurso') {
                    $descricao .= sprintf(' | %s - %s', $dado->complemento, $dado->descricao_complemento);
                }
                $linha = [$descricao];
            }

            if ($this->porBimestre) {
                $linha[] = formataValorMonetario($dado->bimestre_1);
                $linha[] = formataValorMonetario($dado->bimestre_2);
                $linha[] = formataValorMonetario($dado->bimestre_3);
                $linha[] = formataValorMonetario($dado->bimestre_4);
                $linha[] = formataValorMonetario($dado->bimestre_5);
                $linha[] = formataValorMonetario($dado->bimestre_6);
            } else {
                $linha[] = formataValorMonetario($dado->janeiro);
                $linha[] = formataValorMonetario($dado->fevereiro);
                $linha[] = formataValorMonetario($dado->marco);
                $linha[] = formataValorMonetario($dado->abril);
                $linha[] = formataValorMonetario($dado->maio);
                $linha[] = formataValorMonetario($dado->junho);
                $linha[] = formataValorMonetario($dado->julho);
                $linha[] = formataValorMonetario($dado->agosto);
                $linha[] = formataValorMonetario($dado->setembro);
                $linha[] = formataValorMonetario($dado->outubro);
                $linha[] = formataValorMonetario($dado->novembro);
                $linha[] = formataValorMonetario($dado->dezembro);
            }
            $linha[] = formataValorMonetario($dado->valor);

            $dadosImprimir[] = $linha;
        }

        $dadosImprimir[] = '';

        // totalizador
        if ($this->porBimestre) {
            if ($this->porReceita) {
                $totalizador = ['', 'Total Geral'];
            } else {
                $totalizador = ['Total Geral'];
            }

            $totalizador = array_merge($totalizador, [
                formataValorMonetario($this->dados['totalizador']->bimestre_1),
                formataValorMonetario($this->dados['totalizador']->bimestre_2),
                formataValorMonetario($this->dados['totalizador']->bimestre_3),
                formataValorMonetario($this->dados['totalizador']->bimestre_4),
                formataValorMonetario($this->dados['totalizador']->bimestre_5),
                formataValorMonetario($this->dados['totalizador']->bimestre_6),
            ]);
        } else {
            if ($this->porReceita) {
                $totalizador = ['', 'Total Geral'];
            } else {
                $totalizador = ['Total Geral'];
            }
            $totalizador = array_merge($totalizador, [
                formataValorMonetario($this->dados['totalizador']->janeiro),
                formataValorMonetario($this->dados['totalizador']->fevereiro),
                formataValorMonetario($this->dados['totalizador']->marco),
                formataValorMonetario($this->dados['totalizador']->abril),
                formataValorMonetario($this->dados['totalizador']->maio),
                formataValorMonetario($this->dados['totalizador']->junho),
                formataValorMonetario($this->dados['totalizador']->julho),
                formataValorMonetario($this->dados['totalizador']->agosto),
                formataValorMonetario($this->dados['totalizador']->setembro),
                formataValorMonetario($this->dados['totalizador']->outubro),
                formataValorMonetario($this->dados['totalizador']->novembro),
                formataValorMonetario($this->dados['totalizador']->dezembro),

            ]);
        }
        $totalizador[] = formataValorMonetario($this->dados['totalizador']->valor);

        $dadosImprimir[] = $totalizador;
        return $dadosImprimir;
    }

    private function cabecalho()
    {
        $cabecalho = [];

        if ($this->porReceita) {
            $cabecalho[] = 'Estrutural';
            $cabecalho[] = 'Descrição';
        } else {
            $cabecalho[] = 'Recurso';
        }


        if ($this->porBimestre) {
            $cabecalho[] = '1º Bimestre';
            $cabecalho[] = '2º Bimestre';
            $cabecalho[] = '3º Bimestre';
            $cabecalho[] = '4º Bimestre';
            $cabecalho[] = '5º Bimestre';
            $cabecalho[] = '6º Bimestre';
        } else {
            $cabecalho[] = 'Janeiro';
            $cabecalho[] = 'Fevereiro';
            $cabecalho[] = 'Março';
            $cabecalho[] = 'Abril';
            $cabecalho[] = 'Maio';
            $cabecalho[] = 'Junho';
            $cabecalho[] = 'Julho';
            $cabecalho[] = 'Agosto';
            $cabecalho[] = 'Setembro';
            $cabecalho[] = 'Outubro';
            $cabecalho[] = 'Novembro';
            $cabecalho[] = 'Dezembro';
        }
        $cabecalho[] = 'Meta Anual';

        return $cabecalho;
    }
}

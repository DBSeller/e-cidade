<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios;

use ECidade\File\Csv\Dumper\Dumper;

class PlanoContasOrcamentarioPcaspCsv extends Dumper
{
    protected $dados = [];

    protected $cabecalho = [
        "conta" => "Conta",
        "nome" => "Nome",
        "funcao" => "Função",
        "sintetica" => "Sintética"
    ];


    public function setDados(array $dados)
    {
        $this->dados = $dados;
    }

    public function emitir()
    {
        $this->setCsvControl(';', '"');
        $filename = sprintf('tmp/orcamentario-%s.csv', time());
        $this->dumpToFile($this->organizarDados(), $filename);
        return [
            'csv' => $filename,
            'csvLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    private function organizarDados()
    {
        $dadosImprimir = [];
        $dadosImprimir[] = $this->dados['filtros'];
        $dadosImprimir[] = $this->cabecalho;

        foreach ($this->dados['dados'] as $dado) {
            $linha = [
                $dado->conta,
                $dado->nome,
                $dado->funcao,
                $dado->sintetica ? 'Sim' : 'Não'
            ];

            $dadosImprimir[] = $linha;
        }
        return $dadosImprimir;
    }
}

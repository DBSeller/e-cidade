<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios;

use ECidade\File\Csv\Dumper\Dumper;

class PlanoContasPadraoPcaspCsv extends Dumper
{
    protected $dados = [];

    protected $cabecalho = [
        "classe" => "Classe",
        "grupo" => "Grupo",
        "subgrupo" => "Subgrupo",
        "titulo" => "Título",
        "subtitulo" => "Subtítulo",
        "item" => "Item",
        "subitem" => "Subitem",
        "desdobramento1" => "Desdobramento1",
        "desdobramento2" => "Desdobramento2",
        "desdobramento3" => "Desdobramento3",
        "informacoescomplementares" => "Informações Complementares",
        "conta" => "Conta",
        "nome" => "Nome",
        "funcao" => "Função",
        "natureza" => "Natureza Saldo",
        "sintetica" => "Sintética",
        "indicador" => "Indicador de Superávit",
    ];

    public function setDados(array $dados)
    {
        $this->dados = $dados;
    }

    public function emitir()
    {
        $this->setCsvControl(";", '"');
        $filename = sprintf('tmp/pcasp-%s.csv', time());
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
                $dado->classe,
                $dado->grupo,
                $dado->subgrupo,
                $dado->titulo,
                $dado->subtitulo,
                $dado->item,
                $dado->subitem,
                $dado->desdobramento1,
                $dado->desdobramento2,
                $dado->desdobramento3,
                $dado->informacoescomplementares,
                $dado->conta,
                $dado->nome,
                $dado->funcao,
                $dado->natureza,
                $dado->sintetica ? 'Sim' : 'Não',
                $dado->indicador
            ];

            $dadosImprimir[] = $linha;
        }
        return $dadosImprimir;
    }
}

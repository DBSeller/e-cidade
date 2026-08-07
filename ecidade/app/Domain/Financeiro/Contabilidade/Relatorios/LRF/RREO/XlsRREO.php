<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios\LRF\RREO;

use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\Xls;
use ECidade\Library\SpreadSheet\Template\Parser;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

abstract class XlsRREO extends Xls
{
    /**
     * @param string $template
     * @param string $orientacao
     * @throws \Exception
     */
    public function __construct($template, $orientacao = null)
    {
        if (is_null($orientacao)) {
            $orientacao = PageSetup::ORIENTATION_LANDSCAPE;
        }
        if (!file_exists($template)) {
            throw new \Exception("Não foi encontrado o template do {$this->nomeArquivo}.", 403);
        }
        $this->parser = new Parser();
        $this->parser->findSectionsautomatically(false);
        $this->parser->loadXLS($template);
        $this->parser->setOrientation($orientacao);
    }

    /**
     * Seta o valor do exercício anterior
     * @param $ano
     */
    public function setExercicioAnterior($ano)
    {
        $this->setVariavel('exercicio_anterior', $ano);
    }

    /**
     * Seta a string com o nome dos meses do período selecionado
     * @param $mesesPeriodo
     */
    public function setMesesPeriodo($mesesPeriodo)
    {
        $this->setVariavel('meses_periodo', $mesesPeriodo);
    }

    public function setNomePrefeito($nome)
    {
        $this->setVariavel('nome_prefeito', $nome);
    }

    public function setNomeContador($nome)
    {
        $this->setVariavel('nome_contador', $nome);
    }

    public function setNomeOrdenador($nome)
    {
        $this->setVariavel('nome_ordenador_despesa', $nome);
    }
}

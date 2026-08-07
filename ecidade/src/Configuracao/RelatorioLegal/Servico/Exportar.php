<?php

namespace ECidade\Configuracao\RelatorioLegal\Servico;

use ECidade\Configuracao\RelatorioLegal\Modelo\Coluna;
use ECidade\Configuracao\RelatorioLegal\Modelo\Periodo;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\ColunaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\InformacaoComplementarLancamentoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\PeriodoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\RelatorioPeriodoRepositorio;
use Exception;
use stdClass;

abstract class Exportar
{
    const FORMATO_JSON = 'json';
    const FORMATO_SQL = 'sql';

    /**
     * @var Relatorio
     */
    protected $relatorio;
    /**
     * @var string
     */
    protected $formatoExportacao;
    /**
     * @var bool
     */
    protected $exportarRelatorio = false;
    /**
     * @var bool
     */
    protected $exportarPeriodos = false;
    /**
     * @var bool
     */
    protected $exportarColunas = false;
    /**
     * @var Coluna[]
     */
    protected $colunas = array();
    /**
     * @var Periodo[]
     */
    protected $periodos = array();
    /**
     * @var stdClass
     */
    protected $dadosProcessados;
    /**
     * @var string
     */
    protected $arquivo;

    /**
     * Exportar constructor.
     */
    public function __construct()
    {
        $this->dadosProcessados = new stdClass();
    }

    /**
     * @param Relatorio $relatorio
     * @return Exportar
     */
    public function setRelatorio(Relatorio $relatorio)
    {
        $this->relatorio = $relatorio;
        return $this;
    }

    /**
     * @param bool $exportar
     * @return $this
     */
    public function exportarRelatorio($exportar)
    {
        $this->exportarRelatorio = $exportar;
        return $this;
    }

    /**
     * @param bool $exportar
     * @return $this
     */
    public function exportarPeriodos($exportar)
    {
        $this->exportarPeriodos = $exportar;
        return $this;
    }

    /**
     * @param bool $exportar
     * @return $this
     */
    public function exportarColunas($exportar)
    {
        $this->exportarColunas = $exportar;
        return $this;
    }

    /**
     * @param string $formato
     * @return $this
     * @throws Exception
     */
    public function formato($formato)
    {
        if (!in_array($formato, array(self::FORMATO_JSON, self::FORMATO_SQL))) {
            throw new Exception('Formato inválido para exportação.');
        }
        $this->formatoExportacao = $formato;
        return $this;
    }

    /**
     * @return string
     */
    public function getCaminhoArquivo()
    {
        return $this->arquivo;
    }

    /**
     *
     */
    abstract public function exportar();

    /**
     * @throws Exception
     */
    protected function getDados()
    {
        if ($this->exportarRelatorio) {
            $this->buscarDadosRelatorio();
        }

        if ($this->exportarColunas) {
            $this->buscarColunas();
        }

        if ($this->exportarPeriodos) {
            $this->buscarPeriodos();
        }
    }

    /**
     * @throws Exception
     */
    protected function buscarDadosRelatorio()
    {
        $informacaoComplementarLancamentoRepositorio = new InformacaoComplementarLancamentoRepositorio();
        $informacaoComplementarLancamentos = $informacaoComplementarLancamentoRepositorio->setUseJoin(true)
            ->scopeRelatorio($this->relatorio)
            ->get(array(
                'distinct orcparamseqinfocomplementarlancamento.*'
            ));

        $linhaRepositorio = new LinhaRepositorio();
        $periodoRelatorioRepositorio = new RelatorioPeriodoRepositorio();
        $periodosRelatorio = $periodoRelatorioRepositorio->scopeRelatorio($this->relatorio)->get();
        $this->relatorio->setPeriodos($periodosRelatorio);


        $linhas = $linhaRepositorio->scopeRelatorio($this->relatorio)->addOrder('o69_ordem')->get();
        $this->relatorio->setInformacoesComplementaresLancamentos($informacaoComplementarLancamentos);
        $this->relatorio->setLinhas($linhas);
    }

    /**
     * @throws Exception
     */
    protected function buscarColunas()
    {
        $this->colunas = ColunaRepositorio::colunasPorRelatorio($this->relatorio);
    }

    /**
     * @throws Exception
     */
    protected function buscarPeriodos()
    {
        $this->periodos = PeriodoRepositorio::colunasPorRelatorio($this->relatorio);
    }

    /**
     *
     */
    protected function processar()
    {
        $this->processarRelatorio();
        $this->processarPeriodos();
        $this->processaColunas();
    }

    /**
     *
     */
    private function processarRelatorio()
    {
        $this->dadosProcessados->relatorio = $this->relatorio->toArray();
    }

    /**
     *
     */
    protected function processarPeriodos()
    {
        $this->dadosProcessados->periodos = array();

        foreach ($this->periodos as $periodo) {
            $this->dadosProcessados->periodos[] = $periodo->toArray();
        }
    }

    /**
     *
     */
    protected function processaColunas()
    {
        $this->dadosProcessados->colunas = array();

        foreach ($this->colunas as $coluna) {
            $this->dadosProcessados->colunas[] = $coluna->toArray();
        }
    }
}

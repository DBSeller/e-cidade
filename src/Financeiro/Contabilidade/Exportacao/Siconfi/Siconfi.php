<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Siconfi;

use ECidade\File\Csv\Dumper\Dumper;

/**
 * Clase para a exportação da Matriz de saldo contábeis para o SICONFI
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package ECidade\Financeiro\Contabilidade\Exportacao\Siconfi
 * @example
 *  $matriz = array(
 *       array('1.1.1.1.1.06.02', '55555', 'PO', '9', 'FP', 4444, 'FR'),
 *       array('1.1.1.1.1.06.03', '55555', 'PO', '9', 'FP', 4444, 'FR'),
 *       array('1.1.2.1.1.01.04', '55555', 'PO', '9', 'FP', 4444),
 *       array('1.1.2.1.1.01.05', '55555', 'PO', '9', 'FP', 4444),
 *   );
 *
 *   $siconfi = new Siconfi();
 *   $siconfi->setCodigoSiconfi(56565);
 *   $siconfi->setCompetencia('2018-01');
 *   $siconfi->setMatriz($matriz);
 *   $nomeArquivo = $siconfi->gerarArquivo('tmp/xyz.csv');
 */
class Siconfi
{
    /**
     * Código da instituição Siconfi
     *
     * @var integer
     */
    private $siconfi;

    /**
     * Competência no formato YYYY-MM
     *
     * @var string
     */
    private $competencia;

    /**
     * Cabecalho default do arquivo
     *
     * @var array
     */
    private $cabecalho = null;


    /**
     * Array com os dados da Matriz de saldo contábeis
     *
     * @var array
     */
    private $matrizSaldo;

    protected $encerramento = false;

    /**
     * @param boolean $encerramento
     */
    public function setEncerramento($encerramento)
    {
        $this->encerramento = $encerramento;
    }

    /**
     * Sobrescreve o cabelho do csv
     *
     * @param array $cabecalho
     */
    public function setCabecalhoMatrizSaldoContabil(array $cabecalho)
    {
        $this->cabecalho = $cabecalho;
    }

    /**
     * Define o código da instituição
     *
     * @param integer $siconfi
     */
    public function setCodigoSiconfi($siconfi)
    {
        $this->siconfi = $siconfi;
    }

    /**
     * Define a competência
     *
     * @param string $competencia
     */
    public function setCompetencia($competencia)
    {
        $this->competencia = $competencia;
    }

    /**
     * Define a matriz de saldos contábeis
     *
     * @param array $matriz
     */
    public function setMatriz($matriz)
    {
        $this->matrizSaldo = $matriz;
    }

    public function gerarArquivo($arquivo = null)
    {

        $cabecalho = array_keys($this->getColunas());
        $dados = array(
            array($this->siconfi, $this->competencia),
            $cabecalho
        );
        if ($this->encerramento) {
            list($ano, $mes) = explode('-', $this->competencia);
            $dados = array(
                array($this->siconfi, "{$ano}-13"),
                $cabecalho
            );
        }

        $dados = array_merge($dados, $this->matrizSaldo);
        if (empty($arquivo)) {
            $arquivo = 'tmp/SICONFI_matriz_de_saldos_contabeis_' . date('Y-m-d_Hi', time()) . '.csv';
        }

        $cvs = new Dumper();
        $cvs->setCsvControl(';');
        $cvs->dumpToFile($dados, $arquivo);

        return $arquivo;
    }

    /**
     * Retorna o cabecalho dos dados conforme o ano da emissao da Matriz.
     * @return array
     */
    public function getColunas()
    {
        if (!empty($this->cabecalho)) {
            return $this->cabecalho;
        }

        $cabecalho = array(
            2018 => ['fim' => 4],
            2019 => ['fim' => 6],
            2020 => ['fim' => 7],
            2021 => ['fim' => 7],
            2022 => ['fim' => 6],
        );

        list($ano,$mes) = explode('-', $this->competencia);
        if (empty($cabecalho[$ano])) {
            // apenas no php 7
            // $ano = array_key_last($cabecalho);
            $ano = array_reverse(array_keys($cabecalho))[0];
        }

        $valorFinal = $cabecalho[$ano]['fim'];
        $this->cabecalho = ['CONTA' => ''];
        for ($i = 1; $i <= $valorFinal; $i++) {
            $this->cabecalho["IC{$i}"] = '';
            $this->cabecalho["TIPO{$i}"] = '';
        }
        $this->cabecalho["Valor"] =  '';
        $this->cabecalho["Tipo_valor"] =  '';
        $this->cabecalho["Natureza_valor"] =  '';
        return $this->cabecalho;
    }
}

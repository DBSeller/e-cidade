<?php

namespace ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Formatter;

use ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao;

/**
 * Class Cvs
 * @package ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Formatter
 */
class Csv implements ConsultaInterface
{

    /**
     * lista de dados
     * @var array
     */
    protected $dados = array();

    /**
     * Linhas do csv
     * @var array
     */
    protected $linhas = array();

    /**
     * Colunas da consulta
     * @var array
     */
    protected $colunas = array();

    protected $separador = ";";

    protected $totalizadores = array();

    /**
     * @var Visao
     */
    protected $visao;

    /**
     * @var bool
     */
    private $agruparPorDocumento = false;

    /**
     * Define dos dados para serem formatados
     * @param $dados
     */
    public function setDados($dados)
    {
        $this->dados = $dados;
    }

    public function setColunas(array $colunas)
    {
        $this->colunas = $colunas;
    }

    /**
     *
     * @param $agruparPorDocumento
     */
    public function setAgruparPorDocumento($agruparPorDocumento)
    {
        $this->agruparPorDocumento = $agruparPorDocumento;
    }

    /**
     * Formata os dados e retorna o nome do arquivo gerado
     */
    public function formatar()
    {

        $nomeArquivo = "tmp/consulta_conta_corrente.csv";

        file_put_contents($nomeArquivo, "");
        $this->escreverCabecalho($nomeArquivo);
        if (!empty($this->visao)) {
            $this->formatarVisao($nomeArquivo);
            return $nomeArquivo;
        }
        $totalSaldoAnterior = 0;
        $totalDebito = 0;
        $totalCredito = 0;
        $totalSaldoFinal = 0;
        foreach ($this->dados as $dados) {

            $linha = array($this->encodeCsvString($dados->estrutural), $this->encodeCsvString($dados->nome_conta));

            if ($this->agruparPorDocumento) {
                $linha[] = $dados->documento;
            }
            $atributos = $dados->lista_atributos;
            foreach ($atributos as $sigla => $atributo) {
                if (in_array($sigla, $this->colunas)) {
                    $linha[] = $this->encodeCsvString($atributo);
                }
            }
            $linha[] = number_format(round(abs($dados->saldo_anterior), 2), 2, ',', ".");;
            $linha[] = $dados->natureza_anterior;
            $linha[] = number_format(round($dados->valor_debito, 2), 2, ',', ".");
            $linha[] = number_format(round($dados->valor_credito, 2), 2, ',', ".");
            $linha[] = number_format(round($dados->saldo_final, 2), 2, ',', ".");
            $linha[] = $dados->natureza_saldo_final;
            $this->linhas[] = $linha;
            $totalSaldoAnterior += ($dados->natureza_anterior == 'D' ? $dados->saldo_anterior * -1 : $dados->saldo_anterior);
            $totalDebito += $dados->valor_debito;
            $totalDebito += $dados->valor_credito;
            $totalSaldoFinal += ($dados->natureza_saldo_final == 'D' ? $dados->saldo_final * -1 : $dados->saldo_final);
            file_put_contents($nomeArquivo, implode($this->separador, $linha)."\n", FILE_APPEND);
        }

        $this->totalizadores[] = number_format(round(abs($totalSaldoAnterior), 2), 2, ',', ".");
        $this->totalizadores[] = $totalSaldoAnterior < 0 ? 'D' : 'C';
        $this->totalizadores[] = number_format(round(abs($totalDebito), 2), 2, ',', ".");
        $this->totalizadores[] = number_format(round(abs($totalCredito), 2), 2, ',', ".");
        $this->totalizadores[] = number_format(round(abs($totalSaldoFinal), 2), 2, ',', ".");
        $this->totalizadores[] = $totalSaldoFinal < 0 ? 'D' : 'C';
        file_put_contents($nomeArquivo, implode($this->separador, $this->totalizadores)."\n", FILE_APPEND);
        return $nomeArquivo;

    }

    /**
     * @param  $arquivo
     */
    protected function escreverCabecalho($arquivo)
    {


        if (!empty($this->visao)) {
            $this->escreverCabecalhoVisao($arquivo);
            return;
        }
        $cabecalho = array();
        $cabecalho[] = "Estrutural";
        $cabecalho[] = "Conta";
        $this->totalizadores[] = $this->encodeCsvString('Total:');
        $this->totalizadores[] = '';
        if ($this->agruparPorDocumento) {
            $cabecalho[] = "Documento";
            $this->totalizadores[] = '';
        }
        $cabecalho = array_merge($cabecalho, $this->colunas);
        foreach ($this->colunas as $colunas) {
            $this->totalizadores[] = '';
        }

        $cabecalho[] = "Saldo Anterior";
        $cabecalho[] = "Natureza Saldo Anterior";
        $cabecalho[] = "Debito";
        $cabecalho[] = "Credito";
        $cabecalho[] = "Saldo Final";
        $cabecalho[] = "Natureza Saldo Final";
        file_put_contents($arquivo, implode($this->separador, $cabecalho)."\n", FILE_APPEND);
    }

    public function setVisao(Visao $visao)
    {
        $this->visao = $visao;
    }

    /**
     * escreve os dados da visao conforme o arquivo
     * @param  $arquivo
     */
    protected function escreverCabecalhoVisao( $arquivo)
    {
        $dadosCabecalho = $this->visao->getFiltros();

        $cabecalho = array();
        if ($dadosCabecalho->configuracaoGrid->estrutural->visible) {
            $cabecalho[] = $dadosCabecalho->configuracaoGrid->estrutural->label;
            $this->totalizadores[] = 'Total:';
        }
        if ($dadosCabecalho->configuracaoGrid->descricao->visible) {
            $cabecalho[] = $dadosCabecalho->configuracaoGrid->descricao->label;
            $this->totalizadores[] = ' ';
        }


        if ($this->agruparPorDocumento) {
            $cabecalho[] = "Documento";
            $this->totalizadores[] = ' ';
        }
        $cabecalho = array_merge($cabecalho, $this->colunas);
        foreach ($this->colunas as $colunas) {
            $this->totalizadores[] = ' ';
        }

        if ($dadosCabecalho->configuracaoGrid->saldo_anterior->visible) {
            $cabecalho[] = $dadosCabecalho->configuracaoGrid->saldo_anterior->label;
            $cabecalho[] = "Natureza Saldo Anterior";
        }
        if ($dadosCabecalho->configuracaoGrid->debitos->visible) {
            $cabecalho[] = $dadosCabecalho->configuracaoGrid->debitos->label;
        }
        if ($dadosCabecalho->configuracaoGrid->creditos->visible) {
            $cabecalho[] = $dadosCabecalho->configuracaoGrid->creditos->label;
        }
        if ($dadosCabecalho->configuracaoGrid->saldo_final->visible) {
            $cabecalho[] = $dadosCabecalho->configuracaoGrid->saldo_final->label;
            $cabecalho[] = "Natureza Saldo Final";
        }
        file_put_contents($arquivo, implode($this->separador, $cabecalho)."\n", FILE_APPEND);

    }


    /**
     * Formata o CVS conforme a visao
     * @param string $arquivo
     */
    protected function formatarVisao($arquivo)
    {

        $totalSaldoAnterior = 0;
        $totalDebito = 0;
        $totalCredito = 0;
        $totalSaldoFinal = 0;
        $dadosCabecalho = $this->visao->getFiltros()->configuracaoGrid;
        foreach ($this->dados as $dados) {

            $linha = array();
            if ($dadosCabecalho->estrutural->visible) {
                $linha[] = $this->encodeCsvString($dados->estrutural);
            }
            if ($dadosCabecalho->descricao->visible) {
                $linha[] =  $this->encodeCsvString($dados->nome_conta);
            }

            if ($this->agruparPorDocumento) {
                $linha[] =  $this->encodeCsvString($dados->documento);
            }
            $atributos = $dados->lista_atributos;
            foreach ($atributos as $sigla => $atributo) {
                if (in_array($sigla, $this->colunas)) {
                    $linha[] =  $this->encodeCsvString($atributo);
                }
            }

            if ($dadosCabecalho->saldo_anterior->visible) {
                $linha[] = number_format(round(abs($dados->saldo_anterior), 2), 2, ',', ".");;
                $linha[] = $dados->natureza_anterior;
            }
            if ($dadosCabecalho->debitos->visible) {
                $linha[] = number_format(round($dados->valor_debito, 2), 2, ',', ".");
            }
            if ($dadosCabecalho->creditos->visible) {
                $linha[] = number_format(round($dados->valor_credito, 2), 2, ',', ".");
            }
            if ($dadosCabecalho->saldo_final->visible) {
                $linha[] = number_format(round($dados->saldo_final, 2), 2, ',', ".");
                $linha[] = $dados->natureza_saldo_final;
            }
            $this->linhas[] = $linha;
            $totalSaldoAnterior += ($dados->natureza_anterior == 'D' ? $dados->saldo_anterior * -1 : $dados->saldo_anterior);
            $totalDebito += $dados->valor_debito;
            $totalDebito += $dados->valor_credito;
            $totalSaldoFinal += ($dados->natureza_saldo_final == 'D' ? $dados->saldo_final * -1 : $dados->saldo_final);
            file_put_contents($arquivo, implode($this->separador, $linha)."\n", FILE_APPEND);
        }

        if ($dadosCabecalho->saldo_anterior->visible) {
            $this->totalizadores[] = number_format(round(abs($totalSaldoAnterior), 2), 2, ',', ".");
            $this->totalizadores[] = $totalSaldoAnterior < 0 ? 'D' : 'C';
        }
        if ($dadosCabecalho->debitos->visible) {
            $this->totalizadores[] = number_format(round(abs($totalDebito), 2), 2, ',', ".");
        }
        if ($dadosCabecalho->creditos->visible) {
            $this->totalizadores[] = number_format(round(abs($totalCredito), 2), 2, ',', ".");
        }
        if ($dadosCabecalho->saldo_final->visible) {
            $this->totalizadores[] = number_format(round(abs($totalSaldoFinal), 2), 2, ',', ".");
            $this->totalizadores[] = $totalSaldoFinal < 0 ? 'D' : 'C';
        }
        file_put_contents($arquivo, implode($this->separador, $this->totalizadores)."\n", FILE_APPEND);
    }



    function encodeCsvString($value) {
        $value = str_replace('\\"','"',$value);
        $value = str_replace('"','\"',$value);
        return '"'.$value.'"';
    }




}

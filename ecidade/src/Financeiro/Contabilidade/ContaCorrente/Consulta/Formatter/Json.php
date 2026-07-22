<?php


namespace ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Formatter;

use ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao;

/**
 * Class Cvs
 *
 * @package ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Formatter
 */
class Json implements ConsultaInterface
{

    /**
     * lista de dados
     *
     * @var array
     */
    protected $dados = array();

    /**
     * Colunas da consulta
     *
     * @var array
     */
    protected $colunas = array();


    /**
     * @var Visao
     */
    protected $visao;

    /**
     * Agrupar por documento
     *
     * @var bool
     */
    private $agruparPorDocumento = false;

    /**
     * Define dos dados para serem formatados
     *
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


        $mostrarSigla = true;
        $stringConcatenarAtributos = ", ";
        if (!empty($this->visao)) {
            $mostrarSigla = $this->visao->getFiltros()->mostrarSiglaAtributos;

        }
        if (!$mostrarSigla) {
            $stringConcatenarAtributos = "";
        }
        $retorno = new \stdClass();
        $retorno->colunas = $this->getCabecalho();

        $registros = array();
        foreach ($this->dados as $dados) {

            if (empty($registros[$dados->estrutural])) {

                $conta = new \stdClass();
                $conta->estrutural = db_formatar($dados->estrutural, 'sistema');
                $conta->nome_conta = $dados->nome_conta;
                $conta->reduzido = $dados->reduzido;
                $conta->movimentacoes = array();
                $registros[$dados->estrutural] = $conta;
            }
            $conta = $registros[$dados->estrutural];
            $movimentacao = new \stdClass();
            if ($this->agruparPorDocumento) {
                $movimentacao->documento = $dados->documento;
            }
            $atributos = $dados->lista_atributos;
            $listaDeAtributosParaMostrar = array();
            foreach ($atributos as $sigla => $atributo) {
                if (in_array($sigla, $this->colunas)) {
                    $stringSigla = $mostrarSigla ? "{$sigla}: {$atributo}" : $atributo;
                    $listaDeAtributosParaMostrar[] = $stringSigla;
                }
            }
            $movimentacao->codigos_lancamentos = $dados->codigos_lancamentos;
            $movimentacao->conta_corrente = implode($stringConcatenarAtributos, $listaDeAtributosParaMostrar);
            $movimentacao->saldo_anterior = round((float)$dados->saldo_anterior, 2);
            $movimentacao->natureza_anterior = $dados->natureza_anterior;
            $movimentacao->valor_credito = round((float)$dados->valor_credito,2 );
            $movimentacao->valor_debito = round((float)$dados->valor_debito, 2);
            $movimentacao->saldo_final = round((float)$dados->saldo_final, 2);
            $movimentacao->natureza_final = $dados->natureza_saldo_final;
            $conta->movimentacoes[] = $movimentacao;
        }
        sort($registros);
        $retorno->registros = $registros;

        return $retorno;

    }

    /**
     * Retorna a estrutura dos campos
     */
    protected function getCabecalho()
    {

        $campos = array(
            (object)array("nome" => "estrutural", "label" => "estrutural"),
            (object)array("nome" => "nome_conta", "label" => "Conta"),
        );
        if ($this->agruparPorDocumento) {
            $campos[] = (object)array("nome" => "documento", "label" => "Documento");
        }
        $camposValores = array(
            (object)array("nome" => "saldo_anterior", "label" => "Saldo Anterior"),
            (object)array("nome" => "valor_credito", "label" => "Crédito"),
            (object)array("nome" => "valor_debito", "label" => "Débito"),
            (object)array("nome" => "valor_debito", "label" => "Saldo Final"),
        );

        return array_merge($campos, $camposValores);
    }

    public function setVisao(Visao $visao)
    {

        $this->visao = $visao;
    }


}

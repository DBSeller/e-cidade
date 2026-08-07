<?php


namespace ECidade\Financeiro\Contabilidade\Relatorio\Razao\PorConta;

class ExcelLinha
{

    private $lancamento;
    private $sequencial;
    private $data;
    private $receita;
    private $dotacao;
    private $empenho;
    private $suplementacao;
    private $documento;
    private $planilha;
    private $slip;
    private $op;
    private $contaOrigem;
    private $contraPartida;
    private $valor;
    private $tipo;
    private $historico;
    private $instituicao;
    /**
     * @return mixed
     */
    public function getLancamento()
    {
        return $this->lancamento;
    }

    /**
     * @param mixed $lancamento
     */
    public function setLancamento($lancamento)
    {
        $this->lancamento = $lancamento;
    }

    /**
     * @return mixed
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param mixed $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getReceita()
    {
        return $this->receita;
    }

    /**
     * @param mixed $receita
     */
    public function setReceita($receita)
    {
        $this->receita = $receita;
    }

    /**
     * @return mixed
     */
    public function getDotacao()
    {
        return $this->dotacao;
    }

    /**
     * @param mixed $dotacao
     */
    public function setDotacao($dotacao)
    {
        $this->dotacao = $dotacao;
    }

    /**
     * @return mixed
     */
    public function getEmpenho()
    {
        return $this->empenho;
    }

    /**
     * @param mixed $empenho
     */
    public function setEmpenho($empenho)
    {
        $this->empenho = $empenho;
    }

    /**
     * @return mixed
     */
    public function getSuplementacao()
    {
        return $this->suplementacao;
    }

    /**
     * @param mixed $suplementacao
     */
    public function setSuplementacao($suplementacao)
    {
        $this->suplementacao = $suplementacao;
    }

    /**
     * @return mixed
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param mixed $documento
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;
    }

    /**
     * @return mixed
     */
    public function getPlanilha()
    {
        return $this->planilha;
    }

    /**
     * @param mixed $planilha
     */
    public function setPlanilha($planilha)
    {
        $this->planilha = $planilha;
    }

    /**
     * @return mixed
     */
    public function getSlip()
    {
        return $this->slip;
    }

    /**
     * @param mixed $slip
     */
    public function setSlip($slip)
    {
        $this->slip = $slip;
    }

    /**
     * @return mixed
     */
    public function getOp()
    {
        return $this->op;
    }

    /**
     * @param mixed $op
     */
    public function setOp($op)
    {
        $this->op = $op;
    }


    /**
     * @return mixed
     */
    public function getContaOrigem()
    {
        return $this->contaOrigem;
    }

    /**
     * @param mixed $contaOrigem
     */
    public function setContaOrigem($contaOrigem)
    {
        $this->contaOrigem = $contaOrigem;
    }

    /**
     * @return mixed
     */
    public function getContraPartida()
    {
        return $this->contraPartida;
    }

    /**
     * @param mixed $contraPartida
     */
    public function setContraPartida($contraPartida)
    {
        $this->contraPartida = $contraPartida;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getHistorico()
    {
        return $this->historico;
    }

    /**
     * @param mixed $historico
     */
    public function setHistorico($historico)
    {
        $this->historico = $historico;
    }

    /**
     * @return mixed
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param mixed $instituicao
     *
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }
}

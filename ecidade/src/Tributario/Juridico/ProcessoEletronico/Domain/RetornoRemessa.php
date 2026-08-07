<?php


namespace ECidade\Tributario\Juridico\ProcessoEletronico\Domain;

/**
 * Class RetornoRemessa
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Domain
 */
class RetornoRemessa
{
    /**
     * @var  $status
     */
    private $status;

    /**
     * @var  $status
     */
    private $dataOperacao;

    /**
     * @var  $recibo
     */
    private $recibo;

    /**
     * @var  $mensagem
     */
    private $mensagem;

    /**
     * @var $orgao
     */
    private $orgao;

    /**
     * @var $protocoloRecebimento
     */
    private $protocoloRecebimento;

    /**
     * @var $parametros
     */
    private $parametros;

    /**
     * @var $parametros
     */
    private $cartorio;

    /**
     * @var $numero_processo
     */
    private $numero_processo;

    /**
     * @return mixed
     */
    public function getNumeroProcesso()
    {
        return $this->numero_processo;
    }

    /**
     * @param mixed $numero_processo
     */
    public function setNumeroProcesso($numero_processo)
    {
        $this->numero_processo = $numero_processo;
    }



    /**
     * @return mixed
     */
    public function getCartorio()
    {
        return $this->cartorio;
    }

    /**
     * @param mixed $cartorio
     */
    public function setCartorio($cartorio)
    {
        $this->cartorio = $cartorio;
    }




    /**
     * @return mixed
     */
    public function getParametros()
    {
        return $this->parametros;
    }

    /**
     * @param mixed $parametros
     */
    public function setParametros($parametros)
    {
        $this->parametros = $parametros;
    }


    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getDataOperacao()
    {
        return $this->dataOperacao;
    }

    /**
     * @param mixed $dataOperacao
     */
    public function setDataOperacao($dataOperacao)
    {
        $this->dataOperacao = $dataOperacao;
    }

    /**
     * @return mixed
     */
    public function getRecibo()
    {
        return $this->recibo;
    }

    /**
     * @param mixed $recibo
     */
    public function setRecibo($recibo)
    {
        $this->recibo = $recibo;
    }

    /**
     * @return mixed
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * @param mixed $mensagem
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
    }

    /**
     * @return mixed
     */
    public function getOrgao()
    {
        return $this->orgao;
    }

    /**
     * @param mixed $orgao
     */
    public function setOrgao($orgao)
    {
        $this->orgao = $orgao;
    }

    /**
     * @return mixed
     */
    public function getProtocoloRecebimento()
    {
        return $this->protocoloRecebimento;
    }

    /**
     * @param mixed $protocoloRecebimento
     */
    public function setProtocoloRecebimento($protocoloRecebimento)
    {
        $this->protocoloRecebimento = $protocoloRecebimento;
    }
}
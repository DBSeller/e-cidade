<?php

namespace ECidade\Tributario\Juridico\ProcessoEletronico;


use ECidade\Tributario\Juridico\Inicial\Inicial;

class ProcessoEletronico
{
    const PROCESSO_GERADO = 2;

    const PROCESSO_CRIADO = 1;


    /**
     * @var integer
     */
    protected $codigo;

    /**
     * @var Inicial
     */
    protected $inicial;

    /**
     * base64 do recibo do processo eletronico
     * @var string
     */
    protected $recibo;

    /**
     * @var array
     */
    protected $movimentacoes = array();

    /**
     * @var integer
     */
    protected $situacao;

    /**
     * @var Documento
     */
    private $documento;

    /**
     * @var \CgmFisico|\CgmJuridico
     */
    private $parte;


    /**
     * @var \DateTime
     */
    private $dataCalculo;

    /**
     * ProcessoEletronico constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return Inicial
     */
    public function getInicial()
    {
        return $this->inicial;
    }

    /**
     * @param mixed $inicial
     */
    public function setInicial($inicial)
    {
        $this->inicial = $inicial;
    }

    /**
     * @return string
     */
    public function getRecibo()
    {
        return $this->recibo;
    }

    /**
     * @param string $recibo
     */
    public function setRecibo($recibo)
    {
        $this->recibo = $recibo;
    }

    /**
     * @return array
     */
    public function getMovimentacoes()
    {
        return $this->movimentacoes;
    }

    /**
     * Adiciona uma lista de movimentacoes
     * @param array $movimentacoes
     */
    public function adicionarMovimentacoes($movimentacoes)
    {
        foreach ($movimentacoes as $movimentacao) {
            $this->movimentacoes[] = $movimentacao;

        }
    }

    /**
     * adiciona uma movimentação ao processo.
     * @param $movimentacao
     */
    public function adicionarMovimentacao($movimentacao)
    {
        $this->movimentacoes[] = $movimentacao;
    }

    /**
     * @return mixed
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * @param mixed $situacao
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
    }

    public function adicionarDocumento(Documento $documento)
    {
        $this->documento[] = $documento;
    }

    /**
     * @return \CgmFisico|\CgmJuridico
     */
    public function getParte()
    {
        return $this->parte;
    }

    /**
     * @param \CgmFisico|\CgmJuridico $parte
     */
    public function setParte($parte)
    {
        $this->parte = $parte;
    }

    /**
     * @return \DateTime
     */
    public function getDataCalculo()
    {
        return $this->dataCalculo;
    }

    /**
     * @param \DateTime $dataCalculo
     */
    public function setDataCalculo($dataCalculo)
    {
        $this->dataCalculo = $dataCalculo;
    }



}

<?php

namespace ECidade\Financeiro\Orcamento\Dotacao\Model;

class LinhaDePacto
{

    /**
     * Código Sequencial
     * @var integer
     */
    protected $codigo;

    /**
     * Código da Linha de Pacto
     * @var integer
     */
    protected $codigoLinha;

    /**
     * @var integer
     */
    protected $codigoPlano;

    /**
     * @var string
     */
    protected $descricao;

    /**
     * @var float
     */
    protected $valor;

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
     * @return int
     */
    public function getCodigoLinha()
    {
        return $this->codigoLinha;
    }

    /**
     * @param int $codigoLinha
     */
    public function setCodigoLinha($codigoLinha)
    {
        $this->codigoLinha = $codigoLinha;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @param $codigoPlano
     */
    public function setCodigoPlano($codigoPlano)
    {
        $this->codigoPlano = $codigoPlano;
    }

    /**
     * @return int
     */
    public function getCodigoPlano()
    {
        return $this->codigoPlano;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

}
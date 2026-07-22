<?php

namespace ECidade\Financeiro\Orcamento\Dotacao\Model;

/**
 * Class PlanoOrcamentario
 * @package ECidade\Financeiro\Orcamento\Dotacao\Model
 */
class PlanoOrcamentario
{
    /**
     * @var integer
     */
    protected $codigo;

    /**
     * @var string
     */
    protected $titulo;

    /**
     * @var float
     */
    protected $valor;

    /**
     * @var \Dotacao
     */
    protected $dotacao;

    /**
     * @var LinhaDePacto[]
     */
    protected $linhasPacto = array();

    /**
     * @return LinhaDePacto[]
     */
    public function getLinhasPacto()
    {
        return $this->linhasPacto;
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
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
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
     * @param \Dotacao $dotacao
     */
    public function setDotacao(\Dotacao $dotacao)
    {
        $this->dotacao = $dotacao;
    }

    /**
     * @return \Dotacao
     */
    public function getDotacao()
    {
        return $this->dotacao;
    }

    public function adicionarLinha(LinhaDePacto $linhaDePacto)
    {
        $this->linhasPacto[$linhaDePacto->getCodigo()] = $linhaDePacto;
    }
}
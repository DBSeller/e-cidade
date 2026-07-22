<?php


namespace ECidade\Configuracao\RelatorioLegal\Modelo;

abstract class Configuracao
{
    /**
     * @var int
     */
    protected $sequencial;

    /**
     * @var Relatorio
     */
    protected $relatorio;

    /**
     * @var Linha
     */
    protected $linha;

    /**
     * @var integer
     */
    protected $ano;

    /**
     * @var string
     */
    protected $filtro;

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sequencial' => $this->getSequencial(),
            'relatorio' => $this->getRelatorio()->getSequencial(),
            'linha' => $this->getLinha()->getLinha(),
            'ordemLinha' => $this->getLinha()->getOrdem(),
            'ano' => $this->getAno(),
            'filtro' => $this->getFiltro(),
        );
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return (int)$this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return ConfiguracaoPadrao
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = (int)$sequencial;
        return $this;
    }

    /**
     * @return Relatorio
     */
    public function getRelatorio()
    {
        return $this->relatorio;
    }

    /**
     * @param Relatorio $relatorio
     * @return ConfiguracaoPadrao
     */
    public function setRelatorio(Relatorio $relatorio)
    {
        $this->relatorio = $relatorio;
        return $this;
    }

    /**
     * @return Linha
     */
    public function getLinha()
    {
        return $this->linha;
    }

    /**
     * @param Linha $linha
     * @return ConfiguracaoPadrao
     */
    public function setLinha(Linha $linha)
    {
        $this->linha = $linha;
        return $this;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return (int)$this->ano;
    }

    /**
     * @param int $ano
     * @return ConfiguracaoPadrao
     */
    public function setAno($ano)
    {
        $this->ano = (int)$ano;
        return $this;
    }

    /**
     * @return string
     */
    public function getFiltro()
    {
        return (string)$this->filtro;
    }

    /**
     * @param string $filtro
     * @return ConfiguracaoPadrao
     */
    public function setFiltro($filtro)
    {
        $this->filtro = (string)$filtro;
        return $this;
    }
}

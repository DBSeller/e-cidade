<?php

namespace ECidade\Tributario\Arrecadacao\Model;

class TaxasLancadasDinamicosValor
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $codcam;

    /**
     * @var string
     */
    private $conteudo;

    /**
     * @var integer
     */
    private $numnov;

    /**
     * Get the value of sequencial
     *
     * @return  integer
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * Set the value of sequencial
     *
     * @param  integer  $sequencial
     *
     * @return  self
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;

        return $this;
    }

    /**
     * Get the value of codcam
     *
     * @return  integer
     */
    public function getCodcam()
    {
        return $this->codcam;
    }

    /**
     * Set the value of codcam
     *
     * @param  integer  $codcam
     *
     * @return  self
     */
    public function setCodcam($codcam)
    {
        $this->codcam = $codcam;

        return $this;
    }

    /**
     * Get the value of conteudo
     *
     * @return  string
     */
    public function getConteudo()
    {
        return $this->conteudo;
    }

    /**
     * Set the value of conteudo
     *
     * @param  string  $conteudo
     *
     * @return  self
     */
    public function setConteudo(string $conteudo)
    {
        $this->conteudo = $conteudo;

        return $this;
    }

    /**
     * Get the value of numnov
     *
     * @return  integer
     */
    public function getNumnov()
    {
        return $this->numnov;
    }

    /**
     * Set the value of numnov
     *
     * @param  integer  $numnov
     *
     * @return  self
     */
    public function setNumnov($numnov)
    {
        $this->numnov = $numnov;

        return $this;
    }
}

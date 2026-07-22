<?php

namespace App\Domain\Patrimonial\Patrimonio\Builders;

use Proner\PhpPimaco\Tag;

class EtiquetaModelo02Builder implements \App\Domain\Patrimonial\Patrimonio\Contracts\EtiquetaBuilder
{

    /**
     * @var Tag
     */
    private $tag;
    /**
     * @var string
     */
    private $instituicao;
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var integer
     */
    private $placa;
    /**
     * @var string
     */
    private $descricao;

    public function create()
    {
        $tag = new Tag();
        $tag->setPadding(2);
        $tag->p($this->instituicao)->br();
        $tag->p('')->br();
        $descricao = substr($this->descricao, 0, 30);
        $tag->p($descricao)->br();
        $tag->p("BEM: {$this->codigo}  / PLACA: {$this->placa}");
        $this->tag = $tag;
    }

    /**
     * @param $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @param $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @param $placa
     */
    public function setPlaca($placa)
    {
        $this->placa = $placa;
    }

    /**
     * @param $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @param $placa
     */
    public function setBarcode($placa)
    {
        $this->barcode = $placa;
    }

    /**
     * @return Tag
     */
    public function getEtiqueta()
    {
        return $this->tag;
    }
}

<?php

namespace App\Domain\Patrimonial\Patrimonio\Contracts;

use Proner\PhpPimaco\Tag;

interface EtiquetaBuilder
{

    public function create();

    public function setInstituicao($instituicao);

    public function setCodigo($codigo);

    public function setPlaca($placa);

    public function setDescricao($descricao);

    public function setBarcode($barcode);

    /**
     * @return Tag
     */
    public function getEtiqueta();
}

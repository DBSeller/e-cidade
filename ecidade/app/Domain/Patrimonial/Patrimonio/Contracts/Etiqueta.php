<?php

namespace App\Domain\Patrimonial\Patrimonio\Contracts;

interface Etiqueta
{
    /**
     * @param $dados
     * @param $template
     * @param null $path_template
     * @return mixed
     */
    public function __construct($dados, $template, $path_template = null);

    /**
     * @return mixed
     */
    public function gerar();
}

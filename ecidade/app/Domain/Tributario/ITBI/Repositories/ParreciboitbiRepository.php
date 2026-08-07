<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Parreciboitbi;

class ParreciboitbiRepository
{
    /**
     * @var Parreciboitbi
     */
    private $parreciboitbi;

    public function __construct()
    {
        $this->parreciboitbi = new Parreciboitbi();
    }

    /**
     * Retorna os dados de parâmetros do ITBI
     * @return Parreciboitbi|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function get()
    {
        $oParreciboitbi = $this->parreciboitbi->first();

        if (empty($oParreciboitbi)) {
            throw new \Exception("Erro ao buscar os dados na tabela parreciboitbi.");
        }

        return $oParreciboitbi;
    }
}

<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Paritbi;

class ParitbiRepository
{
    /**
     * @var Paritbi
     */
    private $paritbi;

    public function __construct()
    {
        $this->paritbi = new Paritbi();
    }

    public function getByAnousu($anousu)
    {
        $oParitbi = $this->paritbi->where(
            "it24_anousu",
            "=",
            $anousu
        )->first();

        if (empty($oParitbi)) {
            throw new \Exception("Erro ao bsucar os parâmetros do ITBI.");
        }

        return $oParitbi;
    }
}

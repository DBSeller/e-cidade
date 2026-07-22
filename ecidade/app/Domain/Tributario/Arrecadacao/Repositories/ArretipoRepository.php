<?php

namespace App\Domain\Tributario\Arrecadacao\Repositories;

use App\Domain\Tributario\Arrecadacao\Models\Arretipo;

class ArretipoRepository
{
    /**
     * @var Arretipo
     */
    private $arretipo;

    public function __construct()
    {
        $this->arretipo = new Arretipo();
    }

    /**
     * Retorna os dados com base no código da tabela cadtipo
     * @param $tipo
     * @param string[] $campos
     * @param int $instituicao
     * @return Arretipo|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null
     */
    public function getByCadTipo($tipo, $campos = ["*"], $instituicao = 1)
    {
        return $this->arretipo->where(
            "k03_tipo",
            "=",
            $tipo
        )->where(
            "k00_instit",
            "=",
            $instituicao
        )->select($campos)->first();
    }
}

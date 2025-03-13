<?php

namespace App\Domain\Tributario\Arrecadacao\Repositories;

use App\Domain\Tributario\Arrecadacao\Models\Arrebanco;
use Illuminate\Support\Facades\DB;

class ArrebancoRepository
{
    /**
     * @var Arrebanco
     */
    private $arrebanco;

    public function __construct()
    {
        $this->arrebanco = new Arrebanco();
    }

    /**
     * Busca os dados com base no nosso número
     * @param $nossoNumero
     * @return Arrebanco|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function getByNossoNumero($nossoNumero)
    {
        $oArrebanco = $this->arrebanco->where(
            "k00_numbco",
            "=",
            $nossoNumero
        )->first();

        if (empty($oArrebanco)) {
            throw new \Exception("Erro ao buscar os dados na tabela arrebanco.");
        }

        return $oArrebanco;
    }

    public function getItbiMigradoByNbant($nbant, $erro = true)
    {
        $oArrebanco = $this->arrebanco->join(
            "recibopaga",
            "recibopaga.k00_numnov",
            "=",
            "arrebanco.k00_numpre"
        )->join(
            "itbinumpre",
            "itbinumpre.it15_numpre",
            "=",
            "recibopaga.k00_numpre"
        )->join(
            "arrepaga",
            function ($join) {
                $join->on("arrepaga.k00_numpre", "=", "recibopaga.k00_numpre");
                $join->on("arrepaga.k00_numpar", "=", "recibopaga.k00_numpar");
            }
        )->where(
            "arrebanco.k00_nbant",
            "=",
            $nbant
        )->groupBy([
            "it15_guia",
            "recibopaga.k00_numnov",
            "arrepaga.k00_dtpaga",
            "arrebanco.k00_nbant"
        ])->distinct()->first([
            "it15_guia",
            "recibopaga.k00_numnov",
            "arrepaga.k00_dtpaga",
            "arrebanco.k00_nbant"
        ]);

        if (empty($oArrebanco) and $erro) {
            throw new \Exception("Guia de ITBI migrada não ancontrada.");
        }

        return $oArrebanco;
    }
}

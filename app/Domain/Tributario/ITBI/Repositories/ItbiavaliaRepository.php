<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbiavalia;

final class ItbiavaliaRepository
{
    /**
     * @var Itbiavalia
     */
    private $itbiavalia;

    public function __construct()
    {
        $this->itbiavalia = new Itbiavalia();
    }

    /**
     * Busca os dados com base no número da guia
     * @param $guia
     * @return Itbiavalia|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function getByGuia($guia)
    {
        $oItbiavalia = $this->itbiavalia->where(
            "it14_guia",
            "=",
            $guia
        )->first();

        if (empty($oItbiavalia)) {
            throw new \Exception("Erro ao buscar os valores avaliados do ITBI.");
        }

        return $oItbiavalia;
    }

    /**
     * Busca todos os dados necessários referente a avaliação do ITBI
     * @param $guia
     * @param string[] $campos
     * @return \stdClass[]
     * @throws \Exception
     */
    public function getAllDadosByGuia($guia, $campos = ["*"])
    {
        $cl_itbiavalia = new \cl_itbiavalia;

        $campos = implode(",", $campos);

        $rItbiavalia = $cl_itbiavalia->sql_record(
            $cl_itbiavalia->sql_query_pag(
                $guia,
                $campos,
                "it28_sequencial"
            )
        );

        if (!$rItbiavalia) {
            throw new \Exception("Erro ao buscar todos os dados referente a avaliação do ITBI.");
        }

        return \db_utils::getCollectionByRecord($rItbiavalia);
    }
}

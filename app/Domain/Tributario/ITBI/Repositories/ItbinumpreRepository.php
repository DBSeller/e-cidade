<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbinumpre;

final class ItbinumpreRepository extends \BaseClassRepository
{
    /**
     * @var Itbinumpre
     */
    private $itbinumpre;

    public function __construct()
    {
        $this->itbinumpre = new Itbinumpre();
    }

    /**
     * Busca os dados com base no numpre
     * @param $numpre
     * @return Itbinumpre|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function getByNumpre($numpre)
    {
        $oItbiNumpre = $this->itbinumpre->where(
            "it15_numpre",
            "=",
            $numpre
        )->first();

        if (empty($oItbiNumpre)) {
            throw new \Exception("Erro ao buscar os dados na tabela itbinumpre.");
        }

        return $oItbiNumpre;
    }

    /**
     * Busca os dados de pagamento da guia com base no número desta e a data de pagamento
     * @param $guia
     * @param $dataPagamento
     * @param string $tabelaRecibo
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function getPagamentosGuia($guia, $dataPagamento = null, $tabelaRecibo = "recibo")
    {
        $oQuery = $this->itbinumpre->join(
            "$tabelaRecibo AS recibo",
            "recibo.k00_numpre",
            "=",
            "itbinumpre.it15_numpre"
        )->join(
            "tabrec",
            "k02_codigo",
            "=",
            "recibo.k00_receit"
        )->join("arrepaga", function ($join) {
            $join->on("arrepaga.k00_numpre", "=", "recibo.k00_numpre");
            $join->on("arrepaga.k00_numpar", "=", "recibo.k00_numpar");
            $join->on("arrepaga.k00_receit", "=", "recibo.k00_receit");
        })->leftJoin("disbanco", function ($join) {
            $join->on("disbanco.k00_numpre", "=", "recibo.k00_numpre");
            $join->on("disbanco.k00_numpar", "=", "recibo.k00_numpar");
        })->where(
            "itbinumpre.it15_guia",
            "=",
            $guia
        );

        if (!empty($dataPagamento)) {
            $oQuery->whereRaw(\DB::raw(
                "(CASE WHEN disbanco.dtpago IS NOT NULL
                            THEN disbanco.dtpago = '{$dataPagamento}'
                            ELSE arrepaga.k00_dtpaga = '{$dataPagamento}'
                            END)"
            ));
        }

        return $oQuery->get([
            "itbinumpre.it15_numpre",
            "recibo.k00_receit",
            "tabrec.k02_drecei",
            "recibo.k00_valor",
            "recibo.k00_numpar",
            "arrepaga.k00_dtpaga",
            "disbanco.dtpago"
        ]);
    }

    /**
     * Atualiza todos os ultimos numpres a não ser mais a ultima guia
     * @param $guia
     */
    public function atualizaUltimaGuia($guia)
    {
        $this->itbinumpre->where(
            "itbinumpre.it15_guia",
            "=",
            $guia
        )->update([
            "it15_ultimaguia" => "f"
        ]);
    }

    /**
     * Retorna todos os dados da tabela com base no número da guia
     * @param $guia
     * @return \Illuminate\Support\Collection
     */
    public function getByGuia($guia)
    {
        return $this->itbinumpre->where(
            "it15_guia",
            "=",
            $guia
        )->get();
    }

    public function salvar(Itbinumpre $entity)
    {
        $clitbinumpre = new \cl_itbinumpre;

        $clitbinumpre->it15_guia = $entity->getGuia();
        $clitbinumpre->it15_numpre = $entity->getNumpre();
        $clitbinumpre->it15_sequencial = $entity->getSequencial();
        $clitbinumpre->it15_ultimaguia = $entity->getUltimaguia();

        if (!empty($clitbinumpre->it15_sequencial)) {
            $clitbinumpre->alterar($clitbinumpre->it15_sequencial);
        } else {
            $clitbinumpre->incluir(null);
        }

        if ($clitbinumpre->erro_status == "0") {
            throw new \Exception($clitbinumpre->erro_msg);
        }
    }
}

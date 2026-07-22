<?php

/**
 * Class cl_sliprecursocontas
 * @property integer k181_sequencial
 * @property integer k181_slip
 * @property integer k181_recursocredito
 * @property integer k181_recursodebito
 */
class cl_sliprecursocontas extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('caixa.sliprecursocontas');
    }

    /**
     * @param null $sequencial
     * @param string $campos
     * @param null $ordem
     * @param null $where
     * @return string
     */
    public function sql_query($sequencial = null, $campos = "*", $ordem = null, $where = null)
    {

        $sql  = " select {$campos} ";
        $sql .= "   from sliprecursocontas ";
        $sql .= "        join slip on slip.k17_codigo = sliprecursocontas.k181_slip ";
        $sql .= "        join orctiporec recursodebito on recursodebito.o15_codigo = sliprecursocontas.k181_recursodebito ";
        $sql .= "        join orctiporec recursocredito on recursocredito.o15_codigo = sliprecursocontas.k181_recursocredito ";

        if (!empty($sequencial)) {
            $sql .= " where k181_sequencial = {$sequencial} ";
        } else {

            if (!empty($where)) {
                $sql .= " where {$where} ";
            }
            if (!empty($ordem)) {
                $sql .= " order by {$ordem} ";
            }
        }
        return $sql;

    }

    public function sql_query_plano($sequencial = null, $campos = "*", $ordem = null, $where = null)
    {

        $sql = " select {$campos} ";
        $sql .= "   from slip";
        $sql .= "        left join sliprecursocontas on slip.k17_codigo = sliprecursocontas.k181_slip ";
        $sql .= "        left join orctiporec recursodebito on recursodebito.o15_codigo = sliprecursocontas.k181_recursodebito ";
        $sql .= "        left join orctiporec recursocredito on recursocredito.o15_codigo = sliprecursocontas.k181_recursocredito ";
        $sql .= "        join conplanoreduz reduzdebito on k17_debito =  reduzdebito.c61_reduz and reduzdebito.c61_anousu  = ".db_getsession("DB_anousu");
        $sql .= "        join conplanoreduz reduzcredito on k17_credito =  reduzcredito.c61_reduz and reduzcredito.c61_anousu  = ".db_getsession("DB_anousu");

        if (!empty($sequencial)) {
            $sql .= " where k181_sequencial = {$sequencial} ";
        } else {

            if (!empty($where)) {
                $sql .= " where {$where} ";
            }
            if (!empty($ordem)) {
                $sql .= " order by {$ordem} ";
            }
        }
        return $sql;
    }
}
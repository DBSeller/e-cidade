<?php

/**
 * Class cl_origemcomplementorecurso
 * @property integer o206_sequencial
 * @property integer o206_origem
 * @property integer o206_numero
 * @property integer o206_recurso
 * @property integer o206_complementorecurso
 */
class cl_origemcomplementorecurso extends DAOBasica
{
    public function __construct()
    {
        parent::__construct('orcamento.origemcomplementorecurso');
    }

    public function sql_query_complemento($campos = "*", $where = null)
    {
        $sql  = " select {$campos} ";
        $sql .= "   from origemcomplementorecurso ";
        $sql .= "        inner join complementofonterecurso on o200_sequencial = o206_complementorecurso";
        $sql .= "        inner join orctiporec on o15_codigo = o206_recurso ";

        if (!empty($where)) {
            $sql .= " where {$where} ";
        }
        return $sql;
    }
}
